import React, { useRef, useState } from 'react';
import {
  View,
  Text,
  TouchableOpacity,
  StyleSheet,
  Modal,
  Platform,
  StatusBar,
} from 'react-native';
import { WebView } from 'react-native-webview';

/* ─────────────────────────────────────────────────────────────
   HTML injecté dans le WebView plein-écran
   • passive:false sur tous les touch listeners → zéro scroll parasite
   • quadraticCurveTo + requestAnimationFrame → tracé ultra-fluide
   • Épaisseur dynamique basée sur la vitesse du doigt (effet stylo)
───────────────────────────────────────────────────────────── */
const SIGNATURE_HTML = `
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<style>
  *, *::before, *::after {
    box-sizing: border-box; margin: 0; padding: 0;
    -webkit-user-select: none; user-select: none;
    -webkit-tap-highlight-color: transparent;
    -webkit-touch-callout: none;
    touch-action: none;
  }
  html, body {
    width: 100%; height: 100%; overflow: hidden;
    background: #ffffff;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  }
  canvas {
    position: fixed; top: 0; left: 0;
    width: 100%; height: 100%;
    touch-action: none;
    cursor: crosshair;
  }
  #guide {
    position: fixed; bottom: 90px; left: 50%;
    transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    pointer-events: none; transition: opacity 0.4s;
  }
  #guide-line {
    width: 220px; height: 1.5px; background: #1a7a3c30;
  }
  #guide-text {
    font-size: 12px; font-weight: 600; color: #1a7a3c80; letter-spacing: 0.5px;
  }
  #guide.hidden { opacity: 0; }
</style>
</head>
<body>
<canvas id="c"></canvas>
<div id="guide">
  <div id="guide-line"></div>
  <div id="guide-text">Signez au-dessus de la ligne</div>
</div>

<script>
(function(){
  const canvas  = document.getElementById('c');
  const ctx     = canvas.getContext('2d');
  const guide   = document.getElementById('guide');
  const dpr     = window.devicePixelRatio || 1;

  let drawing      = false;
  let hasSigned    = false;
  let strokes      = [];
  let currentStroke = [];
  let rafId        = null;

  /* ── Initialisation du canvas ── */
  function resize() {
    const w = window.innerWidth;
    const h = window.innerHeight;
    canvas.width  = w * dpr;
    canvas.height = h * dpr;
    canvas.style.width  = w + 'px';
    canvas.style.height = h + 'px';
    ctx.scale(dpr, dpr);
    ctx.lineCap  = 'round';
    ctx.lineJoin = 'round';
    redrawAll();
  }

  /* ── Extraction des coordonnées ── */
  function getPos(e) {
    const src  = e.changedTouches ? e.changedTouches[0] : e;
    const rect = canvas.getBoundingClientRect();
    return {
      x: (src.clientX - rect.left),
      y: (src.clientY - rect.top),
      t: Date.now()
    };
  }

  /* ── Épaisseur dynamique (effet plume) ── */
  function dynamicWidth(p1, p2, base) {
    if (!p1) return base;
    const dx = p2.x - p1.x;
    const dy = p2.y - p1.y;
    const dt = Math.max(p2.t - p1.t, 1);
    const v  = Math.sqrt(dx * dx + dy * dy) / dt;
    return Math.max(1.2, Math.min(base, base - v * 0.9));
  }

  /* ── Rendu d'un tableau de points en courbes quadratiques ── */
  function drawStroke(pts) {
    if (!pts || pts.length < 2) return;
    ctx.beginPath();
    ctx.strokeStyle = '#0d1f10';
    ctx.moveTo(pts[0].x, pts[0].y);

    for (let i = 1; i < pts.length - 1; i++) {
      ctx.lineWidth = dynamicWidth(pts[i - 1], pts[i], 2.8);
      const mx = (pts[i].x + pts[i + 1].x) / 2;
      const my = (pts[i].y + pts[i + 1].y) / 2;
      ctx.quadraticCurveTo(pts[i].x, pts[i].y, mx, my);
    }

    const last = pts[pts.length - 1];
    ctx.lineTo(last.x, last.y);
    ctx.stroke();
  }

  /* ── Redessine tous les traits ── */
  function redrawAll() {
    ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
    strokes.forEach(s => drawStroke(s));
    if (currentStroke.length > 1) drawStroke(currentStroke);
  }

  /* ── Début du tracé ── */
  function onStart(e) {
    e.preventDefault();
    drawing  = true;
    hasSigned = true;
    const pos = getPos(e);
    currentStroke = [pos];
    guide.classList.add('hidden');

    // Dot immédiat pour réactivité perçue
    ctx.beginPath();
    ctx.arc(pos.x, pos.y, 1.4, 0, Math.PI * 2);
    ctx.fillStyle = '#0d1f10';
    ctx.fill();

    if (rafId) cancelAnimationFrame(rafId);
  }

  /* ── Mouvement avec requestAnimationFrame ── */
  function onMove(e) {
    if (!drawing) return;
    e.preventDefault();
    currentStroke.push(getPos(e));
    if (rafId) cancelAnimationFrame(rafId);
    rafId = requestAnimationFrame(redrawAll);
  }

  /* ── Fin du tracé ── */
  function onEnd(e) {
    if (!drawing) return;
    drawing = false;
    if (currentStroke.length > 0) {
      strokes.push([...currentStroke]);
    }
    currentStroke = [];
    if (rafId) cancelAnimationFrame(rafId);
    redrawAll();
    if (window.ReactNativeWebView) {
      window.ReactNativeWebView.postMessage('SIGNED');
    }
  }

  /* ── Listeners : passive:false = zéro vol par le scroll parent ── */
  canvas.addEventListener('touchstart',  onStart, { passive: false });
  canvas.addEventListener('touchmove',   onMove,  { passive: false });
  canvas.addEventListener('touchend',    onEnd,   { passive: false });
  canvas.addEventListener('touchcancel', onEnd,   { passive: false });
  canvas.addEventListener('mousedown', onStart);
  canvas.addEventListener('mousemove', onMove);
  window.addEventListener('mouseup',   onEnd);

  /* ── API exposée depuis React Native ── */
  window.clearSignature = function() {
    strokes = []; currentStroke = []; hasSigned = false;
    ctx.clearRect(0, 0, canvas.width / dpr, canvas.height / dpr);
    guide.classList.remove('hidden');
    if (window.ReactNativeWebView) window.ReactNativeWebView.postMessage('CLEAR');
  };

  window.saveSignature = function() {
    if (!hasSigned || strokes.length === 0) return;
    const data = canvas.toDataURL('image/png', 1.0);
    if (window.ReactNativeWebView) window.ReactNativeWebView.postMessage(data);
  };

  window.addEventListener('resize', resize);
  resize();
})();
</script>
</body>
</html>
`;

