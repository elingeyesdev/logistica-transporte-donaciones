// Configuración base de la API Laravel
// Desarrollo: usa la IP de tu PC en la red local (Wi‑Fi)
// - Emulador Android: 10.0.2.2:8000
// - Dispositivo físico/Expo Go: tu IP local (ej: 192.168.0.18:8000)
export const API_BASE_URL = __DEV__
  ? 'http://192.168.10.162:8000/api'
  : 'http://127.0.0.1:8000/api';

import AsyncStorage from '@react-native-async-storage/async-storage';

export const getAuthToken = async () => {
  try {
    const token = await AsyncStorage.getItem('authToken');
    console.log('Token obtenido:', token); 
    return token ? `Bearer ${token}` : null;
  } catch (error) {
    console.error('Error al obtener el token de autenticación:', error);
    return null;
  }
};

export const getApiConfig = async () => {
  const token = await getAuthToken();
  const config = {
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(token && { Authorization: token }),
    },
  };
  console.log('Configuración de la solicitud:', config); // Depuración
  return config;
};
