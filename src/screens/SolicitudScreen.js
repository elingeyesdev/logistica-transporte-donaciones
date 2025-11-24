import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  Alert,
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';

export default function SolicitudScreen() {
  const [form, setForm] = useState({
    nombre: '',
    apellido: '',
    carnet: '',
    email: '',
    comunidad: '',
    direccion: '',
    provincia: '',
    celular: '',
    cantidadPersonas: '',
    fechaEmergencia: '',
    tipoEmergencia: '',
  });

  const [productosSeleccionados, setProductosSeleccionados] = useState([]);

  const handleChange = (field, value) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const handleBuscarCodigo = () => {
    // Aquí luego vas a navegar a una pantalla de "Verificar solicitud" o abrir un modal
    Alert.alert('Buscar por código', 'Funcionalidad de búsqueda se implementará más adelante.');
  };

  const handleVerProductos = () => {
    // Más adelante: navegar a pantalla de selección de productos o abrir modal nativo
    Alert.alert('Productos', 'Pantalla de selección de productos se implementará más adelante.');
  };

  const handleCancelar = () => {
    // En móvil normalmente se navega atrás; por ahora solo limpiamos
    setForm({
      nombre: '',
      apellido: '',
      carnet: '',
      email: '',
      comunidad: '',
      direccion: '',
      provincia: '',
      celular: '',
      cantidadPersonas: '',
      fechaEmergencia: '',
      tipoEmergencia: '',
    });
    setProductosSeleccionados([]);
  };

  const handleEnviar = () => {
    // Aquí luego harás el POST a tu API Laravel
    // Por ahora solo mostramos lo que se enviaría
    console.log('Formulario enviado:', form, productosSeleccionados);
    Alert.alert('Solicitud enviada', 'Simulación de envío de solicitud al backend DAS.');
  };

  return (
    <AdminLayout>
      {/* Título de página (equivalente a @section('page_title') */}
      <Text style={styles.pageTitle}>Solicitar Insumos</Text>

      {/* Card principal con header y botón Buscar por Código */}
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.cardHeaderTitle}>
            Complete el formulario para solicitar insumos de emergencia
          </Text>
          <TouchableOpacity
            style={styles.searchButton}
            onPress={handleBuscarCodigo}
          >
            <FontAwesome5
              name="search"
              size={16}
              color="#ffffff"
              style={{ marginRight: 6 }}
            />
            <Text style={styles.searchButtonText}>Buscar por Código</Text>
          </TouchableOpacity>
        </View>

        {/* === CONTENIDO DEL FORMULARIO === */}
        {/* Datos del Solicitante */}
        <View style={styles.section}>
          <View style={styles.sectionAlert}>
            <FontAwesome5 name="user" size={16} color={adminlteColors.info} />
            <Text style={styles.sectionAlertText}> Datos del Solicitante</Text>
          </View>

          <View style={styles.row}>
            <View style={styles.col}>
              <Text style={styles.label}>
                Nombre <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.nombre}
                onChangeText={text => handleChange('nombre', text)}
                placeholder="Nombre"
              />
            </View>

            <View style={styles.col}>
              <Text style={styles.label}>
                Apellido <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.apellido}
                onChangeText={text => handleChange('apellido', text)}
                placeholder="Apellido"
              />
            </View>
          </View>

          <View style={styles.row}>
            <View style={styles.col}>
              <Text style={styles.label}>
                Carnet de Identidad <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.carnet}
                onChangeText={text => handleChange('carnet', text)}
                placeholder="Ej: 12345678"
              />
            </View>

            <View style={styles.col}>
              <Text style={styles.label}>
                Correo Electrónico <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.email}
                onChangeText={text => handleChange('email', text)}
                placeholder="correo@ejemplo.com"
                keyboardType="email-address"
                autoCapitalize="none"
              />
            </View>
          </View>

          <View style={styles.row}>
            <View style={styles.colFull}>
              <Text style={styles.label}>
                Comunidad Solicitante <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.comunidad}
                onChangeText={text => handleChange('comunidad', text)}
                placeholder="Nombre de la comunidad"
              />
            </View>
          </View>
        </View>

        {/* Datos de Entrega */}
        <View style={styles.section}>
          <View style={styles.sectionAlert}>
            <FontAwesome5
              name="map-marker-alt"
              size={16}
              color={adminlteColors.info}
            />
            <Text style={styles.sectionAlertText}> Datos de la Entrega</Text>
          </View>

          <View style={styles.row}>
            <View style={styles.colFull}>
              <Text style={styles.label}>
                Dirección <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={[styles.input, styles.textArea]}
                value={form.direccion}
                onChangeText={text => handleChange('direccion', text)}
                placeholder="Describe la dirección de entrega"
                multiline
                numberOfLines={3}
              />
            </View>
          </View>

          {/* Mapa simulado */}
          <View style={styles.row}>
            <View style={styles.colFull}>
              <Text style={styles.label}>Ubicación en el mapa</Text>
              <View style={styles.mapCard}>
                <View style={styles.mapBox}>
                  {/* Controles de zoom simulados */}
                  <View style={styles.mapZoom}>
                    <TouchableOpacity
                      style={styles.mapZoomBtn}
                      onPress={() =>
                        Alert.alert('Mapa', 'Zoom + (simulado por ahora)')
                      }
                    >
                      <Text style={styles.mapZoomText}>+</Text>
                    </TouchableOpacity>
                    <TouchableOpacity
                      style={styles.mapZoomBtn}
                      onPress={() =>
                        Alert.alert('Mapa', 'Zoom - (simulado por ahora)')
                      }
                    >
                      <Text style={styles.mapZoomText}>-</Text>
                    </TouchableOpacity>
                  </View>

                  {/* Marcador */}
                  <View style={styles.mapMarker}>
                    <FontAwesome5
                      name="map-marker-alt"
                      size={26}
                      color={adminlteColors.danger}
                    />
                  </View>

                  {/* Coordenadas & textos simulados */}
                  <View style={styles.mapInfoBottom}>
                    <Text style={styles.mapInfoText}>
                      Coordenadas: -17.720934, -63.166874
                    </Text>
                  </View>

                  <Text style={[styles.mapLabel, { top: 50, left: 40 }]}>
                    Centro de Santa Cruz
                  </Text>
                  <Text style={[styles.mapLabel, { top: 80, left: 110 }]}>
                    Plaza 24 de Septiembre
                  </Text>
                  <Text style={[styles.mapLabel, { top: 120, left: 150 }]}>
                    Mercado Central
                  </Text>
                  <Text style={[styles.mapLabel, { top: 160, left: 60 }]}>
                    Terminal Bimodal
                  </Text>
                </View>
              </View>
              <View style={{ marginTop: 4, flexDirection: 'row', alignItems: 'center' }}>
                <FontAwesome5
                  name="info-circle"
                  size={12}
                  color={adminlteColors.muted}
                  style={{ marginRight: 4 }}
                />
                <Text style={styles.smallMuted}>
                  En una versión posterior podrás seleccionar la ubicación exacta
                  tocando el mapa.
                </Text>
              </View>
            </View>
          </View>

          <View style={styles.row}>
            <View style={styles.col}>
              <Text style={styles.label}>
                Provincia <Text style={styles.required}>*</Text>
              </Text>
              <View style={styles.pickerWrapper}>
                <Picker
                  selectedValue={form.provincia}
                  onValueChange={value => handleChange('provincia', value)}
                  style={styles.picker}
                >
                  <Picker.Item label="Seleccione una provincia" value="" />
                  <Picker.Item label="Chiquitos" value="Chiquitos" />
                  <Picker.Item label="Ñuflo de Chávez" value="Ñuflo de Chávez" />
                  <Picker.Item label="Velasco" value="Velasco" />
                  <Picker.Item label="Ángel Sandoval" value="Ángel Sandoval" />
                  <Picker.Item label="Germán Busch" value="Germán Busch" />
                  <Picker.Item label="Guarayos" value="Guarayos" />
                  <Picker.Item label="Ichilo" value="Ichilo" />
                  <Picker.Item label="Sara" value="Sara" />
                  <Picker.Item
                    label="Obispo Santistevan"
                    value="Obispo Santistevan"
                  />
                  <Picker.Item label="Warnes" value="Warnes" />
                  <Picker.Item label="Andrés Ibáñez" value="Andrés Ibáñez" />
                  <Picker.Item
                    label="José Miguel de Velasco"
                    value="José Miguel de Velasco"
                  />
                  <Picker.Item label="Cordillera" value="Cordillera" />
                  <Picker.Item label="Vallegrande" value="Vallegrande" />
                </Picker>
              </View>
            </View>

            <View style={styles.col}>
              <Text style={styles.label}>
                Nro. de Celular <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.celular}
                onChangeText={text => handleChange('celular', text)}
                placeholder="Ej: 70000000"
                keyboardType="phone-pad"
              />
            </View>
          </View>
        </View>

        {/* Datos de Emergencia */}
        <View style={styles.section}>
          <View style={styles.sectionAlert}>
            <FontAwesome5
              name="exclamation-triangle"
              size={16}
              color={adminlteColors.info}
            />
            <Text style={styles.sectionAlertText}> Datos de Emergencia</Text>
          </View>

          <View style={styles.row}>
            <View style={styles.col}>
              <Text style={styles.label}>
                Cantidad de Personas Afectadas{' '}
                <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.cantidadPersonas}
                onChangeText={text => handleChange('cantidadPersonas', text)}
                placeholder="Ej: 50"
                keyboardType="numeric"
              />
            </View>

            <View style={styles.col}>
              <Text style={styles.label}>
                Inicio de Emergencia <Text style={styles.required}>*</Text>
              </Text>
              <TextInput
                style={styles.input}
                value={form.fechaEmergencia}
                onChangeText={text => handleChange('fechaEmergencia', text)}
                placeholder="YYYY-MM-DD"
              />
            </View>
          </View>

          <View style={styles.row}>
            <View style={styles.colFull}>
              <Text style={styles.label}>
                Tipo de Emergencia <Text style={styles.required}>*</Text>
              </Text>
              <View style={styles.pickerWrapper}>
                <Picker
                  selectedValue={form.tipoEmergencia}
                  onValueChange={value => handleChange('tipoEmergencia', value)}
                  style={styles.picker}
                >
                  <Picker.Item
                    label="Seleccione el tipo de emergencia"
                    value=""
                  />
                  <Picker.Item label="Incendio" value="Incendio" />
                  <Picker.Item label="Inundación" value="Inundación" />
                  <Picker.Item label="Sequía" value="Sequía" />
                  <Picker.Item label="Deslizamiento" value="Deslizamiento" />
                  <Picker.Item label="Terremoto" value="Terremoto" />
                  <Picker.Item label="Granizada" value="Granizada" />
                  <Picker.Item label="Vendaval" value="Vendaval" />
                  <Picker.Item label="Otro" value="Otro" />
                </Picker>
              </View>
            </View>
          </View>
        </View>

        {/* Insumos necesarios */}
        <View style={styles.section}>
          <View style={styles.sectionAlert}>
            <FontAwesome5 name="boxes" size={16} color={adminlteColors.info} />
            <Text style={styles.sectionAlertText}>
              {' '}
              Insumos Necesarios <Text style={styles.required}>*</Text>
            </Text>
          </View>

          <View style={styles.cardInner}>
            <View style={styles.cardInnerHeader}>
              <Text style={styles.cardInnerTitle}>Productos Seleccionados</Text>
              <TouchableOpacity
                style={styles.btnPrimarySm}
                onPress={handleVerProductos}
              >
                <FontAwesome5
                  name="plus"
                  size={12}
                  color="#ffffff"
                  style={{ marginRight: 4 }}
                />
                <Text style={styles.btnPrimarySmText}>Ver Productos</Text>
              </TouchableOpacity>
            </View>

            <View style={styles.cardInnerBody}>
              {productosSeleccionados.length === 0 ? (
                <View style={styles.emptyProducts}>
                  <FontAwesome5
                    name="shopping-cart"
                    size={38}
                    color={adminlteColors.muted}
                    style={{ marginBottom: 8 }}
                  />
                  <Text style={styles.emptyProductsTitle}>
                    No tienes productos seleccionados.
                  </Text>
                  <Text style={styles.emptyProductsText}>
                    Haz clic en "Ver Productos" para seleccionar los insumos
                    necesarios.
                  </Text>
                </View>
              ) : (
                productosSeleccionados.map(item => (
                  <View key={item.id} style={styles.productRow}>
                    <Text style={styles.productName}>{item.nombre}</Text>
                    <Text style={styles.productQty}>x {item.cantidad}</Text>
                  </View>
                ))
              )}
            </View>
          </View>
        </View>

        {/* Footer de la card: botones Cancelar / Enviar */}
        <View style={styles.cardFooter}>
          <TouchableOpacity
            style={[styles.footerButton, styles.btnDefault]}
            onPress={handleCancelar}
          >
            <MaterialIcons
              name="close"
              size={18}
              color={adminlteColors.dark}
              style={{ marginRight: 6 }}
            />
            <Text style={styles.btnDefaultText}>Cancelar</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={[styles.footerButton, styles.btnPrimary]}
            onPress={handleEnviar}
          >
            <FontAwesome5
              name="paper-plane"
              size={14}
              color="#ffffff"
              style={{ marginRight: 6 }}
            />
            <Text style={styles.btnPrimaryText}>Enviar Solicitud</Text>
          </TouchableOpacity>
        </View>
      </View>
    </AdminLayout>
  );
}

