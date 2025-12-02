import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Modal,
  TextInput,
  ScrollView,
  SafeAreaView,
  Alert,
  Image,
} from 'react-native';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';
import { getPaquetes, updatePaquete } from '../services/paqueteService';
import * as Location from 'expo-location';
import MapView, { Marker } from 'react-native-maps';
import * as ImagePicker from 'expo-image-picker';

import { conductorService } from '../services/conductorService';
import { getVehiculos } from '../services/vehiculoService';
import { getEstados } from '../services/estadoService';
import { getSolicitudes } from '../services/solicitudService';

const paquetesIniciales = [];

export default function PaqueteScreen() {
  const [modoEdicion, setModoEdicion] = useState(false);
  const [paqueteActual, setPaqueteActual] = useState(null);
  const [modalVisible, setModalVisible] = useState(false);

  const [estadoEntrega, setEstadoEntrega] = useState('');
  const [zona, setZona] = useState('');
  const [fechaEntrega, setFechaEntrega] = useState('');
  const [latitud, setLatitud] = useState(null);
  const [longitud, setLongitud] = useState(null);
  const [ubicacionActual, setUbicacionActual] = useState('');
  const [conductorId, setConductorId] = useState('');
  const [vehiculoId, setVehiculoId] = useState('');
  const [imagenUri, setImagenUri] = useState(null);

  const [paquetes, setPaquetes] = useState(paquetesIniciales);
  const [solicitudesMap, setSolicitudesMap] = useState({});

  const [conductores, setConductores] = useState([]);
  const [vehiculos, setVehiculosState] = useState([]);
  const [estados, setEstados] = useState([]);

  const [showConductorPicker, setShowConductorPicker] = useState(false);
  const [showVehiculoPicker, setShowVehiculoPicker] = useState(false);
  const [showEstadoPicker, setShowEstadoPicker] = useState(false);

  const resetForm = () => {
    setEstadoEntrega('');
    setZona('');
    setFechaEntrega('');
    setLatitud(null);
    setLongitud(null);
    setUbicacionActual('');
    setConductorId('');
    setVehiculoId('');
    setImagenUri(null);
    setPaqueteActual(null);
    setModoEdicion(false);
  };

  const seleccionarImagen = async () => {
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();

    if (status !== 'granted') {
      Alert.alert(
        'Permiso requerido',
        'Necesitamos acceso a tu galería para que puedas seleccionar una imagen del paquete.'
      );
      return;
    }

    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      quality: 0.8,
    });

    if (!result.canceled && result.assets && result.assets.length > 0) {
      setImagenUri(result.assets[0].uri);
    }
  };

  const tomarFoto = async () => {
    const { status } = await ImagePicker.requestCameraPermissionsAsync();

    if (status !== 'granted') {
      Alert.alert(
        'Permiso requerido',
        'Necesitamos acceso a tu cámara para que puedas tomar una foto del paquete.'
      );
      return;
    }

    const result = await ImagePicker.launchCameraAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsEditing: true,
      quality: 0.8,
    });

    if (!result.canceled && result.assets && result.assets.length > 0) {
      setImagenUri(result.assets[0].uri);
    }
  };


