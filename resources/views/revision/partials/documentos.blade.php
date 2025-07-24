@props(['tramite', 'documentos' => [], 'editable' => false])

<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8" {{ $attributes }}>
    <!-- Encabezado con icono -->
    <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-file-upload text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Documentos</h2>
                <p class="text-sm text-gray-500 mt-1">Archivos adjuntos al trámite</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ count($documentos) }} documento{{ count($documentos) !== 1 ? 's' : '' }}
            </span>
            @if($editable)
                <button type="button" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-edit mr-1"></i>
                    Revisar
                </button>
            @endif
        </div>
    </div>

    @if(count($documentos) > 0)
        <div class="space-y-6">
            @foreach($documentos as $documento)
                <div class="bg-gradient-to-r from-gray-50 to-gray-100/50 rounded-xl border border-gray-200 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <!-- Información del documento -->
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Icono del tipo de archivo -->
                            <div class="flex-shrink-0">
                                @php
                                    $extension = pathinfo($documento['nombre_original'] ?? $documento['nombre'] ?? '', PATHINFO_EXTENSION);
                                    $iconClass = match(strtolower($extension)) {
                                        'pdf' => 'fas fa-file-pdf text-red-600',
                                        'png', 'jpg', 'jpeg' => 'fas fa-file-image text-blue-600',
                                        'mp3' => 'fas fa-file-audio text-purple-600',
                                        'doc', 'docx' => 'fas fa-file-word text-blue-800',
                                        'xls', 'xlsx' => 'fas fa-file-excel text-green-600',
                                        default => 'fas fa-file text-gray-600',
                                    };
                                @endphp
                                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-200">
                                    <i class="{{ $iconClass }} text-xl"></i>
                                </div>
                            </div>

                            <!-- Detalles del documento -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 mb-1 truncate">
                                    {{ $documento['nombre_original'] ?? $documento['nombre'] ?? 'Documento' }}
                                </h4>
                                <div class="flex items-center space-x-4 text-xs text-gray-500 mb-2">
                                    <span class="flex items-center">
                                        <i class="fas fa-weight-hanging mr-1"></i>
                                        {{ $documento['tamaño_formateado'] ?? $documento['tamaño'] ?? 'N/A' }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $documento['fecha_carga'] ?? $documento['created_at'] ?? 'N/A' }}
                                    </span>
                                </div>
                                
                                <!-- Tipo de documento -->
                                @if(isset($documento['catalogo']))
                                    <div class="mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $documento['catalogo']['nombre'] ?? 'Documento' }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Estado de aprobación -->
                                <div class="flex items-center space-x-2">
                                    @if(isset($documento['aprobado']))
                                        @if($documento['aprobado'])
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pendiente
                                            </span>
                                        @endif
                                    @endif

                                    @if(isset($documento['fecha_cotejo']) && $documento['fecha_cotejo'])
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-eye mr-1"></i>
                                            Cotejado
                                        </span>
                                    @endif
                                </div>

                                <!-- Observaciones -->
                                @if(isset($documento['observaciones']) && $documento['observaciones'])
                                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                        <div class="flex items-start space-x-2">
                                            <i class="fas fa-comment-alt text-amber-600 text-sm mt-0.5"></i>
                                            <div class="flex-1">
                                                <p class="text-xs font-medium text-amber-800">Observaciones:</p>
                                                <p class="text-xs text-amber-700 mt-1">{{ $documento['observaciones'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex items-center space-x-2 ml-4">
                            <!-- Ver documento -->
                            @php
                                $urlDocumento = null;
                                if (is_object($documento) && isset($documento->id) && isset($tramite)) {
                                    $filename = basename($documento->ruta_archivo ?? 'documento');
                                    $urlDocumento = route('documento.ver', [
                                        'tramite' => $tramite->id,
                                        'archivo' => $documento->id,
                                        'filename' => $filename
                                    ]);
                                } elseif (is_array($documento) && isset($documento['url_descarga'])) {
                                    $urlDocumento = $documento['url_descarga'];
                                }
                            @endphp
                            
                            <a href="{{ $urlDocumento ?? '#' }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-eye mr-1"></i>
                                Ver
                            </a>

                            <!-- Descargar -->
                            <a href="{{ $urlDocumento ?? '#' }}" 
                               download
                               class="inline-flex items-center px-3 py-2 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <i class="fas fa-download mr-1"></i>
                                Descargar
                            </a>

                            @if($editable)
                                <!-- Comentar -->
                                <button type="button" 
                                        onclick="toggleCommentSection('{{ $documento['id'] ?? $loop->index }}')"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                                        title="Agregar comentario">
                                    <i class="fas fa-comment mr-1"></i>
                                    Comentar
                                </button>
                                
                                <!-- Aprobar/Rechazar -->
                                <div class="flex items-center space-x-1">
                                    <button type="button" 
                                            onclick="approveDocument('{{ $documento['id'] ?? $loop->index }}', true)"
                                            class="inline-flex items-center px-2 py-2 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                            title="Aprobar documento">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" 
                                            onclick="approveDocument('{{ $documento['id'] ?? $loop->index }}', false)"
                                            class="inline-flex items-center px-2 py-2 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            title="Rechazar documento">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($editable)
                        <!-- Sección de comentarios (inicialmente oculta) -->
                        <div id="commentSection-{{ $documento['id'] ?? $loop->index }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                <h5 class="text-sm font-medium text-gray-900 mb-3">Comentarios de Revisión</h5>
                                
                                <!-- Formulario para nuevo comentario -->
                                <form onsubmit="submitComment(event, '{{ $documento['id'] ?? $loop->index }}')" class="mb-4">
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Agregar comentario:</label>
                                        <textarea 
                                            name="comentario"
                                            rows="3" 
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                            placeholder="Escribe tu comentario sobre este documento..."
                                            required></textarea>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="decision" value="aprobar" class="text-green-600 focus:ring-green-500">
                                                <span class="ml-2 text-xs text-green-700">Aprobar</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="decision" value="rechazar" class="text-red-600 focus:ring-red-500">
                                                <span class="ml-2 text-xs text-red-700">Rechazar</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="decision" value="pendiente" class="text-yellow-600 focus:ring-yellow-500" checked>
                                                <span class="ml-2 text-xs text-yellow-700">Solo comentar</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" 
                                                    onclick="toggleCommentSection('{{ $documento['id'] ?? $loop->index }}')"
                                                    class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                                Cancelar
                                            </button>
                                            <button type="submit" 
                                                    class="px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                
                                <!-- Lista de comentarios existentes -->
                                <div id="commentsList-{{ $documento['id'] ?? $loop->index }}" class="space-y-3">
                                    @if(isset($documento['comentarios_revision']) && count($documento['comentarios_revision']) > 0)
                                        @foreach($documento['comentarios_revision'] as $comentario)
                                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                                <div class="flex items-start justify-between mb-2">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs font-medium text-gray-900">
                                                            {{ $comentario['usuario'] ?? 'Revisor' }}
                                                        </span>
                                                        @if(isset($comentario['decision']))
                                                            @if($comentario['decision'] === 'aprobar')
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                    <i class="fas fa-check mr-1"></i>
                                                                    Aprobado
                                                                </span>
                                                            @elseif($comentario['decision'] === 'rechazar')
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                    <i class="fas fa-times mr-1"></i>
                                                                    Rechazado
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $comentario['fecha'] ?? now()->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-700">{{ $comentario['comentario'] }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-xs text-gray-500 italic">No hay comentarios para este documento.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Resumen de documentos -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total de documentos -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Total</p>
                            <p class="text-lg font-bold text-blue-800">{{ count($documentos) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Documentos aprobados -->
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-900">Aprobados</p>
                            <p class="text-lg font-bold text-green-800">
                                {{ collect($documentos)->where('aprobado', true)->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Documentos pendientes -->
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-yellow-900">Pendientes</p>
                            <p class="text-lg font-bold text-yellow-800">
                                {{ collect($documentos)->where('aprobado', false)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Estado sin documentos -->
        <div class="text-center py-12">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-upload text-gray-400 text-2xl"></i>
                </div>
                <div class="text-gray-500">
                    <p class="font-medium text-sm">No hay documentos adjuntos</p>
                    <p class="text-xs mt-1">Este trámite no tiene documentos cargados.</p>
                </div>
            </div>
        </div>
    @endif
</div>

@if($editable)
<script>
// Función para mostrar/ocultar la sección de comentarios
function toggleCommentSection(documentId) {
    const section = document.getElementById(`commentSection-${documentId}`);
    if (section) {
        section.classList.toggle('hidden');
        
        // Si se está mostrando, hacer scroll hacia la sección
        if (!section.classList.contains('hidden')) {
            section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}

// Función para aprobar/rechazar documento directamente
function approveDocument(documentId, approved) {
    const action = approved ? 'aprobar' : 'rechazar';
    const message = approved ? '¿Estás seguro de aprobar este documento?' : '¿Estás seguro de rechazar este documento?';
    
    if (confirm(message)) {
        // Aquí implementarías la llamada AJAX para actualizar el estado
        updateDocumentStatus(documentId, approved ? 'aprobado' : 'rechazado', '');
    }
}

// Función para enviar comentario
function submitComment(event, documentId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const comentario = formData.get('comentario');
    const decision = formData.get('decision');
    
    if (!comentario.trim()) {
        alert('Por favor, escribe un comentario.');
        return;
    }
    
    // Aquí implementarías la llamada AJAX para guardar el comentario
    saveDocumentComment(documentId, comentario, decision);
    
    // Limpiar formulario
    form.reset();
    form.querySelector('input[value="pendiente"]').checked = true;
    
    // Ocultar sección de comentarios
    toggleCommentSection(documentId);
}

// Función para actualizar el estado del documento (implementar con AJAX)
function updateDocumentStatus(documentId, status, comment) {
    // Ejemplo de implementación con fetch
    fetch(`/revision/documento/${documentId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            status: status,
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar la UI para reflejar el cambio
            location.reload(); // O actualizar dinámicamente
        } else {
            alert('Error al actualizar el estado del documento: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}

// Función para guardar comentario (implementar con AJAX)
function saveDocumentComment(documentId, comment, decision) {
    fetch(`/revision/documento/${documentId}/add-comment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            comment: comment,
            decision: decision
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Agregar el comentario a la lista sin recargar la página
            addCommentToList(documentId, {
                usuario: data.usuario || 'Revisor',
                comentario: comment,
                decision: decision,
                fecha: new Date().toLocaleString('es-ES')
            });
            
            // Si hay una decisión, actualizar el estado del documento
            if (decision !== 'pendiente') {
                updateDocumentStatus(documentId, decision === 'aprobar' ? 'aprobado' : 'rechazado', comment);
            }
        } else {
            alert('Error al guardar el comentario: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}

// Función para agregar comentario a la lista dinámicamente
function addCommentToList(documentId, commentData) {
    const commentsList = document.getElementById(`commentsList-${documentId}`);
    if (!commentsList) return;
    
    // Remover mensaje de "no hay comentarios" si existe
    const noCommentsMsg = commentsList.querySelector('.italic');
    if (noCommentsMsg) {
        noCommentsMsg.remove();
    }
    
    // Crear elemento del comentario
    const commentElement = document.createElement('div');
    commentElement.className = 'bg-gray-50 rounded-lg p-3 border border-gray-200';
    
    let decisionBadge = '';
    if (commentData.decision === 'aprobar') {
        decisionBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check mr-1"></i>Aprobado</span>';
    } else if (commentData.decision === 'rechazar') {
        decisionBadge = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times mr-1"></i>Rechazado</span>';
    }
    
    commentElement.innerHTML = `
        <div class="flex items-start justify-between mb-2">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-medium text-gray-900">${commentData.usuario}</span>
                ${decisionBadge}
            </div>
            <span class="text-xs text-gray-500">${commentData.fecha}</span>
        </div>
        <p class="text-xs text-gray-700">${commentData.comentario}</p>
    `;
    
    // Agregar al inicio de la lista
    commentsList.insertBefore(commentElement, commentsList.firstChild);
}
</script>
@endif