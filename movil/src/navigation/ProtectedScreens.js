import React, { useContext } from 'react';
import { View, Text } from 'react-native';
import { AuthContext } from '../context/AuthContext';

export const AdminOnly = (Component) => {
  return (props) => {
    const { user } = useContext(AuthContext);

    if (!user?.administrador) {
      return (
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center' }}>
          <Text>Solo disponible para Administradores</Text>
        </View>
      );
    }

    return <Component {...props} />;
  };
};
