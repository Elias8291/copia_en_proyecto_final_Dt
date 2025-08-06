@props([
    'seccion' => '',
    'titulo' => 'Sección',
    'size' => 'default', // sm, default, lg
    'style' => 'default' // default, compact, inline
])

@php
    $buttonClasses = match($size) {
        'sm' => 'px-3 py-2 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm'
    };

    $containerClasses = match($style) {
        'compact' => 'flex space-x-2',
        'inline' => 'inline-flex space-x-2',
        default => 'flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3'
    };
@endphp

<div class="{{ $containerClasses }}">
    <button 
        type="button" 
        onclick="evaluarSeccion('{{ $seccion }}', 'Aprobado')" 
        class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded transition-colors {{ $buttonClasses }}"
    >
        ✓ Aprobar
    </button>
    
    <button 
        type="button" 
        onclick="evaluarSeccion('{{ $seccion }}', 'Rechazado')" 
        class="bg-red-600 hover:bg-red-700 text-white font-medium rounded transition-colors {{ $buttonClasses }}"
    >
        ✗ Rechazar
    </button>
</div> 