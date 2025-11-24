import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import AdminLayout from '../components/AdminLayout';
import SmallBox from '../components/SmallBox';
import { adminlteColors } from '../theme/adminlte';

export default function DashboardScreen() {
  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Dashboard</Text>

      <View style={styles.row}>
        <SmallBox
          color="info"
          title="Donaciones hoy"
          value="12"
          footer="Ver detalle"
        />
        <SmallBox
          color="success"
          title="Entregas"
          value="8"
          footer="Ver detalle"
        />
      </View>

      <View style={styles.row}>
        <SmallBox
          color="warning"
          title="Pendientes"
          value="5"
          footer="Ver pendientes"
        />
        <SmallBox
          color="danger"
          title="Alertas"
          value="2"
          footer="Ver alertas"
        />
      </View>

      <View style={styles.card}>
        <Text style={styles.cardTitle}>Contenido de prueba</Text>
        <Text style={styles.cardText}>
          Aquí luego vas a poner tus tablas/listas conectadas al backend DAS.
        </Text>
      </View>
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
  row: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 12,
  },
  card: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    padding: 12,
    marginTop: 8,
    elevation: 2,
  },
  cardTitle: {
    fontSize: 16,
    fontWeight: '600',
    marginBottom: 6,
    color: adminlteColors.dark,
  },
  cardText: {
    fontSize: 14,
    color: adminlteColors.muted,
  },
});
