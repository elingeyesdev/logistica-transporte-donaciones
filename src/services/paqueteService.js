import axios from 'axios';
import { API_BASE_URL } from '../config/api';
import { api } from './apiClient';
import NetInfo from '@react-native-community/netinfo';
import { savePaquetesOffline, getPaquetesOffline } from '../offline/paqueteOfflineStore';
import { queuePaqueteUpdate } from '../offline/queueOfflineUpdates';

const axiosInstance = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

export const getPaquetes = async () => {

  const net = await NetInfo.fetch();

  if (!net.isConnected) {
    const offline = await getPaquetesOffline();
    console.log('Usando paquetes desde cache offline:', offline.length);
    return offline;
  }
  try {
    const response = await api.get('/paquete');
    console.log('Paquetes API respuesta:', response.data);
    const paquetes = response.data?.data?.data || [];
    await savePaquetesOffline(paquetes);
    return paquetes;
  } catch (error) {
    //console.error('Error al obtener paquetes:', error.response?.data || error.message);
    //throw error;
    console.error(
      'Error al obtener paquetes online, intentando cache offline:',
      error.response?.data || error.message
    );

    const offline = await getPaquetesOffline();
    return offline;
  }
};

export const updatePaquete = async (id, data, options = {}) => {
  const net = await NetInfo.fetch();
  if (!net.isConnected && !options.forceOnline) {
    await queuePaqueteUpdate(id, data);
    console.log('Sin conexión. Actualización encolada para paquete:', id);
    return { success: true, offline: true };
  }
  const formData = new FormData();
  formData.append('id_solicitud', String(data.id_solicitud));
  formData.append('estado_id', String(data.estado_id));
  formData.append('codigo', data.codigo);

  if (data.zona) formData.append('zona', data.zona);
  if (data.fecha_entrega) formData.append('fecha_entrega', data.fecha_entrega);
  if (data.latitud) formData.append('latitud', String(data.latitud));
  if (data.longitud) formData.append('longitud', String(data.longitud));

  if (data.id_conductor != null) {
    formData.append('id_conductor', String(data.id_conductor));
  }
  if (data.id_vehiculo != null) {
    formData.append('id_vehiculo', String(data.id_vehiculo));
  }

  if (data.imagenUri) {
    const uri = data.imagenUri;
    const filename = uri.split('/').pop() || 'evidencia.jpg';
    const ext = (filename.split('.').pop() || '').toLowerCase();
    const mime =
      ext === 'png' ? 'image/png'
      : ext === 'webp' ? 'image/webp'
      : 'image/jpeg';

    formData.append('imagen', {
      uri,
      name: filename,
      type: mime,
    });
  }

   formData.append('_method', 'PUT');

  try {
    const res = await api.post(`/paquete/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Accept: 'application/json',
      },
    });
    return res.data;
  } catch (error) {
    console.error(
      'Error al actualizar paquete:',
      error.response?.data || error.message
    );
    throw error;
  }

};

