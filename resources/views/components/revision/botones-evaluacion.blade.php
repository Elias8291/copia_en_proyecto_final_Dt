@props([
    'seccion' => '',
    'titulo' => 'Sección',
    'size' => 'default', // sm, default, lg
    'style' => 'default', // default, compact, inline
    'showIcons' => true,
    'textoAprobar' => 'Aprobar',
    'textoRechazar' => 'Rechazar'
])

@php
    $buttonClasses = match($size) {
        'sm' => 'px-3 py-2 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm'
    };

    $iconClasses = match($size) {
        'sm' => 'w-3 h-3',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4'
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
        class="bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors {{ $buttonClasses }} flex items-center justify-center space-x-2 flex-1"
    >
        @if($showIcons)
            <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        @endif
        <span>{{ $textoAprobar }}</span>
    </button>
    
    <button 
        type="button" 
        onclick="evaluarSeccion('{{ $seccion }}', 'Rechazado')" 
        class="bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors {{ $buttonClasses }} flex items-center justify-center space-x-2 flex-1"
    >
        @if($showIcons)
            <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        @endif
        <span>{{ $textoRechazar }}</span>
    </button>
</div> 