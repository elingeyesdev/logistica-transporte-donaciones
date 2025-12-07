import React, { useEffect, useRef, useState, useContext } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Animated, Modal } from 'react-native';
import { FontAwesome5 } from '@expo/vector-icons';
import { adminlteColors } from '../theme/adminlte';
import { AuthContext } from '../context/AuthContext';

const adminMenu = [
  {
    id: 'dashboard',
    label: 'Dashboard',
    icon: 'tachometer-alt',
    route: 'Dashboard',
  },
  {
    id: 'paquete',
    label: 'Paquetes',
    icon: 'box',
    route: 'Paquete',
  },
  {
    id: 'listadoSolicitud',
    label: 'Listado de Solicitudes',
    icon: 'list',
    route: 'ListadoSolicitud',
  },
  {
    id: 'licencias',
    label: 'Licencias',
    icon: 'certificate',
    route: 'Licencias',
  },
  {
    id: 'conductores',
    label: 'Conductores',
    icon: 'id-badge',
    route: 'Conductores',
  },
  {
    id: 'marcas',
    label: 'Marcas',
    icon: 'tag',
    route: 'Marcas',
  },
  {
    id: 'vehiculos',
    label: 'Vehículos Registrados',
    icon: 'car',
    route: 'Vehiculos',
  },
  {
    id: 'tipoVehiculo',
    label: 'Tipos de Vehículo',
    icon: 'truck-monster',
    route: 'TipoVehiculo',
  },
  {
    id: 'roles',
    label: 'Roles del Sistema',
    icon: 'user-shield',
    route: 'Roles',
  },
    {
    id: 'voluntario',
    label: 'Voluntarios',
    icon: 'user-alt',
    route: 'Voluntario',
  },
];

const voluntarioMenu = [
  {
    id: 'paquete',
    label: 'Paquetes',
    icon: 'box',
    route: 'Paquete',
  },
  {
    id: 'listadoSolicitud',
    label: 'Listado de Solicitudes',
    icon: 'list',
    route: 'ListadoSolicitud',
  },
  {
    id: 'conductores',
    label: 'Conductores',
    icon: 'id-badge',
    route: 'Conductores',
  },
  {
    id: 'marcas',
    label: 'Marcas',
    icon: 'tag',
    route: 'Marcas',
  },
  {
    id: 'vehiculos',
    label: 'Vehículos Registrados',
    icon: 'car',
    route: 'Vehiculos',
  },
  {
    id: 'tipoVehiculo',
    label: 'Tipos de Vehículo',
    icon: 'truck-monster',
    route: 'TipoVehiculo',
  },

];

