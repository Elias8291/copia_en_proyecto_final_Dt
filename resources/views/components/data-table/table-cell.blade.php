@props([
    'column' => [],
    'item' => null,
    'mobile' => false
])

@php
    $value = data_get($item, $column['field'], 'N/A');
    $subvalue = isset($column['subfield']) ? data_get($item, $column['subfield'], 'N/A') : null;
    $type = $column['type'] ?? 'text';
@endphp

@if($type === 'avatar')
    @if($mobile)
        <div class="text-sm font-semibold text-gray-900">{{ $value ?? 'N/A' }}</div>
        @if(isset($column['subfield']))
            <div class="text-xs text-gray-500">{{ $column['subfield_label'] ?? '' }}: {{ $subvalue ?? 'N/A' }}</div>
        @endif
    @else
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
    @endif

@elseif($type === 'badge')
    @php
        $badgeValue = $value ?? 'default';
        $badgeClasses = $column['colors'][$badgeValue] ?? 'bg-gray-100 text-gray-700 border-gray-200';
    @endphp
    <span class="inline-flex items-center px-2{{ $mobile ? '' : '.5' }} py-0.5 rounded-full text-xs font-medium border {{ $badgeClasses }}">
        {{ $badgeValue }}
    </span>

@elseif($type === 'date')
    <{{ $mobile ? 'div' : 'span' }} class="text-sm {{ $mobile ? 'font-semibold' : 'font-medium' }} text-gray-900">
        @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
            {{ $value->format($column['format'] ?? 'd/m/Y') }}
        @elseif(!empty($value) && strtotime($value))
            {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y') }}
        @else
            N/A
        @endif
    </{{ $mobile ? 'div' : 'span' }}>

@elseif($type === 'datetime')
    <{{ $mobile ? 'div' : 'span' }} class="text-sm {{ $mobile ? 'font-semibold' : 'font-medium' }} text-gray-900">
        @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
            {{ $value->format($column['format'] ?? 'd/m/Y H:i') }}
        @elseif(!empty($value) && strtotime($value))
            {{ \Carbon\Carbon::parse($value)->format($column['format'] ?? 'd/m/Y H:i') }}
        @else
            N/A
        @endif
    </{{ $mobile ? 'div' : 'span' }}>

@elseif($type === 'time')
    <{{ $mobile ? 'div' : 'span' }} class="text-sm {{ $mobile ? 'font-semibold' : 'font-medium' }} text-gray-900">
        @if(!empty($value) && ($value instanceof \Illuminate\Support\Carbon || $value instanceof \Carbon\Carbon))
            {{ $value->format('H:i') }}
        @elseif(!empty($value) && strtotime($value))
            {{ \Carbon\Carbon::parse($value)->format('H:i') }}
        @else
            N/A
        @endif
    </{{ $mobile ? 'div' : 'span' }}>

@else
    <{{ $mobile ? 'div' : 'span' }} class="text-sm {{ $mobile ? 'font-semibold' : '' }} text-gray-900">{{ $value ?? 'N/A' }}</{{ $mobile ? 'div' : 'span' }}>
@endif