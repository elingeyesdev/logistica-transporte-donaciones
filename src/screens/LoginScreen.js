import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  KeyboardAvoidingView,
  Platform,
  Alert,
  ActivityIndicator,
} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { FontAwesome5 } from '@expo/vector-icons';
import { adminlteColors } from '../theme/adminlte';
import { login } from '../services/authService';

export default function LoginScreen() {
  const navigation = useNavigation();

  const [correo, setCorreo] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!correo.trim() || !password.trim()) {
      Alert.alert('Campos requeridos', 'Ingresa correo y contraseña.');
      return;
    }

    setLoading(true);
    try {
      await login(correo.trim(), password);
      navigation.reset({
        index: 0,
        routes: [{ name: 'ListadoSolicitud' }],
      });
    } catch (e) {
      Alert.alert('Error de inicio de sesión', e.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
    >
      <View style={styles.card}>
        <View style={styles.logoRow}>
          <FontAwesome5
            name="hands-helping"
            size={32}
            color={adminlteColors.primary}
            style={{ marginRight: 8 }}
          />
          <Text style={styles.appTitle}>Alas Chiquitanas</Text>
        </View>

        <Text style={styles.subtitle}>Iniciar sesión</Text>

        <View style={styles.formGroup}>
          <Text style={styles.label}>Correo electrónico</Text>
          <TextInput
            style={styles.input}
            placeholder="tu@correo.com"
            placeholderTextColor={adminlteColors.muted}
            value={correo}
            onChangeText={setCorreo}
            keyboardType="email-address"
            autoCapitalize="none"
            autoCorrect={false}
          />
        </View>

        <View style={styles.formGroup}>
          <Text style={styles.label}>Contraseña</Text>
          <TextInput
            style={styles.input}
            placeholder="••••••••"
            placeholderTextColor={adminlteColors.muted}
            value={password}
            onChangeText={setPassword}
            secureTextEntry
          />
        </View>

        <TouchableOpacity
          style={[
            styles.button,
            (!correo.trim() || !password.trim() || loading) &&
              styles.buttonDisabled,
          ]}
          disabled={!correo.trim() || !password.trim() || loading}
          onPress={handleLogin}
        >
          {loading ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.buttonText}>Entrar</Text>
          )}
        </TouchableOpacity>

        <Text style={styles.helperText}>
          Usa las mismas credenciales que en el panel web.
        </Text>
      </View>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 16,
  },
  card: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 10,
    padding: 20,
    elevation: 3,
  },
  logoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
    justifyContent: 'center',
  },
  appTitle: {
    fontSize: 22,
    fontWeight: '700',
    color: adminlteColors.dark,
  },
  subtitle: {
    fontSize: 16,
    color: adminlteColors.muted,
    textAlign: 'center',
    marginBottom: 20,
  },
  formGroup: {
    marginBottom: 14,
  },
  label: {
    fontSize: 13,
    fontWeight: '500',
    marginBottom: 6,
    color: adminlteColors.dark,
  },
  input: {
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    paddingHorizontal: 12,
    paddingVertical: 10,
    fontSize: 14,
    backgroundColor: '#ffffff',
    color: adminlteColors.dark,
  },
  button: {
    backgroundColor: adminlteColors.primary,
    paddingVertical: 11,
    borderRadius: 6,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonText: {
    color: '#ffffff',
    fontSize: 15,
    fontWeight: '600',
  },
  helperText: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginTop: 12,
    textAlign: 'center',
  },
});
