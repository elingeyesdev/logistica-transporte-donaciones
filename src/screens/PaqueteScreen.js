import React, { useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Modal, TextInput, ScrollView } from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';

// Datos iniciales de paquetes
const paquetesIniciales = [
  {
    id: 1,
    estadoEntrega: 'pendiente',
    ubicacionActual: 'Almacén Central',
    fechaCreacion: '2025-11-24',
    fechaEntrega: '',
  },
  {
    id: 2,
    estadoEntrega: 'en_transito',
    ubicacionActual: 'Ruta a Comunidad San José',
    fechaCreacion: '2025-11-23',
    fechaEntrega: '',
  },
];

export default function PaqueteScreen() {
  const [paquetes, setPaquetes] = useState(paquetesIniciales);
  const [modalVisible, setModalVisible] = useState(false);
  const [estadoEntrega, setEstadoEntrega] = useState('');
  const [ubicacionActual, setUbicacionActual] = useState('');
  const [fechaEntrega, setFechaEntrega] = useState(''); // Vacío inicialmente

  const resetForm = () => {
    setEstadoEntrega('');
    setUbicacionActual('');
    setFechaEntrega('');
  };

  const crearPaquete = () => {
    const nuevo = {
      id: Date.now(),
      estadoEntrega: estadoEntrega.trim(),
      ubicacionActual: ubicacionActual.trim(),
      fechaCreacion: new Date().toISOString().slice(0, 10),
      fechaEntrega: fechaEntrega.trim(), // puede quedar vacío
    };
    setPaquetes(prev => [nuevo, ...prev]);
    setModalVisible(false);
    resetForm();
  };

  const obtenerColorBorde = index => {
    const colors = [
      adminlteColors.primary,
      adminlteColors.success,
      adminlteColors.warning,
      adminlteColors.info,
      adminlteColors.secondary,
      adminlteColors.danger,
    ];
    return colors[index % colors.length];
  };

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Gestión de Paquetes</Text>

      {/* Card Crear Paquete */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <View style={styles.cardHeaderContent}>
            <FontAwesome5 name="box" size={16} color={adminlteColors.dark} style={{ marginRight: 8 }} />
            <Text style={styles.cardHeaderTitle}>Crear Paquete</Text>
          </View>
          <TouchableOpacity style={styles.btnCrear} onPress={() => setModalVisible(true)}>
            <FontAwesome5 name="plus" size={12} color="#ffffff" style={{ marginRight: 6 }} />
            <Text style={styles.btnCrearText}>Nuevo</Text>
          </TouchableOpacity>
        </View>
        <View style={styles.cardBody}>
          <Text style={styles.cardBodyHelper}>Administra los paquetes y su estado de entrega.</Text>
        </View>
      </View>

      {/* Lista de Paquetes */}
      <ScrollView style={styles.listaContainer}>
        <View style={styles.grid}>
          {paquetes.map((p, idx) => (
            <View
              key={p.id}
              style={[
                styles.itemCard,
                { borderTopWidth: 3, borderTopColor: obtenerColorBorde(idx) },
              ]}
            >
              <View style={styles.itemHeader}>
                <View style={styles.itemHeaderContent}>
                  <FontAwesome5 name="box" size={14} color={adminlteColors.dark} style={{ marginRight: 6 }} />
                  <Text style={styles.itemTitle}>Paquete #{String(p.id).slice(-4)}</Text>
                </View>
              </View>
              <View style={styles.itemBody}>
                <View style={styles.row}>
                  <FontAwesome5 name="shipping-fast" size={12} color={adminlteColors.primary} style={{ marginRight: 6 }} />
                  <Text style={styles.label}>Estado de Entrega:</Text>
                </View>
                <Text style={styles.valuePrimary}>{p.estadoEntrega || '—'}</Text>

                <View style={styles.row}>
                  <FontAwesome5 name="map-marker-alt" size={12} color={adminlteColors.muted} style={{ marginRight: 6 }} />
                  <Text style={styles.label}>Ubicación Actual:</Text>
                </View>
                <Text style={styles.valueMuted}>{p.ubicacionActual || '—'}</Text>

                <View style={styles.row}>
                  <FontAwesome5 name="calendar-plus" size={12} color={adminlteColors.muted} style={{ marginRight: 6 }} />
                  <Text style={styles.label}>Fecha Creación:</Text>
                </View>
                <Text style={styles.valueMuted}>{p.fechaCreacion}</Text>

                <View style={styles.row}>
                  <FontAwesome5 name="calendar-check" size={12} color={adminlteColors.muted} style={{ marginRight: 6 }} />
                  <Text style={styles.label}>Fecha Entrega:</Text>
                </View>
                <Text style={styles.valueMuted}>{p.fechaEntrega || '(vacío)'}</Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Paquete */}
      <Modal
        visible={modalVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => { setModalVisible(false); resetForm(); }}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <View style={styles.modalHeaderContent}>
              <FontAwesome5 name="box" size={18} color="#ffffff" style={{ marginRight: 8 }} />
              <Text style={styles.modalHeaderTitle}>Nuevo Paquete</Text>
            </View>
            <TouchableOpacity onPress={() => { setModalVisible(false); resetForm(); }} style={styles.modalCloseButton}>
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>
          <ScrollView style={styles.modalBody}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Estado de Entrega *</Text>
              <TextInput
                style={styles.input}
                placeholder="pendiente / en_transito / entregado"
                value={estadoEntrega}
                onChangeText={setEstadoEntrega}
                placeholderTextColor={adminlteColors.muted}
              />
            </View>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Ubicación Actual *</Text>
              <TextInput
                style={styles.input}
                placeholder="Ej: Almacén Central"
                value={ubicacionActual}
                onChangeText={setUbicacionActual}
                placeholderTextColor={adminlteColors.muted}
              />
            </View>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Fecha Entrega (opcional)</Text>
              <TextInput
                style={styles.input}
                placeholder="YYYY-MM-DD (o dejar vacío)"
                value={fechaEntrega}
                onChangeText={setFechaEntrega}
                placeholderTextColor={adminlteColors.muted}
              />
            </View>
            <View style={styles.helperBox}>
              <Text style={styles.helperText}>La fecha de creación se asignará automáticamente al guardar.</Text>
            </View>
          </ScrollView>
          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => { setModalVisible(false); resetForm(); }}
            >
              <Text style={styles.modalFooterButtonText}>Cancelar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.modalFooterButtonPrimary,
                (!estadoEntrega.trim() || !ubicacionActual.trim()) && styles.modalFooterButtonDisabled,
              ]}
              disabled={!estadoEntrega.trim() || !ubicacionActual.trim()}
              onPress={crearPaquete}
            >
              <Text style={styles.modalFooterButtonText}>Crear</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </AdminLayout>
  );
}

