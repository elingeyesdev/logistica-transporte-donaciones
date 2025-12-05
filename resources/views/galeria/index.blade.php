@extends('adminlte::page')

@section('title', 'Galería')

@section('content_header')
    <h1>Galería</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 text-center">
            <button class="btn btn-primary" id="filter-gratitude">Galería de Agradecimiento</button>
        {{--  <button class="btn btn-secondary" id="filter-animals">Galería de Animales</button> --}}
            @guest
                <a href="{{ route('solicitud.public.create') }}" class="btn btn-outline-primary ml-2">
                    Enviar nueva solicitud
                </a>
            @endguest
        </div>
    </div>

    <div id="gratitude-gallery" class="gallery-section">
        <div class="row">
            @forelse ($paquetes as $paquete)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        @php
                            $imageUrl = $paquete->imagen
                                ? route('paquete.imagen', $paquete->id_paquete)
                                : null;
                        @endphp
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" class="card-img-top" style="max-width: 100%; height:200px; object-fit: cover; " alt="Foto de entrega">
                        @else
                            <img src="{{ asset('images/default-placeholder.png') }}" class="card-img-top" alt="Imagen no disponible">
                        @endif

                        <div class="card-body">
                            <h5 class="">Donación entregada a la comunidad {{ $paquete->solicitud->destino->comunidad }} </h5>
                            <p class="card-text">Fecha de Entrega: {{ \Carbon\Carbon::parse($paquete->fecha_entrega)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No hay paquetes entregados disponibles.</p>
            @endforelse
        </div>
    </div>

    <div id="animals-gallery" class="gallery-section" style="display: none;">
        <div class="row" id="animals-container">
            <p class="text-muted">Cargando imágenes de animales...</p>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gratitudeGallery = document.getElementById('gratitude-gallery');
        const animalsGallery = document.getElementById('animals-gallery');
        const animalsContainer = document.getElementById('animals-container');

        document.getElementById('filter-gratitude').addEventListener('click', function() {
            gratitudeGallery.style.display = 'block';
            animalsGallery.style.display = 'none';
        });

        document.getElementById('filter-animals').addEventListener('click', function() {
            gratitudeGallery.style.display = 'none';
            animalsGallery.style.display = 'block';

            // Fetch images for animals gallery
            fetch('https://api.animales.com/imagenes') // Reemplaza con el endpoint real
                .then(response => response.json())
                .then(data => {
                    animalsContainer.innerHTML = '';
                    if (data && Array.isArray(data.imagenes)) {
                        data.imagenes.forEach(imagen => {
                            const col = document.createElement('div');
                            col.className = 'col-md-4 mb-4';

                            const card = document.createElement('div');
                            card.className = 'card';

                            const img = document.createElement('img');
                            img.src = imagen.url;
                            img.alt = imagen.descripcion || 'Imagen de animal';
                            img.className = 'card-img-top';

                            card.appendChild(img);
                            col.appendChild(card);
                            animalsContainer.appendChild(col);
                        });
                    } else {
                        animalsContainer.innerHTML = '<p class="text-muted">No hay imágenes disponibles.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las imágenes:', error);
                    animalsContainer.innerHTML = '<p class="text-danger">Error al cargar las imágenes.</p>';
                });
        });
    });
</script>
@endsection