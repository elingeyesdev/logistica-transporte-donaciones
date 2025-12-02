import React, { useEffect } from "react";
import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { adminlteColors } from './src/theme/adminlte';
import DashboardScreen from './src/screens/DashboardScreen';
import SolicitudScreen from './src/screens/SolicitudScreen';
import ListadoSolicitudScreen from './src/screens/ListadoSolicitudScreen';
import PaqueteScreen from './src/screens/PaqueteScreen';
import TipoEmergenciaScreen from './src/screens/TipoEmergenciaScreen';
import EstadoScreen from './src/screens/EstadoScreen';
import SolicitantesScreen from './src/screens/SolicitantesScreen';
import DestinoScreen from './src/screens/DestinoScreen';
import UbicacionesScreen from './src/screens/UbicacionesScreen';
import VoluntarioScreen from './src/screens/VoluntarioScreen';
import ReporteScreen from './src/screens/ReporteScreen';
import SeguimientoPaqueteScreen from './src/screens/SeguimientoPaqueteScreen';
import LicenciasScreen from './src/screens/LicenciasScreen';
import ConductoresScreen from './src/screens/ConductoresScreen';
import MarcasScreen from './src/screens/MarcasScreen';
import VehiculosScreen from './src/screens/VehiculosScreen';
import TipoVehiculoScreen from './src/screens/TipoVehiculoScreen';
import RolesScreen from './src/screens/RolesScreen';

import LoginScreen from './src/screens/LoginScreen';
import { getStoredSession } from './src/services/authService';
import { AuthProvider } from './src/context/AuthContext';
import { AdminOnly } from './src/navigation/ProtectedScreens';

import { listenConnection } from "./src/offline/connectivity";
import { syncPending } from "./src/offline/syncManager";

const Stack = createNativeStackNavigator();

export default function App() {
  const [initialRoute, setInitialRoute] = React.useState('Login');
  const [checkingSession, setCheckingSession] = React.useState(true);
  useEffect(() => {
    const unsub = listenConnection((online) => {
      if (online) {
        syncPending();
      }
    });

    return () => {
      if (unsub) unsub();
    };
  }, []);
  React.useEffect(() => {
    const checkSession = async () => {
      try {
        const { token } = await getStoredSession();
        if (token) {
          setInitialRoute('ListadoSolicitud');
        } else {
          setInitialRoute('Login');
        }
      } catch (e) {
        setInitialRoute('Login');
      } finally {
        setCheckingSession(false);
      }
    };

    checkSession();
  }, []);

  if (checkingSession) {
    return null;
  }

  return (
    <AuthProvider>
      <SafeAreaProvider>
        <NavigationContainer>
        <StatusBar style="light" />
        <Stack.Navigator
          initialRouteName={initialRoute}
          screenOptions={{
            headerShown: false,
            contentStyle: {
              backgroundColor: adminlteColors.bodyBg,
            },
          }}
        > 
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{ title: 'Iniciar sesión' }}
        />

        <Stack.Screen
          name="Dashboard"
          component={AdminOnly(DashboardScreen)}
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
          options={{ title: 'Paquetes' }}
        />
        <Stack.Screen
          name="ListadoSolicitud"
          component={ListadoSolicitudScreen}
          options={{ title: 'Listado de Solicitudes' }}
        />
        <Stack.Screen
          name="TipoEmergencia"
          component={AdminOnly(TipoEmergenciaScreen)}
          options={{ title: 'Tipos de Emergencia' }}
        />
         <Stack.Screen
          name="Estado"
          component={AdminOnly(TipoEmergenciaScreen)}
          options={{ title: 'Estados de Paquete' }}
        />
        <Stack.Screen
          name="Solicitantes"
          component={SolicitantesScreen}
          options={{ title: 'Solicitantes' }}
        />
        <Stack.Screen
          name="Destino"
          component={DestinoScreen}
          options={{ title: 'Destinos' }}
        />
        <Stack.Screen
          name="Ubicaciones"
          component={UbicacionesScreen}
          options={{ title: 'Ubicaciones' }}
        />
        <Stack.Screen
          name="Voluntario"
          component={VoluntarioScreen}
          options={{ title: 'Voluntarios' }}
        />
        <Stack.Screen
          name="Reporte"
          component={AdminOnly(ReporteScreen)}
          options={{ title: 'Gestión de Reportes' }}
        />
        <Stack.Screen
          name="SeguimientoPaquete"
          component={SeguimientoPaqueteScreen}
          options={{ title: 'Seguimiento de Paquetes' }}
        />
        <Stack.Screen
          name="Licencias"
          component={AdminOnly(LicenciasScreen)}
          options={{ title: 'Licencias' }}
        />
        <Stack.Screen
          name="Conductores"
          component={ConductoresScreen}
          options={{ title: 'Conductores' }}
        />
        <Stack.Screen
          name="Marcas"
          component={MarcasScreen}
          options={{ title: 'Marcas' }}
        />
        <Stack.Screen
          name="Vehiculos"
          component={VehiculosScreen}
          options={{ title: 'Vehículos' }}
        />
        <Stack.Screen
          name="TipoVehiculo"
          component={TipoVehiculoScreen}
          options={{ title: 'Tipos de Vehículo' }}
        />
        <Stack.Screen
          name="Roles"
          component={AdminOnly(RolesScreen)}
          options={{ title: ' Roles Registrados' }}
        />
      </Stack.Navigator>
      </NavigationContainer>
      </SafeAreaProvider>
  </AuthProvider>
  );
}
