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
import { getPaquetes, updatePaquete, sendEntregaCode, verifyEntregaCode, } from '../services/paqueteService';
import * as Location from 'expo-location';
import MapView, { Marker } from 'react-native-maps';
import * as ImagePicker from 'expo-image-picker';

import { conductorService } from '../services/conductorService';
import { getVehiculos } from '../services/vehiculoService';
import { getEstados } from '../services/estadoService';
import { getSolicitudes } from '../services/solicitudService';
import { fetchVoluntarios } from '../services/VoluntarioService';

const paquetesIniciales = [];

export default function PaqueteScreen() {
  const [modoEdicion, setModoEdicion] = useState(false);
  const [paqueteActual, setPaqueteActual] = useState(null);
  const [modalVisible, setModalVisible] = useState(false);

  const [showCodigoModal, setShowCodigoModal] = useState(false);
  const [codigoEntrega, setCodigoEntrega] = useState('');
  const [codigoError, setCodigoError] = useState('');
  const [isSaving, setIsSaving] = useState(false);
  const [isVerifying, setIsVerifying] = useState(false);
  const [payloadPendiente, setPayloadPendiente] = useState(null);

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

  const [filtroEstadoActivo, setFiltroEstadoActivo] = useState('todos');
  const [filtroOrdenActivo, setFiltroOrdenActivo] = useState('recientes');

  const [isLoadingUbicacion, setIsLoadingUbicacion] = useState(false);
  const [ubicacionError, setUbicacionError] = useState('');

  const [modalDetalleVisible, setModalDetalleVisible] = useState(false);
  const [paqueteSeleccionado, setPaqueteSeleccionado] = useState(null);
  const [usuariosPorCi, setUsuariosPorCi] = useState({});

  const filtrosEstado = [
    { id: 'todos', label: 'Todos', icon: 'list' },
    { id: 'pendiente', label: 'Pendiente', icon: 'clock' },
    { id: 'en_camino', label: 'En camino', icon: 'truck' },
    { id: 'entregado', label: 'Entregado', icon: 'check' },
  ];

  const filtrosOrden = [
    { id: 'recientes', label: 'Recientes', icon: 'sort-amount-down' },
    { id: 'antiguos', label: 'Antiguos', icon: 'sort-amount-up' },
  ];

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
      //REFERENTES AL CODIGO DE VERIFICACION
    setShowCodigoModal(false);
    setCodigoEntrega('');
    setCodigoError('');
    setPayloadPendiente(null);
    setIsSaving(false);
    setIsVerifying(false);
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

  const normalizarPaquetes = (listaBack, solicitudesIndex = {}, usuariosIndexPorCi = {}) =>
    (listaBack || []).map((p) => {
      const solicitudKey =
        p.id_solicitud != null ? String(p.id_solicitud) : null;

      const solicitud =
        solicitudKey && solicitudesIndex[solicitudKey]
          ? solicitudesIndex[solicitudKey]
          : null;

      const destinoRaw =
        solicitud?.destino ??
        solicitud?.destino_data ??
        solicitud?.destino_info ??
        solicitud?.ubicacion_destino ??
        solicitud?.destinoSolicitud ??
        null;

      const codigoSolicitud =
        solicitud?.codigo ??
        solicitud?.codigo_seguimiento ??
        null;

      const comunidadDestino =
        destinoRaw?.comunidad ??
        destinoRaw?.comunidad_nombre ??
        destinoRaw?.nombre_comunidad ??
        destinoRaw?.nombre ??
        null;

      const comunidadSolicitud =
        solicitud?.comunidad ??
        solicitud?.comunidad_solicitante ??
        solicitud?.comunidad_nombre ??
        comunidadDestino ??
        null;

      const solicitanteRaw =
        solicitud?.solicitante ??
        solicitud?.solicitante_data ??
        solicitud?.solicitante_info ??
        solicitud?.datos_solicitante ??
        null;

      let solicitanteNombre = '—';
      let solicitanteCi = '—';

      if (typeof solicitanteRaw === 'string') {
        const limpio = solicitanteRaw.trim();
        if (limpio) {
          solicitanteNombre = limpio;
        }
      } else if (solicitanteRaw && typeof solicitanteRaw === 'object') {
        const partesNombre = [
          solicitanteRaw.nombre,
          solicitanteRaw.apellido,
          solicitanteRaw.primer_apellido,
          solicitanteRaw.segundo_apellido,
        ]
          .map((parte) => (parte ? String(parte).trim() : ''))
          .filter(Boolean);

        const nombreAlterno =
          solicitanteRaw.nombre_completo ??
          solicitanteRaw.nombreCompleto ??
          solicitanteRaw.full_name ??
          solicitanteRaw.fullName ??
          null;

        if (partesNombre.length > 0) {
          solicitanteNombre = partesNombre.join(' ');
        } else if (nombreAlterno) {
          const limpio = String(nombreAlterno).trim();
          if (limpio) {
            solicitanteNombre = limpio;
          }
        }

        const ciAlterno =
          solicitanteRaw.ci ??
          solicitanteRaw.carnet ??
          solicitanteRaw.documento ??
          solicitanteRaw.documento_identidad ??
          solicitanteRaw.numero_documento ??
          null;

        if (ciAlterno != null) {
          const limpioCi = String(ciAlterno).trim();
          if (limpioCi) {
            solicitanteCi = limpioCi;
          }
        }
      }

      const tipoEmergenciaRaw =
        solicitud?.tipoEmergencia ??
        solicitud?.tipo_emergencia ??
        solicitud?.tipo_emergencia_data ??
        solicitud?.tipo_emergencia_detalle ??
        solicitud?.tipo_emergencia_info ??
        solicitud?.emergencia_tipo ??
        null;

      let tipoEmergencia = '—';

      if (typeof tipoEmergenciaRaw === 'string') {
        const limpio = tipoEmergenciaRaw.trim();
        if (limpio) {
          tipoEmergencia = limpio;
        }
      } else if (tipoEmergenciaRaw && typeof tipoEmergenciaRaw === 'object') {
        const candidato =
          tipoEmergenciaRaw.nombre ??
          tipoEmergenciaRaw.descripcion ??
          tipoEmergenciaRaw.titulo ??
          tipoEmergenciaRaw.label ??
          null;

        if (candidato) {
          const limpio = String(candidato).trim();
          if (limpio) {
            tipoEmergencia = limpio;
          }
        }
      }

      if (tipoEmergencia === '—') {
        const alterno =
          solicitud?.tipo_emergencia_nombre ??
          solicitud?.tipo_emergencia ??
          solicitud?.emergencia ??
          null;

        if (alterno) {
          const limpio = String(alterno).trim();
          if (limpio) {
            tipoEmergencia = limpio;
          }
        }
      }

      const referenciaNombre =
        solicitud?.referencia_nombre ??
        solicitud?.nombre_referencia ??
        solicitud?.contacto_referencia_nombre ??
        solicitud?.referenciaNombre ??
        solicitud?.persona_referencia?.nombre ??
        solicitud?.referencia?.nombre ??
        null;

      const referenciaTelefono =
        solicitud?.referencia_celular ??
        solicitud?.celular_referencia ??
        solicitud?.referencia_telefono ??
        solicitud?.telefono_referencia ??
        solicitud?.referenciaTelefono ??
        solicitud?.persona_referencia?.telefono ??
        solicitud?.referencia?.telefono ??
        null;

      const ciEncargado = p.id_encargado;

      let voluntarioEncargado = '—';

      if (ciEncargado) {
        const user = usuariosIndexPorCi[ciEncargado];

        if (user) {
          const nombreCompleto = [
            user.nombre,
            user.apellido
          ]
            .map((parte) => (parte ? String(parte).trim() : ''))
            .filter(Boolean)
            .join(' ');

          if (nombreCompleto) {
            voluntarioEncargado = `${nombreCompleto} - CI ${ciEncargado}`;
          } else {
            voluntarioEncargado = `CI ${ciEncargado} 2`;
          }
        } else {
          voluntarioEncargado = `CI ${ciEncargado}`;
        }
      }

      const fechaCreacion =
        p.fecha_creacion ??
        p.created_at ??
        p.fecha_aprobacion ??
        null;

      const fechaActualizacion =
        p.updated_at ??
        p.fecha_actualizacion ??
        p.fecha_revision ??
        null;

      let ubicacionClean = '—';
      const rawUbicacion = p.ubicacion_actual;

      if (rawUbicacion != null) {
        const str = String(rawUbicacion).trim();
        if (str) {
          const idx = str.indexOf('-');
          const base = idx !== -1 ? str.slice(0, idx) : str;
          ubicacionClean = base.trim() || '—';
          if (base=='(') ubicacionClean='Nombre no registrado';
        }
      }
      return {
        id: p.id_paquete,
        codigo:
          p.codigo ??
          codigoSolicitud ??
          (solicitudKey ? `SOL-${solicitudKey}` : null),

        id_solicitud: p.id_solicitud,
        codigoSolicitud,
        comunidadSolicitud,
        solicitanteNombre,
        solicitanteCi,
        tipoEmergencia,
        referenciaNombre: referenciaNombre ? String(referenciaNombre).trim() : null,
        referenciaTelefono: referenciaTelefono ? String(referenciaTelefono).trim() : null,
        voluntarioEncargado,

        estado_id: p.estado_id,
        estadoNombre: p.estado?.nombre_estado ?? '—',
        ubicacionActual: ubicacionClean ?? '—',

        fechaAprobacion: p.fecha_aprobacion ?? p.created_at ?? '—',
        fechaCreacion,
        fechaActualizacion,

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

  const getConductorDetalle = (id) => {
    if (!id) return 'Sin asignar';

    const found = conductores.find((c) => String(c.conductor_id) === String(id));
    if (!found) return `ID ${id}`;

    const partesNombre = [
      found.nombre,
      found.apellido,
      found.primer_apellido,
      found.segundo_apellido,
    ]
      .map((parte) => (parte ? String(parte).trim() : ''))
      .filter(Boolean);

    const nombre = partesNombre.length > 0
      ? partesNombre.join(' ')
      : (found.nombre_completo || found.full_name || found.alias || `Conductor ${found.conductor_id}`);

    const ci =
      found.ci ??
      found.documento ??
      found.documento_identidad ??
      found.numero_documento ??
      null;

    if (ci == null || String(ci).trim() === '') {
      return nombre;
    }

    return `${nombre} - CI ${String(ci).trim()}`;
  };

  const getVehiculoDetalle = (id) => {
    if (!id) return 'Sin asignar';

    const found = vehiculos.find((v) => String(v.id_vehiculo) === String(id));
    if (!found) return `ID ${id}`;

    const placa =
      found.placa ??
      found.placa_vehiculo ??
      found.numero_placa ??
      null;

    const marca =
      found.marca?.nombre_marca ??
      found.marca?.nombre ??
      found.marca_nombre ??
      found.nombre_marca ??
      found.marca ??
      null;

    const partes = [];
    if (placa) {
      partes.push(String(placa).trim());
    }
    if (marca) {
      partes.push(String(marca).trim());
    }

    if (partes.length > 0) {
      return partes.join(' - ');
    }

    return `Vehículo ID ${id}`;
  };

  const getEstadoLabelById = (id) => {
    if (!id) return 'Sin estado';
    const found = estados.find((e) => String(e.id_estado) === String(id));
    return found ? found.nombre_estado : `ID ${id}`;
  };

  const esEstadoEntregado = (id) => {
    if (!id) return false;
    const found = estados.find(e => String(e.id_estado) === String(id));
    if (!found || !found.nombre_estado) return false;

    const nombre = found.nombre_estado.trim().toLowerCase();
    return nombre === 'entregado' || nombre === 'entregada';
  };

  const getEstadoKey = (p) => {
      const nombre = (p.estadoNombre || '').toLowerCase();
      if (nombre.includes('pendiente')) return 'pendiente';
      if (nombre.includes('camino')) return 'en_camino';
      if (nombre.includes('entregado')) return 'entregado';
      return 'otro';
    };

    const getEstadoBadgeColor = (nombre) => {
      const value = (nombre || '').toLowerCase();
      if (value.includes('pendiente')) return adminlteColors.warning;
      if (value.includes('camino')) return adminlteColors.info;
      if (value.includes('entreg')) return adminlteColors.success;
      return adminlteColors.secondary;
    };

    const getFechaReferencia = (p) => {
      const raw = p.fechaActualizacion || p.fechaEntrega || p.fechaAprobacion;
      if (!raw) return null;
      const d = new Date(raw);
      return isNaN(d.getTime()) ? null : d;
    };

    const obtenerPaquetesFiltrados = () => {
      let resultado = [...paquetes];

      if (filtroEstadoActivo !== 'todos') {
        resultado = resultado.filter((p) => getEstadoKey(p) === filtroEstadoActivo);
      }

      const estadoPrioridad = {
        en_camino: 0,
        pendiente: 1,
        otro: 2,
        entregado: 3,
      };

      resultado.sort((a, b) => {
        const pa = estadoPrioridad[getEstadoKey(a)] ?? 99;
        const pb = estadoPrioridad[getEstadoKey(b)] ?? 99;

        if (pa !== pb) {
          return pa - pb;
        }

        const fa = getFechaReferencia(a);
        const fb = getFechaReferencia(b);

        if (!fa && !fb) return 0;
        if (!fa) return 1;
        if (!fb) return -1;

        if (filtroOrdenActivo === 'antiguos') {
          return fa - fb;
        }
        return fb - fa;
      });

      return resultado;
    };

  const paquetesFiltrados = obtenerPaquetesFiltrados();

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

        setSolicitudesMap(solicitudesIndex);
      const usuarios = await fetchVoluntarios();
      const usuariosIndexPorCi = {};

      (usuarios || []).forEach((u) => {
        const ci = u.ci;
        if (ci != null) {
          const key = String(ci).trim();
          if (key) {
            usuariosIndexPorCi[key] = u;
          }
        }
        
        });

        setUsuariosPorCi(usuariosIndexPorCi);
        const lista = await getPaquetes();
        const normalizados = normalizarPaquetes(lista, solicitudesIndex, usuariosIndexPorCi);
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

        setSolicitudesMap(solicitudesIndex);

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
      setIsLoadingUbicacion(true);
      setUbicacionError('');

      const { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        setUbicacionError('Permiso denegado para obtener la ubicación.');
        Alert.alert(
          'Permiso denegado',
          'No se pudo obtener la ubicación del dispositivo.'
        );
        return;
      }

      const location = await Location.getCurrentPositionAsync({
        accuracy: Location.Accuracy.High,
      });

      const lat = Number(location.coords.latitude.toFixed(6));
      const lng = Number(location.coords.longitude.toFixed(6));

      setLatitud(lat);
      setLongitud(lng);

      const resultados = await Location.reverseGeocodeAsync({
        latitude: lat,
        longitude: lng,
      });

      if (resultados && resultados.length > 0) {
        const info = resultados[0];
        const partes = [
          info.street || info.name,
          info.district,
          info.city || info.subregion || info.region,
          info.country,
        ].filter(Boolean);

        const descripcion = partes.join(', ');

        setUbicacionActual(
          descripcion || `Ubicación aproximada (${lat}, ${lng})`
        );
         setZona(
          descripcion || `Ubicación aproximada (${lat}, ${lng})`
        );
      } else {
        setUbicacionActual(`Ubicación capturada (${lat}, ${lng})`);
      }
    } catch (e) {
      console.log('Error obteniendo ubicación', e);
      setUbicacionError('No se pudo obtener la ubicación actual.');
      setUbicacionActual('');
    } finally {
      setIsLoadingUbicacion(false);
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

    const payloadBase = {
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

    const esEntregadoNuevo = esEstadoEntregado(estadoIdNum);

    if (!esEntregadoNuevo) {
      try {
        setIsSaving(true);
        const result = await updatePaquete(paqueteActual.id, payloadBase);

        if (result?.offline) {
          Alert.alert(
            'Sin conexión',
            'Los cambios se guardaron en el dispositivo y se enviarán automáticamente cuando tengas internet.'
          );
        } else {
          Alert.alert('Éxito', 'Paquete actualizado correctamente');
        }

        const lista = await getPaquetes();
        setPaquetes(normalizarPaquetes(lista, solicitudesMap, usuariosPorCi));

        setModalVisible(false);
        resetForm();
      } catch (error) {
        console.error('Error en guardarCambiosPaquete:', error);
        Alert.alert('Error', 'No se pudo actualizar el paquete');
      } finally {
        setIsSaving(false);
      }
      return;
    }

    try {
      setIsSaving(true);
      setPayloadPendiente(payloadBase);

      const res = await sendEntregaCode(paqueteActual.id, estadoIdNum);

      if (res?.success) {
        Alert.alert(
          'Código enviado',
          'Se envió un código al correo del solicitante. Pídele que te lo dicte para confirmar la entrega.'
        );
        setCodigoEntrega('');
        setCodigoError('');
        setShowCodigoModal(true);
      } else {
        const msg = res?.message || 'No se pudo enviar el código al solicitante.';
        Alert.alert('Error', msg);
      }
    } catch (error) {
      console.error('Error enviando código de entrega:', error);
      Alert.alert('Error', 'No se pudo enviar el código de verificación.');
    } finally {
      setIsSaving(false);
    }
  };

  const confirmarCodigoEntrega = async () => {
    if (!paqueteActual || !payloadPendiente) {
      return;
    }

    const code = (codigoEntrega || '').trim();
    if (!/^\d{4}$/.test(code)) {
      setCodigoError('El código debe tener 4 dígitos numéricos.');
      return;
    }

    try {
      setIsVerifying(true);
      setCodigoError('');

      const res = await verifyEntregaCode(paqueteActual.id, code);

      if (!res?.success) {
        const msg = res?.message || 'Código incorrecto o vencido. El paquete no fue entregado.';
        setCodigoError(msg);
        return;
      }

      const result = await updatePaquete(paqueteActual.id, payloadPendiente);

      setShowCodigoModal(false);
      setPayloadPendiente(null);
      setCodigoEntrega('');

      if (result?.offline) {
        Alert.alert(
          'Sin conexión',
          'Los cambios se guardaron en el dispositivo y se enviarán automáticamente cuando tengas internet.'
        );
      } else {
        Alert.alert('Éxito', 'Paquete marcado como entregado.');
      }

      const lista = await getPaquetes();
      setPaquetes(normalizarPaquetes(lista, solicitudesMap, usuariosPorCi));

      setModalVisible(false);
      resetForm();
    } catch (error) {
      console.error('Error confirmando código de entrega:', error);
      setCodigoError('Error de red al validar el código. Intenta nuevamente.');
    } finally {
      setIsVerifying(false);
    }
  };

  const formatFechaAprobacion = (isoString) => {
    if (!isoString) return '—';

    const clean = isoString.replace(/\.\d+Z$/, 'Z');

    const date = new Date(clean);
    if (isNaN(date.getDate())) {
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
      }).format(date);
    } catch (e) {
      const pad = (n) => String(n).padStart(2, '0');
      const d = date.getDate();
      const m = date.getMonth() + 1;
      const y = date.getFullYear();
      return `${pad(d)}/${pad(m)}/${y}`;
    }
  };

  const obtenerColorBorde = (paquete, index) => {
    const estado = getEstadoKey(paquete);
    if (estado === 'en_camino') {
      return adminlteColors.info;
    }
    if (estado === 'pendiente') {
      return adminlteColors.warning;
    }
    if (estado === 'entregado') {
      return adminlteColors.success;
    }

    const colors = [
      adminlteColors.primary,
      adminlteColors.secondary,
      adminlteColors.danger,
    ];
    return colors[index % colors.length];
  };

  const handleVerPaquete = (paquete) => {
    if (!paquete) {
      return;
    }

    setPaqueteSeleccionado(paquete);
    setModalDetalleVisible(true);
  };

  const handleCerrarDetalle = () => {
    setModalDetalleVisible(false);
    setPaqueteSeleccionado(null);
  };

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Paquetes</Text>
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <View style={styles.cardHeaderContent}>
            <FontAwesome5
              name="filter"
              size={12}
              color={adminlteColors.secondary}
              style={{ marginRight: 2, marginTop:-2 }}
            />
            <Text style={styles.filterGroupLabel}>Filtros</Text>
          </View>
        </View>
        <View style={styles.cardBody}>
          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.filtrosContainer}
          >
            {filtrosEstado.map((f) => (
              <TouchableOpacity
                key={f.id}
                style={[
                  styles.filtroButton,
                  filtroEstadoActivo === f.id && styles.filtroButtonActive,
                ]}
                onPress={() => setFiltroEstadoActivo(f.id)}
              >
                <FontAwesome5
                  name={f.icon}
                  size={14}
                  color={
                    filtroEstadoActivo === f.id ? '#ffffff' : adminlteColors.primary
                  }
                  style={{ marginRight: 6 }}
                />
                <Text
                  style={[
                    styles.filtroButtonText,
                    filtroEstadoActivo === f.id && styles.filtroButtonTextActive,
                  ]}
                >
                  {f.label}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>

          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            contentContainerStyle={styles.filtrosContainer}
            style={{ marginTop: 12 }}
          >
            <Text style={[styles.filterGroupLabel, { marginTop: 10, marginEnd:5 }]}>Orden:</Text>
            {filtrosOrden.map((f) => (
              <TouchableOpacity
                key={f.id}
                style={[
                  styles.filtroButton,
                  filtroOrdenActivo === f.id && styles.filtroButtonActive,
                ]}
                onPress={() => setFiltroOrdenActivo(f.id)}
              >
                <FontAwesome5
                  name={f.icon}
                  size={14}
                  color={
                    filtroOrdenActivo === f.id ? '#ffffff' : adminlteColors.primary
                  }
                  style={{ marginRight: 6 }}
                />
                <Text
                  style={[
                    styles.filtroButtonText,
                    filtroOrdenActivo === f.id && styles.filtroButtonTextActive,
                  ]}
                >
                  {f.label}
                </Text>
              </TouchableOpacity>
            ))}
          </ScrollView>
        </View>
      </View>
      {/* Lista de Paquetes */}
      <ScrollView style={styles.listaContainer}>
        <View style={styles.grid}>
          {paquetesFiltrados.map((p, idx) => (
            <View
              key={p.id}
              style={[
                styles.itemCard,
                { borderTopWidth: 3, borderTopColor: obtenerColorBorde(p, idx) },
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
                <View
                  style={[
                    styles.estadoBadge,
                    { backgroundColor: getEstadoBadgeColor(p.estadoNombre) },
                  ]}
                >
                  <Text style={styles.estadoBadgeText}>
                    {(p.estadoNombre || '—').toUpperCase()}
                  </Text>
                </View>
              </View>
              
              
              <View style={styles.itemBody}>
                <View style={styles.row}>
                  <FontAwesome5
                    name="user"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Solicitante:</Text>
                  <Text style={styles.valueMuted}>{p.solicitanteNombre || '—'}</Text>
                </View>

                <View style={styles.row}>
                  <FontAwesome5
                    name="id-card"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>CI:</Text>
                  <Text style={styles.valueMuted}>{p.solicitanteCi || '—'}</Text>
                </View>
                <View style={styles.row}>
                  <FontAwesome5
                    name="users"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Comunidad:</Text>
                <Text style={styles.valueMuted}>{p.comunidadSolicitud || '—'}</Text>

                </View>
                <View style={styles.row}>
                  <FontAwesome5
                    name="exclamation-triangle"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Emergencia:</Text>
                <Text style={styles.valueMuted}>{p.tipoEmergencia || '—'}</Text>
                </View>

                <View style={styles.row}>
                  <FontAwesome5
                    name="user-tag"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Voluntario encargado:</Text>
                  <Text style={styles.valueMuted}>
                  {p.voluntarioEncargado || 'El voluntario se asigna al iniciar la ruta'}
                </Text>
                </View>

                <View style={styles.row}>
                  <FontAwesome5
                    name="map-marker-alt"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Ubicación:</Text>
                </View>
                <Text style={styles.valuePrimary}>{p.ubicacionActual || '—'}</Text>
                <View style={styles.row}>
                  <FontAwesome5
                    name="calendar-plus"
                    size={12}
                    color={adminlteColors.muted}
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.label}>Fecha Creación:</Text>
                  <Text style={styles.valueMuted}>{formatFechaAprobacion(p.fechaCreacion) || '—'}</Text>

                </View>

                {esEstadoEntregado(p.estado_id) && p.fechaEntrega ? (
                  <>
                    <View style={styles.row}>
                      <FontAwesome5
                        name="calendar-check"
                        size={12}
                        color={adminlteColors.muted}
                        style={{ marginRight: 6 }}
                      />
                      <Text style={styles.label}>Fecha Entrega:</Text>
                    </View>
                    <Text style={styles.valueMuted}>{formatFechaAprobacion(p.fechaEntrega)}</Text>
                  </>
                ) : null}
              </View>

              <View style={styles.itemActions}>
                <TouchableOpacity
                  style={styles.btnVerPaquete}
                  onPress={() => handleVerPaquete(p)}
                >
                  <FontAwesome5
                    name="eye"
                    size={12}
                    color="#ffffff"
                    style={{ marginRight: 6 }}
                  />
                  <Text style={styles.btnVerPaqueteText}>Ver</Text>
                </TouchableOpacity>

                {!esEstadoEntregado(p.estado_id) && (
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

                      setUbicacionActual(p.ubicacionActual || '');
                      setLatitud(null);
                      setLongitud(null);

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
                )}
              </View>
            </View>
          ))}
        </View>
      </ScrollView>

      <Modal
        visible={modalDetalleVisible}
        animationType="fade"
        transparent
        onRequestClose={handleCerrarDetalle}
      >
        <View style={styles.overlayBackdrop}>
          <View style={styles.modalCardDetalle}>
            <View style={styles.modalHeaderDetalle}>
              <View style={styles.modalHeaderContentDetalle}>
                <FontAwesome5
                  name="box-open"
                  size={18}
                  color="#ffffff"
                  style={{ marginRight: 8 }}
                />
                <Text style={styles.modalHeaderTitleDetalle}>Detalle de Paquete</Text>
              </View>
              <TouchableOpacity
                onPress={handleCerrarDetalle}
                style={styles.modalCloseButtonDetalle}
              >
                <MaterialIcons name="close" size={24} color="#ffffff" />
              </TouchableOpacity>
            </View>

            <ScrollView style={styles.modalBodyCardDetalle}>
              {paqueteSeleccionado ? (
                <View style={styles.detalleContentDetalle}>
                  <View style={styles.alertInfoDetalle}>
                    <Text style={styles.alertInfoTitleDetalle}>
                      Paquete {paqueteSeleccionado.codigoSolicitud || paqueteSeleccionado.codigo || '—'}
                    </Text>
                    <Text style={styles.alertInfoTextDetalle}>
                      Información general del envío y responsables asignados.
                    </Text>
                  </View>

                  {(() => {
                    const referenciaNombre =
                      paqueteSeleccionado.referenciaNombre && paqueteSeleccionado.referenciaNombre !== ''
                        ? paqueteSeleccionado.referenciaNombre
                        : null;
                    const referenciaTelefono =
                      paqueteSeleccionado.referenciaTelefono && paqueteSeleccionado.referenciaTelefono !== ''
                        ? paqueteSeleccionado.referenciaTelefono
                        : null;

                    let referenciaLinea = '—';
                    if (referenciaNombre && referenciaTelefono) {
                      referenciaLinea = `${referenciaNombre} - ${referenciaTelefono}`;
                    } else if (referenciaNombre) {
                      referenciaLinea = referenciaNombre;
                    } else if (referenciaTelefono) {
                      referenciaLinea = referenciaTelefono;
                    }

                    const fechaCreacion =
                      formatFechaAprobacion(
                        paqueteSeleccionado.fechaCreacion || paqueteSeleccionado.fechaAprobacion
                      ) || '—';

                    return (
                      <>
                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Código de solicitud</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {paqueteSeleccionado.codigoSolicitud || paqueteSeleccionado.codigo || '—'}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Nombre del solicitante</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {paqueteSeleccionado.solicitanteNombre || '—'}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>CI</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {paqueteSeleccionado.solicitanteCi || '—'}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Contacto de referencia</Text>
                          <Text style={styles.detalleValueDetalle}>{referenciaLinea}</Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Estado del paquete</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {paqueteSeleccionado.estadoNombre || '—'}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Ubicación actual</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {paqueteSeleccionado.ubicacionActual || '—'}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Fecha creación</Text>
                          <Text style={styles.detalleValueDetalle}>{fechaCreacion}</Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Conductor</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {getConductorDetalle(paqueteSeleccionado.id_conductor)}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Vehículo</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {getVehiculoDetalle(paqueteSeleccionado.id_vehiculo)}
                          </Text>
                        </View>

                        <View style={styles.detalleSectionDetalle}>
                          <Text style={styles.detalleLabelDetalle}>Voluntario encargado</Text>
                          <Text style={styles.detalleValueDetalle}>
                            {paqueteSeleccionado.voluntarioEncargado ||
                              'El voluntario se asigna al iniciar la ruta'}
                          </Text>
                        </View>
                      </>
                    );
                  })()}
                </View>
              ) : (
                <Text style={styles.detalleValueDetalle}>Cargando detalles…</Text>
              )}
            </ScrollView>

            <View style={styles.modalFooterDetalle}>
              <TouchableOpacity
                style={styles.modalFooterButtonSecondary}
                onPress={handleCerrarDetalle}
              >
                <Text style={styles.modalFooterButtonText}>Cerrar</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </Modal>

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

              <View style={styles.input}>
                <Text
                  style={{
                    fontSize: 14,
                    color: adminlteColors.dark,
                  }}
                  numberOfLines={2}
                >
                  {zona || ubicacionActual || 'Ubicación no disponible'}
                </Text>
              </View>

              <Text style={styles.smallTextMuted}>
                Este valor se obtiene automáticamente desde tu ubicación y no puede modificarse.
              </Text>
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

                          {isLoadingUbicacion && (
                            <Text style={styles.smallTextMuted}>Obteniendo ubicación…</Text>
                          )}

                          {ubicacionError ? (
                            <Text style={[styles.smallTextMuted, { color: 'red' }]}>
                              {ubicacionError}
                            </Text>
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
                            ((!estadoEntrega.trim() || !imagenUri) || isSaving) &&
                              styles.modalFooterButtonDisabled,
                          ]}
                          disabled={!estadoEntrega.trim() || !imagenUri || isSaving}
                          onPress={guardarCambiosPaquete}
                        >
                          <Text style={styles.modalFooterButtonText}>
                            {isSaving ? 'Guardando...' : 'Guardar Cambios'}
                          </Text>
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
            <Modal
                visible={showCodigoModal}
                transparent
                animationType="fade"
                onRequestClose={() => {
                  if (isVerifying) return;
                  setShowCodigoModal(false);
                }}
              >
                <View style={styles.pickerOverlay}>
                  <View style={styles.codigoModal}>
                    <Text style={styles.pickerTitle}>Confirmar entrega</Text>
                    <Text style={styles.smallTextMuted}>
                      Se envió un código numérico de 4 dígitos al correo del solicitante.
                      Pídele que te lo dicte e ingrésalo para confirmar la entrega del paquete.
                    </Text>

                    <TextInput
                      style={[
                        styles.input,
                        { textAlign: 'center', letterSpacing: 8, marginTop: 12 },
                      ]}
                      value={codigoEntrega}
                      onChangeText={(txt) => {
                        setCodigoError('');
                        setCodigoEntrega(txt.replace(/[^0-9]/g, '').slice(0, 4));
                      }}
                      keyboardType="number-pad"
                      maxLength={4}
                      placeholder="••••"
                    />

                    {codigoError ? (
                      <Text style={[styles.smallTextMuted, { color: 'red', marginTop: 4 }]}>
                        {codigoError}
                      </Text>
                    ) : null}

                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'flex-end',
                        marginTop: 16,
                      }}
                    >
                      <TouchableOpacity
                        style={[styles.modalFooterButtonSecondary, { marginRight: 8 }]}
                        onPress={() => {
                          if (isVerifying) return;
                          setShowCodigoModal(false);
                        }}
                      >
                        <Text style={styles.modalFooterButtonText}>Cancelar</Text>
                      </TouchableOpacity>

                      <TouchableOpacity
                        style={[
                          styles.modalFooterButtonPrimary,
                          (codigoEntrega.length !== 4 || isVerifying) &&
                            styles.modalFooterButtonDisabled,
                        ]}
                        disabled={codigoEntrega.length !== 4 || isVerifying}
                        onPress={confirmarCodigoEntrega}
                      >
                        <Text style={styles.modalFooterButtonText}>
                          {isVerifying ? 'Verificando...' : 'Confirmar entrega'}
                        </Text>
                      </TouchableOpacity>
                    </View>
                  </View>
                </View>
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
  codigoModal: {
  width: '85%',
  backgroundColor: '#ffffff',
  borderRadius: 10,
  padding: 16,
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
  estadoBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
    alignSelf: 'flex-start',
  },
  estadoBadgeText: { fontSize: 11, fontWeight: '700', color: '#ffffff' },
  itemBody: { padding: 12 },
  itemActions: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    alignItems: 'center',
    paddingHorizontal: 12,
    paddingBottom: 12,
  },
  row: { flexDirection: 'row', alignItems: 'center', marginBottom: 4 },
  label: { fontSize: 13, fontWeight: '600', color: adminlteColors.dark },
  valuePrimary: {
    fontSize: 13,
    color: adminlteColors.primary,
    marginBottom: 2,
    marginLeft: 8,
  },
  valueMuted: {
    fontSize: 13,
    color: adminlteColors.muted,
    marginBottom: 2,
    marginLeft: 8,
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

  overlayBackdrop: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.7)',
    alignItems: 'center',
    justifyContent: 'center',
    padding: 16,
  },
  modalCardDetalle: {
    backgroundColor: '#ffffff',
    borderRadius: 8,
    width: '92%',
    maxWidth: 420,
    maxHeight: '85%',
    overflow: 'hidden',
    elevation: 6,
    alignSelf: 'center',
  },
  modalHeaderDetalle: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: adminlteColors.primary,
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  modalHeaderContentDetalle: {
    flexDirection: 'row',
    alignItems: 'center',
    flex: 1,
  },
  modalHeaderTitleDetalle: {
    color: '#ffffff',
    fontSize: 18,
    fontWeight: '600',
  },
  modalCloseButtonDetalle: {
    padding: 4,
  },
  modalBodyCardDetalle: {
    padding: 16,
    backgroundColor: '#ffffff',
  },
  detalleContentDetalle: {
    flex: 1,
    paddingBottom: 12,
  },
  alertInfoDetalle: {
    backgroundColor: '#d1ecf1',
    borderRadius: 4,
    padding: 12,
    marginBottom: 16,
  },
  alertInfoTitleDetalle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#0c5460',
    marginBottom: 6,
  },
  alertInfoTextDetalle: {
    fontSize: 14,
    color: '#0c5460',
  },
  detalleSectionDetalle: {
    marginBottom: 12,
  },
  detalleLabelDetalle: {
    fontSize: 13,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 4,
  },
  detalleValueDetalle: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  modalFooterDetalle: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: adminlteColors.primary,
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },

  btnVerPaquete: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
    marginRight: 8,
  },
  btnVerPaqueteText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '500',
  },
  btnEditarPaquete: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.info,
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
    marginLeft: 4,
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

card: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 8,
    padding: 12,
    elevation: 3,
    marginBottom: 16,
  },
  cardHeader: {
    marginBottom: 8,
  },
  cardHeaderContent: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  cardHeaderTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  cardBody: {
    paddingTop: 4,
  },
  filtrosContainer: {
    flexDirection: 'row',
    gap: 8,
  },
  filtroButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: adminlteColors.primary,
    paddingHorizontal: 8,
    paddingVertical: 8,
    borderRadius: 4,
    marginRight: 2,
  },
  filtroButtonActive: {
    backgroundColor: adminlteColors.primary,
    borderColor: adminlteColors.primary,
  },
  filtroButtonText: {
    fontSize: 13,
    color: adminlteColors.primary,
    fontWeight: '500',
  },
  filtroButtonTextActive: {
    color: '#ffffff',
  },
  filterGroupLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.muted,
    marginBottom: 4,
  },
});
