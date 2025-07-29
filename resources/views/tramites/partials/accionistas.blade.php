@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true, 'tramite' => null])

@php
    // Obtener el estado de la sección
    $revisionSeccion = null;
    if ($tramite) {
        $revisionSeccion = \App\Models\RevisionSeccion::where('tramite_id', $tramite->id)
            ->where('seccion', 'accionistas')
            ->first();
    }
    
    $seccionAprobada = $revisionSeccion && $revisionSeccion->aprobado === true;
    $permitirEdicion = $editable && !$seccionAprobada;
    
    // Si es una corrección y tenemos datos del trámite, obtener los accionistas existentes
    $accionistasExistentes = [];
    if ($tramite && $tramite->accionistas) {
        $accionistasExistentes = $tramite->accionistas->toArray();
    }
@endphp

<div class="space-y-6" {{ $attributes }} data-seccion="accionistas">
    <!-- Botón para agregar accionista -->
    @if($permitirEdicion)
    <div class="flex justify-end mb-4">
        <button type="button" 
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9d2449] to-[#8a203f] text-white text-sm font-medium rounded-lg shadow-sm hover:from-[#8a203f] hover:to-[#7a1d37] focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:ring-opacity-50 transition-all duration-200 transform hover:scale-105"
                onclick="agregarAccionista()">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Agregar Accionista
        </button>
    </div>
    @endif

    <!-- Container para los cards de accionistas -->
    <div id="accionistas-container" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        
        @if(!empty($accionistasExistentes))
            @foreach($accionistasExistentes as $index => $accionista)
                <!-- Card de accionista existente -->
                <div class="accionista-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#9d2449]/10 text-[#9d2449]">
                                Accionista #{{ $index + 1 }}
                            </span>
                        </div>
                        @if($permitirEdicion)
                            <button type="button" 
                                    class="text-gray-400 hover:text-red-500 transition-all duration-200 p-2 rounded-lg hover:bg-red-50"
                                    onclick="eliminarAccionista(this)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Campo Nombre -->
                        <div class="form-group sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Nombre Completo
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="accionistas[{{ $index }}][nombre]"
                                       value="{{ $accionista['nombre_completo'] ?? '' }}"
                                       data-validate="required|minLength:3|maxLength:255"
                                       class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50"
                                       placeholder="Nombre completo"
                                       {{ !$permitirEdicion ? 'readonly' : '' }} required>
                            </div>
                        </div>

                        <!-- Campo RFC -->
                        <div class="form-group">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                RFC
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="accionistas[{{ $index }}][rfc]"
                                       value="{{ $accionista['rfc'] ?? '' }}"
                                       data-validate="required|rfc"
                                       class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50 font-mono"
                                       placeholder="AAAA######AAA"
                                       maxlength="13"
                                       pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}"
                                       style="text-transform: uppercase;"
                                       {{ !$permitirEdicion ? 'readonly' : '' }} required>
                            </div>
                        </div>

                        <!-- Campo Porcentaje -->
                        <div class="form-group">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Porcentaje de Participación
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <input type="number" 
                                       name="accionistas[{{ $index }}][porcentaje]"
                                       value="{{ $accionista['porcentaje_participacion'] ?? '' }}"
                                       data-validate="required|min:0|max:100"
                                       class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50"
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       {{ !$permitirEdicion ? 'readonly' : '' }} required>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Card de accionista básico (solo si no hay accionistas existentes) -->
        <div class="accionista-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#9d2449]/10 text-[#9d2449]">
                        Accionista #1
                    </span>
                </div>
                    @if($permitirEdicion)
                <button type="button" 
                        class="text-gray-400 hover:text-red-500 transition-all duration-200 p-2 rounded-lg hover:bg-red-50"
                        onclick="eliminarAccionista(this)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                    @endif
            </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Campo Nombre -->
                <div class="form-group sm:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Nombre Completo
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="accionistas[0][nombre]"
                               data-validate="required|minLength:3|maxLength:255"
                               class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50"
                               placeholder="Nombre completo"
                                   {{ !$permitirEdicion ? 'readonly' : '' }} required>
                    </div>
                </div>

                                    <!-- Campo RFC -->
                <div class="form-group">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                            RFC
                        <span class="text-red-500 ml-1">*</span>
                        </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            </div>
                            <input type="text" 
                                   name="accionistas[0][rfc]"
                                   data-validate="required|rfc"
                               class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50 font-mono"
                                   placeholder="AAAA######AAA"
                                   maxlength="13"
                                   pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}"
                                   style="text-transform: uppercase;"
                                   {{ !$permitirEdicion ? 'readonly' : '' }} required>
                        </div>
                    </div>

                    <!-- Campo Porcentaje -->
                <div class="form-group">
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Porcentaje de Participación
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="number" 
                               name="accionistas[0][porcentaje]"
                               data-validate="required|min:0|max:100"
                                   class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50"
                               placeholder="0.00"
                                   step="0.01"
                               min="0"
                               max="100"
                                   {{ !$permitirEdicion ? 'readonly' : '' }} required>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Información importante -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-blue-900 mb-2">Información Importante</h4>
                <p class="text-sm text-blue-800 leading-relaxed">
                    La suma total de los porcentajes de participación debe ser igual al 100%. 
                    Puede agregar tantos accionistas como sea necesario para completar el total.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let contadorAccionistas = {{ count($accionistasExistentes) }};

