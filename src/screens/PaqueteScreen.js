import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Modal,
  ScrollView,
  Alert,
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';

// Datos de solicitudes de ejemplo
const solicitudesIniciales = [
  {
    id: 1,
    numero: '001',
    estado: 'sin_contestar',
    fecha: '2025-01-10',
    solicitante: 'María González López',
    email: 'maria.gonzalez@email.com',
    ci: '12345678',
    direccion: 'Comunidad San José, Chiquitos',
    fechaTexto: '10 de Enero, 2025',
    productos: ['Agua potable', 'Alimentos', 'Frazadas'],
  },
  {
    id: 2,
    numero: '002',
    estado: 'aprobadas',
    fecha: '2025-01-09',
    solicitante: 'Carlos Rodríguez Vega',
    email: 'carlos.rodriguez@email.com',
    ci: '87654321',
    direccion: 'Comunidad El Carmen, Ñuflo de Chávez',
    fechaTexto: '9 de Enero, 2025',
    productos: ['Kit primeros auxilios', 'Medicamentos', 'Carpas'],
  },
  {
    id: 3,
    numero: '003',
    estado: 'rechazadas',
    fecha: '2025-01-08',
    solicitante: 'Ana María Silva',
    email: 'ana.silva@email.com',
    ci: '11223344',
    direccion: 'Comunidad Santa Ana, Velasco',
    fechaTexto: '8 de Enero, 2025',
    productos: ['Ropa', 'Calzado', 'Colchones'],
  },
  {
    id: 4,
    numero: '004',
    estado: 'sin_contestar',
    fecha: '2025-01-11',
    solicitante: 'Pedro Martínez Cruz',
    email: 'pedro.martinez@email.com',
    ci: '55667788',
    direccion: 'Comunidad San Pedro, Guarayos',
    fechaTexto: '11 de Enero, 2025',
    productos: ['Artículos de higiene', 'Utensilios de cocina', 'Linternas'],
  },
];

