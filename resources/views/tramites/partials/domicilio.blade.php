@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-map-marker-alt text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Domicilio Fiscal</h2>
                <p class="text-sm text-gray-500 mt-1">Información del domicilio registrado ante el SAT</p>
            </div>
        </div>
    </div>

    <!-- Modo de captura -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 p-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-sm">
                <i class="fas fa-cog text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-700 mb-3">Seleccione el método para capturar la dirección:</p>
                <div class="flex space-x-6">
                    <label class="flex items-center group cursor-pointer">
                        <input type="radio" name="modo_captura" value="codigo_postal" id="modo_codigo_postal" 
                               class="mr-2 text-[#9d2449] focus:ring-[#9d2449] focus:ring-2" checked>
                        <span class="text-sm text-gray-700 group-hover:text-[#9d2449] transition-colors">Por Código Postal (Automático)</span>
                    </label>
                    <label class="flex items-center group cursor-pointer">
                        <input type="radio" name="modo_captura" value="manual" id="modo_manual" 
                               class="mr-2 text-[#9d2449] focus:ring-[#9d2449] focus:ring-2">
                        <span class="text-sm text-gray-700 group-hover:text-[#9d2449] transition-colors">Captura Manual</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Código Postal y Ubicación -->
    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Código Postal -->
            <div class="form-group">
                <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-2">
                    Código Postal
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-map-pin text-gray-500"></i>
                    </div>
                    <input type="text" id="codigo_postal" name="codigo_postal"
                           value="{{ old('codigo_postal') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 01000"
                           pattern="[0-9]{5}"
                           maxlength="5"
                           aria-label="Código postal"
                           required>
                </div>
                <p class="mt-1 text-sm text-gray-500" id="cp-help-text">5 dígitos - Se cargarán los datos automáticamente</p>
            </div>

            <!-- País (oculto, siempre México) -->
            <input type="hidden" id="pais" name="pais" value="MÉXICO">
            <input type="hidden" id="pais_id" name="pais_id" value="1">

            <!-- Estado -->
            <div class="form-group">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <select id="estado" name="estado_id" 
                            class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all appearance-none shadow-sm">
                        <option value="">Seleccione un estado</option>
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500" id="estado-help-text">Se carga automáticamente con código postal</p>
            </div>

            <!-- Municipio -->
            <div class="form-group">
                <label for="municipio" class="block text-sm font-medium text-gray-700 mb-2">
                    Municipio/Delegación
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-city text-gray-500"></i>
                    </div>
                    <select id="municipio" name="municipio" 
                            class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all appearance-none shadow-sm">
                        <option value="">Seleccione un municipio</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                <input type="hidden" id="municipio_id" name="municipio_id">
                <p class="mt-1 text-sm text-gray-500" id="municipio-help-text">Se carga automáticamente con código postal</p>
            </div>
        </div>

        <!-- Asentamiento -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="asentamiento" class="block text-sm font-medium text-gray-700 mb-2">
                    Asentamiento/Colonia
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-home text-gray-500"></i>
                    </div>
                    <input type="text" id="asentamiento" name="asentamiento" 
                           value="{{ old('asentamiento') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ingrese el asentamiento/colonia"
                           aria-label="Asentamiento o colonia"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="tipo_asentamiento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Asentamiento</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-map-signs text-gray-500"></i>
                    </div>
                    <input type="text" id="tipo_asentamiento" name="tipo_asentamiento" 
                           value="{{ old('tipo_asentamiento') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: Colonia, Fraccionamiento"
                           aria-label="Tipo de asentamiento">
                </div>
            </div>
        </div>

        <!-- Dirección -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Calle -->
            <div class="form-group md:col-span-2">
                <label for="calle" class="block text-sm font-medium text-gray-700 mb-2">
                    Calle
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text" id="calle" name="calle"
                           value="{{ old('calle') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: Av. Principal"
                           maxlength="100"
                           aria-label="Nombre de la calle"
                           required>
                </div>
            </div>
            
            <!-- Número Exterior -->
            <div class="form-group">
                <label for="numero_exterior" class="block text-sm font-medium text-gray-700 mb-2">
                    Número Exterior
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text" id="numero_exterior" name="numero_exterior"
                           value="{{ old('numero_exterior') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 123 o S/N"
                           maxlength="10"
                           aria-label="Número exterior"
                           required>
                </div>
            </div>
            
            <!-- Número Interior -->
            <div class="form-group">
                <label for="numero_interior" class="block text-sm font-medium text-gray-700 mb-2">Número Interior</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-door-open text-gray-500"></i>
                    </div>
                    <input type="text" id="numero_interior" name="numero_interior"
                           value="{{ old('numero_interior') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 5A"
                           maxlength="10"
                           aria-label="Número interior">
                </div>
            </div>
            
            <!-- Entre Calles -->
            <div class="form-group md:col-span-2">
                <label for="entre_calles" class="block text-sm font-medium text-gray-700 mb-2">Entre Calles</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-arrows-alt-h text-gray-500"></i>
                    </div>
                    <input type="text" id="entre_calles" name="entre_calles"
                           value="{{ old('entre_calles') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: Entre Calle Independencia y Calle Morelos"
                           maxlength="200"
                           aria-label="Entre calles">
                </div>
            </div>
        </div>

        <!-- Hidden inputs for latitude and longitude -->
        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">

    </div>

</div>

@push('scripts')
<script src="{{ asset('js/domicilio.js') }}"></script>
@endpush