@props(['tramite', 'documentos' => [], 'editable' => false])

@if(count($documentos) > 0)
    <div class="space-y-4">
        @foreach($documentos as $documento)
            <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-sm transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 flex-1">
                        <div class="flex-shrink-0">
                            @php
                                $extension = pathinfo($documento['nombre_original'] ?? $documento['nombre'] ?? '', PATHINFO_EXTENSION);
                                $iconData = match(strtolower($extension)) {
                                    'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => 'text-red-600'],
                                    'png', 'jpg', 'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-blue-600'],
                                    'mp3' => ['icon' => 'fas fa-file-audio', 'color' => 'text-purple-600'],
                                    'doc', 'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-blue-800'],
                                    'xls', 'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => 'text-green-600'],
                                    default => ['icon' => 'fas fa-file', 'color' => 'text-gray-600'],
                                };
                            @endphp
                            <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center">
                                <i class="{{ $iconData['icon'] }} {{ $iconData['color'] }} text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $documento['nombre_original'] ?? $documento['nombre'] ?? 'Documento' }}
                                </h4>
                                <div class="flex items-center space-x-2 ml-2">
                                    @if(isset($documento['catalogo']))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $documento['catalogo']['nombre'] ?? 'Documento' }}
                                        </span>
                                    @endif
                                    @if(isset($documento['aprobado']))
                                        @if($documento['aprobado'] === true)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Aprobado
                                            </span>
                                        @elseif($documento['aprobado'] === false)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Rechazado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                                </svg>
                                                Pendiente
                                            </span>
                                        @endif
                                    @endif
                                    @if(isset($documento['fecha_cotejo']) && $documento['fecha_cotejo'])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            Cotejado
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span>{{ $documento['tamaño_formateado'] ?? $documento['tamaño'] ?? 'N/A' }}</span>
                                <span>•</span>
                                <span>{{ $documento['fecha_carga'] ?? $documento['created_at'] ?? 'N/A' }}</span>
                            </div>
                            @if(isset($documento['observaciones']) && $documento['observaciones'])
                                <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded text-xs">
                                    <span class="font-medium text-amber-800">Observaciones:</span>
                                    <span class="text-amber-700">{{ $documento['observaciones'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <a href="{{ route('revision.verDocumento', [
                            'tramite' => is_object($tramite) ? $tramite->id : $tramite['id'],
                            'archivo' => $documento['id'],
                            'filename' => basename($documento['ruta_archivo'] ?? 'documento')
                        ]) }}" 
                        target="_blank" 
                        class="group inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors" 
                        title="Ver documento">
                            <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        @if($editable)
                            <button type="button" 
                                onclick="toggleDocumentComment({{ $documento['id'] }})"
                                class="group inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors" 
                                title="Comentar documento">
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-[#9D2449] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
                @if($editable)
                    <div id="comment-form-{{ $documento['id'] }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                        <form class="documento-review-form" data-documento-id="{{ $documento['id'] }}">
                            <div class="mb-3">
                                <textarea 
                                    name="comentario" 
                                    rows="2"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] resize-none"
                                    placeholder="Comentario sobre este documento..."></textarea>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center text-xs">
                                        <input type="radio" name="decision_documento" value="aprobar" class="text-green-600 focus:outline-none focus:ring-green-500 mr-1">
                                        <span class="text-green-700">Aprobar</span>
                                    </label>
                                    <label class="flex items-center text-xs">
                                        <input type="radio" name="decision_documento" value="rechazar" class="text-red-600 focus:outline-none focus:ring-red-500 mr-1">
                                        <span class="text-red-700">Rechazar</span>
                                    </label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                        onclick="toggleDocumentComment({{ $documento['id'] }})"
                                        class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit" 
                                        class="px-3 py-1 bg-[#9D2449] text-white rounded text-xs hover:bg-[#8a203f] transition-colors">
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12">
        <div class="flex flex-col items-center justify-center space-y-3">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="text-gray-500">
                <p class="text-sm font-medium">No hay documentos adjuntos</p>
                <p class="text-xs mt-1">Este trámite no tiene documentos cargados.</p>
            </div>
        </div>
    </div>
@endif

@if($editable)
<script>
function toggleDocumentComment(documentoId) {
    const form = document.getElementById(`comment-form-${documentoId}`);
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
        form.querySelector('textarea').value = '';
        // No hay opción comentar, así que no es necesario marcar checked
    }
}

// AJAX para guardar comentario y estado

document.querySelectorAll('.documento-review-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const documentoId = this.getAttribute('data-documento-id');
        const comentario = this.querySelector('textarea[name="comentario"]').value;
        const decision = this.querySelector('input[name="decision_documento"]:checked').value;
        let aprobado = null;
        if (decision === 'aprobar') aprobado = true;
        else if (decision === 'rechazar') aprobado = false;
        // Enviar AJAX para comentario
        fetch(`/revision/documento/${documentoId}/comentario`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ comentario })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Cambiar estado tanto en aprobar como en rechazar
                if (aprobado !== null) {
                    fetch(`/revision/documento/${documentoId}/estado`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ aprobado })
                    })
                    .then(res2 => res2.json())
                    .then(data2 => {
                        if (data2.success) {
                            location.reload();
                        }
                    });
                } else {
                    location.reload();
                }
            }
        });
    });
});
</script>
@endif