export default function Sidebar({ isVisible, onClose, navigation }) {
  const slideAnim = useRef(new Animated.Value(-250)).current;
  const overlayAnim = useRef(new Animated.Value(0)).current;
  const [shouldRender, setShouldRender] = useState(false);
  const [showLogoutConfirm, setShowLogoutConfirm] = useState(false);
  const { user, logout } = useContext(AuthContext);
  const isAdmin = !!user && (
    user.administrador === true || user.administrador === 1 || user.administrador === '1' || user.role === 'admin' || user.roles?.includes?.('admin')         // por si luego mandas un array de roles
  );

const finalMenu = isAdmin ? adminMenu : voluntarioMenu;

  useEffect(() => {
    if (isVisible) {
      setShouldRender(true);
      Animated.parallel([
        Animated.timing(slideAnim, {
          toValue: 0,
          duration: 300,
          useNativeDriver: true,
        }),
        Animated.timing(overlayAnim, {
          toValue: 1,
          duration: 300,
          useNativeDriver: true,
        }),
      ]).start();
    } else {
      Animated.parallel([
        Animated.timing(slideAnim, {
          toValue: -250,
          duration: 300,
          useNativeDriver: true,
        }),
        Animated.timing(overlayAnim, {
          toValue: 0,
          duration: 300,
          useNativeDriver: true,
        }),
      ]).start(() => {
        if (!isVisible) {
          setShouldRender(false);
        }
      });
    }
  }, [isVisible]);

  if (!shouldRender && !isVisible) return null;

  const handleNavigate = (route) => {
    if (navigation) {
      navigation.navigate(route);
    }
    onClose();
  };

   const handleConfirmLogout = async () => {
    try {
      setShowLogoutConfirm(false);
      if (onClose) {
        onClose();
      }

      await logout();
      if (navigation) {
        navigation.reset({
          index: 0,
          routes: [{ name: 'Login' }],
        });
      }
    } catch (err) {
      console.log('Error en logout:', err);
    }
  };

  return (
    <>
      <Animated.View
        style={[
          styles.overlay,
          {
            opacity: overlayAnim,
          },
        ]}
      >
        <TouchableOpacity
          style={StyleSheet.absoluteFill}
          activeOpacity={1}
          onPress={onClose}
        />
      </Animated.View>
      
      <Animated.View
        style={[
          styles.sidebar,
          {
            transform: [{ translateX: slideAnim }],
          },
        ]}
      >
        <View style={styles.sidebarHeader}>
          <Text style={styles.sidebarTitle}>D.A.S</Text>
          <TouchableOpacity onPress={onClose} style={styles.closeButton}>
            <FontAwesome5 name="times" size={18} color="#ffffff" />
          </TouchableOpacity>
        </View>

        <ScrollView style={styles.sidebarBody}>
          <View style={styles.menuSection}>
            <Text style={styles.menuSectionTitle}>MENÚ PRINCIPAL</Text>
            {finalMenu.map(item => (
              <TouchableOpacity
                key={item.id}
                style={styles.menuItem}
                onPress={() => handleNavigate(item.route)}
              >
                <FontAwesome5
                  name={item.icon}
                  size={16}
                  color="#c2c7d0"
                  style={styles.menuIcon}
                />
                <Text style={styles.menuItemText}>{item.label}</Text>
              </TouchableOpacity>
            ))}
          </View>
        </ScrollView>

        <View style={styles.sidebarFooter}>
          <TouchableOpacity
            style={styles.logoutButton}
            onPress={() => setShowLogoutConfirm(true)}
          >
            <FontAwesome5
              name="sign-out-alt"
              size={16}
              color="#dc3545"
              padding="20px"
              style={styles.logoutIcon}
            />
            <Text style={styles.logoutText}>Cerrar sesión</Text>
          </TouchableOpacity>

          <Text style={styles.sidebarFooterText}>Versión 1.0</Text>
          
        </View>
      </Animated.View>
      <Modal
        visible={showLogoutConfirm}
        transparent
        animationType="fade"
        onRequestClose={() => setShowLogoutConfirm(false)}
      >
        <View style={styles.logoutOverlay}>
          <View style={styles.logoutModal}>
            <Text style={styles.logoutTitle}>Cerrar sesión</Text>
            <Text style={styles.logoutMessage}>
              ¿Seguro que deseas cerrar sesión?
            </Text>

            <View style={styles.logoutButtonsRow}>
              <TouchableOpacity
                style={styles.logoutCancelButton}
                onPress={() => setShowLogoutConfirm(false)}
              >
                <Text style={styles.logoutCancelText}>Cancelar</Text>
              </TouchableOpacity>

              <TouchableOpacity
                style={styles.logoutConfirmButton}
                onPress={handleConfirmLogout}
              >
                <Text style={styles.logoutConfirmText}>Cerrar sesión</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>
    </>
  );
}

const styles = StyleSheet.create({
  overlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    zIndex: 998,
    paddingTop:10,
  },
  sidebar: {
    position: 'absolute',
    top: 0,
    left: 0,
    bottom: 0,
    width: 250,
    backgroundColor: '#343a40',
    zIndex: 999,
    elevation: 5,
  },
  sidebarHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 16,
    backgroundColor: '#212529',
    borderBottomWidth: 1,
    borderBottomColor: '#495057',
  },
  sidebarTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: '#ffffff',
  },
  closeButton: {
    padding: 4,
  },
  sidebarBody: {
    flex: 1,
    paddingTop: 8,
  },
  menuSection: {
    paddingVertical: 60,
    
  },
  menuSectionTitle: {
    fontSize: 11,
    fontWeight: '700',
    color: '#6c757d',
    paddingHorizontal: 16,
    paddingVertical: 8,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  menuIcon: {
    marginRight: 12,
    width: 20,
  },
  menuItemText: {
    fontSize: 14,
    color: '#c2c7d0',
    fontWeight: '500',
  },
  sidebarFooter: {
    paddingHorizontal: 10,
    paddingVertical: 20,
    borderTopWidth: 1,
    borderTopColor: '#495057',
    backgroundColor: '#212529',
  },
  sidebarFooterText: {
    fontSize: 12,
    color: '#6c757d',
    textAlign: 'center',
  },
 logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    marginBottom: 4,
  },
  logoutIcon: {
    marginRight: 8,
  },
  logoutText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#dc3545',
  },
  logoutOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  logoutModal: {
    width: '80%',
    backgroundColor: '#ffffff',
    borderRadius: 8,
    padding: 20,
  },
  logoutTitle: {
    fontSize: 18,
    fontWeight: '700',
    marginBottom: 8,
    color: adminlteColors.dark,
    textAlign: 'center',
  },
   logoutMessage: {
    fontSize: 14,
    color: adminlteColors.muted,
    marginBottom: 16,
    textAlign: 'center',
  },
  logoutButtonsRow: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
  },
  logoutCancelButton: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    marginRight: 8,
    borderRadius: 4,
    backgroundColor: '#e9ecef',
  },
  logoutCancelText: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  logoutConfirmButton: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
    backgroundColor: adminlteColors.danger || '#dc3545',
  },
  logoutConfirmText: {
    fontSize: 14,
    color: '#ffffff',
    fontWeight: '600',
  },

});

