import axios from 'axios';
import { API_BASE_URL } from '../config/api';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const getVehiculos = async () => {
  try {
    const response = await axiosInstance.get('/vehiculo');
    console.log('Respuesta completa de la API:', response.data);
    
    // Laravel devuelve: { success: true, data: { data: [...], pagination_info } }
    const vehiculos = response.data.data.data || [];
    console.log('Vehículos extraídos:', vehiculos);
    
    return vehiculos;
  } catch (error) {
    console.error('Error al obtener vehículos:', error);
    throw error;
  }
};

export const createVehiculo = async (vehiculoData) => {
  try {
    console.log('Datos enviados:', vehiculoData);
    const response = await axiosInstance.post('/vehiculo', vehiculoData);
    console.log('Vehículo creado:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al crear vehículo:', error);
    console.error('Respuesta del error:', error.response?.data);
    throw error;
  }
};

export const updateVehiculo = async (id, vehiculoData) => {
  try {
    const response = await axiosInstance.put(`/vehiculo/${id}`, vehiculoData);
    console.log('Vehículo actualizado:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al actualizar vehículo:', error);
    throw error;
  }
};

export const deleteVehiculo = async (id) => {
  try {
    const response = await axiosInstance.delete(`/vehiculo/${id}`);
    console.log('Vehículo eliminado:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al eliminar vehículo:', error);
    throw error;
  }
};
