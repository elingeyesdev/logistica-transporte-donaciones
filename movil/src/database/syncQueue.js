import { db } from "./offlineDB";

export const queuePaqueteUpdate = (paquete_id, payload) => {
  db.transaction(tx => {
    tx.executeSql(
      "INSERT INTO paquete_updates (paquete_id, payload) VALUES (?, ?);",
      [paquete_id, JSON.stringify(payload)]
    );
  });
};

export const getPendingUpdates = () =>
  new Promise((resolve) => {
    db.transaction(tx => {
      tx.executeSql(
        "SELECT * FROM paquete_updates;",
        [],
        (_, result) => resolve(result.rows._array)
      );
    });
  });

export const removePendingUpdate = (id) => {
  db.transaction(tx => {
    tx.executeSql("DELETE FROM paquete_updates WHERE id = ?;", [id]);
  });
};
