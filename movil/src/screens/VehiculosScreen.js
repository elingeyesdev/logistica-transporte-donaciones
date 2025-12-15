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
  KeyboardAvoidingView,
  Platform,
  Keyboard,
  TouchableWithoutFeedback,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import * as vehiculoService from '../services/vehiculoService';
import * as tipoVehiculoService from '../services/tipoVehiculoService';
import * as marcaService from '../services/marcaService';
import { getPaquetes } from '../services/paqueteService';
import { getSolicitudes } from '../services/solicitudService';

export default function VehiculosScreen() {
  const [vehiculos, setVehiculos] = useState([]);
  const [tiposVehiculo, setTiposVehiculo] = useState([]);
  const [marcas, setMarcas] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    placa: '',
    capacidad_aproximada: '',
    id_tipovehiculo: '',
    modelo_anio: '',
    modelo: '',
    id_marca: '',
    color: '',
    color_otro: '',
  });
  const [paquetes, setPaquetes] = useState([]);
  const [solicitudesMap, setSolicitudesMap] = useState({});

  const [modalPaquetesVisible, setModalPaquetesVisible] = useState(false);
  const [vehiculoSeleccionado, setVehiculoSeleccionado] = useState(null);
  const [paquetesVehiculoSeleccionado, setPaquetesVehiculoSeleccionado] = useState([]);

  const colores = [
    { label: 'Rojo', value: 'Rojo' },
    { label: 'Blanco', value: 'Blanco' },
    { label: 'Plomo', value: 'Plomo' },
    { label: 'Negro', value: 'Negro' },
    { label: 'Azul', value: 'Azul' },
    { label: 'Dorado', value: 'Dorado' },
    { label: 'Guindo/Rojo Oscuro', value: 'Guindo/Rojo Oscuro' },
    { label: 'Otro', value: 'Otro' },
  ];

  useEffect(() => {
    cargarVehiculos();
    cargarTiposVehiculo();
    cargarMarcas();
    cargarPaquetesYSolicitudes();
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

  const cargarPaquetesYSolicitudes = async () => {
    try {
      const solicitudes = await getSolicitudes();
      const index = {};
      (solicitudes || []).forEach(s => {
        if (s.id_solicitud != null) {
          index[String(s.id_solicitud)] = s;
        }
      });
      setSolicitudesMap(index);

      const listaPaquetes = await getPaquetes();
      setPaquetes(listaPaquetes || []);
    } catch (error) {
      console.error('Error al cargar paquetes/solicitudes:', error);
    }
  };


  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearVehiculo = async () => {
    const colorIsEmpty = !formData.color || (formData.color === 'Otro' && !formData.color_otro.trim());
    if (
      !formData.placa.trim() ||
      !formData.capacidad_aproximada ||
      !formData.id_tipovehiculo ||
      !formData.modelo_anio ||
      !formData.modelo.trim() ||
      !formData.id_marca ||
      colorIsEmpty
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    setLoading(true);
    const colorToSend = formData.color === 'Otro' ? formData.color_otro.trim() : formData.color;
    try {
      await vehiculoService.createVehiculo({
        placa: formData.placa.trim().toUpperCase(),
        capacidad_aproximada: formData.capacidad_aproximada.toString(),
        id_tipovehiculo: formData.id_tipovehiculo,
        modelo_anio: formData.modelo_anio.toString(),
        modelo: formData.modelo.trim(),
        id_marca: formData.id_marca,
        color: colorToSend,
      });
      Alert.alert('Éxito', 'Vehículo creado exitosamente');
      setFormData({
        placa: '',
        capacidad_aproximada: '',
        id_tipovehiculo: '',
        modelo_anio: '',
        modelo: '',
        id_marca: '',
        color: '',
        color_otro: '',
      });
      setModalCrearVisible(false);
      await cargarVehiculos();
    } catch (error) {
      Alert.alert('Error', 'Error de conexión con el servidor');
    } finally {
      setLoading(false);
    }
  };
  const getMarcaNombre = (idMarca) => {
    const marca = marcas.find(m => String(m.id_marca) === String(idMarca));
    return marca?.nombre_marca || 'N/A';
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

  const getEstadoKeyFromPaquete = (p) => {
    const nombre = (
      p.estado?.nombre_estado ||
      p.estado_nombre ||
      p.estadoNombre ||
      ''
    ).toLowerCase();

    if (nombre.includes('camino')) return 'en_camino';
    if (nombre.includes('pendiente')) return 'pendiente';
    if (nombre.includes('armado')) return 'armado';
    if (nombre.includes('entregado')) return 'entregado';
    return 'otro';
  };

  const esPaqueteEnCamino = (p) => getEstadoKeyFromPaquete(p) === 'en_camino';

  const getPaquetesEnCaminoPorVehiculo = (vehiculoId) => {
    if (!vehiculoId) return [];
    return (paquetes || []).filter(
      (p) =>
        p.id_vehiculo &&
        String(p.id_vehiculo) === String(vehiculoId) &&
        esPaqueteEnCamino(p)
    );
  };

  const getDestinoDesdeSolicitud = (solicitud) => {
    if (!solicitud) return '—';

    const destinoRaw =
      solicitud.destino
      null;

    if (!destinoRaw) return '—';

    if (typeof destinoRaw === 'object') {
      const partes = [
        destinoRaw.comunidad,
        destinoRaw.direccion,
        destinoRaw.provincia
      ]
        .map((x) => (x ? String(x).trim() : ''))
        .filter(Boolean);

      return partes.length > 0 ? partes.join(', ') : '—';
    }

    return '—';
  };


  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Vehículos Registrados</Text>

      {/* Botón Crear Vehículo */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          
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
            {vehiculos.map((vehiculo, index) => {
              const paquetesEnCamino = getPaquetesEnCaminoPorVehiculo(vehiculo.id_vehiculo);
              const countPaquetes = paquetesEnCamino.length;
              return(
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
                 {countPaquetes > 0 && (

                    <TouchableOpacity
                      style={styles.badgePaquetes}
                      onPress={() => {
                        setVehiculoSeleccionado(vehiculo);

                        const detalles = paquetesEnCamino.map((p) => {
                          const solicitud =
                            p.id_solicitud != null
                              ? solicitudesMap[String(p.id_solicitud)]
                              : null;

                          const codigo =
                            solicitud?.codigo_seguimiento ||
                            solicitud?.codigo ||
                            p.codigo ||
                            (p.id_solicitud != null
                              ? `SOL-${p.id_solicitud}`
                              : `ID-${p.id_paquete || p.id}`);

                          const destino = getDestinoDesdeSolicitud(solicitud);

                          return {
                            id: p.id_paquete || p.id,
                            codigo,
                            destino,
                          };
                        });

                        setPaquetesVehiculoSeleccionado(detalles);
                        setModalPaquetesVisible(true);
                      }}
                    >
                      <Text style={styles.badgePaquetesText}>
                        {countPaquetes} Paquete{countPaquetes !== 1 ? 's' : ''} 
                      </Text>
                      <FontAwesome5
                        name="chevron-down"
                        size={11}
                        color="#ffffff"
                        style={{ marginLeft: 3 }}
                      />
                    </TouchableOpacity>
                  )}
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
                  <Text style={styles.vehiculoInfoValue}>
                    {vehiculo.placa}
                  </Text>
                </View>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="weight"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Capacidad:</Text>
                    <Text style={styles.vehiculoInfoValueMuted}>
                    {vehiculo.capacidad_aproximada} Kg
                    </Text>
                </View>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="truck"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Tipo:</Text>
                  <Text style={styles.vehiculoInfoValueMuted}>
                    {vehiculo.tipo_vehiculo?.nombre_tipo_vehiculo || 'N/A'}
                  </Text>
                </View>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="calendar"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Año:</Text>
                  <Text style={styles.vehiculoInfoValueMuted}>
                    {vehiculo.modelo_anio}
                  </Text>
                </View>


                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="car-side"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Modelo:</Text>
                    <Text style={styles.vehiculoInfoValueMuted}>
                      {vehiculo.modelo}
                    </Text>
                </View>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="tag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Marca: </Text>
                  <Text style={styles.vehiculoInfoValueMuted}>
                    {getMarcaNombre(vehiculo.id_marca)}
                  </Text>
                </View>

                <View style={styles.vehiculoInfoRow}>
                  <FontAwesome5
                    name="brush"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.vehiculoInfoLabel}>Color:</Text>
                  <Text style={styles.vehiculoInfoValueMuted}>
                  {vehiculo.color || 'Otro'}
                </Text>
                </View>
              </View>
            </View>
            );
          })} 
          </View>
        )}
      </ScrollView>

      {/* Modal Crear Vehículo (overlay centrado) */}
      <Modal
        visible={modalCrearVisible}
        animationType="fade"
        transparent={true}
        onRequestClose={() => setModalCrearVisible(false)}
      >
        <View style={styles.overlayBackdrop}>
                 <KeyboardAvoidingView
                  style={styles.keyboardAvoidingContainer}
                  behavior={Platform.OS === 'ios' ? 'padding' : 'padding'}
                  keyboardVerticalOffset={Platform.OS === 'ios' ? -40 : 0}
                >
          <View style={styles.modalCard}>
            <View style={styles.modalHeaderCard}>
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

            <ScrollView style={styles.modalBodyCard}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Placa <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 1234ABC"
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
              <View style={styles.inlineSelectorContainer}>
                {tiposVehiculo.map((item, index) => {
                  const isSelected = formData.id_tipovehiculo === item.id_tipovehiculo;
                  return (
                    <TouchableOpacity
                      key={item?.id_tipovehiculo ? item.id_tipovehiculo.toString() : `tipo-${index}`}
                      style={[styles.inlineOption, isSelected && styles.inlineOptionSelected]}
                      onPress={() => handleChange('id_tipovehiculo', item.id_tipovehiculo)}
                    >
                      <Text style={[styles.inlineOptionText, isSelected && styles.inlineOptionTextSelected]}>
                        {item.nombre_tipo_vehiculo}
                      </Text>
                      {isSelected && (<FontAwesome5 name="check" size={14} color="#ffffff" style={{ marginLeft: 6 }} />)}
                    </TouchableOpacity>
                  );
                })}
              </View>
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
              <View style={styles.inlineSelectorContainer}>
                {marcas.map((item, index) => {
                  const isSelected = formData.id_marca === item.id_marca;
                  return (
                    <TouchableOpacity
                      key={item?.id_marca ? item.id_marca.toString() : `marca-${index}`}
                      style={[styles.inlineOption, isSelected && styles.inlineOptionSelected]}
                      onPress={() => handleChange('id_marca', item.id_marca)}
                    >
                      <Text style={[styles.inlineOptionText, isSelected && styles.inlineOptionTextSelected]}>
                        {item.nombre_marca}
                      </Text>
                      {isSelected && (<FontAwesome5 name="check" size={14} color="#ffffff" style={{ marginLeft: 6 }} />)}
                    </TouchableOpacity>
                  );
                })}
              </View>
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Color <Text style={styles.required}>*</Text>
              </Text>

              <View style={styles.inlineSelectorContainer}>
                {colores.map((option) => {
                  const isSelected = formData.color === option.value;

                  return (
                    <TouchableOpacity
                      key={option.value}
                      style={[
                        styles.inlineOption,
                        isSelected && styles.inlineOptionSelected,
                      ]}
                      onPress={() => handleChange('color', option.value)}
                    >
                      <Text
                        style={[
                          styles.inlineOptionText,
                          isSelected && styles.inlineOptionTextSelected,
                        ]}
                      >
                        {option.label}
                      </Text>

                      {isSelected && (
                        <FontAwesome5
                          name="check"
                          size={14}
                          color="#ffffff"
                          style={{ marginLeft: 6 }}
                        />
                      )}
                    </TouchableOpacity>
                  );
                })}
              </View>

              {formData.color === 'Otro' && ( 
                <View style={{ marginTop: 8 }}>
                  <Text style={styles.label}>Especificar color</Text>
                  <TextInput
                    style={styles.textInput}
                    placeholder="Escribe el color"
                    value={formData.color_otro || ''}
                    onChangeText={(text) => handleChange('color_otro', text)}
                  />
                </View>
              )}
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
              <Text style={styles.modalFooterButtonText}>Crear</Text>
            </TouchableOpacity>
            </View>
          </View>
          </KeyboardAvoidingView>
        </View>
      </Modal>
      <Modal
        visible={modalPaquetesVisible}
        transparent
        animationType="fade"
        onRequestClose={() => {
          setModalPaquetesVisible(false);
          setVehiculoSeleccionado(null);
          setPaquetesVehiculoSeleccionado([]);
        }}
      >
          <View style={styles.overlayBackdrop}>
            <View style={styles.modalCardPaquetes}>
              <View style={styles.modalHeaderCardPaquetes}>
                <View style={styles.modalHeaderContent}>
                  <FontAwesome5
                    name="boxes"
                    size={18}
                    color="#ffffff"
                    style={{ marginRight: 8 }}
                  />
                  <Text style={styles.modalHeaderTitle}>
                    Paquetes en camino
                  </Text>
                </View>
                <TouchableOpacity
                  onPress={() => {
                    setModalPaquetesVisible(false);
                    setVehiculoSeleccionado(null);
                    setPaquetesVehiculoSeleccionado([]);
                  }}
                  style={styles.modalCloseButton}
                >
                  <MaterialIcons name="close" size={24} color="#ffffff" />
                </TouchableOpacity>
              </View>

              <ScrollView style={styles.modalBodyCard}>
                {vehiculoSeleccionado && (
                  <Text style={[styles.label, { marginBottom: 8 }]}>
                    Vehículo: {vehiculoSeleccionado.placa}
                  </Text>
                )}

                {paquetesVehiculoSeleccionado.length === 0 ? (
                  <Text style={styles.vehiculoInfoValueMuted}>
                    No hay paquetes en camino para este vehículo.
                  </Text>
                ) : (
                  paquetesVehiculoSeleccionado.map((item) => (
                    <View key={item.id} style={styles.paqueteRow}>
                      <Text style={styles.paqueteCodigo}>{item.codigo}</Text>
                      <Text style={styles.paqueteDestino}>{item.destino}</Text>
                    </View>
                  ))
                )}
              </ScrollView>

              <View style={styles.modalFooterCard}>
                <TouchableOpacity
                  style={styles.modalFooterButtonSecondary}
                  onPress={() => {
                    setModalPaquetesVisible(false);
                    setVehiculoSeleccionado(null);
                    setPaquetesVehiculoSeleccionado([]);
                  }}
                >
                  <Text style={styles.modalFooterButtonText}>Cerrar</Text>
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
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',

  },
  badgePaquetes: {
    backgroundColor: adminlteColors.info,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    flexDirection: 'row',
    alignItems: 'center',
  },
  badgePaquetesText: {
    color: '#ffffff',
    fontSize: 11,
    fontWeight: '700',
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
    marginLeft: 8,
  },
  vehiculoInfoValueMuted: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 8,
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
  modalBodyCard: {
    paddingHorizontal: 18,
    paddingVertical: 16,
  },
  modalCardPaquetes: {
    width: '92%',
    maxHeight: '80%',
    backgroundColor: '#ffffff',
    borderRadius: 12,
    overflow: 'hidden',
    elevation: 6,
  },
  modalHeaderCardPaquetes: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: adminlteColors.info,
    paddingHorizontal: 18,
    paddingVertical: 14,
  },
  paqueteRow: {
    marginBottom: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#eee',
    paddingBottom: 8,
  },
  paqueteCodigo: {
    fontSize: 14,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  paqueteDestino: {
    fontSize: 13,
    color: adminlteColors.muted,
    marginTop: 2,
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
  inlineSelectorContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginTop: 8,
    gap: 8,
  },
  inlineOption: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#dee2e6',
  },
  inlineOptionSelected: {
    backgroundColor: adminlteColors.primary,
    borderColor: adminlteColors.primary,
  },
  inlineOptionText: {
    fontSize: 14,
    color: adminlteColors.dark,
    fontWeight: '500',
  },
  inlineOptionTextSelected: {
    color: '#ffffff',
    fontWeight: '600',
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
});
