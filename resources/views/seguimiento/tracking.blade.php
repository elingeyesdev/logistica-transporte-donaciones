@extends('adminlte::page')

@section('title','Tracking del Paquete')
 @section('css')
            <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
            @endsection
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Tracking del Paquete: {{ $paquete->codigo }}</h1>
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
                <strong>Código:</strong> {{ $paquete->codigo }}<br>
                <strong>Estado:</strong> {{ optional($paquete->estado)->nombre_estado }}<br>
                <strong>Fecha creación:</strong> {{ $paquete->fecha_creacion }}<br>
            </div>

            <div class="col-md-4">
                <strong>Solicitante:</strong> 
                    {{ optional($paquete->solicitud->solicitante)->nombre }} 
                    {{ optional($paquete->solicitud->solicitante)->apellido }}<br>
                <strong>CI:</strong> {{ optional($paquete->solicitud->solicitante)->ci }}<br>
                <strong>Comunidad:</strong> 
                    {{ optional($paquete->solicitud->destino)->comunidad }}<br>
            </div>

            <div class="col-md-4">
                <strong>Conductor:</strong> 
                    {{ optional($paquete->conductor)->nombre }} 
                    {{ optional($paquete->conductor)->apellido }}<br>
                <strong>CI:</strong> {{ optional($paquete->conductor)->ci }}<br>
                <strong>Vehículo:</strong> 
                    {{ optional($paquete->vehiculo)->placa }}
                    ({{ optional($paquete->vehiculo->marcaVehiculo)->nombre_marca }})<br>
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
                        <td>{{ $h->vehiculo_placa }}</td>
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
<script>
document.addEventListener("DOMContentLoaded", () => {

    const points = JSON.parse(String.raw`{!! json_encode($points) !!}`);

    if (!points.length) return;

    const map = L.map('tracking-map').setView([points[0].lat, points[0].lng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const coords = [];

    points.forEach(p => {
        coords.push([p.lat, p.lng]);
        L.marker([p.lat, p.lng])
            .bindPopup(`<strong>${p.fecha}</strong><br>${p.zona ?? ''}`)
            .addTo(map);
    });

    L.polyline(coords, { color: 'blue' }).addTo(map);

    map.fitBounds(coords, { padding: [40, 40] });

});
</script>
@endsection