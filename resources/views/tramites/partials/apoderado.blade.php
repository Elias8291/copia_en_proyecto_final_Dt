@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-user-tie text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Apoderado Legal</h2>
                <p class="text-sm text-gray-500 mt-1">Información del representante legal autorizado</p>
            </div>
        </div>
    </div>

    <!-- Información del apoderado -->
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-amber-600 mr-2"></i>
            <p class="text-sm text-amber-700">
                Información del representante legal autorizado para actuar en nombre de la empresa.
            </p>
        </div>
    </div>

    <div class="space-y-8">

        <!-- Datos personales del apoderado -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="apoderado_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre Completo
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <input type="text" id="apoderado_nombre" name="apoderado_nombre" 
                           value="{{ old('apoderado_nombre') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Nombre completo del apoderado legal"
                           aria-label="Nombre completo del apoderado">
                </div>
            </div>

            <div class="form-group">
                <label for="apoderado_rfc" class="block text-sm font-medium text-gray-700 mb-2">
                    RFC
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-id-card text-gray-500"></i>
                    </div>
                    <input type="text" id="apoderado_rfc" name="apoderado_rfc" 
                           value="{{ old('apoderado_rfc') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="RFC del apoderado"
                           maxlength="13"
                           pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                           aria-label="RFC del apoderado">
                </div>
            </div>
        </div>

    <!-- Información del poder notarial -->
    <div class="border-t border-gray-200 pt-6">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-gavel text-white text-sm"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900">Información del Poder Notarial</h4>
        </div>
        
        <!-- Primera fila: Número de Escritura y Fecha de Constitución -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="poder_numero_escritura" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Escritura
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-file-contract text-gray-500"></i>
                    </div>
                    <input type="text" id="poder_numero_escritura" name="poder_numero_escritura" 
                           value="{{ old('poder_numero_escritura') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Número de escritura del poder"
                           aria-label="Número de escritura del poder">
                </div>
            </div>

            <div class="form-group">
                <label for="poder_fecha_constitucion" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Constitución
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                    </div>
                    <input type="date" id="poder_fecha_constitucion" name="poder_fecha_constitucion" 
                           value="{{ old('poder_fecha_constitucion') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           aria-label="Fecha de constitución del poder">
                </div>
            </div>
        </div>

        <!-- Segunda fila: Nombre del Notario y Entidad Federativa -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="form-group">
                <label for="poder_notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre del Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-user-tie text-gray-500"></i>
                    </div>
                    <input type="text" id="poder_notario_nombre" name="poder_notario_nombre" 
                           value="{{ old('poder_notario_nombre') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Nombre completo del notario"
                           aria-label="Nombre del notario">
                </div>
            </div>

            <div class="form-group">
                <label for="poder_entidad_federativa" class="block text-sm font-medium text-gray-700 mb-2">
                    Entidad Federativa
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-map-marked-alt text-gray-500"></i>
                    </div>
                    <select id="poder_entidad_federativa" name="poder_entidad_federativa" 
                            class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none shadow-sm"
                            aria-label="Entidad federativa del notario">
                        <option value="">Seleccione la entidad federativa</option>
                        <!-- Estados se cargarán dinámicamente -->
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tercera fila: Número de Notario y Número de Registro -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="form-group">
                <label for="poder_notario_numero" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Notario
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text" id="poder_notario_numero" name="poder_notario_numero" 
                           value="{{ old('poder_notario_numero') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 123"
                           aria-label="Número del notario">
                </div>
            </div>

            <div class="form-group">
                <label for="poder_numero_registro" class="block text-sm font-medium text-gray-700 mb-2">
                    Número de Registro
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-registered text-gray-500"></i>
                    </div>
                    <input type="text" id="poder_numero_registro" name="poder_numero_registro" 
                           value="{{ old('poder_numero_registro') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: REG-2024-001"
                           aria-label="Número de registro">
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