const styles = StyleSheet.create({
  pageTitle: { fontSize: 22, fontWeight: '700', marginBottom: 12, color: adminlteColors.dark },
  listaContainer: { flex: 1 },
  grid: { flexDirection: 'row', flexWrap: 'wrap', gap: 12 },
  itemCard: { width: '100%', backgroundColor: adminlteColors.cardBg, borderRadius: 8, marginBottom: 16, elevation: 3, overflow: 'hidden' },
  itemHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', paddingHorizontal: 12, paddingVertical: 10, borderBottomWidth: 1, borderBottomColor: '#dee2e6' },
  itemHeaderContent: { flexDirection: 'row', alignItems: 'center', flex: 1 },
  itemTitle: { fontSize: 15, fontWeight: '600', color: adminlteColors.dark },
  itemBody: { padding: 12 },
  row: { flexDirection: 'row', alignItems: 'center', marginBottom: 4 },
  label: { fontSize: 13, fontWeight: '600', color: adminlteColors.dark },
  valuePrimary: { fontSize: 13, color: adminlteColors.primary, marginBottom: 8, marginLeft: 20 },
  valueMuted: { fontSize: 13, color: adminlteColors.muted, marginBottom: 8, marginLeft: 20 },
  card: { backgroundColor: adminlteColors.cardBg, borderRadius: 8, padding: 12, elevation: 3, marginBottom: 16 },
  cardHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 12 },
  cardHeaderContent: { flexDirection: 'row', alignItems: 'center' },
  cardHeaderTitle: { fontSize: 16, fontWeight: '600', color: adminlteColors.dark },
  cardBody: { paddingTop: 4 },
  cardBodyHelper: { fontSize: 13, color: adminlteColors.muted },
  btnCrear: { flexDirection: 'row', alignItems: 'center', backgroundColor: adminlteColors.primary, paddingHorizontal: 12, paddingVertical: 8, borderRadius: 4 },
  btnCrearText: { color: '#ffffff', fontSize: 13, fontWeight: '500' },
  // Modal
  modalContainer: { flex: 1, backgroundColor: adminlteColors.bodyBg },
  modalHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', backgroundColor: adminlteColors.primary, paddingHorizontal: 16, paddingVertical: 12 },
  modalHeaderContent: { flexDirection: 'row', alignItems: 'center', flex: 1 },
  modalHeaderTitle: { fontSize: 18, fontWeight: '600', color: '#ffffff' },
  modalCloseButton: { padding: 4 },
  modalBody: { flex: 1, padding: 16 },
  formGroup: { marginBottom: 16 },
  input: { borderWidth: 1, borderColor: '#ced4da', borderRadius: 4, paddingHorizontal: 12, paddingVertical: 10, fontSize: 14, backgroundColor: '#ffffff', color: adminlteColors.dark },
  helperBox: { backgroundColor: '#f8f9fa', borderRadius: 4, padding: 12, marginBottom: 16 },
  helperText: { fontSize: 12, color: adminlteColors.muted },
  modalFooter: { flexDirection: 'row', justifyContent: 'flex-end', paddingHorizontal: 16, paddingVertical: 12, backgroundColor: adminlteColors.primary, gap: 8 },
  modalFooterButtonSecondary: { backgroundColor: adminlteColors.secondary, paddingHorizontal: 16, paddingVertical: 8, borderRadius: 4 },
  modalFooterButtonPrimary: { backgroundColor: adminlteColors.primary, paddingHorizontal: 16, paddingVertical: 8, borderRadius: 4 },
  modalFooterButtonDisabled: { opacity: 0.5 },
  modalFooterButtonText: { color: '#ffffff', fontSize: 14, fontWeight: '500' },
  card: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    padding: 12,
    elevation: 3,
    marginBottom: 16,
  },
  cardHeader: {
    marginBottom: 12,
  },
  cardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  cardHeaderTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  cardBody: {
    paddingTop: 8,
  },
  filtrosContainer: {
    flexDirection: 'row',
    gap: 8,
  },
  filtroButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: adminlteColors.primary,
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 4,
    marginRight: 8,
  },
  filtroButtonActive: {
    backgroundColor: adminlteColors.primary,
    borderColor: adminlteColors.primary,
  },
  filtroButtonText: {
    fontSize: 13,
    color: adminlteColors.primary,
    fontWeight: '500',
  },
  filtroButtonTextActive: {
    color: '#ffffff',
  },
  solicitudesContainer: {
    flex: 1,
  },
  solicitudesGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  solicitudCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  solicitudCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  solicitudCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  solicitudCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  badge: {
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
  },
  badgeText: {
    color: '#ffffff',
    fontSize: 11,
    fontWeight: '600',
  },
  solicitudCardBody: {
    padding: 12,
  },
  solicitudInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  solicitudInfoLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  solicitudInfoValue: {
    fontSize: 13,
    color: adminlteColors.primary,
    marginBottom: 8,
    marginLeft: 20,
  },
  solicitudInfoValueMuted: {
    fontSize: 13,
    color: adminlteColors.muted,
    marginBottom: 8,
    marginLeft: 20,
  },
  productosContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginTop: 4,
    marginLeft: 20,
  },
  productoBadge: {
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    marginRight: 6,
    marginBottom: 6,
  },
  productoBadgeText: {
    color: '#ffffff',
    fontSize: 11,
  },
  solicitudCardFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-start',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderTopWidth: 1,
    borderTopColor: '#dee2e6',
    gap: 8,
    flexWrap: 'wrap',
  },
  btnVerDetalle: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
  },
  btnVerDetalleText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '500',
  },
  btnAprobar: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: adminlteColors.secondary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
  },
  btnAprobarText: {
    color: adminlteColors.secondary,
    fontSize: 12,
    fontWeight: '500',
  },
  btnRechazar: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
  },
  btnRechazarText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '500',
  },
  // Modal styles
  modalContainer: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  modalHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  modalHeaderTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#ffffff',
  },
  modalCloseButton: {
    padding: 4,
  },
  modalBody: {
    flex: 1,
    padding: 16,
  },
  modalBodyText: {
    fontSize: 14,
    color: adminlteColors.dark,
    marginBottom: 16,
  },
  detalleContent: {
    flex: 1,
  },
  alertInfo: {
    backgroundColor: '#d1ecf1',
    borderRadius: 4,
    padding: 12,
    marginBottom: 16,
  },
  alertInfoTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#0c5460',
    marginBottom: 8,
  },
  alertInfoText: {
    fontSize: 14,
    color: '#0c5460',
    marginBottom: 12,
  },
  detalleSection: {
    marginBottom: 12,
  },
  detalleLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 4,
  },
  detalleValue: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  formGroup: {
    marginBottom: 16,
  },
  label: {
    fontSize: 13,
    fontWeight: '500',
    marginBottom: 8,
    color: adminlteColors.dark,
  },
  pickerWrapper: {
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    overflow: 'hidden',
    backgroundColor: '#ffffff',
  },
  picker: {
    height: 50,
  },
  motivoSeleccionadoContainer: {
    marginTop: 12,
    padding: 12,
    backgroundColor: '#f8f9fa',
    borderRadius: 4,
  },
  motivoSeleccionadoText: {
    fontSize: 13,
    color: adminlteColors.dark,
  },
  modalFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: adminlteColors.primary,
    gap: 8,
  },
  modalFooterButtonSecondary: {
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  modalFooterButtonPrimary: {
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  modalFooterButtonDanger: {
    backgroundColor: adminlteColors.danger,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  modalFooterButtonDisabled: {
    opacity: 0.5,
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },
});

