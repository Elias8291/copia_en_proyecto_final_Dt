@props(['datos' => [], 'editable' => false, 'datosApoderado' => null])

@php
    // Si se proporciona un FormDataViewModel, usar sus datos
    if ($datosApoderado instanceof \App\ViewModels\FormDataViewModel) {
        $datos = $datosApoderado->getApoderado();
    }
    
    // Asegurar que tenemos todos los campos necesarios
    $datos = array_merge([
        'nombre_apoderado' => '',
        'rfc' => '',
        'numero_escritura_poder' => '',
        'fecha_poder' => '',
        'nombre_notario_poder' => '',
        'numero_notario_poder' => '',
        'numero_escritura_constitutiva_poder' => '',
        'numero_registro_publico_poder' => '',
        'fecha_inscripcion_poder' => '',
        'estado_id' => '',
        'estado_nombre' => '',
        'numero_registro_publico' => '',
        'fecha_inscripcion' => '',
    ], $datos ?? []);
@endphp

<div class="space-y-6" {{ $attributes }}>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Apoderado Legal</h3>
            <p class="text-sm text-gray-500">Información del apoderado legal</p>
        </div>
    </div>

    @if($editable)
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Datos del Apoderado
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Apoderado <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="nombre_apoderado"
                        value="{{ $datos['nombre_apoderado'] ?? old('nombre_apoderado') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('nombre_apoderado') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese nombre completo">
                </div>
                @error('nombre_apoderado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    RFC <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="rfc_apoderado"
                        value="{{ $datos['rfc'] ?? old('rfc_apoderado') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono {{ $errors->has('rfc_apoderado') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese RFC">
                </div>
                @error('rfc_apoderado')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Escritura de Poder
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-file-contract text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_escritura_poder"
                        value="{{ $datos['numero_escritura_poder'] ?? old('numero_escritura_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('numero_escritura_poder') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese número de escritura">
                </div>
                @error('numero_escritura_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha del Poder
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                    </div>
                    <input type="date"
                        name="fecha_poder"
                        value="{{ $datos['fecha_poder'] ?? old('fecha_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('fecha_poder') ? 'border-red-500' : '' }}">
                </div>
                @error('fecha_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Notario
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tie text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="nombre_notario_poder"
                        value="{{ $datos['nombre_notario_poder'] ?? old('nombre_notario_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('nombre_notario_poder') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese nombre del notario">
                </div>
                @error('nombre_notario_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Notario <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-badge text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_notario_poder"
                        value="{{ $datos['numero_notario_poder'] ?? old('numero_notario_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('numero_notario_poder') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese número de notario">
                </div>
                @error('numero_notario_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Escritura Constitutiva <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-file-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_escritura_constitutiva_poder"
                        value="{{ $datos['numero_escritura_constitutiva_poder'] ?? old('numero_escritura_constitutiva_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('numero_escritura_constitutiva_poder') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese número constitutivo">
                </div>
                @error('numero_escritura_constitutiva_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Registro Público <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-registered text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_registro_publico_poder"
                        value="{{ $datos['numero_registro_publico_poder'] ?? old('numero_registro_publico_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('numero_registro_publico_poder') ? 'border-red-500' : '' }}"
                        placeholder="Ingrese número de registro">
                </div>
                @error('numero_registro_publico_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Inscripción <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-check text-gray-500"></i>
                    </div>
                    <input type="date"
                        name="fecha_inscripcion_poder"
                        value="{{ $datos['fecha_inscripcion_poder'] ?? old('fecha_inscripcion_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has('fecha_inscripcion_poder') ? 'border-red-500' : '' }}">
                </div>
                @error('fecha_inscripcion_poder')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    @else
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Datos del Apoderado
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Datos básicos del apoderado -->
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Apoderado
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['nombre_apoderado'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                        readonly>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    RFC
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['rfc'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono"
                        readonly>
                </div>
            </div>
        </div>

        <!-- Datos del Instrumento Notarial del Poder -->
        @if(!empty($datos['numero_escritura']) || !empty($datos['nombre_notario_poder']))
        <div class="mt-6">
            <h5 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b border-gray-200">
                Datos del Instrumento Notarial del Poder
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Escritura del Poder
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-file-contract text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ $datos['numero_escritura'] ?? $datos['numero_escritura_poder'] ?? '' }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha del Poder
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-alt text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ $datos['fecha_poder'] ?? '' }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Notario del Poder
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tie text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ $datos['nombre_notario_poder'] ?? '' }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número del Notario del Poder
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-badge text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ $datos['numero_notario_poder'] ?? '' }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>

                @if(!empty($datos['estado_id']))
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado del Notario
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-map-marker-alt text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ !empty($datos['estado_nombre']) ? $datos['estado_nombre'] : (!empty($datos['estado_id']) ? 'Estado ID: ' . $datos['estado_id'] : '') }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>
                @endif

                @if(!empty($datos['numero_registro_publico']))
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número de Registro Público del Instrumento
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-registered text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ $datos['numero_registro_publico'] ?? '' }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>
                @endif

                @if(!empty($datos['fecha_inscripcion']))
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha de Inscripción del Instrumento
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-check text-gray-500"></i>
                        </div>
                        <input type="text"
                            value="{{ $datos['fecha_inscripcion'] ?? '' }}"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                            readonly>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    @endif
</div> 