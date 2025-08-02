@props(['datos' => [], 'editable' => false])

@php
    // Obtener coordenadas del trámite con valores por defecto
    $latitud = 19.4326; // CDMX por defecto
    $longitud = -99.1332;
    
    // Intentar obtener coordenadas de diferentes fuentes
    if (!empty($datos)) {
        if (isset($datos['coordenadas']) && is_object($datos['coordenadas'])) {
            // Si coordenadas es un objeto (relación cargada)
            if (isset($datos['coordenadas']->latitud) && isset($datos['coordenadas']->longitud)) {
                $latitud = floatval($datos['coordenadas']->latitud);
                $longitud = floatval($datos['coordenadas']->longitud);
            }
        } elseif (isset($datos['coordenadas']) && is_array($datos['coordenadas'])) {
            // Si coordenadas es un array
            if (isset($datos['coordenadas']['latitud']) && isset($datos['coordenadas']['longitud'])) {
                $latitud = floatval($datos['coordenadas']['latitud']);
                $longitud = floatval($datos['coordenadas']['longitud']);
            }
        } elseif (isset($datos['latitud']) && isset($datos['longitud'])) {
            // Si están directamente en el array de datos
            $latitud = floatval($datos['latitud']);
            $longitud = floatval($datos['longitud']);
        } elseif (isset($datos['coordenadas_latitud']) && isset($datos['coordenadas_longitud'])) {
            // Formato alternativo
            $latitud = floatval($datos['coordenadas_latitud']);
            $longitud = floatval($datos['coordenadas_longitud']);
        }
    }
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="domicilio" data-lat="{{ $latitud }}" data-lng="{{ $longitud }}">
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Domicilio</h3>
            <p class="text-sm text-gray-500">Dirección completa del solicitante</p>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Domicilio
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Código Postal
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-mail-bulk text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['codigo_postal'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed font-mono"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['estado'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Municipio/Delegación
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-city text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['municipio'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Colonia/Asentamiento
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-home text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['asentamiento'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['calle'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Exterior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['numero_exterior'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Interior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['numero_interior'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Referencias
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-info-circle text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['referencias'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    País
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-flag text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['pais'] ?? 'MÉXICO' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>
        </div>

        <!-- Coordenadas GPS -->
        <div class="mt-6">
            <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
                Coordenadas GPS
            </h4>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Latitud
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-pin text-gray-500"></i>
                        </div>
                        @if($editable)
                            <input type="number" 
                                   step="any"
                                   name="coordenadas_latitud"
                                   value="{{ $latitud }}"
                                   placeholder="Ej: 19.4326"
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] font-mono"
                                   min="-90" max="90">
                        @else
                            <input type="text"
                                   value="{{ $latitud }}"
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed font-mono"
                                   disabled>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Coordenada norte-sur (-90 a 90)</p>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Longitud
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-pin text-gray-500"></i>
                        </div>
                        @if($editable)
                            <input type="number" 
                                   step="any"
                                   name="coordenadas_longitud"
                                   value="{{ $longitud }}"
                                   placeholder="Ej: -99.1332"
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-300 rounded-lg shadow-sm focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] font-mono"
                                   min="-180" max="180">
                        @else
                            <input type="text"
                                   value="{{ $longitud }}"
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed font-mono"
                                   disabled>
                        @endif
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Coordenada este-oeste (-180 a 180)</p>
                </div>
            </div>

            @if($editable)
            <div class="mt-4 flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    <span class="text-sm text-blue-800 font-medium">Obtener coordenadas automáticamente</span>
                </div>
                <button type="button" 
                        id="obtenerCoordenadas"
                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-location-arrow mr-1"></i>
                    Ubicar
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Sección del Mapa -->
    @if($latitud != 19.4326 || $longitud != -99.1332)
    <div>
        <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
            🗺️ Mapa de Ubicación
        </h4>
        <!-- El mapa se creará dinámicamente aquí por el JavaScript -->
    </div>
    @endif

    <!-- Dirección Completa -->
    <div>
        <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
            Dirección Completa
        </h4>
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-map-marked-alt text-[#9d2449] text-lg mt-1"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 leading-relaxed">
                        {{ $datos['calle'] ?? '' }}
                        @if($datos['numero_exterior'] ?? '') #{{ $datos['numero_exterior'] }} @endif
                        @if($datos['numero_interior'] ?? '') Int. {{ $datos['numero_interior'] }} @endif
                        @if($datos['asentamiento'] ?? ''), {{ $datos['asentamiento'] }} @endif
                        @if($datos['codigo_postal'] ?? ''), C.P. {{ $datos['codigo_postal'] }} @endif
                        @if($datos['municipio'] ?? ''), {{ $datos['municipio'] }} @endif
                        @if($datos['estado'] ?? ''), {{ $datos['estado'] }} @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/tramites/handlers/mapa-simple.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const botonObtenerCoordenadas = document.getElementById('obtenerCoordenadas');
    const inputLatitud = document.querySelector('input[name="coordenadas_latitud"]');
    const inputLongitud = document.querySelector('input[name="coordenadas_longitud"]');
    
    if (botonObtenerCoordenadas && inputLatitud && inputLongitud) {
        botonObtenerCoordenadas.addEventListener('click', function() {
            // Cambiar texto del botón mientras se obtiene la ubicación
            const textoOriginal = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Obteniendo...';
            this.disabled = true;
            
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        // Éxito: establecer las coordenadas
                        const lat = position.coords.latitude.toFixed(6);
                        const lng = position.coords.longitude.toFixed(6);
                        
                        inputLatitud.value = lat;
                        inputLongitud.value = lng;
                        
                        // Restaurar botón
                        botonObtenerCoordenadas.innerHTML = '<i class="fas fa-check mr-1"></i>¡Ubicado!';
                        botonObtenerCoordenadas.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        botonObtenerCoordenadas.classList.add('bg-green-600', 'hover:bg-green-700');
                        
                        // Reset después de 3 segundos
                        setTimeout(() => {
                            botonObtenerCoordenadas.innerHTML = textoOriginal;
                            botonObtenerCoordenadas.classList.remove('bg-green-600', 'hover:bg-green-700');
                            botonObtenerCoordenadas.classList.add('bg-blue-600', 'hover:bg-blue-700');
                            botonObtenerCoordenadas.disabled = false;
                        }, 3000);
                        
                        // Mostrar notificación
                        if (typeof mostrarNotificacion === 'function') {
                            mostrarNotificacion('Ubicación obtenida correctamente', 'success');
                        }
                    },
                    function(error) {
                        // Error: mostrar mensaje según el tipo de error
                        let mensaje = 'No se pudo obtener la ubicación';
                        
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                mensaje = 'Permiso de ubicación denegado';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                mensaje = 'Ubicación no disponible';
                                break;
                            case error.TIMEOUT:
                                mensaje = 'Tiempo de espera agotado';
                                break;
                        }
                        
                        // Restaurar botón
                        botonObtenerCoordenadas.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Error';
                        botonObtenerCoordenadas.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        botonObtenerCoordenadas.classList.add('bg-red-600', 'hover:bg-red-700');
                        
                        // Reset después de 3 segundos
                        setTimeout(() => {
                            botonObtenerCoordenadas.innerHTML = textoOriginal;
                            botonObtenerCoordenadas.classList.remove('bg-red-600', 'hover:bg-red-700');
                            botonObtenerCoordenadas.classList.add('bg-blue-600', 'hover:bg-blue-700');
                            botonObtenerCoordenadas.disabled = false;
                        }, 3000);
                        
                        // Mostrar notificación
                        if (typeof mostrarNotificacion === 'function') {
                            mostrarNotificacion(mensaje, 'error');
                        } else {
                            alert(mensaje);
                        }
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 300000 // 5 minutos
                    }
                );
            } else {
                // Geolocalización no soportada
                botonObtenerCoordenadas.innerHTML = '<i class="fas fa-times mr-1"></i>No compatible';
                botonObtenerCoordenadas.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                botonObtenerCoordenadas.classList.add('bg-gray-600');
                
                setTimeout(() => {
                    botonObtenerCoordenadas.innerHTML = textoOriginal;
                    botonObtenerCoordenadas.classList.remove('bg-gray-600');
                    botonObtenerCoordenadas.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    botonObtenerCoordenadas.disabled = false;
                }, 3000);
                
                if (typeof mostrarNotificacion === 'function') {
                    mostrarNotificacion('Geolocalización no soportada en este navegador', 'error');
                } else {
                    alert('Geolocalización no soportada en este navegador');
                }
            }
        });
    }
});
</script>
@endpush 