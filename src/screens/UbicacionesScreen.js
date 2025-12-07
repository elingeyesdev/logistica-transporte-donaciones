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

const ubicacionesIniciales = [
  {
    id: 1,
    latitud: '-17.7833',
    longitud: '-63.1821',
    zona: 'Zona Norte',
  },
  {
    id: 2,
    latitud: '-15.5000',
    longitud: '-62.8500',
    zona: 'Zona Sur',
  },
  {
    id: 3,
    latitud: '-16.2500',
    longitud: '-61.5000',
    zona: 'Zona Centro',
  },
];

export default function UbicacionesScreen() {
  const [ubicaciones, setUbicaciones] = useState(ubicacionesIniciales);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    latitud: '',
    longitud: '',
    zona: '',
  });

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearUbicacion = () => {
    if (
      !formData.latitud.trim() ||
      !formData.longitud.trim() ||
      !formData.zona.trim()
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    const nuevaUbicacion = {
      id: Date.now(),
      ...formData,
    };

    setUbicaciones(prev => [nuevaUbicacion, ...prev]);
    setFormData({
      latitud: '',
      longitud: '',
      zona: '',
    });
    setModalCrearVisible(false);
    Alert.alert('Éxito', 'Ubicación creada exitosamente');
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
      <Text style={styles.pageTitle}>Ubicaciones</Text>

      {/* Botón Crear Ubicación */}
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

      {/* Lista de Ubicaciones */}
      <ScrollView style={styles.ubicacionesContainer}>
        <View style={styles.ubicacionesGrid}>
          {ubicaciones.map((ubicacion, index) => (
            <View
              key={ubicacion.id}
              style={[
                styles.ubicacionCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.ubicacionCardHeader}>
                <View style={styles.ubicacionCardHeaderContent}>
                  <FontAwesome5
                    name="map-pin"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.ubicacionCardTitle}>
                    {ubicacion.zona}
                  </Text>
                </View>
              </View>

              <View style={styles.ubicacionCardBody}>
                <View style={styles.ubicacionInfoRow}>
                  <FontAwesome5
                    name="compass"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.ubicacionInfoLabel}>Latitud:</Text>
                </View>
                <Text style={styles.ubicacionInfoValue}>
                  {ubicacion.latitud}
                </Text>

                <View style={styles.ubicacionInfoRow}>
                  <FontAwesome5
                    name="globe"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.ubicacionInfoLabel}>Longitud:</Text>
                </View>
                <Text style={styles.ubicacionInfoValueMuted}>
                  {ubicacion.longitud}
                </Text>

                <View style={styles.ubicacionInfoRow}>
                  <FontAwesome5
                    name="map-marked-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.ubicacionInfoLabel}>Zona:</Text>
                </View>
                <Text style={styles.ubicacionInfoValueMuted}>
                  {ubicacion.zona}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Ubicación */}
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
                name="map-marker-alt"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Crear Nueva Ubicación</Text>
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
                Latitud <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. -17.7833"
                value={formData.latitud}
                onChangeText={text => handleChange('latitud', text)}
                keyboardType="numeric"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Longitud <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. -63.1821"
                value={formData.longitud}
                onChangeText={text => handleChange('longitud', text)}
                keyboardType="numeric"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Zona <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Zona Este"
                value={formData.zona}
                onChangeText={text => handleChange('zona', text)}
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
                (!formData.latitud.trim() ||
                  !formData.longitud.trim() ||
                  !formData.zona.trim()) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearUbicacion}
              disabled={
                !formData.latitud.trim() ||
                !formData.longitud.trim() ||
                !formData.zona.trim()
              }
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Ubicación</Text>
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
  ubicacionesContainer: {
    flex: 1,
  },
  ubicacionesGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  ubicacionCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  ubicacionCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  ubicacionCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  ubicacionCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  ubicacionCardBody: {
    padding: 12,
  },
  ubicacionInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  ubicacionInfoLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  ubicacionInfoValue: {
    fontSize: 15,
    color: adminlteColors.primary,
    marginBottom: 8,
    marginLeft: 20,
    fontWeight: '600',
  },
  ubicacionInfoValueMuted: {
    fontSize: 13,
    color: adminlteColors.muted,
    marginBottom: 8,
    marginLeft: 20,
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
