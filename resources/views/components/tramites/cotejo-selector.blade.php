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
            <option value="{{ $archivo['id'] }}" data-nombre="{{ $archivo['nombre_catalogo'] ?? $archivo['nombre_original'] }}" data-original="{{ $archivo['nombre_original'] }}">
                {{ $archivo['nombre_catalogo'] ?? $archivo['nombre_original'] }}
            </option>
        @endforeach
    </select>
</div>

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
            class="w-full h-auto aspect-[4/3] min-h-[500px] max-h-[80vh]" 
            frameborder="0">
        </iframe>
    </div>
</div>

<script>
function mostrarArchivo(seccion, selectElement) {
    const viewer = document.getElementById(`archivo_viewer_${seccion}`);
    const archivoInfo = document.getElementById(`archivo_info_${seccion}`);
    const pdfViewer = document.getElementById(`pdf_viewer_${seccion}`);
    
    if (selectElement.value) {
        const option = selectElement.options[selectElement.selectedIndex];
        const nombreCatalogo = option.getAttribute('data-nombre');
        const nombreOriginal = option.getAttribute('data-original');
        
        archivoInfo.innerHTML = `${nombreOriginal}`;
        
        const baseUrl = '{{ route("revisiones.mostrar-archivo", ":id") }}';
        pdfViewer.src = baseUrl.replace(':id', selectElement.value);
        
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