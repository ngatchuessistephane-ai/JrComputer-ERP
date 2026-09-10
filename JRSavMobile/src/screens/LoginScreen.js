import React, { useState, useRef, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Alert,
  ActivityIndicator,
  KeyboardAvoidingView,
  Platform,
  Animated,
  Image,
  StatusBar,
  Dimensions,
  Pressable,
} from 'react-native';
import { Mail, Lock, Eye, EyeOff, ArrowRight, Cpu } from 'lucide-react-native';
import { useAuth } from '../contexts/AuthContext';

const { width, height } = Dimensions.get('window');

// ── Palette Jr Computer Premium ──
const C = {
  bg: '#050906',           // Noir organique ultra-profond
  bgCard: '#0B120E',       // Fond de carte texturé style graphite sombre
  border: '#142217',       // Bordure structurelle fusionnée avec le vert du logo
  greenLogo: '#008542',    // Le vert officiel de la marque (centre du logo)
  greenGlow: '#00D154',    // Vert fluorescent d'activation tech
  orangeLogo: '#F07D00',   // L'orange signature dynamique
  orangeLight: '#FF981A',  
  textPrimary: '#F5F8F6',  
  textSecondary: '#829A89',
  textMuted: '#3A4F40',
};

export default function LoginScreen() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [focusedField, setFocusedField] = useState(null);
  const [showPassword, setShowPassword] = useState(false);
  const { login } = useAuth();

  // ── Séquences d'Animations Fluides ──
  const fadeAnim = useRef(new Animated.Value(0)).current;
  const slideForm = useRef(new Animated.Value(35)).current;
  const logoScale = useRef(new Animated.Value(0.9)).current;
  const ambientGlow = useRef(new Animated.Value(0)).current;
  const btnScale = useRef(new Animated.Value(1)).current;

  // États pour les labels flottants haut de gamme
  const emailLabelAnim = useRef(new Animated.Value(email ? 1 : 0)).current;
  const passLabelAnim = useRef(new Animated.Value(password ? 1 : 0)).current;

  useEffect(() => {
    Animated.stagger(120, [
      Animated.parallel([
        Animated.spring(logoScale, { toValue: 1, tension: 50, friction: 8, useNativeDriver: true }),
        Animated.timing(fadeAnim, { toValue: 1, duration: 500, useNativeDriver: true }),
      ]),
      Animated.spring(slideForm, { toValue: 0, tension: 45, friction: 9, useNativeDriver: true })
    ]).start();

    Animated.loop(
      Animated.sequence([
        Animated.timing(ambientGlow, { toValue: 1, duration: 3500, useNativeDriver: true }),
        Animated.timing(ambientGlow, { toValue: 0, duration: 3500, useNativeDriver: true }),
      ])
    ).start();
  }, []);

  const handleFocus = (field) => {
    setFocusedField(field);
    Animated.timing(field === 'email' ? emailLabelAnim : passLabelAnim, {
      toValue: 1,
      duration: 150,
      useNativeDriver: false,
    }).start();
  };

  const handleBlur = (field, value) => {
    setFocusedField(null);
    if (!value) {
      Animated.timing(field === 'email' ? emailLabelAnim : passLabelAnim, {
        toValue: 0,
        duration: 150,
        useNativeDriver: false,
      }).start();
    }
  };

  const onPressInBtn = () => {
    Animated.spring(btnScale, { toValue: 0.97, useNativeDriver: true }).start();
  };
  const onPressOutBtn = () => {
    Animated.spring(btnScale, { toValue: 1, useNativeDriver: true }).start();
  };

  const handleLogin = async () => {
    if (!email || !password) {
      Alert.alert('Champs requis', 'Veuillez saisir vos identifiants de maintenance.');
      return;
    }
    setLoading(true);
    const result = await login(email, password);
    setLoading(false);
    if (!result.success) {
      Alert.alert('Échec de l\'authentification', result.error || 'Accès refusé.');
    }
  };

  const glowOpacity = ambientGlow.interpolate({ inputRange: [0, 1], outputRange: [0.1, 0.25] });
  const glowScale = ambientGlow.interpolate({ inputRange: [0, 1], outputRange: [1, 1.12] });

  const labelStyle = (anim) => ({
    position: 'absolute',
    left: 46,
    top: anim.interpolate({ inputRange: [0, 1], outputRange: [16, -9] }),
    fontSize: anim.interpolate({ inputRange: [0, 1], outputRange: [15, 11] }),
    color: anim.interpolate({ inputRange: [0, 1], outputRange: [C.textSecondary, C.greenGlow] }),
    backgroundColor: C.bgCard,
    paddingHorizontal: 6,
    fontWeight: '600',
    zIndex: 2,
  });

  return (
    <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor={C.bg} />

      {/* Halo lumineux en arrière-plan basé sur logo-jr.jpg */}
      <Animated.View style={[styles.haloRadial, { opacity: glowOpacity, transform: [{ scale: glowScale }] }]} />
      <View style={styles.topNeonBorder} />

      <View style={styles.inner}>
        
        {/* Section Branding & Logo */}
        <Animated.View style={[styles.brandHeader, { opacity: fadeAnim, transform: [{ scale: logoScale }] }]}>
          <View style={styles.logoFrame}>
            <Image
              source={require('../../assets/logo-jr.jpg')}
              style={styles.logoAsset}
              resizeMode="contain"
            />
          </View>
          <Text style={styles.titleText}>
            Jr <Text style={{ color: C.orangeLogo }}>Computer</Text>
          </Text>
          <View style={styles.terminalBadge}>
            <Cpu size={13} color={C.orangeLogo} strokeWidth={2.5} />
            <Text style={styles.terminalBadgeText}>ESPACE TECHNIQUE SAV</Text>
          </View>
        </Animated.View>

        {/* Formulaire Élargi & Ergonomique */}
        <Animated.View style={[styles.card, { opacity: fadeAnim, transform: [{ translateY: slideForm }] }]}>
          
          {/* Input Email */}
          <View style={[styles.inputBox, focusedField === 'email' && styles.inputBoxFocused]}>
            <Mail size={20} color={focusedField === 'email' ? C.greenGlow : C.textMuted} strokeWidth={2} />
            <Animated.Text style={labelStyle(emailLabelAnim)}>Adresse email professionnelle</Animated.Text>
            <TextInput
              style={styles.textInput}
              value={email}
              onChangeText={setEmail}
              autoCapitalize="none"
              keyboardType="email-address"
              onFocus={() => handleFocus('email')}
              onBlur={() => handleBlur('email', email)}
              placeholderTextColor="transparent"
              cursorColor={C.greenGlow}
            />
          </View>

          {/* Input Mot de Passe */}
          <View style={[styles.inputBox, focusedField === 'password' && styles.inputBoxFocused]}>
            <Lock size={20} color={focusedField === 'password' ? C.greenGlow : C.textMuted} strokeWidth={2} />
            <Animated.Text style={labelStyle(passLabelAnim)}>Mot de passe de session</Animated.Text>
            <TextInput
              style={styles.textInput}
              value={password}
              onChangeText={setPassword}
              secureTextEntry={!showPassword}
              onFocus={() => handleFocus('password')}
              onBlur={() => handleBlur('password', password)}
              placeholderTextColor="transparent"
              cursorColor={C.greenGlow}
            />
            <TouchableOpacity onPress={() => setShowPassword(!showPassword)} style={styles.eyeBtn} activeOpacity={0.6}>
              {showPassword ? <EyeOff size={20} color={C.textSecondary} /> : <Eye size={20} color={C.textMuted} />}
            </TouchableOpacity>
          </View>

          {/* Option Utilitaires */}
          <TouchableOpacity style={styles.supportLink} activeOpacity={0.7}>
            <Text style={styles.supportLinkText}>Assistance d'identification ou clé manquante</Text>
          </TouchableOpacity>

          {/* Bouton "Se connecter" Large & Impactant */}
          <Animated.View style={{ transform: [{ scale: btnScale }] }}>
            <Pressable
              onPressIn={onPressInBtn}
              onPressOut={onPressOutBtn}
              onPress={handleLogin}
              disabled={loading}
              style={({ pressed }) => [
                styles.ctaButton,
                loading && styles.ctaButtonDisabled,
                pressed && { backgroundColor: C.greenLogo }
              ]}
            >
              {loading ? (
                <ActivityIndicator color="#000" size="small" />
              ) : (
                <View style={styles.ctaContent}>
                  <Text style={styles.ctaText}>Se connecter</Text>
                  <ArrowRight size={18} color="#000000" strokeWidth={2.5} />
                </View>
              )}
            </Pressable>
          </Animated.View>

          {/* Mentions de Cryptage ERP */}
          <Text style={styles.systemFooter}>
            Cryptage de bout en bout actif • Terminal Jr Computer Sarl v3.4
          </Text>

        </Animated.View>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: C.bg,
    justifyContent: 'center',
    alignItems: 'center',
  },
  inner: {
    width: '92%',         // Augmentation globale de la zone d'occupation
    maxWidth: 440,        // Plus de largeur pour un confort visuel maximal (Style SaaS)
    alignItems: 'center',
  },

  // ── Éléments Lumineux de Fond ──
  haloRadial: {
    position: 'absolute',
    width: 360,
    height: 360,
    borderRadius: 180,
    backgroundColor: C.greenLogo,
    top: height * 0.08,
    filter: Platform.OS === 'ios' ? 'blur(65px)' : [],
    opacity: 0.18,
  },
  topNeonBorder: {
    position: 'absolute',
    top: 0,
    width: width * 0.7,
    height: 3,
    backgroundColor: C.orangeLogo,
    opacity: 0.5,
    shadowColor: C.orangeLogo,
    shadowOffset: { width: 0, height: 5 },
    shadowOpacity: 0.7,
    shadowRadius: 12,
  },

  // ── Identité de l'En-tête ──
  brandHeader: {
    alignItems: 'center',
    marginBottom: 36,
  },
  logoFrame: {
    width: 105,
    height: 105,
    borderRadius: 26,
    backgroundColor: '#FFFFFF', // Cadre immaculé pour faire ressortir le vert du fichier logo-jr.jpg
    padding: 8,
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 12 },
    shadowOpacity: 0.35,
    shadowRadius: 18,
    elevation: 10,
    marginBottom: 16,
  },
  logoAsset: {
    width: '95%',
    height: '95%',
  },
  titleText: {
    fontSize: 34,
    fontWeight: '900',
    color: C.textPrimary,
    letterSpacing: -1.2,
  },
  terminalBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(240, 125, 0, 0.08)',
    borderWidth: 1.2,
    borderColor: 'rgba(240, 125, 0, 0.22)',
    paddingHorizontal: 14,
    paddingVertical: 5,
    borderRadius: 30,
    gap: 6,
    marginTop: 10,
  },
  terminalBadgeText: {
    fontSize: 10.5,
    fontWeight: '800',
    color: C.orangeLight,
    letterSpacing: 1,
  },

  // ── Structure Card Formulaire Élargie ──
  card: {
    width: '100%',
    backgroundColor: C.bgCard,
    borderRadius: 28,
    padding: 28,          // Augmentation des paddings internes pour aérer
    borderWidth: 1,
    borderColor: C.border,
    gap: 22,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 25 },
    shadowOpacity: 0.55,
    shadowRadius: 35,
    elevation: 15,
  },
  inputBox: {
    position: 'relative',
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'rgba(5, 9, 6, 0.7)',
    borderWidth: 1.5,
    borderColor: C.border,
    borderRadius: 16,
    paddingHorizontal: 18,
    height: 60,           // Inputs plus hauts et plus spacieux pour l'ergonomie mobile
    gap: 14,
  },
  inputBoxFocused: {
    borderColor: C.greenGlow,
    backgroundColor: '#060F09',
    shadowColor: C.greenGlow,
    shadowOffset: { width: 0, height: 0 },
    shadowOpacity: 0.18,
    shadowRadius: 10,
  },
  textInput: {
    flex: 1,
    height: '100%',
    fontSize: 15.5,
    color: C.textPrimary,
    fontWeight: '500',
    paddingTop: Platform.OS === 'ios' ? 2 : 0,
  },
  eyeBtn: {
    padding: 4,
  },
  supportLink: {
    alignSelf: 'flex-start', // Changement à gauche pour un accès direct et naturel à l'œil
    marginTop: -6,
    paddingHorizontal: 2,
  },
  supportLinkText: {
    fontSize: 12,
    color: C.textSecondary,
    fontWeight: '500',
    textDecorationLine: 'underline',
    textDecorationColor: 'rgba(130, 154, 137, 0.3)',
  },

  // ── Bouton "Se connecter" Épuré & Premium ──
  ctaButton: {
    backgroundColor: C.greenGlow,
    borderRadius: 16,
    height: 58,           // Bouton généreux, impossible à rater pour le technicien
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: C.greenGlow,
    shadowOffset: { width: 0, height: 8 },
    shadowOpacity: 0.32,
    shadowRadius: 16,
    elevation: 6,
  },
  ctaButtonDisabled: {
    opacity: 0.45,
  },
  ctaContent: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 10,
    width: '100%',
  },
  ctaText: {
    color: '#000000',     // Lisibilité pure et moderne (Texte noir sur fond vert flashy)
    fontWeight: '800',
    fontSize: 17,
    letterSpacing: -0.2,
  },
  systemFooter: {
    textAlign: 'center',
    fontSize: 11,
    color: C.textMuted,
    fontWeight: '600',
    marginTop: 2,
  },
});