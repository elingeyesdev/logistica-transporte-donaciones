import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { adminlteColors, adminlteLayout } from '../theme/adminlte';

export default function SmallBox({ color = 'info', title, value, footer }) {
  const bgColor = adminlteColors[color] || adminlteColors.info;

  return (
    <View style={[styles.box, { backgroundColor: bgColor }]}>
      <Text style={styles.title}>{title}</Text>
      <Text style={styles.value}>{value}</Text>
      {footer ? <Text style={styles.footer}>{footer}</Text> : null}
    </View>
  );
}

const styles = StyleSheet.create({
  box: {
    flex: 1,
    borderRadius: adminlteLayout.borderRadius,
    padding: adminlteLayout.padding,
    elevation: 3, // sombra Android
  },
  title: {
    color: adminlteColors.light,
    fontSize: 14,
  },
  value: {
    color: adminlteColors.light,
    fontSize: 24,
    fontWeight: '700',
    marginTop: 4,
    marginBottom: 8,
  },
  footer: {
    color: adminlteColors.light,
    fontSize: 12,
    opacity: 0.9,
  },
});
