import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Modal,
  TextInput,
  Alert,
} from 'react-native';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import AdminLayout from '../components/AdminLayout';
import { adminlteColors } from '../theme/adminlte';

const reportesIniciales = [
  {
    id: 1,
    direccionArchivo: '/reportes/2024/reporte_enero.pdf',
    fechaReporte: '15/01/2024',
    gestion: '2024',
  },
  {
    id: 2,
    direccionArchivo: '/reportes/2024/reporte_febrero.pdf',
    fechaReporte: '15/02/2024',
    gestion: '2024',
  },
  {
    id: 3,
    direccionArchivo: '/reportes/2023/reporte_diciembre.pdf',
    fechaReporte: '31/12/2023',
    gestion: '2023',
  },
  {
    id: 4,
    direccionArchivo: '/reportes/2024/reporte_marzo.pdf',
    fechaReporte: '15/03/2024',
    gestion: '2024',
  },
];

const obtenerColorBorde = index => {
  const colores = [
    adminlteColors.primary,
    adminlteColors.success,
    adminlteColors.info,
    adminlteColors.warning,
    adminlteColors.danger,
  ];
  return colores[index % colores.length];
};

export default function ReporteScreen() {
  const [reportes, setReportes] = useState(reportesIniciales);
  const [modalCrearVisible, setModalCrearVisible] = useState(false);
  const [formData, setFormData] = useState({
    direccionArchivo: '',
    fechaReporte: '',
    gestion: '',
  });

  const handleChange = (field, value) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleCrearReporte = () => {
    if (
      !formData.direccionArchivo.trim() ||
      !formData.fechaReporte.trim() ||
      !formData.gestion.trim()
    ) {
      Alert.alert('Error', 'Por favor completa todos los campos');
      return;
    }

    const nuevoReporte = {
      id: Date.now(),
      ...formData,
    };

    setReportes(prev => [nuevoReporte, ...prev]);
    setFormData({
      direccionArchivo: '',
      fechaReporte: '',
      gestion: '',
    });
    setModalCrearVisible(false);
    Alert.alert('Éxito', 'Reporte creado exitosamente');
  };

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Gestión de Reportes</Text>

      {/* Botón Crear Reporte */}
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

      {/* Lista de Reportes */}
      <ScrollView style={styles.reportesContainer}>
        <View style={styles.reportesGrid}>
          {reportes.map((reporte, index) => (
            <View
              key={reporte.id}
              style={[
                styles.reporteCard,
                {
                  borderTopWidth: 3,
                  borderTopColor: obtenerColorBorde(index),
                },
              ]}
            >
              <View style={styles.reporteCardHeader}>
                <View style={styles.reporteCardHeaderContent}>
                  <FontAwesome5
                    name="file-alt"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.reporteCardTitle}>
                    Reporte {reporte.gestion}
                  </Text>
                </View>
              </View>

              <View style={styles.reporteCardBody}>
                <View style={styles.reporteInfoRow}>
                  <FontAwesome5
                    name="folder-open"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.reporteInfoLabel}>Dirección Archivo:</Text>
                </View>
                <Text style={styles.reporteInfoValue}>
                  {reporte.direccionArchivo}
                </Text>

                <View style={styles.reporteInfoRow}>
                  <FontAwesome5
                    name="calendar-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.reporteInfoLabel}>Fecha Reporte:</Text>
                </View>
                <Text style={styles.reporteInfoValueMuted}>
                  {reporte.fechaReporte}
                </Text>

                <View style={styles.reporteInfoRow}>
                  <FontAwesome5
                    name="calendar-check"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.reporteInfoLabel}>Gestión:</Text>
                </View>
                <Text style={styles.reporteInfoValueMuted}>
                  {reporte.gestion}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      {/* Modal Crear Reporte */}
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
                name="file-medical"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Crear Nuevo Reporte</Text>
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
                Dirección Archivo <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. /reportes/2024/reporte_abril.pdf"
                value={formData.direccionArchivo}
                onChangeText={text => handleChange('direccionArchivo', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Fecha Reporte <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 15/04/2024"
                value={formData.fechaReporte}
                onChangeText={text => handleChange('fechaReporte', text)}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>
                Gestión <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. 2024"
                value={formData.gestion}
                onChangeText={text => handleChange('gestion', text)}
                keyboardType="numeric"
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
                (!formData.direccionArchivo.trim() ||
                  !formData.fechaReporte.trim() ||
                  !formData.gestion.trim()) &&
                  styles.modalFooterButtonDisabled,
              ]}
              onPress={handleCrearReporte}
              disabled={
                !formData.direccionArchivo.trim() ||
                !formData.fechaReporte.trim() ||
                !formData.gestion.trim()
              }
            >
              <FontAwesome5
                name="check"
                size={14}
                color="#ffffff"
                style={{ marginRight: 6 }}
              />
              <Text style={styles.modalFooterButtonText}>Crear Reporte</Text>
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
  reportesContainer: {
    flex: 1,
    marginBottom: 16,
  },
  reportesGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
  },
  reporteCard: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    width: '48%',
    marginBottom: 12,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.15,
    shadowRadius: 3,
  },
  reporteCardHeader: {
    padding: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#e0e0e0',
  },
  reporteCardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  reporteCardTitle: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  reporteCardBody: {
    padding: 10,
  },
  reporteInfoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  reporteInfoLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  reporteInfoValue: {
    fontSize: 12,
    color: adminlteColors.dark,
    marginTop: 2,
    marginBottom: 4,
    marginLeft: 18,
  },
  reporteInfoValueMuted: {
    fontSize: 12,
    color: adminlteColors.muted,
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
});
