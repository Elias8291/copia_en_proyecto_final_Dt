@props(['tramite', 'proveedor', 'editable' => false])

<div class="space-y-8">
    <div>
        <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
            Información del Trámite
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

    <div>
        <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
            Información Básica
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

    <div>
        <h4 class="text-base font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100">
            Datos de Contacto
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($tramite->contactos as $contacto)
                <div class="form-group field-container col-span-2">
                    <div class="mb-2 font-semibold text-gray-700 flex items-center">
                        <i class="fas fa-user mr-2 text-gray-500"></i> {{ $contacto->nombre_contacto ?? 'Sin nombre' }}
                        @if($contacto->cargo)
                            <span class="ml-2 text-xs text-gray-500">({{ $contacto->cargo }})</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Correo Electrónico</label>
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span>{{ $contacto->correo_electronico ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Teléfono</label>
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span>{{ $contacto->telefono ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-gray-500">No hay contactos registrados.</div>
            @endforelse
        </div>
    </div>
</div>