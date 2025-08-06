@props([
    'titulo' => '',
    'descripcion' => null,
    'icono' => null,
    'colorIcono' => 'blue', // blue, green, red, yellow, gray, purple
    'mostrarLinea' => true,
    'size' => 'md', // sm, md, lg
    'acciones' => null // slot para botones de acción
])

@php
    $colorClasses = match($colorIcono) {
        'blue' => 'from-blue-600 via-blue-700 to-blue-800',
        'green' => 'from-green-600 via-green-700 to-green-800',
        'red' => 'from-red-600 via-red-700 to-red-800',
        'yellow' => 'from-yellow-500 via-yellow-600 to-yellow-700',
        'gray' => 'from-gray-600 via-gray-700 to-gray-800',
        'purple' => 'from-purple-600 via-purple-700 to-purple-800',
        'indigo' => 'from-indigo-600 via-indigo-700 to-indigo-800',
        default => 'from-blue-600 via-blue-700 to-blue-800'
    };
    
    $titleSizes = match($size) {
        'sm' => 'text-lg font-semibold',
        'md' => 'text-xl md:text-2xl font-bold',
        'lg' => 'text-2xl md:text-3xl font-bold',
        default => 'text-xl md:text-2xl font-bold'
    };
    
    $iconSizes = match($size) {
        'sm' => 'w-5 h-5 p-2',
        'md' => 'w-6 h-6 p-3',
        'lg' => 'w-8 h-8 p-4',
        default => 'w-6 h-6 p-3'
    };
    
    $descriptionSizes = match($size) {
        'sm' => 'text-xs text-gray-500',
        'md' => 'text-sm md:text-base text-gray-500',
        'lg' => 'text-base md:text-lg text-gray-500',
        default => 'text-sm md:text-base text-gray-500'
    };
@endphp

<div class="bg-white rounded-2xl shadow-xl border border-gray-200/70 mb-8">
    <div class="p-6 @if($mostrarLinea) border-b border-gray-200/70 @endif">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center space-x-4">
                @if($icono)
                <div class="bg-gradient-to-br {{ $colorClasses }} rounded-xl {{ $iconSizes }} shadow-md flex items-center justify-center">
                    <div class="text-white">
                        {!! $icono !!}
                    </div>
                </div>
                @endif
                
                <div>
                    <h1 class="{{ $titleSizes }} text-gray-800">{{ $titulo }}</h1>
                    @if($descripcion)
                    <p class="{{ $descriptionSizes }}">{{ $descripcion }}</p>
                    @endif
                </div>
            </div>
            
            @if($acciones || isset($actions))
            <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                @if(isset($actions))
                    {{ $actions }}
                @endif
                @if($acciones)
                    {!! $acciones !!}
                @endif
            </div>
            @endif
        </div>
    </div>
    
    @if($slot->isNotEmpty())
    <div class="p-6">
        {{ $slot }}
    </div>
    @endif
</div> 