@props([
    'title' => '',
    'description' => '',
    'icon' => '',
    'gradient' => 'from-[#9D2449] to-[#B91C1C]',
    'isActive' => false,
    'actionText' => 'Comenzar',
    'actionUrl' => '#'
])

<div class="group relative bg-white rounded-2xl shadow-lg border border-gray-200/70 overflow-hidden transition-all duration-300 {{ $isActive ? 'hover:shadow-xl hover:scale-[1.02]' : 'opacity-60' }}">
    
    @if(!$isActive)
        <div class="absolute inset-0 bg-gray-50/80 backdrop-blur-sm flex items-center justify-center z-10 rounded-2xl p-4">
            <div>
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-4.5V7a4 4 0 10-8 0v5.5"></path>
                    </svg>
                </div>
                <p class="text-gray-600 font-semibold text-sm text-center">No Disponible</p>
            </div>
        </div>
    @endif

    <div class="p-6 flex flex-col h-full">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br {{ $gradient }} rounded-xl flex items-center justify-center shadow-lg">
                    {!! $icon !!}
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-600 border border-gray-200/80' }}">
                    {{ $isActive ? 'Disponible' : 'Bloqueado' }}
                </span>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $title }}</h3>
            <p class="text-gray-600 text-sm leading-relaxed">
                {{ $description }}
            </p>
        </div>

        <div class="mt-auto pt-6">
            @if($isActive)
                <a href="{{ $actionUrl }}" 
                   class="w-full bg-gradient-to-r {{ $gradient }} hover:from-[#8a1f40] hover:to-[#a51d1d] text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center group-hover:shadow-lg">
                    <span>{{ $actionText }}</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <button disabled
                        class="w-full bg-gray-200 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
                    No Disponible
                </button>
            @endif
        </div>
    </div>
</div> 