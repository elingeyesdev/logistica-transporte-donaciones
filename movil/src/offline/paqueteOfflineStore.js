// src/offline/paqueteOfflineStore.js
import AsyncStorage from '@react-native-async-storage/async-storage';

const KEY = 'offline_paquetes_v1';

export const savePaquetesOffline = async (paquetes) => {
  try {
    await AsyncStorage.setItem(KEY, JSON.stringify(paquetes || []));
  } catch (e) {
    console.log('Error guardando paquetes offline:', e);
  }
};

export const getPaquetesOffline = async () => {
  try {
    const raw = await AsyncStorage.getItem(KEY);
    if (!raw) return [];
    return JSON.parse(raw);
  } catch (e) {
    console.log('Error leyendo paquetes offline:', e);
    return [];
  }
};
