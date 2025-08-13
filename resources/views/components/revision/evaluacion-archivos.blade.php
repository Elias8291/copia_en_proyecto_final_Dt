@props(['archivosSubidos', 'seccion'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="px-3 xs:px-4 sm:px-6 lg:px-8 py-3 xs:py-4 sm:py-5 lg:py-6 border-b border-gray-200">
        <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3 xs:gap-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-base xs:text-lg sm:text-xl lg:text-2xl font-semibold text-gray-900 leading-tight">Documentos del Trámite</h3>
                <p class="text-xs xs:text-sm sm:text-base text-gray-600 mt-1">Documentos cargados para revisión</p>
            </div>
            <div class="text-center xs:text-right">
                <div class="text-xl xs:text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-800">{{ count($archivosSubidos) }}</div>
                <div class="text-xs xs:text-sm text-blue-600">Documentos</div>
            </div>
        </div>
    </div>

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
</div>

<script>
// Función simplificada para evaluar archivo
async function evaluarArchivo(archivoId, decision) {
    const textarea = document.getElementById(`textarea_archivo_${archivoId}`);
    const estadoEl = document.getElementById(`estado_archivo_${archivoId}`);
    const comentario = textarea ? textarea.value.trim() : '';
    
    // Actualizar UI
    if (estadoEl) {
        estadoEl.textContent = decision;
        estadoEl.className = `inline-flex items-center px-2 xs:px-2.5 py-0.5 xs:py-1 rounded-full text-xs xs:text-sm font-medium ${
            decision === 'Aprobado' ? 'bg-green-100 text-green-800' : 
            decision === 'Rechazado' ? 'bg-red-100 text-red-800' : 
            'bg-gray-100 text-gray-600'
        }`;
    }
    
    // Enviar al servidor
    try {
        const response = await fetch(`/archivos/${archivoId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: decision, comentario_revision: comentario })
        });
        
        if (!response.ok) throw new Error('Error en la petición');
        
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>