export default function PaqueteScreen() {
  const [filtroActivo, setFiltroActivo] = useState('todas');
  const [solicitudes, setSolicitudes] = useState(solicitudesIniciales);
  const [modalDetalleVisible, setModalDetalleVisible] = useState(false);
  const [modalRechazoVisible, setModalRechazoVisible] = useState(false);
  const [solicitudSeleccionada, setSolicitudSeleccionada] = useState(null);
  const [motivoRechazo, setMotivoRechazo] = useState('');
  const [motivoSeleccionado, setMotivoSeleccionado] = useState('');

  const filtros = [
    { id: 'todas', label: 'Todas', icon: 'list' },
    { id: 'recientes', label: 'Recientes', icon: 'clock' },
    { id: 'antiguas', label: 'Antiguas', icon: 'history' },
    { id: 'aprobadas', label: 'Aprobadas', icon: 'check' },
    { id: 'rechazadas', label: 'Rechazadas', icon: 'times' },
    { id: 'sin_contestar', label: 'Sin Contestar', icon: 'question' },
  ];

  const motivosRechazo = [
    { value: '', label: 'Seleccione un motivo...' },
    {
      value: 'informacion_incompleta',
      label:
        'La solicitud presenta información incompleta o inconsistente',
    },
    {
      value: 'destino_atendido',
      label:
        'El destino reportado ya fue atendido recientemente con recursos similares',
    },
    {
      value: 'cantidad_insuficiente',
      label:
        'La cantidad de personas afectadas es insuficiente para justificar la asignación de recursos',
    },
    {
      value: 'no_emergencia',
      label:
        'La situación reportada no califica como una emergencia según los criterios establecidos',
    },
  ];

  const filtrarSolicitudes = filtro => {
    setFiltroActivo(filtro);
  };

  const obtenerSolicitudesFiltradas = () => {
    if (filtroActivo === 'todas') {
      return solicitudes;
    } else if (filtroActivo === 'recientes') {
      return solicitudes.filter(
        s => s.fecha === '2025-01-11' || s.fecha === '2025-01-10',
      );
    } else if (filtroActivo === 'antiguas') {
      return solicitudes.filter(
        s => s.fecha === '2025-01-08' || s.fecha === '2025-01-09',
      );
    } else {
      return solicitudes.filter(s => s.estado === filtroActivo);
    }
  };

  const verDetalle = solicitud => {
    setSolicitudSeleccionada(solicitud);
    setModalDetalleVisible(true);
  };

  const aprobar = id => {
    setSolicitudes(prev =>
      prev.map(s =>
        s.id === id
          ? { ...s, estado: 'aprobadas' }
          : s,
      ),
    );
    Alert.alert(
      'Éxito',
      `Solicitud #${String(id).padStart(3, '0')} aprobada exitosamente`,
    );
  };

  const rechazar = id => {
    const solicitud = solicitudes.find(s => s.id === id);
    setSolicitudSeleccionada(solicitud);
    setMotivoRechazo('');
    setMotivoSeleccionado('');
    setModalRechazoVisible(true);
  };

  const actualizarMotivoSeleccionado = value => {
    setMotivoRechazo(value);
    if (value) {
      const motivo = motivosRechazo.find(m => m.value === value);
      setMotivoSeleccionado(motivo ? motivo.label : '');
    } else {
      setMotivoSeleccionado('');
    }
  };

  const confirmarRechazo = () => {
    if (!motivoRechazo) {
      Alert.alert('Error', 'Por favor selecciona un motivo de rechazo');
      return;
    }

    if (solicitudSeleccionada) {
      setSolicitudes(prev =>
        prev.map(s =>
          s.id === solicitudSeleccionada.id
            ? { ...s, estado: 'rechazadas' }
            : s,
        ),
      );
      Alert.alert(
        'Éxito',
        `Solicitud #${solicitudSeleccionada.numero} rechazada exitosamente`,
      );
      setModalRechazoVisible(false);
      setMotivoRechazo('');
      setMotivoSeleccionado('');
      setSolicitudSeleccionada(null);
    }
  };

  const obtenerColorBorde = estado => {
    switch (estado) {
      case 'aprobadas':
        return adminlteColors.success;
      case 'rechazadas':
        return adminlteColors.danger;
      case 'sin_contestar':
        return adminlteColors.secondary;
      default:
        return '#dee2e6';
    }
  };

  const obtenerBadgeColor = estado => {
    switch (estado) {
      case 'aprobadas':
        return adminlteColors.success;
      case 'rechazadas':
        return adminlteColors.danger;
      case 'sin_contestar':
        return adminlteColors.secondary;
      default:
        return adminlteColors.secondary;
    }
  };

  const obtenerBadgeTexto = estado => {
    switch (estado) {
      case 'aprobadas':
        return 'Aprobada';
      case 'rechazadas':
        return 'Rechazada';
      case 'sin_contestar':
        return 'Sin Contestar';
      default:
        return 'Sin Contestar';
    }
  };

  const solicitudesFiltradas = obtenerSolicitudesFiltradas();

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Listado de Solicitudes</Text>

      {/* Filtros */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <View style={styles.cardHeaderContent}>
            <FontAwesome5
              name="filter"
              size={16}
              color={adminlteColors.dark}
              style={{ marginRight: 8 }}
            />
            <Text style={styles.cardHeaderTitle}>Filtros</Text>
          </View>
        </View>
        <View style={styles.cardBody}>
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.filtrosContainer}
          >
            {filtros.map(filtro => (
              <TouchableOpacity
                key={filtro.id}
                style={[
                  styles.filtroButton,
                  filtroActivo === filtro.id && styles.filtroButtonActive,
                ]}
                onPress={() => filtrarSolicitudes(filtro.id)}
              >
                <FontAwesome5
                  name={filtro.icon}
                  size={14}
                  color={
                    filtroActivo === filtro.id ? '#ffffff' : adminlteColors.primary
                  }
                  style={{ marginRight: 6 }}
                />
                <Text
                  style={[
                    styles.filtroButtonText,
                    filtroActivo === filtro.id && styles.filtroButtonTextActive,
                  ]}
                >
                  {filtro.label}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>
        </View>
      </View>

      {/* Lista de Solicitudes */}
      <ScrollView style={styles.solicitudesContainer}>
        <View style={styles.solicitudesGrid}>
          {solicitudesFiltradas.map(solicitud => (
            <View
              key={solicitud.id}
              style={[
                styles.solicitudCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(solicitud.estado),
                },
              ]}
            >
              <View style={styles.solicitudCardHeader}>
                <View style={styles.solicitudCardHeaderContent}>
                  <FontAwesome5
                    name="file-alt"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudCardTitle}>
                    Solicitud #{solicitud.numero}
                  </Text>
                </View>
                <View
                  style={[
                    styles.badge,
                    {
                      backgroundColor: obtenerBadgeColor(solicitud.estado),
                    },
                  ]}
                >
                  <Text style={styles.badgeText}>
                    {obtenerBadgeTexto(solicitud.estado)}
                  </Text>
                </View>
              </View>

              <View style={styles.solicitudCardBody}>
                <View style={styles.solicitudInfoRow}>
                  <FontAwesome5
                    name="user"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudInfoLabel}>Solicitante:</Text>
                </View>
                <Text style={styles.solicitudInfoValue}>
                  {solicitud.solicitante}
                </Text>

                <View style={styles.solicitudInfoRow}>
                  <FontAwesome5
                    name="envelope"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudInfoLabel}>Email:</Text>
                </View>
                <Text style={styles.solicitudInfoValueMuted}>
                  {solicitud.email}
                </Text>

                <View style={styles.solicitudInfoRow}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudInfoLabel}>CI:</Text>
                </View>
                <Text style={styles.solicitudInfoValueMuted}>
                  {solicitud.ci}
                </Text>

                <View style={styles.solicitudInfoRow}>
                  <FontAwesome5
                    name="map-marker-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudInfoLabel}>Dirección:</Text>
                </View>
                <Text style={styles.solicitudInfoValueMuted}>
                  {solicitud.direccion}
                </Text>

                <View style={styles.solicitudInfoRow}>
                  <FontAwesome5
                    name="calendar"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudInfoLabel}>Fecha:</Text>
                </View>
                <Text style={styles.solicitudInfoValueMuted}>
                  {solicitud.fechaTexto}
                </Text>

                <View style={styles.solicitudInfoRow}>
                  <FontAwesome5
                    name="boxes"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitudInfoLabel}>Productos:</Text>
                </View>
                <View style={styles.productosContainer}>
                  {solicitud.productos.map((producto, index) => (
                    <View key={index} style={styles.productoBadge}>
                      <Text style={styles.productoBadgeText}>{producto}</Text>
                    </View>
                  ))}
                </View>
              </View>

              <View style={styles.solicitudCardFooter}>
                <TouchableOpacity
                  style={styles.btnVerDetalle}
                  onPress={() => verDetalle(solicitud)}
                >
                  <FontAwesome5
                    name="eye"
                    size={12}
                    color="#ffffff"
                    style={{ marginRight: 4 }}
                  />
                  <Text style={styles.btnVerDetalleText}>Ver Detalle</Text>
                </TouchableOpacity>

                {solicitud.estado === 'sin_contestar' && (
                  <>
                    <TouchableOpacity
                      style={styles.btnAprobar}
                      onPress={() => aprobar(solicitud.id)}
                    >
                      <FontAwesome5
                        name="check"
                        size={12}
                        color={adminlteColors.secondary}
                        style={{ marginRight: 4 }}
                      />
                      <Text style={styles.btnAprobarText}>Aprobar</Text>
                    </TouchableOpacity>

                    <TouchableOpacity
                      style={styles.btnRechazar}
                      onPress={() => rechazar(solicitud.id)}
                    >
                      <FontAwesome5
                        name="times"
                        size={12}
                        color="#ffffff"
                        style={{ marginRight: 4 }}
                      />
                      <Text style={styles.btnRechazarText}>Rechazar</Text>
                    </TouchableOpacity>
                  </>
                )}
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Detalle de Solicitud */}
      <Modal
        visible={modalDetalleVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalDetalleVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <View style={styles.modalHeaderContent}>
              <FontAwesome5
                name="file-alt"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Detalle de Solicitud</Text>
            </View>
            <TouchableOpacity
              onPress={() => setModalDetalleVisible(false)}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <ScrollView style={styles.modalBody}>
            {solicitudSeleccionada ? (
              <View style={styles.detalleContent}>
                <View style={styles.alertInfo}>
                  <Text style={styles.alertInfoTitle}>
                    Detalles de la Solicitud #{solicitudSeleccionada.numero}
                  </Text>
                  <Text style={styles.alertInfoText}>
                    Aquí se mostrarían todos los detalles completos de la
                    solicitud...
                  </Text>
                  <View style={styles.detalleSection}>
                    <Text style={styles.detalleLabel}>Solicitante:</Text>
                    <Text style={styles.detalleValue}>
                      {solicitudSeleccionada.solicitante}
                    </Text>
                  </View>
                  <View style={styles.detalleSection}>
                    <Text style={styles.detalleLabel}>Email:</Text>
                    <Text style={styles.detalleValue}>
                      {solicitudSeleccionada.email}
                    </Text>
                  </View>
                  <View style={styles.detalleSection}>
                    <Text style={styles.detalleLabel}>CI:</Text>
                    <Text style={styles.detalleValue}>
                      {solicitudSeleccionada.ci}
                    </Text>
                  </View>
                  <View style={styles.detalleSection}>
                    <Text style={styles.detalleLabel}>Dirección:</Text>
                    <Text style={styles.detalleValue}>
                      {solicitudSeleccionada.direccion}
                    </Text>
                  </View>
                  <View style={styles.detalleSection}>
                    <Text style={styles.detalleLabel}>Fecha:</Text>
                    <Text style={styles.detalleValue}>
                      {solicitudSeleccionada.fechaTexto}
                    </Text>
                  </View>
                  <View style={styles.detalleSection}>
                    <Text style={styles.detalleLabel}>Productos:</Text>
                    <View style={styles.productosContainer}>
                      {solicitudSeleccionada.productos.map((producto, index) => (
                        <View key={index} style={styles.productoBadge}>
                          <Text style={styles.productoBadgeText}>
                            {producto}
                          </Text>
                        </View>
                      ))}
                    </View>
                  </View>
                </View>
              </View>
            ) : (
              <Text>Cargando detalles...</Text>
            )}
          </ScrollView>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalDetalleVisible(false)}
            >
              <Text style={styles.modalFooterButtonText}>Cerrar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={styles.modalFooterButtonPrimary}
              onPress={() => {
                Alert.alert('Imprimir', 'Funcionalidad de impresión');
              }}
            >
              <Text style={styles.modalFooterButtonText}>Imprimir</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* Modal Confirmar Rechazo */}
      <Modal
        visible={modalRechazoVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalRechazoVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <Text style={styles.modalHeaderTitle}>Confirmar Rechazo</Text>
            <TouchableOpacity
              onPress={() => setModalRechazoVisible(false)}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <ScrollView style={styles.modalBody}>
            <Text style={styles.modalBodyText}>
              ¿Estás seguro que deseas rechazar la solicitud?
            </Text>

            <View style={styles.formGroup}>
              <Text style={styles.label}>Motivo del rechazo:</Text>
              <View style={styles.pickerWrapper}>
                <Picker
                  selectedValue={motivoRechazo}
                  onValueChange={actualizarMotivoSeleccionado}
                  style={styles.picker}
                >
                  {motivosRechazo.map((motivo, index) => (
                    <Picker.Item
                      key={index}
                      label={motivo.label}
                      value={motivo.value}
                    />
                  ))}
                </Picker>
              </View>
            </View>

            {motivoSeleccionado ? (
              <View style={styles.motivoSeleccionadoContainer}>
                <Text style={styles.motivoSeleccionadoText}>
                  Motivo seleccionado: {motivoSeleccionado}
                </Text>
              </View>
            ) : null}
          </ScrollView>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalRechazoVisible(false)}
            >
              <Text style={styles.modalFooterButtonText}>Cancelar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.modalFooterButtonDanger,
                !motivoRechazo && styles.modalFooterButtonDisabled,
              ]}
              onPress={confirmarRechazo}
              disabled={!motivoRechazo}
            >
              <Text style={styles.modalFooterButtonText}>Confirmar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </AdminLayout>
  );
}

const styles = StyleSheet.create({
  pageTitle: {
    fontSize: 22,
    fontWeight: '700',
    marginBottom: 12,
    color: adminlteColors.dark,
  },
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

