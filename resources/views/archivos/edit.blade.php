@extends('layouts.app')

@section('title', 'Editar Catálogo de Archivo')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Editar Catálogo de Archivo
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">
                        Modifica la información del catálogo de archivo
                    </p>
                </div>
                <a href="{{ route('archivos.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <form method="POST" action="{{ route('archivos.update', $archivo) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Catálogo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nombre" 
                               id="nombre" 
                               value="{{ old('nombre', $archivo->nombre) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#9d2449] focus:border-[#9d2449] sm:text-sm @error('nombre') border-red-300 @enderror"
                               placeholder="Ej: Acta Constitutiva"
                               maxlength="100"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <textarea name="descripcion" 
                                  id="descripcion" 
                                  rows="4"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#9d2449] focus:border-[#9d2449] sm:text-sm @error('descripcion') border-red-300 @enderror"
                                  placeholder="Describe el propósito y uso de este tipo de archivo"
                                  required>{{ old('descripcion', $archivo->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Persona -->
                    <div>
                        <label for="tipo_persona" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Persona <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo_persona" 
                                id="tipo_persona" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#9d2449] focus:border-[#9d2449] sm:text-sm @error('tipo_persona') border-red-300 @enderror"
                                required>
                            <option value="">Selecciona un tipo</option>
                            <option value="Física" {{ old('tipo_persona', $archivo->tipo_persona) == 'Física' ? 'selected' : '' }}>Física</option>
                            <option value="Moral" {{ old('tipo_persona', $archivo->tipo_persona) == 'Moral' ? 'selected' : '' }}>Moral</option>
                            <option value="Ambas" {{ old('tipo_persona', $archivo->tipo_persona) == 'Ambas' ? 'selected' : '' }}>Ambas</option>
                        </select>
                        @error('tipo_persona')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Archivo -->
                    <div>
                        <label for="tipo_archivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Formato de Archivo <span class="text-red-500">*</span>
                        </label>
                        <select name="tipo_archivo" 
                                id="tipo_archivo" 
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#9d2449] focus:border-[#9d2449] sm:text-sm @error('tipo_archivo') border-red-300 @enderror"
                                required>
                            <option value="">Selecciona un formato</option>
                            <option value="png" {{ old('tipo_archivo', $archivo->tipo_archivo) == 'png' ? 'selected' : '' }}>PNG - Imagen</option>
                            <option value="pdf" {{ old('tipo_archivo', $archivo->tipo_archivo) == 'pdf' ? 'selected' : '' }}>PDF - Documento</option>
                            <option value="mp3" {{ old('tipo_archivo', $archivo->tipo_archivo) == 'mp3' ? 'selected' : '' }}>MP3 - Audio</option>
                            <option value="mp4" {{ old('tipo_archivo', $archivo->tipo_archivo) == 'mp4' ? 'selected' : '' }}>MP4 - Video</option>
                        </select>
                        @error('tipo_archivo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado Visible -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="es_visible" 
                                   id="es_visible" 
                                   value="1"
                                   {{ old('es_visible', $archivo->es_visible) ? 'checked' : '' }}
                                   class="h-4 w-4 text-[#9d2449] focus:ring-[#9d2449] border-gray-300 rounded">
                            <label for="es_visible" class="ml-2 block text-sm text-gray-700">
                                Visible en el sistema
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Si está marcado, este catálogo estará disponible para seleccionar al subir archivos
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('archivos.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-[#9d2449] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#8a1f40] active:bg-[#7a1b38] focus:outline-none focus:border-[#7a1b38] focus:ring ring-[#9d2449]/30 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Catálogo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 