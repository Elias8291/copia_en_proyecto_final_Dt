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
                    <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Rol</h1>
                    <p class="mt-2 text-gray-600">Define un nuevo rol con sus permisos correspondientes.</p>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
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
                                       value="{{ old('name') }}"
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
                                    <option value="web" {{ old('guard_name', 'web') === 'web' ? 'selected' : '' }}>Web</option>
                                    <option value="api" {{ old('guard_name') === 'api' ? 'selected' : '' }}>API</option>
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
                                      placeholder="Describe las responsabilidades y alcance de este rol...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
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
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-200/70 flex justify-end space-x-3">
                        <a href="{{ route('roles.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] text-white text-sm font-medium rounded-lg hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Crear Rol
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection