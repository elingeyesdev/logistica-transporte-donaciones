import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Modal,
  ScrollView,
  TextInput,
  Alert,
  ActivityIndicator,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import * as tipoVehiculoService from '../services/tipoVehiculoService';

export default function TipoVehiculoScreen() {
  const [tiposVehiculo, setTiposVehiculo] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    nombre_tipo_vehiculo: '',
  });

  useEffect(() => {
    cargarTiposVehiculo();
  }, []);

  const cargarTiposVehiculo = async () => {
    setLoading(true);
    try {
      const data = await tipoVehiculoService.getTiposVehiculo();
      setTiposVehiculo(data);
    } catch (error) {
      Alert.alert('Error', 'No se pudieron cargar los tipos de vehículo');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearTipoVehiculo = async () => {
    if (!formData.nombre_tipo_vehiculo.trim()) {
      Alert.alert('Error', 'Por favor completa el campo');
      return;
    }

    setLoading(true);
    try {
      await tipoVehiculoService.createTipoVehiculo(formData);
      Alert.alert('Éxito', 'Tipo de Vehículo creado exitosamente');
      setFormData({ nombre_tipo_vehiculo: '' });
      setModalCrearVisible(false);
      await cargarTiposVehiculo();
    } catch (error) {
      Alert.alert('Error', 'Error de conexión con el servidor');
    } finally {
      setLoading(false);
    }
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
      <Text style={styles.pageTitle}>Gestión de Tipos de Vehículo</Text>

      {/* Botón Crear Tipo de Vehículo */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>
            Tipos de Vehículo Registrados
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
            <Text style={styles.btnCrearText}>Crear</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Lista de Tipos de Vehículo */}
      <ScrollView style={styles.tiposVehiculoContainer}>
        {loading ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <ActivityIndicator size="large" color={adminlteColors.primary} />
            <Text style={{ marginTop: 10, color: adminlteColors.muted }}>
              Cargando tipos de vehículo...
            </Text>
          </View>
        ) : tiposVehiculo.length === 0 ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <Text style={{ color: adminlteColors.muted }}>
              No hay tipos de vehículo registrados
            </Text>
          </View>
        ) : (
          <View style={styles.tiposVehiculoGrid}>
            {tiposVehiculo.map((tipoVehiculo, index) => (
              <View
                key={tipoVehiculo.id ? `tipo-vehiculo-${tipoVehiculo.id}` : `tipo-vehiculo-index-${index}`}
                style={[
                styles.tipoVehiculoCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.tipoVehiculoCardHeader}>
                <View style={styles.tipoVehiculoCardHeaderContent}>
                  <FontAwesome5
                    name="truck-monster"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.tipoVehiculoCardTitle}>
                    Tipo #{String(index + 1).padStart(3, '0')}
                  </Text>
                </View>
              </View>

              <View style={styles.tipoVehiculoCardBody}>
                <View style={styles.tipoVehiculoInfoRow}>
                  <FontAwesome5
                    name="car-side"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.tipoVehiculoInfoLabel}>Nombre Tipo Vehículo:</Text>
                </View>
                <Text style={styles.tipoVehiculoInfoValue}>
                  {tipoVehiculo.nombre_tipo_vehiculo}
                </Text>
              </View>
            </View>
            ))}
          </View>
        )}
      </ScrollView>

      {/* Modal Crear Tipo de Vehículo (overlay centrado) */}
      <Modal
        visible={modalCrearVisible}
        animationType="fade"
        transparent={true}
        onRequestClose={() => setModalCrearVisible(false)}
      >
        <View style={styles.overlayBackdrop}>
          <View style={styles.modalCard}>
            <View style={styles.modalHeaderCard}>
              <View style={styles.modalHeaderContent}>
                <FontAwesome5
                  name="plus-circle"
                  size={18}
                  color="#ffffff"
                  style={{ marginRight: 8 }}
                />
                <Text style={styles.modalHeaderTitle}>Crear Nuevo Tipo de Vehículo</Text>
              </View>
              <TouchableOpacity
                onPress={() => setModalCrearVisible(false)}
                style={styles.modalCloseButton}
              >
                <MaterialIcons name="close" size={24} color="#ffffff" />
              </TouchableOpacity>
            </View>

            <ScrollView style={styles.modalBodyCard}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Nombre Tipo Vehículo <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Camioneta doble cabina"
                value={formData.nombre_tipo_vehiculo}
                onChangeText={text => handleChange('nombre_tipo_vehiculo', text)}
              />
            </View>
            </ScrollView>

            <View style={styles.modalFooterCard}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalCrearVisible(false)}
            >
              <Text style={styles.modalFooterButtonText}>Cancelar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.modalFooterButtonSuccess,
                !formData.nombre_tipo_vehiculo.trim() &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearTipoVehiculo}
              disabled={!formData.nombre_tipo_vehiculo.trim()}
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
                <Text style={styles.modalFooterButtonText}>Crear</Text>
            </TouchableOpacity>
            </View>
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
  tiposVehiculoContainer: {
    flex: 1,
    marginBottom: 16,
  },
  tiposVehiculoGrid: {
    flexDirection: 'column',
  },
  tipoVehiculoCard: {
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
  tipoVehiculoCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  tipoVehiculoCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  tipoVehiculoCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  tipoVehiculoCardBody: {
    padding: 10,
  },
  tipoVehiculoInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  tipoVehiculoInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  tipoVehiculoInfoValue: {
    fontSize: 12,
    color: adminlteColors.dark,
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
  modalFooterCard: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingHorizontal: 18,
    paddingVertical: 14,
    backgroundColor: '#ffffff',
    borderTopWidth: 1,
    borderTopColor: adminlteColors.border,
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
    opacity: 0.5,
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '600',
  },
  overlayBackdrop: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.7)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  modalCard: {
    width: '92%',
    maxHeight: '90%',
    backgroundColor: '#ffffff',
    borderRadius: 12,
    overflow: 'hidden',
    elevation: 6,
  },
  modalHeaderCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 18,
    paddingVertical: 14,
    justifyContent: 'space-between',
  },
  modalBodyCard: {
    paddingHorizontal: 18,
    paddingVertical: 16,
  },
});
