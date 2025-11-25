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
import { marcaService } from '../services/marcaService';

export default function MarcasScreen() {
  const [marcas, setMarcas] = useState([]);
  const [loading, setLoading] = useState(false);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    nombreMarca: '',
  });

  // Cargar marcas al montar el componente
  useEffect(() => {
    cargarMarcas();
  }, []);

  const cargarMarcas = async () => {
    setLoading(true);
    try {
      const result = await marcaService.getMarcas();
      if (result.success) {
        setMarcas(result.data || []); // Si data es null/undefined, usa array vacío
      } else {
        Alert.alert('Error', 'No se pudieron cargar las marcas');
        setMarcas([]); // Asegura que marcas sea un array
      }
    } catch (error) {
      Alert.alert('Error', 'Error de conexión con el servidor');
      setMarcas([]); // Asegura que marcas sea un array
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearMarca = async () => {
    if (!formData.nombreMarca.trim()) {
      Alert.alert('Error', 'Por favor completa el campo');
      return;
    }

    setLoading(true);
    try {
      const result = await marcaService.createMarca(formData.nombreMarca.trim());
      
      if (result.success) {
        Alert.alert('Éxito', 'Marca creada exitosamente');
        setFormData({ nombreMarca: '' });
        setModalCrearVisible(false);
        cargarMarcas(); // Recargar lista
      } else {
        Alert.alert('Error', result.error || 'No se pudo crear la marca');
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
      <Text style={styles.pageTitle}>Gestión de Marcas</Text>

      {/* Botón Crear Marca */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>
            Listado de Marcas Registradas
          </Text>
          <TouchableOpacity
            style={styles.btnCrear}
            onPress={() => setModalCrearVisible(true)}
            disabled={loading}
          >
            <FontAwesome5
              name="plus"
              size={14}
              color="#ffffff"
              style={{ marginRight: 6 }}
            />
            <Text style={styles.btnCrearText}>Crear Marca</Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* Loading Indicator */}
      {loading && (
        <View style={styles.loadingContainer}>
          <ActivityIndicator size="large" color={adminlteColors.primary} />
          <Text style={styles.loadingText}>Cargando marcas...</Text>
        </View>
      )}

      {/* Lista de Marcas */}
      {!loading && (
        <ScrollView style={styles.marcasContainer}>
          <View style={styles.marcasGrid}>
            {marcas.length === 0 ? (
              <View style={styles.emptyContainer}>
                <FontAwesome5 name="inbox" size={48} color={adminlteColors.muted} />
                <Text style={styles.emptyText}>No hay marcas registradas</Text>
              </View>
            ) : (
              marcas.map((marca, index) => (
                <View
                  key={marca.id ? `marca-${marca.id}` : `marca-index-${index}`}
                  style={[
                    styles.marcaCard,
                    {
                      borderTopWidth: 3,
                      borderTopColor: obtenerColorBorde(index),
                    },
                  ]}
                >
                  <View style={styles.marcaCardHeader}>
                    <View style={styles.marcaCardHeaderContent}>
                      <FontAwesome5
                        name="tag"
                        size={14}
                        color={adminlteColors.dark}
                        style={{ marginRight: 6 }}
                      />
                      <Text style={styles.marcaCardTitle}>
                        Marca #{String(index + 1).padStart(3, '0')}
                      </Text>
                    </View>
                  </View>

                  <View style={styles.marcaCardBody}>
                    <View style={styles.marcaInfoRow}>
                      <FontAwesome5
                        name="copyright"
                        size={12}
                        color={adminlteColors.primary}
                        style={{ marginRight: 6 }}
                      />
                      <Text style={styles.marcaInfoLabel}>Nombre Marca:</Text>
                    </View>
                    <Text style={styles.marcaInfoValue}>
                      {marca.nombre_marca}
                    </Text>
                  </View>
                </View>
              ))
            )}
          </View>
        </ScrollView>
      )}

      {/* Modal Crear Marca */}
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
              <Text style={styles.modalHeaderTitle}>Crear Nueva Marca</Text>
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
                Nombre Marca <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Toyota"
                value={formData.nombreMarca}
                onChangeText={text => handleChange('nombreMarca', text)}
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
                (!formData.nombreMarca.trim() || loading) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearMarca}
              disabled={!formData.nombreMarca.trim() || loading}
            >
              {loading ? (
                <ActivityIndicator size="small" color="#ffffff" style={{ marginRight: 6 }} />
              ) : (
                <FontAwesome5
                  name="check"
                  size={14}
                  color="#ffffff"
                  style={{ marginRight: 6 }}
                />
              )}
              <Text style={styles.modalFooterButtonText}>
                {loading ? 'Creando...' : 'Crear Marca'}
              </Text>
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
  marcasContainer: {
    flex: 1,
    marginBottom: 16,
  },
  marcasGrid: {
    flexDirection: 'column',
  },
  marcaCard: {
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
  marcaCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  marcaCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  marcaCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  marcaCardBody: {
    padding: 10,
  },
  marcaInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  marcaInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  marcaInfoValue: {
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
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 40,
  },
  loadingText: {
    marginTop: 12,
    fontSize: 14,
    color: adminlteColors.muted,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 60,
  },
  emptyText: {
    marginTop: 16,
    fontSize: 16,
    color: adminlteColors.muted,
    textAlign: 'center',
  },
});
