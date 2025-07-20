@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="space-y-6">
    <!-- Información del apoderado -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <p class="text-sm text-blue-700">
                Información del representante legal autorizado para actuar en nombre de la empresa.
            </p>
        </div>
    </div>

    <!-- Datos personales del apoderado -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="apoderado_nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo <span class="text-red-500">*</span></label>
            <input type="text" id="apoderado_nombre" name="apoderado_nombre" 
                   value="{{ old('apoderado_nombre') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Nombre completo del apoderado legal"
                   required>
        </div>

        <div>
            <label for="apoderado_rfc" class="block text-sm font-medium text-gray-700 mb-2">RFC <span class="text-red-500">*</span></label>
            <input type="text" id="apoderado_rfc" name="apoderado_rfc" 
                   value="{{ old('apoderado_rfc') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="RFC del apoderado"
                   maxlength="13"
                   pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                   required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="apoderado_curp" class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
            <input type="text" id="apoderado_curp" name="apoderado_curp" 
                   value="{{ old('apoderado_curp') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="CURP del apoderado"
                   maxlength="18">
        </div>

        <div>
            <label for="apoderado_cargo" class="block text-sm font-medium text-gray-700 mb-2">Cargo <span class="text-red-500">*</span></label>
            <select id="apoderado_cargo" name="apoderado_cargo" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent" 
                    required>
                <option value="">Seleccione el cargo</option>
                <option value="Administrador Único">Administrador Único</option>
                <option value="Director General">Director General</option>
                <option value="Gerente General">Gerente General</option>
                <option value="Presidente">Presidente</option>
                <option value="Apoderado Legal">Apoderado Legal</option>
                <option value="Otro">Otro</option>
            </select>
        </div>
    </div>

    <!-- Contacto del apoderado -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="apoderado_telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono <span class="text-red-500">*</span></label>
            <input type="tel" id="apoderado_telefono" name="apoderado_telefono" 
                   value="{{ old('apoderado_telefono') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Ej: 5551234567"
                   pattern="[0-9]{10}"
                   maxlength="10"
                   required>
        </div>

        <div>
            <label for="apoderado_email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" id="apoderado_email" name="apoderado_email" 
                   value="{{ old('apoderado_email') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="email@empresa.com"
                   required>
        </div>
    </div>

    <!-- Información del poder -->
    <div class="border-t border-gray-200 pt-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Información del Poder Notarial</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="poder_numero_escritura" class="block text-sm font-medium text-gray-700 mb-2">Número de Escritura del Poder</label>
                <input type="text" id="poder_numero_escritura" name="poder_numero_escritura" 
                       value="{{ old('poder_numero_escritura') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                       placeholder="Número de escritura del poder">
            </div>

            <div>
                <label for="poder_fecha" class="block text-sm font-medium text-gray-700 mb-2">Fecha del Poder</label>
                <input type="date" id="poder_fecha" name="poder_fecha" 
                       value="{{ old('poder_fecha') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div>
                <label for="poder_notario_numero" class="block text-sm font-medium text-gray-700 mb-2">Número del Notario</label>
                <input type="text" id="poder_notario_numero" name="poder_notario_numero" 
                       value="{{ old('poder_notario_numero') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                       placeholder="Número del notario">
            </div>

            <div>
                <label for="poder_notario_nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Notario</label>
                <input type="text" id="poder_notario_nombre" name="poder_notario_nombre" 
                       value="{{ old('poder_notario_nombre') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                       placeholder="Nombre completo del notario">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de RFC
    const rfcInput = document.getElementById('apoderado_rfc');
    rfcInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Validación de CURP
    const curpInput = document.getElementById('apoderado_curp');
    curpInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Validación de teléfono
    const telefonoInput = document.getElementById('apoderado_telefono');
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
});
</script>
@endpush 