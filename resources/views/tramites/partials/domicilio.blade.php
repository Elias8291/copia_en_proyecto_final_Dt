@props(['tipo' => 'inscripcion', 'proveedor' => null, 'datosSat' => [], 'editable' => true, 'tramite' => null])

@php
    // Si es una corrección y tenemos datos del trámite, obtener la dirección existente
    $direccion = null;
    if ($tramite && $tramite->direcciones) {
        $direccion = $tramite->direcciones->first();
    }
    
    // Valores para los campos
    $codigoPostal = old('codigo_postal', $direccion?->codigo_postal ?? $datosSat['cp'] ?? '');
    $estadoId = old('estado_id', $direccion?->estado_id ?? '');
    $municipio = old('municipio', $direccion?->municipio ?? '');
    $asentamiento = old('asentamiento', $direccion?->asentamiento ?? $datosSat['colonia'] ?? '');
    $calle = old('calle', $direccion?->calle ?? $datosSat['nombre_vialidad'] ?? '');
    $numeroExterior = old('numero_exterior', $direccion?->numero_exterior ?? $datosSat['numero_exterior'] ?? '');
    $numeroInterior = old('numero_interior', $direccion?->numero_interior ?? $datosSat['numero_interior'] ?? '');
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="domicilio">
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
                           value="{{ $codigoPostal }}"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm font-mono sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm {{ $errors->has('codigo_postal') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Ej: 01000"
                           pattern="[0-9]{5}"
                           maxlength="5"
                           aria-label="Código postal">
                    @error('codigo_postal')
                        <div class="mt-2 flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $message }}</span>
                        </div>
                    @enderror
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


        </div>

        <!-- Municipio y Asentamiento en la misma fila -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

            <!-- Asentamiento -->
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
                           value="{{ $calle }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ $errors->has('calle') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Ej: Av. Principal"
                           maxlength="100"
                           aria-label="Nombre de la calle">
                    @error('calle')
                        <div class="mt-2 flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $message }}</span>
                        </div>
                    @enderror
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
                           value="{{ $numeroExterior }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ $errors->has('numero_exterior') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Ej: 123 o S/N"
                           maxlength="10"
                           aria-label="Número exterior">
                    @error('numero_exterior')
                        <div class="mt-2 flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $message }}</span>
                        </div>
                    @enderror
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
                           value="{{ $numeroInterior }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 5A"
                           maxlength="10"
                           aria-label="Número interior">
                </div>
            </div>
            
            <!-- Entre Calles -->
            <div class="form-group md:col-span-2">
                <label for="entre_calles" class="block text-sm font-medium text-gray-700 mb-2">
                    Entre Calles
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-arrows-alt-h text-gray-500"></i>
                    </div>
                    <input type="text" id="entre_calles" name="entre_calles"
                           value="{{ old('entre_calles') }}"
                           data-validate="minLength:3|maxLength:200"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm {{ $errors->has('entre_calles') ? 'border-red-500 bg-red-50' : '' }}"
                           placeholder="Ej: Entre Calle Independencia y Calle Morelos"
                           maxlength="200"
                           aria-label="Entre calles">
                    @error('entre_calles')
                        <div class="mt-2 flex items-center text-red-600">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ $message }}</span>
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Hidden inputs for latitude and longitude -->
        <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud', $direccion?->coordenadas?->latitud ?? '') }}">
        <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud', $direccion?->coordenadas?->longitud ?? '') }}">

    </div>



</div>

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'domicilio', 'tramite' => $tramite, 'editable' => $editable])
@endif

@push('scripts')
<script src="{{ asset('js/tramites/handlers/codigo-postal-handler.js') }}"></script>
<script src="{{ asset('js/tramites/handlers/mapa-coordenadas.js') }}"></script>
@endpush
