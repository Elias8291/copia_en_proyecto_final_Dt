@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="space-y-6">
    <!-- Información sobre accionistas -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            <p class="text-sm text-blue-700">
                Registre la información de los accionistas de la empresa. Puede agregar múltiples accionistas.
            </p>
        </div>
    </div>

    <!-- Lista de accionistas -->
    <div id="accionistas-container">
        <!-- Accionista inicial -->
        <div class="accionista-item bg-white border border-gray-200 rounded-lg p-6 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Accionista #1</h4>
                <button type="button" class="remove-accionista text-red-600 hover:text-red-800 hidden">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" name="accionistas[0][nombre]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                           placeholder="Nombre completo del accionista"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RFC <span class="text-red-500">*</span></label>
                    <input type="text" name="accionistas[0][rfc]" 
                           class="rfc-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                           placeholder="RFC del accionista"
                           maxlength="13"
                           pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
                    <input type="text" name="accionistas[0][curp]" 
                           class="curp-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                           placeholder="CURP del accionista"
                           maxlength="18">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nacionalidad <span class="text-red-500">*</span></label>
                    <select name="accionistas[0][nacionalidad]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent" 
                            required>
                        <option value="">Seleccione nacionalidad</option>
                        <option value="MEXICANA">Mexicana</option>
                        <option value="EXTRANJERA">Extranjera</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje de Participación <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="accionistas[0][porcentaje]" 
                               class="porcentaje-input w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                               placeholder="0.00"
                               min="0"
                               max="100"
                               step="0.01"
                               required>
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Participación</label>
                    <select name="accionistas[0][tipo_participacion]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent">
                        <option value="">Seleccione tipo</option>
                        <option value="ORDINARIA">Ordinaria</option>
                        <option value="PREFERENTE">Preferente</option>
                        <option value="COMUN">Común</option>
                        <option value="OTRA">Otra</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                <textarea name="accionistas[0][observaciones]" rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                          placeholder="Información adicional sobre este accionista (opcional)"></textarea>
            </div>
        </div>
    </div>

    <!-- Botón para agregar accionista -->
    <div class="flex justify-between items-center">
        <button type="button" id="add-accionista" 
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Agregar Accionista
        </button>
        
        <!-- Resumen de participación -->
        <div class="text-sm">
            <span class="text-gray-600">Total de participación: </span>
            <span id="total-participacion" class="font-semibold text-gray-900">0%</span>
        </div>
    </div>

    <!-- Validación de participación -->
    <div id="participacion-warning" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
            <p class="text-sm text-yellow-700">
                La suma de participaciones debe ser igual al 100%.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let accionistaCount = 1;
    
    // Función para agregar nuevo accionista
    document.getElementById('add-accionista').addEventListener('click', function() {
        const container = document.getElementById('accionistas-container');
        const newAccionista = createAccionistaElement(accionistaCount);
        container.appendChild(newAccionista);
        accionistaCount++;
        updateRemoveButtons();
        calculateTotalParticipacion();
    });
    
    // Función para crear elemento de accionista
    function createAccionistaElement(index) {
        const div = document.createElement('div');
        div.className = 'accionista-item bg-white border border-gray-200 rounded-lg p-6 mb-4';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-900">Accionista #${index + 1}</h4>
                <button type="button" class="remove-accionista text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" name="accionistas[${index}][nombre]" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                           placeholder="Nombre completo del accionista"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">RFC <span class="text-red-500">*</span></label>
                    <input type="text" name="accionistas[${index}][rfc]" 
                           class="rfc-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                           placeholder="RFC del accionista"
                           maxlength="13"
                           pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
                    <input type="text" name="accionistas[${index}][curp]" 
                           class="curp-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                           placeholder="CURP del accionista"
                           maxlength="18">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nacionalidad <span class="text-red-500">*</span></label>
                    <select name="accionistas[${index}][nacionalidad]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent" 
                            required>
                        <option value="">Seleccione nacionalidad</option>
                        <option value="MEXICANA">Mexicana</option>
                        <option value="EXTRANJERA">Extranjera</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje de Participación <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="accionistas[${index}][porcentaje]" 
                               class="porcentaje-input w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                               placeholder="0.00"
                               min="0"
                               max="100"
                               step="0.01"
                               required>
                        <span class="absolute right-3 top-2 text-gray-500">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Participación</label>
                    <select name="accionistas[${index}][tipo_participacion]" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent">
                        <option value="">Seleccione tipo</option>
                        <option value="ORDINARIA">Ordinaria</option>
                        <option value="PREFERENTE">Preferente</option>
                        <option value="COMUN">Común</option>
                        <option value="OTRA">Otra</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                <textarea name="accionistas[${index}][observaciones]" rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent"
                          placeholder="Información adicional sobre este accionista (opcional)"></textarea>
            </div>
        `;
        
        // Agregar event listeners para validación
        const rfcInput = div.querySelector('.rfc-input');
        const curpInput = div.querySelector('.curp-input');
        const porcentajeInput = div.querySelector('.porcentaje-input');
        const removeBtn = div.querySelector('.remove-accionista');
        
        rfcInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        curpInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        porcentajeInput.addEventListener('input', calculateTotalParticipacion);
        
        removeBtn.addEventListener('click', function() {
            div.remove();
            updateRemoveButtons();
            calculateTotalParticipacion();
        });
        
        return div;
    }
    
    // Función para actualizar botones de eliminar
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.accionista-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-accionista');
            if (items.length > 1) {
                removeBtn.classList.remove('hidden');
            } else {
                removeBtn.classList.add('hidden');
            }
        });
    }
    
    // Función para calcular total de participación
    function calculateTotalParticipacion() {
        const porcentajeInputs = document.querySelectorAll('.porcentaje-input');
        let total = 0;
        
        porcentajeInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        
        const totalSpan = document.getElementById('total-participacion');
        const warning = document.getElementById('participacion-warning');
        
        totalSpan.textContent = total.toFixed(2) + '%';
        
        if (total !== 100 && total > 0) {
            warning.classList.remove('hidden');
            totalSpan.className = 'font-semibold text-red-600';
        } else {
            warning.classList.add('hidden');
            totalSpan.className = 'font-semibold text-green-600';
        }
    }
    
    // Event listeners iniciales
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('rfc-input')) {
            e.target.value = e.target.value.toUpperCase();
        }
        if (e.target.classList.contains('curp-input')) {
            e.target.value = e.target.value.toUpperCase();
        }
        if (e.target.classList.contains('porcentaje-input')) {
            calculateTotalParticipacion();
        }
    });
    
    // Inicializar
    updateRemoveButtons();
    calculateTotalParticipacion();
});
</script>
@endpush 