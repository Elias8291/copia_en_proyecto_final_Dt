@props(['tramite', 'documentos' => [], 'editable' => false])

@if(count($documentos) > 0)
    <div class="space-y-4">
        @foreach($documentos as $documento)
            <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 hover:border-[#9d2449] hover:shadow-md"
                 data-documento-id="{{ is_array($documento) ? $documento['id'] : $documento->id }}"
                 data-documento-nombre="{{ is_array($documento) ? ($documento['nombre'] ?? $documento['nombre_original'] ?? 'Documento') : ($documento->catalogoArchivo->nombre ?? $documento->nombre_original ?? 'Documento') }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        @php
                            // Manejar tanto arrays como objetos Eloquent
                            $nombreArchivo = is_array($documento) 
                                ? ($documento['nombre_original'] ?? $documento['nombre'] ?? '')
                                : ($documento->nombre_original ?? '');
                            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
                            $iconData = match(strtolower($extension)) {
                                'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => 'text-white'],
                                'png', 'jpg', 'jpeg' => ['icon' => 'fas fa-file-image', 'color' => 'text-white'],
                                'mp3' => ['icon' => 'fas fa-file-audio', 'color' => 'text-white'],
                                'doc', 'docx' => ['icon' => 'fas fa-file-word', 'color' => 'text-white'],
                                'xls', 'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => 'text-white'],
                                default => ['icon' => 'fas fa-file', 'color' => 'text-white'],
                            };
                        @endphp
                        <div class="w-12 h-12 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm mr-4">
                            <i class="{{ $iconData['icon'] }} {{ $iconData['color'] }} text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-900 mb-1">
                                {{ is_array($documento) ? ($documento['nombre'] ?? $documento['nombre_original'] ?? 'Documento') : ($documento->catalogoArchivo->nombre ?? $documento->nombre_original ?? 'Documento') }}
                            </h4>
                            <div class="hidden sm:flex items-center space-x-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/10 text-black">
                                    {{ strtoupper($extension) }}
                                </span>
                            </div>
                            <div class="flex flex-col space-y-1 sm:flex-row sm:items-center sm:space-x-4 text-xs text-gray-500 sm:hidden">
                                <span>{{ is_array($documento) ? ($documento['tamaño_formateado'] ?? $documento['tamaño'] ?? 'N/A') : $documento->tamaño_formateado }}</span>
                                <span class="hidden sm:inline">•</span>
                                <span>{{ is_array($documento) ? ($documento['fecha_carga'] ?? $documento['created_at'] ?? 'N/A') : $documento->fecha_carga }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        @if(is_array($documento) ? isset($documento['aprobado']) : isset($documento->aprobado))
                            @if((is_array($documento) ? $documento['aprobado'] : $documento->aprobado) === true)
                                <span class="estado-documento inline-flex items-center px-2 py-1 sm:px-3 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="hidden sm:inline">Aprobado</span>
                                </span>
                            @elseif((is_array($documento) ? $documento['aprobado'] : $documento->aprobado) === false)
                                <span class="estado-documento inline-flex items-center px-2 py-1 sm:px-3 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="hidden sm:inline">Rechazado</span>
                                </span>
                            @else
                                <span class="estado-documento inline-flex items-center px-2 py-1 sm:px-3 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                    </svg>
                                    <span class="hidden sm:inline">Pendiente</span>
                                </span>
                            @endif
                        @endif
                        @if((is_array($documento) ? isset($documento['fecha_cotejo']) : isset($documento->fecha_cotejo)) && (is_array($documento) ? $documento['fecha_cotejo'] : $documento->fecha_cotejo))
                            <span class="inline-flex items-center px-2 py-1 sm:px-3 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="hidden sm:inline">Cotejado</span>
                            </span>
                        @endif
                        <a href="{{ route('revision.verDocumento', [
                            'tramite' => is_object($tramite) ? $tramite->id : $tramite['id'],
                            'archivo' => is_array($documento) ? $documento['id'] : $documento->id,
                            'filename' => basename(is_array($documento) ? ($documento['ruta_archivo'] ?? 'documento') : ($documento->ruta_archivo ?? 'documento'))
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
                                onclick="toggleDocumentComment({{ is_array($documento) ? $documento['id'] : $documento->id }})"
                                class="group inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition-colors" 
                                title="Comentar documento">
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-[#9D2449] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Información adicional del documento -->
                <div class="hidden sm:block mt-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                    <div class="flex items-center text-xs text-gray-700">
                        <i class="fas fa-info-circle mr-2 text-[#9d2449]"></i>
                        <span class="flex-1">
                            {{ is_array($documento) ? ($documento['tamaño_formateado'] ?? $documento['tamaño'] ?? 'N/A') : $documento->tamaño_formateado }} • 
                            {{ is_array($documento) ? ($documento['fecha_carga'] ?? $documento['created_at'] ?? 'N/A') : $documento->fecha_carga }}
                        </span>
                    </div>
                </div>

                @if((is_array($documento) ? isset($documento['observaciones']) : isset($documento->observaciones)) && (is_array($documento) ? $documento['observaciones'] : $documento->observaciones))
                    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center text-xs text-amber-800">
                            <i class="fas fa-comment mr-2"></i>
                            <span class="font-medium">Observaciones:</span>
                            <span class="ml-2 text-amber-700">{{ is_array($documento) ? $documento['observaciones'] : $documento->observaciones }}</span>
                        </div>
                    </div>
                @endif

                @if($editable)
                    <div id="comment-form-{{ is_array($documento) ? $documento['id'] : $documento->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                        <form class="documento-review-form" data-documento-id="{{ is_array($documento) ? $documento['id'] : $documento->id }}">
                            <div class="mb-3">
                                <textarea 
                                    name="comentario" 
                                    rows="3"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] resize-none"
                                    placeholder="Comentario sobre este documento...">Observación de revisión digital: </textarea>
                            </div>
                            <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                                <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:space-x-3">
                                    <label class="flex items-center text-xs">
                                        <input type="radio" name="decision_documento" value="1" class="text-green-600 focus:outline-none focus:ring-green-500 mr-1">
                                        <span class="text-green-700">Aprobar</span>
                                    </label>
                                    <label class="flex items-center text-xs">
                                        <input type="radio" name="decision_documento" value="0" class="text-red-600 focus:outline-none focus:ring-red-500 mr-1">
                                        <span class="text-red-700">Rechazar</span>
                                    </label>
                                </div>
                                <div class="flex items-center justify-end space-x-2">
                                    <button type="button" 
                                        onclick="toggleDocumentComment({{ is_array($documento) ? $documento['id'] : $documento->id }})"
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
    <div class="text-center py-8">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6">
            <div class="w-16 h-16 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-circle text-white text-xl"></i>
            </div>
            <p class="text-sm text-gray-600">
                No hay documentos adjuntos para este trámite.
            </p>
        </div>
    </div>
@endif

@if($editable)
    <script src="{{ asset('js/revision/documentos.js') }}"></script>
@endif