@props(['editable' => false, 'archivosRequeridos' => [], 'tipoPersona' => 'Física', 'archivosCargados' => null, 'soloLectura' => false])

@php
    // Asegurar que archivosRequeridos sea una colección
    $archivosRequeridosCollection = collect($archivosRequeridos ?? []);
    $archivosFiltrados = $archivosRequeridosCollection->filter(function($archivo) use ($tipoPersona) {
        $tipo = is_array($archivo) ? ($archivo['tipo_persona'] ?? null) : ($archivo->tipo_persona ?? null);
        return $tipo === 'Ambas' || $tipo === $tipoPersona;
    });
@endphp

<div class="space-y-6" {{ $attributes }}>
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Archivos Requeridos</h3>
            <p class="text-sm text-gray-500">Documentación obligatoria para {{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}</p>
        </div>
    </div>

    @if($editable && $archivosFiltrados->count() > 0)
        <div class="bg-[#9D2449]/10 border border-[#9D2449]/20 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-[#9D2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-[#9D2449]">
                        Archivos Obligatorios
                    </h3>
                    <div class="mt-2 text-sm text-[#9D2449]/80">
                        <p>Complete la carga de todos los archivos marcados como obligatorios para continuar con el trámite.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($archivosFiltrados as $archivo)
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-[#9D2449] transition-colors">
                    <div class="text-center">
                        <!-- Icono según tipo de archivo -->
                        <div class="w-12 h-12 bg-[#9D2449]/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            @switch($archivo->tipo_archivo)
                                @case('pdf')
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    @break
                                @case('mp4')
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4A2,2 0 0,1 4,2M5,5V11H19V5H5Z"/>
                                    </svg>
                                    @break
                                @case('png')
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                    </svg>
                                    @break
                                @case('mp3')
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,3V13.55C11.41,13.21 10.73,13 10,13A3,3 0 0,0 7,16A3,3 0 0,0 10,19A3,3 0 0,0 13,16V7H18V3H12Z"/>
                                    </svg>
                                    @break
                                @default
                                    <svg class="w-6 h-6 text-[#9D2449]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                            @endswitch
                        </div>
                        
                        <!-- Nombre del archivo -->
                        <h5 class="font-medium text-gray-900 mb-2 text-sm">
                            {{ $archivo->nombre }} <span class="text-red-500">*</span>
                        </h5>
                        
                        <!-- Descripción -->
                        <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $archivo->descripcion }}</p>
                        
                        <!-- Tipo de archivo -->
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#9D2449]/10 text-[#9D2449] border border-[#9D2449]/20">
                                {{ strtoupper($archivo->tipo_archivo) }}
                            </span>
                        </div>
                        
                        <!-- Botón de carga -->
                        <label class="cursor-pointer group">
                            <input type="file"
                                name="documentos[{{ Str::slug($archivo->nombre) }}]"
                                accept=".{{ $archivo->tipo_archivo }}"
                                class="hidden {{ $errors->has('documentos.' . Str::slug($archivo->nombre)) ? 'border-red-500' : '' }}"
                                onchange="updateFileName(this, '{{ Str::slug($archivo->nombre) }}-name')">
                            <span class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-gray-300 to-gray-400 hover:from-gray-400 hover:to-gray-500 text-gray-800 text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Subir Archivo
                            </span>
                        </label>
                        
                        <!-- Nombre del archivo seleccionado -->
                        <p id="{{ Str::slug($archivo->nombre) }}-name" class="text-xs text-[#9D2449] mt-2 hidden font-medium"></p>
                        
                        <!-- Error individual para cada archivo -->
                        @error('documentos.' . Str::slug($archivo->nombre))
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>
        
        @error('documentos.*')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @error('archivos_faltantes')
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="text-sm text-red-700">
                        <strong>Error:</strong> {{ $message }}
                    </p>
                </div>
            </div>
        @enderror

        <!-- Indicador de estado de archivos -->
        <div id="estado-archivos" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-blue-700">
                    <strong>Estado de Archivos:</strong> <span id="archivos-progreso">0 de 0</span> archivos obligatorios cargados.
                </p>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h5 class="font-medium text-gray-900 mb-2">Formatos permitidos:</h5>
            <ul class="text-sm text-gray-600 space-y-1">
                <li>• <strong>PDF:</strong> Para documentos legales (máx. 10MB)</li>
                <li>• <strong>MP4:</strong> Para videos (máx. 50MB)</li>
                <li>• <strong>PNG:</strong> Para imágenes (máx. 5MB)</li>
                <li>• <strong>MP3:</strong> Para audio (máx. 10MB)</li>
            </ul>
        </div>
    @elseif(!$editable)
        @if($archivosCargados && (is_array($archivosCargados) ? count($archivosCargados) : $archivosCargados->count()) > 0)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800">
                            Archivos Cargados
                        </h3>
                        <div class="mt-2 text-sm text-gray-700">
                            <p>Archivos que han sido cargados para este trámite.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($archivosCargados as $archivo)
                    <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col h-full">
                        <div class="text-center flex-grow">
                            <!-- Icono según tipo de archivo -->
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                @php
                                    $extension = is_array($archivo) ? ($archivo['extension'] ?? '') : ($archivo->extension ?? '');
                                @endphp
                                @switch($extension)
                                    @case('pdf')
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                        @break
                                    @case('jpg')
                                    @case('jpeg')
                                    @case('png')
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                @endswitch
                            </div>
                            
                            <!-- Nombre del archivo -->
                            <h5 class="font-medium text-gray-900 mb-2 text-sm">
                                {{ is_array($archivo) ? ($archivo['nombre_catalogo'] ?? $archivo['nombre_original'] ?? 'Archivo') : ($archivo->catalogoArchivo->nombre ?? $archivo->nombre_original) }}
                            </h5>
                            
                            <!-- Nombre original -->
                            <p class="text-xs text-gray-500 mb-3">{{ is_array($archivo) ? ($archivo['nombre_original'] ?? '') : ($archivo->nombre_original ?? '') }}</p>
                            
                            <!-- Tipo de archivo -->
                            <div class="mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    {{ strtoupper($extension) }}
                                </span>
                            </div>
                            
                            <!-- Tamaño del archivo -->
                            <p class="text-xs text-gray-500 mb-3">
                                {{ number_format((is_array($archivo) ? ($archivo['tamaño'] ?? 0) : ($archivo->tamaño ?? 0)) / 1024, 2) }} KB
                            </p>
                            
                            <!-- Botón para ver/descargar -->
                            <a href="{{ route('revisiones.mostrar-archivo', is_array($archivo) ? ($archivo['id'] ?? '') : ($archivo->id ?? '')) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver Archivo
                            </a>
                        </div>
                        
                        @if(!$soloLectura)
                        <!-- Área de Decisión por Archivo -->
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-600 mb-2 text-left">
                                    Revisión de archivo:
                                </label>
                                <textarea 
                                    placeholder="Comentarios sobre este archivo..."
                                    class="w-full text-xs px-2 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                    rows="2"></textarea>
                            </div>
                            
                            <div class="flex gap-1">
                                <button type="button" class="flex-1 bg-green-50 hover:bg-green-100 text-green-700 font-medium py-1.5 px-2 rounded-md transition-colors text-xs flex items-center justify-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Aprobar</span>
                                </button>
                                
                                <button type="button" class="flex-1 bg-red-50 hover:bg-red-100 text-red-700 font-medium py-1.5 px-2 rounded-md transition-colors text-xs flex items-center justify-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Rechazar</span>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">
                            Sin Archivos
                        </h3>
                        <p class="text-gray-600">
                            No se han cargado archivos para este trámite.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-yellow-800">
                        No hay archivos configurados
                    </h3>
                    <p class="text-yellow-700">
                        No se encontraron archivos requeridos para su tipo de persona.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function updateFileName(input, elementId) {
    const fileNameElement = document.getElementById(elementId);
    if (input.files && input.files[0]) {
        fileNameElement.textContent = input.files[0].name;
        fileNameElement.classList.remove('hidden');
    } else {
        fileNameElement.classList.add('hidden');
    }
    
    // Actualizar estado de archivos
    if (window.actualizarEstadoArchivos) {
        window.actualizarEstadoArchivos();
    }
}

// Actualizar estado inicial de archivos
document.addEventListener('DOMContentLoaded', function() {
    if (window.actualizarEstadoArchivos) {
        window.actualizarEstadoArchivos();
    }
    
    // Agregar validación al envío del formulario
    const form = document.querySelector('#tramite-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validar archivos antes de enviar
            if (window.validateArchivosOnSubmit) {
                const archivosValidos = window.validateArchivosOnSubmit();
                if (!archivosValidos) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Mostrar mensaje de error
                    const errorMessage = document.createElement('div');
                    errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    errorMessage.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span>Por favor, corrija los errores en los archivos antes de continuar.</span>
                        </div>
                    `;
                    
                    document.body.appendChild(errorMessage);
                    
                    // Remover mensaje después de 5 segundos
                    setTimeout(() => {
                        if (errorMessage.parentNode) {
                            errorMessage.remove();
                        }
                    }, 5000);
                    
                    return false;
                }
            }
        });
    }
});
</script> 