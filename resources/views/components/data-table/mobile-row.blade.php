@props([
    'item' => null,
    'columns' => [],
    'showActions' => true,
    'actions' => [],
    'permissions' => []
])

<div class="lg:hidden bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-lg hover:shadow-xl transition-all duration-200" data-tramite-id="{{ $item->id ?? '' }}">
    <div class="space-y-3">
        @foreach($columns as $column)
            @php
                $value = data_get($item, $column['field'], 'N/A');
                $subvalue = isset($column['subfield']) ? data_get($item, $column['subfield'], 'N/A') : null;
            @endphp
            
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        @if($column['type'] === 'avatar')
                            <div class="w-6 h-6 bg-primary rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xs">{{ strtoupper(substr($value ?? 'U', 0, 1)) }}</span>
                            </div>
                        @elseif($column['type'] === 'badge')
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        @elseif($column['type'] === 'date')
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @elseif($column['type'] === 'time')
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-700">{{ $column['label'] }}</div>
                        @if($column['type'] === 'avatar')
                            <div class="text-sm font-semibold text-gray-900">{{ $value ?? 'N/A' }}</div>
                            @if(isset($column['subfield']))
                                <div class="text-xs text-gray-500">{{ $column['subfield_label'] ?? '' }}: {{ $subvalue ?? 'N/A' }}</div>
                            @endif
                        @elseif($column['type'] === 'badge')
                            @php
                                $badgeValue = $value ?? 'default';
                                $badgeClasses = $column['colors'][$badgeValue] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $badgeClasses }}">
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
            <div class="pt-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Acciones:</span>
                    <div class="flex items-center space-x-2">
                        @foreach($actions as $actionKey => $action)
                            @if($actionKey !== 'create')
                                @php
                                    $hasPermission = !isset($action['permission']) || in_array($action['permission'], $permissions);
                                @endphp
                                
                                @if($hasPermission)
                                    @if(isset($action['url']))
                                        @if(isset($action['method']) && $action['method'] === 'DELETE')
                                            @if(isset($action['modalTitle']) || isset($action['modalMessage']))
                                                <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline" id="delete-form-mobile-{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="openDeleteModal('deleteModal{{ $item->id }}', '{{ $item->nombre ?? $item->name ?? 'Elemento' }}', '{{ $action['itemType'] ?? 'elemento' }}')"
                                                            class="p-2 {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
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
                                                            class="p-2 {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                                            title="{{ $action['label'] ?? $actionKey }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route($action['url'], $item->id) }}" 
                                               class="p-2 {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                               title="{{ $action['label'] ?? $actionKey }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                                </svg>
                                            </a>
                                        @endif
                                    @else
                                        <button class="p-2 {{ $action['color'] ?? 'text-primary' }} hover:bg-primary/10 rounded-lg transition-all duration-200" 
                                                title="{{ $action['label'] ?? $actionKey }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $action['icon'] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>' !!}
                                            </svg>
                                        </button>
                                    @endif
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div> 