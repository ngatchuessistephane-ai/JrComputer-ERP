import React, { useRef, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Animated,
  StatusBar,
  Image,
  Dimensions,
  Platform,
} from 'react-native';
// Importation des icônes professionnelles pour remplacer les émojis
import { 
  ChevronLeft, 
  User, 
  Mail, 
  Phone, 
  Laptop, 
  Barcode, 
  Wrench, 
  Package, 
  CheckCircle, 
  AlertCircle,
  Clock,
  Search,
  ShieldCheck,
  ArrowRight
} from 'lucide-react-native';

const { width } = Dimensions.get('window');

// ─── Palette Jr Computer (Conservée & Sublimée) ─────────────────────────
const C = {
  greenDark:   '#1a6b2e',
  greenMid:    '#2d9048',
  greenLight:  '#4db86a',
  orange:      '#f07d00',
  orangeLight: '#ffa030',
  bg:          '#f2f6f3',
  surface:     '#ffffff',
  border:      '#d6e8da',
  textPrimary: '#0d1f10',
  textSecond:  '#3d5c45',
  textMuted:   '#7fa687',
};

// Configuration dynamique des icônes Lucide selon le statut
const STATUS_CONFIG = {
  pending:     { label: 'En attente',  color: '#f07d00', bg: '#fff5e6', icon: Clock },
  assigned:    { label: 'Assigné',     color: '#0891b2', bg: '#e6f7fb', icon: User },
  diagnosing:  { label: 'Diagnostic',  color: '#3b82f6', bg: '#eff6ff', icon: Search },
  repairing:   { label: 'Réparation',  color: '#8b5cf6', bg: '#f5f3ff', icon: Wrench },
  completed:   { label: 'Terminé',     color: '#1a6b2e', bg: '#eaf6ee', icon: CheckCircle },
  restituted:  { label: 'Restitué',    color: '#6b7280', bg: '#f3f4f6', icon: Package },
};

const PRIORITY_CONFIG = {
  low:       { label: 'Basse',    color: '#6b7280', bg: '#f3f4f6' },
  medium:    { label: 'Normale',  color: '#0891b2', bg: '#e6f7fb' },
  high:      { label: 'Haute',    color: '#f07d00', bg: '#fff5e6' },
  critical:  { label: 'Critique', color: '#ef4444', bg: '#fff0f0' },
};

// ─── Composants Réutilisables Internes ─────────────────────────────────
function InfoRow({ icon: Icon, label, value, valueStyle }) {
  return (
    <View style={styles.infoRow}>
      <View style={styles.infoIconWrap}>
        <Icon size={15} color={C.greenMid} strokeWidth={2} />
      </View>
      <View style={styles.infoContent}>
        <Text style={styles.infoLabel}>{label}</Text>
        <Text style={[styles.infoValue, valueStyle]}>{value || '—'}</Text>
      </View>
    </View>
  );
}

function Section({ title, icon: Icon, children, accent }) {
  return (
    <View style={styles.section}>
      <View style={[styles.sectionHeader, accent && { borderLeftColor: accent }]}>
        <Icon size={16} color={accent || C.greenMid} strokeWidth={2.5} />
        <Text style={styles.sectionTitle}>{title}</Text>
      </View>
      <View style={styles.sectionBody}>{children}</View>
    </View>
  );
}

