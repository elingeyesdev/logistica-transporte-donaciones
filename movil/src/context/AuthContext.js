import { createContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';

export const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);

  useEffect(() => {
    const loadSession = async () => {
      const userStr = await AsyncStorage.getItem('authUser');
      if (userStr) {
        setUser(JSON.parse(userStr));
      }
    };
    loadSession();
  }, []);

  const login = async (userData) => {
    setUser(userData);
    await AsyncStorage.setItem('authUser', JSON.stringify(userData));
  };

  const logout = async () => {
    setUser(null);
    await AsyncStorage.removeItem('authUser');
    await AsyncStorage.removeItem('authToken'); 
  };

  return (
    <AuthContext.Provider value={{ user, setUser, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
