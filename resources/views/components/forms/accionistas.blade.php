@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }}>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Accionistas</h3>
            <p class="text-sm text-gray-500">Información de los accionistas</p>
        </div>
    </div>

    @if(empty($datos))
        @if($editable)
        <div class="space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            No hay accionistas registrados
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Agregue al menos un accionista para continuar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3">Agregar Accionista</h4>
                
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <input type="text"
                                name="accionistas[0][nombre]"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                placeholder="Ingrese nombre completo">
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            RFC <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-500"></i>
                            </div>
                            <input type="text"
                                name="accionistas[0][rfc]"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono"
                                placeholder="Ingrese RFC">
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Porcentaje de Participación <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-percentage text-gray-500"></i>
                            </div>
                            <input type="number"
                                name="accionistas[0][porcentaje_participacion]"
                                step="0.01"
                                min="0"
                                max="100"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" id="agregarAccionista" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar otro accionista
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        No hay accionistas registrados
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>No se han registrado accionistas para este trámite.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="space-y-4">
            @foreach($datos as $index => $accionista)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-800">
                        Accionista #{{ $index + 1 }}
                    </h4>
                    @if($editable)
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="eliminarAccionista({{ $index }})">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre Completo
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <input type="text"
                                name="accionistas[{{ $index }}][nombre]"
                                value="{{ $accionista['nombre'] ?? '' }}"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                {{ !$editable ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            RFC
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-500"></i>
                            </div>
                            <input type="text"
                                name="accionistas[{{ $index }}][rfc]"
                                value="{{ $accionista['rfc'] ?? '' }}"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono"
                                {{ !$editable ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Porcentaje de Participación
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-percentage text-gray-500"></i>
                            </div>
                            <input type="number"
                                name="accionistas[{{ $index }}][porcentaje_participacion]"
                                value="{{ $accionista['porcentaje_participacion'] ?? '' }}"
                                step="0.01"
                                min="0"
                                max="100"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                {{ !$editable ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if($editable)
            <div class="mt-4">
                <button type="button" id="agregarAccionista" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar otro accionista
                </button>
            </div>
            @endif
        </div>
    @endif
</div>

@if($editable)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let accionistaCount = {{ empty($datos) ? 1 : count($datos) }};
    
    document.getElementById('agregarAccionista').addEventListener('click', function() {
        const container = document.querySelector('.space-y-4');
        const newAccionista = document.createElement('div');
        newAccionista.className = 'border border-gray-200 rounded-lg p-4';
        newAccionista.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-gray-800">
                    Accionista #${accionistaCount + 1}
                </h4>
                <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre Completo <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <input type="text"
                            name="accionistas[${accionistaCount}][nombre]"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            placeholder="Ingrese nombre completo">
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        RFC <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-500"></i>
                        </div>
                        <input type="text"
                            name="accionistas[${accionistaCount}][rfc]"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono"
                            placeholder="Ingrese RFC">
                    </div>
                </div>

                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Porcentaje de Participación <span class="text-red-500">*</span>
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-percentage text-gray-500"></i>
                        </div>
                        <input type="number"
                            name="accionistas[${accionistaCount}][porcentaje_participacion]"
                            step="0.01"
                            min="0"
                            max="100"
                            class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            placeholder="0.00">
                    </div>
                </div>
            </div>
        `;
        
        container.insertBefore(newAccionista, document.getElementById('agregarAccionista').parentElement);
        accionistaCount++;
    });
});

function eliminarAccionista(index) {
    if (confirm('¿Está seguro de que desea eliminar este accionista?')) {
        const accionistaElement = document.querySelector(`[name="accionistas[${index}][nombre]"]`).closest('.border');
        accionistaElement.remove();
    }
}
</script>
@endif 