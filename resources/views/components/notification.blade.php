@props([
    'tipo' => 'info', // success, error, warning, info
    'titulo' => null,
    'mensaje' => '',
    'dismissible' => true,
    'icono' => null,
    'autoHide' => false,
    'duration' => 5000 // duración en milisegundos para auto-hide
])

@php
    $colorClasses = match($tipo) {
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        default => 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    $iconClasses = match($tipo) {
        'success' => 'text-green-400',
        'error' => 'text-red-400',
        'warning' => 'text-yellow-400',
        'info' => 'text-blue-400',
        default => 'text-blue-400'
    };
    
    $defaultIcons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
    ];
    
    $iconoFinal = $icono ?: $defaultIcons[$tipo];
    $uniqueId = 'notification-' . uniqid();
@endphp

<div id="{{ $uniqueId }}" 
     class="rounded-lg border p-4 {{ $colorClasses }} shadow-sm transition-all duration-300"
     @if($autoHide)
     x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, {{ $duration }})"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     @endif>
    
    <div class="flex">
        <!-- Icono -->
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconoFinal !!}
            </svg>
        </div>
        
        <!-- Contenido -->
        <div class="ml-3 flex-1">
            @if($titulo)
            <h3 class="text-sm font-medium">{{ $titulo }}</h3>
            @endif
            
            @if($mensaje)
            <div class="@if($titulo) mt-2 @endif text-sm">
                {{ $mensaje }}
            </div>
            @endif
            
            @if($slot->isNotEmpty())
            <div class="@if($titulo || $mensaje) mt-2 @endif text-sm">
                {{ $slot }}
            </div>
            @endif
        </div>
        
        <!-- Botón de cerrar -->
        @if($dismissible)
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" 
                        onclick="document.getElementById('{{ $uniqueId }}').remove()"
                        class="inline-flex rounded-md p-1.5 hover:bg-black hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent focus:ring-gray-500">
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5 {{ $iconClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>
</div> 