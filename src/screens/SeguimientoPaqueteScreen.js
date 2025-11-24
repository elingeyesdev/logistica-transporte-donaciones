import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Modal,
  ScrollView,
  TextInput,
  Alert,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';

const seguimientosIniciales = [
  {
    id: 1,
    ciUsuario: '12345678',
    estado: 'En Tránsito',
    fechaActualizacion: '20/11/2024',
    imagenEvidencia: 'https://example.com/img1.jpg',
    idPaquete: 'PKG001',
    idUbicacion: 'UB001',
  },
  {
    id: 2,
    ciUsuario: '87654321',
    estado: 'Entregado',
    fechaActualizacion: '21/11/2024',
    imagenEvidencia: 'https://example.com/img2.jpg',
    idPaquete: 'PKG002',
    idUbicacion: 'UB002',
  },
  {
    id: 3,
    ciUsuario: '11223344',
    estado: 'Pendiente',
    fechaActualizacion: '22/11/2024',
    imagenEvidencia: 'https://example.com/img3.jpg',
    idPaquete: 'PKG003',
    idUbicacion: 'UB003',
  },
];

export default function SeguimientoPaqueteScreen() {
  const [seguimientos, setSeguimientos] = useState(seguimientosIniciales);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    ciUsuario: '',
    estado: '',
    fechaActualizacion: '',
    imagenEvidencia: '',
    idPaquete: '',
    idUbicacion: '',
  });

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearSeguimiento = () => {
    if (
      !formData.ciUsuario.trim() ||
      !formData.estado.trim() ||
      !formData.fechaActualizacion.trim() ||
      !formData.imagenEvidencia.trim() ||
      !formData.idPaquete.trim() ||
      !formData.idUbicacion.trim()
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    const nuevoSeguimiento = {
      id: Date.now(),
      ...formData,
    };

    setSeguimientos(prev => [nuevoSeguimiento, ...prev]);
    setFormData({
      ciUsuario: '',
      estado: '',
      fechaActualizacion: '',
      imagenEvidencia: '',
      idPaquete: '',
      idUbicacion: '',
    });
    setModalCrearVisible(false);
    Alert.alert('Éxito', 'Seguimiento creado exitosamente');
  };

  const obtenerColorBorde = index => {
    const colores = [
      adminlteColors.primary,
      adminlteColors.success,
      adminlteColors.info,
      adminlteColors.warning,
      adminlteColors.danger,
      adminlteColors.secondary,
    ];
    return colores[index % colores.length];
  };

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Seguimiento de Paquetes</Text>

      {/* Botón Crear Seguimiento */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>
            Listado de Seguimientos Registrados
          </Text>
          <TouchableOpacity
            style={styles.btnCrear}
            onPress={() => setModalCrearVisible(true)}
          >
            <FontAwesome5
              name="plus"
              size={14}
              color="#ffffff"
              style={{ marginRight: 6 }}
            />
            <Text style={styles.btnCrearText}>Crear Seguimiento</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Lista de Seguimientos */}
      <ScrollView style={styles.seguimientosContainer}>
        <View style={styles.seguimientosGrid}>
          {seguimientos.map((seguimiento, index) => (
            <View
              key={seguimiento.id}
              style={[
                styles.seguimientoCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.seguimientoCardHeader}>
                <View style={styles.seguimientoCardHeaderContent}>
                  <FontAwesome5
                    name="shipping-fast"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoCardTitle}>
                    {seguimiento.idPaquete}
                  </Text>
                </View>
              </View>

              <View style={styles.seguimientoCardBody}>
                <View style={styles.seguimientoInfoRow}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoInfoLabel}>CI Usuario:</Text>
                </View>
                <Text style={styles.seguimientoInfoValue}>
                  {seguimiento.ciUsuario}
                </Text>

                <View style={styles.seguimientoInfoRow}>
                  <FontAwesome5
                    name="flag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoInfoLabel}>Estado:</Text>
                </View>
                <Text style={styles.seguimientoInfoValueMuted}>
                  {seguimiento.estado}
                </Text>

                <View style={styles.seguimientoInfoRow}>
                  <FontAwesome5
                    name="calendar-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoInfoLabel}>
                    Fecha Actualización:
                  </Text>
                </View>
                <Text style={styles.seguimientoInfoValueMuted}>
                  {seguimiento.fechaActualizacion}
                </Text>

                <View style={styles.seguimientoInfoRow}>
                  <FontAwesome5
                    name="image"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoInfoLabel}>
                    Imagen Evidencia:
                  </Text>
                </View>
                <Text
                  style={styles.seguimientoInfoValueMuted}
                  numberOfLines={1}
                  ellipsizeMode="middle"
                >
                  {seguimiento.imagenEvidencia}
                </Text>

                <View style={styles.seguimientoInfoRow}>
                  <FontAwesome5
                    name="box"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoInfoLabel}>ID Paquete:</Text>
                </View>
                <Text style={styles.seguimientoInfoValueMuted}>
                  {seguimiento.idPaquete}
                </Text>

                <View style={styles.seguimientoInfoRow}>
                  <FontAwesome5
                    name="map-marker-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.seguimientoInfoLabel}>ID Ubicación:</Text>
                </View>
                <Text style={styles.seguimientoInfoValueMuted}>
                  {seguimiento.idUbicacion}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Seguimiento */}
      <Modal
        visible={modalCrearVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalCrearVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <View style={styles.modalHeaderContent}>
              <FontAwesome5
                name="plus-circle"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Seguimiento</Text>
            </View>
            <TouchableOpacity
              onPress={() => setModalCrearVisible(false)}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <ScrollView style={styles.modalBody}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>
                CI Usuario <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 12345678"
                value={formData.ciUsuario}
                onChangeText={text => handleChange('ciUsuario', text)}
                keyboardType="numeric"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Estado <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. En Tránsito"
                value={formData.estado}
                onChangeText={text => handleChange('estado', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Fecha Actualización <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 24/11/2024"
                value={formData.fechaActualizacion}
                onChangeText={text => handleChange('fechaActualizacion', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Imagen Evidencia <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. https://example.com/imagen.jpg"
                value={formData.imagenEvidencia}
                onChangeText={text => handleChange('imagenEvidencia', text)}
                autoCapitalize="none"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                ID Paquete <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. PKG004"
                value={formData.idPaquete}
                onChangeText={text => handleChange('idPaquete', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                ID Ubicación <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. UB004"
                value={formData.idUbicacion}
                onChangeText={text => handleChange('idUbicacion', text)}
              />
            </View>
          </ScrollView>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalCrearVisible(false)}
            >
              <Text style={styles.modalFooterButtonText}>Cancelar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.modalFooterButtonSuccess,
                (!formData.ciUsuario.trim() ||
                  !formData.estado.trim() ||
                  !formData.fechaActualizacion.trim() ||
                  !formData.imagenEvidencia.trim() ||
                  !formData.idPaquete.trim() ||
                  !formData.idUbicacion.trim()) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearSeguimiento}
              disabled={
                !formData.ciUsuario.trim() ||
                !formData.estado.trim() ||
                !formData.fechaActualizacion.trim() ||
                !formData.imagenEvidencia.trim() ||
                !formData.idPaquete.trim() ||
                !formData.idUbicacion.trim()
              }
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Seguimiento</Text>
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
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  cardHeaderTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  btnCrear: {
    backgroundColor: adminlteColors.primary,
    borderRadius: 6,
    paddingVertical: 8,
    paddingHorizontal: 14,
    flexDirection: 'row',
    alignItems: 'center',
  },
  btnCrearText: {
    color: '#ffffff',
    fontSize: 13,
    fontWeight: '600',
  },
  seguimientosContainer: {
    flex: 1,
    marginBottom: 16,
  },
  seguimientosGrid: {
    flexDirection: 'column',
  },
  seguimientoCard: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    width: '100%',
    marginBottom: 12,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.15,
    shadowRadius: 3,
  },
  seguimientoCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  seguimientoCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  seguimientoCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  seguimientoCardBody: {
    padding: 10,
  },
  seguimientoInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  seguimientoInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  seguimientoInfoValue: {
    fontSize: 12,
    color: adminlteColors.dark,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 18,
  },
  seguimientoInfoValueMuted: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 18,
  },
  modalContainer: {
    flex: 1,
    backgroundColor: adminlteColors.lightBg,
  },
  modalHeader: {
    backgroundColor: adminlteColors.primary,
    paddingVertical: 16,
    paddingHorizontal: 16,
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    elevation: 4,
  },
  modalHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  modalHeaderTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: '#ffffff',
  },
  modalCloseButton: {
    padding: 4,
  },
  modalBody: {
    flex: 1,
    padding: 16,
  },
  formGroup: {
    marginBottom: 16,
  },
  label: {
    fontSize: 14,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 6,
  },
  required: {
    color: adminlteColors.danger,
  },
  input: {
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 6,
    paddingHorizontal: 12,
    paddingVertical: 10,
    fontSize: 14,
    backgroundColor: '#ffffff',
    color: adminlteColors.dark,
  },
  modalFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderTopWidth: 1,
    borderTopColor: '#e0e0e0',
    backgroundColor: '#ffffff',
  },
  modalFooterButtonSecondary: {
    backgroundColor: adminlteColors.secondary,
    borderRadius: 6,
    paddingVertical: 10,
    paddingHorizontal: 16,
    marginRight: 8,
  },
  modalFooterButtonSuccess: {
    backgroundColor: adminlteColors.success,
    borderRadius: 6,
    paddingVertical: 10,
    paddingHorizontal: 16,
    flexDirection: 'row',
    alignItems: 'center',
  },
  modalFooterButtonDisabled: {
    backgroundColor: '#cccccc',
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '600',
  },
});
