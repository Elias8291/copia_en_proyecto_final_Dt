@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
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
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-sm flex-shrink-0">
                <i class="fas fa-info-circle text-sm"></i>
            </div>
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
                           aria-label="Nombre del apoderado legal"
                           required>
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
                           aria-label="RFC del apoderado legal"
                           required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="apoderado_curp" class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-address-card text-gray-500"></i>
                    </div>
                    <input type="text" id="apoderado_curp" name="apoderado_curp" 
                           value="{{ old('apoderado_curp') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="CURP del apoderado"
                           maxlength="18"
                           aria-label="CURP del apoderado legal">
                </div>
            </div>

            <div class="form-group">
                <label for="apoderado_cargo" class="block text-sm font-medium text-gray-700 mb-2">
                    Cargo
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-briefcase text-gray-500"></i>
                    </div>
                    <select id="apoderado_cargo" name="apoderado_cargo" 
                            class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none shadow-sm"
                            aria-label="Cargo del apoderado"
                            required>
                        <option value="">Seleccione el cargo</option>
                        <option value="Administrador Único">Administrador Único</option>
                        <option value="Director General">Director General</option>
                        <option value="Gerente General">Gerente General</option>
                        <option value="Presidente">Presidente</option>
                        <option value="Apoderado Legal">Apoderado Legal</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contacto del apoderado -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="apoderado_telefono" class="block text-sm font-medium text-gray-700 mb-2">
                    Teléfono
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-phone text-gray-500"></i>
                    </div>
                    <input type="tel" id="apoderado_telefono" name="apoderado_telefono" 
                           value="{{ old('apoderado_telefono') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Ej: 5551234567"
                           pattern="[0-9]{10}"
                           maxlength="10"
                           aria-label="Teléfono del apoderado"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="apoderado_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                    <span class="text-[#9d2449]">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <input type="email" id="apoderado_email" name="apoderado_email" 
                           value="{{ old('apoderado_email') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="email@empresa.com"
                           aria-label="Email del apoderado"
                           required>
                </div>
            </div>
        </div>

    <!-- Información del poder -->
    <div class="border-t border-gray-200 pt-6">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-gavel text-white text-sm"></i>
            </div>
            <h4 class="text-lg font-semibold text-gray-900">Información del Poder Notarial</h4>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="poder_numero_escritura" class="block text-sm font-medium text-gray-700 mb-2">Número de Escritura del Poder</label>
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
                <label for="poder_fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha del Poder</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                    </div>
                    <input type="date" id="poder_fecha" name="poder_fecha" 
                           value="{{ old('poder_fecha') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           aria-label="Fecha del poder notarial">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="form-group">
                <label for="poder_notario_numero" class="block text-sm font-medium text-gray-700 mb-2">Número del Notario</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <i class="fas fa-hashtag text-gray-500"></i>
                    </div>
                    <input type="text" id="poder_notario_numero" name="poder_notario_numero" 
                           value="{{ old('poder_notario_numero') }}"
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                           placeholder="Número del notario"
                           aria-label="Número del notario">
                </div>
            </div>

            <div class="form-group">
                <label for="poder_notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Notario</label>
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
        </div>
    </div>

    <!-- Información adicional para renovación/actualización -->
    @if($tipo !== 'inscripcion')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
            <div>
                <h4 class="text-base font-semibold text-yellow-800 mb-1">Verificación de Datos del Apoderado</h4>
                <p class="text-sm text-yellow-700">
                    @if($tipo === 'renovacion')
                        Verifique que los datos del apoderado legal coincidan con el poder notarial vigente.
                    @else
                        Solo modifique los campos que han cambiado desde su último registro.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de RFC
    const rfcInput = document.getElementById('apoderado_rfc');
    if (rfcInput) {
        rfcInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Validación de CURP
    const curpInput = document.getElementById('apoderado_curp');
    if (curpInput) {
        curpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Validación de teléfono
    const telefonoInput = document.getElementById('apoderado_telefono');
    if (telefonoInput) {
        telefonoInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }
});
</script>
@endpush

<style>
.h-12 {
    position: relative;
    overflow: hidden;
}
.h-12::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
    );
    transform: rotate(45deg);
    animation: shine 3s infinite;
}
@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    20%, 100% {
        transform: translateX(100%) rotate(45deg);
    }
}
.form-group:hover input:not([readonly]),
.form-group:hover select,
.form-group:hover textarea {
    @apply border-[#9d2449]/30;
}
input:focus:not([readonly]), 
select:focus,
textarea:focus {
    @apply ring-2 ring-[#9d2449]/20 border-[#9d2449];
    box-shadow: 0 0 0 1px rgba(157, 36, 73, 0.1), 
                0 2px 4px rgba(157, 36, 73, 0.05);
}
input[readonly] {
    @apply bg-gray-50;
}
input, select, textarea {
    @apply transition-all duration-300 bg-white shadow-sm;
}
input:focus:not([readonly]), 
select:focus, 
textarea:focus {
    @apply transform -translate-y-px shadow-md bg-white;
}
.form-group {
    @apply relative;
}
</style>