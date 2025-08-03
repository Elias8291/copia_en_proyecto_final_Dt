@props(['datos' => [], 'editable' => false])

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
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese nombre completo">
                </div>
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
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono"
                        placeholder="Ingrese RFC">
                </div>
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
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número de escritura">
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
                    <input type="date"
                        name="fecha_poder"
                        value="{{ $datos['fecha_poder'] ?? old('fecha_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
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
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese nombre del notario">
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Notario
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-badge text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_notario_poder"
                        value="{{ $datos['numero_notario_poder'] ?? old('numero_notario_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número de notario">
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Escritura Constitutiva
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-file-alt text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_escritura_constitutiva_poder"
                        value="{{ $datos['numero_escritura_constitutiva_poder'] ?? old('numero_escritura_constitutiva_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número constitutivo">
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Registro Público
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-registered text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_registro_publico_poder"
                        value="{{ $datos['numero_registro_publico_poder'] ?? old('numero_registro_publico_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número de registro">
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Inscripción
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-check text-gray-500"></i>
                    </div>
                    <input type="date"
                        name="fecha_inscripcion_poder"
                        value="{{ $datos['fecha_inscripcion_poder'] ?? old('fecha_inscripcion_poder') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
            </div>
        </div>
    </div>
    @else
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Datos del Apoderado Registrados
        </h4>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Apoderado registrado
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <ul class="list-disc list-inside space-y-1">
                            @if(!empty($datos['nombre_apoderado']))
                                <li>Nombre: {{ $datos['nombre_apoderado'] }}</li>
                            @endif
                            @if(!empty($datos['rfc']))
                                <li>RFC: {{ $datos['rfc'] }}</li>
                            @endif
                            @if(!empty($datos['numero_escritura_poder']))
                                <li>Escritura: {{ $datos['numero_escritura_poder'] }}</li>
                            @endif
                            @if(!empty($datos['numero_escritura_constitutiva_poder']))
                                <li>Escritura Constitutiva: {{ $datos['numero_escritura_constitutiva_poder'] }}</li>
                            @endif
                            @if(!empty($datos['numero_registro_publico_poder']))
                                <li>Registro Público: {{ $datos['numero_registro_publico_poder'] }}</li>
                            @endif
                            @if(!empty($datos['fecha_inscripcion_poder']))
                                <li>Fecha de Inscripción: {{ $datos['fecha_inscripcion_poder'] }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 