const normalizarPaquetes = (listaBack, solicitudesIndex = {}) =>
  (listaBack || []).map((p) => {
    const solicitudKey =
      p.id_solicitud != null ? String(p.id_solicitud) : null;

    const solicitud =
      solicitudKey && solicitudesIndex[solicitudKey]
        ? solicitudesIndex[solicitudKey]
        : null;

    const codigoSolicitud =
      solicitud?.codigo ??
      solicitud?.codigo_seguimiento ??
      null;
    const comunidadSolicitud =
      solicitud?.comunidad ??
      solicitud?.comunidad_solicitante ??
      solicitud?.comunidad_nombre ??
      null;

    return {
      id: p.id_paquete,
      codigo:
        p.codigo ??
        codigoSolicitud ??
        (solicitudKey ? `SOL-${solicitudKey}` : null),

      id_solicitud: p.id_solicitud,
      codigoSolicitud,
      comunidadSolicitud,

      estado_id: p.estado_id,
      estadoNombre: p.estado?.nombre_estado ?? '—',
      ubicacionActual: p.ubicacion_actual ?? '—',

      fechaAprobacion: p.fecha_aprobacion ?? p.created_at ?? '—',

      fechaEntrega: p.fecha_entrega ?? null,
      latitud: p.latitud,
      longitud: p.longitud,
      zona: p.zona ?? '',
      id_conductor: p.id_conductor,
      id_vehiculo: p.id_vehiculo,
    };
  });

  const getConductorLabelById = (id) => {
    if (!id) return 'Sin asignar';
    const found = conductores.find((c) => String(c.conductor_id) === String(id));
    if (!found) return `ID ${id}`;
    const nombre = `${found.nombre ?? ''} ${found.apellido ?? ''}`.trim() || 'Sin nombre';
    return `${nombre} (CI ${found.ci ?? '—'})`;
  };

  const getVehiculoLabelById = (id) => {
    if (!id) return 'Sin asignar';
    const found = vehiculos.find((v) => String(v.id_vehiculo) === String(id));
    if (!found) return `ID ${id}`;
    return found.placa || `Vehículo ID ${id}`;
  };

  const getEstadoLabelById = (id) => {
    if (!id) return 'Sin estado';
    const found = estados.find((e) => String(e.id_estado) === String(id));
    return found ? found.nombre_estado : `ID ${id}`;
  };
  const esEstadoEntregado = (id) => {
    if (!id) return false;
    const found = estados.find(
      (e) => String(e.id_estado) === String(id)
    );
    if (!found || !found.nombre_estado) return false;
    return found.nombre_estado.toLowerCase().includes('entregado');
  };

