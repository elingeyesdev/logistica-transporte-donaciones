import AsyncStorage from '@react-native-async-storage/async-storage';

const KEY = 'offline_paquete_updates_v1';

const readQueue = async () => {
  try {
    const raw = await AsyncStorage.getItem(KEY);
    if (!raw) return [];
    return JSON.parse(raw);
  } catch (e) {
    console.log('Error leyendo cola offline:', e);
    return [];
  }
};

const writeQueue = async (queue) => {
  try {
    await AsyncStorage.setItem(KEY, JSON.stringify(queue || []));
  } catch (e) {
    console.log('Error guardando cola offline:', e);
  }
};

export const queuePaqueteUpdate = async (paqueteId, payload) => {
  const queue = await readQueue();
  const queueId = `${Date.now()}-${paqueteId}`;
  queue.push({
    queueId,
    paqueteId,
    payload,
  });
  await writeQueue(queue);
  return queueId;
};

export const getPendingUpdates = async () => {
  return await readQueue();
};

export const removePendingUpdate = async (queueId) => {
  const queue = await readQueue();
  const filtered = queue.filter((item) => item.queueId !== queueId);
  await writeQueue(filtered);
};
