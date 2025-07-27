@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8">

    <!-- Header mejorado -->
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-4">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-2 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Crear Usuario</h1>
                        <p class="text-base text-gray-500 mt-1">Complete la información requerida</p>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                    <a href="{{ route('users.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Compacto -->
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4 p-4">
            @csrf
            
            <!-- Información Personal -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Información Personal</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Nombre -->
                    <div class="space-y-1">
                        <label for="nombre" class="block text-base font-medium text-gray-700">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="nombre" 
                                   id="nombre" 
                                   value="{{ old('nombre') }}"
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('nombre') border-red-300 ring-red-100 @enderror"
                                   placeholder="Ingrese el nombre completo">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('nombre')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Correo -->
                    <div class="space-y-1">
                        <label for="correo" class="block text-base font-medium text-gray-700">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   name="correo" 
                                   id="correo" 
                                   value="{{ old('correo') }}"
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('correo') border-red-300 ring-red-100 @enderror"
                                   placeholder="ejemplo@correo.com">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                        </div>
                        @error('correo')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- RFC -->
                <div class="mt-3 space-y-1">
                    <label for="rfc" class="block text-base font-medium text-gray-700">
                        RFC
                    </label>
                    <div class="relative max-w-xs">
                        <input type="text" 
                               name="rfc" 
                               id="rfc" 
                               value="{{ old('rfc') }}"
                               class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('rfc') border-red-300 ring-red-100 @enderror"
                               placeholder="ABCD123456789"
                               maxlength="13">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    @error('rfc')
                        <p class="text-sm text-red-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-sm text-gray-400 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Formato: 4 letras + 6 números + 3 caracteres
                    </p>
                </div>
            </div>

            <!-- Contraseña -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Contraseña</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Contraseña -->
                    <div class="space-y-1">
                        <label for="password" class="block text-base font-medium text-gray-700">
                            Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('password') border-red-300 ring-red-100 @enderror"
                                   placeholder="Mínimo 8 caracteres">
                            <button type="button" 
                                    onclick="togglePassword('password')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-base font-medium text-gray-700">
                            Confirmar Contraseña <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200"
                                   placeholder="Repita la contraseña">
                            <button type="button" 
                                    onclick="togglePassword('password_confirmation')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles -->
            <div class="pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Roles y Permisos</h3>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-base font-medium text-gray-700 mb-2">
                        Roles del Usuario
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($roles as $role)
                            <div class="relative">
                                <input type="checkbox" 
                                       name="roles[]" 
                                       id="role_{{ $role->id }}" 
                                       value="{{ $role->name }}"
                                       {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                                       class="peer hidden">
                                <label for="role_{{ $role->id }}" 
                                       class="flex items-center p-2.5 border border-gray-200 rounded-lg cursor-pointer hover:border-primary/40 hover:bg-primary/5 transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:ring-1 peer-checked:ring-primary/30">
                                    <div class="w-4 h-4 border-2 border-gray-300 rounded flex items-center justify-center mr-2 peer-checked:border-primary peer-checked:bg-primary">
                                        <svg class="w-2.5 h-2.5 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-base font-medium text-gray-900">{{ ucfirst($role->name) }}</span>
                                        @if($role->name === 'user')
                                            <span class="block text-xs text-gray-400">(Por defecto)</span>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="text-sm text-red-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-base text-gray-400 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Si no se selecciona ningún rol, se asignará "user" por defecto
                    </p>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;
}

// Función para mejorar la funcionalidad de los checkboxes de roles
document.addEventListener('DOMContentLoaded', function() {
    // Obtener todos los checkboxes de roles
    const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
    
    // Función para actualizar el estado visual del checkbox
    function updateCheckboxVisual(checkbox) {
        const label = checkbox.nextElementSibling;
        const checkboxDiv = label.querySelector('.border-gray-300');
        const checkIcon = checkboxDiv.querySelector('svg');
        
        if (checkbox.checked) {
            // Marcar como seleccionado
            label.classList.add('border-primary', 'bg-primary/10', 'ring-1', 'ring-primary/30');
            checkboxDiv.classList.add('border-primary', 'bg-primary');
            checkIcon.classList.remove('hidden');
        } else {
            // Desmarcar
            label.classList.remove('border-primary', 'bg-primary/10', 'ring-1', 'ring-primary/30');
            checkboxDiv.classList.remove('border-primary', 'bg-primary');
            checkIcon.classList.add('hidden');
        }
    }
    
    // Agregar event listener a cada checkbox
    roleCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateCheckboxVisual(this);
        });
        
        // Inicializar el estado visual
        updateCheckboxVisual(checkbox);
    });
    
    // Agregar funcionalidad de click en las etiquetas
    const roleLabels = document.querySelectorAll('label[for^="role_"]');
    roleLabels.forEach(function(label) {
        label.addEventListener('click', function(e) {
            const checkbox = document.getElementById(this.getAttribute('for'));
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                updateCheckboxVisual(checkbox);
            }
        });
    });
});
</script>
@endsection 