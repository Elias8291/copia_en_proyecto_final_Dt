@props(['datos' => [], 'editable' => false, 'datosConstancia' => null])

@php
    if ($datosConstancia instanceof \App\ViewModels\TramiteViewModel) {
        $datosFinales = $datosConstancia->getDatosDomicilioForm($datos);
    } elseif ($datosConstancia instanceof \App\ViewModels\FormDataViewModel) {
        // Para FormDataViewModel (datos de revisión) - usar getDatosDomicilioForm para incluir coordenadas
        $datosFinales = $datosConstancia->getDatosDomicilioForm();
    } else {
        $datosFinales = $datosConstancia ? [
            'codigo_postal' => $datosConstancia['domicilio']['codigo_postal'] ?? ($datos['codigo_postal'] ?? ''),
            'estado' => $datosConstancia['domicilio']['entidad_federativa'] ?? ($datos['estado'] ?? ''),
            'municipio' => $datosConstancia['domicilio']['municipio'] ?? ($datos['municipio'] ?? ''),
            'asentamiento' => $datosConstancia['domicilio']['colonia'] ?? ($datos['asentamiento'] ?? ''),
            'calle' => $datosConstancia['domicilio']['calle'] ?? ($datos['calle'] ?? ''),
            'numero_exterior' => $datosConstancia['domicilio']['numero_exterior'] ?? ($datos['numero_exterior'] ?? ''),
            'numero_interior' => $datosConstancia['domicilio']['numero_interior'] ?? ($datos['numero_interior'] ?? ''),
            'latitud' => $datosConstancia['domicilio']['latitud'] ?? ($datos['latitud'] ?? ''),
            'longitud' => $datosConstancia['domicilio']['longitud'] ?? ($datos['longitud'] ?? ''),
            // Los campos entre_calle y y_calle SOLO vienen de los datos del formulario, no de la constancia
            'entre_calle' => !empty($datos['entre_calle']) ? $datos['entre_calle'] : '',
            'y_calle' => !empty($datos['y_calle']) ? $datos['y_calle'] : '',
        ] : $datos;
    }
    
    // Asegurar que entre_calle y y_calle siempre vengan de los datos del formulario
    $datosFinales['entre_calle'] = !empty($datos['entre_calle']) ? $datos['entre_calle'] : ($datosFinales['entre_calle'] ?? '');
    $datosFinales['y_calle'] = !empty($datos['y_calle']) ? $datos['y_calle'] : ($datosFinales['y_calle'] ?? '');
    
    // Asegurar que las coordenadas estén disponibles
    $latitud = $datosFinales['latitud'] ?? $datos['latitud'] ?? null;
    $longitud = $datosFinales['longitud'] ?? $datos['longitud'] ?? null;
