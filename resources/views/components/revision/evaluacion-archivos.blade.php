@props(['archivosSubidos', 'seccion', 'modoCorreccion' => false])

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <!-- Header -->
    <div class="px-3 xs:px-4 sm:px-6 lg:px-8 py-3 xs:py-4 sm:py-5 lg:py-6 border-b border-gray-200">
        <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3 xs:gap-4">
            <div class="min-w-0 flex-1">
                <h3 class="text-base xs:text-lg sm:text-xl lg:text-2xl font-semibold text-gray-900 leading-tight">
                    {{ $modoCorreccion ? 'Corrección de Documentos' : 'Documentos del Trámite' }}
                </h3>
                <p class="text-xs xs:text-sm sm:text-base text-gray-600 mt-1">
                    {{ $modoCorreccion ? 'Sube nuevos documentos para los rechazados' : 'Documentos cargados para revisión' }}
                </p>
            </div>
            <div class="text-center xs:text-right">
                <div class="text-xl xs:text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-800">{{ count($archivosSubidos) }}</div>
                <div class="text-xs xs:text-sm text-blue-600">Documentos</div>
            </div>
        </div>
    </div>

    @if($modoCorreccion)
        <div class="p-4 border-b border-gray-200">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="w-6 h-6 text-blue-700 mr-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg></div>
                    <div>
                        <p class="font-bold text-blue-800">Instrucciones para la Corrección</p>
                        <p class="text-sm text-blue-700">
                            Revise los documentos marcados como <strong class="font-semibold">"Rechazado"</strong>. Debe subir una nueva versión corregida para cada uno de ellos para continuar con su trámite.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($archivosSubidos && count($archivosSubidos) > 0)
        <!-- Lista de archivos -->
        <div class="grid gap-4 p-4">
            @foreach($archivosSubidos as $index => $archivo)
                <div class="group relative bg-gradient-to-r from-slate-50 to-white border border-slate-200 rounded-xl p-4 hover:border-slate-300 hover:shadow-lg transition-all duration-300" data-archivo-id="{{ $archivo['id'] }}">
                    
                    @php
                        $status = $archivo['status'] ?? 'Pendiente';
                        $statusConfig = [
                            'Pendiente' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200', 'icon' => 'clock'],
                            'Aprobado' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200', 'icon' => 'check'],
                            'Rechazado' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'border' => 'border-rose-200', 'icon' => 'x']
                        ];
                        $config = $statusConfig[$status] ?? $statusConfig['Pendiente'];
                    @endphp

                    <!-- Contenido principal -->
                    <div class="flex items-start gap-4">
                        <!-- Icono del archivo -->
                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-slate-200 shadow-sm group-hover:border-slate-300 transition-colors">
                            @switch($archivo['extension'])
                                @case('pdf')
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                                    @break
                                @case('jpg')
                                @case('jpeg')
                                @case('png')
                                <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                    </svg>
                                    @break
                                @default
                                <svg class="w-6 h-6 text-slate-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                    </svg>
                            @endswitch
                        </div>

                        <!-- Información del archivo -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-slate-900 truncate group-hover:text-slate-700 transition-colors">
                                {{ $archivo['nombre_catalogo'] ?? 'Documento sin Categoría' }}
                            </h4>
                            <p class="text-xs text-slate-500 truncate mt-0.5">
                                {{ $archivo['nombre_original'] }}
                            </p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-slate-500 font-medium">
                                    {{ number_format($archivo['tamaño'] / 1024, 1) }} KB
                                </span>
                                <span class="text-xs text-slate-300">•</span>
                                <span class="text-xs text-slate-500 font-mono uppercase tracking-wide">
                                    {{ $archivo['extension'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Badge de estado -->
                        <div class="flex-shrink-0 status-badge-container">
                             <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold leading-none {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }} border shadow-sm">
                                @if($config['icon'] === 'check')
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                @elseif($config['icon'] === 'x')
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                @else
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                @endif
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <!-- Comentarios del revisor (solo mostrar si hay comentarios) -->
                    @if(!empty($archivo['comentario_revision']))
                    <div class="mt-4 pt-3 border-t border-dashed border-slate-200">
                        <div class="bg-rose-50 border border-rose-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-rose-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-rose-800 mb-1">Observaciones del Revisor:</p>
                                    <p class="text-sm text-rose-700 leading-relaxed">{{ $archivo['comentario_revision'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Área de acciones -->
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        @if($modoCorreccion)
                            @if(($archivo['status'] ?? 'Pendiente') === 'Rechazado')
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                                    <a href="{{ route('revisiones.mostrar-archivo', $archivo['id']) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500/50 transition-all duration-200 w-full sm:w-auto">
                                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver Original
                                    </a>
                                    <label for="nuevo_archivo_{{ $archivo['id'] }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all duration-200 cursor-pointer w-full sm:w-auto">
                                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        Subir Corrección
                                    </label>
                                    <input type="file" id="nuevo_archivo_{{ $archivo['id'] }}" name="documentos_correccion[{{ $archivo['catalogo_archivo_id'] }}]" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" data-archivo-id="{{ $archivo['id'] }}" onchange="updateFileName(this, 'nombre_nuevo_archivo_{{ $archivo['id'] }}')">
                                </div>
                                <div id="nombre_nuevo_archivo_{{ $archivo['id'] }}" class="hidden mt-3"></div>
                            @else
                                <div class="flex justify-end">
                                    <a href="{{ route('revisiones.mostrar-archivo', $archivo['id']) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/50 transition-all duration-200">
                                        Ver Documento
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- MODO REVISIÓN -->
                            <div class="space-y-3">
                                <textarea id="textarea_archivo_{{ $archivo['id'] }}" placeholder="Agregar observaciones sobre este documento..." class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all duration-200 resize-none text-sm" rows="2">{{ $archivo['comentario_revision'] ?? '' }}</textarea>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <a href="{{ route('revisiones.mostrar-archivo', $archivo['id']) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-lg hover:bg-slate-200 hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500/50 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Ver Documento
                                    </a>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="evaluarArchivo({{ $archivo['id'] }}, 'Rechazado')" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-rose-300 text-rose-700 text-sm font-medium rounded-lg hover:bg-rose-50 hover:border-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-500/50 transition-all duration-200 flex-1">
                                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Rechazar
                                        </button>
                                        <button type="button" onclick="evaluarArchivo({{ $archivo['id'] }}, 'Aprobado')" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all duration-200 flex-1">
                                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Aprobar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Estado vacío -->
        <div class="p-4 text-center">
            <div class="w-12 h-12 mx-auto mb-3 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-900 mb-1">No hay documentos</h3>
            <p class="text-sm text-slate-500">No se han cargado documentos para este trámite.</p>
        </div>
    @endif
</div>

<script>
function updateFileName(input, nameElementId) {
    const nameElement = document.getElementById(nameElementId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        nameElement.innerHTML = `
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-emerald-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-emerald-800 font-medium">Nuevo documento: <span class="font-semibold">${fileName}</span></p>
                </div>
            </div>
        `;
        nameElement.classList.remove('hidden');
        
        // Cambiar el color del borde del contenedor
        const container = input.closest('.group');
        if (container) {
            container.classList.remove('border-slate-200');
            container.classList.add('border-emerald-300', 'ring-2', 'ring-emerald-200');
        }
    } else {
        nameElement.classList.add('hidden');
    }
}

// Función simplificada para evaluar archivo
async function evaluarArchivo(archivoId, decision) {
    const textarea = document.getElementById(`textarea_archivo_${archivoId}`);
    const comentario = textarea ? textarea.value.trim() : '';
    const container = document.querySelector(`[data-archivo-id="${archivoId}"]`);

    // --- Start UI Update ---
    if (container) {
        // Update status badge
        const statusBadgeContainer = container.querySelector('.status-badge-container');
        if (statusBadgeContainer) {
            const statusConfig = {
                'Aprobado': { bg: 'bg-emerald-100', text: 'text-emerald-800', border: 'border-emerald-200', icon: 'check' },
                'Rechazado': { bg: 'bg-rose-100', text: 'text-rose-800', border: 'border-rose-200', icon: 'x' }
            };
            const config = statusConfig[decision];
            
            let iconSvg = '';
            if (config.icon === 'check') {
                iconSvg = `<svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>`;
            } else if (config.icon === 'x') {
                iconSvg = `<svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>`;
            }

            statusBadgeContainer.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold leading-none ${config.bg} ${config.text} ${config.border} border shadow-sm">
                    ${iconSvg}
                    ${decision}
                </span>`;
        }

        // Highlight border
        container.classList.remove('border-rose-300', 'border-emerald-300', 'ring-2', 'ring-rose-200', 'ring-emerald-200');
        if (decision === 'Aprobado') {
            container.classList.add('border-emerald-300', 'ring-2', 'ring-emerald-200');
        } else if (decision === 'Rechazado') {
            container.classList.add('border-rose-300', 'ring-2', 'ring-rose-200');
        }
    }
    // --- End UI Update ---

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
        
        const data = await response.json();

        // Si se necesita actualizar más la UI con datos del servidor
        if(data.comentario_revision && container) {
            // Lógica para mostrar el comentario actualizado si es necesario
        }

    } catch (error) {
        console.error('Error:', error);
        // Revertir UI en caso de error si es necesario
    }
}
</script>