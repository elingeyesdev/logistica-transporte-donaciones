// Configuración base de la API Laravel
// Para desarrollo: usa la IP de tu PC en la red local
// Si usas emulador Android: 10.0.2.2:8000
// Si usas dispositivo físico/Expo Go: tu IP local (ej: 192.168.0.18:8000)
export const API_BASE_URL = __DEV__ 
  ? 'http://10.26.4.120:8000/api'  
  : 'http://127.0.0.1:8000/api';    // Producción

// Configuración para fetch/axios
export const API_CONFIG = {
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
};
