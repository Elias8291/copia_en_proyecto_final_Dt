@props([
    'icono' => 'M19 14l-7 7m0 0l-7-7m0 0l7-7',
    'tipo' => 'normal' // normal, final
])

@if($tipo === 'final')
<!-- Separador Final -->
<div class="flex items-center my-8">
    <div class="flex-grow border-t-4 border-blue-600"></div>
    <div class="mx-4 bg-blue-600 px-6 py-3 rounded-full shadow-lg">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icono }}"></path>
        </svg>
    </div>
    <div class="flex-grow border-t-4 border-blue-600"></div>
</div>
@else
<!-- Separador Normal -->
<div class="flex items-center my-8">
    <div class="flex-grow border-t-3 border-gray-800"></div>
    <div class="mx-4 bg-gray-800 px-4 py-2 rounded-full shadow-lg">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icono }}"></path>
        </svg>
    </div>
    <div class="flex-grow border-t-3 border-gray-800"></div>
</div>
@endif 