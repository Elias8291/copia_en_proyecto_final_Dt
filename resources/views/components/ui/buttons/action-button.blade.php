@props([
    'tipo' => 'primary', // primary, secondary, success, danger, warning, info
    'size' => 'md', // sm, md, lg
    'url' => null,
    'onclick' => null,
    'disabled' => false,
    'icono' => null,
    'iconoPosicion' => 'left', // left, right, only
    'loading' => false,
    'target' => null,
    'method' => 'GET' // GET, POST, PUT, DELETE
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    // Configurar colores según el tipo
    $colorClasses = match($tipo) {
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 shadow-md hover:shadow-lg',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500 shadow-md hover:shadow-lg',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500 shadow-md hover:shadow-lg',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 shadow-md hover:shadow-lg',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500 shadow-md hover:shadow-lg',
        'info' => 'bg-cyan-600 hover:bg-cyan-700 text-white focus:ring-cyan-500 shadow-md hover:shadow-lg',
        'outline-primary' => 'border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-500',
        'outline-secondary' => 'border border-gray-600 text-gray-600 hover:bg-gray-600 hover:text-white focus:ring-gray-500',
        'text-primary' => 'text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
        'text-danger' => 'text-red-600 hover:bg-red-50 focus:ring-red-500',
        default => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500'
    };
    
    // Configurar tamaños
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm'
    };
    
    // Clases de iconos
    $iconSize = match($size) {
        'sm' => 'w-4 h-4',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4'
    };
    
    $classes = $baseClasses . ' ' . $colorClasses . ' ' . $sizeClasses;
    
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

@if($method === 'POST' || $method === 'PUT' || $method === 'DELETE')
    <form action="{{ $url }}" method="POST" class="inline-block">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif
        <button type="submit" 
                class="{{ $classes }}"
                @if($onclick) onclick="{{ $onclick }}" @endif
                @if($disabled) disabled @endif>
            @if($loading)
                <svg class="animate-spin -ml-1 mr-2 {{ $iconSize }} text-current" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            @elseif($icono && ($iconoPosicion === 'left' || $iconoPosicion === 'only'))
                <span class="{{ $iconSize }} @if($iconoPosicion !== 'only') mr-2 @endif">
                    {!! $icono !!}
                </span>
            @endif
            
            @if($iconoPosicion !== 'only')
                {{ $slot }}
            @endif
            
            @if($icono && $iconoPosicion === 'right')
                <span class="{{ $iconSize }} ml-2">
                    {!! $icono !!}
                </span>
            @endif
        </button>
    </form>
@else
    @if($url)
        <a href="{{ $url }}" 
           class="{{ $classes }}"
           @if($target) target="{{ $target }}" @endif
           @if($onclick) onclick="{{ $onclick }}" @endif>
    @else
        <button type="button" 
                class="{{ $classes }}"
                @if($onclick) onclick="{{ $onclick }}" @endif
                @if($disabled) disabled @endif>
    @endif
    
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 {{ $iconSize }} text-current" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icono && ($iconoPosicion === 'left' || $iconoPosicion === 'only'))
        <span class="{{ $iconSize }} @if($iconoPosicion !== 'only') mr-2 @endif">
            {!! $icono !!}
        </span>
    @endif
    
    @if($iconoPosicion !== 'only')
        {{ $slot }}
    @endif
    
    @if($icono && $iconoPosicion === 'right')
        <span class="{{ $iconSize }} ml-2">
            {!! $icono !!}
        </span>
    @endif
    
    @if($url)
        </a>
    @else
        </button>
    @endif
@endif 