import axios from 'axios';
import { API_BASE_URL } from '../config/api';
import { api } from './apiClient';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

export const getRoles = async () => {
  try {
    const response = await api.get('/api/rol');
    console.log('Roles API respuesta:', response.data);

    const estados = response.data?.data?.data || [];
    return estados;
  } catch (error) {
    console.error('Error al obtener roles:', error.response?.data || error.message);
    throw error;
  }
};
