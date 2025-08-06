@props([
    'seccion' => '',
    'placeholder' => 'Escriba sus observaciones sobre esta sección...',
    'rows' => 3,
    'label' => true,
    'labelText' => 'Comentarios y observaciones',
    'required' => false,
    'maxlength' => null
])

@php
    $textareaId = "textarea_" . $seccion;
    $labelId = "label_" . $seccion;
@endphp

<div class="space-y-2">
    @if($label)
    <label for="{{ $textareaId }}" id="{{ $labelId }}" class="block text-sm font-medium text-gray-700">
        {{ $labelText }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif
    
    <textarea 
        id="{{ $textareaId }}" 
        placeholder="{{ $placeholder }}" 
        rows="{{ $rows }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($required) required @endif
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 resize-none"
    ></textarea>
    
    @if($maxlength)
    <div class="flex justify-end">
        <span class="text-xs text-gray-500">
            <span id="count_{{ $seccion }}">0</span> / {{ $maxlength }} caracteres
        </span>
    </div>
    @endif
</div>

@if($maxlength)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('{{ $textareaId }}');
    const counter = document.getElementById('count_{{ $seccion }}');
    
    if (textarea && counter) {
        textarea.addEventListener('input', function() {
            counter.textContent = this.value.length;
        });
    }
});
</script>
@endif 