useEffect(() => {
    const fetchAll = async () => {
      try {
          const solicitudes = await getSolicitudes();
          const solicitudesIndex = {};

          (solicitudes || []).forEach((s) => {
            if (s.id_solicitud != null) {
              solicitudesIndex[String(s.id_solicitud)] = s;
            }
          });

        const lista = await getPaquetes();
        const normalizados = normalizarPaquetes(lista, solicitudesIndex);
        setPaquetes(normalizados);

        const respCond = await conductorService.getConductores();
        if (respCond.success) {
          setConductores(respCond.data || []);
        } else {
          console.log('No se pudieron cargar conductores');
        }

        const vehs = await getVehiculos();
        setVehiculosState(vehs || []);

        const estadosApi = await getEstados();
        setEstados(estadosApi || []);
      } catch (err) {
        console.error('Error cargando datos:', err);
      }
    };
    fetchAll();
  }, []);

  const obtenerUbicacionUsuario = async () => {
    try {
      const { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        Alert.alert('Permiso denegado', 'No se pudo obtener la ubicación del dispositivo.');
        return;
      }

      const location = await Location.getCurrentPositionAsync({
        accuracy: Location.Accuracy.High,
      });

      const lat = location.coords.latitude.toFixed(6);
      const lng = location.coords.longitude.toFixed(6);

      setLatitud(lat);
      setLongitud(lng);
      setUbicacionActual(`Lat: ${lat}, Lng: ${lng}`);
    } catch (e) {
      console.log('Error obteniendo ubicación', e);
    }
  };

  const guardarCambiosPaquete = async () => {
    if (!paqueteActual) return;

    if (!estadoEntrega.trim()) {
      Alert.alert('Estado requerido', 'Debes indicar el estado (ID numérico).');
      return;
    }

    if (!imagenUri) {
      Alert.alert('Imagen requerida', 'Debes seleccionar una imagen.');
      return;
    }

    const estadoIdNum = parseInt(estadoEntrega, 10);
    if (isNaN(estadoIdNum)) {
      Alert.alert('Estado inválido', 'El estado debe ser un número válido.');
      return;
    }

     let nuevaFechaEntrega = null;

    if (esEstadoEntregado(estadoIdNum)) {
      nuevaFechaEntrega = paqueteActual.fechaEntrega || fechaEntrega || new Date().toISOString().slice(0, 10);
    }

    try {
      const payload = {
        id_solicitud: paqueteActual.id_solicitud,
        codigo: paqueteActual.codigo,
        estado_id: estadoIdNum,
        zona: zona || null,
        fecha_entrega: fechaEntrega || null,
        latitud,
        longitud,
        id_conductor: conductorId ? parseInt(conductorId, 10) : null,
        id_vehiculo: vehiculoId ? parseInt(vehiculoId, 10) : null,
        imagenUri,
      };

      await updatePaquete(paqueteActual.id, payload);

      Alert.alert('Éxito', 'Paquete actualizado correctamente');

      const lista = await getPaquetes();
      setPaquetes(normalizarPaquetes(lista));

      setModalVisible(false);
      resetForm();
    } catch (error) {
      console.error('Error en guardarCambiosPaquete:', error);
      Alert.alert('Error', 'No se pudo actualizar el paquete');
    }
  };

  const formatFechaAprobacion = (isoString) => {
  if (!isoString) return '—';
  const clean = isoString.replace(/\.\d+Z$/, 'Z');

  const date = new Date(clean);
  if (isNaN(date.getTime())) {
    return isoString;
  }

  try {
    const deviceTz =
      Intl.DateTimeFormat().resolvedOptions().timeZone || 'America/La_Paz';

    return new Intl.DateTimeFormat('es-BO', {
      timeZone: deviceTz,
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
    }).format(date); 
  } catch (e) {
    const pad = (n) => String(n).padStart(2, '0');
    const d = date.getDate();
    const m = date.getMonth() + 1;
    const y = date.getFullYear();
    const h = date.getHours();
    const min = date.getMinutes();
    return `${pad(d)}/${pad(m)}/${y} ${pad(h)}:${pad(min)}`;
  }
};


  const obtenerColorBorde = (index) => {
    const colors = [
      adminlteColors.primary,
      adminlteColors.success,
      adminlteColors.warning,
      adminlteColors.info,
      adminlteColors.secondary,
      adminlteColors.danger,
    ];
    return colors[index % colors.length];
  };

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Paquetes</Text>

      {/* Lista de Paquetes */}
      <ScrollView style={styles.listaContainer}>
        <View style={styles.grid}>
          {paquetes.map((p, idx) => (
            <View
              key={p.id}
              style={[
                styles.itemCard,
                { borderTopWidth: 3, borderTopColor: obtenerColorBorde(idx) },
              ]}
            >
              <View style={styles.itemHeader}>
                <View style={styles.itemHeaderContent}>
                  <FontAwesome5
                    name="box"
                    size={14}
                    color={adminlteColors.dark}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.itemTitle}>
                    Paquete {p.codigo ? p.codigoSolicitud : `ID ${String(p.id).slice(-4)}`}
                  </Text>
                </View>
              </View>
              
              
              <View style={styles.itemBody}>
                {/* Estado */}
                <View style={styles.row}>
                  <FontAwesome5
                    name="shipping-fast"
                    size={12}
                    color={adminlteColors.primary}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Estado de Entrega:</Text>
                </View>
                <Text style={styles.valuePrimary}>{p.estadoNombre || '—'}</Text>

                <View style={styles.row}>
                  <FontAwesome5
                    name="user"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Conductor:</Text>
                </View>
                <Text style={styles.valueMuted}>
                  {getConductorLabelById(p.id_conductor)}
                </Text>

                <View style={styles.row}>
                  <FontAwesome5
                    name="truck"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Vehículo:</Text>
                </View>
                <Text style={styles.valueMuted}>
                  {getVehiculoLabelById(p.id_vehiculo)}
                </Text>

                <View style={styles.row}>
                  <FontAwesome5
                    name="map-marker-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Ubicación Actual:</Text>
                </View>
                <Text style={styles.valuePrimary}>{p.ubicacionActual || '—'}</Text>

                <View style={styles.row}>
                  <FontAwesome5
                    name="calendar-plus"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Fecha Aprobación:</Text>
                </View>
                <Text style={styles.valueMuted}>{formatFechaAprobacion(p.fechaAprobacion) || '—'}</Text>

                <View style={styles.row}>
                  <FontAwesome5
                    name="calendar-check"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Fecha Entrega:</Text>
                </View>
                <Text style={styles.valueMuted}>{p.fechaEntrega || '-'}</Text>
              </View>

              <TouchableOpacity
                style={styles.btnEditarPaquete}
                onPress={() => {
                  setModoEdicion(true);
                  setPaqueteActual(p);

                  setEstadoEntrega(p.estado_id ? String(p.estado_id) : '');
                  setFechaEntrega(p.fechaEntrega || '');
                  setZona(p.zona || '');
                  setConductorId(p.id_conductor ? String(p.id_conductor) : '');
                  setVehiculoId(p.id_vehiculo ? String(p.id_vehiculo) : '');
                  setImagenUri(null);

                  obtenerUbicacionUsuario();
                  setModalVisible(true);
                }}
              >
                <FontAwesome5
                  name="edit"
                  size={12}
                  color="#ffffff"
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.btnEditarPaqueteText}>Actualizar</Text>
              </TouchableOpacity>
            </View>
          ))}
        </View>
      </ScrollView>

      <Modal
        visible={modalVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => {
          setModalVisible(false);
          resetForm();
        }}
      >
        <SafeAreaView style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <View style={styles.modalHeaderContent}>
              <FontAwesome5
                name="box"
                size={18}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>
                {modoEdicion ? 'Actualizar Paquete' : 'Nuevo Paquete'}
              </Text>
            </View>
            <TouchableOpacity
              onPress={() => {
                setModalVisible(false);
                resetForm();
              }}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <ScrollView style={styles.modalBody}>
            {paqueteActual && (
              <View style={styles.formGroup}>
                <Text style={styles.label}>Código</Text>
                <TextInput
                  style={styles.input}
                  value={paqueteActual.codigoSolicitud || '—'}
                  editable={false}
                />
              </View>
            )}

            {paqueteActual && (
              <View style={styles.formGroup}>
                <Text style={styles.label}>Fecha Aprobación</Text>
                <TextInput
                  style={styles.input}
                  value={formatFechaAprobacion(paqueteActual.fechaAprobacion) || '—'}
                  editable={false}
                />
              </View>
            )}

            <View style={styles.formGroup}>
              <Text style={styles.label}>Estado *</Text>

              <TouchableOpacity
                style={styles.dropdownBox}
                onPress={() => setShowEstadoPicker(true)}
              >
                <Text style={styles.dropdownText}>
                  {estadoEntrega
                    ? getEstadoLabelById(estadoEntrega)
                    : 'Tocar para seleccionar estado'}
                </Text>
              </TouchableOpacity>

              <Text style={styles.smallTextMuted}>
                {estadoEntrega
                  ? `Seleccionado: ${getEstadoLabelById(estadoEntrega)}`
                  : paqueteActual
                    ? `Estado actual: ${paqueteActual.estadoNombre || '—'}`
                    : 'Ningún estado seleccionado.'}
              </Text>
            </View>

