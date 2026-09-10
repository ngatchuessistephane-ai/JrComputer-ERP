import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  RefreshControl,
  Animated,
  StatusBar,
  TextInput,
  Alert,
  Image,
  Platform,
} from 'react-native';
// Importation des icônes professionnelles style Startup
import { Search, X, Laptop, ChevronRight, LogOut, ClipboardList, Shield, ShieldAlert } from 'lucide-react-native';
import api from '../api/client';
import { useAuth } from '../contexts/AuthContext';
import { useWebSocket } from '../hooks/useWebSocket';

// ─── Palette Jr Computer (Premium & Modernisée) ───────────────────────
const C = {
  greenDark:    '#1a6b2e',
  greenMid:     '#2d9048',
  greenLight:   '#4db86a',
  orange:       '#f07d00',
  orangeLight:  '#ffa030',
  bg:           '#f6f9f7',
  surface:      '#ffffff',
  surfaceAlt:   '#eaf2ec',
  border:       '#e2ede5',
  textPrimary:  '#0f172a',
  textSecond:   '#475569',
  textMuted:    '#94a3b8',
};

const STATUS_CONFIG = {
  pending:    { label: 'En attente',  color: '#d97706', bg: '#fef3c7', dot: '#d97706' },
  assigned:   { label: 'Assigné',     color: '#0891b2', bg: '#ecfeff', dot: '#0891b2' },
  diagnosing: { label: 'Diagnostic',  color: '#2563eb', bg: '#eff6ff', dot: '#2563eb' },
  repairing:  { label: 'Réparation',  color: '#7c3aed', bg: '#f5f3ff', dot: '#7c3aed' },
  completed:  { label: 'Terminé',     color: '#16a34a', bg: '#f0fdf4', dot: '#16a34a' },
  restituted: { label: 'Restitué',    color: '#4b5563', bg: '#f3f4f6', dot: '#4b5563' },
};

const PRIORITY_CONFIG = {
  low:      { label: 'Basse',    color: '#64748b' },
  medium:   { label: 'Normale',  color: '#0284c7' },
  high:     { label: 'Haute',    color: '#ea580c' },
  critical: { label: 'Critique', color: '#dc2626' },
};

// ─── Carte ticket ─────────────────────────────────────────────────────
function TicketCard({ ticket, onPress, index }) {
  const slideAnim = useRef(new Animated.Value(20)).current;
  const fadeAnim  = useRef(new Animated.Value(0)).current;

  useEffect(() => {
    Animated.parallel([
      Animated.timing(fadeAnim,  { toValue: 1, duration: 280, delay: index * 40, useNativeDriver: true }),
      Animated.spring(slideAnim, { toValue: 0, friction: 9, tension: 70, delay: index * 40, useNativeDriver: true }),
    ]).start();
  }, []);

  const status   = STATUS_CONFIG[ticket.status]   || STATUS_CONFIG.pending;
  const priority = PRIORITY_CONFIG[ticket.priority] || PRIORITY_CONFIG.medium;

  return (
    <Animated.View style={{ opacity: fadeAnim, transform: [{ translateY: slideAnim }] }}>
      <TouchableOpacity style={styles.card} onPress={onPress} activeOpacity={0.7}>
        <View style={[styles.cardStripe, { backgroundColor: status.dot }]} />
        <View style={styles.cardBody}>
          <View style={styles.cardRow}>
            <Text style={styles.ticketNum}>#{ticket.ticket_number}</Text>
            <View style={[styles.statusPill, { backgroundColor: status.bg }]}>
              <View style={[styles.statusDot, { backgroundColor: status.dot }]} />
              <Text style={[styles.statusText, { color: status.color }]}>{status.label}</Text>
            </View>
          </View>
          <Text style={styles.clientName} numberOfLines={1}>
            {ticket.customer?.name || '—'}
          </Text>
          <View style={styles.deviceRow}>
            <Laptop size={14} color={C.textSecond} strokeWidth={2.2} />
            <Text style={styles.deviceText} numberOfLines={1}>
              {ticket.device_model || ticket.product?.name || 'Appareil inconnu'}
            </Text>
          </View>
          <View style={styles.cardFooter}>
            <View style={[styles.priorityTag, { backgroundColor: priority.color + '10', borderColor: priority.color + '30' }]}>
              <View style={[styles.priorityDot, { backgroundColor: priority.color }]} />
              <Text style={[styles.priorityText, { color: priority.color }]}>{priority.label}</Text>
            </View>
            <View style={[styles.warrantyTag, { backgroundColor: ticket.is_warranty ? '#f0fdf4' : '#fef2f2' }]}>
              {ticket.is_warranty ? (
                <Shield size={12} color={C.greenDark} strokeWidth={2.5} style={{ marginRight: 3 }} />
              ) : (
                <ShieldAlert size={12} color="#ef4444" strokeWidth={2.5} style={{ marginRight: 3 }} />
              )}
              <Text style={[styles.warrantyText, { color: ticket.is_warranty ? C.greenDark : '#ef4444' }]}>
                {ticket.is_warranty ? 'Garantie' : 'Hors garantie'}
              </Text>
            </View>
          </View>
        </View>
        <View style={styles.cardChevronWrap}>
          <ChevronRight size={18} color={C.textMuted} strokeWidth={2} />
        </View>
      </TouchableOpacity>
    </Animated.View>
  );
}

