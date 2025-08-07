@props([
    'seccion' => '',
    'titulo' => '',
    'numeroSeccion' => '',
    'placeholder' => '',
    'size' => 'default' // sm, default, lg
])

@php
    $iconClasses = match($size) {
        'sm' => 'w-3 h-3',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4'
    };
@endphp

<!-- Área de Decisión por Sección -->
<div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="{{ $seccion }}">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-3">
            <h4 class="text-sm font-medium text-gray-700">{{ $titulo }}</h4>
            <span id="estado_{{ $seccion }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                Pendiente
            </span>
        </div>
        <span class="text-xs text-gray-500">{{ $numeroSeccion }}</span>
    </div>
    
    <div class="mb-3">
        <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
        <textarea 
            id="textarea_{{ $seccion }}"
            placeholder="{{ $placeholder }}"
            class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
            rows="2"></textarea>
    </div>
    
    <div class="flex gap-2">
        <button type="button" onclick="evaluarSeccion('{{ $seccion }}', 'Aprobado')" 
                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-sm flex items-center justify-center space-x-2">
            <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Aprobar Sección</span>
        </button>
        
        <button type="button" onclick="evaluarSeccion('{{ $seccion }}', 'Rechazado')" 
                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-sm flex items-center justify-center space-x-2">
            <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>Rechazar Sección</span>
        </button>
    </div>
</div> 