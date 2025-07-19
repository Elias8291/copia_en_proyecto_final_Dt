@props([
    'name' => '',
    'label' => '',
    'required' => false,
    'value' => '',
    'placeholder' => 'Seleccione una opción',
    'options' => [],
    'class' => '',
    'container_class' => '',
    'help' => null,
    'disabled' => false
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
        <select 
            name="{{ $name }}" 
            id="{{ $attributes->get('id', $name) }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] focus:outline-none transition-colors duration-200 text-sm appearance-none bg-white {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }} {{ $class }}"
            {{ $attributes }}
        >
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        
        <!-- Icono de flecha -->
        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
    
    @if($help)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div> 