/* ─────────────────────────────────────────────────────────────
   Composant SignaturePad
   Architecture : bouton inline → Modal plein écran (isolé du ScrollView)
───────────────────────────────────────────────────────────── */
export default function SignaturePad({ onSave }) {
  const webviewRef = useRef(null);
  const [modalOpen, setModalOpen] = useState(false);
  const [signed, setSigned]       = useState(false);
  const [savedData, setSavedData] = useState(null);

  const handleMessage = (event) => {
    const data = event.nativeEvent.data;
    if (data === 'CLEAR') {
      setSigned(false);
      setSavedData(null);
      onSave && onSave(null);
    } else if (data === 'SIGNED') {
      setSigned(true);
    } else if (data.startsWith('data:image')) {
      setSavedData(data);
      onSave && onSave(data);
      setModalOpen(false);
    }
  };

  const handleValidate = () => {
    webviewRef.current?.injectJavaScript('window.saveSignature(); true;');
  };

  const handleClear = () => {
    webviewRef.current?.injectJavaScript('window.clearSignature(); true;');
    setSigned(false);
  };

  return (
    <>
      {/* ── Bouton inline dans le ScrollView ── */}
      <TouchableOpacity
        activeOpacity={0.82}
        onPress={() => setModalOpen(true)}
        style={[styles.trigger, savedData && styles.triggerSigned]}
      >
        {savedData ? (
          <View style={styles.triggerSignedContent}>
            <View style={styles.checkBadge}>
              <Text style={styles.checkIcon}>✓</Text>
            </View>
            <View>
              <Text style={styles.triggerSignedTitle}>Signature enregistrée</Text>
              <Text style={styles.triggerSignedSub}>Appuyez pour modifier</Text>
            </View>
          </View>
        ) : (
          <View style={styles.triggerEmpty}>
            <Text style={styles.triggerEmoji}>✍️</Text>
            <Text style={styles.triggerTitle}>Appuyez pour signer</Text>
            <Text style={styles.triggerSub}>Zone de signature sécurisée</Text>
          </View>
        )}
      </TouchableOpacity>

      {/* ── Modal plein écran isolé du ScrollView ── */}
      <Modal
        visible={modalOpen}
        animationType="slide"
        transparent={false}
        statusBarTranslucent
        onRequestClose={() => setModalOpen(false)}
      >
        <StatusBar barStyle="dark-content" backgroundColor="#ffffff" />
        <View style={styles.modalRoot}>

          {/* Header */}
          <View style={styles.modalHeader}>
            <TouchableOpacity
              onPress={() => setModalOpen(false)}
              style={styles.closeBtn}
              activeOpacity={0.7}
            >
              <Text style={styles.closeIcon}>✕</Text>
            </TouchableOpacity>
            <View style={styles.headerCenter}>
              <Text style={styles.modalTitle}>Signature client</Text>
              <Text style={styles.modalSub}>Tracez avec votre doigt</Text>
            </View>
            <TouchableOpacity onPress={handleClear} style={styles.clearBtn} activeOpacity={0.7}>
              <Text style={styles.clearText}>Effacer</Text>
            </TouchableOpacity>
          </View>

          {/* Canvas WebView – tout l'espace, aucun conflit */}
          <View style={styles.canvasContainer}>
            <WebView
              ref={webviewRef}
              source={{ html: SIGNATURE_HTML }}
              style={styles.webview}
              onMessage={handleMessage}
              javaScriptEnabled
              domStorageEnabled
              scrollEnabled={false}
              bounces={false}
              overScrollMode="never"
              showsVerticalScrollIndicator={false}
              showsHorizontalScrollIndicator={false}
              scalesPageToFit={false}
              textZoom={100}
              androidHardwareAccelerationDisabled={false}
              {...(Platform.OS === 'android' ? { mixedContentMode: 'always' } : {})}
            />
          </View>

          {/* Footer */}
          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={[styles.validateBtn, !signed && styles.validateBtnDisabled]}
              onPress={handleValidate}
              disabled={!signed}
              activeOpacity={0.85}
            >
              <Text style={styles.validateIcon}>✓</Text>
              <Text style={styles.validateText}>Valider la signature</Text>
            </TouchableOpacity>
            {!signed && (
              <Text style={styles.footerHint}>Commencez à signer pour activer</Text>
            )}
          </View>
        </View>
      </Modal>
    </>
  );
}

