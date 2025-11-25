import * as React from 'react';
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

const Stack = createNativeStackNavigator();

export default function App() {
  const [initialRoute, setInitialRoute] = React.useState('Login');
  const [checkingSession, setCheckingSession] = React.useState(true);

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
          options={{ title: 'Gestión de Paquetes' }}
        />
        <Stack.Screen
          name="ListadoSolicitud"
          component={ListadoSolicitudScreen}
          options={{ title: 'Listado de Solicitudes' }}
        />
        <Stack.Screen
          name="TipoEmergencia"
          component={TipoEmergenciaScreen}
          options={{ title: 'Listado de Solicitudes' }}
        />
         <Stack.Screen
          name="Estado"
          component={EstadoScreen}
          options={{ title: 'Gestión de Estados' }}
        />
        <Stack.Screen
          name="Solicitantes"
          component={SolicitantesScreen}
          options={{ title: 'Gestión de Solicitantes' }}
        />
        <Stack.Screen
          name="Destino"
          component={DestinoScreen}
          options={{ title: 'Gestión de Destinos' }}
        />
        <Stack.Screen
          name="Ubicaciones"
          component={UbicacionesScreen}
          options={{ title: 'Gestión de Ubicaciones' }}
        />
        <Stack.Screen
          name="Voluntario"
          component={VoluntarioScreen}
          options={{ title: 'Gestión de Voluntarios' }}
        />
        <Stack.Screen
          name="Reporte"
          component={ReporteScreen}
          options={{ title: 'Gestión de Reportes' }}
        />
        <Stack.Screen
          name="SeguimientoPaquete"
          component={SeguimientoPaqueteScreen}
          options={{ title: 'Seguimiento de Paquetes' }}
        />
        <Stack.Screen
          name="Licencias"
          component={LicenciasScreen}
          options={{ title: 'Gestión de Licencias' }}
        />
        <Stack.Screen
          name="Conductores"
          component={ConductoresScreen}
          options={{ title: 'Gestión de Conductores' }}
        />
        <Stack.Screen
          name="Marcas"
          component={MarcasScreen}
          options={{ title: 'Gestión de Marcas' }}
        />
        <Stack.Screen
          name="Vehiculos"
          component={VehiculosScreen}
          options={{ title: 'Gestión de Vehículos' }}
        />
        <Stack.Screen
          name="TipoVehiculo"
          component={TipoVehiculoScreen}
          options={{ title: 'Gestión de Tipos de Vehículo' }}
        />
        <Stack.Screen
          name="Roles"
          component={RolesScreen}
          options={{ title: 'Gestión de Roles' }}
        />
      </Stack.Navigator>
      </NavigationContainer>
    </SafeAreaProvider>
  );
}
