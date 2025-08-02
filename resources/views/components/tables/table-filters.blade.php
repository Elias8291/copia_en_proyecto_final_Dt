@props([
    'filters' => [],
    'searchPlaceholder' => 'Buscar...',
    'showSearch' => true,
    'showFilters' => true,
    'dataCount' => 0
])

@if($showSearch || $showFilters)
<div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/70">
    <div class="flex flex-wrap items-center gap-3">
        @if($showSearch)
        <div class="relative flex-1 min-w-[200px] group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0 1 14 0z" />
                </svg>
            </div>
            <input type="text" id="search-filter"
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg bg-white/80 backdrop-blur-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all duration-200 hover:bg-white hover:shadow-sm"
                placeholder="{{ $searchPlaceholder }}">
        </div>
        @endif

        @if($showFilters)
            @foreach($filters as $filter)
                @if($filter['type'] === 'select')
                <div class="relative">
                    <select id="{{ $filter['id'] }}-filter"
                        class="appearance-none bg-white border border-gray-300 rounded-lg pl-8 pr-6 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all duration-200 hover:shadow-sm min-w-[90px]">
                        <option value="">{{ $filter['placeholder'] ?? 'Seleccionar' }}</option>
                        @foreach($filter['options'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                @elseif($filter['type'] === 'input')
                <div class="relative">
                    <input type="text" id="{{ $filter['id'] }}-filter"
                        class="bg-white border border-gray-300 rounded-lg pl-3 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all duration-200 hover:shadow-sm min-w-[80px]"
                        placeholder="{{ $filter['placeholder'] }}">
                </div>
                @endif
            @endforeach

            <button id="clear-filters"
                class="inline-flex items-center px-2.5 py-2.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all duration-200 hover:shadow-sm">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Limpiar
            </button>
        @endif

        <div class="ml-auto">
            <div id="results-count"
                class="text-xs text-gray-600 font-medium bg-white/80 backdrop-blur-sm rounded-full px-2.5 py-1 border border-gray-200/50">
                {{ $dataCount }} elementos
            </div>
        </div>
    </div>
</div>
@endif