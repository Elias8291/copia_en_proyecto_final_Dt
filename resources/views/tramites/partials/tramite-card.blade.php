<div class="group relative bg-white rounded-2xl shadow-lg border border-gray-200/70 overflow-hidden transition-all duration-300 @if($disponible) hover:shadow-xl hover:-translate-y-1 @else opacity-60 @endif">
    @if(!$disponible)
        <div class="absolute inset-0 bg-gray-50/80 backdrop-blur-sm flex items-center justify-center z-10 rounded-2xl p-4">
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-4.5V7a4 4 0 10-8 0v5.5"></path>
                    </svg>
                </div>
                <p class="text-gray-600 font-semibold text-sm">No Disponible</p>
            </div>
        </div>
    @endif

    <div class="p-6 flex flex-col h-full">
        <div class="flex-shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br {{ $colorFrom }} {{ $colorTo }} rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $icon !!}
                    </svg>
                </div>
                @if($disponible)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200/80">Disponible</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200/80">Bloqueado</span>
                @endif
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $titulo }}</h3>
            <p class="text-gray-600 text-sm leading-relaxed">{{ $descripcion }}</p>
        </div>

        <div class="mt-auto pt-6">
            @if($disponible)
                <a href="{{ route('tramites.constancia', $tipo) }}" 
                   class="w-full bg-gradient-to-r {{ $colorFrom }} {{ $colorTo }} hover:brightness-110 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center group-hover:scale-105 transform">
                    <span>{{ ucfirst($tipo) }}</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            @else
                <button disabled class="w-full bg-gray-200 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
                    No Disponible
                </button>
            @endif
        </div>
    </div>
</div>
