@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="space-y-6">
    <!-- Información del domicilio fiscal -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <p class="text-sm text-blue-700">
                Ingrese la dirección del domicilio fiscal registrado ante el SAT
            </p>
        </div>
    </div>

    <!-- Código Postal -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="relative">
            <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-2">Código Postal <span class="text-red-500">*</span></label>
            <input type="text" id="codigo_postal" name="codigo_postal" 
                   value="{{ old('codigo_postal') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Ej: 01000"
                   pattern="[0-9]{5}"
                   maxlength="5"
                   required>
            <p class="text-xs text-gray-500 mt-1">5 dígitos - Se cargarán los datos automáticamente</p>
        </div>

        <!-- País (oculto, siempre México) -->
        <input type="hidden" id="pais" name="pais" value="MÉXICO">
        <input type="hidden" id="pais_id" name="pais_id" value="1">

        <!-- Estado -->
        <div>
            <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado <span class="text-red-500">*</span></label>
            <input type="text" id="estado" name="estado" 
                   value="{{ old('estado') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent bg-gray-50"
                   placeholder="Se cargará automáticamente"
                   readonly
                   required>
            <input type="hidden" id="estado_id" name="estado_id">
        </div>

        <!-- Municipio -->
        <div>
            <label for="municipio" class="block text-sm font-medium text-gray-700 mb-2">Municipio/Delegación <span class="text-red-500">*</span></label>
            <input type="text" id="municipio" name="municipio" 
                   value="{{ old('municipio') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent bg-gray-50"
                   placeholder="Se cargará automáticamente"
                   readonly
                   required>
            <input type="hidden" id="municipio_id" name="municipio_id">
        </div>
    </div>

    <!-- Localidad y Asentamiento -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="localidad" class="block text-sm font-medium text-gray-700 mb-2">Localidad</label>
            <input type="text" id="localidad" name="localidad" 
                   value="{{ old('localidad') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent bg-gray-50"
                   placeholder="Se cargará automáticamente"
                   readonly>
            <input type="hidden" id="localidad_id" name="localidad_id">
        </div>

        <div>
            <label for="asentamiento" class="block text-sm font-medium text-gray-700 mb-2">Asentamiento/Colonia <span class="text-red-500">*</span></label>
            <input type="text" id="asentamiento" name="asentamiento" 
                   value="{{ old('asentamiento') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent bg-gray-50"
                   placeholder="Se cargará automáticamente"
                   readonly
                   required>
            <input type="hidden" id="asentamiento_id" name="asentamiento_id">
        </div>

        <div>
            <label for="tipo_asentamiento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Asentamiento</label>
            <input type="text" id="tipo_asentamiento" name="tipo_asentamiento" 
                   value="{{ old('tipo_asentamiento') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent bg-gray-50"
                   placeholder="Se cargará automáticamente"
                   readonly>
        </div>
    </div>

    <!-- Dirección -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <label for="calle" class="block text-sm font-medium text-gray-700 mb-2">Calle <span class="text-red-500">*</span></label>
            <input type="text" id="calle" name="calle" 
                   value="{{ old('calle') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Nombre de la calle"
                   required>
        </div>

        <div>
            <label for="numero_exterior" class="block text-sm font-medium text-gray-700 mb-2">Número Exterior <span class="text-red-500">*</span></label>
            <input type="text" id="numero_exterior" name="numero_exterior" 
                   value="{{ old('numero_exterior') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="123"
                   required>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="numero_interior" class="block text-sm font-medium text-gray-700 mb-2">Número Interior</label>
            <input type="text" id="numero_interior" name="numero_interior" 
                   value="{{ old('numero_interior') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Depto. A, Piso 2, etc. (opcional)">
        </div>

        <div>
            <label for="entre_calles" class="block text-sm font-medium text-gray-700 mb-2">Entre Calles</label>
            <input type="text" id="entre_calles" name="entre_calles" 
                   value="{{ old('entre_calles') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                   placeholder="Calle A y Calle B (opcional)">
        </div>
    </div>

    <!-- Referencias adicionales -->
    <div>
        <label for="referencias" class="block text-sm font-medium text-gray-700 mb-2">Referencias Adicionales</label>
        <textarea id="referencias" name="referencias" rows="3" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                  placeholder="Puntos de referencia, color del edificio, etc. (opcional)">{{ old('referencias') }}</textarea>
    </div>

    <!-- Información adicional para renovación/actualización -->
    @if($tipo !== 'inscripcion')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2 mt-0.5"></i>
            <div>
                <h4 class="font-medium text-yellow-800 mb-1">Verificación de Domicilio</h4>
                <p class="text-sm text-yellow-700">
                    @if($tipo === 'renovacion')
                        Verifique que los datos del domicilio fiscal coincidan con su registro actual en el SAT.
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
    // Validación de código postal
    const codigoPostalInput = document.getElementById('codigo_postal');
    codigoPostalInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // Auto-capitalizar campos de texto
    const textInputs = ['estado', 'municipio', 'colonia', 'localidad', 'calle'];
    textInputs.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input && input.type === 'text') {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });
});
</script>
@endpush 