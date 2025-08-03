@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }}>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Constitución</h3>
            <p class="text-sm text-gray-500">Información de constitución legal</p>
        </div>
    </div>

    @if($editable)
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Datos de Constitución
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Escritura <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-file-contract text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="numero_escritura"
                        value="{{ $datos['numero_escritura'] ?? old('numero_escritura') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número de escritura">
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
                        name="numero_escritura_constitutiva"
                        value="{{ $datos['numero_escritura_constitutiva'] ?? old('numero_escritura_constitutiva') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número constitutivo">
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Constitución <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                    </div>
                    <input type="date"
                        name="fecha_constitucion"
                        value="{{ $datos['fecha_constitucion'] ?? old('fecha_constitucion') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Notario <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tie text-gray-500"></i>
                    </div>
                    <input type="text"
                        name="nombre_notario"
                        value="{{ $datos['nombre_notario'] ?? old('nombre_notario') }}"
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
                        name="numero_notario"
                        value="{{ $datos['numero_notario'] ?? old('numero_notario') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        placeholder="Ingrese número de notario">
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
                        name="numero_registro_publico"
                        value="{{ $datos['numero_registro_publico'] ?? old('numero_registro_publico') }}"
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
                        name="fecha_inscripcion"
                        value="{{ $datos['fecha_inscripcion'] ?? old('fecha_inscripcion') }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
            </div>
        </div>
    </div>
    @else
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Datos de Constitución Registrados
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
                        Constitución registrada
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <ul class="list-disc list-inside space-y-1">
                            @if(!empty($datos['numero_escritura']))
                                <li>Escritura: {{ $datos['numero_escritura'] }}</li>
                            @endif
                            @if(!empty($datos['fecha_constitucion']))
                                <li>Fecha: {{ $datos['fecha_constitucion'] }}</li>
                            @endif
                            @if(!empty($datos['nombre_notario']))
                                <li>Notario: {{ $datos['nombre_notario'] }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 