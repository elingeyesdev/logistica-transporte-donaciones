import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  Modal,
  TextInput,
  Alert,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, Ionicons } from '@expo/vector-icons';

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
  };

  const getPrioridadColor = prioridad => {
    if (prioridad === 1) return adminlteColors.danger;
    if (prioridad === 2) return adminlteColors.warning;
    return adminlteColors.success;
  };

  const renderItem = ({ item }) => (
    <View style={styles.listGroupItem}>
      <View style={styles.listItemRow}>
        <View style={styles.avatarCircle}>
          <Text style={styles.avatarText}>{item.nombre.charAt(0).toUpperCase()}</Text>
        </View>

        <View style={styles.listItemContent}>
          <Text style={styles.listItemTitle}>{item.nombre}</Text>
        </View>

        <View style={[styles.priorityBadge, { backgroundColor: getPrioridadColor(item.prioridad) }]}> 
          <Text style={styles.priorityText}>{item.prioridad}</Text>
        </View>
      </View>
    </View>
  );

  return (
    <AdminLayout scroll={false}>
      <Text style={styles.pageTitle}>Tipos de Emergencia</Text>

      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>Listado de tipos registrados</Text>
          <TouchableOpacity
            style={styles.btnPrimarySm}
            onPress={() => setModalVisible(true)}
          >
            <Ionicons name="add" size={14} color="#ffffff" style={{ marginRight: 6 }} />
            <Text style={styles.btnPrimarySmText}>Crear Tipo</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.cardBody}>
          {tiposEmergencia.length === 0 ? (
            <View style={styles.emptyState}>
              <FontAwesome5 name="exclamation-circle" size={36} color={adminlteColors.muted} />
              <Text style={styles.emptyText}>No hay tipos de emergencia registrados.</Text>
            </View>
          ) : (
            <FlatList
              data={tiposEmergencia}
              keyExtractor={i => i.id}
              renderItem={renderItem}
            />
          )}
        </View>
      </View>

      <Modal
        visible={modalVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <Text style={styles.modalHeaderTitle}>Nuevo Tipo de Emergencia</Text>
            <TouchableOpacity onPress={() => setModalVisible(false)} style={styles.modalCloseButton}>
              <FontAwesome5 name="times" size={20} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <View style={styles.modalBody}>
            <Text style={styles.label}>Nombre de la Emergencia</Text>
            <TextInput
              style={styles.input}
              placeholder="Ej. Inundación"
              value={nuevoNombre}
              onChangeText={setNuevoNombre}
            />

            <Text style={styles.label}>Número de Prioridad</Text>
            <TextInput
              style={styles.input}
              placeholder="Ej. 1"
              value={nuevaPrioridad}
              onChangeText={setNuevaPrioridad}
              keyboardType="numeric"
            />

            <View style={styles.modalFooter}> 
              <TouchableOpacity style={styles.btnDefault} onPress={() => setModalVisible(false)}>
                <Text style={styles.btnDefaultText}>Cancelar</Text>
              </TouchableOpacity>
              <TouchableOpacity style={styles.btnPrimary} onPress={handleCrearTipo}>
                <Text style={styles.btnPrimaryText}>Guardar</Text>
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
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  cardHeaderTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  btnPrimarySm: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
  },
  btnPrimarySmText: {
    color: '#ffffff',
    fontSize: 13,
    fontWeight: '500',
  },
  cardBody: {
    paddingTop: 6,
  },
  listGroupItem: {
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
    backgroundColor: '#ffffff',
    marginBottom: 8,
    borderRadius: 4,
  },
  listItemRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  avatarCircle: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: '#e9ecef',
    alignItems: 'center',
    justifyContent: 'center',
    marginRight: 12,
  },
  avatarText: {
    fontSize: 16,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  listItemContent: {
    flex: 1,
  },
  listItemTitle: {
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
  },
  emptyState: {
    alignItems: 'center',
    paddingVertical: 20,
  },
  emptyText: {
    marginTop: 8,
    color: adminlteColors.muted,
  },
  // Modal
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
  label: {
    fontSize: 14,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 6,
  },
  input: {
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    paddingHorizontal: 10,
    paddingVertical: 8,
    fontSize: 14,
    marginBottom: 12,
  },
  modalFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    gap: 8,
    marginTop: 12,
  },
  btnDefault: {
    backgroundColor: '#e9ecef',
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 4,
  },
  btnDefaultText: {
    color: adminlteColors.dark,
    fontWeight: '600',
  },
  btnPrimary: {
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 14,
    paddingVertical: 10,
    borderRadius: 4,
  },
  btnPrimaryText: {
    color: '#ffffff',
    fontWeight: '600',
  },
});