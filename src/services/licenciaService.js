import axios from 'axios';
import { API_BASE_URL } from '../config/api';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const getLicencias = async () => {
  try {
    const response = await axiosInstance.get('/tipo-licencia');
    console.log('Respuesta completa de la API:', response.data);
    
    // Laravel devuelve: { success: true, data: { data: [...], pagination_info } }
    const licencias = response.data.data.data || [];
    console.log('Licencias extraídas:', licencias);
    
    return licencias;
  } catch (error) {
    console.error('Error al obtener licencias:', error);
    throw error;
  }
};

export const createLicencia = async (licenciaData) => {
  try {
    console.log('Datos enviados:', licenciaData);
    const response = await axiosInstance.post('/tipo-licencia', licenciaData);
    console.log('Licencia creada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al crear licencia:', error);
    console.error('Respuesta del error:', error.response?.data);
    throw error;
  }
};

export const updateLicencia = async (id, licenciaData) => {
  try {
    const response = await axiosInstance.put(`/tipo-licencia/${id}`, licenciaData);
    console.log('Licencia actualizada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al actualizar licencia:', error);
    throw error;
  }
};

export const deleteLicencia = async (id) => {
  try {
    const response = await axiosInstance.delete(`/tipo-licencia/${id}`);
    console.log('Licencia eliminada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al eliminar licencia:', error);
    throw error;
  }
};
