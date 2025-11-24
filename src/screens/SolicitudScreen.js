import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  Alert,
  Modal,
  ScrollView,
  Animated,
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { adminlteColors } from '../theme/adminlte';
import AdminLayout from '../components/AdminLayout';
import { FontAwesome5, MaterialIcons } from '@expo/vector-icons';

// Datos de productos disponibles
const productos = [
  { id: 1, nombre: 'Camisa', sugerido: 125 },
  { id: 2, nombre: 'Polera', sugerido: 89 },
  { id: 3, nombre: 'Pantalón', sugerido: 67 },
  { id: 4, nombre: 'Abrigo', sugerido: 45 },
  { id: 5, nombre: 'Arroz', sugerido: 200 },
  { id: 6, nombre: 'Agua potable', sugerido: 300 },
  { id: 7, nombre: 'Frazadas', sugerido: 78 },
  { id: 8, nombre: 'Carpas', sugerido: 25 },
  { id: 9, nombre: 'Kit de primeros auxilios', sugerido: 15 },
  { id: 10, nombre: 'Medicamentos básicos', sugerido: 35 },
  { id: 11, nombre: 'Calzado', sugerido: 56 },
  { id: 12, nombre: 'Colchones', sugerido: 30 },
  { id: 13, nombre: 'Artículos de higiene', sugerido: 80 },
  { id: 14, nombre: 'Utensilios de cocina', sugerido: 40 },
  { id: 15, nombre: 'Linternas y pilas', sugerido: 60 },
  { id: 16, nombre: 'Alimentos no perecederos', sugerido: 150 },
  { id: 17, nombre: 'Ropa interior', sugerido: 95 },
  { id: 18, nombre: 'Toallas', sugerido: 70 },
  { id: 19, nombre: 'Mantas', sugerido: 55 },
  { id: 20, nombre: 'Herramientas básicas', sugerido: 20 },
];

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

  const [productosSeleccionados, setProductosSeleccionados] = useState({});
  const [modalProductosVisible, setModalProductosVisible] = useState(false);
  const [modalVerificarVisible, setModalVerificarVisible] = useState(false);
  const [paginaActual, setPaginaActual] = useState(1);
  const [codigoBusqueda, setCodigoBusqueda] = useState('');
  const [resultadoBusqueda, setResultadoBusqueda] = useState(null);
  const [mostrarMapaEntrega, setMostrarMapaEntrega] = useState(false);
  const [coordenadasMapa, setCoordenadasMapa] = useState({
    lat: -17.720934,
    lng: -63.166874,
  });
  const [coordenadasEntrega, setCoordenadasEntrega] = useState({
    lat: -17.7833,
    lng: -63.1821,
  });
  const [mapaMarkerPosition, setMapaMarkerPosition] = useState({
    x: 150, // Posición inicial centrada (aproximadamente mitad de 300px)
    y: 150,
  });
  const [mapaEntregaMarkerPosition, setMapaEntregaMarkerPosition] = useState({
    x: 160, // Posición inicial centrada
    y: 160,
  });

  const productosPorPagina = 5;

  const handleChange = (field, value) => {
    setForm(prev => ({ ...prev, [field]: value }));
  };

  const handleBuscarCodigo = () => {
    setModalVerificarVisible(true);
    setResultadoBusqueda(null);
  };

  const handleBuscarCodigoSubmit = () => {
    if (!codigoBusqueda.trim()) {
      Alert.alert('Error', 'Por favor ingresa un código de búsqueda');
      return;
    }
    // Simulación de búsqueda
    setResultadoBusqueda({
      codigo: codigoBusqueda.toUpperCase(),
      fecha: '05/10/2025',
      estado: 'En espera',
      ultimaActualizacion: '06/10/2025 14:32',
      productosSolicitados: [
        { nombre: 'Agua potable', cantidad: 50 },
        { nombre: 'Frazadas', cantidad: 30 },
        { nombre: 'Arroz 10kg', cantidad: 20 },
      ],
      productosAprobados: [
        { nombre: 'Agua potable', cantidad: 40 },
        { nombre: 'Frazadas', cantidad: 25 },
      ],
    });
  };

  const handleVerProductos = () => {
    setModalProductosVisible(true);
  };

  const cambiarCantidad = (id, cambio) => {
    setProductosSeleccionados(prev => {
      const nuevaCantidad = Math.max(0, (prev[id] || 0) + cambio);
      if (nuevaCantidad === 0) {
        const nuevo = { ...prev };
        delete nuevo[id];
        return nuevo;
      }
      return { ...prev, [id]: nuevaCantidad };
    });
  };

  const actualizarCantidad = (id, cantidad) => {
    const numCantidad = parseInt(cantidad) || 0;
    if (numCantidad === 0) {
      setProductosSeleccionados(prev => {
        const nuevo = { ...prev };
        delete nuevo[id];
        return nuevo;
      });
    } else {
      setProductosSeleccionados(prev => ({ ...prev, [id]: numCantidad }));
    }
  };

  const aplicarSugerido = id => {
    const producto = productos.find(p => p.id === id);
    if (producto) {
      setProductosSeleccionados(prev => ({
        ...prev,
        [id]: producto.sugerido,
      }));
    }
  };

  const eliminarProducto = id => {
    setProductosSeleccionados(prev => {
      const nuevo = { ...prev };
      delete nuevo[id];
      return nuevo;
    });
  };

  const guardarProductos = () => {
    const productosConCantidad = Object.keys(productosSeleccionados).filter(
      id => productosSeleccionados[id] > 0,
    );
    if (productosConCantidad.length === 0) {
      Alert.alert('Error', 'Por favor selecciona al menos un producto.');
      return;
    }
    setModalProductosVisible(false);
    Alert.alert('Éxito', 'Productos seleccionados correctamente');
  };

  const cambiarPagina = direccion => {
    const totalPaginas = Math.ceil(productos.length / productosPorPagina);
    const nuevaPagina = paginaActual + direccion;
    if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
      setPaginaActual(nuevaPagina);
    }
  };

  const handleMapaPress = event => {
    const { locationX, locationY } = event.nativeEvent;
    if (locationX !== undefined && locationY !== undefined) {
      setMapaMarkerPosition({ x: locationX, y: locationY });
      const lat = (-17.720934 + (Math.random() - 0.5) * 0.01).toFixed(6);
      const lng = (-63.166874 + (Math.random() - 0.5) * 0.01).toFixed(6);
      setCoordenadasMapa({ lat, lng });
    }
  };

  const handleMapaEntregaClick = event => {
    const { locationX, locationY } = event.nativeEvent;
    if (locationX !== undefined && locationY !== undefined) {
      setMapaEntregaMarkerPosition({ x: locationX, y: locationY });
      const lat = (-17.78 + (Math.random() - 0.5) * 0.02).toFixed(6);
      const lng = (-63.18 + (Math.random() - 0.5) * 0.02).toFixed(6);
      setCoordenadasEntrega({ lat, lng });
    }
  };

  const handleCancelar = () => {
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
    setProductosSeleccionados({});
  };

  const handleEnviar = () => {
    // Validar campos requeridos
    const camposRequeridos = [
      'nombre',
      'apellido',
      'carnet',
      'email',
      'comunidad',
      'direccion',
      'provincia',
      'celular',
      'cantidadPersonas',
      'fechaEmergencia',
      'tipoEmergencia',
    ];
    const camposVacios = camposRequeridos.filter(
      campo => !form[campo] || form[campo].trim() === '',
    );

    if (camposVacios.length > 0) {
      Alert.alert(
        'Campos incompletos',
        'Por favor completa todos los campos requeridos antes de enviar la solicitud.',
      );
      return;
    }

    // Validar productos
    const productosConCantidad = Object.keys(productosSeleccionados).filter(
      id => productosSeleccionados[id] > 0,
    );
    if (productosConCantidad.length === 0) {
      Alert.alert(
        'Productos requeridos',
        'Por favor selecciona al menos un producto antes de enviar la solicitud.',
      );
      setModalProductosVisible(true);
      return;
    }

    // Aquí harás el POST a tu API Laravel
    console.log('Formulario enviado:', form, productosSeleccionados);
    Alert.alert('Solicitud enviada', 'Simulación de envío de solicitud al backend DAS.');
  };

  const productosPagina = productos.slice(
    (paginaActual - 1) * productosPorPagina,
    paginaActual * productosPorPagina,
  );
  const totalPaginas = Math.ceil(productos.length / productosPorPagina);
  const productosConCantidad = Object.keys(productosSeleccionados).filter(
    id => productosSeleccionados[id] > 0,
  );

  return (
    <AdminLayout>
      <Text style={styles.pageTitle}>Solicitar Insumos</Text>

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
                <View style={styles.mapCardBody}>
                  <TouchableOpacity
                    style={styles.mapBox}
                    activeOpacity={1}
                    onPress={handleMapaPress}
                  >
                    {/* Controles de zoom simulados */}
                    <View style={styles.mapZoom}>
                      <TouchableOpacity
                        style={styles.mapZoomBtn}
                        onPress={e => {
                          e.stopPropagation();
                          Alert.alert('Mapa', 'Zoom +');
                        }}
                      >
                        <Text style={styles.mapZoomText}>+</Text>
                      </TouchableOpacity>
                      <TouchableOpacity
                        style={styles.mapZoomBtn}
                        onPress={e => {
                          e.stopPropagation();
                          Alert.alert('Mapa', 'Zoom -');
                        }}
                      >
                        <Text style={styles.mapZoomText}>-</Text>
                      </TouchableOpacity>
                    </View>

                    {/* Marcador con animación */}
                    <View
                      style={[
                        styles.mapMarker,
                        {
                          left: mapaMarkerPosition.x - 12,
                          top: mapaMarkerPosition.y - 24,
                        },
                      ]}
                    >
                      <FontAwesome5
                        name="map-marker-alt"
                        size={24}
                        color={adminlteColors.danger}
                      />
                    </View>

                    {/* Coordenadas */}
                    <View style={styles.mapInfoBottom}>
                      <Text style={styles.mapInfoText}>
                        Coordenadas: {coordenadasMapa.lat}, {coordenadasMapa.lng}
                      </Text>
                    </View>

                    <Text style={[styles.mapLabel, { top: 50, left: 50 }]}>
                      Centro de Santa Cruz
                    </Text>
                    <Text style={[styles.mapLabel, { top: 80, left: 100 }]}>
                      Plaza 24 de Septiembre
                    </Text>
                    <Text style={[styles.mapLabel, { top: 120, left: 150 }]}>
                      Mercado Central
                    </Text>
                    <Text style={[styles.mapLabel, { top: 160, left: 80 }]}>
                      Terminal Bimodal
                    </Text>
                  </TouchableOpacity>
                </View>
              </View>
              <View
                style={{ marginTop: 4, flexDirection: 'row', alignItems: 'center' }}
              >
                <FontAwesome5
                  name="info-circle"
                  size={12}
                  color={adminlteColors.muted}
                  style={{ marginRight: 4 }}
                />
                <Text style={styles.smallMuted}>
                  Puede hacer clic en el mapa para seleccionar la ubicación exacta
                  de entrega
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
              {productosConCantidad.length === 0 ? (
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
                productosConCantidad.map(id => {
                  const producto = productos.find(p => p.id == id);
                  const cantidad = productosSeleccionados[id];
                  return (
                    <View key={id} style={styles.productCard}>
                      <View style={styles.productCardBody}>
                        <View style={styles.productCardContent}>
                          <Text style={styles.productCardName}>{producto.nombre}</Text>
                          <Text style={styles.productCardQty}>
                            Cantidad: {cantidad}
                          </Text>
                        </View>
                        <View style={styles.productCardBadge}>
                          <Text style={styles.productCardBadgeText}>
                            {cantidad}
                          </Text>
                        </View>
                      </View>
                    </View>
                  );
                })
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

      {/* Modal para seleccionar productos */}
      <Modal
        visible={modalProductosVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalProductosVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={styles.modalHeader}>
            <View style={styles.modalHeaderContent}>
              <FontAwesome5
                name="boxes"
                size={20}
                color="#ffffff"
                style={{ marginRight: 8 }}
              />
              <Text style={styles.modalHeaderTitle}>Seleccionar Productos</Text>
            </View>
            <TouchableOpacity
              onPress={() => setModalProductosVisible(false)}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <View style={styles.modalBody}>
            <View style={styles.modalBodyRow}>
              {/* Columna izquierda - Productos disponibles */}
              <View style={styles.modalLeftColumn}>
                <View style={styles.modalSectionHeader}>
                  <Text style={styles.modalSectionTitle}>
                    Seleccione Los Productos:
                  </Text>
                  <View style={styles.badge}>
                    <Text style={styles.badgeText}>
                      Disponibles{' '}
                      {(paginaActual - 1) * productosPorPagina + 1}-
                      {Math.min(
                        paginaActual * productosPorPagina,
                        productos.length,
                      )}{' '}
                      de {productos.length}
                    </Text>
                  </View>
                </View>

                <ScrollView style={styles.productosList}>
                  {productosPagina.map(producto => {
                    const cantidad = productosSeleccionados[producto.id] || 0;
                    return (
                      <View key={producto.id} style={styles.productCardModal}>
                        <View style={styles.productCardModalBody}>
                          <View style={styles.productCardModalLeft}>
                            <Text style={styles.productCardModalName}>
                              {producto.nombre}
                            </Text>
                            <View style={styles.productCardModalSugerido}>
                              <Text style={styles.productCardModalSugeridoText}>
                                Sugerido: {producto.sugerido}
                              </Text>
                              <TouchableOpacity
                                style={styles.btnAplicar}
                                onPress={() => aplicarSugerido(producto.id)}
                              >
                                <Text style={styles.btnAplicarText}>Aplicar</Text>
                              </TouchableOpacity>
                            </View>
                          </View>
                          <View style={styles.productCardModalRight}>
                            <View style={styles.inputGroup}>
                              <TouchableOpacity
                                style={styles.inputGroupBtn}
                                onPress={() => cambiarCantidad(producto.id, -1)}
                              >
                                <FontAwesome5 name="minus" size={12} color="#6c757d" />
                              </TouchableOpacity>
                              <TextInput
                                style={styles.inputGroupInput}
                                value={cantidad.toString()}
                                onChangeText={text =>
                                  actualizarCantidad(producto.id, text)
                                }
                                keyboardType="numeric"
                              />
                              <TouchableOpacity
                                style={styles.inputGroupBtn}
                                onPress={() => cambiarCantidad(producto.id, 1)}
                              >
                                <FontAwesome5 name="plus" size={12} color="#6c757d" />
                              </TouchableOpacity>
                            </View>
                            <Text style={styles.productCardModalCantidad}>
                              {cantidad} seleccionados
                            </Text>
                          </View>
                        </View>
                      </View>
                    );
                  })}
                </ScrollView>

                {/* Paginación */}
                <View style={styles.pagination}>
                  <TouchableOpacity
                    style={[
                      styles.paginationButton,
                      paginaActual === 1 && styles.paginationButtonDisabled,
                    ]}
                    onPress={() => cambiarPagina(-1)}
                    disabled={paginaActual === 1}
                  >
                    <FontAwesome5
                      name="chevron-left"
                      size={14}
                      color={paginaActual === 1 ? '#ccc' : adminlteColors.dark}
                    />
                  </TouchableOpacity>
                  {Array.from({ length: totalPaginas }, (_, i) => i + 1).map(
                    num => (
                      <TouchableOpacity
                        key={num}
                        style={[
                          styles.paginationNumber,
                          paginaActual === num && styles.paginationNumberActive,
                        ]}
                        onPress={() => setPaginaActual(num)}
                      >
                        <Text
                          style={[
                            styles.paginationNumberText,
                            paginaActual === num &&
                              styles.paginationNumberTextActive,
                          ]}
                        >
                          {num}
                        </Text>
                      </TouchableOpacity>
                    ),
                  )}
                  <TouchableOpacity
                    style={[
                      styles.paginationButton,
                      paginaActual === totalPaginas &&
                        styles.paginationButtonDisabled,
                    ]}
                    onPress={() => cambiarPagina(1)}
                    disabled={paginaActual === totalPaginas}
                  >
                    <FontAwesome5
                      name="chevron-right"
                      size={14}
                      color={
                        paginaActual === totalPaginas
                          ? '#ccc'
                          : adminlteColors.dark
                      }
                    />
                  </TouchableOpacity>
                </View>
              </View>

              {/* Columna derecha - Productos seleccionados */}
              <View style={styles.modalRightColumn}>
                <Text style={styles.modalSectionTitle}>
                  Productos Seleccionados:{' '}
                </Text>
                <ScrollView style={styles.productosSeleccionadosList}>
                  {productosConCantidad.length === 0 ? (
                    <View style={styles.emptyProductsModal}>
                      <FontAwesome5
                        name="box-open"
                        size={48}
                        color={adminlteColors.muted}
                        style={{ marginBottom: 12 }}
                      />
                      <Text style={styles.emptyProductsModalText}>
                        No tienes productos seleccionados.
                      </Text>
                    </View>
                  ) : (
                    productosConCantidad.map(id => {
                      const producto = productos.find(p => p.id == id);
                      const cantidad = productosSeleccionados[id];
                      return (
                        <View key={id} style={styles.productCardModal}>
                          <View style={styles.productCardModalBody}>
                            <View style={styles.productCardModalContent}>
                              <Text style={styles.productCardModalName}>
                                {producto.nombre}
                              </Text>
                              <Text style={styles.productCardModalCantidad}>
                                Cantidad: {cantidad}
                              </Text>
                            </View>
                            <TouchableOpacity
                              style={styles.btnEliminar}
                              onPress={() => eliminarProducto(id)}
                            >
                              <FontAwesome5 name="trash" size={14} color="#ffffff" />
                            </TouchableOpacity>
                          </View>
                        </View>
                      );
                    })
                  )}
                </ScrollView>
              </View>
            </View>
          </View>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalProductosVisible(false)}
            >
              <MaterialIcons name="close" size={18} color="#ffffff" />
              <Text style={styles.modalFooterButtonText}>Cerrar</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={styles.modalFooterButtonSuccess}
              onPress={guardarProductos}
            >
              <FontAwesome5 name="check" size={16} color="#ffffff" />
              <Text style={styles.modalFooterButtonText}>Guardar</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* Modal Verificar Solicitud */}
      <Modal
        visible={modalVerificarVisible}
        animationType="slide"
        transparent={false}
        onRequestClose={() => setModalVerificarVisible(false)}
      >
        <View style={styles.modalContainer}>
          <View style={[styles.modalHeader, { backgroundColor: adminlteColors.info }]}>
            <Text style={styles.modalHeaderTitle}>
              Verifica el estado de tu solicitud
            </Text>
            <TouchableOpacity
              onPress={() => setModalVerificarVisible(false)}
              style={styles.modalCloseButton}
            >
              <MaterialIcons name="close" size={24} color="#ffffff" />
            </TouchableOpacity>
          </View>

          <ScrollView style={styles.modalBody}>
            <View style={styles.buscarCodigoContainer}>
              <View style={styles.inputGroupBuscar}>
                <TextInput
                  style={styles.inputBuscar}
                  value={codigoBusqueda}
                  onChangeText={setCodigoBusqueda}
                  placeholder="SJDC001"
                />
                <TouchableOpacity
                  style={styles.btnBuscar}
                  onPress={handleBuscarCodigoSubmit}
                >
                  <FontAwesome5 name="search" size={16} color="#ffffff" />
                </TouchableOpacity>
              </View>
            </View>

            {resultadoBusqueda && (
              <View style={styles.boxResultado}>
                <View style={styles.boxResultadoHeader}>
                  <Text style={styles.boxResultadoTitle}>
                    Resultado de búsqueda
                  </Text>
                </View>
                <View style={styles.boxResultadoBody}>
                  <View style={styles.row}>
                    <View style={styles.col}>
                      <View style={styles.dlItem}>
                        <Text style={styles.dt}>Código</Text>
                        <Text style={styles.dd}>{resultadoBusqueda.codigo}</Text>
                      </View>
                      <View style={styles.dlItem}>
                        <Text style={styles.dt}>Fecha de Solicitud</Text>
                        <Text style={styles.dd}>{resultadoBusqueda.fecha}</Text>
                      </View>
                      <View style={styles.dlItem}>
                        <Text style={styles.dt}>Estado</Text>
                        <View style={styles.labelEstado}>
                          <Text style={styles.labelEstadoText}>
                            {resultadoBusqueda.estado}
                          </Text>
                        </View>
                      </View>
                    </View>
                    <View style={styles.col}>
                      <View style={styles.dlItem}>
                        <Text style={styles.dt}>Última actualización</Text>
                        <Text style={styles.dd}>
                          {resultadoBusqueda.ultimaActualizacion}
                        </Text>
                      </View>
                    </View>
                  </View>

                  <View style={styles.divider} />

                  <View style={styles.row}>
                    <View style={styles.col}>
                      <Text style={styles.h5}>Productos solicitados</Text>
                      <View style={styles.listGroup}>
                        {resultadoBusqueda.productosSolicitados.map(
                          (item, index) => (
                            <View key={index} style={styles.listGroupItem}>
                              <Text style={styles.listGroupItemText}>
                                {item.nombre} (x {item.cantidad})
                              </Text>
                            </View>
                          ),
                        )}
                      </View>
                    </View>
                    <View style={styles.col}>
                      <Text style={styles.h5}>Productos aprobados</Text>
                      <View style={styles.listGroup}>
                        {resultadoBusqueda.productosAprobados.map(
                          (item, index) => (
                            <View key={index} style={styles.listGroupItem}>
                              <Text style={styles.listGroupItemText}>
                                {item.nombre} (x {item.cantidad})
                              </Text>
                            </View>
                          ),
                        )}
                      </View>
                    </View>
                  </View>

                  <TouchableOpacity
                    style={styles.btnActualizarEntrega}
                    onPress={() => setMostrarMapaEntrega(!mostrarMapaEntrega)}
                  >
                    <FontAwesome5
                      name="map-marker-alt"
                      size={16}
                      color="#ffffff"
                      style={{ marginRight: 6 }}
                    />
                    <Text style={styles.btnActualizarEntregaText}>
                      Actualizar punto de Entrega
                    </Text>
                  </TouchableOpacity>

                  {mostrarMapaEntrega && (
                    <View style={styles.panelMapaEntrega}>
                      <View style={styles.panelMapaEntregaHeader}>
                        <Text style={styles.panelMapaEntregaTitle}>
                          Selecciona el punto de entrega
                        </Text>
                      </View>
                      <View style={styles.panelMapaEntregaBody}>
                        <Text style={styles.panelMapaEntregaText}>
                          Haz clic en el mapa para definir el nuevo punto de
                          entrega.
                        </Text>
                        <TouchableOpacity
                          style={styles.mapEntrega}
                          activeOpacity={1}
                          onPress={handleMapaEntregaClick}
                        >
                          <View
                            style={[
                              styles.mapEntregaMarker,
                              {
                                left: mapaEntregaMarkerPosition.x - 14,
                                top: mapaEntregaMarkerPosition.y - 28,
                              },
                            ]}
                          >
                            <FontAwesome5
                              name="map-marker-alt"
                              size={28}
                              color={adminlteColors.danger}
                            />
                          </View>
                        </TouchableOpacity>
                        <View style={styles.row}>
                          <View style={styles.col}>
                            <Text style={styles.label}>Latitud</Text>
                            <TextInput
                              style={styles.input}
                              value={coordenadasEntrega.lat}
                              editable={false}
                            />
                          </View>
                          <View style={styles.col}>
                            <Text style={styles.label}>Longitud</Text>
                            <TextInput
                              style={styles.input}
                              value={coordenadasEntrega.lng}
                              editable={false}
                            />
                          </View>
                        </View>
                      </View>
                      <View style={styles.panelMapaEntregaFooter}>
                        <TouchableOpacity
                          style={styles.btnCancelarMapa}
                          onPress={() => setMostrarMapaEntrega(false)}
                        >
                          <Text style={styles.btnCancelarMapaText}>Cancelar</Text>
                        </TouchableOpacity>
                        <TouchableOpacity
                          style={styles.btnGuardarMapa}
                          onPress={() => {
                            setMostrarMapaEntrega(false);
                            Alert.alert('Éxito', 'Punto de entrega actualizado');
                          }}
                        >
                          <Text style={styles.btnGuardarMapaText}>Guardar</Text>
                        </TouchableOpacity>
                      </View>
                    </View>
                  )}

                  <View style={styles.boxResultadoFooter}>
                    <Text style={styles.boxResultadoFooterText}>
                      <FontAwesome5
                        name="info-circle"
                        size={12}
                        color={adminlteColors.muted}
                      />{' '}
                      Los tiempos de entrega pueden variar según disponibilidad.
                    </Text>
                  </View>
                </View>
              </View>
            )}
          </ScrollView>

          <View style={styles.modalFooter}>
            <TouchableOpacity
              style={styles.modalFooterButtonSecondary}
              onPress={() => setModalVerificarVisible(false)}
            >
              <Text style={styles.modalFooterButtonText}>Cerrar</Text>
            </TouchableOpacity>
          </View>
        </View>
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
    flexWrap: 'wrap',
  },
  cardHeaderTitle: {
    flex: 1,
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginRight: 8,
    minWidth: 200,
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
  mapCardBody: {
    padding: 2,
  },
  mapBox: {
    height: 300,
    backgroundColor: '#e3f2fd',
    borderRadius: 4,
    borderWidth: 1,
    borderColor: '#dee2e6',
    position: 'relative',
    overflow: 'hidden',
    // Gradiente simulado con múltiples capas
  },
  mapZoom: {
    position: 'absolute',
    top: 10,
    left: 10,
    backgroundColor: '#ffffff',
    borderRadius: 3,
    elevation: 2,
    flexDirection: 'row',
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
    transform: [{ translateX: -12 }, { translateY: -24 }],
    zIndex: 10,
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
  productCard: {
    marginBottom: 8,
    borderLeftWidth: 4,
    borderLeftColor: adminlteColors.primary,
  },
  productCardBody: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#e3f2fd',
    padding: 8,
    borderRadius: 4,
  },
  productCardContent: {
    flex: 1,
  },
  productCardName: {
    fontSize: 14,
    fontWeight: '600',
    color: '#1976d2',
    marginBottom: 4,
  },
  productCardQty: {
    fontSize: 12,
    color: adminlteColors.muted,
  },
  productCardBadge: {
    backgroundColor: adminlteColors.primary,
    borderRadius: 12,
    paddingHorizontal: 8,
    paddingVertical: 4,
  },
  productCardBadgeText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '600',
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
  // Modal styles
  modalContainer: {
    flex: 1,
    backgroundColor: adminlteColors.bodyBg,
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
  modalHeaderTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#ffffff',
  },
  modalCloseButton: {
    padding: 4,
  },
  modalBody: {
    flex: 1,
    padding: 16,
  },
  modalBodyRow: {
    flexDirection: 'row',
    flex: 1,
  },
  modalLeftColumn: {
    flex: 1,
    backgroundColor: '#f8f9fa',
    padding: 12,
    marginRight: 8,
    borderRadius: 4,
  },
  modalRightColumn: {
    flex: 1,
    backgroundColor: '#f8f9fa',
    padding: 12,
    marginLeft: 8,
    borderRadius: 4,
  },
  modalSectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  modalSectionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 12,
  },
  badge: {
    backgroundColor: adminlteColors.info,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
  },
  badgeText: {
    color: '#ffffff',
    fontSize: 11,
    fontWeight: '500',
  },
  productosList: {
    flex: 1,
  },
  productosSeleccionadosList: {
    flex: 1,
  },
  productCardModal: {
    marginBottom: 12,
    borderLeftWidth: 4,
    borderLeftColor: adminlteColors.primary,
  },
  productCardModalBody: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#e3f2fd',
    padding: 12,
    borderRadius: 4,
  },
  productCardModalLeft: {
    flex: 1,
  },
  productCardModalRight: {
    alignItems: 'flex-end',
  },
  productCardModalContent: {
    flex: 1,
  },
  productCardModalName: {
    fontSize: 14,
    fontWeight: '600',
    color: '#1976d2',
    marginBottom: 4,
  },
  productCardModalSugerido: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 4,
  },
  productCardModalSugeridoText: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginRight: 8,
  },
  productCardModalCantidad: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginTop: 4,
  },
  inputGroup: {
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    backgroundColor: '#ffffff',
  },
  inputGroupBtn: {
    paddingHorizontal: 8,
    paddingVertical: 6,
  },
  inputGroupInput: {
    width: 60,
    textAlign: 'center',
    paddingVertical: 4,
    fontSize: 14,
    borderLeftWidth: 1,
    borderRightWidth: 1,
    borderColor: '#ced4da',
  },
  btnAplicar: {
    backgroundColor: adminlteColors.success,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 4,
  },
  btnAplicarText: {
    color: '#ffffff',
    fontSize: 11,
    fontWeight: '500',
  },
  btnEliminar: {
    backgroundColor: adminlteColors.danger,
    paddingHorizontal: 8,
    paddingVertical: 6,
    borderRadius: 4,
  },
  emptyProductsModal: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 40,
  },
  emptyProductsModalText: {
    fontSize: 14,
    color: adminlteColors.muted,
    textAlign: 'center',
  },
  pagination: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 16,
    gap: 4,
  },
  paginationButton: {
    paddingHorizontal: 8,
    paddingVertical: 6,
  },
  paginationButtonDisabled: {
    opacity: 0.5,
  },
  paginationNumber: {
    paddingHorizontal: 10,
    paddingVertical: 6,
    borderRadius: 4,
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#dee2e6',
  },
  paginationNumberActive: {
    backgroundColor: adminlteColors.primary,
    borderColor: adminlteColors.primary,
  },
  paginationNumberText: {
    fontSize: 12,
    color: adminlteColors.dark,
  },
  paginationNumberTextActive: {
    color: '#ffffff',
  },
  modalFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: adminlteColors.primary,
    gap: 8,
  },
  modalFooterButtonSecondary: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
    gap: 6,
  },
  modalFooterButtonSuccess: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.success,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
    gap: 6,
  },
  modalFooterButtonText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },
  // Modal Verificar Solicitud
  buscarCodigoContainer: {
    marginBottom: 16,
  },
  inputGroupBuscar: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  inputBuscar: {
    flex: 1,
    backgroundColor: '#ffffff',
    borderWidth: 1,
    borderColor: '#ced4da',
    borderRadius: 4,
    paddingHorizontal: 12,
    paddingVertical: 8,
    fontSize: 14,
    marginRight: 8,
  },
  btnBuscar: {
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  boxResultado: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 4,
    borderWidth: 1,
    borderColor: '#dee2e6',
    marginTop: 16,
  },
  boxResultadoHeader: {
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
    backgroundColor: adminlteColors.info,
  },
  boxResultadoTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#ffffff',
  },
  boxResultadoBody: {
    padding: 12,
  },
  dlItem: {
    marginBottom: 8,
  },
  dt: {
    fontSize: 12,
    fontWeight: '600',
    color: adminlteColors.dark,
    marginBottom: 2,
  },
  dd: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  labelEstado: {
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 4,
    alignSelf: 'flex-start',
  },
  labelEstadoText: {
    color: '#ffffff',
    fontSize: 12,
    fontWeight: '500',
  },
  divider: {
    height: 1,
    backgroundColor: '#dee2e6',
    marginVertical: 12,
  },
  h5: {
    fontSize: 14,
    fontWeight: '700',
    color: adminlteColors.dark,
    marginBottom: 8,
  },
  listGroup: {
    borderWidth: 1,
    borderColor: '#dee2e6',
    borderRadius: 4,
    overflow: 'hidden',
  },
  listGroupItem: {
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
    backgroundColor: '#ffffff',
  },
  listGroupItemText: {
    fontSize: 14,
    color: adminlteColors.dark,
  },
  btnActualizarEntrega: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: adminlteColors.info,
    paddingHorizontal: 16,
    paddingVertical: 10,
    borderRadius: 4,
    marginTop: 16,
    alignSelf: 'flex-start',
  },
  btnActualizarEntregaText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },
  panelMapaEntrega: {
    backgroundColor: adminlteColors.cardBg,
    borderRadius: 4,
    borderWidth: 1,
    borderColor: '#dee2e6',
    marginTop: 16,
  },
  panelMapaEntregaHeader: {
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderBottomColor: '#dee2e6',
  },
  panelMapaEntregaTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: adminlteColors.dark,
  },
  panelMapaEntregaBody: {
    padding: 12,
  },
  panelMapaEntregaText: {
    fontSize: 12,
    color: adminlteColors.muted,
    marginBottom: 8,
  },
  mapEntrega: {
    height: 320,
    backgroundColor: '#f4f6f9',
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 4,
    position: 'relative',
    marginBottom: 12,
  },
  mapEntregaMarker: {
    position: 'absolute',
    zIndex: 10,
  },
  panelMapaEntregaFooter: {
    flexDirection: 'row',
    justifyContent: 'flex-end',
    paddingHorizontal: 12,
    paddingVertical: 10,
    borderTopWidth: 1,
    borderTopColor: '#dee2e6',
    gap: 8,
  },
  btnCancelarMapa: {
    backgroundColor: adminlteColors.secondary,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  btnCancelarMapaText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },
  btnGuardarMapa: {
    backgroundColor: adminlteColors.success,
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 4,
  },
  btnGuardarMapaText: {
    color: '#ffffff',
    fontSize: 14,
    fontWeight: '500',
  },
  boxResultadoFooter: {
    marginTop: 16,
  },
  boxResultadoFooterText: {
    fontSize: 12,
    color: adminlteColors.muted,
  },
});