// ─── Ecran principal ──────────────────────────────────────────────────
export default function TicketsScreen({ navigation }) {
  const { user, logout } = useAuth();
  const [tickets, setTickets] = useState([]);
  const [refreshing, setRefreshing] = useState(false);
  const [search, setSearch] = useState('');
  const [activeFilter, setActiveFilter] = useState('all');
  const [loading, setLoading] = useState(true);
  const headerAnim = useRef(new Animated.Value(-15)).current;
  const headerOpacity = useRef(new Animated.Value(0)).current;

  // Remplacer la constante FILTERS par :
const FILTERS = [
  { key: 'all', label: 'Tous' },
  { key: 'pending', label: 'En attente' },
  { key: 'progress', label: 'En cours' },      // ✅ Changé de 'repairing' à 'progress'
  { key: 'completed', label: 'Terminés' },
];

  // Fonction corrigée pour charger les tickets
  const loadTickets = useCallback(async () => {
    try {
      console.log('📡 Chargement des tickets...');
      const response = await api.get('/tech/tickets');
      console.log('📥 Réponse reçue:', response.status);
      
      if (response.data?.success) {
        const ticketsData = response.data.tickets || [];
        console.log(`✅ ${ticketsData.length} tickets chargés`);
        setTickets(ticketsData);
      } else {
        console.warn('⚠️ Succès false dans la réponse');
        setTickets([]);
      }
    } catch (error) {
      console.error('❌ Erreur chargement tickets:', error);
      if (error.response) {
        console.error('   Statut:', error.response.status);
        console.error('   Message:', error.response.data?.message);
      } else if (error.request) {
        console.error('   Pas de réponse du serveur');
        console.error('   Vérifiez que le serveur Laravel est démarré');
      }
      setTickets([]);
    } finally {
      setLoading(false);
    }
  }, []);

  // Gestion des nouveaux tickets via WebSocket
  const handleNewTicketAssigned = useCallback((newTicket) => {
    console.log('🔔 Nouveau ticket reçu via WebSocket:', newTicket);
    Alert.alert(
      '📢 Nouveau ticket assigné',
      `${newTicket.ticket_number} — ${newTicket.customer_name || 'Client'}`,
      [{ text: 'OK', onPress: () => loadTickets() }]
    );
    loadTickets();
  }, [loadTickets]);

  // WebSocket
  useWebSocket(user?.id, handleNewTicketAssigned);

  // Initialisation
  useEffect(() => {
    loadTickets();
    Animated.parallel([
      Animated.timing(headerOpacity, { toValue: 1, duration: 350, useNativeDriver: true }),
      Animated.spring(headerAnim, { toValue: 0, friction: 8, tension: 60, useNativeDriver: true }),
    ]).start();
  }, [loadTickets]);

  // Rafraîchissement
  const onRefresh = async () => {
    setRefreshing(true);
    await loadTickets();
    setRefreshing(false);
  };

  // Déconnexion
  const handleLogout = () => {
    Alert.alert(
      'Déconnexion',
      'Voulez-vous vraiment vous déconnecter de votre espace technique ?',
      [
        { text: 'Annuler', style: 'cancel' },
        { text: 'Se déconnecter', style: 'destructive', onPress: () => logout() },
      ]
    );
  };

  // Filtrage des tickets (corrigé)
  const filtered = (tickets || []).filter(t => {
    // Dans la logique de filtrage, remplacer :
const matchFilter = activeFilter === 'all' || 
  (activeFilter === 'progress' && ['repairing', 'diagnosing', 'assigned'].includes(t.status)) ||
  (activeFilter !== 'progress' && activeFilter !== 'all' && t.status === activeFilter);

    const matchSearch = !search ||
      (t.ticket_number?.toLowerCase() || '').includes(search.toLowerCase()) ||
      (t.customer?.name?.toLowerCase() || '').includes(search.toLowerCase());
    return matchFilter && matchSearch;
  });

  const openCount = (tickets || []).filter(t => !['completed', 'restituted'].includes(t.status)).length;

  const getInitials = (name) => {
    if (!name) return 'JR';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
  };

  if (loading) {
    return (
      <View style={[styles.container, styles.centerContent]}>
        <StatusBar barStyle="light-content" backgroundColor={C.greenDark} />
        <View style={styles.loaderWrap}>
          <View style={styles.loaderSpinner} />
          <Text style={styles.loaderText}>Chargement des interventions...</Text>
        </View>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor={C.greenDark} />

      <Animated.View style={[
        styles.header,
        { opacity: headerOpacity, transform: [{ translateY: headerAnim }] }
      ]}>
        <View style={styles.headerLeft}>
          <View style={styles.headerLogoWrap}>
            <Image
              source={require('../../assets/logo-jr.jpg')}
              style={styles.headerLogo}
              resizeMode="cover"
            />
          </View>
          <View>
            <Text style={styles.headerTitle}>Mes Interventions</Text>
            <View style={styles.headerBadge}>
              <View style={styles.headerBadgeDot} />
              <Text style={styles.headerBadgeText}>
                {openCount} actif{openCount !== 1 ? 's' : ''}
              </Text>
            </View>
          </View>
        </View>

        <View style={styles.headerRight}>
          <View style={styles.userInfo}>
            <Text style={styles.techName}>{user?.name?.split(' ')[0] || 'Tech'}</Text>
            <View style={styles.avatarWrap}>
              <Text style={styles.avatarText}>{getInitials(user?.name)}</Text>
            </View>
          </View>
          <TouchableOpacity 
            style={styles.logoutButton} 
            onPress={handleLogout}
            activeOpacity={0.7}
          >
            <LogOut size={16} color="#fff" strokeWidth={2.5} />
          </TouchableOpacity>
        </View>
      </Animated.View>

      <View style={styles.searchBlock}>
        <View style={styles.searchWrap}>
          <Search size={16} color={C.textMuted} strokeWidth={2.5} />
          <TextInput
            style={styles.searchInput}
            placeholder="Rechercher un ticket, client..."
            placeholderTextColor={C.textMuted}
            value={search}
            onChangeText={setSearch}
          />
          {search.length > 0 && (
            <TouchableOpacity onPress={() => setSearch('')} style={styles.clearBtn}>
              <X size={16} color={C.textMuted} strokeWidth={2.5} />
            </TouchableOpacity>
          )}
        </View>
      </View>

      <View style={styles.filtersRow}>
        {FILTERS.map(f => (
          <TouchableOpacity
            key={f.key}
            style={[styles.chip, activeFilter === f.key && styles.chipActive]}
            onPress={() => setActiveFilter(f.key)}
            activeOpacity={0.8}
          >
            <Text style={[styles.chipText, activeFilter === f.key && styles.chipTextActive]}>
              {f.label}
            </Text>
          </TouchableOpacity>
        ))}
      </View>

      <FlatList
        data={filtered}
        keyExtractor={item => item.id?.toString() || Math.random().toString()}
        contentContainerStyle={styles.list}
        renderItem={({ item, index }) => (
          <TicketCard
            ticket={item}
            index={index}
            onPress={() => navigation.navigate('TicketDetail', { ticket: item })}
          />
        )}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={[C.greenMid]}
            tintColor={C.greenMid}
          />
        }
        ListEmptyComponent={() => (
          <View style={styles.emptyWrap}>
            <ClipboardList size={40} color={C.textMuted} strokeWidth={1.5} />
            <Text style={styles.emptyTitle}>Aucun ticket trouvé</Text>
            <Text style={styles.emptyText}>Tirez vers le bas pour actualiser</Text>
          </View>
        )}
        showsVerticalScrollIndicator={false}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: C.bg },
  centerContent: { justifyContent: 'center', alignItems: 'center' },

  // Loader
  loaderWrap: { alignItems: 'center', justifyContent: 'center' },
  loaderSpinner: {
    width: 40,
    height: 40,
    borderRadius: 20,
    borderWidth: 3,
    borderColor: C.greenMid,
    borderTopColor: C.orange,
    marginBottom: 12,
  },
  loaderText: { fontSize: 14, color: C.textSecond, marginTop: 10 },

  // ── En-tête ───────────────────────────────────────
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: C.greenDark,
    paddingTop: Platform.OS === 'ios' ? 56 : 46,
    paddingHorizontal: 16,
    paddingBottom: 20,
    borderBottomWidth: 1,
    borderBottomColor: 'rgba(255,255,255,0.08)',
  },
  headerLeft: { flexDirection: 'row', alignItems: 'center', gap: 12 },
  headerLogoWrap: {
    width: 40, height: 40,
    borderRadius: 12,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
    overflow: 'hidden',
  },
  headerLogo: { width: '100%', height: '100%' },
  headerTitle: { fontSize: 18, fontWeight: '700', color: '#fff', letterSpacing: -0.5 },
  headerBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    marginTop: 3,
    backgroundColor: 'rgba(240, 125, 0, 0.15)',
    paddingHorizontal: 8,
    paddingVertical: 2,
    borderRadius: 6,
    alignSelf: 'flex-start',
  },
  headerBadgeDot: { width: 6, height: 6, borderRadius: 3, backgroundColor: C.orange },
  headerBadgeText: { fontSize: 11, color: C.orangeLight, fontWeight: '600' },
  headerRight: { flexDirection: 'row', alignItems: 'center', gap: 12 },
  userInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    backgroundColor: 'rgba(255,255,255,0.06)',
    paddingLeft: 10,
    paddingRight: 4,
    paddingVertical: 4,
    borderRadius: 30,
  },
  techName: { fontSize: 12, color: '#fff', fontWeight: '600' },
  avatarWrap: {
    width: 28, height: 28,
    borderRadius: 14,
    backgroundColor: C.orange,
    alignItems: 'center',
    justifyContent: 'center',
  },
  avatarText: { color: '#fff', fontWeight: '700', fontSize: 11 },
  logoutButton: {
    width: 36, height: 36,
    borderRadius: 10,
    backgroundColor: 'rgba(255, 255, 255, 0.1)',
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.05)',
  },

  // ── Recherche ─────────────────────────────────────
  searchBlock: { paddingHorizontal: 16, paddingTop: 16, paddingBottom: 8 },
  searchWrap: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: C.surface,
    borderRadius: 12,
    paddingHorizontal: 12,
    borderWidth: 1,
    borderColor: C.border,
    gap: 8,
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.03,
    shadowRadius: 4,
    elevation: 2,
  },
  searchInput: { flex: 1, paddingVertical: 10, fontSize: 14, color: C.textPrimary },
  clearBtn: { padding: 4 },

  // ── Filtres ───────────────────────────────────────
  filtersRow: { flexDirection: 'row', paddingHorizontal: 16, paddingVertical: 8, gap: 8 },
  chip: {
    paddingHorizontal: 14,
    paddingVertical: 6,
    borderRadius: 8,
    backgroundColor: C.surface,
    borderWidth: 1,
    borderColor: C.border,
  },
  chipActive: { backgroundColor: C.greenDark, borderColor: C.greenDark },
  chipText: { fontSize: 13, fontWeight: '600', color: C.textSecond },
  chipTextActive: { color: '#fff' },

  // ── Liste ─────────────────────────────────────────
  list: { paddingHorizontal: 16, paddingBottom: 30, gap: 12 },

  // ── Carte Ticket ──────────────────────────────────
  card: {
    backgroundColor: C.surface,
    borderRadius: 14,
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: C.border,
    overflow: 'hidden',
    shadowColor: '#0f172a',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.04,
    shadowRadius: 10,
    elevation: 3,
  },
  cardStripe: { width: 4, alignSelf: 'stretch' },
  cardBody: { flex: 1, padding: 16, gap: 6 },
  cardRow: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  ticketNum: { fontSize: 12, fontWeight: '700', color: C.textMuted, letterSpacing: 0.5 },
  statusPill: { flexDirection: 'row', alignItems: 'center', paddingHorizontal: 8, paddingVertical: 3, borderRadius: 6, gap: 5 },
  statusDot: { width: 5, height: 5, borderRadius: 2.5 },
  statusText: { fontSize: 11, fontWeight: '700' },
  clientName: { fontSize: 15, fontWeight: '600', color: C.textPrimary },
  deviceRow: { flexDirection: 'row', alignItems: 'center', gap: 6, marginTop: 2 },
  deviceText: { fontSize: 13, color: C.textSecond, flex: 1 },
  cardFooter: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    marginTop: 6,
    paddingTop: 8,
    borderTopWidth: 1,
    borderTopColor: '#f1f5f9',
  },
  priorityTag: { flexDirection: 'row', alignItems: 'center', borderWidth: 1, borderRadius: 6, paddingHorizontal: 6, paddingVertical: 2, gap: 4 },
  priorityDot: { width: 4, height: 4, borderRadius: 2 },
  priorityText: { fontSize: 11, fontWeight: '600' },
  warrantyTag: { flexDirection: 'row', alignItems: 'center', borderRadius: 6, paddingHorizontal: 6, paddingVertical: 2 },
  warrantyText: { fontSize: 11, fontWeight: '600' },
  cardChevronWrap: { paddingRight: 16 },

  // ── Vue Vide ──────────────────────────────────────
  emptyWrap: { alignItems: 'center', justifyContent: 'center', paddingTop: 80, gap: 12 },
  emptyTitle: { fontSize: 15, fontWeight: '600', color: C.textPrimary, marginTop: 4 },
  emptyText: { fontSize: 13, color: C.textMuted },
});