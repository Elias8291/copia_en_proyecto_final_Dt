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
            <p class="text-sm text-gray-500">Información del representante legal</p>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Apoderado Legal
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Nombre Completo
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['nombre_apoderado'] ?? '' }}"
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
                        value="{{ $datos['rfc_apoderado'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed font-mono"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    CURP
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-id-badge text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['curp_apoderado'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed font-mono"
                        disabled>
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
                    <input type="tel"
                        value="{{ $datos['telefono_apoderado'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Email
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-envelope text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="email"
                        value="{{ $datos['email_apoderado'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Cargo
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user-tag text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['cargo_apoderado'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Poder Notarial
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Escritura
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-file-contract text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['numero_escritura'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Fecha de Constitución
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-calendar text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="date"
                        value="{{ $datos['fecha_constitucion'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Nombre del Notario
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user-tie text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['nombre_notario'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Entidad Federativa
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-map text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['entidad_federativa'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Notario
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-hashtag text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['numero_notario'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Registro Público
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-registered text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text"
                        value="{{ $datos['numero_registro_publico'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Fecha de Inscripción
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-calendar-check text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="date"
                        value="{{ $datos['fecha_inscripcion'] ?? '' }}"
                        class="block w-full pl-8 pr-3 py-2 text-xs text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm cursor-not-allowed"
                        disabled>
                </div>
            </div>
        </div>
    </div>
</div> 