@props(['tramite', 'direccion' => null, 'editable' => false])

@if($direccion)
        <div class="space-y-4 sm:space-y-6 lg:space-y-8">
            <div>
                <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-100 sm:text-base sm:mb-4 sm:pb-3">
                    Información de Domicilio
                </h4>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                    <div class="form-group field-container">
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                            Calle
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                                <i class="fas fa-road text-gray-500 text-xs sm:text-sm"></i>
                            </div>
                            <div class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm">
                                {{ $direccion->calle ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Número Exterior
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-hashtag text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->numero_exterior ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Número Interior
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-door-open text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->numero_interior ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Entre Calles
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-arrows-alt-h text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->entre_calles ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Colonia/Asentamiento
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-home text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->colonia_asentamiento ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Código Postal
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-mail-bulk text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                                {{ $direccion->codigo_postal ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Municipio
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-city text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->municipio ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Estado
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-map text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                                {{ $direccion->estado->nombre ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($direccion->coordenadas)
            <div>
                <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    Coordenadas Geográficas
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Latitud
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-crosshairs text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                                {{ $direccion->coordenadas->latitud ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                            Longitud
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-crosshairs text-gray-500"></i>
                            </div>
                            <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                                {{ $direccion->coordenadas->longitud ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div>
                <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                    🗺️ Mapa de Ubicación
                </h4>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Mapa -->
                    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg">
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Ubicación del domicilio</span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">Calles cercanas visibles</span>
                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                </div>
                            </div>
                        </div>
                        <div id="map" class="w-full h-80"></div>
                    </div>
                    
                    <!-- Contenedor de calles cercanas -->
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-blue-700">📍 Calles Cercanas</span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-blue-500">Actualizado</span>
                                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                        <div id="calles-cercanas" class="p-4 h-80 overflow-y-auto">
                            <div class="text-center text-gray-500 py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                </div>
                                <p class="text-sm">Cargando calles cercanas...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                {{ $direccion->calle ?? '' }}
                                @if($direccion->numero_exterior) #{{ $direccion->numero_exterior }} @endif
                                @if($direccion->numero_interior) Int. {{ $direccion->numero_interior }} @endif
                                @if($direccion->colonia_asentamiento), {{ $direccion->colonia_asentamiento }} @endif
                                @if($direccion->codigo_postal), C.P. {{ $direccion->codigo_postal }} @endif
                                @if($direccion->municipio), {{ $direccion->municipio }} @endif
                                @if($direccion->estado), {{ $direccion->estado->nombre ?? '' }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="{{ asset('js/modules/domicilio-map.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lat = {{ $direccion->coordenadas->latitud ?? 19.4326 }};
                const lng = {{ $direccion->coordenadas->longitud ?? -99.1332 }};
                
                // Función callback para Google Maps API
                window.initDomicilioMapCallback = async function() {
                    // Construir dirección completa más precisa
                    let direccionCompleta = '';
                    
                    @if($direccion->calle)
                        direccionCompleta += '{{ $direccion->calle }}';
                        @if($direccion->numero_exterior)
                            direccionCompleta += ' {{ $direccion->numero_exterior }}';
                        @endif
                        @if($direccion->numero_interior)
                            direccionCompleta += ' Int. {{ $direccion->numero_interior }}';
                        @endif
                        @if($direccion->colonia_asentamiento)
                            direccionCompleta += ', {{ $direccion->colonia_asentamiento }}';
                        @endif
                        @if($direccion->municipio)
                            direccionCompleta += ', {{ $direccion->municipio }}';
                        @endif
                        @if($direccion->estado)
                            direccionCompleta += ', {{ $direccion->estado->nombre }}';
                        @endif
                        @if($direccion->codigo_postal)
                            direccionCompleta += ' {{ $direccion->codigo_postal }}';
                        @endif
                    @else
                        // Si no hay calle, usar solo municipio y estado
                        @if($direccion->municipio)
                            direccionCompleta = '{{ $direccion->municipio }}';
                            @if($direccion->estado)
                                direccionCompleta += ', {{ $direccion->estado->nombre }}';
                            @endif
                        @endif
                    @endif
                    
                    console.log('Dirección para geocoding:', direccionCompleta);
                    await initDomicilioMap('map', lat, lng, direccionCompleta);
                };
                
                // Cargar Google Maps API
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initDomicilioMapCallback&loading=async`;
                script.async = true;
                script.defer = true;
                
                document.head.appendChild(script);
            });
        </script>
        @endpush
@else
    <div class="text-center py-12">
        <div class="flex flex-col items-center justify-center space-y-4">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-gray-400 text-2xl"></i>
            </div>
            <div class="text-gray-500">
                <p class="font-medium text-sm">No hay información de domicilio</p>
                <p class="text-xs mt-1">La dirección no ha sido registrada para este trámite.</p>
            </div>
        </div>
    </div>
@endif