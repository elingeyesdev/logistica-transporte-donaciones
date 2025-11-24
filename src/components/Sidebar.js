import React, { useEffect, useRef, useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Animated } from 'react-native';
import { FontAwesome5 } from '@expo/vector-icons';
import { adminlteColors } from '../theme/adminlte';

const menuItems = [
  {
    id: 'dashboard',
    label: 'Dashboard',
    icon: 'tachometer-alt',
    route: 'Dashboard',
  },
  {
    id: 'solicitud',
    label: 'Solicitar Insumos',
    icon: 'file-alt',
    route: 'Solicitud',
  },
  {
    id: 'paquete',
    label: 'Listado de Solicitudes',
    icon: 'list',
    route: 'Paquete',
  },
  {
    id: 'tipoEmergencia',
    label: 'Tipo de Emergencia',
    icon: 'list',
    route: 'TipoEmergencia',
  },
  {
    id: 'estado',
    label: 'Gestión de Estados',
    icon: 'flag',
    route: 'Estado',
  },
  {
    id: 'solicitantes',
    label: 'Gestión de Solicitantes',
    icon: 'users',
    route: 'Solicitantes',
  },
  {
    id: 'destino',
    label: 'Gestión de Destinos',
    icon: 'map-marked-alt',
    route: 'Destino',
  },
  {
    id: 'ubicaciones',
    label: 'Gestión de Ubicaciones',
    icon: 'location-arrow',
    route: 'Ubicaciones',
  },
  {
    id: 'voluntario',
    label: 'Gestión de Voluntarios',
    icon: 'hands-helping',
    route: 'Voluntario',
  },
  {
    id: 'reporte',
    label: 'Gestión de Reportes',
    icon: 'file-invoice',
    route: 'Reporte',
  },
  {
    id: 'seguimientoPaquete',
    label: 'Seguimiento de Paquetes',
    icon: 'truck',
    route: 'SeguimientoPaquete',
  },
  {
    id: 'licencias',
    label: 'Gestión de Licencias',
    icon: 'certificate',
    route: 'Licencias',
  },
  {
    id: 'conductores',
    label: 'Gestión de Conductores',
    icon: 'id-badge',
    route: 'Conductores',
  },
  {
    id: 'marcas',
    label: 'Gestión de Marcas',
    icon: 'tag',
    route: 'Marcas',
  },
  {
    id: 'vehiculos',
    label: 'Gestión de Vehículos',
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
    label: 'Gestión de Roles',
    icon: 'user-shield',
    route: 'Roles',
  },
];

export default function Sidebar({ isVisible, onClose, navigation }) {
  const slideAnim = useRef(new Animated.Value(-250)).current;
  const overlayAnim = useRef(new Animated.Value(0)).current;
  const [shouldRender, setShouldRender] = useState(false);

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
        // Solo ocultar después de que la animación termine
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

  return (
    <>
      {/* Overlay para cerrar al tocar fuera */}
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
      
      {/* Sidebar */}
      <Animated.View
        style={[
          styles.sidebar,
          {
            transform: [{ translateX: slideAnim }],
          },
        ]}
      >
        <View style={styles.sidebarHeader}>
          <Text style={styles.sidebarTitle}>Alas Chiquitanas</Text>
          <TouchableOpacity onPress={onClose} style={styles.closeButton}>
            <FontAwesome5 name="times" size={18} color="#ffffff" />
          </TouchableOpacity>
        </View>

        <ScrollView style={styles.sidebarBody}>
          <View style={styles.menuSection}>
            <Text style={styles.menuSectionTitle}>MENÚ PRINCIPAL</Text>
            {menuItems.map(item => (
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
          <Text style={styles.sidebarFooterText}>Versión 1.0</Text>
        </View>
      </Animated.View>
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
    paddingVertical: 8,
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
    paddingHorizontal: 16,
    paddingVertical: 12,
    borderTopWidth: 1,
    borderTopColor: '#495057',
    backgroundColor: '#212529',
  },
  sidebarFooterText: {
    fontSize: 12,
    color: '#6c757d',
    textAlign: 'center',
  },
});

