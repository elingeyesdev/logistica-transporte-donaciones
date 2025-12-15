import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Alert, Switch, ActivityIndicator, Modal,} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5 } from '@expo/vector-icons';
import {
  fetchVoluntarios,
  toggleAdminUser,
  toggleActivoUser,
} from '../services/VoluntarioService';

export default function VoluntarioScreen() {
  const [voluntarios, setVoluntarios] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [selectedVoluntario, setSelectedVoluntario] = useState(null);
  const [showDetailModal, setShowDetailModal] = useState(false);

  const formatFecha = (dateStr) => {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    if (isNaN(d.getTime())) return '—';
    return d.toLocaleDateString('es-BO', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
    });
  };
  const loadVoluntarios = async () => {
    try {
      if (!refreshing) setLoading(true);
      const data = await fetchVoluntarios();
      setVoluntarios(data);
    } catch (error) {
      console.error('Error al cargar voluntarios:', error?.response?.data || error.message);
      Alert.alert('Error', 'No se pudieron cargar los voluntarios.');
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    loadVoluntarios();
  }, []);

  const handleToggleAdmin = async voluntario => {
    try {
      setVoluntarios(prev =>
        prev.map(v =>
          v.id === voluntario.id
            ? { ...v, administrador: !v.administrador }
            : v
        )
      );
      const res = await toggleAdminUser(voluntario.id);
      if (typeof res.administrador !== 'undefined') {
        setVoluntarios(prev =>
          prev.map(v =>
            v.id === voluntario.id
              ? { ...v, administrador: !!res.administrador }
              : v
          )
        );
      }
    } catch (error) {
      console.error('Error al cambiar administrador:', error?.response?.data || error.message);
      Alert.alert('Error', 'No se pudo cambiar el estado de administrador.');
      setVoluntarios(prev =>
        prev.map(v =>
          v.id === voluntario.id
            ? { ...v, administrador: !v.administrador }
            : v
        )
      );
    }
  };

  const handleToggleActivo = async voluntario => {
    try {
      setVoluntarios(prev =>
        prev.map(v =>
          v.id === voluntario.id
            ? { ...v, activo: !v.activo }
            : v
        )
      );
      const res = await toggleActivoUser(voluntario.id);
      if (typeof res.activo !== 'undefined') {
        setVoluntarios(prev =>
          prev.map(v =>
            v.id === voluntario.id
              ? { ...v, activo: !!res.activo }
              : v
          )
        );
      }
    } catch (error) {
      console.error('Error al cambiar activo:', error?.response?.data || error.message);
      Alert.alert('Error', 'No se pudo cambiar el estado activo.');
      setVoluntarios(prev =>
        prev.map(v =>
          v.id === voluntario.id
            ? { ...v, activo: !v.activo }
            : v
        )
      );
    }
  };

  const handleMostrar = voluntario => {
    setSelectedVoluntario(voluntario);
    setShowDetailModal(true);
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
      <Text style={styles.pageTitle}>Voluntarios</Text>

      {loading ? (
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center', marginTop: 20 }}>
          <ActivityIndicator size="large" color={adminlteColors.primary} />
          <Text style={{ marginTop: 8, color: adminlteColors.muted }}>
            Cargando voluntarios...
          </Text>
        </View>
      ) : (
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
                      name="user-tag"
                      size={14}
                      color={adminlteColors.dark}
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.voluntarioCardTitle}>
                      {voluntario.nombre} {voluntario.apellido}
                    </Text>
                  </View>

                  <TouchableOpacity
                    style={styles.showButton}
                    onPress={() => handleMostrar(voluntario)}
                  >
                    <FontAwesome5
                      name="eye"
                      size={12}
                      color="#ffffff"
                      style={{ marginRight: 4 }}
                    />
                    <Text style={styles.showButtonText}>Mostrar</Text>
                  </TouchableOpacity>
                </View>

                <View style={styles.voluntarioCardBody}>
                  <View style={styles.voluntarioInfoRow}>
                    <FontAwesome5
                      name="id-card"
                      size={12}
                      color={adminlteColors.muted}
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.voluntarioInfoLabel}>CI:</Text>
                    <Text style={styles.voluntarioInfoValueMuted}>
                      {voluntario.ci}
                    </Text>
                  </View>
                 

                  <View style={styles.voluntarioInfoRow}>
                    <FontAwesome5
                      name="briefcase"
                      size={12}
                      color={adminlteColors.muted}
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.voluntarioInfoLabel}>Rol:</Text>
                    <Text style={styles.voluntarioInfoValueMuted}>{voluntario.rol}</Text>
                  </View>

                  <View style={styles.switchRow}>
                    <View style={styles.switchLabelContainer}>
                      <Text style={styles.switchLabel}>Administrador:</Text>
                    </View>
                    <Switch
                      value={voluntario.administrador}
                      onValueChange={() => handleToggleAdmin(voluntario)}
                      trackColor={{
                        false: '#d3d3d3',
                        true: adminlteColors.success,
                      }}
                      thumbColor={
                        voluntario.administrador ? '#ffffff' : '#f4f3f4'
                      }
                    />
                    
                  </View>

                  <View style={styles.switchRow}>
                    <View style={styles.switchLabelContainer}>
                     
                    <Text style={styles.switchLabel}>Activo:</Text>
                    </View>
                    <Switch
                      value={voluntario.activo}
                      onValueChange={() => handleToggleActivo(voluntario)}
                      trackColor={{
                        false: '#d3d3d3',
                        true: adminlteColors.success,
                      }}
                      thumbColor={
                        voluntario.activo ? '#ffffff' : '#f4f3f4'
                      }
                    />
                  </View>
                </View>
              </View>
            ))}
          </View>
        </ScrollView>
      )}
      <Modal
        visible={showDetailModal}
        transparent
        animationType="fade"
        onRequestClose={() => setShowDetailModal(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalCard}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Detalle</Text>
              
            </View>

            <View style={styles.modalBody}>
              {selectedVoluntario && (
                <>
                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Nombre completo</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.nombre} {selectedVoluntario.apellido}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Correo</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.correo || '—'}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Teléfono</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.telefono || '—'}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>CI</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.ci || '—'}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Rol</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.rol || 'Sin rol'}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Administrador</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.administrador ? 'Sí' : 'No'}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Activo</Text>
                    <Text style={styles.modalValueMuted}>
                      {selectedVoluntario.activo ? 'Sí' : 'No'}
                    </Text>
                  </View>

                  <View style={styles.modalRow}>
                    <Text style={styles.modalLabel}>Registro</Text>
                    <Text style={styles.modalValueMuted}>
                      {formatFecha(selectedVoluntario.created_at)}
                    </Text>
                  </View>
                </>
              )}
            </View>

            <View style={styles.modalFooter}>
              <TouchableOpacity
                style={styles.modalCloseButton}
                onPress={() => setShowDetailModal(false)}
              >
                <Text style={styles.modalCloseText}>Cerrar</Text>
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
    marginRight: 8,
  },
  voluntarioCardTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  showButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
  },
  showButtonText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: '600',
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
    fontSize: 14,
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
    fontSize: 14,
    color: adminlteColors.muted,
    marginLeft: 5,
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
    modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 16,
  },
  modalCard: {
    width: '100%',
    maxWidth: 400,
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    overflow: 'hidden',
    elevation: 5,
  },
  modalHeader: {
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 16,
    paddingVertical: 12,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  modalTitle: {
    color: '#ffffff',
    fontSize: 16,
    fontWeight: '700',
  },
  modalBody: {
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  modalRow: {
    marginBottom: 8,
  },
  modalLabel: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 2,
  },
  modalValueMuted: {
    fontSize: 13,
    color: adminlteColors.muted,
  },
  modalFooter: {
    paddingHorizontal: 16,
    paddingVertical: 10,
    backgroundColor: '#f8f9fa',
    alignItems: 'flex-end',
  },
  modalCloseButton: {
    paddingHorizontal: 14,
    paddingVertical: 6,
    borderRadius: 4,
    backgroundColor: adminlteColors.secondary,
  },
  modalCloseText: {
    color: '#ffffff',
    fontSize: 13,
    fontWeight: '600',
  },

});
