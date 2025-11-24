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
  Switch,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';

const voluntariosIniciales = [
  {
    id: 1,
    nombre: 'Pedro',
    apellido: 'Martínez',
    correo: 'pedro.martinez@email.com',
    telefono: '70123456',
    ci: '12345678',
    rol: 'Coordinador',
    administrador: true,
    activo: true,
  },
  {
    id: 2,
    nombre: 'Laura',
    apellido: 'Fernández',
    correo: 'laura.fernandez@email.com',
    telefono: '71234567',
    ci: '87654321',
    rol: 'Voluntario',
    administrador: false,
    activo: true,
  },
  {
    id: 3,
    nombre: 'Miguel',
    apellido: 'Torres',
    correo: 'miguel.torres@email.com',
    telefono: '72345678',
    ci: '11223344',
    rol: 'Conductor',
    administrador: false,
    activo: false,
  },
];

export default function VoluntarioScreen() {
  const [voluntarios, setVoluntarios] = useState(voluntariosIniciales);

  const toggleAdministrador = id => {
    setVoluntarios(prev =>
      prev.map(v =>
        v.id === id ? { ...v, administrador: !v.administrador } : v
      )
    );
  };

  const toggleActivo = id => {
    setVoluntarios(prev =>
      prev.map(v => (v.id === id ? { ...v, activo: !v.activo } : v))
    );
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
      <Text style={styles.pageTitle}>Gestión de Voluntarios</Text>

      {/* Lista de Voluntarios */}
      <ScrollView style={styles.voluntariosContainer}>
        <View style={styles.voluntariosGrid}>
          {voluntarios.map((voluntario, index) => (
            <View
              key={voluntario.id}
              style={[
                styles.voluntarioCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.voluntarioCardHeader}>
                <View style={styles.voluntarioCardHeaderContent}>
                  <FontAwesome5
                    name="user-circle"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioCardTitle}>
                    {voluntario.nombre} {voluntario.apellido}
                  </Text>
                </View>
              </View>

              <View style={styles.voluntarioCardBody}>
                <View style={styles.voluntarioInfoRow}>
                  <FontAwesome5
                    name="user"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioInfoLabel}>Nombre:</Text>
                </View>
                <Text style={styles.voluntarioInfoValue}>
                  {voluntario.nombre}
                </Text>

                <View style={styles.voluntarioInfoRow}>
                  <FontAwesome5
                    name="user-tag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioInfoLabel}>Apellido:</Text>
                </View>
                <Text style={styles.voluntarioInfoValueMuted}>
                  {voluntario.apellido}
                </Text>

                <View style={styles.voluntarioInfoRow}>
                  <FontAwesome5
                    name="envelope"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioInfoLabel}>Correo:</Text>
                </View>
                <Text style={styles.voluntarioInfoValueMuted}>
                  {voluntario.correo}
                </Text>

                <View style={styles.voluntarioInfoRow}>
                  <FontAwesome5
                    name="phone"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioInfoLabel}>Teléfono:</Text>
                </View>
                <Text style={styles.voluntarioInfoValueMuted}>
                  {voluntario.telefono}
                </Text>

                <View style={styles.voluntarioInfoRow}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioInfoLabel}>CI:</Text>
                </View>
                <Text style={styles.voluntarioInfoValueMuted}>
                  {voluntario.ci}
                </Text>

                <View style={styles.voluntarioInfoRow}>
                  <FontAwesome5
                    name="briefcase"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.voluntarioInfoLabel}>Rol:</Text>
                </View>
                <Text style={styles.voluntarioInfoValueMuted}>
                  {voluntario.rol}
                </Text>

                {/* Administrador Switch */}
                <View style={styles.switchRow}>
                  <View style={styles.switchLabelContainer}>
                    <FontAwesome5
                      name="user-shield"
                      size={12}
                      color={adminlteColors.muted}
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.switchLabel}>Administrador:</Text>
                  </View>
                  <Switch
                    value={voluntario.administrador}
                    onValueChange={() => toggleAdministrador(voluntario.id)}
                    trackColor={{
                      false: '#d3d3d3',
                      true: adminlteColors.success,
                    }}
                    thumbColor={voluntario.administrador ? '#ffffff' : '#f4f3f4'}
                  />
                </View>

                {/* Activo Switch */}
                <View style={styles.switchRow}>
                  <View style={styles.switchLabelContainer}>
                    <FontAwesome5
                      name="toggle-on"
                      size={12}
                      color={adminlteColors.muted}
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.switchLabel}>Activo:</Text>
                  </View>
                  <Switch
                    value={voluntario.activo}
                    onValueChange={() => toggleActivo(voluntario.id)}
                    trackColor={{
                      false: '#d3d3d3',
                      true: adminlteColors.success,
                    }}
                    thumbColor={voluntario.activo ? '#ffffff' : '#f4f3f4'}
                  />
                </View>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>
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
  voluntariosContainer: {
    flex: 1,
  },
  voluntariosGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
  },
  voluntarioCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  voluntarioCardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  voluntarioCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  voluntarioCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  voluntarioCardBody: {
    padding: 12,
  },
  voluntarioInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  voluntarioInfoLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  voluntarioInfoValue: {
    fontSize: 15,
    color: adminlteColors.primary,
    marginBottom: 8,
    marginLeft: 20,
    fontWeight: '600',
  },
  voluntarioInfoValueMuted: {
    fontSize: 13,
    color: adminlteColors.muted,
    marginBottom: 8,
    marginLeft: 20,
  },
  switchRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginTop: 8,
    paddingVertical: 8,
    borderTopWidth: 1,
    borderTopColor: '#f0f0f0',
  },
  switchLabelContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  switchLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
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
