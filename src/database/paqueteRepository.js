import { db } from "./offlineDB";

export const savePaquetes = (paquetes) => {
  db.transaction(tx => {
    tx.executeSql("DELETE FROM paquetes;"); // VACIAMOS LA ANTERIOR PARA SOLO ACTUALIZAR LO QUE SE HIZO EN ESTA SESION
    paquetes.forEach(p => {
      tx.executeSql(
        "INSERT INTO paquetes (id, data) VALUES (?, ?);",
        [p.id, JSON.stringify(p)]
      );
    });
  });
};

export const getPaquetesOffline = () =>
  new Promise((resolve) => {
    db.transaction(tx => {
      tx.executeSql("SELECT * FROM paquetes;", [], (_, result) => {
        const rows = result.rows._array.map(p => JSON.parse(p.data));
        resolve(rows);
      });
    });
});
