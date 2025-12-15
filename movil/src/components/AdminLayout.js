import React, { useState } from 'react';
import { View, ScrollView, StyleSheet, TouchableOpacity, Text, Platform } from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { useNavigation } from '@react-navigation/native';
import { adminlteColors } from '../theme/adminlte';
import Sidebar from './Sidebar';
import { FontAwesome5 } from '@expo/vector-icons';

export default function AdminLayout({ children, scroll = true }) {
  const [sidebarVisible, setSidebarVisible] = useState(false);
  const navigation = useNavigation();
  const insets = useSafeAreaInsets();

  const toggleSidebar = () => {
    setSidebarVisible(!sidebarVisible);
  };

  const closeSidebar = () => {
    setSidebarVisible(false);
  };

  const content = scroll ? (
    <ScrollView style={styles.container}>
      {children}
    </ScrollView>
  ) : (
    <View style={styles.container}>
      {children}
    </View>
  );

  return (
    <View style={styles.wrapper}>
      {/* Header con botón de menú */}
      <View style={[styles.header, { paddingTop: Math.max(insets.top, 12) + 8 }]}>
        <TouchableOpacity
          style={styles.menuButton}
          onPress={toggleSidebar}
          hitSlop={{ top: 10, bottom: 10, left: 10, right: 10 }}
        >
          <FontAwesome5 name="bars" size={22} color="#ffffff" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>D.A.S</Text>
        <View style={styles.headerRight} />
      </View>

      {/* Sidebar */}
      <Sidebar
        isVisible={sidebarVisible}
        onClose={closeSidebar}
        navigation={navigation}
      />

      {/* Contenido principal */}
      <View style={styles.content}>
        {content}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  wrapper: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    backgroundColor: adminlteColors.dark,
    paddingHorizontal: 16,
    paddingBottom: 12,
    elevation: 4,
    zIndex: 1000,
    minHeight: 60,
  },
  menuButton: {
    padding: 10,
    minWidth: 44,
    minHeight: 44,
    justifyContent: 'center',
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: '#ffffff',
    flex: 1,
    marginLeft: 12,
  },
  headerRight: {
    width: 36,
  },
  content: {
    flex: 1,
  },
  container: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
    padding: 12,
  },
});
