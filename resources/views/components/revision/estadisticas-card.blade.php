@props([
    'estadisticas' => [],
    'layout' => 'grid' // grid, flex
])

<div class="{{ $layout === 'grid' ? 'grid grid-cols-2 md:grid-cols-4 gap-4' : 'flex flex-wrap gap-4' }}">
    @foreach($estadisticas as $key => $stat)
    @php
        $color = $stat['color'] ?? 'gray';
        $bgColor = match($color) {
            'green' => 'bg-green-50',
            'red' => 'bg-red-50',
            'yellow' => 'bg-yellow-50',
            'blue' => 'bg-blue-50',
            'purple' => 'bg-purple-50',
            default => 'bg-gray-50'
        };
        $textColor = match($color) {
            'green' => 'text-green-600',
            'red' => 'text-red-600',
            'yellow' => 'text-yellow-600',
            'blue' => 'text-blue-600',
            'purple' => 'text-purple-600',
            default => 'text-gray-900'
        };
        $labelColor = match($color) {
            'green' => 'text-green-600',
            'red' => 'text-red-600',
            'yellow' => 'text-yellow-600',
            'blue' => 'text-blue-600',
            'purple' => 'text-purple-600',
            default => 'text-gray-500'
        };
    @endphp
    <div class="{{ $bgColor }} p-3 rounded-lg text-center {{ $layout === 'flex' ? 'flex-1 min-w-[120px]' : '' }}">
        <div class="text-2xl font-bold {{ $textColor }}">
            {{ $stat['valor'] }}
        </div>
        <div class="text-xs {{ $labelColor }}">
            {{ $stat['label'] }}
        </div>
    </div>
    @endforeach
</div> 