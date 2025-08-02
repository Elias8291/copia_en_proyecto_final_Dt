@props([
    'item' => null,
    'columns' => [],
    'showActions' => true,
    'actions' => [],
    'permissions' => []
])

<tr class="hidden lg:table-row hover:bg-gray-50/50 transition-all duration-200" data-tramite-id="{{ $item->id ?? '' }}">
    @foreach($columns as $column)
        <td class="px-10 py-5">
            @php
                $value = data_get($item, $column['field'], 'N/A');
                $subvalue = isset($column['subfield']) ? data_get($item, $column['subfield'], 'N/A') : null;
            @endphp
            
            @if(($column['type'] ?? 'text') === 'avatar')
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($value ?? 'U', 0, 1)) }}</span>
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
                    $badgeClasses = $column['colors'][$badgeValue] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeClasses }}">
                    {{ $badgeValue }}
                </span>
            @elseif(($column['type'] ?? 'text') === 'date')
                <span class="text-sm text-gray-900 font-medium">
                    @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                        {{ $value->format($column['format'] ?? 'd/m/Y') }}
                    @elseif(!empty($value) && strtotime($value))
                        {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y') }}
                    @else
                        N/A
                    @endif
                </span>
            @elseif(($column['type'] ?? 'text') === 'datetime')
                <span class="text-sm text-gray-900 font-medium">
                    @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
                        {{ $value->format($column['format'] ?? 'd/m/Y H:i') }}
                    @elseif(!empty($value) && strtotime($value))
                        {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y H:i') }}
                    @else
                        N/A
                    @endif
                </span>
            @elseif(($column['type'] ?? 'text') === 'time')
                <span class="text-sm text-gray-900 font-medium">
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
        <td class="px-10 py-5">
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
                                        <form action="{{ route($action['url'], $item->id) }}" method="POST" class="inline" id="delete-form-{{ $item->id }}">
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
        </td>
    @endif
</tr> 