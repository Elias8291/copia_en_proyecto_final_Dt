@if($users->hasPages())
<div class="flex flex-col sm:flex-row items-center justify-between gap-4">
    <!-- Info de resultados -->
    <div class="flex items-center text-sm text-gray-700">
        <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
        <span class="mx-2">-</span>
        <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
        <span class="mx-2">de</span>
        <span class="font-medium">{{ $users->total() }}</span>
        <span class="ml-2">usuarios</span>
    </div>

    <!-- Enlaces de paginación -->
    <div class="flex items-center space-x-1">
        {{-- Botón Anterior --}}
        @if ($users->onFirstPage())
            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $users->previousPageUrl() }}" 
               class="pagination-link relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-[#B4325E] transition-all duration-200 rounded-l-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Elementos de paginación --}}
        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            @if ($page == $users->currentPage())
                <span aria-current="page">
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#93264B] border border-[#B4325E] cursor-default shadow-md">
                        {{ $page }}
                    </span>
                </span>
            @else
                <a href="{{ $url }}" 
                   class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-[#B4325E] transition-all duration-200">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Botón Siguiente --}}
        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" 
               class="pagination-link relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-[#B4325E] transition-all duration-200 rounded-r-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
</div>
@endif 