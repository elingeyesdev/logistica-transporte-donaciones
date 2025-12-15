import axios from 'axios';
import { API_BASE_URL } from '../config/api';
import { api } from './apiClient';
import NetInfo from '@react-native-community/netinfo';
import {
  saveSolicitudesOffline,
  getSolicitudesOffline,
} from '../offline/solicitudOfflineStore';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

export const getSolicitudes = async () => {
  const net = await NetInfo.fetch();
  if (!net.isConnected) {
    const offline = await getSolicitudesOffline();
    console.log('Usando solicitudes desde cache offline:', offline.length);
    return offline;
  }

  try {
    const response = await api.get('/solicitud');
    console.log('Solicitudes API respuesta:', response.data);
    const solicitudes = response.data?.data || [];
    await saveSolicitudesOffline(solicitudes);

    return solicitudes;
  } catch (error) {
    console.error(
      'Error al obtener solicitudes online, intentando cache offline:',
      error.response?.data || error.message
    );

    const offline = await getSolicitudesOffline();
    return offline;
  }
};



export const approveSolicitud = async (id) => {
  try {
    const response = await api.post(`/solicitud/${id}/aprobar`, null, {
      timeout: 30000,
    });
    console.log('Solicitud aprobada:', response.data);
    return response.data;
  } catch (error) {
    const isTimeout =
      error.code === 'ECONNABORTED' ||
      (error.message && error.message.toLowerCase().includes('timeout'));

    console.error(
      'Error al aprobar solicitud:',
      error.response?.data || error.message
    );
    throw { ...error, isTimeout };
  }
};

export const denySolicitud = async (id, justificacion) => {
  try {
    const response = await api.post(`/solicitud/${id}/negar`, { justificacion }, {
      timeout: 30000,
    });
    console.log('Solicitud negada:', response.data);
    return response.data;
  } catch (error) {
    const isTimeout =
      error.code === 'ECONNABORTED' ||
      (error.message && error.message.toLowerCase().includes('timeout'));

    console.error(
      'Error al negar solicitud:',
      error.response?.data || error.message
    );
    throw { ...error, isTimeout };
  }
};