@endphp

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
                        id="codigo_postal"
                        value="{{ old('codigo_postal', $datosFinales['codigo_postal'] ?? '') }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono {{ $errors->has('codigo_postal') ? 'border-red-500' : '' }}"
                        placeholder="12345"
                        maxlength="5">
                    <div id="loading-cp" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-spinner fa-spin"></i> Buscando...
                        </span>
                    </div>
                </div>
                @error('codigo_postal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                    <select name="estado_id" id="estado_id"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('estado_id') ? 'border-red-500' : '' }}">
                        <option value="">Cargando estados...</option>
                    </select>
                    <input type="hidden" name="estado_id_old" value="{{ old('estado_id', $datosFinales['estado_id'] ?? '') }}">
                    <div id="loading-estados" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-spinner fa-spin text-gray-400"></i>
                    </div>
                </div>
                @error('estado_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                        id="municipio"
                        value="{{ old('municipio', $datosFinales['municipio'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('municipio') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese municipio o delegación">
                </div>
                @error('municipio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                        id="asentamiento"
                        value="{{ old('asentamiento', $datosFinales['asentamiento'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('asentamiento') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese colonia o asentamiento">
                </div>
                @error('asentamiento')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Calle       -->
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
                        id="calle"
                        value="{{ old('calle', $datosFinales['calle'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('calle') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese nombre de la calle">
                </div>
                @error('calle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Entre Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Entre Calle <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="entre_calle"
                        id="entre_calle"
                        value="{{ old('entre_calle') ?: ($datosFinales['entre_calle'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('entre_calle') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese primera calle de referencia">
                </div>
                @error('entre_calle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Y Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Y Calle <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="y_calle"
                        id="y_calle"
                        value="{{ old('y_calle') ?: ($datosFinales['y_calle'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('y_calle') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese segunda calle de referencia">
                </div>
                @error('y_calle')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                        id="numero_exterior"
                        value="{{ old('numero_exterior', $datosFinales['numero_exterior'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('numero_exterior') ? 'border-red-500' : '' }}"
                        placeholder="123 o A-1">
                </div>
                @error('numero_exterior')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                        id="numero_interior"
                        value="{{ old('numero_interior', $datosFinales['numero_interior'] ?? '') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('numero_interior') ? 'border-red-500' : '' }}"
                        placeholder="Apto 5 o Local 2">
                </div>
                @error('numero_interior')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Latitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Latitud <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="number"
                        name="latitud"
                        id="latitud-manual"
                        value="{{ old('latitud', $latitud ?? '') }}"
                        step="0.000001"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('latitud') ? 'border-red-500' : '' }}"
                        placeholder="19.4326">
                </div>
                @error('latitud')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo: Longitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Longitud <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="number"
                        name="longitud"
                        id="longitud-manual"
                        value="{{ old('longitud', $longitud ?? '') }}"
                        step="0.000001"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('longitud') ? 'border-red-500' : '' }}"
                        placeholder="-99.1332">
                </div>
                @error('longitud')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo oculto para colonia (usa el valor de asentamiento) -->
                            <input type="hidden" name="colonia" value="{{ old('asentamiento', $datosFinales['asentamiento'] ?? '') }}">
        </div>
    </div>
    @else
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Domicilio
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <!-- Campo: Código Postal -->
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Código Postal
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-mail-bulk text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['codigo_postal'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed font-mono"
                        readonly>
                </div>
            </div>

            <!-- Campo: Estado -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['estado'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Municipio -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Municipio/Delegación
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-city text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['municipio'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Asentamiento -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Asentamiento/Colonia
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-home text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['asentamiento'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['calle'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Entre Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Entre Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['entre_calle'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Y Calle -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Y Calle
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-road text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['y_calle'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Número Exterior -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número Exterior
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datosFinales['numero_exterior'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
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
                        value="{{ $datosFinales['numero_interior'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Latitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Latitud
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $latitud ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>

            <!-- Campo: Longitud -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Longitud
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-map-marker-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $longitud ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-100 border border-gray-300 rounded-lg shadow-sm cursor-not-allowed"
                        readonly>
                </div>
            </div>
        </div>

        <!-- Dirección concatenada como resumen -->
        <div class="mt-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-map-marked-alt text-blue-600 text-lg mt-1"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-blue-600 font-medium mb-1">Dirección Completa:</p>
                    <p class="text-sm text-blue-800 leading-relaxed">
                        {{ $datosFinales['calle'] ?? '' }}
                        @if(!empty($datosFinales['numero_exterior'] ?? '')) #{{ $datosFinales['numero_exterior'] }} @endif
                        @if(!empty($datosFinales['numero_interior'] ?? '')) Int. {{ $datosFinales['numero_interior'] }} @endif
                        @if(!empty($datosFinales['entre_calle'] ?? '') && !empty($datosFinales['y_calle'] ?? '')) , Entre {{ $datosFinales['entre_calle'] }} y {{ $datosFinales['y_calle'] }} @endif
                        @if(!empty($datosFinales['asentamiento'] ?? '')) , {{ $datosFinales['asentamiento'] }} @endif
                        @if(!empty($datosFinales['codigo_postal'] ?? '')) , C.P. {{ $datosFinales['codigo_postal'] }} @endif
                        @if(!empty($datosFinales['municipio'] ?? '')) , {{ $datosFinales['municipio'] }} @endif
                        @if(!empty($datosFinales['estado'] ?? '')) , {{ $datosFinales['estado'] }} @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mapa siempre visible -->
    <div class="mt-6">
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Ubicación en Mapa
        </h4>
        <x-ui.forms.openstreet-map 
            :lat="$latitud" 
            :lng="$longitud" 
            :editable="$editable"
            height="300px"
        />
        
        @if($editable)
        <div class="mt-2 text-sm text-gray-600">
            <span id="coordenadas-display-domicilio">
                @if(!empty($latitud) && !empty($longitud))
                    Coordenadas seleccionadas: {{ $latitud }}, {{ $longitud }}
                @else
                    Haz clic en el mapa o ingresa las coordenadas manualmente
                @endif
            </span>
        </div>
        
        <!-- Enlace dinámico a Google Maps para modo editable -->
        <div id="google-maps-link-container" class="mt-4 flex justify-center">
            @if(!empty($latitud) && !empty($longitud))
            <a id="google-maps-link" 
               href="https://www.google.com/maps?q={{ $latitud }},{{ $longitud }}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ver en Google Maps
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
            @endif
        </div>
        @else
        <!-- Enlace a Google Maps para modo solo lectura -->
        @if(!empty($latitud) && !empty($longitud))
        <div class="mt-4 flex justify-center">
            <a href="https://www.google.com/maps?q={{ $latitud }},{{ $longitud }}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ver en Google Maps
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
        @endif
        @endif


    </div>
</div>

@if($editable)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const $ = id => document.getElementById(id);
    const elements = {
        cp: $('codigo_postal'),
        estado: $('estado_id'),
        municipio: $('municipio'),
        asentamiento: $('asentamiento'),
        entreCalle: $('entre_calle'),
        yCalle: $('y_calle'),
        loadingCp: $('loading-cp'),
        loadingEstados: $('loading-estados'),
    };

    // 🔁 Utilidad: Notificación visual
    const notificar = (msg, tipo = 'success') => {
        const div = document.createElement('div');
        div.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 text-white ${
            tipo === 'success' ? 'bg-green-500' :
            tipo === 'warning' ? 'bg-yellow-500' : 'bg-red-500'
        }`;
        div.textContent = msg;
        document.body.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    };

    // 📥 Cargar estados
    fetch('/api/ubicacion/estados')
        .then(res => res.json())
        .then(data => {
            elements.loadingEstados.classList.add('hidden');
            if (data.success) {
                elements.estado.innerHTML = `<option value="">Seleccione estado</option>` +
                    data.data.map(e => `<option value="${e.id}">${e.nombre}</option>`).join('');
                
                // Restaurar valor old si existe
                const estadoIdOld = document.querySelector('input[name="estado_id_old"]').value;
                if (estadoIdOld) {
                    elements.estado.value = estadoIdOld;
                }
                
                if (elements.cp.value.trim().length === 5) {
                    buscarPorCodigoPostal(elements.cp.value.trim());
                }
            } else {
                throw new Error('Error al cargar estados');
            }
        })
        .catch((error) => {
            console.error('Error cargando estados:', error);
            elements.loadingEstados.classList.add('hidden');
            elements.estado.innerHTML = `<option value="">Error al cargar estados</option>`;
        });

    // 🔍 Función para buscar por código postal
    function buscarPorCodigoPostal(cp) {
        if (cp.length !== 5) return;

        elements.loadingCp.classList.remove('hidden');

        fetch('/api/ubicacion/buscar-codigo-postal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ codigo_postal: cp })
        })
        .then(res => res.json())
        .then(data => {
            elements.loadingCp.classList.add('hidden');

            if (data.success && data.data.length > 0) {
                const u = data.data[0];
                
                // Solo llenar campos que estén vacíos o que no vengan de la constancia
                if (!elements.municipio.value) {
                    elements.municipio.value = u.municipio;
                }
                if (!elements.asentamiento.value) {
                    elements.asentamiento.value = u.asentamiento;
                }

                const estadoOption = [...elements.estado.options]
                    .find(o => o.text.toLowerCase() === u.estado.toLowerCase());

                // Solo llenar el estado si no hay valor old y no hay valor seleccionado
                const estadoIdOld = document.querySelector('input[name="estado_id_old"]').value;
                if (estadoOption && !elements.estado.value && !estadoIdOld) {
                    elements.estado.value = estadoOption.value;
                }

            } else {
                notificar('No se encontraron datos para este código postal', 'warning');
            }
        })
        .catch(() => {
            elements.loadingCp.classList.add('hidden');
            notificar('Error al cargar los datos', 'error');
        });
    }
    
    let timeoutId;
    elements.cp.addEventListener('input', () => {
        const cp = elements.cp.value.trim();

        clearTimeout(timeoutId);
        if (cp.length !== 5) {
            elements.loadingCp.classList.add('hidden');
            return;
        }

        timeoutId = setTimeout(() => {
            buscarPorCodigoPostal(cp);
        }, 500);
    });

    // 📍 Actualizar enlace de Google Maps cuando cambien las coordenadas
    const latitudInput = $('latitud-manual');
    const longitudInput = $('longitud-manual');
    const coordenadasDisplay = $('coordenadas-display-domicilio');
    const googleMapsLinkContainer = $('google-maps-link-container');

    function actualizarGoogleMapsLink() {
        const lat = latitudInput?.value;
        const lng = longitudInput?.value;
        
        if (lat && lng && !isNaN(lat) && !isNaN(lng) && lat.trim() !== '' && lng.trim() !== '') {
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);
            
            // Actualizar display de coordenadas
            if (coordenadasDisplay) {
                coordenadasDisplay.textContent = `Coordenadas seleccionadas: ${latNum.toFixed(6)}, ${lngNum.toFixed(6)}`;
            }
            
            // Crear o actualizar enlace de Google Maps
            const existingLink = $('google-maps-link');
            const googleMapsUrl = `https://www.google.com/maps?q=${latNum},${lngNum}`;
            
            if (existingLink) {
                existingLink.href = googleMapsUrl;
                googleMapsLinkContainer.style.display = 'flex';
            } else if (googleMapsLinkContainer) {
                googleMapsLinkContainer.innerHTML = `
                    <a id="google-maps-link" 
                       href="${googleMapsUrl}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Ver en Google Maps
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                `;
                googleMapsLinkContainer.style.display = 'flex';
            }
        } else {
            // Ocultar enlace si no hay coordenadas válidas
            if (googleMapsLinkContainer) {
                googleMapsLinkContainer.style.display = 'none';
            }
            if (coordenadasDisplay) {
                coordenadasDisplay.textContent = 'Haz clic en el mapa o ingresa las coordenadas manualmente';
            }
        }
    }

    // Actualizar enlace cuando cambien los inputs de coordenadas
    if (latitudInput) {
        latitudInput.addEventListener('input', function() {
            console.log('Input latitud cambiado:', this.value);
            actualizarGoogleMapsLink();
        });
    }
    if (longitudInput) {
        longitudInput.addEventListener('input', function() {
            console.log('Input longitud cambiado:', this.value);
            actualizarGoogleMapsLink();
        });
    }

    // Actualizar enlace al cargar la página si ya hay coordenadas
    actualizarGoogleMapsLink();

    // Sincronizar campo oculto del estado cuando cambie la selección
    elements.estado.addEventListener('change', function() {
        const hiddenInput = document.querySelector('input[name="estado_id_old"]');
        if (hiddenInput) {
            hiddenInput.value = this.value;
        }
    });

    // Asegurar que el mapa se inicialice correctamente cuando se cargue este componente
    setTimeout(() => {
        const mapContainer = document.getElementById('mapa');
        if (mapContainer && !mapContainer._leaflet_map) {
            // Si el mapa no está inicializado, disparar un evento para forzar la inicialización
            window.dispatchEvent(new CustomEvent('forceMapInitialization'));
        }
    }, 500);
});

// Escuchar eventos del mapa OpenStreetMap - FUERA del DOMContentLoaded para asegurar que se registre
window.addEventListener('coordenadasActualizadas', function(event) {
    console.log('Evento coordenadasActualizadas recibido:', event.detail);
    
    const latitudInput = document.getElementById('latitud-manual');
    const longitudInput = document.getElementById('longitud-manual');
    const coordenadasDisplay = document.getElementById('coordenadas-display-domicilio');
    const googleMapsLinkContainer = document.getElementById('google-maps-link-container');
    
    if (latitudInput && longitudInput) {
        latitudInput.value = event.detail.lat.toFixed(6);
        longitudInput.value = event.detail.lng.toFixed(6);
        console.log('Coordenadas actualizadas en inputs:', latitudInput.value, longitudInput.value);
        
        // Actualizar display de coordenadas
        if (coordenadasDisplay) {
            coordenadasDisplay.textContent = `Coordenadas seleccionadas: ${event.detail.lat.toFixed(6)}, ${event.detail.lng.toFixed(6)}`;
        }
        
        // Actualizar enlace de Google Maps inmediatamente
        setTimeout(() => {
            const existingLink = document.getElementById('google-maps-link');
            const googleMapsUrl = `https://www.google.com/maps?q=${event.detail.lat},${event.detail.lng}`;
            
            console.log('Actualizando enlace de Google Maps:', googleMapsUrl);
            
            if (existingLink) {
                existingLink.href = googleMapsUrl;
                if (googleMapsLinkContainer) {
                    googleMapsLinkContainer.style.display = 'flex';
                }
                console.log('Enlace de Google Maps actualizado');
            } else if (googleMapsLinkContainer) {
                googleMapsLinkContainer.innerHTML = `
                    <a id="google-maps-link" 
                       href="${googleMapsUrl}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Ver en Google Maps
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                `;
                googleMapsLinkContainer.style.display = 'flex';
                console.log('Nuevo enlace de Google Maps creado');
            }
        }, 100);
    }
});
</script>
@endif
