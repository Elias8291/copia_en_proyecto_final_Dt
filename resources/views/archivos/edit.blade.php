@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('archivos.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-[#B4325E] transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Catálogo de Archivos
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Editar: {{ $archivo->nombre }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Contenedor Principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
            <!-- Encabezado -->
            <div class="p-6 border-b border-gray-200/70">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Editar Archivo</h1>
                        <p class="text-sm text-gray-500">Modifica la información del archivo "{{ $archivo->nombre }}".</p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="p-6">
                <form action="{{ route('archivos.update', $archivo) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <div class="bg-gray-50/50 rounded-xl p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#B4325E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Información Básica
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Archivo *
                                </label>
                                <input type="text" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="{{ old('nombre', $archivo->nombre) }}"
                                       required
                                       maxlength="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-200 @error('nombre') border-red-500 ring-2 ring-red-200 @enderror">
                                @error('nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Ejemplo: "Identificación Oficial", "Acta Constitutiva"</p>
                            </div>

                            <!-- Tipo de Archivo -->
                            <div>
                                <label for="tipo_archivo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Archivo *
                                </label>
                                <select id="tipo_archivo" 
                                        name="tipo_archivo" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-200 @error('tipo_archivo') border-red-500 ring-2 ring-red-200 @enderror">
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="pdf" {{ old('tipo_archivo', $archivo->tipo_archivo) === 'pdf' ? 'selected' : '' }}>PDF - Documento</option>
                                    <option value="png" {{ old('tipo_archivo', $archivo->tipo_archivo) === 'png' ? 'selected' : '' }}>PNG - Imagen</option>
                                    <option value="mp3" {{ old('tipo_archivo', $archivo->tipo_archivo) === 'mp3' ? 'selected' : '' }}>MP3 - Audio</option>
                                </select>
                                @error('tipo_archivo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción
                            </label>
                            <textarea id="descripcion" 
                                      name="descripcion" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-200 resize-none @error('descripcion') border-red-500 ring-2 ring-red-200 @enderror"
                                      placeholder="Describe brevemente qué tipo de documento es y para qué se utiliza...">{{ old('descripcion', $archivo->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Configuración -->
                    <div class="bg-gray-50/50 rounded-xl p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#B4325E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Configuración
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tipo de Persona -->
                            <div>
                                <label for="tipo_persona" class="block text-sm font-medium text-gray-700 mb-2">
                                    Aplica para *
                                </label>
                                <select id="tipo_persona" 
                                        name="tipo_persona" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-200 @error('tipo_persona') border-red-500 ring-2 ring-red-200 @enderror">
                                    <option value="">Seleccionar...</option>
                                    <option value="Física" {{ old('tipo_persona', $archivo->tipo_persona) === 'Física' ? 'selected' : '' }}>Solo Persona Física</option>
                                    <option value="Moral" {{ old('tipo_persona', $archivo->tipo_persona) === 'Moral' ? 'selected' : '' }}>Solo Persona Moral</option>
                                    <option value="Ambas" {{ old('tipo_persona', $archivo->tipo_persona) === 'Ambas' ? 'selected' : '' }}>Ambos tipos</option>
                                </select>
                                @error('tipo_persona')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="es_visible" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="es_visible" 
                                               value="1" 
                                               {{ old('es_visible', $archivo->es_visible ? '1' : '0') === '1' ? 'checked' : '' }}
                                               class="h-4 w-4 text-[#B4325E] focus:ring-[#B4325E] border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Visible</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" 
                                               name="es_visible" 
                                               value="0" 
                                               {{ old('es_visible', $archivo->es_visible ? '1' : '0') === '0' ? 'checked' : '' }}
                                               class="h-4 w-4 text-[#B4325E] focus:ring-[#B4325E] border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Oculto</span>
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Los archivos ocultos no aparecerán en los formularios</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="bg-blue-50/50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Información del Registro
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium text-gray-700">Creado:</span>
                                <span>{{ $archivo->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Última actualización:</span>
                                <span>{{ $archivo->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('archivos.index') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] text-white text-sm font-medium rounded-xl hover:from-[#93264B] hover:to-[#6b1a2f] focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:ring-offset-2 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Actualizar Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 