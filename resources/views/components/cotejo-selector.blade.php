@props(['archivosSubidos', 'seccion' => ''])

<div class="mb-4">
    <label for="archivo_cotejo_{{ $seccion }}" class="block text-sm font-medium text-gray-600 mb-2">
        Documento Subido
    </label>
    <select 
        id="archivo_cotejo_{{ $seccion }}" 
        name="archivo_cotejo_{{ $seccion }}" 
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
        onchange="mostrarArchivo('{{ $seccion }}', this)"
    >
        <option value="">Seleccionar documento...</option>
        @foreach($archivosSubidos as $archivo)
            <option value="{{ $archivo['id'] }}" data-nombre="{{ $archivo['nombre'] }}" data-original="{{ $archivo['nombre_original'] }}">
                {{ $archivo['nombre'] }}
            </option>
        @endforeach
    </select>
</div>

<!-- Visor de PDF integrado -->
<div id="archivo_viewer_{{ $seccion }}" class="hidden mb-4">
    <div class="border border-gray-300 rounded-lg overflow-hidden bg-white">
        <div class="flex items-center justify-between p-2 bg-gray-50 border-b">
            <div id="archivo_info_{{ $seccion }}" class="text-xs text-gray-600"></div>
            <button type="button" onclick="cerrarViewer('{{ $seccion }}')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <iframe 
            id="pdf_viewer_{{ $seccion }}" 
            class="w-full h-96" 
            frameborder="0"
            style="min-height: 400px;">
        </iframe>
    </div>
</div>

<div class="space-y-3">
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Estado de Verificación</label>
        <div class="flex space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" name="estado_{{ $seccion }}" value="conforme" class="form-radio text-green-600" onchange="toggleObservaciones('{{ $seccion }}', false)">
                <span class="ml-2 text-sm text-green-700">✓ Conforme</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="estado_{{ $seccion }}" value="no_conforme" class="form-radio text-red-600" onchange="toggleObservaciones('{{ $seccion }}', true)">
                <span class="ml-2 text-sm text-red-700">✗ No Conforme</span>
            </label>
        </div>
    </div>
    
    <div id="observaciones_container_{{ $seccion }}" class="hidden">
        <label for="observaciones_{{ $seccion }}" class="block text-sm font-medium text-gray-600 mb-1">
            Observaciones <span class="text-red-500">*</span>
        </label>
        <textarea 
            id="observaciones_{{ $seccion }}" 
            name="observaciones_{{ $seccion }}" 
            rows="3" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none"
            placeholder="Especifica las observaciones del documento no conforme..."
        ></textarea>
    </div>
</div>

<script>
function toggleObservaciones(seccion, mostrar) {
    const container = document.getElementById(`observaciones_container_${seccion}`);
    const textarea = document.getElementById(`observaciones_${seccion}`);
    
    if (mostrar) {
        container.classList.remove('hidden');
        textarea.setAttribute('required', 'required');
    } else {
        container.classList.add('hidden');
        textarea.removeAttribute('required');
        textarea.value = ''; // Limpiar observaciones si se cambia a conforme
    }
}

function mostrarArchivo(seccion, selectElement) {
    const viewer = document.getElementById(`archivo_viewer_${seccion}`);
    const archivoInfo = document.getElementById(`archivo_info_${seccion}`);
    const pdfViewer = document.getElementById(`pdf_viewer_${seccion}`);
    
    if (selectElement.value) {
        const option = selectElement.options[selectElement.selectedIndex];
        const nombreCatalogo = option.getAttribute('data-nombre');
        const nombreOriginal = option.getAttribute('data-original');
        
        archivoInfo.innerHTML = `${nombreOriginal}`;
        
        // Cargar el PDF en el iframe usando la ruta segura
        pdfViewer.src = `/archivo/${selectElement.value}`;
        
        viewer.classList.remove('hidden');
    } else {
        viewer.classList.add('hidden');
        pdfViewer.src = '';
    }
}

function cerrarViewer(seccion) {
    const viewer = document.getElementById(`archivo_viewer_${seccion}`);
    const select = document.getElementById(`archivo_cotejo_${seccion}`);
    const pdfViewer = document.getElementById(`pdf_viewer_${seccion}`);
    
    viewer.classList.add('hidden');
    select.value = '';
    pdfViewer.src = '';
}
</script> 