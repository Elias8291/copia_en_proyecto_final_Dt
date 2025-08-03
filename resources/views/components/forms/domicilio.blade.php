@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }} data-seccion="domicilio">
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

    @if($editable)
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Domicilio
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <!-- Campo: Código Postal -->
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Código Postal <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-mail-bulk text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        name="codigo_postal"
                        value="{{ $datos['codigo_postal'] ?? old('codigo_postal') }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono"
                        placeholder="12345"
                        maxlength="5">
                </div>
            </div>

            <!-- Campo: Estado -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <select name="estado_id"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="">Seleccione estado</option>
                        <option value="1" {{ ($datos['estado_id'] ?? old('estado_id')) == '1' ? 'selected' : '' }}>Ciudad de México</option>
                        <option value="2" {{ ($datos['estado_id'] ?? old('estado_id')) == '2' ? 'selected' : '' }}>Estado de México</option>
                        <option value="3" {{ ($datos['estado_id'] ?? old('estado_id')) == '3' ? 'selected' : '' }}>Jalisco</option>
                        <option value="4" {{ ($datos['estado_id'] ?? old('estado_id')) == '4' ? 'selected' : '' }}>Nuevo León</option>
                        <option value="5" {{ ($datos['estado_id'] ?? old('estado_id')) == '5' ? 'selected' : '' }}>Veracruz</option>
                    </select>
                </div>
            </div>

            <!-- Campo: Municipio -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Municipio/Delegación <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-city text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="municipio"
                        value="{{ $datos['municipio'] ?? old('municipio') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese municipio o delegación">
                </div>
            </div>

            <!-- Campo: Asentamiento -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Asentamiento/Colonia <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-home text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="asentamiento"
                        value="{{ $datos['asentamiento'] ?? old('asentamiento') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese colonia o asentamiento">
                </div>
            </div>

            <!-- Campo: Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Calle <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="calle"
                        value="{{ $datos['calle'] ?? old('calle') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese nombre de la calle">
                </div>
            </div>

            <!-- Campo: Número Exterior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Exterior <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_exterior"
                        value="{{ $datos['numero_exterior'] ?? old('numero_exterior') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="123 o A-1">
                </div>
            </div>

            <!-- Campo: Número Interior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Interior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-door-open text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_interior"
                        value="{{ $datos['numero_interior'] ?? old('numero_interior') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Apto 5 o Local 2">
                </div>
            </div>
        </div>
    </div>
    @else
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Dirección Registrada
        </h4>
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-map-marked-alt text-[#9d2449] text-lg mt-1"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-800 leading-relaxed">
                        {{ $datos['calle'] ?? '' }}
                        @if(!empty($datos['numero_exterior'] ?? '')) #{{ $datos['numero_exterior'] }} @endif
                        @if(!empty($datos['numero_interior'] ?? '')) Int. {{ $datos['numero_interior'] }} @endif
                        @if(!empty($datos['asentamiento'] ?? '')) , {{ $datos['asentamiento'] }} @endif
                        @if(!empty($datos['codigo_postal'] ?? '')) , C.P. {{ $datos['codigo_postal'] }} @endif
                        @if(!empty($datos['municipio'] ?? '')) , {{ $datos['municipio'] }} @endif
                        @if(!empty($datos['estado'] ?? '')) , {{ $datos['estado'] }} @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="map" class="w-full h-64 rounded-lg border border-gray-200"></div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([19.4326, -99.1332], 13); // Coordenadas CDMX

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([19.4326, -99.1332]).addTo(map)
            .bindPopup('Ubicación de ejemplo')
            .openPopup();
    });
</script>
