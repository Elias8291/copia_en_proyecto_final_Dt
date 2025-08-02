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
    'actions' => [],
    'pagination' => null
])

<div class="w-full max-w-7xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg border border-gray-200 mb-6">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#9d2449] rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">{{ $title }}</h1>
                        <p class="text-sm text-gray-600">{{ $description }}</p>
                    </div>
                </div>
                
                @if(isset($actions['create']))
                    <a href="{{ $actions['create']['url'] ?? '#' }}" 
                       class="px-4 sm:px-6 py-2.5 sm:py-3 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="hidden sm:inline">{{ $actions['create']['label'] ?? 'Nuevo' }}</span>
                    </a>
                @endif
            </div>
        </div>

        @if($showSearch || $showFilters)
        <!-- Búsqueda y Filtros -->
        <div class="p-4 sm:p-6 border-t border-gray-100">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                @if($showSearch)
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm">🔍</span>
                        </div>
                        <input type="text" id="search-filter"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200"
                            placeholder="{{ $searchPlaceholder }}">
                    </div>
                </div>
                @endif

                @if($showFilters)
                    @foreach($filters as $filter)
                        @if($filter['type'] === 'select')
                        <div class="relative">
                            <select id="{{ $filter['id'] }}-filter"
                                class="appearance-none bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 min-w-[120px]">
                                <option value="">{{ $filter['placeholder'] ?? 'Seleccionar' }}</option>
                                @foreach($filter['options'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <span class="text-gray-400 text-xs">▼</span>
                            </div>
                        </div>
                        @elseif($filter['type'] === 'input')
                        <div class="relative">
                            <input type="text" id="{{ $filter['id'] }}-filter"
                                class="bg-white border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 min-w-[100px]"
                                placeholder="{{ $filter['placeholder'] }}">
                        </div>
                        @endif
                    @endforeach

                    <button id="clear-filters"
                        class="px-3 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 transition-all duration-200">
                        Limpiar
                    </button>
                @endif

                <div class="ml-auto">
                    <div id="results-count" class="text-sm text-gray-600 font-medium">
                        {{ $pagination ? $pagination->total() : count($data) }} elementos
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- Tabla Desktop -->
        <div class="overflow-x-auto hidden lg:block">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($columns as $column)
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">{{ $column['label'] }}</th>
                        @endforeach
                        @if($showActions)
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($data as $item)
                        <tr class="hover:bg-gray-50 transition-colors duration-200" data-tramite-id="{{ $item->id ?? '' }}">
                            @foreach($columns as $column)
                                <td class="px-6 py-4">
                                    @php
                                        $value = data_get($item, $column['field'], 'N/A');
                                        $subvalue = isset($column['subfield']) ? data_get($item, $column['subfield'], 'N/A') : null;
                                    @endphp
                                    @if(($column['type'] ?? 'text') === 'avatar')
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-[#9d2449] rounded-lg flex items-center justify-center mr-3">
                                                <span class="text-white font-semibold text-sm">{{ strtoupper(substr($value ?? 'U', 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $value ?? 'N/A' }}</div>
                                                @if(isset($column['subfield']))
                                                    <div class="text-xs text-gray-500">{{ $column['subfield_label'] ?? '' }}: {{ $subvalue ?? 'N/A' }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif(($column['type'] ?? 'text') === 'badge')
                                        @php
                                            $badgeValue = $value ?? 'default';
                                            $badgeClasses = $column['colors'][$badgeValue] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                            {{ $badgeValue }}
                                        </span>
                                    @elseif(($column['type'] ?? 'text') === 'date')
                                        <span class="text-sm text-gray-900">
                                            @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                                                {{ $value->format($column['format'] ?? 'd/m/Y') }}
                                            @elseif(!empty($value) && strtotime($value))
                                                {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    @elseif(($column['type'] ?? 'text') === 'datetime')
                                        <span class="text-sm text-gray-900">
                                            @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                                                {{ $value->format($column['format'] ?? 'd/m/Y H:i') }}
                                            @elseif(!empty($value) && strtotime($value))
                                                {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y H:i') }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    @elseif(($column['type'] ?? 'text') === 'time')
                                        <span class="text-sm text-gray-900">
                                            @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                                                {{ $value->format('H:i') }}
                                            @elseif(!empty($value) && strtotime($value))
                                                {{ \Carbon\Carbon::parse($value)->format('H:i') }}
                                            @else
                                                N/A
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-900">{{ $value ?? 'N/A' }}</span>
                                    @endif
                                </td>
                            @endforeach
                            
                            @if($showActions)
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @foreach($actions as $actionKey => $action)
                                            @if($actionKey !== 'create')
                                                @if(isset($action['url']))
                                                    @if(isset($action['method']) && $action['method'] === 'DELETE')
                                                        @if(isset($action['modalTitle']) || isset($action['modalMessage']))
                                                            <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline" id="delete-form-{{ $item->id }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" 
                                                                        onclick="openDeleteModal('deleteModal{{ $item->id }}', '{{ $item->nombre ?? $item->name ?? 'Elemento' }}', '{{ $action['itemType'] ?? 'elemento' }}')"
                                                                        class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                                        title="{{ $action['label'] ?? $actionKey }}">
                                                                    <span class="text-xs">⚙️</span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                                        title="{{ $action['label'] ?? $actionKey }}">
                                                                    <span class="text-xs">⚙️</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <a href="{{ route($action['url'], $item->id) }}" 
                                                       class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                       title="{{ $action['label'] ?? $actionKey }}">
                                                        <span class="text-xs">⚙️</span>
                                                    </a>
                                                    @endif
                                                @else
                                                    <button class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                            title="{{ $action['label'] ?? $actionKey }}">
                                                        <span class="text-xs">⚙️</span>
                                                    </button>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + ($showActions ? 1 : 0) }}" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <span class="text-2xl mx-auto mb-2 block">📄</span>
                                    <p class="text-sm">No hay datos</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Cards Mobile -->
        <div class="lg:hidden">
            @forelse($data as $item)
            <div class="bg-white border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors duration-200" data-tramite-id="{{ $item->id ?? '' }}">
                <div class="space-y-3">
                    @foreach($columns as $column)
                        @php
                            $value = data_get($item, $column['field'], 'N/A');
                            $subvalue = isset($column['subfield']) ? data_get($item, $column['subfield'], 'N/A') : null;
                        @endphp
                        
                        <div class="flex items-center justify-between py-1">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center">
                                    @if($column['type'] === 'avatar')
                                        <div class="w-4 h-4 bg-[#9d2449] rounded flex items-center justify-center">
                                            <span class="text-white font-bold text-xs">{{ strtoupper(substr($value ?? 'U', 0, 1)) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">ℹ️</span>
                                    @endif
                                </div>
                                
                                <div>
                                    <div class="text-xs text-gray-500">{{ $column['label'] }}</div>
                                    @if($column['type'] === 'avatar')
                                        <div class="text-sm font-semibold text-gray-900">{{ $value ?? 'N/A' }}</div>
                                        @if(isset($column['subfield']))
                                            <div class="text-xs text-gray-500">{{ $column['subfield_label'] ?? '' }}: {{ $subvalue ?? 'N/A' }}</div>
                                        @endif
                                    @elseif($column['type'] === 'badge')
                                        @php
                                            $badgeValue = $value ?? 'default';
                                            $badgeClasses = $column['colors'][$badgeValue] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                            {{ $badgeValue }}
                                        </span>
                                    @elseif($column['type'] === 'date')
                                        <div class="text-sm font-semibold text-gray-900">
                                            @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                                                {{ $value->format($column['format'] ?? 'd/m/Y') }}
                                            @elseif(!empty($value) && strtotime($value))
                                                {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    @elseif($column['type'] === 'time')
                                        <div class="text-sm font-semibold text-gray-900">
                                            @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                                                {{ $value->format('H:i') }}
                                            @elseif(!empty($value) && strtotime($value))
                                                {{ \Carbon\Carbon::parse($value)->format('H:i') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    @elseif($column['type'] === 'datetime')
                                        <div class="text-sm font-semibold text-gray-900">
                                            @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                                                {{ $value->format($column['format'] ?? 'd/m/Y H:i') }}
                                            @elseif(!empty($value) && strtotime($value))
                                                {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y H:i') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-sm font-semibold text-gray-900">{{ $value ?? 'N/A' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($showActions)
                        <div class="pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Acciones:</span>
                                <div class="flex items-center space-x-2">
                                    @foreach($actions as $actionKey => $action)
                                        @if($actionKey !== 'create')
                                            @if(isset($action['url']))
                                                @if(isset($action['method']) && $action['method'] === 'DELETE')
                                                    @if(isset($action['modalTitle']) || isset($action['modalMessage']))
                                                        <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline" id="delete-form-mobile-{{ $item->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                    onclick="openDeleteModal('deleteModal{{ $item->id }}', '{{ $item->nombre ?? $item->name ?? 'Elemento' }}', '{{ $action['itemType'] ?? 'elemento' }}')"
                                                                    class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                                    title="{{ $action['label'] ?? $actionKey }}">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                                    title="{{ $action['label'] ?? $actionKey }}">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <a href="{{ route($action['url'], $item->id) }}" 
                                                       class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                       title="{{ $action['label'] ?? $actionKey }}">
                                                        <span class="text-xs">⚙️</span>
                                                    </a>
                                                @endif
                                            @else
                                                <button class="p-2 {{ $action['color'] ?? 'text-[#9d2449]' }} hover:bg-[#9d2449]/10 rounded-lg transition-colors duration-200" 
                                                        title="{{ $action['label'] ?? $actionKey }}">
                                                    <span class="text-xs">⚙️</span>
                                                </button>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="text-gray-500">
                    <span class="text-2xl mx-auto mb-2 block">📄</span>
                    <p class="text-sm">No hay datos</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Paginación -->
    @if($pagination && $pagination->hasPages())
        <div class="mt-6 bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Información de resultados -->
                <div class="text-sm text-gray-700">
                    Mostrando <span class="font-semibold text-[#9d2449]">{{ $pagination->firstItem() }}</span> a 
                    <span class="font-semibold text-[#9d2449]">{{ $pagination->lastItem() }}</span> de 
                    <span class="font-semibold text-[#9d2449]">{{ $pagination->total() }}</span> resultados
                </div>

                <!-- Navegación de páginas -->
                <div class="flex items-center justify-center sm:justify-end space-x-1">
                    <!-- Botón Anterior -->
                    @if($pagination->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <span class="text-xs">◀</span>
                        </span>
                    @else
                        <a href="{{ $pagination->previousPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                            <span class="text-xs">◀</span>
                        </a>
                    @endif

                    <!-- Números de página -->
                    @foreach($pagination->getUrlRange(1, $pagination->lastPage()) as $page => $url)
                        @if($page == $pagination->currentPage())
                            <span class="px-3 py-2 text-sm font-semibold text-white bg-[#9d2449] rounded-lg">{{ $page }}</span>
                        @elseif($page <= 3 || $page > $pagination->lastPage() - 2 || ($page >= $pagination->currentPage() - 1 && $page <= $pagination->currentPage() + 1))
                            <a href="{{ $url }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">{{ $page }}</a>
                        @elseif($page == 4 && $pagination->currentPage() > 5)
                            <span class="px-3 py-2 text-sm text-gray-400">...</span>
                        @elseif($page == $pagination->lastPage() - 3 && $pagination->currentPage() < $pagination->lastPage() - 4)
                            <span class="px-3 py-2 text-sm text-gray-400">...</span>
                        @endif
                    @endforeach

                    <!-- Botón Siguiente -->
                    @if($pagination->hasMorePages())
                        <a href="{{ $pagination->nextPageUrl() }}" class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                            <span class="text-xs">▶</span>
                        </a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <span class="text-xs">▶</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modales de eliminación -->
@foreach($data as $item)
    @foreach($actions as $actionKey => $action)
        @if($actionKey === 'delete' && isset($action['modalTitle']))
            <x-delete-confirmation-modal 
                :id="'deleteModal' . $item->id"
                :title="$action['modalTitle'] ?? 'Confirmar Eliminación'"
                :message="$action['modalMessage'] ?? '¿Está seguro de que desea eliminar este elemento?'"
                :confirm-text="$action['confirmText'] ?? 'Eliminar'"
                :cancel-text="$action['cancelText'] ?? 'Cancelar'"
                :item-name="$item->nombre ?? $item->name ?? ''"
                :item-type="$action['itemType'] ?? 'elemento'"
            />
        @endif
    @endforeach
@endforeach

@if($showSearch || $showFilters)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filter = () => {
        const search = document.getElementById('search-filter')?.value.toLowerCase() || '';
        const filters = {};
        @if($showFilters)
            @foreach($filters as $filter)
                filters['{{ $filter['id'] }}'] = document.getElementById('{{ $filter['id'] }}-filter')?.value.toLowerCase() || '';
            @endforeach
        @endif

        const rows = [
            ...document.querySelectorAll('tbody tr'),
            ...document.querySelectorAll('div.lg\\:hidden > div')
        ];

        let count = 0;
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            let show = !search || text.includes(search);
            @if($showFilters)
                @foreach($filters as $filter)
                    if (show && filters['{{ $filter['id'] }}'])
                        show = text.includes(filters['{{ $filter['id'] }}']);
                @endforeach
            @endif
            row.style.display = show ? '' : 'none';
            if (show) count++;
        });
        document.getElementById('results-count').textContent = `${count} elementos`;
    };

    @if($showSearch)
        document.getElementById('search-filter')?.addEventListener('input', filter);
    @endif
    @if($showFilters)
        @foreach($filters as $filter)
            document.getElementById('{{ $filter['id'] }}-filter')?.addEventListener('{{ $filter['type'] === 'select' ? 'change' : 'input' }}', filter);
        @endforeach
        document.getElementById('clear-filters')?.addEventListener('click', () => {
            @if($showSearch)
                document.getElementById('search-filter').value = '';
            @endif
            @foreach($filters as $filter)
                document.getElementById('{{ $filter['id'] }}-filter').value = '';
            @endforeach
            filter();
        });
    @endif
    filter();
});
</script>
@endif 