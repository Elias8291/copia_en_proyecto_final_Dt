@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10,9 9,9 8,9"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Crear Nuevo Archivo</h1>
                    <p class="text-base text-gray-500 mt-1">Sube y configura un nuevo archivo en el sistema</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('archivos.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre Original -->
                <div class="md:col-span-2">
                    <label for="nombre_original" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Archivo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nombre_original" 
                           id="nombre_original" 
                           value="{{ old('nombre_original') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200"
                           placeholder="Ingresa el nombre del archivo"
                           required>
                    @error('nombre_original')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Proveedor -->
                <div>
                    <label for="proveedor_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Proveedor <span class="text-red-500">*</span>
                    </label>
                    <select name="proveedor_id" 
                            id="proveedor_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200"
                            required>
                        <option value="">Selecciona un proveedor</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trámite -->
                <div>
                    <label for="tramite_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Trámite
                    </label>
                    <select name="tramite_id" 
                            id="tramite_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                        <option value="">Selecciona un trámite (opcional)</option>
                        @foreach($tramites as $tramite)
                            <option value="{{ $tramite->id }}" {{ old('tramite_id') == $tramite->id ? 'selected' : '' }}>
                                {{ $tramite->folio }} - {{ $tramite->proveedor->nombre ?? 'Sin proveedor' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tramite_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Archivo -->
                <div>
                    <label for="catalogo_archivo_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Archivo <span class="text-red-500">*</span>
                    </label>
                    <select name="catalogo_archivo_id" 
                            id="catalogo_archivo_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200"
                            required>
                        <option value="">Selecciona el tipo de archivo</option>
                        @foreach($catalogoArchivos as $catalogo)
                            <option value="{{ $catalogo->id }}" {{ old('catalogo_archivo_id') == $catalogo->id ? 'selected' : '' }}>
                                {{ $catalogo->nombre }} ({{ $catalogo->tipo_archivo }})
                            </option>
                        @endforeach
                    </select>
                    @error('catalogo_archivo_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200"
                            required>
                        <option value="">Selecciona el estado</option>
                        <option value="Pendiente" {{ old('status') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Aprobado" {{ old('status') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="Rechazado" {{ old('status') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Archivo -->
                <div class="md:col-span-2">
                    <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                        Archivo <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-[#9d2449] transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-[#9d2449] hover:text-[#8a1f40] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#9d2449]">
                                    <span>Subir archivo</span>
                                    <input id="archivo" name="archivo" type="file" class="sr-only" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx" required>
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, DOC, DOCX, JPG, PNG, XLS, XLSX hasta 10MB
                            </p>
                        </div>
                    </div>
                    @error('archivo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observaciones -->
                <div class="md:col-span-2">
                    <label for="observaciones_documento" class="block text-sm font-medium text-gray-700 mb-2">
                        Observaciones
                    </label>
                    <textarea name="observaciones_documento" 
                              id="observaciones_documento" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200"
                              placeholder="Observaciones adicionales sobre el archivo">{{ old('observaciones_documento') }}</textarea>
                    @error('observaciones_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('archivos.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-[#9d2449] border border-transparent rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-200">
                    Crear Archivo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('archivo');
    const dropZone = document.querySelector('.border-dashed');
    
    // Prevenir comportamiento por defecto del navegador
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Resaltar zona de drop
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Manejar archivos soltados
    dropZone.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('border-[#9d2449]');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-[#9d2449]');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
    }
});
</script>
@endsection 