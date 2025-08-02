@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="w-full">
    <!-- Mobile Pagination -->
    <div class="flex items-center justify-between sm:hidden bg-white rounded-lg border border-gray-200 shadow-sm p-3">
        <div class="flex-1">
            <p class="text-xs text-gray-600 font-sans">
                Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-[#9d2449] hover:text-[#9d2449] transition-all duration-200 shadow-sm">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-[#9d2449] hover:text-[#9d2449] transition-all duration-200 shadow-sm">
                    Siguiente
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-gray-100 text-gray-400 cursor-not-allowed">
                    Siguiente
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            @endif
        </div>
    </div>

    <!-- Desktop Pagination -->
    <div class="hidden sm:flex sm:items-center sm:justify-center">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <ul class="flex items-center divide-x divide-gray-200">
                {{-- Anterior --}}
                <li>
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center px-4 py-2.5 text-sm font-medium bg-gray-50 text-gray-400 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Anterior
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 hover:text-[#9d2449] transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Anterior
                        </a>
                    @endif
                </li>

                {{-- Páginas --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li>
                            <span class="inline-flex items-center px-4 py-2.5 text-sm font-medium bg-white text-gray-500 cursor-not-allowed">
                                {{ $element }}
                            </span>
                        </li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <li>
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold bg-[#9d2449] text-white">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 hover:text-[#9d2449] transition-all duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endforeach

                {{-- Siguiente --}}
                <li>
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium bg-white text-gray-700 hover:bg-gray-50 hover:text-[#9d2449] transition-all duration-200">
                            Siguiente
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-4 py-2.5 text-sm font-medium bg-gray-50 text-gray-400 cursor-not-allowed">
                            Siguiente
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>
@endif