const styles = StyleSheet.create({
  /* Trigger */
  trigger: {
    borderRadius: 14,
    borderWidth: 1.5,
    borderStyle: 'dashed',
    borderColor: '#b8d4c0',
    backgroundColor: '#f8faf8',
    minHeight: 90,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 16,
  },
  triggerSigned: {
    borderStyle: 'solid',
    borderColor: '#1a7a3c',
    backgroundColor: '#f0faf4',
  },
  triggerEmpty: { alignItems: 'center', gap: 4 },
  triggerEmoji: { fontSize: 28, marginBottom: 4 },
  triggerTitle: { fontSize: 15, fontWeight: '700', color: '#0a1f0e' },
  triggerSub: { fontSize: 12, color: '#9db8a4', fontWeight: '500' },
  triggerSignedContent: { flexDirection: 'row', alignItems: 'center', gap: 12 },
  checkBadge: {
    width: 38, height: 38, borderRadius: 19,
    backgroundColor: '#1a7a3c',
    alignItems: 'center', justifyContent: 'center',
  },
  checkIcon: { fontSize: 18, color: '#fff', fontWeight: '800' },
  triggerSignedTitle: { fontSize: 14, fontWeight: '800', color: '#0a1f0e' },
  triggerSignedSub: { fontSize: 12, color: '#6aaa78', marginTop: 1 },

  /* Modal */
  modalRoot: {
    flex: 1,
    backgroundColor: '#ffffff',
    paddingTop: Platform.OS === 'ios' ? 50 : (StatusBar.currentHeight || 24),
  },
  modalHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 14,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f5f1',
  },
  closeBtn: {
    width: 36, height: 36, borderRadius: 18,
    backgroundColor: '#f4f7f5',
    alignItems: 'center', justifyContent: 'center',
  },
  closeIcon: { fontSize: 14, color: '#5a7a62', fontWeight: '700' },
  headerCenter: { flex: 1, alignItems: 'center' },
  modalTitle: { fontSize: 16, fontWeight: '800', color: '#0a1f0e' },
  modalSub: { fontSize: 11, color: '#9db8a4', marginTop: 2 },
  clearBtn: {
    backgroundColor: '#f0f5f1',
    borderRadius: 10,
    paddingHorizontal: 12,
    paddingVertical: 8,
  },
  clearText: { fontSize: 13, fontWeight: '700', color: '#5a7a62' },

  canvasContainer: {
    flex: 1,
    borderBottomWidth: 1,
    borderColor: '#f0f5f1',
  },
  webview: {
    flex: 1,
    backgroundColor: '#ffffff',
  },

  modalFooter: {
    padding: 20,
    paddingBottom: Platform.OS === 'ios' ? 36 : 20,
    alignItems: 'center',
    gap: 8,
    backgroundColor: '#ffffff',
  },
  validateBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 10,
    width: '100%',
    backgroundColor: '#1a7a3c',
    borderRadius: 16,
    paddingVertical: 17,
    shadowColor: '#1a7a3c',
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.35,
    shadowRadius: 14,
    elevation: 10,
  },
  validateBtnDisabled: { backgroundColor: '#c2d9c9', shadowOpacity: 0 },
  validateIcon: { fontSize: 18, color: '#fff' },
  validateText: { fontSize: 16, fontWeight: '800', color: '#fff', letterSpacing: 0.2 },
  footerHint: { fontSize: 12, color: '#9db8a4', fontWeight: '500' },
});