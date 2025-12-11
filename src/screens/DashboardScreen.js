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
        total: response.data.total || 0,
        aceptadas: response.data.aceptadas || 0,
        rechazadas: response.data.rechazadas || 0,
        tasa: response.data.tasa || 0,
        totalVoluntarios: response.data.totalVoluntarios || 0,
        voluntariosConductores: response.data.voluntariosConductores || 0,
        totalPaquetes: response.data.totalPaquetes || 0,
        paquetesEntregados: response.data.paquetesEntregados || 0,
        paquetes: response.data.paquetes || [], 
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
          <SmallBox color="success" title="Solicitudes Aceptadas" value={data.aceptadas}  />
        </View>

        <View style={styles.row}>
          <SmallBox color="danger" title="Solicitudes Rechazadas" value={data.rechazadas}  />
          <SmallBox color="warning" title="Tasa de Aprobación" value={`${data.tasa}%`}  />
        </View>

        <View style={styles.row}>
          <SmallBox color="purple" title="Total Voluntarios" value={data.totalVoluntarios}  />
          <SmallBox color="teal" title="Conductores Registrados" value={data.voluntariosConductores}  />
        </View>

        <View style={styles.row}>
          <SmallBox color="primary" title="Total Paquetes" value={data.totalPaquetes}  />
          <SmallBox color="success" title="Paquetes Entregados" value={data.paquetesEntregados}  />
        </View>

    <View>
    <View >
    <Text style={styles.cardTitle}>Paquetes Entregados</Text>

    <ScrollView
      horizontal
      showsHorizontalScrollIndicator={false}
      contentContainerStyle={{ paddingHorizontal: 0, paddingBottom:40,paddingTop:10, margin:0}}
    >
      <View style={{ minWidth: 370}}> 
        <View style={[styles.tableHeaderRow, { paddingVertical: 6 }]}>
          <Text style={[styles.tableHeaderText, { flex: 0.1 }]}>Código</Text>
          <Text style={[styles.tableHeaderText, { flex: 0.2 }]}>Creado</Text>
          <Text style={[styles.tableHeaderText, { flex: 0.2 }]}>Entregado</Text>
          <Text style={[styles.tableHeaderText, { flex: 0.2}]}>
            Tiempo
          </Text>
        </View>

        {data.paquetes.length > 0 ? (
          data.paquetes.map((paq, index) => {
            const badgeStyle =
              paq.dias_entrega > 7
                ? styles.badgeDanger
                : paq.dias_entrega > 3
                ? styles.badgeWarning
                : styles.badgeSuccess;

            const codigo =
              paq?.solicitud?.codigo_seguimiento ??
              `Nº${paq.id_paquete ?? index + 1}`;

            const diasEntregaRedondeado = Math.round(paq.dias_entrega * 10) / 10;
            const formatDate = (dateStr) => {
              if (!dateStr) return '-';
              const d = new Date(dateStr);
              if (isNaN(d.getTime())) return dateStr;

              const day = String(d.getDate()).padStart(2, '0');
              const month = String(d.getMonth() + 1).padStart(2, '0');
              const year = d.getFullYear();

              return `${day}/${month}/${year}`;
            };


            return (
              <View
                key={paq.id_paquete ?? index}
                style={[styles.tableRow, { paddingVertical: 6 }]}
              >
                <Text
                  style={[styles.tableCell, { flex: 0.3 }]}
                  numberOfLines={1} 
                >
                  {codigo}
                </Text>

                <Text style={[styles.tableCell, { flex: 0.2 }]}>
                  {formatDate(paq.fecha_create)}
                </Text>

                <Text style={[styles.tableCell, { flex: 0.2 }]}>
                  {formatDate(paq.fecha_entrega)}
                </Text>

                <Text
                  style={[
                    styles.tableCell,
                    { flex: 0.2},
                  ]}
                >
                  <Text style={[styles.badge, badgeStyle]}>
                    {diasEntregaRedondeado} días
                  </Text>
                </Text>
              </View>
            );
          })
        ) : (
          <View style={[styles.tableRow, { paddingVertical: 8 }]}>
            <Text style={styles.noDataText}>
              No hay paquetes con fechas de entrega.
            </Text>
          </View>
        )}
      </View>
    </ScrollView>
  </View>
</View>

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
    gap: 16, 
  },
  cardContainer: {
    marginTop: 20,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 16,
    marginBottom: 16,
    marginHorizontal: 8, 
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
  tableHeaderRow: {
    flexDirection: 'row',
    backgroundColor: '#f8f9fa',
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  tableHeaderText: {
    fontWeight: 'bold',
    fontSize: 14,
    color: '#495057',
    textTransform: 'uppercase', 
    letterSpacing: 1, 
  },
  tableRow: {
    flexDirection: 'row',
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  tableCell: {
    fontSize: 14,
    color: '#212529',
    lineHeight: 20, 
  },
  badge: {
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    color: '#fff',
    fontSize: 12,
    overflow: 'hidden',
  },
  badgeSuccess: {
    backgroundColor: '#28a745',
  },
  badgeWarning: {
    backgroundColor: '#ffc107',
  },
  badgeDanger: {
    backgroundColor: '#dc3545',
  },
  noDataText: {
    fontSize: 14,
    color: '#6c757d',
    textAlign: 'center',
    paddingVertical: 16,
    letterSpacing: 0.5,
  },
});
