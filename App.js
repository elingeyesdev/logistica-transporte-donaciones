import * as React from 'react';
import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { adminlteColors } from './src/theme/adminlte';
import DashboardScreen from './src/screens/DashboardScreen';
import SolicitudScreen from './src/screens/SolicitudScreen';
import PaqueteScreen from './src/screens/PaqueteScreen';
import TipoEmergenciaScreen from './src/screens/TipoEmergenciaScreen';
const Stack = createNativeStackNavigator();

export default function App() {
  return (
    <SafeAreaProvider>
      <NavigationContainer>
        <StatusBar style="light" />
        <Stack.Navigator
        screenOptions={{
          headerShown: false, // Ocultamos el header nativo porque usamos nuestro propio header en AdminLayout
          contentStyle: {
            backgroundColor: adminlteColors.bodyBg,
          },
        }}
      >
        <Stack.Screen
          name="Dashboard"
          component={DashboardScreen}
          options={{ title: 'Dashboard' }}
        />
        <Stack.Screen
          name="Solicitud"
          component={SolicitudScreen}
          options={{ title: 'Solicitar Insumos' }}
        />
        <Stack.Screen
          name="Paquete"
          component={PaqueteScreen}
          options={{ title: 'Listado de Solicitudes' }}
        />
        <Stack.Screen
          name="TipoEmergencia"
          component={TipoEmergenciaScreen}
          options={{ title: 'Tipo de Emergencia' }}
        />
      </Stack.Navigator>
      </NavigationContainer>
    </SafeAreaProvider>
  );
}
