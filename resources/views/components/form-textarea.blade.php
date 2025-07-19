@props([
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'rows' => 3,
    'readonly' => false,
    'disabled' => false,
    'maxlength' => null,
    'class' => '',
    'container_class' => '',
    'help' => null
])

<div class="{{ $container_class }}">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <textarea 
        name="{{ $name }}" 
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($readonly) readonly @endif
        @if($disabled) disabled @endif
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm resize-none {{ $readonly ? 'bg-gray-50' : '' }} {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $class }}"
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    
    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 