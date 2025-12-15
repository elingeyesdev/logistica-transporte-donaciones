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

export default function TipoEmergenciaScreen({ navigation }) {
  const [tiposEmergencia, setTiposEmergencia] = useState([
    { id: '1', nombre: 'Incendio Forestal', prioridad: 1 },
    { id: '2', nombre: 'Accidente Vehicular', prioridad: 2 },
    { id: '3', nombre: 'Rescate Animal', prioridad: 3 },
  ]);

  const [modalVisible, setModalVisible] = useState(false);
  const [nuevoNombre, setNuevoNombre] = useState('');
  const [nuevaPrioridad, setNuevaPrioridad] = useState('');

  const handleCrearTipo = () => {
    if (!nuevoNombre.trim() || !nuevaPrioridad.trim()) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    const nuevoTipo = {
      id: Date.now().toString(),
      nombre: nuevoNombre,
      prioridad: parseInt(nuevaPrioridad, 10) || 0,
    };

    setTiposEmergencia(prev => [nuevoTipo, ...prev]);
    setNuevoNombre('');
    setNuevaPrioridad('');
    setModalVisible(false);
    Alert.alert('Éxito', 'Tipo de emergencia creado exitosamente');
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

  const getPrioridadColor = prioridad => {
    if (prioridad === 1) return adminlteColors.danger;
    if (prioridad === 2) return adminlteColors.warning;
    return adminlteColors.success;
  };

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Tipos de Emergencia</Text>

      {/* Botón Crear Tipo */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
         
          <TouchableOpacity
            style={styles.btnCrear}
            onPress={() => setModalVisible(true)}
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

      {/* Lista de Tipos de Emergencia */}
      <ScrollView style={styles.tiposContainer}>
        <View style={styles.tiposGrid}>
          {tiposEmergencia.map((tipo, index) => (
            <View
              key={tipo.id}
              style={[
                styles.tipoCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.tipoCardHeader}>
                <View style={styles.tipoCardHeaderContent}>
                  <FontAwesome5
                    name="exclamation-triangle"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.tipoCardTitle}>
                    {tipo.nombre}
                  </Text>
                </View>
                <View style={[styles.priorityBadge, { backgroundColor: getPrioridadColor(tipo.prioridad) }]}>
                  <Text style={styles.priorityText}>{tipo.prioridad}</Text>
                </View>
              </View>

              <View style={styles.tipoCardBody}>
                <View style={styles.tipoInfoRow}>
                  <FontAwesome5
                    name="sort-numeric-up"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.tipoInfoLabel}>Prioridad:</Text>
                </View>
                <Text style={styles.tipoInfoValue}>{tipo.prioridad}</Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Tipo de Emergencia */}
      <Modal
        visible={modalVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalVisible(false)}
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
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Tipo de Emergencia</Text>
            </View>
            <TouchableOpacity
              onPress={() => setModalVisible(false)}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <ScrollView style={styles.modalBody}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Nombre de la Emergencia <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Inundación"
                value={nuevoNombre}
                onChangeText={setNuevoNombre}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Número de Prioridad <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 1"
                value={nuevaPrioridad}
                onChangeText={setNuevaPrioridad}
                keyboardType="numeric"
              />
            </View>
          </ScrollView>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalVisible(false)}
            >
              <Text style={styles.modalFooterButtonText}>Cancelar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.modalFooterButtonSuccess,
                (!nuevoNombre.trim() || !nuevaPrioridad.trim()) && styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearTipo}
              disabled={!nuevoNombre.trim() || !nuevaPrioridad.trim()}
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Tipo</Text>
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
    flex: 1,
  },
  btnCrear: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.success,
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 4,
  },
  btnCrearText: {
    color: '#ffffff',
    fontSize: 13,
    fontWeight: '500',
  },
  tiposContainer: {
    flex: 1,
  },
  tiposGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  tipoCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  tipoCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  tipoCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  tipoCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  priorityBadge: {
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 16,
    alignItems: 'center',
    justifyContent: 'center',
  },
  priorityText: {
    color: '#ffffff',
    fontWeight: '700',
    fontSize: 12,
  },
  tipoCardBody: {
    padding: 12,
  },
  tipoInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  tipoInfoLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  tipoInfoValue: {
    fontSize: 15,
    color: adminlteColors.primary,
    marginLeft: 20,
    fontWeight: '600',
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
  formGroup: {
    marginBottom: 16,
  },
  label: {
    fontSize: 13,
    fontWeight: '500',
    marginBottom: 8,
    color: adminlteColors.dark,
  },
  required: {
    color: adminlteColors.danger,
  },
  input: {
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    paddingHorizontal: 12,
    paddingVertical: 10,
    fontSize: 14,
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
    flexDirection: 'row',
    alignItems: 'center',
  },
  modalFooterButtonSuccess: {
    backgroundColor: adminlteColors.success,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
    flexDirection: 'row',
    alignItems: 'center',
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