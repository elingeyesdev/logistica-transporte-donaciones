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

const solicitantesIniciales = [
  {
    id: 1,
    numero: '001',
    nombre: 'María',
    apellido: 'González',
    ci: '12345678',
    email: 'maria.gonzalez@email.com',
    telefono: '70123456',
  },
  {
    id: 2,
    numero: '002',
    nombre: 'Carlos',
    apellido: 'Rodríguez',
    ci: '87654321',
    email: 'carlos.rodriguez@email.com',
    telefono: '71234567',
  },
  {
    id: 3,
    numero: '003',
    nombre: 'Ana',
    apellido: 'Silva',
    ci: '11223344',
    email: 'ana.silva@email.com',
    telefono: '72345678',
  },
];

export default function SolicitantesScreen() {
  const [solicitantes, setSolicitantes] = useState(solicitantesIniciales);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    nombre: '',
    apellido: '',
    ci: '',
    email: '',
    telefono: '',
  });

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearSolicitante = () => {
    if (
      !formData.nombre.trim() ||
      !formData.apellido.trim() ||
      !formData.ci.trim() ||
      !formData.email.trim() ||
      !formData.telefono.trim()
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    const nuevoNumero = String(solicitantes.length + 1).padStart(3, '0');
    const nuevoSolicitante = {
      id: Date.now(),
      numero: nuevoNumero,
      ...formData,
    };

    setSolicitantes(prev => [nuevoSolicitante, ...prev]);
    setFormData({
      nombre: '',
      apellido: '',
      ci: '',
      email: '',
      telefono: '',
    });
    setModalCrearVisible(false);
    Alert.alert('Éxito', `Solicitante #${nuevoNumero} creado exitosamente`);
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
      <Text style={styles.pageTitle}>Solicitantes</Text>

      {/* Botón Crear Solicitante */}
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

      {/* Lista de Solicitantes */}
      <ScrollView style={styles.solicitantesContainer}>
        <View style={styles.solicitantesGrid}>
          {solicitantes.map((solicitante, index) => (
            <View
              key={solicitante.id}
              style={[
                styles.solicitanteCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.solicitanteCardHeader}>
                <View style={styles.solicitanteCardHeaderContent}>
                  <FontAwesome5
                    name="user"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitanteCardTitle}>
                    Solicitante #{solicitante.numero}
                  </Text>
                </View>
              </View>

              <View style={styles.solicitanteCardBody}>
                <View style={styles.solicitanteInfoRow}>
                  <FontAwesome5
                    name="user-circle"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitanteInfoLabel}>Nombre:</Text>
                </View>
                <Text style={styles.solicitanteInfoValue}>
                  {solicitante.nombre}
                </Text>

                <View style={styles.solicitanteInfoRow}>
                  <FontAwesome5
                    name="user-tag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitanteInfoLabel}>Apellido:</Text>
                </View>
                <Text style={styles.solicitanteInfoValueMuted}>
                  {solicitante.apellido}
                </Text>

                <View style={styles.solicitanteInfoRow}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitanteInfoLabel}>CI:</Text>
                </View>
                <Text style={styles.solicitanteInfoValueMuted}>
                  {solicitante.ci}
                </Text>

                <View style={styles.solicitanteInfoRow}>
                  <FontAwesome5
                    name="envelope"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitanteInfoLabel}>Email:</Text>
                </View>
                <Text style={styles.solicitanteInfoValueMuted}>
                  {solicitante.email}
                </Text>

                <View style={styles.solicitanteInfoRow}>
                  <FontAwesome5
                    name="phone"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.solicitanteInfoLabel}>Teléfono:</Text>
                </View>
                <Text style={styles.solicitanteInfoValueMuted}>
                  {solicitante.telefono}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Solicitante */}
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
                name="user-plus"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Solicitante</Text>
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
                Nombre <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Juan"
                value={formData.nombre}
                onChangeText={text => handleChange('nombre', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Apellido <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Pérez"
                value={formData.apellido}
                onChangeText={text => handleChange('apellido', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                CI <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 12345678"
                value={formData.ci}
                onChangeText={text => handleChange('ci', text)}
                keyboardType="numeric"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Email <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. juan.perez@email.com"
                value={formData.email}
                onChangeText={text => handleChange('email', text)}
                keyboardType="email-address"
                autoCapitalize="none"
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Teléfono <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 70123456"
                value={formData.telefono}
                onChangeText={text => handleChange('telefono', text)}
                keyboardType="phone-pad"
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
                (!formData.nombre.trim() ||
                  !formData.apellido.trim() ||
                  !formData.ci.trim() ||
                  !formData.email.trim() ||
                  !formData.telefono.trim()) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearSolicitante}
              disabled={
                !formData.nombre.trim() ||
                !formData.apellido.trim() ||
                !formData.ci.trim() ||
                !formData.email.trim() ||
                !formData.telefono.trim()
              }
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Solicitante</Text>
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
  solicitantesContainer: {
    flex: 1,
  },
  solicitantesGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  solicitanteCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  solicitanteCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  solicitanteCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  solicitanteCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  solicitanteCardBody: {
    padding: 12,
  },
  solicitanteInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  solicitanteInfoLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  solicitanteInfoValue: {
    fontSize: 15,
    color: adminlteColors.primary,
    marginBottom: 8,
    marginLeft: 20,
    fontWeight: '600',
  },
  solicitanteInfoValueMuted: {
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
