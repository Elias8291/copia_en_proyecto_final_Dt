@props([
    'title' => 'Lista de Datos',
    'description' => 'Administra y revisa los datos',
    'data' => [],
    'columns' => [],
    'filters' => [],
    'searchPlaceholder' => 'Buscar...',
    'showSearch' => true,
    'showFilters' => true,
    'showActions' => true,
    'actions' => []
])

<div class="w-full">
    <!-- Header simplificado -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200/70 mb-8">
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800">{{ $title }}</h1>
                        <p class="text-sm md:text-base text-gray-500">{{ $description }}</p>
                    </div>
                </div>
                
                @if(isset($actions['create']))
                <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                    <button class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-primary to-primary-dark rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ $actions['create']['label'] ?? 'Nuevo' }}
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($showSearch || $showFilters)
    <!-- Filtros elegantes y compactos -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="p-4 bg-gradient-to-r from-gray-50/80 to-gray-100/60">
            <!-- Filtros en línea compactos -->
            <div class="flex flex-wrap items-center gap-3">
                @if($showSearch)
                <!-- Búsqueda principal -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0 1 14 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-filter"
                        class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white/90 backdrop-blur-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm"
                        placeholder="{{ $searchPlaceholder }}">
                </div>
                @endif

                @if($showFilters)
                    @foreach($filters as $filter)
                        @if($filter['type'] === 'select')
                        <!-- Filtro Select -->
                        <div class="relative">
                            <select id="{{ $filter['id'] }}-filter"
                                class="appearance-none bg-white border border-gray-200 rounded-lg pl-9 pr-8 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm min-w-[130px]">
                                <option value="">{{ $filter['placeholder'] ?? 'Seleccionar' }}</option>
                                @foreach($filter['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @elseif($filter['type'] === 'input')
                        <!-- Filtro Input -->
                        <div class="relative">
                            <input type="text" id="{{ $filter['id'] }}-filter"
                                class="bg-white border border-gray-200 rounded-lg pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary/50 transition-all duration-200 hover:bg-white hover:shadow-sm min-w-[100px]"
                                placeholder="{{ $filter['placeholder'] }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <!-- Botón limpiar -->
                    <button id="clear-filters"
                        class="inline-flex items-center px-3 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-200 hover:shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar
                    </button>
                @endif

                <!-- Contador de resultados -->
                <div class="ml-auto">
                    <div id="results-count"
                        class="text-xs text-gray-500 font-medium bg-white/80 backdrop-blur-sm rounded-full px-3 py-1.5 border border-gray-200/60">
                        {{ count($data) }} elementos
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla simplificada -->
    <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Header de tabla -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ $title }}</h2>
            </div>
        </div>

        <!-- Contenido de tabla -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="hidden lg:table-header-group bg-gray-50">
                    <tr>
                        @foreach($columns as $column)
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $column['label'] }}</th>
                        @endforeach
                        @if($showActions)
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $item)
                        <!-- Desktop -->
                        <tr class="hidden lg:table-row hover:bg-gray-50/50 transition-all duration-200">
                            @foreach($columns as $column)
                                <td class="px-6 py-4">
                                    @if($column['type'] === 'avatar')
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($item->{$column['field']} ?? 'U', 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $item->{$column['field']} ?? 'N/A' }}</div>
                                                @if(isset($column['subfield']))
                                                    <div class="text-xs text-gray-500">{{ $column['subfield_label'] ?? '' }}: {{ $item->{$column['subfield']} ?? 'N/A' }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($column['type'] === 'badge')
                                        @php
                                            $colors = $column['colors'] ?? [];
                                            $value = $item->{$column['field']};
                                            $color = $colors[$value] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $color }}">{{ $value }}</span>
                                    @elseif($column['type'] === 'date')
                                        <span class="text-sm text-gray-900 font-medium">
                                            {{ $item->{$column['field']} ? $item->{$column['field']}->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-900">{{ $item->{$column['field']} ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            @if($showActions)
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @foreach($actions as $actionKey => $action)
                                            @if($actionKey !== 'create')
                                                <button class="p-2 {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                                        title="{{ $action['label'] ?? $actionKey }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                                    </svg>
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            @endif
                        </tr>

                        <!-- Móvil -->
                        <div class="lg:hidden bg-white border border-gray-200 rounded-xl p-6 mb-4 shadow-sm hover:shadow-lg transition-all duration-300">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($item->{$columns[0]['field']} ?? 'U', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div class="text-base font-semibold text-gray-900">{{ $item->{$columns[0]['field']} ?? 'N/A' }}</div>
                                        @if(isset($columns[0]['subfield']))
                                            <div class="text-sm text-gray-500">{{ $columns[0]['subfield_label'] ?? '' }}: {{ $item->{$columns[0]['subfield']} ?? 'N/A' }}</div>
                                        @endif
                                    </div>
                                </div>
                                @if($showActions)
                                    <div class="flex items-center space-x-1">
                                        @foreach($actions as $actionKey => $action)
                                            @if($actionKey !== 'create')
                                                <button class="p-2 {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                                        title="{{ $action['label'] ?? $actionKey }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                                    </svg>
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                @foreach(array_slice($columns, 1, 2) as $column)
                                    <div>
                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2 block">{{ $column['label'] }}</span>
                                        @if($column['type'] === 'badge')
                                            @php
                                                $colors = $column['colors'] ?? [];
                                                $value = $item->{$column['field']};
                                                $color = $colors[$value] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                            @endphp
                                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold border {{ $color }}">{{ $value }}</span>
                                        @else
                                            <span class="text-sm text-gray-900">{{ $item->{$column['field']} ?? 'N/A' }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            @if(count($columns) > 3)
                                <div class="flex items-center justify-between">
                                    @foreach(array_slice($columns, 3, 1) as $column)
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">{{ $column['label'] }}:</span> 
                                            @if($column['type'] === 'date')
                                                {{ $item->{$column['field']} ? $item->{$column['field']}->format('d/m/Y') : 'N/A' }}
                                            @else
                                                {{ $item->{$column['field']} ?? 'N/A' }}
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <tr class="lg:table-row">
                            <td colspan="{{ count($columns) + ($showActions ? 1 : 0) }}" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mb-6">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No hay datos</h3>
                                    <p class="text-gray-500 text-base mb-6 max-w-md">No se encontraron registros.</p>
                                    @if(isset($actions['create']))
                                        <button class="px-6 py-3 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            {{ $actions['create']['label'] ?? 'Crear nuevo' }}
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación simplificada -->
        @if(count($data) > 0)
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="text-sm text-gray-700 mb-4 lg:mb-0">
                        Mostrando <span class="font-semibold">{{ count($data) }}</span> de <span class="font-semibold">{{ count($data) }}</span> resultados
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Anterior
                        </button>
                        <span class="px-4 py-2 text-sm font-semibold text-primary bg-primary/10 border border-primary/20 rounded-lg">1</span>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                            Siguiente
                            <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if($showSearch || $showFilters)
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para filtrar la tabla
    function filterTable() {
        const searchTerm = document.getElementById('search-filter')?.value.toLowerCase() || '';
        const filterValues = {};
        
        // Obtener valores de filtros
        @if($showFilters)
            @foreach($filters as $filter)
                const {{ $filter['id'] }}Filter = document.getElementById('{{ $filter['id'] }}-filter')?.value.toLowerCase() || '';
                filterValues['{{ $filter['id'] }}'] = {{ $filter['id'] }}Filter;
            @endforeach
        @endif
        
        const rows = document.querySelectorAll('tbody tr, tbody div.lg\\:hidden');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            let shouldShow = true;
            
            // Verificar búsqueda general
            if (searchTerm && !text.includes(searchTerm)) {
                shouldShow = false;
            }
            
            // Verificar filtros específicos
            @if($showFilters)
                @foreach($filters as $filter)
                    if (filterValues['{{ $filter['id'] }}'] && !text.includes(filterValues['{{ $filter['id'] }}'])) {
                        shouldShow = false;
                    }
                @endforeach
            @endif
            
            if (shouldShow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Actualizar contador
        const resultsCount = document.getElementById('results-count');
        if (resultsCount) {
            resultsCount.textContent = `${visibleCount} elementos`;
        }
    }
    
    // Event listeners para filtros
    @if($showSearch)
        document.getElementById('search-filter')?.addEventListener('input', filterTable);
    @endif
    
    @if($showFilters)
        @foreach($filters as $filter)
            document.getElementById('{{ $filter['id'] }}-filter')?.addEventListener('{{ $filter['type'] === 'select' ? 'change' : 'input' }}', filterTable);
        @endforeach
        
        // Botón limpiar filtros
        document.getElementById('clear-filters')?.addEventListener('click', function() {
            @if($showSearch)
                document.getElementById('search-filter').value = '';
            @endif
            @foreach($filters as $filter)
                document.getElementById('{{ $filter['id'] }}-filter').value = '';
            @endforeach
            filterTable();
        });
    @endif
});
</script>
@endif 