<View style={styles.formGroup}>
  <Text style={styles.label}>Conductor asignado (opcional)</Text>

  <TouchableOpacity
    style={styles.dropdownBox}
    onPress={() => setShowConductorPicker(true)}
  >
    <Text style={styles.dropdownText}>
      {conductorId
        ? getConductorLabelById(conductorId)
        : 'Tocar para seleccionar conductor'}
    </Text>
  </TouchableOpacity>

  <Text style={styles.smallTextMuted}>
    {conductorId
      ? `Seleccionado: ${getConductorLabelById(conductorId)}`
      : 'Ningún conductor asignado.'}
  </Text>
</View>

<View style={styles.formGroup}>
  <Text style={styles.label}>Vehículo asignado</Text>

  <TouchableOpacity
    style={styles.dropdownBox}
    onPress={() => setShowVehiculoPicker(true)}
  >
    <Text style={styles.dropdownText}>
      {vehiculoId
        ? getVehiculoLabelById(vehiculoId)
        : 'Tocar para seleccionar vehículo'}
    </Text>
  </TouchableOpacity>

  <Text style={styles.smallTextMuted}>
    {vehiculoId
      ? `Seleccionado: ${getVehiculoLabelById(vehiculoId)}`
      : 'Ningún vehículo asignado.'}
  </Text>
