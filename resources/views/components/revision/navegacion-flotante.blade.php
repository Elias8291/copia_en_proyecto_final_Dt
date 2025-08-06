@props([
    'position' => 'bottom-right' // bottom-right, bottom-left, top-right, top-left
])

@php
    $positionClasses = match($position) {
        'bottom-right' => 'fixed bottom-6 right-6',
        'bottom-left' => 'fixed bottom-6 left-6',
        'top-right' => 'fixed top-6 right-6',
        'top-left' => 'fixed top-6 left-6',
        default => 'fixed bottom-6 right-6'
    };
@endphp

<div class="{{ $positionClasses }} space-y-2 z-40">
    <button type="button" id="btn-prev" onclick="navigateSection('prev')" 
            class="w-12 h-12 bg-gray-600 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors"
            title="Sección anterior">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
        </svg>
    </button>
    <button type="button" id="btn-next" onclick="navigateSection('next')" 
            class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors"
            title="Siguiente sección">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
</div> 