const styles = StyleSheet.create({
  pageTitle: {
    fontSize: 22,
    fontWeight: '700',
    marginBottom: 12,
    color: adminlteColors.dark,
  },
  card: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    padding: 12,
    elevation: 3,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  cardHeaderTitle: {
    flex: 1,
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginRight: 8,
  },
  searchButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
  },
  searchButtonText: {
    color: '#ffffff',
    fontSize: 13,
    fontWeight: '500',
  },
  section: {
    marginBottom: 16,
  },
  sectionAlert: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#d1ecf1',
    borderRadius: 4,
    paddingHorizontal: 8,
    paddingVertical: 6,
    marginBottom: 8,
  },
  sectionAlertText: {
    color: '#0c5460',
    fontWeight: '600',
    marginLeft: 6,
  },
  row: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 8,
  },
  col: {
    flex: 1,
  },
  colFull: {
    flex: 1,
  },
  label: {
    fontSize: 13,
    fontWeight: '500',
    marginBottom: 4,
    color: adminlteColors.dark,
  },
  required: {
    color: adminlteColors.danger,
  },
  input: {
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    paddingHorizontal: 10,
    paddingVertical: 8,
    fontSize: 14,
  },
  textArea: {
    height: 80,
    textAlignVertical: 'top',
  },
  pickerWrapper: {
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    overflow: 'hidden',
    backgroundColor: '#ffffff',
  },
  picker: {
    height: 40,
    fontSize: 14,
  },
  mapCard: {
    backgroundColor: '#ffffff',
    borderRadius: 4,
    padding: 4,
    marginTop: 4,
  },
  mapBox: {
    height: 220,
    backgroundColor: '#e3f2fd',
    borderRadius: 4,
    borderWidth: 1,
    borderColor: '#dee2e6',
    position: 'relative',
    overflow: 'hidden',
  },
  mapZoom: {
    position: 'absolute',
    top: 10,
    left: 10,
    backgroundColor: '#ffffff',
    borderRadius: 3,
    elevation: 2,
  },
  mapZoomBtn: {
    paddingHorizontal: 6,
    paddingVertical: 2,
  },
  mapZoomText: {
    fontSize: 16,
    color: adminlteColors.dark,
  },
  mapMarker: {
    position: 'absolute',
    top: '50%',
    left: '50%',
    marginLeft: -10,
    marginTop: -18,
  },
  mapInfoBottom: {
    position: 'absolute',
    bottom: 10,
    left: 10,
    backgroundColor: '#ffffff',
    paddingHorizontal: 6,
    paddingVertical: 3,
    borderRadius: 3,
  },
  mapInfoText: {
    fontSize: 11,
    color: adminlteColors.dark,
  },
  mapLabel: {
    position: 'absolute',
    fontSize: 11,
    color: adminlteColors.muted,
  },
  smallMuted: {
    fontSize: 12,
    color: adminlteColors.muted,
  },
  cardInner: {
    backgroundColor: '#ffffff',
    borderRadius: 4,
    borderWidth: 1,
    borderColor: '#dee2e6',
    marginTop: 6,
  },
  cardInnerHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 10,
    paddingVertical: 8,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  cardInnerTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  cardInnerBody: {
    paddingHorizontal: 10,
    paddingVertical: 12,
  },
  btnPrimarySm: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 4,
  },
  btnPrimarySmText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '500',
  },
  emptyProducts: {
    alignItems: 'center',
    paddingVertical: 16,
  },
  emptyProductsTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: adminlteColors.muted,
    marginBottom: 4,
  },
  emptyProductsText: {
    fontSize: 12,
    color: adminlteColors.muted,
    textAlign: 'center',
  },
  productRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 6,
    borderBottomWidth: 1,
    borderBottomColor: '#f1f3f5',
  },
  productName: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  productQty: {
    fontSize: 14,
    fontWeight: '600',
    color: adminlteColors.primary,
  },
  cardFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    marginTop: 12,
    gap: 8,
  },
  footerButton: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 14,
    paddingVertical: 8,
    borderRadius: 4,
  },
  btnDefault: {
    backgroundColor: '#e9ecef',
  },
  btnDefaultText: {
    color: adminlteColors.dark,
    fontSize: 14,
    fontWeight: '500',
  },
  btnPrimary: {
    backgroundColor: adminlteColors.primary,
  },
  btnPrimaryText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },
});