</View>


            <View style={styles.formGroup}>
              <Text style={styles.label}>Zona o Comunidad</Text>
              <TextInput
                style={styles.input}
                placeholder="Ej: Zona Sur, Centro, Norte..."
                value={zona}
                onChangeText={setZona}
                placeholderTextColor={adminlteColors.muted}
              />
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>Ubicación actual (automática)</Text>

              <View style={styles.mapaContainer}>
                <MapView
                  style={{ flex: 1 }}
                  region={{
                    latitude: latitud ? parseFloat(latitud) : -17.7833,
                    longitude: longitud ? parseFloat(longitud) : -63.1821,
                    latitudeDelta: 0.005,
                    longitudeDelta: 0.005,
                  }}
                  scrollEnabled={false}
                  zoomEnabled={false}
                  rotateEnabled={false}
                  pitchEnabled={false}
                  pointerEvents="none"
                >
                  {latitud && longitud && (
                    <Marker
                      coordinate={{
                        latitude: parseFloat(latitud),
                        longitude: parseFloat(longitud),
                      }}
                      pinColor="red"
                    />
                  )}
                </MapView>
              </View>

              <Text style={styles.smallTextMuted}>
                Esta ubicación se toma de tu dispositivo y no puede modificarse.
              </Text>

              {ubicacionActual ? (
                <Text style={styles.smallTextMuted}>{ubicacionActual}</Text>
              ) : null}
            </View>

          <View style={styles.formGroup}>
            <Text style={styles.label}>Imagen (obligatoria)</Text>

            <View style={styles.imageButtonsRow}>
              <TouchableOpacity
                onPress={tomarFoto}
                style={styles.btnImagenSecundario}
              >
                <FontAwesome5
                  name="camera"
                  size={14}
                  color="#ffffff"
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.btnImagenText}>Tomar foto</Text>
              </TouchableOpacity>

              <TouchableOpacity
                onPress={seleccionarImagen}
                style={styles.btnImagenPrimario}
              >
                <FontAwesome5
                  name="images"
                  size={14}
                  color="#ffffff"
                  style={{ marginRight: 6 }}
                />
                <Text style={styles.btnImagenText}>Galería</Text>
              </TouchableOpacity>
            </View>

            {imagenUri && (
              <Image
                source={{ uri: imagenUri }}
                style={styles.previewImagen}
                resizeMode="cover"
              />
            )}

            <Text style={styles.smallTextMuted}>
              Esta imagen se usará como evidencia de la entrega del paquete.
            </Text>
          </View>

          </ScrollView>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => {
                setModalVisible(false);
                resetForm();
              }}
            >
              <Text style={styles.modalFooterButtonText}>Cancelar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[
                styles.modalFooterButtonPrimary,
                (!estadoEntrega.trim() || !imagenUri) &&
                  styles.modalFooterButtonDisabled,
              ]}
              disabled={!estadoEntrega.trim() || !imagenUri}
              onPress={guardarCambiosPaquete}
            >
              <Text style={styles.modalFooterButtonText}>Guardar Cambios</Text>
            </TouchableOpacity>
          </View>