function agregarAccionista() {
    const container = document.getElementById('accionistas-container');
    if (!container) return;
    
    const nuevoIndex = contadorAccionistas;
    const nuevoCard = document.createElement('div');
    nuevoCard.className = 'accionista-card bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200';
    
    nuevoCard.innerHTML = `
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#9d2449]/10 text-[#9d2449]">
                    Accionista #${nuevoIndex + 1}
                </span>
            </div>
            <button type="button" 
                    class="text-gray-400 hover:text-red-500 transition-all duration-200 p-2 rounded-lg hover:bg-red-50"
                    onclick="eliminarAccionista(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="form-group sm:col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Nombre Completo
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           name="accionistas[${nuevoIndex}][nombre]"
                           data-validate="required|minLength:3|maxLength:255"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50"
                           placeholder="Nombre completo" required>
                </div>
            </div>

            <div class="form-group">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    RFC
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           name="accionistas[${nuevoIndex}][rfc]"
                           data-validate="required|rfc"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50 font-mono"
                           placeholder="AAAA######AAA"
                           maxlength="13"
                           pattern="[A-Z]{4}[0-9]{6}[A-Z0-9]{3}"
                           style="text-transform: uppercase;" required>
                </div>
            </div>

            <div class="form-group">
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Porcentaje de Participación
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <input type="number" 
                           name="accionistas[${nuevoIndex}][porcentaje]"
                           data-validate="required|min:0|max:100"
                           class="block w-full pl-8 pr-3 py-2 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-[#9d2449] focus:ring-2 focus:ring-[#9d2449]/20 transition-all hover:border-[#9d2449]/50"
                           placeholder="0.00"
                           min="0"
                           max="100"
                           step="0.01" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 text-xs font-medium">%</span>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(nuevoCard);
    
    // Animación de entrada
    nuevoCard.style.opacity = '0';
    nuevoCard.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        nuevoCard.style.transition = 'all 0.3s ease-out';
        nuevoCard.style.opacity = '1';
        nuevoCard.style.transform = 'translateY(0)';
    }, 10);
    
    contadorAccionistas++;
}

function eliminarAccionista(button) {
    const card = button.closest('.accionista-card');
    if (card && document.querySelectorAll('.accionista-card').length > 1) {
        // Animación de salida
        card.style.transition = 'all 0.3s ease-out';
        card.style.opacity = '0';
        card.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            card.remove();
            actualizarNumeracion();
        }, 300);
    }
}

function actualizarNumeracion() {
    const cards = document.querySelectorAll('.accionista-card');
    cards.forEach((card, index) => {
        const badge = card.querySelector('span');
        if (badge) {
            badge.textContent = `Accionista #${index + 1}`;
        }
    });
    
    // Actualizar el contador global
    contadorAccionistas = cards.length;
}
</script>
@endpush

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'accionistas', 'tramite' => $tramite])
@endif