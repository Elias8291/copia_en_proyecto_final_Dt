@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
    'error' => null,
    'options' => [], // para select
    'rows' => 3, // para textarea
    'accept' => null, // para file inputs
    'multiple' => false, // para file y select
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left' // left, right
])

@php
    $inputClasses = 'block w-full rounded-lg border-gray-200 shadow-sm transition-colors duration-200 focus:border-blue-500 focus:ring-blue-500';
    
    // Configurar tamaños
    $sizeClasses = match($size) {
        'sm' => 'text-sm px-3 py-2',
        'md' => 'text-sm px-4 py-2.5',
        'lg' => 'text-base px-4 py-3',
        default => 'text-sm px-4 py-2.5'
    };
    
    $labelClasses = match($size) {
        'sm' => 'text-xs font-medium text-gray-700',
        'md' => 'text-sm font-medium text-gray-700',
        'lg' => 'text-base font-medium text-gray-700',
        default => 'text-sm font-medium text-gray-700'
    };
    
    if ($error) {
        $inputClasses .= ' border-red-300 focus:border-red-500 focus:ring-red-500';
    }
    
    if ($disabled) {
        $inputClasses .= ' bg-gray-50 cursor-not-allowed';
    }
    
    if ($icon) {
        if ($iconPosition === 'left') {
            $inputClasses .= ' pl-10';
        } else {
            $inputClasses .= ' pr-10';
        }
    }
    
    $inputClasses .= ' ' . $sizeClasses;
    
    $fieldId = $name ?: 'field-' . uniqid();
@endphp

<div class="w-full">
    @if($label)
    <label for="{{ $fieldId }}" class="{{ $labelClasses }} mb-2 block">
        {{ $label }}
        @if($required)
            <span class="text-red-500 text-xs">*</span>
        @endif
    </label>
    @endif
    
    <div class="relative">
        @if($icon)
        <div class="absolute inset-y-0 {{ $iconPosition === 'left' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center pointer-events-none">
            <div class="h-5 w-5 text-gray-400">
                {!! $icon !!}
            </div>
        </div>
        @endif
        
        @if($type === 'textarea')
            <textarea
                id="{{ $fieldId }}"
                name="{{ $name }}"
                rows="{{ $rows }}"
                class="{{ $inputClasses }}"
                placeholder="{{ $placeholder }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
            >{{ old($name, $value) }}</textarea>
        @elseif($type === 'select')
            <select
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="{{ $inputClasses }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($multiple) multiple @endif
            >
                @if(!$multiple && !$required)
                    <option value="">{{ $placeholder ?: 'Seleccionar...' }}</option>
                @endif
                @foreach($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" 
                            @if(old($name, $value) == $optionValue) selected @endif>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
        @elseif($type === 'file')
            <input
                type="file"
                id="{{ $fieldId }}"
                name="{{ $name }}"
                class="{{ $inputClasses }}"
                @if($accept) accept="{{ $accept }}" @endif
                @if($multiple) multiple @endif
                @if($required) required @endif
                @if($disabled) disabled @endif
            />
        @elseif($type === 'checkbox')
            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="{{ $fieldId }}"
                    name="{{ $name }}"
                    value="1"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    @if(old($name, $value)) checked @endif
                    @if($required) required @endif
                    @if($disabled) disabled @endif
                />
                @if($label && $type === 'checkbox')
                <label for="{{ $fieldId }}" class="ml-2 block text-sm text-gray-900">
                    {{ $label }}
                    @if($required)
                        <span class="text-red-500 text-xs">*</span>
                    @endif
                </label>
                @endif
            </div>
        @elseif($type === 'radio')
            <div class="space-y-2">
                @foreach($options as $optionValue => $optionLabel)
                <div class="flex items-center">
                    <input
                        type="radio"
                        id="{{ $fieldId }}_{{ $optionValue }}"
                        name="{{ $name }}"
                        value="{{ $optionValue }}"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                        @if(old($name, $value) == $optionValue) checked @endif
                        @if($required) required @endif
                        @if($disabled) disabled @endif
                    />
                    <label for="{{ $fieldId }}_{{ $optionValue }}" class="ml-2 block text-sm text-gray-900">
                        {{ $optionLabel }}
                    </label>
                </div>
                @endforeach
            </div>
        @else
            <input
                type="{{ $type }}"
                id="{{ $fieldId }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                class="{{ $inputClasses }}"
                placeholder="{{ $placeholder }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
            />
        @endif
    </div>
    
    @if($help)
    <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
    
    @if($error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
    
    @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 