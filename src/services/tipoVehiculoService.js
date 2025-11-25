import axios from 'axios';
import { API_BASE_URL } from '../config/api';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const getTiposVehiculo = async () => {
  try {
    const response = await axiosInstance.get('/tipo-vehiculo');
    console.log('Respuesta completa de la API:', response.data);
    
    // Laravel devuelve: { success: true, data: { data: [...], pagination_info } }
    const tiposVehiculo = response.data.data.data || [];
    console.log('Tipos de vehículo extraídos:', tiposVehiculo);
    
    return tiposVehiculo;
  } catch (error) {
    console.error('Error al obtener tipos de vehículo:', error);
    throw error;
  }
};

export const createTipoVehiculo = async (tipoVehiculoData) => {
  try {
    console.log('Datos enviados:', tipoVehiculoData);
    const response = await axiosInstance.post('/tipo-vehiculo', tipoVehiculoData);
    console.log('Tipo de vehículo creado:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al crear tipo de vehículo:', error);
    console.error('Respuesta del error:', error.response?.data);
    throw error;
  }
};

export const updateTipoVehiculo = async (id, tipoVehiculoData) => {
  try {
    const response = await axiosInstance.put(`/tipo-vehiculo/${id}`, tipoVehiculoData);
    console.log('Tipo de vehículo actualizado:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al actualizar tipo de vehículo:', error);
    throw error;
  }
};

export const deleteTipoVehiculo = async (id) => {
  try {
    const response = await axiosInstance.delete(`/tipo-vehiculo/${id}`);
    console.log('Tipo de vehículo eliminado:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al eliminar tipo de vehículo:', error);
    throw error;
  }
};
