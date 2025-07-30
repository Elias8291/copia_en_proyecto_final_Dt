@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Header Principal -->
            <div class="w-full max-w-7xl mx-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200/70 p-8 -mt-4 mb-8">
                <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                    <div class="p-6 border-b border-gray-200/70">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-800">Cotejo Domiciliario</h1>
                                    <p class="text-base text-gray-500 mt-1">Trámite #{{ $tramite->id }} • 
                                        {{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'Proveedor N/A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    En Desarrollo
                                </span>
                                <a href="{{ route('revision.seleccion-tipo', $tramite->id) }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                    </svg>
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Panel de Información -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200/50 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Información del Domicilio</h2>
                        
                        <!-- Datos del Proveedor -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Datos del Proveedor</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Razón Social:</span>
                                        <span class="font-medium">{{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'N/A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">RFC:</span>
                                        <span class="font-medium">{{ $tramite->datosGenerales->rfc ?? ($tramite->proveedor->rfc ?? 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Dirección -->
                            @if($tramite->direccion)
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-blue-900 mb-2">Dirección Fiscal</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Calle:</span>
                                        <span class="font-medium">{{ $tramite->direccion->calle ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Número:</span>
                                        <span class="font-medium">{{ $tramite->direccion->numero_exterior ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Colonia:</span>
                                        <span class="font-medium">{{ $tramite->direccion->asentamiento ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">CP:</span>
                                        <span class="font-medium">{{ $tramite->direccion->codigo_postal ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Municipio:</span>
                                        <span class="font-medium">{{ $tramite->direccion->municipio ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Estado:</span>
                                        <span class="font-medium">{{ $tramite->direccion->estado ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Coordenadas -->
                            @if($tramite->direccion && $tramite->direccion->coordenadas)
                            <div class="bg-green-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-green-900 mb-2">Coordenadas GPS</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-green-700">Latitud:</span>
                                        <span class="font-medium" id="latitud-display">{{ $tramite->direccion->coordenadas->latitud ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700">Longitud:</span>
                                        <span class="font-medium" id="longitud-display">{{ $tramite->direccion->coordenadas->longitud ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Estado del Cotejo -->
                            <div class="bg-purple-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-purple-900 mb-2">Estado del Cotejo</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                                        <span class="text-purple-700">Pendiente de programación</span>
                                    </div>
                                    <p class="text-xs text-purple-600 mt-2">
                                        Esta funcionalidad estará disponible próximamente para agendar visitas domiciliarias.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Ubicación del Domicilio Fiscal</h2>
                            <p class="text-sm text-gray-600 mt-1">Visualiza la ubicación exacta del domicilio fiscal del proveedor</p>
                        </div>
                        
                        <div class="p-6">
                            @if($tramite->direccion && $tramite->direccion->coordenadas)
                                <div id="mapa-cotejo-domiciliario" class="w-full h-96 rounded-lg border border-gray-200"></div>
                                
                                <!-- Controles del Mapa -->
                                <div class="mt-4 flex flex-wrap gap-4">
                                    <button id="btn-mi-ubicacion" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Mi Ubicación
                                    </button>
                                    
                                    <button id="btn-centrar-domicilio" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Centrar en Domicilio
                                    </button>
                                    
                                    <button id="btn-ruta" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        Calcular Ruta
                                    </button>
                                </div>

                                <!-- Información de Distancia -->
                                <div id="info-distancia" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                                    <h3 class="text-sm font-medium text-blue-900 mb-2">Información de Distancia</h3>
                                    <div class="text-sm text-blue-700">
                                        <p>Distancia al domicilio fiscal: <span id="distancia-texto" class="font-medium">Calculando...</span></p>
                                        <p>Tiempo estimado de viaje: <span id="tiempo-viaje" class="font-medium">Calculando...</span></p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-96 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-700 mb-2">Sin coordenadas disponibles</h3>
                                        <p class="text-sm text-gray-500">No se encontraron coordenadas GPS para este domicilio.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tramite->direccion && $tramite->direccion->coordenadas)
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Coordenadas del domicilio fiscal
            const domicilioLat = {{ $tramite->direccion->coordenadas->latitud }};
            const domicilioLng = {{ $tramite->direccion->coordenadas->longitud }};
            
            // Inicializar mapa
            const map = L.map('mapa-cotejo-domiciliario').setView([domicilioLat, domicilioLng], 15);
            
            // Agregar capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Marcador del domicilio fiscal
            const domicilioMarker = L.marker([domicilioLat, domicilioLng], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #dc2626; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);
            
            // Popup del domicilio
            domicilioMarker.bindPopup(`
                <div class="text-center">
                    <h3 class="font-bold text-gray-900">Domicilio Fiscal</h3>
                    <p class="text-sm text-gray-600">{{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'Proveedor') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $tramite->direccion->calle ?? '' }} {{ $tramite->direccion->numero_exterior ?? '' }}</p>
                </div>
            `);
            
            let miUbicacionMarker = null;
            let rutaPolyline = null;
            
            // Botón Mi Ubicación
            document.getElementById('btn-mi-ubicacion').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Remover marcador anterior si existe
                        if (miUbicacionMarker) {
                            map.removeLayer(miUbicacionMarker);
                        }
                        
                        // Agregar marcador de mi ubicación
                        miUbicacionMarker = L.marker([lat, lng], {
                            icon: L.divIcon({
                                className: 'custom-div-icon',
                                html: '<div style="background-color: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            })
                        }).addTo(map);
                        
                        miUbicacionMarker.bindPopup(`
                            <div class="text-center">
                                <h3 class="font-bold text-gray-900">Mi Ubicación</h3>
                                <p class="text-sm text-gray-600">Posición actual</p>
                            </div>
                        `);
                        
                        // Calcular distancia
                        const distancia = calcularDistancia(lat, lng, domicilioLat, domicilioLng);
                        mostrarInformacionDistancia(distancia);
                        
                        // Ajustar vista para mostrar ambos marcadores
                        const bounds = L.latLngBounds([[lat, lng], [domicilioLat, domicilioLng]]);
                        map.fitBounds(bounds, { padding: [20, 20] });
                        
                    }, function(error) {
                        alert('Error al obtener tu ubicación: ' + error.message);
                    });
                } else {
                    alert('Tu navegador no soporta geolocalización');
                }
            });
            
            // Botón Centrar en Domicilio
            document.getElementById('btn-centrar-domicilio').addEventListener('click', function() {
                map.setView([domicilioLat, domicilioLng], 15);
            });
            
            // Botón Calcular Ruta
            document.getElementById('btn-ruta').addEventListener('click', function() {
                if (miUbicacionMarker) {
                    const miLat = miUbicacionMarker.getLatLng().lat;
                    const miLng = miUbicacionMarker.getLatLng().lng;
                    
                    // Remover ruta anterior si existe
                    if (rutaPolyline) {
                        map.removeLayer(rutaPolyline);
                    }
                    
                    // Crear línea directa entre puntos
                    rutaPolyline = L.polyline([[miLat, miLng], [domicilioLat, domicilioLng]], {
                        color: '#3b82f6',
                        weight: 4,
                        opacity: 0.7
                    }).addTo(map);
                    
                    // Ajustar vista para mostrar la ruta completa
                    const bounds = L.latLngBounds([[miLat, miLng], [domicilioLat, domicilioLng]]);
                    map.fitBounds(bounds, { padding: [20, 20] });
                } else {
                    alert('Primero obtén tu ubicación usando el botón "Mi Ubicación"');
                }
            });
            
            // Función para calcular distancia usando fórmula de Haversine
            function calcularDistancia(lat1, lon1, lat2, lon2) {
                const R = 6371; // Radio de la Tierra en km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                         Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                         Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }
            
            // Función para mostrar información de distancia
            function mostrarInformacionDistancia(distancia) {
                const infoDistancia = document.getElementById('info-distancia');
                const distanciaTexto = document.getElementById('distancia-texto');
                const tiempoViaje = document.getElementById('tiempo-viaje');
                
                distanciaTexto.textContent = distancia.toFixed(2) + ' km';
                
                // Estimación de tiempo (asumiendo 30 km/h en ciudad)
                const tiempoEstimado = (distancia / 30) * 60; // en minutos
                tiempoViaje.textContent = Math.round(tiempoEstimado) + ' minutos';
                
                infoDistancia.classList.remove('hidden');
            }
        });
    </script>
    @endif
@endsection 
 
 
 

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Header Principal -->
            <div class="w-full max-w-7xl mx-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200/70 p-8 -mt-4 mb-8">
                <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                    <div class="p-6 border-b border-gray-200/70">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-800">Cotejo Domiciliario</h1>
                                    <p class="text-base text-gray-500 mt-1">Trámite #{{ $tramite->id }} • 
                                        {{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'Proveedor N/A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    En Desarrollo
                                </span>
                                <a href="{{ route('revision.seleccion-tipo', $tramite->id) }}"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                    </svg>
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Panel de Información -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200/50 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Información del Domicilio</h2>
                        
                        <!-- Datos del Proveedor -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Datos del Proveedor</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Razón Social:</span>
                                        <span class="font-medium">{{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'N/A') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">RFC:</span>
                                        <span class="font-medium">{{ $tramite->datosGenerales->rfc ?? ($tramite->proveedor->rfc ?? 'N/A') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Dirección -->
                            @if($tramite->direccion)
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-blue-900 mb-2">Dirección Fiscal</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Calle:</span>
                                        <span class="font-medium">{{ $tramite->direccion->calle ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Número:</span>
                                        <span class="font-medium">{{ $tramite->direccion->numero_exterior ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Colonia:</span>
                                        <span class="font-medium">{{ $tramite->direccion->asentamiento ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">CP:</span>
                                        <span class="font-medium">{{ $tramite->direccion->codigo_postal ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Municipio:</span>
                                        <span class="font-medium">{{ $tramite->direccion->municipio ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-700">Estado:</span>
                                        <span class="font-medium">{{ $tramite->direccion->estado ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Coordenadas -->
                            @if($tramite->direccion && $tramite->direccion->coordenadas)
                            <div class="bg-green-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-green-900 mb-2">Coordenadas GPS</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-green-700">Latitud:</span>
                                        <span class="font-medium" id="latitud-display">{{ $tramite->direccion->coordenadas->latitud ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-green-700">Longitud:</span>
                                        <span class="font-medium" id="longitud-display">{{ $tramite->direccion->coordenadas->longitud ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Estado del Cotejo -->
                            <div class="bg-purple-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-purple-900 mb-2">Estado del Cotejo</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                                        <span class="text-purple-700">Pendiente de programación</span>
                                    </div>
                                    <p class="text-xs text-purple-600 mt-2">
                                        Esta funcionalidad estará disponible próximamente para agendar visitas domiciliarias.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mapa -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200/50 overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-bold text-gray-900">Ubicación del Domicilio Fiscal</h2>
                            <p class="text-sm text-gray-600 mt-1">Visualiza la ubicación exacta del domicilio fiscal del proveedor</p>
                        </div>
                        
                        <div class="p-6">
                            @if($tramite->direccion && $tramite->direccion->coordenadas)
                                <div id="mapa-cotejo-domiciliario" class="w-full h-96 rounded-lg border border-gray-200"></div>
                                
                                <!-- Controles del Mapa -->
                                <div class="mt-4 flex flex-wrap gap-4">
                                    <button id="btn-mi-ubicacion" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Mi Ubicación
                                    </button>
                                    
                                    <button id="btn-centrar-domicilio" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Centrar en Domicilio
                                    </button>
                                    
                                    <button id="btn-ruta" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        Calcular Ruta
                                    </button>
                                </div>

                                <!-- Información de Distancia -->
                                <div id="info-distancia" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                                    <h3 class="text-sm font-medium text-blue-900 mb-2">Información de Distancia</h3>
                                    <div class="text-sm text-blue-700">
                                        <p>Distancia al domicilio fiscal: <span id="distancia-texto" class="font-medium">Calculando...</span></p>
                                        <p>Tiempo estimado de viaje: <span id="tiempo-viaje" class="font-medium">Calculando...</span></p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-96 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-700 mb-2">Sin coordenadas disponibles</h3>
                                        <p class="text-sm text-gray-500">No se encontraron coordenadas GPS para este domicilio.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tramite->direccion && $tramite->direccion->coordenadas)
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Coordenadas del domicilio fiscal
            const domicilioLat = {{ $tramite->direccion->coordenadas->latitud }};
            const domicilioLng = {{ $tramite->direccion->coordenadas->longitud }};
            
            // Inicializar mapa
            const map = L.map('mapa-cotejo-domiciliario').setView([domicilioLat, domicilioLng], 15);
            
            // Agregar capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Marcador del domicilio fiscal
            const domicilioMarker = L.marker([domicilioLat, domicilioLng], {
                icon: L.divIcon({
                    className: 'custom-div-icon',
                    html: '<div style="background-color: #dc2626; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(map);
            
            // Popup del domicilio
            domicilioMarker.bindPopup(`
                <div class="text-center">
                    <h3 class="font-bold text-gray-900">Domicilio Fiscal</h3>
                    <p class="text-sm text-gray-600">{{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'Proveedor') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $tramite->direccion->calle ?? '' }} {{ $tramite->direccion->numero_exterior ?? '' }}</p>
                </div>
            `);
            
            let miUbicacionMarker = null;
            let rutaPolyline = null;
            
            // Botón Mi Ubicación
            document.getElementById('btn-mi-ubicacion').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        // Remover marcador anterior si existe
                        if (miUbicacionMarker) {
                            map.removeLayer(miUbicacionMarker);
                        }
                        
                        // Agregar marcador de mi ubicación
                        miUbicacionMarker = L.marker([lat, lng], {
                            icon: L.divIcon({
                                className: 'custom-div-icon',
                                html: '<div style="background-color: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                                iconSize: [20, 20],
                                iconAnchor: [10, 10]
                            })
                        }).addTo(map);
                        
                        miUbicacionMarker.bindPopup(`
                            <div class="text-center">
                                <h3 class="font-bold text-gray-900">Mi Ubicación</h3>
                                <p class="text-sm text-gray-600">Posición actual</p>
                            </div>
                        `);
                        
                        // Calcular distancia
                        const distancia = calcularDistancia(lat, lng, domicilioLat, domicilioLng);
                        mostrarInformacionDistancia(distancia);
                        
                        // Ajustar vista para mostrar ambos marcadores
                        const bounds = L.latLngBounds([[lat, lng], [domicilioLat, domicilioLng]]);
                        map.fitBounds(bounds, { padding: [20, 20] });
                        
                    }, function(error) {
                        alert('Error al obtener tu ubicación: ' + error.message);
                    });
                } else {
                    alert('Tu navegador no soporta geolocalización');
                }
            });
            
            // Botón Centrar en Domicilio
            document.getElementById('btn-centrar-domicilio').addEventListener('click', function() {
                map.setView([domicilioLat, domicilioLng], 15);
            });
            
            // Botón Calcular Ruta
            document.getElementById('btn-ruta').addEventListener('click', function() {
                if (miUbicacionMarker) {
                    const miLat = miUbicacionMarker.getLatLng().lat;
                    const miLng = miUbicacionMarker.getLatLng().lng;
                    
                    // Remover ruta anterior si existe
                    if (rutaPolyline) {
                        map.removeLayer(rutaPolyline);
                    }
                    
                    // Crear línea directa entre puntos
                    rutaPolyline = L.polyline([[miLat, miLng], [domicilioLat, domicilioLng]], {
                        color: '#3b82f6',
                        weight: 4,
                        opacity: 0.7
                    }).addTo(map);
                    
                    // Ajustar vista para mostrar la ruta completa
                    const bounds = L.latLngBounds([[miLat, miLng], [domicilioLat, domicilioLng]]);
                    map.fitBounds(bounds, { padding: [20, 20] });
                } else {
                    alert('Primero obtén tu ubicación usando el botón "Mi Ubicación"');
                }
            });
            
            // Función para calcular distancia usando fórmula de Haversine
            function calcularDistancia(lat1, lon1, lat2, lon2) {
                const R = 6371; // Radio de la Tierra en km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                         Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                         Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                return R * c;
            }
            
            // Función para mostrar información de distancia
            function mostrarInformacionDistancia(distancia) {
                const infoDistancia = document.getElementById('info-distancia');
                const distanciaTexto = document.getElementById('distancia-texto');
                const tiempoViaje = document.getElementById('tiempo-viaje');
                
                distanciaTexto.textContent = distancia.toFixed(2) + ' km';
                
                // Estimación de tiempo (asumiendo 30 km/h en ciudad)
                const tiempoEstimado = (distancia / 30) * 60; // en minutos
                tiempoViaje.textContent = Math.round(tiempoEstimado) + ' minutos';
                
                infoDistancia.classList.remove('hidden');
            }
        });
    </script>
    @endif
@endsection 
 
 
 