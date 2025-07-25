@props(['tramite', 'proveedor', 'editable' => false])

<div class="space-y-8">
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información Básica
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Razón Social
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-building text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <div class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm">
                        {{ $tramite->datosGenerales->razon_social ?? $proveedor->razon_social ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    RFC
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-id-card text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <div class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm">
                        {{ $tramite->datosGenerales->rfc ?? $proveedor->rfc ?? 'N/A' }}
                    </div>
                </div>
            </div>

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

    <!-- Separador entre secciones -->
    <div class="my-6 sm:my-8 lg:my-10">
        <div class="flex items-center">
            <div class="flex-1 h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
            <div class="px-4 sm:px-6">
                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
            </div>
            <div class="flex-1 h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Datos de Contacto
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 lg:gap-6">
            @forelse($tramite->contactos as $contacto)
                <div class="form-group field-container">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                        Nombre del Contacto
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                            <i class="fas fa-user text-gray-500 text-xs sm:text-sm"></i>
                        </div>
                        <div class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm">
                            {{ $contacto->nombre_contacto ?? 'Sin nombre' }}
                            @if($contacto->cargo)
                                <span class="text-gray-500"> - {{ $contacto->cargo }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                            <i class="fas fa-envelope text-gray-500 text-xs sm:text-sm"></i>
                        </div>
                        <div class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm">
                            {{ $contacto->correo_electronico ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                        Teléfono
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                            <i class="fas fa-phone text-gray-500 text-xs sm:text-sm"></i>
                        </div>
                        <div class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm">
                            {{ $contacto->telefono ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-xs text-gray-500 p-3 bg-gray-50 rounded-lg border border-gray-200 sm:text-sm">
                    <div class="flex items-center justify-center space-x-2">
                        <i class="fas fa-info-circle text-gray-400"></i>
                        <span>No hay contactos registrados.</span>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>