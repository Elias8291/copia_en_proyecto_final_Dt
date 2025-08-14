@props(['archivosSubidos', 'seccion', 'modoCorreccion' => false])

<div>

    @if(!$modoCorreccion)
        @if($archivosSubidos && count($archivosSubidos) > 0)
            <!-- Lista de archivos -->
            <div class="space-y-3 xs:space-y-4 sm:space-y-5 lg:space-y-6 p-3 xs:p-4 sm:p-6 lg:p-8">
                @foreach($archivosSubidos as $index => $archivo)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden" data-archivo-id="{{ $archivo['id'] }}">
                        
                        <!-- Header del archivo -->
                        <div class="p-3 xs:p-4 sm:p-5 lg:p-6">
                            <div class="flex flex-col xs:flex-row xs:items-center gap-3 xs:gap-4 sm:gap-5">
                                <!-- Icono del archivo -->
                                <div class="w-8 h-8 xs:w-10 xs:h-10 sm:w-12 sm:h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200 flex-shrink-0">
                                    @switch($archivo['extension'])
                                        @case('pdf')
                                            <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                            </svg>
                                            @break
                                        @case('jpg')
                                        @case('jpeg')
                                        @case('png')
                                            <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                            </svg>
                                    @endswitch
                                </div>

                                <!-- Información del archivo -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs xs:text-sm sm:text-base lg:text-lg font-medium text-gray-900 break-words leading-tight">
                                        {{ $archivo['nombre_original'] }}
                                    </h4>
                                    <p class="text-xs xs:text-sm sm:text-base font-medium text-blue-600 mt-1 break-words">
                                        {{ $archivo['nombre_catalogo'] ?? 'Sin categoría' }}
                                    </p>
                                    <p class="text-xs xs:text-sm text-gray-500 mt-1">
                                        {{ number_format($archivo['tamaño'] / 1024, 1) }} KB
                                    </p>
                                    <!-- Estado del archivo -->
                                    <div class="mt-2 xs:mt-3">
                                        <span id="estado_archivo_{{ $archivo['id'] }}" class="inline-flex items-center px-2 xs:px-2.5 py-0.5 xs:py-1 rounded-full text-xs xs:text-sm font-medium bg-gray-100 text-gray-600">
                                            {{ $archivo['status'] ?? 'Pendiente' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Botón Ver -->
                                <div class="flex-shrink-0">
                                    <a href="{{ route('revisiones.mostrar-archivo', $archivo['id']) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-2 xs:px-3 sm:px-4 py-2 xs:py-2.5 sm:py-3 bg-blue-100 text-blue-700 text-xs xs:text-sm sm:text-base font-medium rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-200 min-h-[44px]">
                                        <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden xs:inline">Ver Documento</span>
                                        <span class="xs:hidden">Ver</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Área de comentarios -->
                        <div class="px-3 xs:px-4 sm:px-5 lg:px-6 pb-3 xs:pb-4 sm:pb-5 lg:pb-6">
                            <textarea 
                                id="textarea_archivo_{{ $archivo['id'] }}"
                                placeholder="Agregar comentarios sobre este documento..."
                                class="w-full px-2 xs:px-3 sm:px-4 py-2 xs:py-2.5 sm:py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-xs xs:text-sm sm:text-base"
                                rows="2"
                            >{{ $archivo['comentario_revision'] ?? '' }}</textarea>
                        </div>

                        <!-- Botones de decisión -->
                        <div class="px-3 xs:px-4 sm:px-5 lg:px-6 pb-3 xs:pb-4 sm:pb-5 lg:pb-6">
                            <div class="flex flex-col xs:flex-row justify-end gap-2 xs:gap-3 sm:gap-4">
                                <button type="button" 
                                        onclick="evaluarArchivo({{ $archivo['id'] }}, 'Rechazado')"
                                        class="inline-flex items-center justify-center px-3 xs:px-4 sm:px-5 py-2 xs:py-2.5 sm:py-3 bg-white border border-red-300 text-red-700 text-xs xs:text-sm sm:text-base font-medium rounded-lg hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all duration-200 shadow-sm min-h-[44px]">
                                    <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="hidden xs:inline">Rechazar</span>
                                    <span class="xs:hidden">No</span>
                                </button>
                                <button type="button" 
                                        onclick="evaluarArchivo({{ $archivo['id'] }}, 'Aprobado')"
                                        class="inline-flex items-center justify-center px-3 xs:px-4 sm:px-5 py-2 xs:py-2.5 sm:py-3 bg-green-600 text-white text-xs xs:text-sm sm:text-base font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-sm min-h-[44px]">
                                    <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="hidden xs:inline">Aprobar</span>
                                    <span class="xs:hidden">Sí</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado vacío -->
            <div class="p-4 xs:p-6 sm:p-8 lg:p-10 text-center">
                <svg class="mx-auto h-8 w-8 xs:h-10 xs:w-10 sm:h-12 sm:w-12 lg:h-16 lg:w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 xs:mt-3 text-xs xs:text-sm sm:text-base lg:text-lg font-medium text-gray-900">No hay documentos</h3>
                <p class="mt-1 xs:mt-2 text-xs xs:text-sm sm:text-base text-gray-500">No se han cargado documentos para esta sección.</p>
            </div>
        @endif
    @endif

    @if($modoCorreccion)
        <!-- Área para subir nuevos archivos en modo corrección -->
        <div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">Suba los archivos corregidos para reemplazar los rechazados</p>
            </div>
            
            <div class="space-y-4">
                @foreach($archivosSubidos as $archivo)
                    @if(($archivo['status'] ?? 'Pendiente') === 'Rechazado')
                        <div class="p-4 border border-red-200 rounded-lg bg-red-50">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="text-sm font-medium text-gray-800">{{ $archivo['nombre_catalogo'] ?? 'Documento' }}</h5>
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Rechazado</span>
                            </div>
                            
                            @if(!empty($archivo['comentario_revision']))
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-red-800 mb-1">Comentario de revisión:</p>
                                        <p class="text-sm text-red-700">{{ $archivo['comentario_revision'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-red-300 rounded-lg p-4 bg-white">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-xs text-gray-600 mb-3">Haga clic para seleccionar el archivo corregido</p>
                                    <input type="file" 
                                           name="documentos_correccion[{{ $archivo['catalogo_archivo_id'] ?? $archivo['id'] }}]" 
                                           id="archivo_correccion_{{ $archivo['id'] }}"
                                           accept=".pdf,.jpg,.jpeg,.png"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 cursor-pointer"
                                           onchange="updateFileName(this, 'nombre_archivo_{{ $archivo['id'] }}')">
                                    <p id="nombre_archivo_{{ $archivo['id'] }}" class="text-xs text-gray-500 mt-2 hidden"></p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
// Función para actualizar el nombre del archivo seleccionado
function updateFileName(input, nameElementId) {
    const nameElement = document.getElementById(nameElementId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        nameElement.textContent = `Archivo seleccionado: ${fileName}`;
        nameElement.classList.remove('hidden');
        
        // Cambiar el color del borde del contenedor para indicar que se seleccionó un archivo
        const container = input.closest('.border-dashed');
        if (container) {
            container.classList.remove('border-red-300');
            container.classList.add('border-green-300');
        }
    } else {
        nameElement.classList.add('hidden');
    }
}
</script>