import axios from 'axios';
import { API_BASE_URL } from '../config/api';
import AsyncStorage from '@react-native-async-storage/async-storage';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

axiosInstance.interceptors.request.use(async config => {
  const token = await AsyncStorage.getItem('authToken');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const fetchVoluntarios = async () => {
  const res = await axiosInstance.get('/usuario');

  const users = res.data?.users || [];

  return users.map(u => ({
    id: u.id,
    nombre: u.nombre ?? '',
    apellido: u.apellido ?? '',
    correo: u.correo_electronico ?? u.email ?? '',
    telefono: u.telefono ?? '',
    ci: u.ci ?? '',
    rol: u.rol?.titulo_rol ?? 'Sin rol',
    administrador: Boolean(u.administrador),
    activo: Boolean(u.activo),
    created_at:u.created_at ?? null
  }));
};

export const toggleAdminUser = async id => {
  const res = await axiosInstance.post(`/usuario/${id}/toggle-admin`);
  return res.data;
};

export const toggleActivoUser = async id => {
  const res = await axiosInstance.post(`/usuario/${id}/toggle-activo`);
  return res.data;
};
