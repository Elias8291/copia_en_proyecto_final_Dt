@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div
                class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                <p class="text-sm text-gray-500 mt-1">Información de los accionistas de la empresa</p>
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
    <div id="accionistas-container" class="space-y-6">
        
        <!-- Card de accionista básico -->
        <div class="accionista-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-sm">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800">Accionista #1</h3>
                    </div>
                </div>
                <button type="button" 
                        class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 rounded-full hover:bg-red-50"
                        onclick="eliminarAccionista(this)">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Campo Nombre -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-[#9d2449] mr-2"></i>
                        Nombre Completo
                    </label>
                    <input type="text" 
                           name="accionistas[0][nombre]"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                           placeholder="Ingrese el nombre completo"
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md uppercase"
                           placeholder="AAAA######AAA"
                           maxlength="13"
                           pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}"
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
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                               placeholder="0.00"
                               min="0"
                               max="100"
                               step="0.01"
                               {{ !$editable ? 'readonly' : '' }}>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm font-medium">%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Información importante -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-center space-x-3">
                <i class="fas fa-info-circle text-blue-600"></i>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-blue-900">Información Importante</h4>
                    <p class="text-sm text-blue-800 mt-1">
                        La suma total de los porcentajes de participación debe ser igual al 100%.
                        Puede agregar tantos accionistas como sea necesario.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let contadorAccionistas = 1;

function agregarAccionista() {
    const container = document.getElementById('accionistas-container');
    if (!container) return;
    
    const nuevoIndex = contadorAccionistas;
    const nuevoCard = document.createElement('div');
    nuevoCard.className = 'accionista-card bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-6 shadow-sm';
    
    nuevoCard.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-sm">
                    <i class="fas fa-user text-sm"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-800">Accionista #${nuevoIndex + 1}</h3>
                </div>
            </div>
            <button type="button" 
                    class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 rounded-full hover:bg-red-50"
                    onclick="eliminarAccionista(this)">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <div class="space-y-4">
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-[#9d2449] mr-2"></i>
                    Nombre Completo
                </label>
                <input type="text" 
                       name="accionistas[${nuevoIndex}][nombre]"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                       placeholder="Ingrese el nombre completo">
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-id-card text-[#9d2449] mr-2"></i>
                    RFC
                </label>
                <input type="text" 
                       name="accionistas[${nuevoIndex}][rfc]"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md uppercase"
                       placeholder="AAAA######AAA"
                       maxlength="13"
                       pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}">
            </div>

            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-percentage text-[#9d2449] mr-2"></i>
                    Porcentaje de Participación
                </label>
                <div class="relative">
                    <input type="number" 
                           name="accionistas[${nuevoIndex}][porcentaje]"
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                           placeholder="0.00"
                           min="0"
                           max="100"
                           step="0.01">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-sm font-medium">%</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(nuevoCard);
    contadorAccionistas++;
}

function eliminarAccionista(button) {
    const card = button.closest('.accionista-card');
    if (card && document.querySelectorAll('.accionista-card').length > 1) {
        card.remove();
        actualizarNumeracion();
    }
}

function actualizarNumeracion() {
    const cards = document.querySelectorAll('.accionista-card');
    cards.forEach((card, index) => {
        const titulo = card.querySelector('h3');
        if (titulo) {
            titulo.textContent = `Accionista #${index + 1}`;
        }
    });
}
</script>