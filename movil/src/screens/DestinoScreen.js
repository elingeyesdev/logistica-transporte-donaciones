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

const destinosIniciales = [
  {
    id: 1,
    comunidad: 'San José',
    direccion: 'Calle Principal 123, Zona Norte',
    latitud: '-17.7833',
    longitud: '-63.1821',
    provincia: 'Chiquitos',
  },
  {
    id: 2,
    comunidad: 'El Carmen',
    direccion: 'Av. Libertad s/n, Zona Sur',
    latitud: '-15.5000',
    longitud: '-62.8500',
    provincia: 'Ñuflo de Chávez',
  },
  {
    id: 3,
    comunidad: 'Santa Ana',
    direccion: 'Plaza Principal, Centro',
    latitud: '-16.2500',
    longitud: '-61.5000',
    provincia: 'Velasco',
  },
];

export default function DestinoScreen() {
  const [destinos, setDestinos] = useState(destinosIniciales);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    comunidad: '',
    direccion: '',
    latitud: '',
    longitud: '',
    provincia: '',
  });

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearDestino = () => {
    if (
      !formData.comunidad.trim() ||
      !formData.direccion.trim() ||
      !formData.latitud.trim() ||
      !formData.longitud.trim() ||
      !formData.provincia.trim()
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    const nuevoDestino = {
      id: Date.now(),
      ...formData,
    };

    setDestinos(prev => [nuevoDestino, ...prev]);
    setFormData({
      comunidad: '',
      direccion: '',
      latitud: '',
      longitud: '',
      provincia: '',
    });
    setModalCrearVisible(false);
    Alert.alert('Éxito', 'Destino creado exitosamente');
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
      <Text style={styles.pageTitle}>Destinos</Text>

      {/* Botón Crear Destino */}
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

      {/* Lista de Destinos */}
      <ScrollView style={styles.destinosContainer}>
        <View style={styles.destinosGrid}>
          {destinos.map((destino, index) => (
            <View
              key={destino.id}
              style={[
                styles.destinoCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.destinoCardHeader}>
                <View style={styles.destinoCardHeaderContent}>
                  <FontAwesome5
                    name="map-marker-alt"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.destinoCardTitle}>
                    {destino.comunidad}
                  </Text>
                </View>
              </View>

              <View style={styles.destinoCardBody}>
                <View style={styles.destinoInfoRow}>
                  <FontAwesome5
                    name="map-marked-alt"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.destinoInfoLabel}>Comunidad:</Text>
                </View>
                <Text style={styles.destinoInfoValue}>
                  {destino.comunidad}
                </Text>

                <View style={styles.destinoInfoRow}>
                  <FontAwesome5
                    name="home"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.destinoInfoLabel}>Dirección:</Text>
                </View>
                <Text style={styles.destinoInfoValueMuted}>
                  {destino.direccion}
                </Text>

                <View style={styles.destinoInfoRow}>
                  <FontAwesome5
                    name="compass"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.destinoInfoLabel}>Latitud:</Text>
                </View>
                <Text style={styles.destinoInfoValueMuted}>
                  {destino.latitud}
                </Text>

                <View style={styles.destinoInfoRow}>
                  <FontAwesome5
                    name="globe"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.destinoInfoLabel}>Longitud:</Text>
                </View>
                <Text style={styles.destinoInfoValueMuted}>
                  {destino.longitud}
                </Text>

                <View style={styles.destinoInfoRow}>
                  <FontAwesome5
                    name="map"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.destinoInfoLabel}>Provincia:</Text>
                </View>
                <Text style={styles.destinoInfoValueMuted}>
                  {destino.provincia}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Destino */}
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
                name="map-pin"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Destino</Text>
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
                Comunidad <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. San Pedro"
                value={formData.comunidad}
                onChangeText={text => handleChange('comunidad', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Dirección <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={[styles.input, styles.textArea]}
                placeholder="Ej. Calle Principal 456"
                value={formData.direccion}
                onChangeText={text => handleChange('direccion', text)}
                multiline
                numberOfLines={3}
              />
            </View>

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
                Provincia <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Guarayos"
                value={formData.provincia}
                onChangeText={text => handleChange('provincia', text)}
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
                (!formData.comunidad.trim() ||
                  !formData.direccion.trim() ||
                  !formData.latitud.trim() ||
                  !formData.longitud.trim() ||
                  !formData.provincia.trim()) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearDestino}
              disabled={
                !formData.comunidad.trim() ||
                !formData.direccion.trim() ||
                !formData.latitud.trim() ||
                !formData.longitud.trim() ||
                !formData.provincia.trim()
              }
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Destino</Text>
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
  destinosContainer: {
    flex: 1,
  },
  destinosGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  destinoCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  destinoCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  destinoCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  destinoCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  destinoCardBody: {
    padding: 12,
  },
  destinoInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  destinoInfoLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  destinoInfoValue: {
    fontSize: 15,
    color: adminlteColors.primary,
    marginBottom: 8,
    marginLeft: 20,
    fontWeight: '600',
  },
  destinoInfoValueMuted: {
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
  textArea: {
    height: 80,
    textAlignVertical: 'top',
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
