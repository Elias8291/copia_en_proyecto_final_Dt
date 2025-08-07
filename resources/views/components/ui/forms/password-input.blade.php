@props([
    'name' => 'password',
    'label' => 'Contraseña',
    'placeholder' => '••••••••',
    'required' => true,
    'value' => ''
])

<div>
    <label for="{{ $name }}" class="block text-xs font-medium text-gray-700 mb-0.5">{{ $label }}</label>
    <div class="relative">
        <input 
            type="password" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            @if($required) required @endif
            value="{{ old($name, $value) }}" 
            class="w-full px-2.5 py-1.5 pr-10 rounded-lg border @error($name) border-red-500 bg-red-50/30 @else border-gray-300 @enderror focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm" 
            placeholder="{{ $placeholder }}" 
            aria-describedby="{{ $name }}-error">
        
        <!-- Toggle password visibility button -->
        <button 
            type="button" 
            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200 focus:outline-none focus:text-primary"
            onclick="togglePasswordVisibility('{{ $name }}')"
            tabindex="-1">
            <svg id="{{ $name }}-eye-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg id="{{ $name }}-eye-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
            </svg>
        </button>
        
        @error($name)
            <div class="absolute right-8 top-1/2 -translate-y-1/2">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        @enderror
    </div>
    
    @error($name)
        <div class="mt-1">
            <span class="text-xs text-red-500 font-medium" id="{{ $name }}-error">{{ $message }}</span>
        </div>
    @enderror
</div>

@push('scripts')
<script>
if (typeof togglePasswordVisibility === 'undefined') {
    function togglePasswordVisibility(fieldName) {
        const input = document.getElementById(fieldName);
        const eyeOpen = document.getElementById(fieldName + '-eye-open');
        const eyeClosed = document.getElementById(fieldName + '-eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
}
</script>
@endpush