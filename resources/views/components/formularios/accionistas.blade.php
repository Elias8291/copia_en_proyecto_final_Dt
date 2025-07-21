@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                <p class="text-sm text-gray-500 mt-1">Información de los accionistas de la empresa</p>
            </div>
        </div>
    </div>

    <!-- Información sobre accionistas -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-sm flex-shrink-0">
                <i class="fas fa-info-circle text-sm"></i>
            </div>
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
                @if ($editable)
                <button type="button" class="remove-accionista text-red-600 hover:text-red-800 hidden">
                    <i class="fas fa-trash"></i>
                </button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                                            <input type="text" name="accionistas[0][nombre]" {{ $editable ? 'required' : 'readonly' }}
                           class="block w-full pl-10 pr-4 py-2.5 text-gray-700 {{ $editable ? 'bg-white' : 'bg-gray-50' }} border border-gray-200 rounded-lg {{ $editable ? 'focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50' : 'cursor-not-allowed' }} shadow-sm"
                           placeholder="Nombre completo del accionista"
                           aria-label="Nombre del accionista">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        RFC
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-500"></i>
                        </div>
                        <input type="text" name="accionistas[0][rfc]" 
                               class="rfc-input block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                               placeholder="RFC del accionista"
                               maxlength="13"
                               pattern="[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}"
                               aria-label="RFC del accionista"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">CURP</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-address-card text-gray-500"></i>
                        </div>
                        <input type="text" name="accionistas[0][curp]" 
                               class="curp-input block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                               placeholder="CURP del accionista"
                               maxlength="18"
                               aria-label="CURP del accionista">
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nacionalidad
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-globe text-gray-500"></i>
                        </div>
                        <select name="accionistas[0][nacionalidad]" 
                                class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none shadow-sm"
                                aria-label="Nacionalidad del accionista"
                                required>
                            <option value="">Seleccione nacionalidad</option>
                            <option value="MEXICANA">Mexicana</option>
                            <option value="EXTRANJERA">Extranjera</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Porcentaje de Participación
                        <span class="text-[#9d2449]">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-percentage text-gray-500"></i>
                        </div>
                        <input type="number" name="accionistas[0][porcentaje]" 
                               class="porcentaje-input block w-full pl-10 pr-12 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 shadow-sm"
                               placeholder="0.00"
                               min="0"
                               max="100"
                               step="0.01"
                               aria-label="Porcentaje de participación"
                               required>
                        <span class="absolute right-3 top-2.5 text-gray-500 text-sm">%</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Participación</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-users text-gray-500"></i>
                        </div>
                        <select name="accionistas[0][tipo_participacion]" 
                                class="block w-full pl-10 pr-10 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 appearance-none shadow-sm"
                                aria-label="Tipo de participación">
                            <option value="">Seleccione tipo</option>
                            <option value="ORDINARIA">Ordinaria</option>
                            <option value="PREFERENTE">Preferente</option>
                            <option value="COMUN">Común</option>
                            <option value="OTRA">Otra</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                <div class="relative group">
                    <div class="absolute top-3 left-0 pl-3 flex items-start pointer-events-none">
                        <i class="fas fa-align-left text-gray-500"></i>
                    </div>
                    <textarea name="accionistas[0][observaciones]" rows="2" 
                              class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-white border border-gray-200 rounded-lg focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all group-hover:border-[#9d2449]/50 resize-none shadow-sm"
                              placeholder="Información adicional sobre este accionista (opcional)"
                              aria-label="Observaciones del accionista"></textarea>
                </div>
            </div>
        </div>
    </div>

    @if ($editable)
    <!-- Botón para agregar accionista -->
    <div class="flex justify-between items-center">
        <button type="button" id="add-accionista" 
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Agregar Accionista
        </button>
    @else
    <div class="flex justify-between items-center">
        <div class="text-sm text-gray-600">
            <i class="fas fa-users mr-2"></i>
            Accionistas registrados
        </div>
    @endif
        
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