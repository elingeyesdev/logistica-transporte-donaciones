import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ActivityIndicator, Alert, ScrollView, Button } from 'react-native';
import AdminLayout from '../components/AdminLayout';
import SmallBox from '../components/SmallBox';
import { getApiConfig, API_BASE_URL } from '../config/api';
import axios from 'axios';

export default function DashboardScreen() {
  const [loading, setLoading] = useState(true);
  const [data, setData] = useState(null);

  const fetchDashboardData = async () => {
    setLoading(true);
    try {
      const config = await getApiConfig(); 
      const response = await axios.get(`${API_BASE_URL}/dashboard`, config);

      console.log('Respuesta completa de la API:', JSON.stringify(response.data, null, 2)); 

      
      const mappedData = {
        total: response.data.total || response.data.listadoSolicitud || 0, 
        aceptadas: response.data.aceptadas || 0,
        rechazadas: response.data.rechazadas || 0,
        tasa: response.data.tasa || 0,
        totalVoluntarios: response.data.totalVoluntarios || 0,
        voluntariosConductores: response.data.voluntariosConductores || 0,
      };

      console.log('Datos mapeados para el dashboard:', mappedData); 

      setData(mappedData);
    } catch (error) {
      console.error('Error al obtener datos del dashboard:', error);
      Alert.alert('Error', 'No se pudo cargar el dashboard.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDashboardData();
  }, []);

  if (loading) {
    return (
      <View style={styles.loaderContainer}>
        <ActivityIndicator size="large" color="#0000ff" />
      </View>
    );
  }

  if (!data) {
    console.log('Estado de los datos en el renderizado:', data); 
    return (
      <View style={styles.errorContainer}>
        <Text style={styles.errorText}>No se pudo cargar el dashboard.</Text>
        <TouchableOpacity style={styles.retryButton} onPress={fetchDashboardData}>
          <Text style={styles.retryButtonText}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <AdminLayout>
      <ScrollView contentContainerStyle={styles.scrollContainer}>
        <View style={styles.headerContainer}>
          <Text style={styles.pageTitle}>Dashboard</Text>
          <Button title="Recargar" onPress={fetchDashboardData} color="#007bff" />
        </View>

        <View style={styles.row}>
          <SmallBox color="info" title="Solicitudes Totales" value={data.total}  />
          <SmallBox color="success" title="Aceptadas" value={data.aceptadas}  />
        </View>

        <View style={styles.row}>
          <SmallBox color="danger" title="Rechazadas" value={data.rechazadas}  />
          <SmallBox color="warning" title="Tasa de Aprobación" value={`${data.tasa}%`}  />
        </View>

        <View style={styles.row}>
          <SmallBox color="purple" title="Total Voluntarios" value={data.totalVoluntarios}  />
          <SmallBox color="teal" title="Voluntarios Conductores" value={data.voluntariosConductores}  />
        </View>

        <View style={styles.cardContainer}>
          <View style={[styles.card, styles.shadow]}>
            <Text style={styles.cardTitle}>Resumen General</Text>
            <Text style={styles.cardText}>Total de solicitudes: {data.total}</Text>
            <Text style={styles.cardText}>Aceptadas: {data.aceptadas}</Text>
            <Text style={styles.cardText}>Rechazadas: {data.rechazadas}</Text>
            <Text style={styles.cardText}>Tasa de aprobación: {data.tasa}%</Text>
          </View>
        </View>

        {/* Aquí puedes agregar más secciones como gráficos o tablas */}
      </ScrollView>
    </AdminLayout>
  );
}

const styles = StyleSheet.create({
  scrollContainer: {
    padding: 16,
  },
  headerContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },
  pageTitle: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#333',
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 16,
  },
  cardContainer: {
    marginTop: 20,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 16,
    marginBottom: 16,
  },
  shadow: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 3,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 10,
    color: '#007bff',
  },
  cardText: {
    fontSize: 16,
    color: '#555',
    marginBottom: 5,
  },
  loaderContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  errorText: {
    color: '#dc3545',
    fontSize: 16,
    marginBottom: 10,
  },
  retryButton: {
    backgroundColor: '#007bff',
    padding: 10,
    borderRadius: 5,
  },
  retryButtonText: {
    color: '#fff',
    fontWeight: 'bold',
  },
});