<Modal
  visible={showConductorPicker}
  transparent
  animationType="fade"
  onRequestClose={() => setShowConductorPicker(false)}
>
  <TouchableOpacity
    style={styles.pickerOverlay}
    activeOpacity={1}
    onPressOut={() => setShowConductorPicker(false)}
  >
    <View style={styles.pickerModal}>
      <Text style={styles.pickerTitle}>Seleccionar conductor</Text>
      <ScrollView style={{ maxHeight: 300 }}>
        <TouchableOpacity
          style={styles.pickerItem}
          onPress={() => {
            setConductorId('');
            setShowConductorPicker(false);
          }}
        >
          <Text style={styles.pickerItemText}>— Sin asignar —</Text>
        </TouchableOpacity>

        {conductores.map(c => {
          const label = getConductorLabelById(c.conductor_id);
          return (
            <TouchableOpacity
              key={c.conductor_id}
              style={styles.pickerItem}
              onPress={() => {
                setConductorId(String(c.conductor_id));
                setShowConductorPicker(false);
              }}
            >
              <Text style={styles.pickerItemText}>{label}</Text>
            </TouchableOpacity>
          );
        })}
      </ScrollView>
    </View>
  </TouchableOpacity>
</Modal>

<Modal
  visible={showVehiculoPicker}
  transparent
  animationType="fade"
  onRequestClose={() => setShowVehiculoPicker(false)}
