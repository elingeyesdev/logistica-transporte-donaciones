// Configuración base de la API Laravel
// Desarrollo: usa la IP de tu PC en la red local (Wi‑Fi)
// - Emulador Android: 10.0.2.2:8000
// - Dispositivo físico/Expo Go: tu IP local (ej: 192.168.0.18:8000)
export const API_BASE_URL = __DEV__
  ? 'http://10.26.14.33:8000/api'
  : 'http://127.0.0.1:8000/api';

// Función para obtener el token almacenado (ejemplo con AsyncStorage)
import AsyncStorage from '@react-native-async-storage/async-storage';

export const getAuthToken = async () => {
  try {
    const token = await AsyncStorage.getItem('authToken');
    console.log('Token obtenido:', token); // Depuración
    return token ? `Bearer ${token}` : null;
  } catch (error) {
    console.error('Error al obtener el token de autenticación:', error);
    return null;
  }
};

// Configuración para fetch/axios con autenticación dinámica
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
