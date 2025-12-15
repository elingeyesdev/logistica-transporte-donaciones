@extends('adminlte::page')

@section('title','Tracking del Paquete')
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <style>
        .leaflet-routing-container {
            display: none !important;
        }
    </style>
@endsection
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Tracking del Paquete: {{ $paquete->solicitud->codigo_seguimiento }}</h1>
        <a href="{{ route('paquete.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@endsection


@section('content')
<div class="container-fluid">

    <div class="card mb-4">
        <div class="card-header"><strong>Información del Paquete</strong></div>
        <div class="card-body row">

            <div class="col-md-4">
                <h5>Información de la Solicitud</h5>

                <strong>Código:</strong> {{ $paquete->solicitud->codigo_seguimiento }}<br>
                <strong>Estado:</strong> {{ optional($paquete->estado)->nombre_estado }}<br>
                <strong>Fecha creación:</strong> {{ \Carbon\Carbon::parse($paquete->fecha_creacion)->format('d/m/Y') }}<br>
            </div>

            <div class="col-md-4">
                <h5>Información del Solicitante</h5>
                <strong>Nombre Completo:</strong> 
                    {{ optional($paquete->solicitud->solicitante)->nombre }} 
                    {{ optional($paquete->solicitud->solicitante)->apellido }}<br>
                <strong>CI:</strong> {{ optional($paquete->solicitud->solicitante)->ci }}<br>
                <strong>Comunidad:</strong> 
                    {{ optional($paquete->solicitud->destino)->comunidad }}<br>
            </div>

            <div class="col-md-4">
             <h5>Información del Transporte</h5>
                <strong>Conductor:</strong> 
                    {{ optional($paquete->conductor)->nombre }} 
                    {{ optional($paquete->conductor)->apellido }}<br>
                <strong>CI:</strong> {{ optional($paquete->conductor)->ci }}<br>
                <strong>Vehículo:</strong> 
                    {{ optional($paquete->vehiculo)->placa }}, 
                    {{ optional($paquete->vehiculo->marcaVehiculo)->nombre_marca }} {{ optional($paquete->vehiculo)->modelo }} {{ optional($paquete->vehiculo)->color }}<br>
            </div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Mapa del Recorrido</strong></div>
        <div class="card-body">
            <div id="tracking-map" style="height: 500px; width:100%; border-radius:8px;">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Historial Completo</strong></div>

        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Zona</th>
                        <th>Conductor</th>
                        <th>Vehículo</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($historial as $h)
                    <tr>
                        
                        <td> {{ \Carbon\Carbon::parse($h->fecha_actualizacion)->format('d/m/Y - H:m') }}</td>
                        <td>{{ $h->estado }}</td>
                        <td>{{ optional($h->ubicacion)->zona }}</td>
                        <td>{{ $h->conductor_nombre }}</td>
                        <td>{{ $h->vehiculo_placa }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

@section('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script>
    window.MAPBOX_TOKEN = @json(config('services.mapbox.token'));
</script>
<script>
const formatDate = (dateStr) => {
    if (!dateStr) return 'Sin fecha';
    const d = new Date(dateStr);
    if (isNaN(d)) return 'Sin fecha';

    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();

    return `${day}/${month}/${year}`;
};

document.addEventListener("DOMContentLoaded", () => {
    const parsedPoints = JSON.parse(String.raw`{!! json_encode($points) !!}`) || [];
    const points = parsedPoints
        .map(p => ({
            ...p,
            lat: p.lat !== undefined ? parseFloat(p.lat) : null,
            lng: p.lng !== undefined ? parseFloat(p.lng) : null,
        }))
        .filter(p => Number.isFinite(p.lat) && Number.isFinite(p.lng));
    
    const destinoLatRaw = @json(optional(optional($paquete->solicitud)->destino)->latitud);
    const destinoLngRaw = @json(optional(optional($paquete->solicitud)->destino)->longitud);

    const destinoLat = destinoLatRaw !== null && destinoLatRaw !== undefined ? parseFloat(destinoLatRaw) : NaN;
    const destinoLng = destinoLngRaw !== null && destinoLngRaw !== undefined ? parseFloat(destinoLngRaw) : NaN;

    const hasDestino = Number.isFinite(destinoLat) && Number.isFinite(destinoLng);
    let destinoMarker = null;

    const addDestinoMarker = (mapInstance) => {
        if (!hasDestino) return null;

        const destinoIcon = L.divIcon({
            className: 'bg-transparent',
            html: `
                <div style="
                    background:#dc3545;
                    border:2px solid #dc3545;
                    border-radius:50%;
                    width:34px;height:34px;
                    display:flex;align-items:center;justify-content:center;
                    box-shadow:0 3px 8px rgba(0,0,0,.35);
                ">
                    <i class="fas fa-map-marker-alt" style="color:#fff;font-size:16px;"></i>
                </div>
            `,
            iconSize: [34, 34],
            iconAnchor: [17, 17]
        });

        const m = L.marker([destinoLat, destinoLng], { icon: destinoIcon, zIndexOffset: 1000 })
            .addTo(mapInstance);

        m.bindTooltip('Destino', {
            permanent: true,
            direction: 'top',
            offset: [0, -16],
            opacity: 0.95
        });

        return m;
    };


    let truckMarker = null;
    let truckTimer = null;

    const startTruckAnimation = (mapInstance, coords) => {
        if (!coords.length) return;

        const normalized = coords.map(coord => {
            if (coord && coord.lat !== undefined && coord.lng !== undefined) {
                return L.latLng(coord.lat, coord.lng);
            }
            if (coord && coord.latLng && coord.latLng.lat !== undefined && coord.latLng.lng !== undefined) {
                return L.latLng(coord.latLng.lat, coord.latLng.lng);
            }
            if (Array.isArray(coord) && coord.length >= 2) {
                return L.latLng(coord[0], coord[1]);
            }
            return null;
        }).filter(Boolean);

        if (!normalized.length) return;

        if (truckMarker) {
            mapInstance.removeLayer(truckMarker);
            truckMarker = null;
        }

        if (truckTimer) {
            clearTimeout(truckTimer);
            truckTimer = null;
        }

        const truckIcon = L.divIcon({
            className: 'bg-transparent',
            html: '<div style="background-color: #007bff; border: 2px solid white; border-radius: 50%; width: 36px; height: 36px; display: flex; justify-content: center; align-items: center; box-shadow: 0 3px 6px rgba(0,0,0,0.3);"><i class="fas fa-truck" style="color: white; font-size: 18px;"></i></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        truckMarker = L.marker(normalized[0], { icon: truckIcon, title: 'Camión en ruta' }).addTo(mapInstance);

        let index = 0;
        const delayMs = 2500;

        const advance = () => {
            index = (index + 1) % normalized.length;
            truckMarker.setLatLng(normalized[index]);
            truckTimer = setTimeout(advance, delayMs);
        };

        truckTimer = setTimeout(advance, delayMs);
    };

    if (!points.length && !hasDestino) {
        return;
    }

    const initialCenter = points.length ? [points[0].lat, points[0].lng] : [destinoLat, destinoLng];
    const initialZoom = points.length ? 12 : 14;

    const map = L.map('tracking-map').setView(initialCenter, initialZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    if (!points.length) {
        map.setView([destinoLat, destinoLng], 14);
        return;
    }

    if (points.length === 1) {
        const point = points[0];
        L.marker([point.lat, point.lng])
            .bindPopup(`<strong>${formatDate(point.fecha)}</strong><br>${point.zona ?? ''}`)
            .addTo(map);
        if (hasDestino) {
            const b = L.latLngBounds([[point.lat, point.lng], [destinoLat, destinoLng]]);
            map.fitBounds(b, { padding: [40, 40] });
        } else {
            map.setView([point.lat, point.lng], 14);
        }

        startTruckAnimation(map, [point]);
        return;
    }

    const fallbackPolyline = () => {
        const latLngs = points.map(p => [p.lat, p.lng]);
        L.polyline(latLngs, { color: 'blue', opacity: 0.6, weight: 4 }).addTo(map);
        const bounds = L.latLngBounds(latLngs);
        if (hasDestino) bounds.extend([destinoLat, destinoLng]);
        map.fitBounds(bounds, { padding: [40, 40] });

        startTruckAnimation(map, latLngs);
    };

    const routing = L.Routing.control({
        waypoints: points.map(p => L.latLng(p.lat, p.lng)),
        router: L.Routing.mapbox(window.MAPBOX_TOKEN),
        lineOptions: {
            styles: [{ color: 'blue', opacity: 0.7, weight: 5 }]
        },
        routeWhileDragging: false,
        addWaypoints: false,
        draggableWaypoints: false,
        fitSelectedRoutes: true,
        show: false,
        createMarker: function(i, wp) {
            const point = points[i];
            return L.marker(wp.latLng).bindPopup(`<strong>${formatDate(point.fecha) ?? 'Sin fecha'}</strong><br>${point.zona ?? ''}`);
        }
    }).addTo(map);
    destinoMarker = addDestinoMarker(map);


    routing.on('routesfound', function(e) {
        const coordinates = e.routes[0]?.coordinates;
        if (coordinates && coordinates.length) {
            const bounds = L.latLngBounds(coordinates.map(c => [c.lat, c.lng]));
            if (hasDestino) bounds.extend([destinoLat, destinoLng]);
            map.fitBounds(bounds, { padding: [40, 40] });

            startTruckAnimation(map, coordinates);
        }
    });

    routing.on('routingerror', function(err) {
        console.warn('OSRM routing failed, using straight polyline fallback.', err);
        routing.remove();
        fallbackPolyline();
    });
});
</script>
@endsection