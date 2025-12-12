@extends('adminlte::page')

@section('title', 'Galería')

@section('content_header')
    <h1>Galería</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 justify-content-between text-center">
            <button class="btn btn-primary mr-5" id="btnGratitude">Galería de Agradecimiento</button>
            <button class="btn btn-info mr-5" id="btnAnimals">Galería de Animales</button> 
            @guest
                <a href="{{ route('solicitud.public.create') }}" class="btn btn-outline-primary">
                    Enviar nueva solicitud
                </a>
            @endguest
        </div>
    </div>

    <div id="gratitude-gallery" class="gallery-section">
        <div class="row">
            @forelse ($paquetes as $paquete)
                <div class="col-md-3 mb-4">
                    <div class="card" style="border-radius: 10px;">
                        @php
                            $imageUrl = $paquete->imagen
                                ? route('paquete.imagen', $paquete->id_paquete)
                                : null;
                        @endphp
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" class="card-img-top" style="max-width: 100%; height:200px; object-fit: cover;  border-radius:10px 10px 0px 0px; " alt="Foto de entrega">
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
        const animalsGallery   = document.getElementById('animals-gallery');
        const animalsContainer = document.getElementById('animals-container');

        const btnGratitude = document.getElementById('btnGratitude');
        const btnAnimals   = document.getElementById('btnAnimals');

        // La URL base de animales viene desde el backend (.env)
        const ANIMALES = @json(env('ANIMALES_API_URL', 'http://localhost:8001'));
        const ANIMALS_API_URL = ANIMALES + '/api/releases'; 
        function buildImageUrl(relativePath) {
            if (!relativePath) return null;
            if (/^https?:\/\//i.test(relativePath)) {
                return relativePath;
            }
            return ANIMALES + '/storage/' + relativePath.replace(/^\/+/, '');
        }
        let animalsLoaded = false;
        function formatReleaseDate(isoString) {
            if (!isoString) return 'Fecha no disponible';
            const date = new Date(isoString);
            if (isNaN(date.getTime())) {
                return 'Fecha no disponible';
            }
            const day   = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year  = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        btnGratitude.addEventListener('click', function() {
            gratitudeGallery.style.display = 'block';
            animalsGallery.style.display   = 'none';

            btnGratitude.classList.add('btn-primary');
            btnGratitude.classList.remove('btn-secondary');

            btnAnimals.classList.add('btn-secondary');
            btnAnimals.classList.remove('btn-primary');
        });

         btnAnimals.addEventListener('click', function() {
            gratitudeGallery.style.display = 'none';
            animalsGallery.style.display   = 'block';

            btnAnimals.classList.add('btn-primary');
            btnAnimals.classList.remove('btn-secondary');

            btnGratitude.classList.add('btn-secondary');
            btnGratitude.classList.remove('btn-primary');

            if (!animalsLoaded) {
                loadAnimalsGallery();
            }
        });


       function loadAnimalsGallery() {
            animalsContainer.innerHTML = '<p class="text-muted mx-3 my-2">Cargando imágenes de animales...</p>';

            fetch(ANIMALS_API_URL)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error HTTP ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    animalsContainer.innerHTML = '';

                    const releases = Array.isArray(data) ? data : [];

                    if (!releases.length) {
                        animalsContainer.innerHTML = '<p class="text-muted mx-3 my-2">No hay imágenes de animales disponibles.</p>';
                        return;
                    }

                    releases.forEach(item => {
                        const animalFile   = item.animal_file || {};
                        const animal       = animalFile.animal || {};
                        const species      = animalFile.species || {};

                        const especieNombre = species.nombre || 'Especie desconocida';
                        const animalNombre  = animal.nombre || 'Sin nombre';

                        const titulo = `${especieNombre} - ${animalNombre}`;
                        const detalle = item.detalle || 'Sin detalle disponible';

                        const imagenRelease = buildImageUrl(item.imagen_url);
                        const imagenAnimal  = buildImageUrl(animalFile.imagen_url);
                        const imagenReporte = buildImageUrl(animal.report?.imagen_url);

                        const imagenFinal = imagenRelease || imagenAnimal || imagenReporte || `{{ asset('images/default-placeholder.png') }}`;

                        const fechaLiberacion = formatReleaseDate(item.created_at);

                        const col  = document.createElement('div');
                        col.className = 'col-md-3 mb-4';

                        const card = document.createElement('div');
                        card.className = 'card';

                        const img = document.createElement('img');
                        img.src = imagenFinal;
                        img.alt = titulo;
                        img.className = 'card-img-top';
                        img.style.maxWidth = '100%';
                        img.style.height   = '200px';
                        img.style.objectFit = 'cover';

                        const body = document.createElement('div');
                        body.className = 'card-body';

                        const h5 = document.createElement('h5');
                        h5.className = 'card-title';
                        h5.textContent = titulo;

                        const pDetalle = document.createElement('p');
                        pDetalle.className = 'card-text mb-1';
                        pDetalle.textContent = detalle;

                        const pFecha = document.createElement('p');
                        pFecha.className = 'card-text';
                        pFecha.innerHTML = `<small class="text-muted">Fecha de liberación: ${fechaLiberacion}</small>`;

                        body.appendChild(h5);
                        body.appendChild(pDetalle);
                        body.appendChild(pFecha);

                        card.appendChild(img);
                        card.appendChild(body);
                        col.appendChild(card);
                        animalsContainer.appendChild(col);
                    });

                    animalsLoaded = true;
                })
                .catch(error => {
                    console.error('Error al cargar las imágenes de animales:', error);
                    animalsContainer.innerHTML = '<p class="text-danger mx-3 my-2">Error al cargar las imágenes de animales.</p>';
                });
        }
    });
</script>
@endsection
