@props([
    'margin' => 'my-8',
    'color' => 'border-gray-300',
    'style' => 'default' // default, gradient, dotted
])

@php
    $colorClass = match($color) {
        'border-indigo-300' => 'border-indigo-300/60 from-indigo-200 to-indigo-100',
        'border-emerald-300' => 'border-emerald-300/60 from-emerald-200 to-emerald-100',
        'border-amber-300' => 'border-amber-300/60 from-amber-200 to-amber-100',
        'border-purple-300' => 'border-purple-300/60 from-purple-200 to-purple-100',
        'border-rose-300' => 'border-rose-300/60 from-rose-200 to-rose-100',
        'border-cyan-300' => 'border-cyan-300/60 from-cyan-200 to-cyan-100',
        'border-blue-400' => 'border-blue-400/60 from-blue-200 to-blue-100',
        'border-slate-400' => 'border-slate-400/60 from-slate-200 to-slate-100',
        default => 'border-gray-300/60 from-gray-200 to-gray-100'
    };
    
    $borderColor = explode(' ', $colorClass)[0];
    $gradientColors = str_replace($borderColor . ' ', '', $colorClass);
@endphp

@if($style === 'gradient')
    <div class="{{ $margin }} flex items-center">
        <div class="flex-grow h-px bg-gradient-to-r {{ $gradientColors }}"></div>
        <div class="mx-6 w-3 h-3 rounded-full bg-gradient-to-br {{ $gradientColors }} shadow-sm border {{ $borderColor }}"></div>
        <div class="flex-grow h-px bg-gradient-to-l {{ $gradientColors }}"></div>
    </div>
@elseif($style === 'dotted')
    <div class="{{ $margin }} flex items-center justify-center">
        <div class="flex space-x-2">
            <div class="w-2 h-2 rounded-full bg-gradient-to-br {{ $gradientColors }} {{ $borderColor }}"></div>
            <div class="w-2 h-2 rounded-full bg-gradient-to-br {{ $gradientColors }} {{ $borderColor }}"></div>
            <div class="w-2 h-2 rounded-full bg-gradient-to-br {{ $gradientColors }} {{ $borderColor }}"></div>
        </div>
    </div>
@else
    <div class="{{ $margin }} flex items-center">
        <div class="flex-grow border-t-2 {{ $borderColor }} opacity-30"></div>
        <div class="mx-4 w-2 h-2 rounded-full {{ $borderColor }} bg-current opacity-50"></div>
        <div class="flex-grow border-t-2 {{ $borderColor }} opacity-30"></div>
    </div>
@endif 