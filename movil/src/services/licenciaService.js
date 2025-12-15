import axios from 'axios';
import { API_BASE_URL } from '../config/api';
import { api } from './apiClient';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const getLicencias = async () => {
  try {
    const response = await api.get('/tipo-licencia');
    console.log('Respuesta completa de la API:', response.data);
    
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
    const response = await api.post('/tipo-licencia', licenciaData);
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
    const response = await api.put(`/tipo-licencia/${id}`, licenciaData);
    console.log('Licencia actualizada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al actualizar licencia:', error);
    throw error;
  }
};

export const deleteLicencia = async (id) => {
  try {
    const response = await api.delete(`/tipo-licencia/${id}`);
    console.log('Licencia eliminada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al eliminar licencia:', error);
    throw error;
  }
};
