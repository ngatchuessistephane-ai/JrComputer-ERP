import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
  Alert,
  ActivityIndicator,
  Modal,
  FlatList,
  StatusBar,
  Platform,
} from 'react-native';
// Icônes de niveau startup pour remplacer les émojis
import { 
  ChevronLeft, 
  FileText, 
  Clock, 
  Settings, 
  PenTool, 
  Check, 
  Plus, 
  Minus, 
  X, 
  Search,
  Package,
  AlertTriangle 
} from 'lucide-react-native';
import SignaturePad from '../components/SignaturePad';
import api from '../api/client';
import { savePendingReport } from '../utils/storage';
import NetInfo from '@react-native-community/netinfo';

/* ─── Indicateur d'étapes ─── */
function StepIndicator({ step, totalSteps }) {
  return (
    <View style={styles.stepRow}>
      {Array.from({ length: totalSteps }).map((_, i) => (
        <View
          key={i}
          style={[
            styles.stepDot,
            i < step && styles.stepDotActive,
            i === step - 1 && styles.stepDotCurrent,
          ]}
        />
      ))}
    </View>
  );
}

/* ─── Carte de section ─── */
function SectionCard({ title, icon: Icon, children, optional }) {
  return (
    <View style={styles.sectionCard}>
      <View style={styles.sectionCardHeader}>
        <View style={styles.sectionCardTitle}>
          <Icon size={16} color="#1a7a3c" strokeWidth={2.5} />
          <Text style={styles.sectionCardLabel}>{title}</Text>
          {optional && (
            <View style={styles.optionalTag}>
              <Text style={styles.optionalTagText}>Optionnel</Text>
            </View>
          )}
        </View>
      </View>
      <View style={styles.sectionCardBody}>{children}</View>
    </View>
  );
}

