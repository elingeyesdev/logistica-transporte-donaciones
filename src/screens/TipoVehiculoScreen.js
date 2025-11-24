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

const tiposVehiculoIniciales = [
  {
    id: 1,
    nombreTipoVehiculo: 'Camioneta',
  },
  {
    id: 2,
    nombreTipoVehiculo: 'Camión',
  },
  {
    id: 3,
    nombreTipoVehiculo: 'Van',
  },
  {
    id: 4,
    nombreTipoVehiculo: 'Automóvil',
  },
];

export default function TipoVehiculoScreen() {
  const [tiposVehiculo, setTiposVehiculo] = useState(tiposVehiculoIniciales);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    nombreTipoVehiculo: '',
  });

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearTipoVehiculo = () => {
    if (!formData.nombreTipoVehiculo.trim()) {
      Alert.alert('Error', 'Por favor completa el campo');
      return;
    }

    const nuevoTipoVehiculo = {
      id: Date.now(),
      ...formData,
    };

    setTiposVehiculo(prev => [nuevoTipoVehiculo, ...prev]);
    setFormData({
      nombreTipoVehiculo: '',
    });
    setModalCrearVisible(false);
    Alert.alert('Éxito', 'Tipo de Vehículo creado exitosamente');
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
            Listado de Tipos de Vehículo Registrados
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
            <Text style={styles.btnCrearText}>Crear Tipo Vehículo</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Lista de Tipos de Vehículo */}
      <ScrollView style={styles.tiposVehiculoContainer}>
        <View style={styles.tiposVehiculoGrid}>
          {tiposVehiculo.map((tipoVehiculo, index) => (
            <View
              key={tipoVehiculo.id}
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
                  {tipoVehiculo.nombreTipoVehiculo}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Tipo de Vehículo */}
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
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Tipo de Vehículo</Text>
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
                Nombre Tipo Vehículo <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Camioneta"
                value={formData.nombreTipoVehiculo}
                onChangeText={text => handleChange('nombreTipoVehiculo', text)}
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
                !formData.nombreTipoVehiculo.trim() &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearTipoVehiculo}
              disabled={!formData.nombreTipoVehiculo.trim()}
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Tipo Vehículo</Text>
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
