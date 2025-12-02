import AsyncStorage from '@react-native-async-storage/async-storage';

const KEY_SOLICITUDES = 'offline_solicitudes_v1';

export const saveSolicitudesOffline = async (solicitudes) => {
  try {
    await AsyncStorage.setItem(KEY_SOLICITUDES, JSON.stringify(solicitudes || []));
  } catch (e) {
    console.log('Error guardando solicitudes offline:', e);
  }
};

export const getSolicitudesOffline = async () => {
  try {
    const raw = await AsyncStorage.getItem(KEY_SOLICITUDES);
    if (!raw) return [];
    return JSON.parse(raw);
  } catch (e) {
    console.log('Error leyendo solicitudes offline:', e);
    return [];
  }
};
