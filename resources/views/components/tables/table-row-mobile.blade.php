@props([
    'item' => null,
    'columns' => [],
    'actions' => [],
    'showActions' => true,
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
                        @include('components.tables.partials.column-icon', ['type' => $column['type'] ?? 'text', 'value' => $value])
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
                        @elseif(in_array($column['type'], ['date', 'datetime', 'time']))
                            <div class="text-sm font-semibold text-gray-900">
                                @include('components.tables.partials.date-formatter', [
                                    'value' => $value,
                                    'format' => $column['format'] ?? ($column['type'] === 'time' ? 'H:i' : ($column['type'] === 'datetime' ? 'd/m/Y H:i' : 'd/m/Y'))
                                ])
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
                            @if($actionKey !== 'create' && hasActionPermission($action, $permissions))
                                @include('components.tables.partials.action-button', [
                                    'action' => $action,
                                    'actionKey' => $actionKey,
                                    'item' => $item,
                                    'formPrefix' => 'mobile'
                                ])
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@php
function hasActionPermission($action, $permissions = []) {
    $permission = $action['permission'] ?? null;
    if (!$permission) return true;
    if (auth()->user() && auth()->user()->can($permission)) return true;
    return in_array($permission, $permissions);
}
@endphp