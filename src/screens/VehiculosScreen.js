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
  FlatList,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import * as vehiculoService from '../services/vehiculoService';
import * as tipoVehiculoService from '../services/tipoVehiculoService';
import * as marcaService from '../services/marcaService';

export default function VehiculosScreen() {
  const [vehiculos, setVehiculos] = useState([]);
  const [tiposVehiculo, setTiposVehiculo] = useState([]);
  const [marcas, setMarcas] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [modalTipoVisible, setModalTipoVisible] = useState(false);
  const [modalMarcaVisible, setModalMarcaVisible] = useState(false);
  const [formData, setFormData] = useState({
    placa: '',
    capacidad_aproximada: '',
    id_tipovehiculo: '',
    modelo_anio: '',
    modelo: '',
    id_marca: '',
  });

  useEffect(() => {
    cargarVehiculos();
    cargarTiposVehiculo();
    cargarMarcas();
  }, []);

  const cargarVehiculos = async () => {
    setLoading(true);
    try {
      const data = await vehiculoService.getVehiculos();
      setVehiculos(data);
    } catch (error) {
      Alert.alert('Error', 'No se pudieron cargar los vehículos');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const cargarTiposVehiculo = async () => {
    try {
      const data = await tipoVehiculoService.getTiposVehiculo();
      setTiposVehiculo(data);
    } catch (error) {
      console.error('Error al cargar tipos de vehículo:', error);
    }
  };

  const cargarMarcas = async () => {
    try {
      const data = await marcaService.getMarcas();
      setMarcas(data);
    } catch (error) {
      console.error('Error al cargar marcas:', error);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearVehiculo = async () => {
    if (
      !formData.placa.trim() ||
      !formData.capacidad_aproximada ||
      !formData.id_tipovehiculo ||
      !formData.modelo_anio ||
      !formData.modelo.trim() ||
      !formData.id_marca
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    setLoading(true);
    try {
      await vehiculoService.createVehiculo({
        placa: formData.placa.trim().toUpperCase(),
        capacidad_aproximada: formData.capacidad_aproximada.toString(),
        id_tipovehiculo: formData.id_tipovehiculo,
        modelo_anio: formData.modelo_anio.toString(),
        modelo: formData.modelo.trim(),
        id_marca: formData.id_marca,
      });
      Alert.alert('Éxito', 'Vehículo creado exitosamente');
      setFormData({
        placa: '',
        capacidad_aproximada: '',
        id_tipovehiculo: '',
        modelo_anio: '',
        modelo: '',
        id_marca: '',
      });
      setModalCrearVisible(false);
      await cargarVehiculos();
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
      <Text style={styles.pageTitle}>Gestión de Vehículos</Text>

      {/* Botón Crear Vehículo */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>
            Listado de Vehículos Registrados
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
            <Text style={styles.btnCrearText}>Crear Vehículo</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Lista de Vehículos */}
      <ScrollView style={styles.vehiculosContainer}>
        {loading ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <ActivityIndicator size="large" color={adminlteColors.primary} />
            <Text style={{ marginTop: 10, color: adminlteColors.muted }}>
              Cargando vehículos...
            </Text>
          </View>
        ) : vehiculos.length === 0 ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <Text style={{ color: adminlteColors.muted }}>
              No hay vehículos registrados
            </Text>
          </View>
        ) : (
          <View style={styles.vehiculosGrid}>
            {vehiculos.map((vehiculo, index) => (
              <View
                key={vehiculo.id_vehiculo ? `vehiculo-${vehiculo.id_vehiculo}` : `vehiculo-index-${index}`}
                style={[
                  styles.vehiculoCard,
                  {
                    borderTopWidth: 3,
                    borderTopColor: obtenerColorBorde(index),
                  },
                ]}
              >
              <View style={styles.vehiculoCardHeader}>
                <View style={styles.vehiculoCardHeaderContent}>
                  <FontAwesome5
                    name="car"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoCardTitle}>
                    {vehiculo.placa}
                  </Text>
                </View>
              </View>

              <View style={styles.vehiculoCardBody}>
                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="clipboard"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Placa:</Text>
                </View>
                <Text style={styles.vehiculoInfoValue}>
                  {vehiculo.placa}
                </Text>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="weight"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Capacidad:</Text>
                </View>
                <Text style={styles.vehiculoInfoValueMuted}>
                  {vehiculo.capacidad_aproximada} Kg
                </Text>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="truck"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Tipo:</Text>
                </View>
                <Text style={styles.vehiculoInfoValueMuted}>
                  {vehiculo.tipo_vehiculo?.nombre_tipo_vehiculo || 'N/A'}
                </Text>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="calendar"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Año:</Text>
                </View>
                <Text style={styles.vehiculoInfoValueMuted}>
                  {vehiculo.modelo_anio}
                </Text>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="car-side"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Modelo:</Text>
                </View>
                <Text style={styles.vehiculoInfoValueMuted}>
                  {vehiculo.modelo}
                </Text>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="tag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Marca:</Text>
                </View>
                <Text style={styles.vehiculoInfoValueMuted}>
                  {vehiculo.marca?.nombre_marca || 'N/A'}
                </Text>
              </View>
            </View>
          ))}
          </View>
        )}
      </ScrollView>

      {/* Modal Crear Vehículo */}
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
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Vehículo</Text>
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
                Placa <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. ABC-1234"
                value={formData.placa}
                onChangeText={text => handleChange('placa', text)}
                autoCapitalize="characters"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Capacidad Aproximada (kg) <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 1500"
                value={formData.capacidad_aproximada}
                onChangeText={text => handleChange('capacidad_aproximada', text)}
                keyboardType="numeric"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Tipo de Vehículo <Text style={styles.required}>*</Text>
              </Text>
              <TouchableOpacity
                style={styles.selectButton}
                onPress={() => setModalTipoVisible(true)}
              >
                <Text style={styles.selectButtonText}>
                  {formData.id_tipovehiculo 
                    ? tiposVehiculo.find(t => t.id_tipovehiculo === formData.id_tipovehiculo)?.nombre_tipo_vehiculo || 'Seleccionar tipo'
                    : 'Seleccionar tipo de vehículo'}
                </Text>
                <FontAwesome5 name="chevron-down" size={14} color={adminlteColors.muted} />
              </TouchableOpacity>
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Año de Fabricación <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 2020"
                value={formData.modelo_anio}
                onChangeText={text => handleChange('modelo_anio', text)}
                keyboardType="numeric"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Modelo <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Hilux"
                value={formData.modelo}
                onChangeText={text => handleChange('modelo', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Marca <Text style={styles.required}>*</Text>
              </Text>
              <TouchableOpacity
                style={styles.selectButton}
                onPress={() => setModalMarcaVisible(true)}
              >
                <Text style={styles.selectButtonText}>
                  {formData.id_marca 
                    ? marcas.find(m => m.id_marca === formData.id_marca)?.nombre_marca || 'Seleccionar marca'
                    : 'Seleccionar marca'}
                </Text>
                <FontAwesome5 name="chevron-down" size={14} color={adminlteColors.muted} />
              </TouchableOpacity>
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
                (!formData.placa.trim() ||
                  !formData.capacidad_aproximada ||
                  !formData.id_tipovehiculo ||
                  !formData.modelo_anio ||
                  !formData.modelo.trim() ||
                  !formData.id_marca) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearVehiculo}
              disabled={
                !formData.placa.trim() ||
                !formData.capacidad_aproximada ||
                !formData.id_tipovehiculo ||
                !formData.modelo_anio ||
                !formData.modelo.trim() ||
                !formData.id_marca
              }
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Vehículo</Text>
            </TouchableOpacity>
          </View>

          {/* Modal Seleccionar Tipo Vehículo */}
          <Modal
            visible={modalTipoVisible}
            animationType="slide"
            transparent={true}
            onRequestClose={() => setModalTipoVisible(false)}
          >
            <TouchableOpacity 
              style={styles.modalOverlay}
              activeOpacity={1}
              onPress={() => setModalTipoVisible(false)}
            >
              <TouchableOpacity 
                style={styles.modalSelectContainer}
                activeOpacity={1}
                onPress={(e) => e.stopPropagation()}
              >
                <View style={styles.modalSelectHeader}>
                  <Text style={styles.modalSelectTitle}>Seleccionar Tipo de Vehículo</Text>
                  <TouchableOpacity onPress={() => setModalTipoVisible(false)}>
                    <MaterialIcons name="close" size={24} color={adminlteColors.dark} />
                  </TouchableOpacity>
                </View>
                
                <ScrollView style={styles.selectList}>
                  {tiposVehiculo.map((item, index) => {
                    const isSelected = formData.id_tipovehiculo === item.id_tipovehiculo;
                    return (
                      <TouchableOpacity
                        key={item?.id_tipovehiculo ? item.id_tipovehiculo.toString() : `tipo-${index}`}
                        style={[
                          styles.selectOption,
                          isSelected && styles.selectOptionSelected
                        ]}
                        onPress={() => {
                          handleChange('id_tipovehiculo', item.id_tipovehiculo);
                          setModalTipoVisible(false);
                        }}
                      >
                        <Text style={[
                          styles.selectOptionText,
                          isSelected && styles.selectOptionTextSelected
                        ]}>
                          {item.nombre_tipo_vehiculo}
                        </Text>
                        {isSelected && (
                          <FontAwesome5 name="check" size={16} color={adminlteColors.primary} />
                        )}
                      </TouchableOpacity>
                    );
                  })}
                </ScrollView>
              </TouchableOpacity>
            </TouchableOpacity>
          </Modal>

          {/* Modal Seleccionar Marca */}
          <Modal
            visible={modalMarcaVisible}
            animationType="slide"
            transparent={true}
            onRequestClose={() => setModalMarcaVisible(false)}
          >
            <TouchableOpacity 
              style={styles.modalOverlay}
              activeOpacity={1}
              onPress={() => setModalMarcaVisible(false)}
            >
              <TouchableOpacity 
                style={styles.modalSelectContainer}
                activeOpacity={1}
                onPress={(e) => e.stopPropagation()}
              >
                <View style={styles.modalSelectHeader}>
                  <Text style={styles.modalSelectTitle}>Seleccionar Marca</Text>
                  <TouchableOpacity onPress={() => setModalMarcaVisible(false)}>
                    <MaterialIcons name="close" size={24} color={adminlteColors.dark} />
                  </TouchableOpacity>
                </View>
                
                <ScrollView style={styles.selectList}>
                  {marcas.map((item, index) => {
                    const isSelected = formData.id_marca === item.id_marca;
                    return (
                      <TouchableOpacity
                        key={item?.id_marca ? item.id_marca.toString() : `marca-${index}`}
                        style={[
                          styles.selectOption,
                          isSelected && styles.selectOptionSelected
                        ]}
                        onPress={() => {
                          handleChange('id_marca', item.id_marca);
                          setModalMarcaVisible(false);
                        }}
                      >
                        <Text style={[
                          styles.selectOptionText,
                          isSelected && styles.selectOptionTextSelected
                        ]}>
                          {item.nombre_marca}
                        </Text>
                        {isSelected && (
                          <FontAwesome5 name="check" size={16} color={adminlteColors.primary} />
                        )}
                      </TouchableOpacity>
                    );
                  })}
                </ScrollView>
              </TouchableOpacity>
            </TouchableOpacity>
          </Modal>
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
  vehiculosContainer: {
    flex: 1,
    marginBottom: 16,
  },
  vehiculosGrid: {
    flexDirection: 'column',
  },
  vehiculoCard: {
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
  vehiculoCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  vehiculoCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  vehiculoCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  vehiculoCardBody: {
    padding: 10,
  },
  vehiculoInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  vehiculoInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  vehiculoInfoValue: {
    fontSize: 12,
    color: adminlteColors.dark,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 18,
  },
  vehiculoInfoValueMuted: {
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
  selectButton: {
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 6,
    paddingHorizontal: 12,
    paddingVertical: 12,
    backgroundColor: '#ffffff',
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  selectButtonText: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    justifyContent: 'flex-end',
  },
  modalSelectContainer: {
    backgroundColor: '#ffffff',
    borderTopLeftRadius: 20,
    borderTopRightRadius: 20,
    maxHeight: '70%',
    paddingBottom: 20,
  },
  modalSelectHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  modalSelectTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  selectList: {
    maxHeight: 400,
  },
  selectOption: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 16,
    borderBottomWidth: 1,
    borderBottomColor: '#f0f0f0',
  },
  selectOptionSelected: {
    backgroundColor: adminlteColors.lightBg,
  },
  selectOptionText: {
    fontSize: 15,
    color: adminlteColors.dark,
  },
  selectOptionTextSelected: {
    fontWeight: '600',
    color: adminlteColors.primary,
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
