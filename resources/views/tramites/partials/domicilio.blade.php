@props(['tipo' => 'inscripcion', 'proveedor' => null, 'datosSat' => [], 'editable' => true])

<div class="space-y-8" {{ $attributes }}>
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Domicilio
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label for="codigo_postal" class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Código Postal
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-mail-bulk text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="codigo_postal" name="codigo_postal"
                           value="{{ old('codigo_postal') }}"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm font-mono sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm"
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
            <option value="otro">Otro</option>
        </select>
        <input type="text" id="estado_otro" name="estado_otro" 
               class="mt-2 hidden block w-full pl-3 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all shadow-sm" 
               placeholder="Especifique otro estado">
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
            <option value="otro">Otro</option>
        </select>
        <input type="text" id="municipio_otro" name="municipio_otro" 
               class="mt-2 hidden block w-full pl-3 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all shadow-sm" 
               placeholder="Especifique otro municipio">
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
                    <select id="asentamiento" name="asentamiento" 
                            class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all appearance-none shadow-sm">
                        <option value="">Seleccione un asentamiento</option>
                        <!-- Opciones dinámicas por JS -->
                        <option value="otro">Otro</option>
                    </select>
                    <input type="text" id="asentamiento_otro" name="asentamiento_otro" class="mt-2 hidden block w-full pl-3 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all shadow-sm" placeholder="Especifique otro asentamiento">
                    <!-- Campo oculto para datos SAT -->
                    <input type="hidden" id="sat_colonia" value="">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
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
