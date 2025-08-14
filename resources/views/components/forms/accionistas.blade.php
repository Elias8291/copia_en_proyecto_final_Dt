@props(['datos' => [], 'editable' => false, 'accionistas' => null])

@php
    // Obtener accionistas del old() si hay errores de validación
    $accionistasOld = old('accionistas');
    $accionistasArray = [];
    
    if ($accionistasOld && is_array($accionistasOld)) {
        $accionistasArray = $accionistasOld;
    } elseif ($accionistas instanceof \App\ViewModels\FormDataViewModel) {
        // Para FormDataViewModel (datos de revisión)
        $accionistasArray = $accionistas->getAccionistas();
    } elseif (!empty($datos)) {
        $accionistasArray = $datos;
    }
    
    // Normalizar datos de accionistas
    $accionistasNormalizados = [];
    foreach ($accionistasArray as $accionista) {
        if (is_array($accionista)) {
            $accionistasNormalizados[] = [
                'nombre' => $accionista['nombre'] ?? '',
                'rfc' => $accionista['rfc'] ?? '',
                'porcentaje_participacion' => $accionista['porcentaje_participacion'] ?? ''
            ];
        } elseif (is_object($accionista)) {
            $accionistasNormalizados[] = [
                'nombre' => $accionista->nombre ?? '',
                'rfc' => $accionista->rfc ?? '',
                'porcentaje_participacion' => $accionista->porcentaje_participacion ?? ''
            ];
        }
    }
    
    // Si no hay accionistas, crear uno por defecto solo en modo editable
    if (empty($accionistasNormalizados) && $editable) {
        $accionistasNormalizados = [
            [
                'nombre' => '',
                'rfc' => '',
                'porcentaje_participacion' => ''
            ]
        ];
    }
    
    $accionistasArray = $accionistasNormalizados;
@endphp

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

    @if($editable)
    <div class="space-y-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Debe tener por lo menos un accionista
                    </h3>
                    <p class="text-sm text-blue-700 mt-1">
                        Complete la información del accionista principal para continuar.
                    </p>
                </div>
            </div>
        </div>

        <div id="accionistas-container" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach($accionistasArray as $index => $accionista)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="bg-gray-50 border-b border-gray-200 rounded-t-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-[#9d2449] rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <h4 class="text-gray-700 font-semibold text-sm">Accionista #{{ $index + 1 }}</h4>
                        </div>
                        @if($index > 0)
                        <button type="button" class="text-gray-500 hover:text-red-600 transition-colors" onclick="this.closest('.bg-white').remove(); actualizarNumeracion();">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                        @endif
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <input type="text" 
                                   name="accionistas[{{ $index }}][nombre]" 
                                   value="{{ $accionista['nombre'] ?? '' }}"
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has("accionistas.{$index}.nombre") ? 'border-red-500' : '' }}">
                        </div>
                        @error("accionistas.{$index}.nombre")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">RFC</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-500"></i>
                            </div>
                            <input type="text" 
                                   name="accionistas[{{ $index }}][rfc]" 
                                   value="{{ $accionista['rfc'] ?? '' }}"
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono {{ $errors->has("accionistas.{$index}.rfc") ? 'border-red-500' : '' }}">
                        </div>
                        @error("accionistas.{$index}.rfc")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje de Participación</label>
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
                                   class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary {{ $errors->has("accionistas.{$index}.porcentaje_participacion") ? 'border-red-500' : '' }}">
                        </div>
                        @error("accionistas.{$index}.porcentaje_participacion")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="button" id="agregarAccionista" 
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i>
                Agregar otro accionista
            </button>
        </div>
        
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-blue-800">Total de Participación:</span>
                <span id="total-porcentaje" class="text-lg font-bold text-blue-900">0%</span>
            </div>
            <div id="porcentaje-status" class="mt-1 text-xs">
                <span id="porcentaje-message" class="text-blue-600">Debe sumar exactamente 100%</span>
            </div>
        </div>
        
        @error('accionistas')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    @else
        @if(!empty($accionistasArray))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($accionistasArray as $index => $accionista)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="bg-gray-50 border-b border-gray-200 rounded-t-xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-[#9d2449] rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <h4 class="text-gray-700 font-semibold text-sm">Accionista #{{ $index + 1 }}</h4>
                        </div>
                    </div>
                    <div class="p-4 space-y-4 grid grid-cols-1 gap-4">
                        <div class="form-group field-container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <input type="text"
                                    value="{{ $accionista['nombre'] ?? '' }}"
                                    class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                                    readonly>
                            </div>
                        </div>
                        
                        <div class="form-group field-container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">RFC</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-500"></i>
                                </div>
                                <input type="text"
                                    value="{{ $accionista['rfc'] ?? '' }}"
                                    class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm font-mono"
                                    readonly>
                            </div>
                        </div>
                        
                        <div class="form-group field-container">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje de Participación</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-percentage text-gray-500"></i>
                                </div>
                                <input type="text"
                                    value="{{ $accionista['porcentaje_participacion'] ?? '' }}%"
                                    class="block w-full pl-10 pr-4 py-2.5 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm"
                                    readonly>
                            </div>
                        </div>
                        
                        @if(isset($accionista['numero_escritura_constitutiva']) && !empty($accionista['numero_escritura_constitutiva']))
                        <div class="col-span-2 mt-4 pt-4 border-t border-gray-200">
                            <h5 class="text-xs font-semibold text-gray-600 mb-3 uppercase tracking-wide">Datos del Instrumento Notarial</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group field-container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Escritura Constitutiva</label>
                                    <input type="text" value="{{ $accionista['numero_escritura_constitutiva'] ?? '' }}" class="block w-full px-3 py-2 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-sm" readonly>
                                </div>
                                <div class="form-group field-container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Constitución</label>
                                    <input type="text" value="{{ $accionista['fecha_constitucion'] ?? '' }}" class="block w-full px-3 py-2 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-sm" readonly>
                                </div>
                                <div class="form-group field-container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Notario</label>
                                    <input type="text" value="{{ $accionista['nombre_notario'] ?? '' }}" class="block w-full px-3 py-2 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-sm" readonly>
                                </div>
                                <div class="form-group field-container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número del Notario</label>
                                    <input type="text" value="{{ $accionista['numero_notario'] ?? '' }}" class="block w-full px-3 py-2 text-gray-900 bg-gray-50 border border-gray-200 rounded-lg shadow-sm text-sm" readonly>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-gray-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800">
                            No hay accionistas registrados
                        </h3>
                        <p class="text-gray-600">
                            No se han registrado accionistas para este trámite.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

