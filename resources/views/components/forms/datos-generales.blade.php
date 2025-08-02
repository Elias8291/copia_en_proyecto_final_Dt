@props(['datos' => [], 'editable' => false])

@php
    // Determinar tipo de persona basado en RFC
    $rfcValue = $datos['rfc'] ?? '';
    $tipoPersona = 'Física';
    if ($rfcValue && strlen($rfcValue) > 13) {
        $tipoPersona = 'Moral';
    }
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
            <h3 class="text-lg font-semibold text-gray-900">Datos Generales</h3>
            <p class="text-sm text-gray-500">Información personal y de contacto</p>
        </div>
    </div>

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
                    <input type="text"
                        value="{{ $datos['razon_social'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
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
                    <input type="text"
                        value="{{ $datos['rfc'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed font-mono"
                        disabled>
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
                    <div class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoPersona === 'Moral' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}
                        </span>
                    </div>
                </div>
            </div>

            @if($tipoPersona === 'Física')
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    CURP
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-address-card text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['curp'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono cursor-not-allowed"
                        disabled>
                </div>
            </div>
            @endif

            <div class="form-group field-container {{ $tipoPersona === 'Física' ? '' : 'md:col-span-2' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">Página Web</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-globe text-gray-500"></i>
                    </div>
                    <input type="url"
                        value="{{ $datos['pagina_web'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información de Contacto
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Teléfono
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-500"></i>
                    </div>
                    <input type="tel"
                        value="{{ $datos['telefono'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Email de Contacto
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email"
                        value="{{ $datos['email_contacto'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
                    Cargo
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-briefcase text-gray-500"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['cargo'] ?? '' }}"
                        class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>
        </div>
    </div>
</div> 