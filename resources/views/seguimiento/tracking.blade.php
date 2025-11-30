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
        <a href="{{ route('seguimiento.index') }}" class="btn btn-primary">
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
                <strong>Fecha creación:</strong> {{ $paquete->fecha_creacion }}<br>
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
document.addEventListener("DOMContentLoaded", () => {
    const parsedPoints = JSON.parse(String.raw`{!! json_encode($points) !!}`) || [];
    const points = parsedPoints
        .map(p => ({
            ...p,
            lat: p.lat !== undefined ? parseFloat(p.lat) : null,
            lng: p.lng !== undefined ? parseFloat(p.lng) : null,
        }))
        .filter(p => Number.isFinite(p.lat) && Number.isFinite(p.lng));

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

        truckMarker = L.marker(normalized[0], { title: 'Camión en ruta' }).addTo(mapInstance);

        let index = 0;
        const delayMs = 2500;

        const advance = () => {
            index = (index + 1) % normalized.length;
            truckMarker.setLatLng(normalized[index]);
            truckTimer = setTimeout(advance, delayMs);
        };

        truckTimer = setTimeout(advance, delayMs);
    };

    if (!points.length) {
        return;
    }

    const map = L.map('tracking-map').setView([points[0].lat, points[0].lng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    if (points.length === 1) {
        const point = points[0];
        L.marker([point.lat, point.lng])
            .bindPopup(`<strong>${point.fecha ?? 'Sin fecha'}</strong><br>${point.zona ?? ''}`)
            .addTo(map);
        map.setView([point.lat, point.lng], 14);
        startTruckAnimation(map, [point]);
        return;
    }

    const fallbackPolyline = () => {
        const latLngs = points.map(p => [p.lat, p.lng]);
        L.polyline(latLngs, { color: 'blue', opacity: 0.6, weight: 4 }).addTo(map);
        map.fitBounds(latLngs, { padding: [40, 40] });
        startTruckAnimation(map, latLngs);
    };

    const routing = L.Routing.control({
        waypoints: points.map(p => L.latLng(p.lat, p.lng)),
        router: L.Routing.osrmv1({
            serviceUrl: 'https://router.project-osrm.org/route/v1'
        }),
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
            return L.marker(wp.latLng).bindPopup(`<strong>${point.fecha ?? 'Sin fecha'}</strong><br>${point.zona ?? ''}`);
        }
    }).addTo(map);

    routing.on('routesfound', function(e) {
        const coordinates = e.routes[0]?.coordinates;
        if (coordinates && coordinates.length) {
            map.fitBounds(coordinates.map(c => [c.lat, c.lng]), { padding: [40, 40] });
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