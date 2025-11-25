import axios from 'axios';
import { API_BASE_URL } from '../config/api';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

export const getEstados = async () => {
  try {
    const response = await axiosInstance.get('/estado');
    console.log('Estados API full response:', response.data);

    const estados = response.data?.data?.data || [];
    return estados;
  } catch (error) {
    console.error('Error al obtener estados:', error.response?.data || error.message);
    throw error;
  }
};