/* ─── Écran Principal ─── */
export default function CloseTicketScreen({ route, navigation }) {
  const { ticket } = route.params;
  const [report, setReport]       = useState('');
  const [duration, setDuration]   = useState('');
  const [signature, setSignature] = useState(null);
  const [loading, setLoading]     = useState(false);

  const [parts, setParts]                               = useState([]);
  const [availableParts, setAvailableParts]             = useState([]);
  const [selectedPart, setSelectedPart]                 = useState(null);
  const [partQuantity, setPartQuantity]                 = useState('1');
  const [modalVisible, setModalVisible]                 = useState(false);
  const [quantityModalVisible, setQuantityModalVisible] = useState(false);
  const [partSearch, setPartSearch]                     = useState('');

  useEffect(() => {
    loadParts();
    loadExistingParts();
  }, []);

  const loadExistingParts = () => {
    if (ticket.items?.length > 0) {
      setParts(
        ticket.items.map((item, index) => ({
          id: item.spare_part_id,
          name: item.spare_part?.name || 'Pièce',
          quantity: item.quantity,
          unit_price: item.unit_price,
          selling_price: item.unit_price,
          key: index.toString(),
        }))
      );
    }
  };

  const loadParts = async () => {
    try {
      const response = await api.get('/tech/parts');
      if (response.data.success) setAvailableParts(response.data.parts);
    } catch (error) {
      console.error(error);
    }
  };

  const openQuantityModal = (part) => {
    setSelectedPart(part);
    setPartQuantity('1');
    setQuantityModalVisible(true);
  };

  const addPart = () => {
    if (!selectedPart) return;
    const quantity = parseInt(partQuantity);
    if (isNaN(quantity) || quantity < 1) return;
    const existingIndex = parts.findIndex(p => p.id === selectedPart.id);
    if (existingIndex !== -1) {
      const updated = [...parts];
      updated[existingIndex].quantity += quantity;
      updated[existingIndex].unit_price = selectedPart.selling_price;
      setParts(updated);
    } else {
      setParts([...parts, {
        id: selectedPart.id,
        name: selectedPart.name,
        quantity,
        unit_price: selectedPart.selling_price,
        selling_price: selectedPart.selling_price,
        key: Date.now().toString(),
      }]);
    }
    setSelectedPart(null);
    setPartQuantity('1');
    setQuantityModalVisible(false);
  };

  const updatePartQuantity = (index, newQuantity) => {
    const updated = [...parts];
    updated[index].quantity = Math.max(1, parseInt(newQuantity) || 1);
    setParts(updated);
  };

  const removePart = (index) => {
    const newParts = [...parts];
    newParts.splice(index, 1);
    setParts(newParts);
  };

  const totalCost = parts.reduce((sum, p) => sum + (p.unit_price * p.quantity), 0);

  const submitReport = async () => {
    if (!report.trim()) {
      Alert.alert('Rapport manquant', 'Le rapport technique est obligatoire pour clôturer.');
      return;
    }
    const data = {
      technical_report: report,
      duration_minutes: duration.trim() !== '' ? parseInt(duration) : null, // ✅ null si vide
      signature,
      parts: parts.map(p => ({
        spare_part_id: p.id,
        quantity: p.quantity,
        unit_price: p.unit_price,
      })),
    };
    setLoading(true);
    const netState = await NetInfo.fetch();
    if (netState.isConnected) {
      try {
        await api.post(`/tech/tickets/${ticket.id}/close`, data);
        Alert.alert('Intervention clôturée ✓', 'Synchronisée avec le serveur.');
        navigation.goBack();
      } catch (error) {
        await savePendingReport(ticket.id, data);
        Alert.alert(
          'Enregistré localement',
          `Synchronisation échouée: ${error.response?.data?.message || error.message}`
        );
        navigation.goBack();
      }
    } else {
      await savePendingReport(ticket.id, data);
      Alert.alert('Mode hors-ligne', 'Rapport sauvegardé. Synchronisation automatique à la reconnexion.');
      navigation.goBack();
    }
    setLoading(false);
  };

  const filteredParts = availableParts.filter(
    p =>
      !parts.some(ex => ex.id === p.id) &&
      (!partSearch || p.name.toLowerCase().includes(partSearch.toLowerCase()))
  );

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#0d1f10" />

      {/* ── Header Premium ── */}
      <View style={styles.header}>
        <TouchableOpacity 
          style={styles.backBtn} 
          onPress={() => navigation.goBack()}
          activeOpacity={0.7}
        >
          <ChevronLeft size={20} color="#fff" strokeWidth={2.5} />
        </TouchableOpacity>
        <View style={styles.headerMid}>
          <Text style={styles.headerTitle}>Clôturer l'intervention</Text>
          <Text style={styles.headerSub}>#{ticket.ticket_number} · {ticket.customer?.name}</Text>
        </View>
      </View>

      <ScrollView
        style={styles.scroll}
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled"
      >
        {/* Section 1 : Rapport */}
        <SectionCard title="Rapport technique" icon={FileText}>
          <TextInput
            style={styles.textArea}
            multiline
            numberOfLines={5}
            value={report}
            onChangeText={setReport}
            placeholder="Décrivez l'intervention réalisée, les problèmes détectés et les actions effectuées..."
            placeholderTextColor="#9db8a4"
            textAlignVertical="top"
          />
          {!report.trim() && (
            <View style={styles.requiredRow}>
              <AlertTriangle size={13} color="#f07d00" strokeWidth={2.5} />
              <Text style={styles.requiredHint}>Champ obligatoire</Text>
            </View>
          )}
        </SectionCard>

        {/* Section 2 : Durée */}
        <SectionCard title="Durée de l'intervention" icon={Clock} optional>
          <View style={styles.durationRow}>
            <TextInput
              style={styles.durationInput}
              keyboardType="numeric"
              value={duration}
              onChangeText={setDuration}
              placeholder="90"
              placeholderTextColor="#9db8a4"
            />
            <View style={styles.durationUnit}>
              <Text style={styles.durationUnitText}>minutes</Text>
            </View>
          </View>
        </SectionCard>

        {/* Section 3 : Pièces */}
        <SectionCard title="Pièces utilisées" icon={Settings} optional>
          {parts.length > 0 && (
            <View style={styles.partsList}>
              {parts.map((part, idx) => (
                <View key={part.key || idx} style={styles.partItem}>
                  <View style={styles.partInfo}>
                    <Text style={styles.partName}>{part.name}</Text>
                    <Text style={styles.partUnitPrice}>{part.unit_price?.toLocaleString()} FCFA/u</Text>
                  </View>
                  <View style={styles.partControls}>
                    <TouchableOpacity
                      onPress={() => updatePartQuantity(idx, part.quantity - 1)}
                      style={styles.qtyBtn}
                      activeOpacity={0.6}
                    >
                      <Minus size={14} color="#1a7a3c" strokeWidth={3} />
                    </TouchableOpacity>
                    <Text style={styles.qtyValue}>{part.quantity}</Text>
                    <TouchableOpacity
                      onPress={() => updatePartQuantity(idx, part.quantity + 1)}
                      style={styles.qtyBtn}
                      activeOpacity={0.6}
                    >
                      <Plus size={14} color="#1a7a3c" strokeWidth={3} />
                    </TouchableOpacity>
                  </View>
                  <TouchableOpacity 
                    onPress={() => removePart(idx)} 
                    style={styles.removeBtn}
                    activeOpacity={0.6}
                  >
                    <X size={15} color="#ef4444" strokeWidth={2.5} />
                  </TouchableOpacity>
                </View>
              ))}
              <View style={styles.totalRow}>
                <Text style={styles.totalLabel}>Total pièces</Text>
                <Text style={styles.totalValue}>{totalCost.toLocaleString()} FCFA</Text>
              </View>
            </View>
          )}
          
          <TouchableOpacity 
            style={styles.addPartBtn} 
            onPress={() => setModalVisible(true)}
            activeOpacity={0.7}
          >
            <Plus size={16} color="#1a7a3c" strokeWidth={2.5} />
            <Text style={styles.addPartText}>Ajouter une pièce</Text>
          </TouchableOpacity>
        </SectionCard>

        {/* Section 4 : Signature */}
        <SectionCard title="Signature client" icon={PenTool} optional>
          <SignaturePad onSave={setSignature} />
        </SectionCard>

        {/* Bouton de validation Premium */}
        <TouchableOpacity
          style={[styles.submitBtn, (!report.trim() || loading) && styles.submitBtnDisabled]}
          onPress={submitReport}
          disabled={loading || !report.trim()}
          activeOpacity={0.85}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <>
              <Check size={18} color="#fff" strokeWidth={3} />
              <Text style={styles.submitText}>Valider l'intervention</Text>
            </>
          )}
        </TouchableOpacity>

        <Text style={styles.submitHint}>
          L'intervention sera synchronisée automatiquement à la détection du réseau.
        </Text>
      </ScrollView>

      {/* ── Modal Sélection Pièce ── */}
      <Modal
        animationType="slide"
        transparent
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalBackdrop}>
          <View style={styles.modal}>
            <View style={styles.modalHandle} />
            <Text style={styles.modalTitle}>Sélectionner une pièce</Text>

            <View style={styles.modalSearch}>
              <Search size={16} color="#9db8a4" strokeWidth={2} />
              <TextInput
                style={styles.modalSearchInput}
                placeholder="Rechercher une pièce..."
                placeholderTextColor="#9db8a4"
                value={partSearch}
                onChangeText={setPartSearch}
              />
            </View>

            <FlatList
              data={filteredParts}
              keyExtractor={item => item.id.toString()}
              style={styles.modalList}
              showsVerticalScrollIndicator={false}
              renderItem={({ item }) => (
                <TouchableOpacity
                  style={styles.modalItem}
                  onPress={() => { setModalVisible(false); openQuantityModal(item); }}
                  activeOpacity={0.7}
                >
                  <View style={styles.modalItemLeft}>
                    <Text style={styles.modalItemName}>{item.name}</Text>
                    <Text style={styles.modalItemStock}>Stock: {item.quantity_in_stock} unités</Text>
                  </View>
                  <View style={styles.modalItemRight}>
                    <Text style={styles.modalItemPrice}>{item.selling_price?.toLocaleString()}</Text>
                    <Text style={styles.modalItemCurrency}>FCFA</Text>
                  </View>
                </TouchableOpacity>
              )}
              ListEmptyComponent={
                <View style={styles.modalEmpty}>
                  <Package size={28} color="#cdedd6" style={{ marginBottom: 6 }} />
                  <Text style={styles.modalEmptyText}>Aucune pièce disponible</Text>
                </View>
              }
            />

            <TouchableOpacity 
              style={styles.modalCloseBtn} 
              onPress={() => setModalVisible(false)}
              activeOpacity={0.7}
            >
              <Text style={styles.modalCloseBtnText}>Fermer</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* ── Modal Quantité ── */}
      <Modal
        animationType="fade"
        transparent
        visible={quantityModalVisible}
        onRequestClose={() => setQuantityModalVisible(false)}
      >
        <View style={styles.qtyModalBackdrop}>
          <View style={styles.qtyModal}>
            <Text style={styles.qtyModalTitle}>{selectedPart?.name}</Text>
            <Text style={styles.qtyModalSub}>{selectedPart?.selling_price?.toLocaleString()} FCFA / unité</Text>

            <View style={styles.qtySelectorRow}>
              <TouchableOpacity
                onPress={() => setPartQuantity(Math.max(1, parseInt(partQuantity) - 1).toString())}
                style={styles.qtySelectorBtn}
                activeOpacity={0.6}
              >
                <Minus size={20} color="#1a7a3c" strokeWidth={2.5} />
              </TouchableOpacity>
              <Text style={styles.qtySelectorValue}>{partQuantity}</Text>
              <TouchableOpacity
                onPress={() => setPartQuantity((parseInt(partQuantity) + 1).toString())}
                style={styles.qtySelectorBtn}
                activeOpacity={0.6}
              >
                <Plus size={20} color="#1a7a3c" strokeWidth={2.5} />
              </TouchableOpacity>
            </View>

            <Text style={styles.qtyTotal}>
              Total : {((selectedPart?.selling_price || 0) * parseInt(partQuantity || 1)).toLocaleString()} FCFA
            </Text>

            <View style={styles.qtyModalBtns}>
              <TouchableOpacity
                style={styles.qtyCancelBtn}
                onPress={() => setQuantityModalVisible(false)}
                activeOpacity={0.7}
              >
                <Text style={styles.qtyCancelText}>Annuler</Text>
              </TouchableOpacity>
              <TouchableOpacity 
                style={styles.qtyConfirmBtn} 
                onPress={addPart}
                activeOpacity={0.8}
              >
                <Text style={styles.qtyConfirmText}>Ajouter</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#f4f7f5' },

  header: {
    backgroundColor: '#0d1f10',
    flexDirection: 'row',
    alignItems: 'center',
    paddingTop: Platform.OS === 'ios' ? 54 : 44,
    paddingHorizontal: 20,
    paddingBottom: 22,
    gap: 14,
  },
  backBtn: {
    width: 34, height: 34, borderRadius: 10,
    backgroundColor: 'rgba(255, 255, 255, 0.12)',
    alignItems: 'center', justifyContent: 'center',
  },
  headerMid: { flex: 1 },
  headerTitle: { fontSize: 16, fontWeight: '700', color: '#fff' },
  headerSub: { fontSize: 12, color: '#6aaa78', marginTop: 3 },

  scroll: { flex: 1 },
  scrollContent: { padding: 16, gap: 14, paddingBottom: 40 },

  sectionCard: {
    backgroundColor: '#fff',
    borderRadius: 14,
    borderWidth: 1,
    borderColor: '#e2eae4',
    overflow: 'hidden',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.015,
    shadowRadius: 4,
    elevation: 1,
  },
  sectionCardHeader: {
    paddingHorizontal: 16,
    paddingTop: 14,
    paddingBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f4f8f5',
  },
  sectionCardTitle: { flexDirection: 'row', alignItems: 'center', gap: 8 },
  sectionCardLabel: { fontSize: 13, fontWeight: '700', color: '#0a1f0e', flex: 1 },
  optionalTag: {
    backgroundColor: '#f0f5f1',
    paddingHorizontal: 8, paddingVertical: 3,
    borderRadius: 6,
  },
  optionalTagText: { fontSize: 10, color: '#7fa687', fontWeight: '700', textTransform: 'uppercase' },
  sectionCardBody: { padding: 16 },

  textArea: {
    minHeight: 110, fontSize: 14, color: '#0a1f0e', lineHeight: 21,
    backgroundColor: '#f8faf8', borderRadius: 10, padding: 12,
    borderWidth: 1, borderColor: '#e4ece6',
  },
  requiredRow: { flexDirection: 'row', alignItems: 'center', gap: 5, marginTop: 8 },
  requiredHint: { fontSize: 12, color: '#f07d00', fontWeight: '600' },

  durationRow: { flexDirection: 'row', gap: 12, alignItems: 'center' },
  durationInput: {
    flex: 1, borderWidth: 1, borderColor: '#e0ece4', borderRadius: 10,
    padding: 12, fontSize: 18, fontWeight: '700', color: '#0a1f0e',
    textAlign: 'center', backgroundColor: '#f8faf8',
  },
  durationUnit: {
    backgroundColor: '#eaf6ee', borderRadius: 10,
    paddingHorizontal: 16, paddingVertical: 14,
    borderWidth: 1, borderColor: '#d3edd9',
  },
  durationUnitText: { fontSize: 13, color: '#1a7a3c', fontWeight: '700' },

  partsList: { gap: 8, marginBottom: 12 },
  partItem: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#f8faf8', borderRadius: 10,
    padding: 10, gap: 10,
    borderWidth: 1, borderColor: '#e6ede8',
  },
  partInfo: { flex: 1 },
  partName: { fontSize: 13, fontWeight: '600', color: '#0a1f0e' },
  partUnitPrice: { fontSize: 11, color: '#9db8a4', marginTop: 2, fontWeight: '500' },
  partControls: { flexDirection: 'row', alignItems: 'center', gap: 10 },
  qtyBtn: {
    width: 26, height: 26, borderRadius: 8,
    backgroundColor: '#eaf6ee',
    alignItems: 'center', justifyContent: 'center',
    borderWidth: 1, borderColor: '#cdedd6',
  },
  qtyValue: { fontSize: 13, fontWeight: '700', color: '#0a1f0e', minWidth: 20, textAlign: 'center' },
  removeBtn: { width: 28, height: 28, alignItems: 'center', justifyContent: 'center', marginLeft: 4 },

  totalRow: {
    flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center',
    backgroundColor: '#eaf6ee', borderRadius: 8,
    paddingHorizontal: 12, paddingVertical: 10, marginTop: 4,
    borderWidth: 1, borderColor: '#cdedd6',
  },
  totalLabel: { fontSize: 13, fontWeight: '700', color: '#1a7a3c' },
  totalValue: { fontSize: 15, fontWeight: '800', color: '#1a7a3c' },

  addPartBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center',
    backgroundColor: '#eaf6ee', borderRadius: 10, paddingVertical: 12, gap: 8,
    borderWidth: 1.5, borderStyle: 'dashed', borderColor: '#1a7a3c50',
  },
  addPartText: { fontSize: 13, color: '#1a7a3c', fontWeight: '700' },

  submitBtn: {
    backgroundColor: '#1a7a3c', borderRadius: 14,
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center',
    paddingVertical: 15, gap: 8, marginTop: 6,
    shadowColor: '#1a7a3c',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.25, shadowRadius: 10, elevation: 5,
  },
  submitBtnDisabled: { backgroundColor: '#8bb397', shadowOpacity: 0.05, elevation: 0 },
  submitText: { fontSize: 15, fontWeight: '700', color: '#fff' },
  submitHint: { textAlign: 'center', fontSize: 11, color: '#9db8a4', fontWeight: '500', marginTop: -2 },

  /* Backdrop global */
  modalBackdrop: { flex: 1, backgroundColor: 'rgba(13, 31, 16, 0.45)', justifyContent: 'flex-end' },
  modal: {
    backgroundColor: '#fff', borderTopLeftRadius: 24, borderTopRightRadius: 24,
    paddingHorizontal: 20, paddingBottom: Platform.OS === 'ios' ? 34 : 24, maxHeight: '80%',
  },
  modalHandle: {
    width: 32, height: 4, backgroundColor: '#e4ece6',
    borderRadius: 2, alignSelf: 'center', marginVertical: 12,
  },
  modalTitle: { fontSize: 16, fontWeight: '700', color: '#0a1f0e', marginBottom: 14 },
  modalSearch: {
    flexDirection: 'row', alignItems: 'center',
    backgroundColor: '#f4f7f5', borderRadius: 10,
    paddingHorizontal: 12, gap: 8, marginBottom: 14,
    borderWidth: 1, borderColor: '#e6ede8',
  },
  modalSearchInput: { flex: 1, paddingVertical: 10, fontSize: 14, color: '#0a1f0e' },
  modalList: { maxHeight: 300 },
  modalItem: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between',
    paddingVertical: 12, borderBottomWidth: 1, borderBottomColor: '#f4f8f5',
  },
  modalItemLeft: { flex: 1, gap: 2 },
  modalItemName: { fontSize: 14, fontWeight: '600', color: '#0a1f0e' },
  modalItemStock: { fontSize: 11, color: '#9db8a4', fontWeight: '500' },
  modalItemRight: { flexDirection: 'row', alignItems: 'baseline', gap: 2 },
  modalItemPrice: { fontSize: 15, fontWeight: '700', color: '#1a7a3c' },
  modalItemCurrency: { fontSize: 10, color: '#7fa687', fontWeight: '600' },
  modalEmpty: { paddingVertical: 40, alignItems: 'center', justifyContent: 'center' },
  modalEmptyText: { fontSize: 13, color: '#9db8a4', fontWeight: '500' },
  modalCloseBtn: {
    marginTop: 14, backgroundColor: '#f4f7f5',
    borderRadius: 12, paddingVertical: 12, alignItems: 'center',
    borderWidth: 1, borderColor: '#e6ede8',
  },
  modalCloseBtnText: { fontSize: 14, fontWeight: '700', color: '#5a7a62' },

  /* Quantity overlay */
  qtyModalBackdrop: {
    flex: 1, backgroundColor: 'rgba(13, 31, 16, 0.45)',
    justifyContent: 'center', alignItems: 'center',
  },
  qtyModal: {
    backgroundColor: '#fff', borderRadius: 18,
    padding: 20, width: '85%', alignItems: 'center', gap: 10,
    shadowColor: '#000', shadowOffset: { width: 0, height: 10 },
    shadowOpacity: 0.1, shadowRadius: 12, elevation: 10,
  },
  qtyModalTitle: { fontSize: 15, fontWeight: '700', color: '#0a1f0e', textAlign: 'center' },
  qtyModalSub: { fontSize: 12, color: '#9db8a4', fontWeight: '500' },
  qtySelectorRow: { flexDirection: 'row', alignItems: 'center', gap: 24, marginVertical: 6 },
  qtySelectorBtn: {
    width: 44, height: 44, borderRadius: 12,
    backgroundColor: '#eaf6ee',
    alignItems: 'center', justifyContent: 'center',
    borderWidth: 1, borderColor: '#cdedd6',
  },
  qtySelectorValue: { fontSize: 28, fontWeight: '800', color: '#0a1f0e', minWidth: 44, textAlign: 'center' },
  qtyTotal: {
    fontSize: 13, fontWeight: '700', color: '#1a7a3c',
    backgroundColor: '#eaf6ee', paddingHorizontal: 14, paddingVertical: 6, borderRadius: 8,
    borderWidth: 1, borderColor: '#cdedd6', overflow: 'hidden',
  },
  qtyModalBtns: { flexDirection: 'row', gap: 10, width: '100%', marginTop: 6 },
  qtyCancelBtn: {
    flex: 1, paddingVertical: 12, borderRadius: 10,
    backgroundColor: '#f4f7f5', alignItems: 'center',
    borderWidth: 1, borderColor: '#e6ede8',
  },
  qtyCancelText: { fontSize: 14, fontWeight: '600', color: '#5a7a62' },
  qtyConfirmBtn: {
    flex: 1, paddingVertical: 12, borderRadius: 10,
    backgroundColor: '#1a7a3c', alignItems: 'center',
  },
  qtyConfirmText: { fontSize: 14, fontWeight: '700', color: '#fff' },

  /* Step lines */
  stepRow: { flexDirection: 'row', gap: 6, justifyContent: 'center', marginVertical: 8 },
  stepDot: { width: 6, height: 6, borderRadius: 3, backgroundColor: '#e2eae4' },
  stepDotActive: { backgroundColor: '#1a7a3c' },
  stepDotCurrent: { width: 16, backgroundColor: '#1a7a3c' },
});