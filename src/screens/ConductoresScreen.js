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
  Platform,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import { conductorService } from '../services/conductorService';
import * as licenciaService from '../services/licenciaService';
import DateTimePicker from '@react-native-community/datetimepicker';

export default function ConductoresScreen() {
  const [conductores, setConductores] = useState([]);
  const [licencias, setLicencias] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [showDatePicker, setShowDatePicker] = useState(false);
  const [fechaNacimiento, setFechaNacimiento] = useState(new Date());
  const [formData, setFormData] = useState({
    nombre: '',
    apellido: '',
    fecha_nacimiento: '',
    ci: '',
    celular: '',
    id_licencia: '',
  });

  // Cargar conductores al montar el componente
  useEffect(() => {
    cargarConductores();
    cargarLicencias();
  }, []);

  const cargarLicencias = async () => {
    try {
      const data = await licenciaService.getLicencias();
      setLicencias(data);
    } catch (error) {
      console.error('Error al cargar licencias:', error);
    }
  };

  const cargarConductores = async () => {
    setLoading(true);
    try {
      const result = await conductorService.getConductores();
      if (result.success) {
        setConductores(result.data || []);
      } else {
        Alert.alert('Error', 'No se pudieron cargar los conductores');
        setConductores([]);
      }
    } catch (error) {
      Alert.alert('Error', 'Error de conexión con el servidor');
      setConductores([]);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const onDateChange = (event, selectedDate) => {
    setShowDatePicker(Platform.OS === 'ios');
    if (selectedDate) {
      setFechaNacimiento(selectedDate);
      const formattedDate = selectedDate.toISOString().split('T')[0];
      handleChange('fecha_nacimiento', formattedDate);
    }
  };

  const handleCrearConductor = async () => {
    if (
      !formData.nombre.trim() ||
      !formData.apellido.trim() ||
      !formData.fecha_nacimiento.trim() ||
      !formData.ci.trim() ||
      !formData.celular.trim()
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos obligatorios');
      return;
    }

    setLoading(true);
    try {
      const result = await conductorService.createConductor({
        nombre: formData.nombre.trim(),
        apellido: formData.apellido.trim(),
        fecha_nacimiento: formData.fecha_nacimiento,
        ci: formData.ci.trim(),
        celular: formData.celular.trim(),
        id_licencia: formData.id_licencia || null,
      });
      
      if (result.success) {
        Alert.alert('Éxito', 'Conductor creado exitosamente');
        setFormData({
          nombre: '',
          apellido: '',
          fecha_nacimiento: '',
          ci: '',
          celular: '',
          id_licencia: '',
        });
        setFechaNacimiento(new Date());
        setModalCrearVisible(false);
        cargarConductores();
      } else {
        Alert.alert('Error', result.error || 'No se pudo crear el conductor');
      }
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
      <Text style={styles.pageTitle}>Gestión de Conductores</Text>

      {/* Botón Crear Conductor */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>
            Conductores Registrados
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

      {/* Lista de Conductores */}
      <ScrollView style={styles.conductoresContainer}>
        {loading ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <ActivityIndicator size="large" color={adminlteColors.primary} />
            <Text style={{ marginTop: 10, color: adminlteColors.muted }}>
              Cargando conductores...
            </Text>
          </View>
        ) : conductores.length === 0 ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <Text style={{ color: adminlteColors.muted }}>
              No hay conductores registrados
            </Text>
          </View>
        ) : (
          <View style={styles.conductoresGrid}>
            {conductores.map((conductor, index) => (
              <View
                key={conductor.id ? `conductor-${conductor.id}` : `conductor-index-${index}`}
                style={[
                  styles.conductorCard,
                  {
                    borderTopWidth: 3,
                    borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.conductorCardHeader}>
                <View style={styles.conductorCardHeaderContent}>
                  <FontAwesome5
                    name="user-tie"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorCardTitle}>
                    {conductor.nombre} {conductor.apellido}
                  </Text>
                </View>
              </View>

              <View style={styles.conductorCardBody}>
                <View style={styles.conductorInfoRow}>
                  <FontAwesome5
                    name="user"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorInfoLabel}>Nombre:</Text>
                </View>
                <Text style={styles.conductorInfoValue}>
                  {conductor.nombre}
                </Text>

                <View style={styles.conductorInfoRow}>
                  <FontAwesome5
                    name="user-tag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorInfoLabel}>Apellido:</Text>
                </View>
                <Text style={styles.conductorInfoValueMuted}>
                  {conductor.apellido}
                </Text>

                <View style={styles.conductorInfoRow}>
                  <FontAwesome5
                    name="calendar"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorInfoLabel}>Fecha Nacimiento:</Text>
                </View>
                <Text style={styles.conductorInfoValueMuted}>
                  {conductor.fecha_nacimiento}
                </Text>

                <View style={styles.conductorInfoRow}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorInfoLabel}>CI:</Text>
                </View>
                <Text style={styles.conductorInfoValueMuted}>
                  {conductor.ci}
                </Text>

                <View style={styles.conductorInfoRow}>
                  <FontAwesome5
                    name="phone"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorInfoLabel}>Celular:</Text>
                </View>
                <Text style={styles.conductorInfoValueMuted}>
                  {conductor.celular}
                </Text>

                <View style={styles.conductorInfoRow}>
                  <FontAwesome5
                    name="certificate"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.conductorInfoLabel}>Tipo Licencia:</Text>
                </View>
                <Text style={styles.conductorInfoValueMuted}>
                  {conductor.licencia 
                    ? conductor.licencia.licencia 
                    : conductor.id_licencia 
                      ? licencias.find(l => l.id === conductor.id_licencia || l.id_licencia === conductor.id_licencia)?.licencia || `ID: ${conductor.id_licencia}`
                      : 'Sin licencia'}
                </Text>
              </View>
            </View>
          ))}
          </View>
        )}
      </ScrollView>

      {/* Modal Crear Conductor (overlay centrado) */}
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
                <FontAwesome5 name="user-plus" size={18} color="#ffffff" style={{ marginRight: 8 }} />
                <Text style={styles.modalHeaderTitle}>Crear Nuevo Conductor</Text>
              </View>
              <TouchableOpacity onPress={() => setModalCrearVisible(false)} style={styles.modalCloseButton}>
                <MaterialIcons name="close" size={24} color="#ffffff" />
              </TouchableOpacity>
            </View>
            <ScrollView style={styles.modalBodyCard}>
              <View style={styles.formGroup}>
                <Text style={styles.label}>Nombre <Text style={styles.required}>*</Text></Text>
                <TextInput style={styles.input} placeholder="Ej. Carlos" value={formData.nombre} onChangeText={text => handleChange('nombre', text)} />
              </View>
              <View style={styles.formGroup}>
                <Text style={styles.label}>Apellido <Text style={styles.required}>*</Text></Text>
                <TextInput style={styles.input} placeholder="Ej. Rodríguez" value={formData.apellido} onChangeText={text => handleChange('apellido', text)} />
              </View>
              <View style={styles.formGroup}>
                <Text style={styles.label}>Fecha Nacimiento <Text style={styles.required}>*</Text></Text>
                <TouchableOpacity 
                  style={styles.input} 
                  onPress={() => setShowDatePicker(!showDatePicker)}
                >
                  <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' }}>
                    <Text style={formData.fecha_nacimiento ? styles.dateText : styles.datePlaceholder}>
                      {formData.fecha_nacimiento || 'Seleccionar fecha'}
                    </Text>
                    <FontAwesome5 name="calendar-alt" size={16} color={adminlteColors.primary} />
                  </View>
                </TouchableOpacity>
                {showDatePicker && (
                  <DateTimePicker
                    value={fechaNacimiento}
                    mode="date"
                    display={Platform.OS === 'ios' ? 'spinner' : 'default'}
                    onChange={onDateChange}
                    maximumDate={new Date()}
                  />
                )}
              </View>
              <View style={styles.formGroup}>
                <Text style={styles.label}>CI <Text style={styles.required}>*</Text></Text>
                <TextInput style={styles.input} placeholder="Ej. 12345678" value={formData.ci} onChangeText={text => handleChange('ci', text)} keyboardType="numeric" />
              </View>
              <View style={styles.formGroup}>
                <Text style={styles.label}>Celular <Text style={styles.required}>*</Text></Text>
                <TextInput style={styles.input} placeholder="Ej. 70123456" value={formData.celular} onChangeText={text => handleChange('celular', text)} keyboardType="phone-pad" />
              </View>
              <View style={styles.formGroup}>
                <Text style={styles.label}>Tipo Licencia <Text style={styles.required}>*</Text></Text>
                <View style={styles.licenciaInlineContainer}>
                  {licencias.map((item, index) => {
                    const isSelected = formData.id_licencia === item.id_licencia;
                    return (
                      <TouchableOpacity
                        key={item?.id_licencia ? item.id_licencia.toString() : `licencia-${index}`}
                        style={[styles.licenciaInlineOption, isSelected && styles.licenciaInlineOptionSelected]}
                        onPress={() => handleChange('id_licencia', item.id_licencia)}
                      >
                        <Text style={[styles.licenciaInlineText, isSelected && styles.licenciaInlineTextSelected]}>
                          {item.licencia}
                        </Text>
                        {isSelected && (<FontAwesome5 name="check" size={14} color="#ffffff" style={{ marginLeft: 6 }} />)}
                      </TouchableOpacity>
                    );
                  })}
                </View>
              </View>
            </ScrollView>
            <View style={styles.modalFooterCard}>
              <TouchableOpacity style={styles.modalFooterButtonSecondary} onPress={() => setModalCrearVisible(false)}>
                <Text style={styles.modalFooterButtonText}>Cancelar</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[styles.modalFooterButtonSuccess, (!formData.nombre.trim() || !formData.apellido.trim() || !formData.fecha_nacimiento.trim() || !formData.ci.trim() || !formData.celular.trim()) && styles.modalFooterButtonDisabled]}
                onPress={handleCrearConductor}
                disabled={!formData.nombre.trim() || !formData.apellido.trim() || !formData.fecha_nacimiento.trim() || !formData.ci.trim() || !formData.celular.trim()}
              >
                <FontAwesome5 name="check" size={14} color="#ffffff" style={{ marginRight: 6 }} />
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
  conductoresContainer: {
    flex: 1,
    marginBottom: 16,
  },
  conductoresGrid: {
    flexDirection: 'column',
  },
  conductorCard: {
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
  conductorCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  conductorCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  conductorCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  conductorCardBody: {
    padding: 10,
  },
  conductorInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  conductorInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  conductorInfoValue: {
    fontSize: 12,
    color: adminlteColors.dark,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 18,
  },
  conductorInfoValueMuted: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 18,
  },
  // Overlay modal new styles replacing old full-screen modal
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
  modalHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  modalHeaderTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#ffffff',
  },
  modalCloseButton: {
    padding: 4,
  },
  modalBodyCard: {
    paddingHorizontal: 18,
    paddingVertical: 16,
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
  dateText: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  datePlaceholder: {
    fontSize: 14,
    color: '#6c757d',
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
  // Inline licencia selector styles
  licenciaInlineContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginTop: 8,
    gap: 8,
  },
  licenciaInlineOption: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f8f9fa',
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: '#dee2e6',
  },
  licenciaInlineOptionSelected: {
    backgroundColor: adminlteColors.primary,
    borderColor: adminlteColors.primary,
  },
  licenciaInlineText: {
    fontSize: 14,
    color: adminlteColors.dark,
    fontWeight: '500',
  },
  licenciaInlineTextSelected: {
    color: '#ffffff',
    fontWeight: '600',
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
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 6,
    marginRight: 10,
  },
  modalFooterButtonSuccess: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.success,
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 6,
  },
  modalFooterButtonDisabled: {
    opacity: 0.5,
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '600',
  },
});