>
  <TouchableOpacity
    style={styles.pickerOverlay}
    activeOpacity={1}
    onPressOut={() => setShowVehiculoPicker(false)}
  >
    <View style={styles.pickerModal}>
      <Text style={styles.pickerTitle}>Seleccionar vehículo</Text>
      <ScrollView style={{ maxHeight: 300 }}>
        <TouchableOpacity
          style={styles.pickerItem}
          onPress={() => {
            setVehiculoId('');
            setShowVehiculoPicker(false);
          }}
        >
          <Text style={styles.pickerItemText}>— Sin asignar —</Text>
        </TouchableOpacity>

        {vehiculos.map(v => (
          <TouchableOpacity
            key={v.id_vehiculo}
            style={styles.pickerItem}
            onPress={() => {
              setVehiculoId(String(v.id_vehiculo));
              setShowVehiculoPicker(false);
            }}
          >
            <Text style={styles.pickerItemText}>
              {getVehiculoLabelById(v.id_vehiculo)}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>
    </View>
  </TouchableOpacity>
</Modal>
<Modal
  visible={showEstadoPicker}
  transparent
  animationType="fade"
  onRequestClose={() => setShowEstadoPicker(false)}
>
  <TouchableOpacity
    style={styles.pickerOverlay}
    activeOpacity={1}
    onPressOut={() => setShowEstadoPicker(false)}
  >
    <View style={styles.pickerModal}>
      <Text style={styles.pickerTitle}>Seleccionar estado</Text>
      <ScrollView style={{ maxHeight: 300 }}>
        <TouchableOpacity
          style={styles.pickerItem}
          onPress={() => {
            setEstadoEntrega('');
            setShowEstadoPicker(false);
          }}
        >
          <Text style={styles.pickerItemText}>— Sin seleccionar —</Text>
        </TouchableOpacity>

        {estados.map((e) => (
          <TouchableOpacity
            key={e.id_estado}
            style={styles.pickerItem}
            onPress={() => {
              const idSel = String(e.id_estado);
              setEstadoEntrega(idSel);

              const nombre = (e.nombre_estado || '').toLowerCase();
              if (nombre.includes('entregado')) {
                setFechaEntrega((prev) => prev || new Date().toISOString().slice(0, 10));
              } else {
                setFechaEntrega('');
              }

              setShowEstadoPicker(false);
            }}
          >
            <Text style={styles.pickerItemText}>{e.nombre_estado}</Text>
          </TouchableOpacity>
        ))}

      </ScrollView>
    </View>
  </TouchableOpacity>
</Modal>

        </SafeAreaView>
      </Modal>
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
  listaContainer: { flex: 1 },
  grid: { flexDirection: 'row', flexWrap: 'wrap', gap: 12 },
  itemCard: {
    width: '100%',
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    marginBottom: 16,
    elevation: 3,
    overflow: 'hidden',
  },
  itemHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  itemHeaderContent: { flexDirection: 'row', alignItems: 'center', flex: 1 },
  itemTitle: { fontSize: 15, fontWeight: '600', color: adminlteColors.dark },
  itemBody: { padding: 12 },
  row: { flexDirection: 'row', alignItems: 'center', marginBottom: 4 },
  label: { fontSize: 13, fontWeight: '600', color: adminlteColors.dark },
  valuePrimary: {
    fontSize: 13,
    color: adminlteColors.primary,
    marginBottom: 8,
    marginLeft: 20,
  },
  valueMuted: {
    fontSize: 13,
    color: adminlteColors.muted,
    marginBottom: 8,
    marginLeft: 20,
  },

  modalContainer: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
    paddingTop: 10,
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  modalHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  modalHeaderTitle: { fontSize: 18, fontWeight: '600', color: '#ffffff' },
  modalCloseButton: { padding: 4 },
  modalBody: { flex: 1, padding: 16 },
  formGroup: { marginBottom: 16 },
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
  helperBox: {
    backgroundColor: '#f8f9fa',
    borderRadius: 4,
    padding: 12,
    marginBottom: 16,
  },
  helperText: { fontSize: 12, color: adminlteColors.muted },
  modalFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: adminlteColors.primary,
    gap: 8,
  },
  modalFooterButtonSecondary: {
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  modalFooterButtonPrimary: {
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  modalFooterButtonDisabled: {
    opacity: 0.5,
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },

  btnEditarPaquete: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.info,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
    marginTop: 10,
    marginHorizontal: 12,
    marginBottom: 12,
  },
  btnEditarPaqueteText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '500',
  },

  mapaContainer: {
    height: 220,
    borderRadius: 12,
    overflow: 'hidden',
    marginTop: 8,
  },
  smallTextMuted: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginTop: 4,
  },
dropdownBox: {
  borderWidth: 1,
  borderColor: '#ced4da',
  borderRadius: 4,
  paddingHorizontal: 12,
  paddingVertical: 12,
  backgroundColor: '#ffffff',
},
dropdownText: {
  fontSize: 14,
  color: adminlteColors.dark,
},

pickerOverlay: {
  flex: 1,
  backgroundColor: 'rgba(0,0,0,0.4)',
  justifyContent: 'center',
  alignItems: 'center',
},
pickerModal: {
  width: '85%',
  backgroundColor: '#ffffff',
  borderRadius: 10,
  padding: 16,
},
pickerTitle: {
  fontSize: 16,
  fontWeight: '600',
  marginBottom: 8,
  color: adminlteColors.dark,
},
pickerItem: {
  paddingVertical: 10,
  borderBottomWidth: 1,
  borderBottomColor: '#eee',
},
pickerItemText: {
  fontSize: 14,
  color: adminlteColors.dark,
},
imageButtonsRow: {
  flexDirection: 'row',
  justifyContent: 'space-between',
  marginBottom: 4,
  marginTop: 12,
},

btnImagenPrimario: {
  flex: 1,
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'center',
  backgroundColor: adminlteColors.primary,
  paddingVertical: 10,
  borderRadius: 6,
  marginLeft: 6,
},

btnImagenSecundario: {
  flex: 1,
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'center',
  backgroundColor: adminlteColors.info,
  paddingVertical: 10,
  borderRadius: 6,
  marginRight: 6,
},

btnImagenText: {
  color: '#ffffff',
  fontSize: 13,
  fontWeight: '600',
},

previewImagen: {
  width: '100%',
  height: 180,
  borderRadius: 8,
  marginBottom: 8,
},


});
