@props([
    'title' => '',
    'description' => '',
    'icon' => '',
    'gradient' => 'bg-blue-600',
    'isActive' => false,
    'isPending' => false,
    'actionText' => 'Comenzar',
    'actionUrl' => '#',
    'showPendingOverlay' => false,
    'formData' => null
])

<div class="group relative bg-white rounded-3xl border-2 border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 {{ $isActive ? 'hover:scale-[1.02] hover:border-blue-300' : 'opacity-75' }} overflow-hidden">
    
    {{-- Overlay para tarjetas no disponibles --}}
    @if(!$isActive && !$isPending)
        <div class="absolute inset-0 bg-white/95 flex items-center justify-center z-10 rounded-3xl">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-4.5V7a4 4 0 10-8 0v5.5"></path>
                    </svg>
                </div>
                <p class="text-gray-700 font-semibold text-base mb-2">No Disponible</p>
                @if(isset($disabledReason))
                    <p class="text-gray-500 text-sm max-w-48 leading-relaxed">{{ $disabledReason }}</p>
                @endif
            </div>
        </div>
    @endif

    {{-- Overlay para trámites pendientes --}}
    @if($showPendingOverlay)
        <div class="absolute inset-0 bg-white/95 flex items-center justify-center z-10 rounded-3xl">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-blue-800 font-semibold text-base mb-2">Trámite en Proceso</p>
                @if(isset($pendingReason))
                    <p class="text-blue-600 text-sm max-w-48 leading-relaxed">{{ $pendingReason }}</p>
                @endif
            </div>
        </div>
    @endif

    <div class="p-6 flex flex-col h-full">
        {{-- Header con icono y estado --}}
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 {{ $gradient }} rounded-2xl flex items-center justify-center shadow-xl border-2 border-white">
                {!! $icon !!}
            </div>
            <div class="flex flex-col items-end">
                <span class="px-3 py-1 rounded-full text-xs font-semibold border
                    {{ $isPending && $isActive ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                    {{ $isActive && !$isPending ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                    {{ !$isActive && !$isPending ? 'bg-gray-50 text-gray-600 border-gray-200' : '' }}">
                    {{ $isPending && $isActive ? 'Ver Estado' : ($isActive ? 'Disponible' : 'Bloqueado') }}
                </span>
            </div>
        </div>

        {{-- Contenido principal --}}
        <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-900 mb-3 leading-tight">{{ $title }}</h3>
            <p class="text-gray-600 text-sm leading-relaxed">
                {!! $description !!}
            </p>
        </div>

        {{-- Botón de acción --}}
        <div class="mt-6">
            @if($isActive)
                @if($formData)
                    <form action="{{ $actionUrl }}" method="POST">
                        @csrf
                        @foreach($formData as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <button type="submit" 
                                class="w-full group/btn relative overflow-hidden rounded-2xl font-semibold py-3 px-4 transition-all duration-300 flex items-center justify-center
                                {{ $isPending ? 'bg-blue-600 hover:bg-blue-700' : 'bg-blue-600 hover:bg-blue-700' }} 
                                text-white shadow-lg hover:shadow-xl hover:scale-[1.02] border-2 border-blue-500">
                            <span class="relative z-10 text-sm">{{ $actionText }}</span>
                            <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </form>
                @else
                    <a href="{{ $actionUrl }}" 
                       class="w-full group/btn relative overflow-hidden rounded-2xl font-semibold py-3 px-4 transition-all duration-300 flex items-center justify-center
                       {{ $isPending ? 'bg-blue-600 hover:bg-blue-700' : 'bg-blue-600 hover:bg-blue-700' }} 
                       text-white shadow-lg hover:shadow-xl hover:scale-[1.02] border-2 border-blue-500">
                        <span class="relative z-10 text-sm">{{ $actionText }}</span>
                        <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                    </a>
                @endif
            @else
                <button disabled
                        class="w-full bg-gray-100 text-gray-400 font-semibold py-3 px-4 rounded-2xl cursor-not-allowed border-2 border-gray-300 text-sm">
                    No Disponible
                </button>
            @endif
        </div>
    </div>
</div> 