import React from 'react';
import { View, ScrollView, StyleSheet } from 'react-native';
import { adminlteColors } from '../theme/adminlte';

export default function AdminLayout({ children, scroll = true }) {
  if (scroll) {
    return (
      <ScrollView style={styles.container}>
        {children}
      </ScrollView>
    );
  }

  return (
    <View style={styles.container}>
      {children}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
    padding: 12,
  },
});
