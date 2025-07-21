<div class="bg-white px-6 py-4 border-t border-gray-200">
    <div class="flex items-center justify-between">
        <div class="flex items-center text-sm text-gray-700">
            <span>Mostrando</span>
            <span class="mx-1 font-medium text-gray-900">{{ $archivos->firstItem() ?? 0 }}</span>
            <span>a</span>
            <span class="mx-1 font-medium text-gray-900">{{ $archivos->lastItem() ?? 0 }}</span>
            <span>de</span>
            <span class="mx-1 font-medium text-gray-900">{{ $archivos->total() }}</span>
            <span>archivos</span>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Botón Anterior -->
            @if ($archivos->onFirstPage())
                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </span>
            @else
                <a href="{{ $archivos->previousPageUrl() }}" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:ring-offset-2 transition-all duration-200">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </a>
            @endif

            <!-- Números de página -->
            <div class="hidden sm:flex items-center space-x-1">
                @foreach ($archivos->getUrlRange(1, $archivos->lastPage()) as $page => $url)
                    @if ($page == $archivos->currentPage())
                        <span class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] border border-[#B4325E] rounded-lg">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" 
                           class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:ring-offset-2 transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Información de página en móvil -->
            <div class="sm:hidden flex items-center">
                <span class="text-sm text-gray-700">
                    Página <span class="font-medium">{{ $archivos->currentPage() }}</span> de <span class="font-medium">{{ $archivos->lastPage() }}</span>
                </span>
            </div>

            <!-- Botón Siguiente -->
            @if ($archivos->hasMorePages())
                <a href="{{ $archivos->nextPageUrl() }}" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:ring-offset-2 transition-all duration-200">
                    Siguiente
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed">
                    Siguiente
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </div>
</div> 