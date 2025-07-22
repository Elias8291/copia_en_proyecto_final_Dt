@extends('layouts.app')

@section('content')
    <div class="min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Encabezado -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('roles.index') }}" 
                       class="inline-flex items-center text-gray-500 hover:text-gray-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a Roles
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="text-3xl font-bold text-gray-900">Editar Rol: {{ $role->name }}</h1>
                    <p class="mt-2 text-gray-600">Modifica la información y permisos del rol.</p>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <!-- Información Básica -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre del Rol -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Rol *
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $role->name) }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 @error('name') border-red-500 @enderror"
                                       placeholder="Ej: Administrador, Editor, Viewer"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guard Name -->
                            <div>
                                <label for="guard_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Guard
                                </label>
                                <select id="guard_name" 
                                        name="guard_name" 
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200">
                                    <option value="web" {{ old('guard_name', $role->guard_name) === 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="api" {{ old('guard_name', $role->guard_name) === 'api' ? 'selected' : '' }}>API</option>
                                </select>
                                @error('guard_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 @error('description') border-red-500 @enderror"
                                      placeholder="Describe las responsabilidades y alcance de este rol...">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Información adicional -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Usuarios asignados:</span>
                                    <span class="text-gray-600">{{ $role->users()->count() }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Creado:</span>
                                    <span class="text-gray-600">{{ $role->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Actualizado:</span>
                                    <span class="text-gray-600">{{ $role->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Permisos -->
                        @if(isset($permissions) && $permissions->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-4">
                                    Permisos
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($permissions as $permission)
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="permission_{{ $permission->id }}" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}"
                                                   class="h-4 w-4 text-[#B4325E] focus:ring-[#B4325E]/30 border-gray-300 rounded"
                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                            <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Botones de Acción -->
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/70 flex justify-between">
                        <div>
                            @if($role->users()->count() === 0)
                                <button type="button" 
                                        onclick="confirmDelete()"
                                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500/30 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar Rol
                                </button>
                            @endif
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('roles.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] text-white text-sm font-medium rounded-lg hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Actualizar Rol
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Formulario oculto para eliminar -->
                <form id="delete-form" action="{{ route('roles.destroy', $role) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('¿Estás seguro de que deseas eliminar este rol? Esta acción no se puede deshacer.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection