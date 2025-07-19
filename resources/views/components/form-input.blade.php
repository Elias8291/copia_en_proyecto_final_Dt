@props([
    'type' => 'text',
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'min' => null,
    'max' => null,
    'step' => null,
    'readonly' => false,
    'disabled' => false,
    'pattern' => null,
    'maxlength' => null,
    'class' => '',
    'container_class' => '',
    'icon' => null,
    'suffix' => null,
    'help' => null
])

<div class="{{ $container_class }}">
    @if($label)
        <label for="{{ $attributes->get('id', $name) }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                {!! $icon !!}
            </div>
        @endif
        
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $attributes->get('id', $name) }}"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif
            @if($readonly) readonly @endif
            @if($disabled) disabled @endif
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
            @if($step !== null) step="{{ $step }}" @endif
            @if($pattern) pattern="{{ $pattern }}" @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($type === 'number' && $step === null) step="any" @endif
            class="w-full {{ $icon ? 'pl-10' : 'px-4' }} {{ $suffix ? 'pr-12' : 'px-4' }} py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm {{ $readonly ? 'bg-gray-50' : '' }} {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $class }}"
            {{ $attributes }}
        >
        
        @if($suffix)
            <span class="absolute right-3 top-2.5 text-gray-500 text-sm">{{ $suffix }}</span>
        @endif
    </div>
    
    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 