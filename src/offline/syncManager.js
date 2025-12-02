// src/offline/syncManager.js
import { getPendingUpdates, removePendingUpdate } from './queueOfflineUpdates';
import { updatePaquete } from '../services/paqueteService';

export const syncPending = async () => {
  const pendings = await getPendingUpdates();
  if (!pendings.length) return;

  console.log('Sincronizando actualizaciones pendientes:', pendings.length);

  for (const item of pendings) {
    try {
      await updatePaquete(item.paqueteId, item.payload, { forceOnline: true });
      await removePendingUpdate(item.queueId);
      console.log('Actualización sincronizada y removida de la cola:', item.queueId);
    } catch (e) {
      console.log('Error sincronizando una actualización, se reintentará luego:', e);
    }
  }
};