@if($editable)
<script>
let accionistaCount = {{ count($accionistasArray ?? []) }};

const agregarAccionistaBtn = document.getElementById('agregarAccionista');
if (agregarAccionistaBtn) {
    agregarAccionistaBtn.addEventListener('click', function() {
        const container = document.getElementById('accionistas-container');
        if (!container) return;
        
        const newAccionista = document.createElement('div');
        newAccionista.className = 'bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200';
        newAccionista.innerHTML = `
            <div class="bg-gray-50 border-b border-gray-200 rounded-t-xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-[#9d2449] rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <h4 class="text-gray-700 font-semibold text-sm">Accionista #${accionistaCount + 1}</h4>
                    </div>
                    <button type="button" class="text-gray-500 hover:text-red-600 transition-colors" onclick="this.closest('.bg-white').remove(); actualizarNumeracion();">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 space-y-4">
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <input type="text" name="accionistas[${accionistaCount}][nombre]" class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </div>
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">RFC</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-500"></i>
                        </div>
                        <input type="text" name="accionistas[${accionistaCount}][rfc]" class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary font-mono">
                    </div>
                </div>
                <div class="form-group field-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Porcentaje de Participación</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-percentage text-gray-500"></i>
                        </div>
                        <input type="number" name="accionistas[${accionistaCount}][porcentaje_participacion]" step="0.01" min="0" max="100" class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </div>
            </div>
        `;
        
        container.appendChild(newAccionista);
        accionistaCount++;
    });
}

function eliminarAccionista(index) {
    if (confirm('¿Eliminar este accionista?')) {
        const element = document.querySelector(`[name="accionistas[${index}][nombre]"]`);
        if (element) {
            element.closest('.bg-white').remove();
            actualizarNumeracion();
        }
    }
}

function actualizarNumeracion() {
    const accionistas = document.querySelectorAll('#accionistas-container .bg-white');
    accionistas.forEach((accionista, index) => {
        const titulo = accionista.querySelector('h4');
        if (titulo) {
            titulo.textContent = `Accionista #${index + 1}`;
        }
    });
    accionistaCount = accionistas.length;
    actualizarTotalPorcentaje();
}

function actualizarTotalPorcentaje() {
    const totalElement = document.getElementById('total-porcentaje');
    const messageElement = document.getElementById('porcentaje-message');
    const statusElement = document.getElementById('porcentaje-status');
    
    if (!totalElement || !messageElement || !statusElement) return;
    
    const porcentajeFields = document.querySelectorAll('[name$="[porcentaje_participacion]"]');
    let total = 0;
    
    porcentajeFields.forEach(field => {
        const value = parseFloat(field.value) || 0;
        total += value;
    });
    
    totalElement.textContent = `${total.toFixed(2)}%`;
    
    if (total === 100) {
        totalElement.className = 'text-lg font-bold text-green-600';
        messageElement.className = 'text-green-600';
        messageElement.textContent = '✅ Total correcto (100%)';
        statusElement.className = 'mt-1 text-xs';
    } else if (total > 100) {
        totalElement.className = 'text-lg font-bold text-red-600';
        messageElement.className = 'text-red-600';
        messageElement.textContent = `❌ Excede 100% (${(total - 100).toFixed(2)}% de más)`;
        statusElement.className = 'mt-1 text-xs';
    } else {
        totalElement.className = 'text-lg font-bold text-orange-600';
        messageElement.className = 'text-orange-600';
        messageElement.textContent = `⚠️ Falta ${(100 - total).toFixed(2)}% para completar`;
        statusElement.className = 'mt-1 text-xs';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const porcentajeFields = document.querySelectorAll('[name$="[porcentaje_participacion]"]');
    porcentajeFields.forEach(field => {
        field.addEventListener('input', actualizarTotalPorcentaje);
        field.addEventListener('blur', actualizarTotalPorcentaje);
    });
    
    actualizarTotalPorcentaje();
    const container = document.getElementById('accionistas-container');
    if (container) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    const newPorcentajeFields = container.querySelectorAll('[name$="[porcentaje_participacion]"]');
                    newPorcentajeFields.forEach(field => {
                        field.addEventListener('input', actualizarTotalPorcentaje);
                        field.addEventListener('blur', actualizarTotalPorcentaje);
                    });
                    actualizarTotalPorcentaje();
                }
            });
        });
        
        observer.observe(container, { childList: true, subtree: true });
    }
});
</script>
@endif 