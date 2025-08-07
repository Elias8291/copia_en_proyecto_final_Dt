@props([
    'textoConforme' => 'Domicilio Conforme',
    'textoNoConforme' => 'Domicilio No Conforme',
    'size' => 'default' // sm, default, lg
])

@php
    $iconClasses = match($size) {
        'sm' => 'w-3 h-3',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4'
    };
@endphp

<div class="flex gap-2">
    <button type="button" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-sm flex items-center justify-center space-x-2">
        <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ $textoConforme }}</span>
    </button>
    
    <button type="button" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-sm flex items-center justify-center space-x-2">
        <svg class="{{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span>{{ $textoNoConforme }}</span>
    </button>
</div> 