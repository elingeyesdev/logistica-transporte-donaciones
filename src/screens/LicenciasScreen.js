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
  Keyboard,
  TouchableWithoutFeedback,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import * as licenciaService from '../services/licenciaService';

export default function LicenciasScreen() {
  const [licencias, setLicencias] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    licencia: '',
  });

  useEffect(() => {
    cargarLicencias();
  }, []);

  const cargarLicencias = async () => {
    setLoading(true);
    try {
      const data = await licenciaService.getLicencias();
      setLicencias(data);
    } catch (error) {
      Alert.alert('Error', 'No se pudieron cargar las licencias');
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearLicencia = async () => {
    if (!formData.licencia.trim()) {
      Alert.alert('Error', 'Por favor completa el campo');
      return;
    }

    setLoading(true);
    try {
      await licenciaService.createLicencia(formData);
      Alert.alert('Éxito', 'Licencia creada exitosamente');
      setFormData({ licencia: '' });
      setModalCrearVisible(false);
      await cargarLicencias();
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
      <Text style={styles.pageTitle}>Licencias</Text>

      {/* Botón Crear Licencia */}
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
            <Text style={styles.btnCrearText}>Crear Licencia</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Lista de Licencias */}
      <ScrollView style={styles.licenciasContainer}>
        {loading ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <ActivityIndicator size="large" color={adminlteColors.primary} />
            <Text style={{ marginTop: 10, color: adminlteColors.muted }}>
              Cargando licencias...
            </Text>
          </View>
        ) : licencias.length === 0 ? (
          <View style={{ padding: 20, alignItems: 'center' }}>
            <Text style={{ color: adminlteColors.muted }}>
              No hay licencias registradas
            </Text>
          </View>
        ) : (
          <View style={styles.licenciasGrid}>
            {licencias.map((licencia, index) => (
              <View
                key={licencia.id ? `licencia-${licencia.id}` : `licencia-index-${index}`}
                style={[
                styles.licenciaCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.licenciaCardHeader}>
                <View style={styles.licenciaCardHeaderContent}>
                  <FontAwesome5
                    name="certificate"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.licenciaCardTitle}>
                    Licencia Nº{String(index + 1).padStart(2, '0')}
                  </Text>
                </View>
              </View>

              <View style={styles.licenciaCardBody}>
                <View style={styles.licenciaInfoRow}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.licenciaInfoLabel}>Tipo Licencia:</Text>
                  <Text style={styles.licenciaInfoValue}>
                  {licencia.licencia}
                </Text>
                </View>
                
              </View>
            </View>
            ))}
          </View>
        )}
      </ScrollView>

      {/* Modal Crear Licencia (overlay centrado) */}
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
                <Text style={styles.modalHeaderTitle}>Crear Nueva Licencia</Text>
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
                  Tipo Licencia <Text style={styles.required}>*</Text>
                </Text>
                <TextInput
                  style={styles.input}
                  placeholder="Ej. Licencia de Conducir Categoría A"
                  value={formData.licencia}
                  onChangeText={text => handleChange('licencia', text)}
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
                  !formData.licencia.trim() &&
                    styles.modalFooterButtonDisabled,
                ]}
                onPress={handleCrearLicencia}
                disabled={!formData.licencia.trim()}
              >
                <FontAwesome5
                  name="check"
                  size={14}
                  color="#ffffff"
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.modalFooterButtonText}>Crear Licencia</Text>
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
  licenciasContainer: {
    flex: 1,
    marginBottom: 16,
  },
  licenciasGrid: {
    flexDirection: 'column',
  },
  licenciaCard: {
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
  licenciaCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  licenciaCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  licenciaCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  licenciaCardBody: {
    padding: 10,
  },
  licenciaInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  licenciaInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  licenciaInfoValue: {
    fontSize: 12,
    color: adminlteColors.dark,
    marginTop: 2,
    marginBottom: 2,
    marginLeft: 8,
  },
  overlayBackdrop: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.7)',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 16,
  },
  modalCard: {
    backgroundColor: '#ffffff',
    borderRadius: 8,
    width: '92%',
    maxHeight: '85%',
    overflow: 'hidden',
    elevation: 5,
  },
  modalHeaderCard: {
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
