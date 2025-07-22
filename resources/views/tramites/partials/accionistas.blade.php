@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div
                class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-gavel text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                <p class="text-sm text-gray-500 mt-1">Información Agrega Accionistas como sea necesario</p>
            </div>
        </div>
        
        <!-- Botón para agregar accionista -->
        <button type="button" 
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9d2449] to-[#8a203f] text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:ring-opacity-50"
                onclick="agregarAccionista()">
            <i class="fas fa-plus text-sm mr-2"></i>
            Agregar Accionista
        </button>
    </div>

    <!-- Container para los cards de accionistas -->
    <div id="accionistas-container" class="grid gap-6 md:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
        
        <!-- Card de ejemplo (se puede remover) -->
        <div class="accionista-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-[1.02]">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3 cursor-pointer" onclick="toggleMinimizar(this)">
                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-sm">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">Accionista #1</h3>
                        <div class="card-summary text-xs text-gray-500 hidden">
                            <span class="nombre-resumen">Sin nombre</span> • 
                            <span class="rfc-resumen">Sin RFC</span> • 
                            <span class="porcentaje-resumen">0%</span>
                        </div>
                    </div>
                    <button type="button" 
                            class="toggle-btn text-gray-400 hover:text-[#9d2449] transition-colors duration-200 p-1"
                            onclick="event.stopPropagation(); toggleMinimizar(this.parentElement)">
                        <i class="fas fa-chevron-up text-sm"></i>
                    </button>
                </div>
                <button type="button" 
                        class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 rounded-full hover:bg-red-50"
                        onclick="eliminarAccionista(this)">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="card-content space-y-4">
                <!-- Campo Nombre -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-[#9d2449] mr-2"></i>
                        Nombre Completo
                    </label>
                    <input type="text" 
                           name="accionistas[0][nombre]"
                           class="nombre-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                           placeholder="Ingrese el nombre completo"
                           oninput="actualizarResumen(this)"
                           {{ !$editable ? 'readonly' : '' }}>
                </div>

                <!-- Campo RFC -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-id-card text-[#9d2449] mr-2"></i>
                        RFC
                    </label>
                    <input type="text" 
                           name="accionistas[0][rfc]"
                           class="rfc-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md uppercase"
                           placeholder="AAAA######AAA"
                           maxlength="13"
                           pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}"
                           oninput="actualizarResumen(this)"
                           {{ !$editable ? 'readonly' : '' }}>
                </div>

                <!-- Campo Porcentaje -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-percentage text-[#9d2449] mr-2"></i>
                        Porcentaje de Participación
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="accionistas[0][porcentaje]"
                               class="porcentaje-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                               placeholder="0.00"
                               min="0"
                               max="100"
                               step="0.01"
                               oninput="actualizarPorcentajeIndividual(this); actualizarTotal(); actualizarResumen(this)"
                               {{ !$editable ? 'readonly' : '' }}>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-medium">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indicador de porcentaje válido -->
            <div class="mt-4 flex items-center text-xs">
                <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#9d2449] to-[#8a203f] h-full rounded-full transition-all duration-300" 
                         style="width: 0%"></div>
                </div>
                <span class="ml-3 text-gray-600 font-medium">0%</span>
            </div>
        </div>

    </div>

    <!-- Resumen total -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="bg-gradient-to-r from-[#9d2449]/10 to-[#8a203f]/10 rounded-xl p-4 border border-[#9d2449]/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 flex items-center justify-center rounded-lg bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white">
                        <i class="fas fa-calculator text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Porcentaje Total:</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span id="porcentaje-total" class="text-lg font-bold text-[#9d2449]">0%</span>
                    <div id="validacion-icono" class="hidden">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div id="barra-total" class="bg-gradient-to-r from-[#9d2449] to-[#8a203f] h-full rounded-full transition-all duration-500" 
                         style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let contadorAccionistas = 1;

function agregarAccionista() {
    const container = document.getElementById('accionistas-container');
    const nuevoCard = crearCardAccionista(contadorAccionistas);
    container.appendChild(nuevoCard);
    contadorAccionistas++;
    actualizarTotal();
}

function crearCardAccionista(index) {
    const div = document.createElement('div');
    div.className = 'accionista-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all duration-300 transform hover:scale-[1.02]';
    
    div.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3 cursor-pointer" onclick="toggleMinimizar(this)">
                <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-sm">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800">Accionista #${index + 1}</h3>
                    <div class="card-summary text-xs text-gray-500 hidden">
                        <span class="nombre-resumen">Sin nombre</span> • 
                        <span class="rfc-resumen">Sin RFC</span> • 
                        <span class="porcentaje-resumen">0%</span>
                    </div>
                </div>
                <button type="button" 
                        class="toggle-btn text-gray-400 hover:text-[#9d2449] transition-colors duration-200 p-1"
                        onclick="event.stopPropagation(); toggleMinimizar(this.parentElement)">
                    <i class="fas fa-chevron-up text-sm"></i>
                </button>
            </div>
            <button type="button" 
                    class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 rounded-full hover:bg-red-50"
                    onclick="eliminarAccionista(this)">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <div class="card-content space-y-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-[#9d2449] mr-2"></i>
                    Nombre Completo
                </label>
                <input type="text" 
                       name="accionistas[${index}][nombre]"
                       class="nombre-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                       placeholder="Ingrese el nombre completo"
                       oninput="actualizarResumen(this)">
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-id-card text-[#9d2449] mr-2"></i>
                    RFC
                </label>
                <input type="text" 
                       name="accionistas[${index}][rfc]"
                       class="rfc-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md uppercase"
                       placeholder="AAAA######AAA"
                       maxlength="13"
                       pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}"
                       oninput="actualizarResumen(this)">
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-percentage text-[#9d2449] mr-2"></i>
                    Porcentaje de Participación
                </label>
                <div class="relative">
                    <input type="number" 
                           name="accionistas[${index}][porcentaje]"
                           class="porcentaje-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                           placeholder="0.00"
                           min="0"
                           max="100"
                           step="0.01"
                           oninput="actualizarPorcentajeIndividual(this); actualizarTotal(); actualizarResumen(this)">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm font-medium">%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center text-xs">
            <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="porcentaje-barra bg-gradient-to-r from-[#9d2449] to-[#8a203f] h-full rounded-full transition-all duration-300" 
                     style="width: 0%"></div>
            </div>
            <span class="porcentaje-texto ml-3 text-gray-600 font-medium">0%</span>
        </div>
    `;
    
    return div;
}

function eliminarAccionista(button) {
    const card = button.closest('.accionista-card');
    card.remove();
    actualizarTotal();
    actualizarNumeracion();
}

function actualizarPorcentajeIndividual(input) {
    const card = input.closest('.accionista-card');
    const barra = card.querySelector('.porcentaje-barra');
    const texto = card.querySelector('.porcentaje-texto');
    const valor = parseFloat(input.value) || 0;
    
    barra.style.width = valor + '%';
    texto.textContent = valor.toFixed(1) + '%';
}

// Función para minimizar/expandir cards
function toggleMinimizar(headerElement) {
    const card = headerElement.closest('.accionista-card');
    const content = card.querySelector('.card-content');
    const progressSection = card.querySelector('.mt-4'); // Sección de la barra de progreso
    const toggleIcon = card.querySelector('.toggle-btn i');
    const summary = card.querySelector('.card-summary');
    
    if (content.style.display === 'none') {
        // Expandir
        content.style.display = 'block';
        if (progressSection) progressSection.style.display = 'block';
        summary.classList.add('hidden');
        toggleIcon.className = 'fas fa-chevron-up text-sm';
        card.classList.remove('minimized');
    } else {
        // Minimizar
        content.style.display = 'none';
        if (progressSection) progressSection.style.display = 'none';
        summary.classList.remove('hidden');
        toggleIcon.className = 'fas fa-chevron-down text-sm';
        card.classList.add('minimized');
    }
}

// Función para actualizar el resumen cuando está minimizado
function actualizarResumen(input) {
    const card = input.closest('.accionista-card');
    const nombreResumen = card.querySelector('.nombre-resumen');
    const rfcResumen = card.querySelector('.rfc-resumen');
    const porcentajeResumen = card.querySelector('.porcentaje-resumen');
    
    if (input.classList.contains('nombre-input')) {
        nombreResumen.textContent = input.value || 'Sin nombre';
    } else if (input.classList.contains('rfc-input')) {
        rfcResumen.textContent = input.value || 'Sin RFC';
    } else if (input.classList.contains('porcentaje-input')) {
        porcentajeResumen.textContent = (input.value || '0') + '%';
    }
}

function actualizarTotal() {
    const inputs = document.querySelectorAll('.porcentaje-input');
    let total = 0;
    
    inputs.forEach(input => {
        total += parseFloat(input.value) || 0;
    });
    
    const porcentajeTotal = document.getElementById('porcentaje-total');
    const barraTotal = document.getElementById('barra-total');
    const validacionIcono = document.getElementById('validacion-icono');
    
    porcentajeTotal.textContent = total.toFixed(1) + '%';
    barraTotal.style.width = Math.min(total, 100) + '%';
    
    // Mostrar validación
    if (total === 100) {
        validacionIcono.classList.remove('hidden');
        porcentajeTotal.classList.add('text-green-600');
        porcentajeTotal.classList.remove('text-[#9d2449]');
    } else {
        validacionIcono.classList.add('hidden');
        porcentajeTotal.classList.remove('text-green-600');
        porcentajeTotal.classList.add('text-[#9d2449]');
    }
}

function actualizarNumeracion() {
    const cards = document.querySelectorAll('.accionista-card');
    cards.forEach((card, index) => {
        const titulo = card.querySelector('h3');
        titulo.textContent = `Accionista #${index + 1}`;
    });
}

// Inicializar eventos para el card existente
document.addEventListener('DOMContentLoaded', function() {
    const porcentajeInput = document.querySelector('.porcentaje-input');
    if (porcentajeInput) {
        porcentajeInput.addEventListener('input', function() {
            actualizarPorcentajeIndividual(this);
            actualizarTotal();
            actualizarResumen(this);
        });
    }
    
    // Inicializar eventos para nombre y RFC del card existente
    const nombreInput = document.querySelector('.nombre-input');
    const rfcInput = document.querySelector('.rfc-input');
    
    if (nombreInput) {
        nombreInput.addEventListener('input', function() {
            actualizarResumen(this);
        });
    }
    
    if (rfcInput) {
        rfcInput.addEventListener('input', function() {
            actualizarResumen(this);
        });
    }
});
</script>