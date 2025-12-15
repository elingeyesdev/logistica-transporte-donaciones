import axios from 'axios';
import { API_BASE_URL } from '../config/api';
import { api } from './apiClient';

const MARCA_ENDPOINT = `${API_BASE_URL}/marca`;

const axiosInstance = axios.create({
  timeout: 10000, 
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

export const getMarcas = async () => {
  try {
    const response = await api.get(MARCA_ENDPOINT);
    console.log('Respuesta completa de la API:', response.data);
    
    const marcas = response.data.data.data || [];
    console.log('Marcas extraídas:', marcas);
    
    return marcas;
  } catch (error) {
    console.error('Error al obtener marcas:', error);
    throw error;
  }
};

export const createMarca = async (marcaData) => {
  try {
    const response = await api.post(MARCA_ENDPOINT, marcaData);
    console.log('Marca creada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al crear marca:', error);
    throw error;
  }
};

export const updateMarca = async (id, marcaData) => {
  try {
    const response = await api.put(`${MARCA_ENDPOINT}/${id}`, marcaData);
    console.log('Marca actualizada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al actualizar marca:', error);
    throw error;
  }
};

export const deleteMarca = async (id) => {
  try {
    const response = await api.delete(`${MARCA_ENDPOINT}/${id}`);
    console.log('Marca eliminada:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error al eliminar marca:', error);
    throw error;
  }
};
