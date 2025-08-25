@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 -mt-4">

    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-4">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-2 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Editar Rol</h1>
                        <p class="text-base text-gray-500 mt-1">Modifique la información del rol</p>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                    <a href="{{ route('roles.index') }}" 
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

    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
        <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-4 p-4">
            @csrf
            @method('PUT')
            
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Información del Rol</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="name" class="block text-base font-medium text-gray-700">
                            Nombre del Rol <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $role->name) }}"
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('name') border-red-300 ring-red-100 @enderror"
                                   placeholder="Ingrese el nombre del rol">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('name')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="guard_name" class="block text-base font-medium text-gray-700">
                            Guard <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="guard_name" 
                                    id="guard_name" 
                                    class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('guard_name') border-red-300 ring-red-100 @enderror">
                                <option value="">Seleccione el guard</option>
                                <option value="web" {{ old('guard_name', $role->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ old('guard_name', $role->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                                <option value="admin" {{ old('guard_name', $role->guard_name) == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('guard_name')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <label for="description" class="block text-base font-medium text-gray-700">
                        Descripción
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('description') border-red-300 ring-red-100 @enderror"
                              placeholder="Descripción del rol">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Permisos</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   id="select-all-permissions"
                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary/30">
                            <span class="ml-2 text-sm font-medium text-gray-700">Seleccionar todos</span>
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 border border-gray-200 rounded-lg p-3">
                        @foreach($permissions as $permission)
                            <label class="flex items-center p-2 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}"
                                       {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary/30 permission-checkbox">
                                <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    @error('permissions')
                        <p class="text-sm text-red-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('roles.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Rol
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-permissions');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
        });
    });
    const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
    const anyChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
    
    selectAllCheckbox.checked = allChecked;
    selectAllCheckbox.indeterminate = anyChecked && !allChecked;
});
</script>
@endsection