@props(['archivosSubidos', 'seccion'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Documentos del Trámite</h3>
                <p class="text-sm text-gray-600">Documentos cargados para revisión</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-blue-800">{{ count($archivosSubidos) }}</div>
                <div class="text-xs text-blue-600">Documentos</div>
            </div>
        </div>
    </div>

    @if($archivosSubidos && count($archivosSubidos) > 0)
        <!-- Lista de archivos -->
        <div class="space-y-4 p-6">
            @foreach($archivosSubidos as $index => $archivo)
                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden" data-archivo-id="{{ $archivo['id'] }}">
                    
                    <!-- Header del archivo -->
                    <div class="p-4">
                        <div class="flex items-center space-x-4">
                        <!-- Icono del archivo -->
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                @switch($archivo['extension'])
                                    @case('pdf')
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                        @break
                                    @case('jpg')
                                    @case('jpeg')
                                    @case('png')
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                        </svg>
                                @endswitch
                        </div>

                        <!-- Información del archivo -->
                        <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $archivo['nombre_original'] }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $archivo['nombre_catalogo'] ?? 'Sin categoría' }} • {{ number_format($archivo['tamaño'] / 1024, 1) }} KB
                                </p>
                                <!-- Estado del archivo -->
                                <div class="mt-2">
                                    <span id="estado_archivo_{{ $archivo['id'] }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        {{ $archivo['status'] ?? 'Pendiente' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Botón Ver (en el header) -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('revisiones.mostrar-archivo', $archivo['id']) }}" 
                               target="_blank"
                                   class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                    Ver Documento
                            </a>
                            </div>
                        </div>
                    </div>

                    <!-- Campos ocultos para el formulario -->
                    <input type="hidden" id="decision_archivo_{{ $archivo['id'] }}" name="archivos[{{ $archivo['id'] }}][status]" value="{{ $archivo['status'] ?? 'Pendiente' }}">
                    <input type="hidden" id="comentario_archivo_{{ $archivo['id'] }}" name="archivos[{{ $archivo['id'] }}][comentario_revision]" value="{{ $archivo['comentario_revision'] ?? '' }}">

                    <!-- Área de comentarios -->
                    <div class="px-4 pb-4">
                                    <textarea 
                            id="textarea_archivo_{{ $archivo['id'] }}"
                            placeholder="Agregar comentarios sobre este documento..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                            rows="2"
                            onchange="sincronizarComentarioArchivo({{ $archivo['id'] }})"
                                    >{{ $archivo['comentario_revision'] ?? '' }}</textarea>
                                </div>

                    <!-- Botones de decisión (en la parte inferior) -->
                    <div class="px-4 pb-4">
                        <div class="flex justify-end space-x-3">
                            <button type="button" 
                                    onclick="evaluarArchivoRevisionDigital({{ $archivo['id'] }}, 'Rechazado')"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-red-300 text-red-700 text-sm font-medium rounded-lg hover:bg-red-50 hover:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Rechazar
                            </button>
                            <button type="button" 
                                    onclick="evaluarArchivoRevisionDigital({{ $archivo['id'] }}, 'Aprobado')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Aprobar
                            </button>
                            </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado vacío -->
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay documentos</h3>
            <p class="mt-1 text-sm text-gray-500">No se han cargado documentos para esta sección.</p>
        </div>
    @endif
</div>

<script>
// Función para evaluar archivo en revisión digital
function evaluarArchivoRevisionDigital(archivoId, decision) {
    const textareaEl = document.getElementById(`textarea_archivo_${archivoId}`);
    const decisionEl = document.getElementById(`decision_archivo_${archivoId}`);
    const comentarioEl = document.getElementById(`comentario_archivo_${archivoId}`);
    const estadoEl = document.getElementById(`estado_archivo_${archivoId}`);
    
    const comentario = textareaEl ? textareaEl.value.trim() : '';
    
    if (decisionEl) decisionEl.value = decision;
    if (comentarioEl) comentarioEl.value = comentario;
    
    if (estadoEl) {
        estadoEl.textContent = decision;
        estadoEl.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
        
        if (decision === 'Aprobado') {
            estadoEl.classList.add('bg-green-100', 'text-green-800');
        } else if (decision === 'Rechazado') {
            estadoEl.classList.add('bg-red-100', 'text-red-800');
        } else {
            estadoEl.classList.add('bg-gray-100', 'text-gray-600');
        }
        
        estadoEl.innerHTML += ' <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
    }
    
    // Enviar al servidor inmediatamente
    enviarEvaluacionArchivo(archivoId, decision, comentario);
}

// Función para sincronizar comentario
function sincronizarComentarioArchivo(archivoId) {
    const textareaEl = document.getElementById(`textarea_archivo_${archivoId}`);
    const comentarioEl = document.getElementById(`comentario_archivo_${archivoId}`);
    
    if (textareaEl && comentarioEl) {
        comentarioEl.value = textareaEl.value.trim();
    }
}

// Función para enviar evaluación al servidor
async function enviarEvaluacionArchivo(archivoId, decision, comentario) {
    try {
        const response = await fetch(`/archivos/${archivoId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: decision,
                comentario_revision: comentario
            })
        });

        const data = await response.json();
        
        if (data.success) {
            console.log(`Archivo ${archivoId} ${decision.toLowerCase()} correctamente`);
        } else {
            console.error('Error al actualizar archivo:', data.message);
        }
    } catch (error) {
        console.error('Error al enviar evaluación:', error);
    }
}
</script>