@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="space-y-8" {{ $attributes }}>
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Apoderado Legal
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Nombre Completo
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="apoderado_nombre" name="apoderado_nombre" 
                           value="{{ old('apoderado_nombre') }}"
                           data-validate="required|minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                           placeholder="Nombre completo del apoderado legal"
                           aria-label="Nombre completo del apoderado" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    RFC
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-id-card text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="apoderado_rfc" name="apoderado_rfc" 
                           value="{{ old('apoderado_rfc') }}"
                           data-validate="required|rfc"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 font-mono"
                           placeholder="RFC del apoderado"
                           maxlength="13"
                           pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                           aria-label="RFC del apoderado" required
                           style="text-transform: uppercase;">
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Poder Notarial -->
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Información del Poder Notarial
        </h4>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Escritura
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-file-contract text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="poder_numero_escritura" name="poder_numero_escritura" 
                           value="{{ old('poder_numero_escritura') }}"
                           data-validate="required|minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                           placeholder="Número de escritura del poder"
                           aria-label="Número de escritura del poder" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Fecha de Constitución
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-calendar-alt text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="date" id="poder_fecha_constitucion" name="poder_fecha_constitucion" 
                           value="{{ old('poder_fecha_constitucion') }}"
                           data-validate="required"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                           aria-label="Fecha de constitución del poder" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Nombre del Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-user-tie text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="poder_notario_nombre" name="poder_notario_nombre" 
                           value="{{ old('poder_notario_nombre') }}"
                           data-validate="required|minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                           placeholder="Nombre completo del notario"
                           aria-label="Nombre del notario" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Entidad Federativa
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-map-marked-alt text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <select id="poder_entidad_federativa" name="poder_entidad_federativa" 
                            data-validate="required"
                            class="block w-full pl-8 pr-10 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-10 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none"
                            aria-label="Entidad federativa del notario" required>
                        <option value="">Seleccione la entidad federativa</option>
                        <!-- Estados se cargarán dinámicamente -->
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-hashtag text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="poder_notario_numero" name="poder_notario_numero" 
                           value="{{ old('poder_notario_numero') }}"
                           data-validate="required|minLength:1|maxLength:10"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                           placeholder="Ej: 123"
                           aria-label="Número del notario" required>
                </div>
            </div>

            <div class="form-group field-container">
                <label class="block text-xs font-medium text-gray-700 mb-1.5 field-label sm:text-sm sm:mb-2">
                    Número de Registro
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none sm:pl-3">
                        <i class="fas fa-registered text-gray-500 text-xs sm:text-sm"></i>
                    </div>
                    <input type="text" id="poder_numero_registro" name="poder_numero_registro" 
                           value="{{ old('poder_numero_registro') }}"
                           data-validate="required|minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-lg shadow-sm sm:pl-10 sm:pr-4 sm:py-2.5 sm:text-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50"
                           placeholder="Ej: REG-2024-001"
                           aria-label="Número de registro" required>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estados para el select de entidad federativa del poder
    const poderEntidadFederativaSelect = document.getElementById('poder_entidad_federativa');
    
    if (poderEntidadFederativaSelect) {
        fetch('/api/ubicacion/estados')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    data.data.forEach(estado => {
                        const option = document.createElement('option');
                        option.value = estado.nombre.toUpperCase();
                        option.textContent = estado.nombre;
                        poderEntidadFederativaSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error al cargar estados:', error);
            });
    }
});
</script>
@endpush