// Configuración base de la API Laravel
// Desarrollo: usa la IP de tu PC en la red local (Wi‑Fi)
// - Emulador Android: 10.0.2.2:8000
// - Dispositivo físico/Expo Go: tu IP local (ej: 10.26.4.136:8000)
export const API_BASE_URL = __DEV__
  ? 'http://10.26.4.136:8000/api'
  : 'http://127.0.0.1:8000/api';

// Configuración para fetch/axios
export const API_CONFIG = {
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
};
