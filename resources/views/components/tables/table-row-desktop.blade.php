@props([
    'item' => null,
    'columns' => [],
    'actions' => [],
    'showActions' => true,
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
                    @include('components.tables.partials.date-formatter', ['value' => $value, 'format' => $column['format'] ?? 'd/m/Y'])
                </span>
            @elseif(($column['type'] ?? 'text') === 'datetime')
                <span class="text-sm text-gray-900 font-medium">
                    @include('components.tables.partials.date-formatter', ['value' => $value, 'format' => $column['format'] ?? 'd/m/Y H:i'])
                </span>
            @elseif(($column['type'] ?? 'text') === 'time')
                <span class="text-sm text-gray-900 font-medium">
                    @include('components.tables.partials.date-formatter', ['value' => $value, 'format' => 'H:i'])
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
                    @if($actionKey !== 'create' && hasActionPermission($action, $permissions))
                        @include('components.tables.partials.action-button', [
                            'action' => $action,
                            'actionKey' => $actionKey,
                            'item' => $item,
                            'formPrefix' => 'desktop'
                        ])
                    @endif
                @endforeach
            </div>
        </td>
    @endif
</tr>

@php
function hasActionPermission($action, $permissions = []) {
    $permission = $action['permission'] ?? null;
    if (!$permission) return true;
    if (auth()->user() && auth()->user()->can($permission)) return true;
    return in_array($permission, $permissions);
}
@endphp