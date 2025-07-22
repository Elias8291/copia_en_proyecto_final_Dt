@if ($roles->hasPages())
    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/70">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Información de resultados -->
            <div class="text-sm text-gray-700">
                <span class="font-medium">{{ $roles->firstItem() ?? 0 }}</span>
                -
                <span class="font-medium">{{ $roles->lastItem() ?? 0 }}</span>
                de
                <span class="font-medium">{{ $roles->total() }}</span>
                roles
            </div>

            <!-- Enlaces de paginación -->
            <div class="flex items-center space-x-2">
                {{-- Botón Anterior --}}
                @if ($roles->onFirstPage())
                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Anterior
                    </span>
                @else
                    <a href="{{ $roles->previousPageUrl() }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Anterior
                    </a>
                @endif

                {{-- Números de página --}}
                <div class="hidden sm:flex items-center space-x-1">
                    @foreach ($roles->getUrlRange(1, $roles->lastPage()) as $page => $url)
                        @if ($page == $roles->currentPage())
                            <span class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="inline-flex items-center justify-center w-10 h-10 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Botón Siguiente --}}
                @if ($roles->hasMorePages())
                    <a href="{{ $roles->nextPageUrl() }}" 
                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200">
                        Siguiente
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-lg cursor-not-allowed">
                        Siguiente
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </div>
@endif