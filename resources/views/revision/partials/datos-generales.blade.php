@props(['tramite', 'proveedor', 'editable' => false])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Datos Generales</h2>
                <p class="text-sm text-gray-500 mt-1">Información básica del proveedor</p>
            </div>
        </div>
        @if($editable)
            <div class="flex items-center space-x-2">
                <button type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i>
                    Editar
                </button>
            </div>
        @endif
    </div>

    <div class="space-y-8">
        <!-- Información Básica -->
        <div>
            <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Información Básica
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Razón Social -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Razón Social
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-building text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            {{ $tramite->datosGenerales->razon_social ?? $proveedor->razon_social ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- RFC -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        RFC
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                            {{ $tramite->datosGenerales->rfc ?? $proveedor->rfc ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Tipo de Persona -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Tipo de Persona
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user-tag text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $proveedor->tipo_persona === 'Moral' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $proveedor->tipo_persona ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CURP (solo para persona física) -->
                @if($proveedor->tipo_persona === 'Física')
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        CURP
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-address-card text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono">
                            {{ $tramite->datosGenerales->curp ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Página Web -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Página Web
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-globe text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            @if($tramite->datosGenerales->pagina_web ?? null)
                                <a href="{{ $tramite->datosGenerales->pagina_web }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                                    {{ $tramite->datosGenerales->pagina_web }}
                                </a>
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Teléfono
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            {{ $tramite->datosGenerales->telefono ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado del Proveedor -->
        <div>
            <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Estado del Proveedor
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Estado del Padrón -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Estado del Padrón
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-clipboard-check text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $proveedor->estado_padron === 'Activo' ? 'bg-green-100 text-green-800' : 
                                   ($proveedor->estado_padron === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($proveedor->estado_padron === 'Inactivo' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ $proveedor->estado_padron ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Fecha de Alta -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Fecha de Alta
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-plus text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            {{ $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                </div>

                <!-- Fecha de Vencimiento -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Fecha de Vencimiento
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar-times text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            {{ $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Trámite -->
        <div>
            <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
                Información del Trámite
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Trámite -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Tipo de Trámite
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-file-alt text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $tramite->tipo_tramite === 'Inscripcion' ? 'bg-green-100 text-green-800' : 
                                   ($tramite->tipo_tramite === 'Renovacion' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : 
                                   ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Fecha de Solicitud -->
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                        Fecha de Solicitud
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-calendar text-gray-500"></i>
                        </div>
                        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                            {{ $tramite->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>