// ─── Écran Principal ──────────────────────────────────────────────────
export default function TicketDetailScreen({ route, navigation }) {
  const { ticket } = route.params;
  const statusConfig = STATUS_CONFIG[ticket.status]   || STATUS_CONFIG.pending;
  const priority     = PRIORITY_CONFIG[ticket.priority] || PRIORITY_CONFIG.medium;
  
  const StatusIcon   = statusConfig.icon;

  const fadeAnim  = useRef(new Animated.Value(0)).current;
  const slideAnim = useRef(new Animated.Value(15)).current;

  useEffect(() => {
    Animated.parallel([
      Animated.timing(fadeAnim,  { toValue: 1, duration: 300, useNativeDriver: true }),
      Animated.spring(slideAnim, { toValue: 0, friction: 9, tension: 60, useNativeDriver: true }),
    ]).start();
  }, []);

  const canClose = !['completed', 'restituted'].includes(ticket.status);

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor={C.greenDark} />

      {/* ── En-tête de page supérieure (Hero) ── */}
      <View style={styles.hero}>
        <View style={styles.heroTopBar}>
          <TouchableOpacity 
            style={styles.backBtn} 
            onPress={() => navigation.goBack()}
            activeOpacity={0.7}
          >
            <ChevronLeft size={22} color={C.orange} strokeWidth={2.5} />
            <Text style={styles.backText}>Tickets</Text>
          </TouchableOpacity>

          <View style={styles.heroLogoWrap}>
            <Image
              source={require('../../assets/logo-jr.jpg')}
              style={styles.heroLogo}
              resizeMode="cover"
            />
          </View>
        </View>

        <View style={styles.heroContent}>
          {/* Badge Statut Fluide */}
          <View style={[styles.statusBadge, { backgroundColor: statusConfig.bg }]}>
            <StatusIcon size={13} color={statusConfig.color} strokeWidth={2.5} />
            <Text style={[styles.statusBadgeText, { color: statusConfig.color }]}>
              {statusConfig.label}
            </Text>
          </View>

          <Text style={styles.heroTicketNum}>#{ticket.ticket_number}</Text>
          <Text style={styles.heroClient}>{ticket.customer?.name || 'Client Anonyme'}</Text>
        </View>

        <View style={styles.heroOrangeAccent} />
      </View>

      {/* ── Corps d'affichage Défilant ── */}
      <Animated.ScrollView
        style={{ opacity: fadeAnim, transform: [{ translateY: slideAnim }] }}
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* ── Quick Stats Épurés ── */}
        <View style={styles.quickStats}>
          {/* Carte Priorité */}
          <View style={[styles.statCard, { borderTopColor: priority.color }]}>
            <Text style={styles.statLabel}>Priorité</Text>
            <View style={[styles.statPill, { backgroundColor: priority.bg }]}>
              <View style={[styles.statPillDot, { backgroundColor: priority.color }]} />
              <Text style={[styles.statValue, { color: priority.color }]}>{priority.label}</Text>
            </View>
          </View>

          {/* Carte Garantie */}
          <View style={[styles.statCard, {
            borderTopColor: ticket.is_warranty ? C.greenMid : '#ef4444',
          }]}>
            <Text style={styles.statLabel}>Couverture</Text>
            <View style={[styles.statPill, {
              backgroundColor: ticket.is_warranty ? '#eaf6ee' : '#fff0f0',
            }]}>
              <Text style={[styles.statValue, {
                color: ticket.is_warranty ? C.greenDark : '#ef4444',
              }]}>
                {ticket.is_warranty ? 'Sous garantie' : 'Hors garantie'}
              </Text>
            </View>
          </View>
        </View>

        {/* ── Section Client ── */}
        <Section title="Informations Client" icon={User} accent={C.orange}>
          <InfoRow icon={Mail} label="Adresse email" value={ticket.customer?.email} />
          <InfoRow icon={Phone} label="Ligne téléphonique" value={ticket.customer?.phone} />
        </Section>

        {/* ── Section Matériel ── */}
        <Section title="Matériel Concerné" icon={Laptop} accent={C.greenMid}>
          <InfoRow icon={Laptop} label="Modèle de l'appareil" value={ticket.device_model || ticket.product?.name} />
          <InfoRow icon={Barcode} label="Numéro de série" value={ticket.serial_number} valueStyle={styles.serialCode} />
        </Section>

        {/* ── Section Description Diagnostiquée ── */}
        <Section title="Description de la Panne" icon={AlertCircle} accent={C.orange}>
          <View style={styles.descriptionBox}>
            <Text style={styles.descriptionText}>
              {ticket.description_failure || 'Aucune spécification sur les symptômes de la panne.'}
            </Text>
          </View>
        </Section>

        {/* ── Section Pièces Remplacées ── */}
        {ticket.items?.length > 0 && (
          <Section title="Pièces Utilisées" icon={Package} accent={C.greenLight}>
            {ticket.items.map((item, i) => (
              <View key={i} style={[
                styles.partRow,
                i < ticket.items.length - 1 && styles.partRowBorder,
              ]}>
                <View style={styles.partRowLeft}>
                  <Text style={styles.partRowName}>{item.spare_part?.name || 'Pièce détachée'}</Text>
                  <Text style={styles.partRowQty}>Quantité : {item.quantity}</Text>
                </View>
                <View style={styles.partRowRight}>
                  <Text style={styles.partRowPrice}>
                    {(item.unit_price * item.quantity).toLocaleString('fr-FR')}
                  </Text>
                  <Text style={styles.partRowCurrency}>FCFA</Text>
                </View>
              </View>
            ))}
          </Section>
        )}

        {/* ── Actions Opérationnelles (CTA) ── */}
        {canClose && (
          <View style={styles.ctaBlock}>
            <TouchableOpacity
              style={styles.ctaButton}
              onPress={() => navigation.navigate('CloseTicket', { ticket })}
              activeOpacity={0.85}
            >
              <View style={styles.ctaIconWrap}>
                <ShieldCheck size={16} color="#fff" strokeWidth={2.5} />
              </View>
              <Text style={styles.ctaText}>Clôturer l'intervention</Text>
              <ChevronLeft size={16} color={C.orange} strokeWidth={3} style={{ transform: [{ rotate: '180deg' }] }} />
            </TouchableOpacity>
            <Text style={styles.ctaHint}>Rapport technique et signature numérique client requis</Text>
          </View>
        )}

      </Animated.ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { 
    flex: 1, 
    backgroundColor: C.bg 
  },

  // ── Structure Hero ────────────────────────────────
  hero: {
    backgroundColor: C.greenDark,
    paddingTop: 54,
    paddingHorizontal: 24,
    paddingBottom: 28,
    borderBottomLeftRadius: 4,
    borderBottomRightRadius: 4,
  },
  heroTopBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  backBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    left: -6,
  },
  backText: {
    fontSize: 15,
    color: '#ffffff',
    fontWeight: '600',
    marginLeft: 2,
  },
  heroLogoWrap: {
    width: 34, 
    height: 34,
    borderRadius: 10,
    backgroundColor: '#ffffff',
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: C.orange,
    overflow: 'hidden',
  },
  heroLogo: {
    width: '100%', 
    height: '100%',
  },
  heroContent: { 
    gap: 6 
  },
  statusBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    alignSelf: 'flex-start',
    paddingHorizontal: 10,
    paddingVertical: 5,
    borderRadius: 8,
    gap: 6,
    marginBottom: 4,
  },
  statusBadgeText: { 
    fontSize: 12, 
    fontWeight: '700' 
  },
  heroTicketNum: {
    fontSize: 30,
    fontWeight: '800',
    color: '#ffffff',
    letterSpacing: -0.5,
  },
  heroClient: {
    fontSize: 14,
    color: 'rgba(255, 255, 255, 0.75)',
    fontWeight: '500',
  },
  heroOrangeAccent: {
    position: 'absolute',
    bottom: 0, 
    left: 0, 
    right: 0,
    height: 3,
    backgroundColor: C.orange,
  },

  // ── Zone de Défilement ──────────────────────────────
  scrollContent: {
    padding: 16,
    gap: 16,
    paddingBottom: 40,
  },

  // ── Stat Cards ────────────────────────────────────
  quickStats: {
    flexDirection: 'row',
    gap: 12,
  },
  statCard: {
    flex: 1,
    backgroundColor: C.surface,
    borderRadius: 14,
    padding: 14,
    gap: 6,
    borderWidth: 1,
    borderColor: C.border,
    borderTopWidth: 3,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 6,
    elevation: 2,
  },
  statLabel: {
    fontSize: 10,
    fontWeight: '700',
    color: C.textMuted,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  statPill: {
    flexDirection: 'row',
    alignItems: 'center',
    borderRadius: 6,
    paddingHorizontal: 8,
    paddingVertical: 4,
    gap: 5,
    alignSelf: 'flex-start',
  },
  statPillDot: {
    width: 6, 
    height: 6,
    borderRadius: 3,
  },
  statValue: {
    fontSize: 12,
    fontWeight: '700',
  },

  // ── Section Container ──────────────────────────────
  section: {
    backgroundColor: C.surface,
    borderRadius: 14,
    overflow: 'hidden',
    borderWidth: 1,
    borderColor: C.border,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.02,
    shadowRadius: 4,
    elevation: 1,
  },
  sectionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    paddingHorizontal: 16,
    paddingTop: 14,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f4faf6',
    borderLeftWidth: 4,
    borderLeftColor: C.greenMid,
  },
  sectionTitle: {
    fontSize: 13,
    fontWeight: '700',
    color: C.textPrimary,
    letterSpacing: 0.1,
  },
  sectionBody: {
    padding: 16,
    gap: 14,
  },

  // ── Info Row Spec ─────────────────────────────────
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
  },
  infoIconWrap: {
    width: 30, 
    height: 30,
    borderRadius: 8,
    backgroundColor: C.bg,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: '#e3eee6',
  },
  infoContent: { 
    flex: 1 
  },
  infoLabel: {
    fontSize: 10,
    fontWeight: '700',
    color: C.textMuted,
    textTransform: 'uppercase',
    letterSpacing: 0.4,
    marginBottom: 1,
  },
  infoValue: {
    fontSize: 14,
    color: C.textPrimary,
    fontWeight: '600',
  },
  serialCode: {
    fontFamily: Platform.OS === 'ios' ? 'Courier' : 'monospace',
    fontSize: 13,
    color: C.textSecond,
  },

  // ── Description Box ────────────────────────────────
  descriptionBox: {
    backgroundColor: C.bg,
    borderRadius: 10,
    padding: 14,
    borderWidth: 1,
    borderColor: C.border,
  },
  descriptionText: {
    fontSize: 14,
    color: C.textSecond,
    lineHeight: 21,
  },

  // ── Pièces Remplacées ──────────────────────────────
  partRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 8,
  },
  partRowBorder: {
    borderBottomWidth: 1,
    borderBottomColor: '#f0f6f2',
  },
  partRowLeft: { 
    gap: 2, 
    flex: 1 
  },
  partRowName: {
    fontSize: 13,
    fontWeight: '600',
    color: C.textPrimary,
  },
  partRowQty: {
    fontSize: 11,
    color: C.textMuted,
    fontWeight: '500',
  },
  partRowRight: {
    flexDirection: 'row',
    alignItems: 'baseline',
    gap: 2,
  },
  partRowPrice: {
    fontSize: 15,
    fontWeight: '700',
    color: C.greenDark,
  },
  partRowCurrency: {
    fontSize: 10,
    color: C.textMuted,
    fontWeight: '700',
  },

  // ── CTA Clôture ───────────────────────────────────
  ctaBlock: {
    gap: 8,
    marginTop: 4,
  },
  ctaButton: {
    backgroundColor: C.greenDark,
    borderRadius: 14,
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 16,
    gap: 12,
    shadowColor: C.greenDark,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.25,
    shadowRadius: 10,
    elevation: 4,
    borderLeftWidth: 4,
    borderLeftColor: C.orange,
  },
  ctaIconWrap: {
    width: 26, 
    height: 26,
    borderRadius: 8,
    backgroundColor: 'rgba(255, 255, 255, 0.15)',
    alignItems: 'center',
    justifyContent: 'center',
  },
  ctaText: {
    flex: 1,
    fontSize: 14,
    fontWeight: '700',
    color: '#ffffff',
  },
  ctaHint: {
    textAlign: 'center',
    fontSize: 11,
    color: C.textMuted,
    fontWeight: '500',
  },
});