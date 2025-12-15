// src/offline/connectivity.js
import NetInfo from '@react-native-community/netinfo';

export const listenConnection = (callback) => {
  return NetInfo.addEventListener((state) => {
    callback(state.isConnected === true);
  });
};
