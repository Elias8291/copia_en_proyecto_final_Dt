@props([
    'documentos' => [],
    'seccion' => '',
    'mostrar' => false,
    'tramiteId' => null
])

@php
    // Mostrar todos los documentos del trámite
    $documentosFiltrados = collect($documentos);
@endphp

<div id="documento-comparador-{{ $seccion }}" class="hidden w-full transition-all duration-300">
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 h-full">

        <!-- Selector -->
        <div class="mb-4">
            <label for="documento-select-{{ $seccion }}" class="block text-sm font-medium text-gray-700 mb-2">
                Seleccionar documento:
            </label>
            <select id="documento-select-{{ $seccion }}" 
                    onchange="cambiarDocumento('{{ $seccion }}', this.value)"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Seleccionar --</option>
                @foreach($documentosFiltrados as $documento)
                    @php
                        // Manejar tanto arrays como objetos Eloquent
                        $nombreArchivo = is_array($documento) 
                            ? ($documento['nombre'] ?? $documento['nombre_original'] ?? basename($documento['ruta'] ?? ''))
                            : ($documento->catalogoArchivo->nombre ?? $documento->nombre_original ?? basename($documento->ruta_archivo ?? ''));
                        
                        // Obtener la ruta del documento usando la misma lógica que documentos.blade.php
                        $documentoId = is_array($documento) 
                            ? ($documento['id'] ?? '')
                            : ($documento->id ?? '');
                        
                        // Construir la ruta para ver el documento
                        $tramiteId = $tramiteId ?? (request()->route('tramite') ? (is_object(request()->route('tramite')) ? request()->route('tramite')->id : request()->route('tramite')) : null);
                        
                        $rutaVerDocumento = route('revision.verDocumento', [
                            'tramite' => $tramiteId,
                            'archivo' => $documentoId,
                            'filename' => basename(is_array($documento) ? ($documento['ruta_archivo'] ?? 'documento') : ($documento->ruta_archivo ?? 'documento'))
                        ]);
                        
                        // Determinar el tipo de archivo basado en la extensión
                        $rutaArchivo = is_array($documento) 
                            ? ($documento['ruta_archivo'] ?? '')
                            : ($documento->ruta_archivo ?? '');
                        $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION);
                        $tipoArchivo = match(strtolower($extension)) {
                            'pdf' => 'application/pdf',
                            'png', 'jpg', 'jpeg', 'gif', 'webp' => 'image/' . strtolower($extension),
                            'mp3', 'wav', 'ogg' => 'audio/' . strtolower($extension),
                            'mp4', 'avi', 'mov' => 'video/' . strtolower($extension),
                            'doc', 'docx' => 'application/msword',
                            'xls', 'xlsx' => 'application/vnd.ms-excel',
                            default => 'application/octet-stream'
                        };
                    @endphp
                    <option value="{{ $documentoId }}" 
                            data-tipo="{{ $tipoArchivo }}"
                            data-ruta="{{ $rutaVerDocumento }}"
                            data-nombre="{{ $nombreArchivo }}">
                        {{ $nombreArchivo }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Contenido -->
        <div id="documento-contenido-{{ $seccion }}" class="space-y-3">
            <div class="flex items-center justify-center h-full min-h-[600px] bg-gray-50 rounded-lg border-2 border-dashed border-gray-300" style="min-height: 600px;">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm text-gray-500 mb-2">Seleccione un documento para previsualizar</p>
                    <p class="text-xs text-gray-400">La vista previa aparecerá aquí</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleComparador(seccion) {
    const comparador = document.getElementById(`documento-comparador-${seccion}`);
    const grid = document.getElementById(`${seccion}Grid`);
    const button = document.querySelector(`[onclick="toggleComparador('${seccion}')"]`);
    
    if (comparador.classList.contains('hidden')) {
        comparador.classList.remove('hidden');
        grid.classList.remove('grid-cols-1');
        grid.classList.add('grid-cols-2');
        if (button) {
            button.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Ocultar
            `;
        }
    } else {
        comparador.classList.add('hidden');
        grid.classList.remove('grid-cols-2');
        grid.classList.add('grid-cols-1');
        if (button) {
            button.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Comparar
            `;
        }
    }
}

function cambiarDocumento(seccion, documentoId) {
    const contenido = document.getElementById(`documento-contenido-${seccion}`);
    const select = document.getElementById(`documento-select-${seccion}`);
    const option = select.querySelector(`option[value="${documentoId}"]`);
    
    if (!documentoId || !option) {
        contenido.innerHTML = `
            <div class="flex items-center justify-center h-full min-h-[600px] bg-gray-50 rounded-lg border-2 border-dashed border-gray-300" style="min-height: 600px;">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm text-gray-500 mb-2">Seleccione un documento para previsualizar</p>
                    <p class="text-xs text-gray-400">La vista previa aparecerá aquí</p>
                </div>
            </div>
        `;
        return;
    }
    
    const tipo = option.dataset.tipo;
    const ruta = option.dataset.ruta;
    const nombre = option.textContent;
    
    let documentoHTML = '';
    
    // Determinar el tipo de archivo para mostrar el badge correcto
    let tipoBadge = 'Otro formato';
    let badgeColor = 'text-gray-600 bg-gray-100';
    
    if (tipo && tipo.includes('image')) {
        tipoBadge = 'Imagen';
        badgeColor = 'text-green-600 bg-green-100';
    } else if (tipo && tipo.includes('pdf')) {
        tipoBadge = 'PDF';
        badgeColor = 'text-blue-600 bg-blue-100';
    } else if (tipo && tipo.includes('audio')) {
        tipoBadge = 'Audio';
        badgeColor = 'text-purple-600 bg-purple-100';
    } else if (tipo && tipo.includes('video')) {
        tipoBadge = 'Video';
        badgeColor = 'text-orange-600 bg-orange-100';
    } else if (tipo && tipo.includes('msword')) {
        tipoBadge = 'Word';
        badgeColor = 'text-blue-800 bg-blue-100';
    } else if (tipo && tipo.includes('excel')) {
        tipoBadge = 'Excel';
        badgeColor = 'text-green-600 bg-green-100';
    }
    
    if (tipo && tipo.includes('image')) {
        documentoHTML = `
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Vista previa de imagen</span>
                        <div class="flex items-center space-x-3">
                            <button onclick="zoomDocumento('${seccion}', 'reset')" class="text-gray-500 hover:text-gray-700 text-xs" title="Restablecer zoom">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                            <button onclick="zoomDocumento('${seccion}', 'out')" class="text-gray-500 hover:text-gray-700" title="Alejar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                                </svg>
                            </button>
                            <button onclick="zoomDocumento('${seccion}', 'in')" class="text-gray-500 hover:text-gray-700" title="Acercar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                </svg>
                            </button>
                            <a href="${ruta}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Abrir
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h-full min-h-[600px] overflow-auto bg-gray-50">
                    <img src="${ruta}" alt="Vista previa" class="w-full h-full object-contain transition-transform duration-200" id="documento-preview-${seccion}" style="min-height: 600px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="hidden h-full flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 mb-3">Error al cargar imagen</p>
                            <a href="${ruta}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-md">Abrir archivo</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (tipo && tipo.includes('pdf')) {
        documentoHTML = `
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Vista previa del documento</span>
                        <a href="${ruta}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Abrir en nueva pestaña
                        </a>
                    </div>
                </div>
                <div class="h-full min-h-[600px]">
                    <iframe src="${ruta}" class="w-full h-full border-0" style="min-height: 600px;" onload="this.style.display='block';" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"></iframe>
                    <div class="hidden h-full flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 mb-3">Error al cargar PDF</p>
                            <a href="${ruta}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-md">Abrir archivo</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else {
        documentoHTML = `
            <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <div class="bg-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Información del archivo</span>
                        <a href="${ruta}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Abrir en nueva pestaña
                        </a>
                    </div>
                </div>
                <div class="h-full min-h-[600px] bg-gray-50 flex items-center justify-center" style="min-height: 600px;">
                    <div class="text-center">
                        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm text-gray-500 mb-2">Vista previa no disponible</p>
                        <p class="text-xs text-gray-400 mb-4">Este tipo de archivo no puede ser previsualizado</p>
                        <a href="${ruta}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-4 py-2 rounded-md">Descargar archivo</a>
                    </div>
                </div>
            </div>
        `;
    }
    
    contenido.innerHTML = documentoHTML;
}

function zoomDocumento(seccion, accion) {
    const preview = document.getElementById(`documento-preview-${seccion}`);
    if (!preview) return;
    
    let currentZoom = parseFloat(preview.style.transform.replace('scale(', '').replace(')', '')) || 1;
    
    if (accion === 'in') {
        currentZoom = Math.min(currentZoom * 1.2, 3);
    } else if (accion === 'out') {
        currentZoom = Math.max(currentZoom / 1.2, 0.5);
    } else if (accion === 'reset') {
        currentZoom = 1;
    }
    
    preview.style.transform = `scale(${currentZoom})`;
    
    // Mostrar indicador de zoom
    const zoomIndicator = document.getElementById(`zoom-indicator-${seccion}`);
    if (zoomIndicator) {
        zoomIndicator.textContent = `${Math.round(currentZoom * 100)}%`;
    }
}


</script> 