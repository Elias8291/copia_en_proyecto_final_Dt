@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true, 'tipoPersona' => 'Física', 'tramite' => null])

@php
    // Obtener documentos según el tipo de persona
    try {
        $documentos = \App\Models\CatalogoArchivo::where('es_visible', true)
            ->where(function ($query) use ($tipoPersona) {
                $query->where('tipo_persona', $tipoPersona ?? 'Física')
                      ->orWhere('tipo_persona', 'Ambas');
            })
            ->orderBy('nombre', 'asc')
            ->get();
        
        $documentosRequeridos = $documentos->map(function ($documento) {
            return (object) [
                'id' => $documento->id,
                'nombre' => $documento->nombre,
                'descripcion' => $documento->descripcion,
                'tipo_persona' => $documento->tipo_persona,
                'tipo_archivo' => $documento->tipo_archivo,
                'tipo_persona_label' => $documento->tipo_persona === 'Física' ? 'Persona Física' : 
                                       ($documento->tipo_persona === 'Moral' ? 'Persona Moral' : 'Ambas'),
                'tipo_archivo_label' => strtoupper($documento->tipo_archivo),
            ];
        });
        
        // Si es una corrección, obtener los documentos existentes del trámite
        $documentosExistentes = [];
        if ($tramite && $tramite->archivos) {
            $documentosExistentes = $tramite->archivos->keyBy('idCatalogoArchivo')->toArray();
        }
    } catch (\Exception $e) {
        \Log::error('Error cargando documentos: ' . $e->getMessage());
        $documentosRequeridos = collect(); // Colección vacía como fallback
        $documentosExistentes = [];
    }
@endphp

@push('scripts')
    <script src="{{ asset('js/tramites/handlers/documentos-handler.js') }}"></script>
    <script>
        function toggleComentario(documentoId) {
            const documentoContainer = document.querySelector(`[data-documento-id="${documentoId}"]`);
            if (!documentoContainer) return;
            
            const preview = documentoContainer.querySelector('.comentario-preview');
            const completo = documentoContainer.querySelector('.comentario-completo');
            const icon = document.getElementById(`icon-${documentoId}`);
            
            if (preview && completo && icon) {
                const isExpanded = !completo.classList.contains('hidden');
                
                if (!isExpanded) {
                    // Expandir
                    preview.classList.add('hidden');
                    completo.classList.remove('hidden');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                    icon.title = 'Contraer comentario';
                } else {
                    // Contraer
                    preview.classList.remove('hidden');
                    completo.classList.add('hidden');
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                    icon.title = 'Expandir comentario';
                }
            }
        }
        
        // Inicializar tooltips para los botones de expandir
        document.addEventListener('DOMContentLoaded', function() {
            const expandButtons = document.querySelectorAll('[onclick*="toggleComentario"]');
            expandButtons.forEach(button => {
                const icon = button.querySelector('i');
                if (icon && icon.classList.contains('fa-chevron-down')) {
                    button.title = 'Expandir comentario';
                }
            });
        });
    </script>
@endpush

<div class="space-y-8" {{ $attributes }} data-seccion="documentos">
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Documentos Requeridos
            <span class="text-xs text-gray-500 font-normal">
                ({{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }})
            </span>
        </h4>
        
        @if($errors->has('documentos'))
            <div class="mb-4 flex items-center justify-center">
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-50 to-pink-50 rounded-full shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium text-red-700">{{ $errors->first('documentos') }}</span>
                </div>
            </div>
        @endif



        <!-- Lista de Documentos -->
        <div class="space-y-3 sm:space-y-4 {{ $errors->has('documentos') ? 'border border-red-200 bg-red-50 rounded-lg p-4' : '' }}">
            @if ($documentosRequeridos->count() > 0)
                @foreach ($documentosRequeridos as $documento)
                    <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 lg:p-6 transition-all duration-300 hover:border-[#9d2449] hover:shadow-md"
                         data-documento-id="{{ $documento->id }}">
                        <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start sm:items-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm mr-3 sm:mr-4 flex-shrink-0">
                                    @php
                                        $iconClass = match (strtolower($documento->tipo_archivo)) {
                                            'pdf' => 'fas fa-file-pdf text-white',
                                            'png', 'jpg', 'jpeg' => 'fas fa-file-image text-white',
                                            'mp3' => 'fas fa-file-audio text-white',
                                            'mp4' => 'fas fa-file-video text-white',
                                            default => 'fas fa-file text-white',
                                        };
                                    @endphp
                                    <i class="{{ $iconClass }} text-xs sm:text-sm lg:text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-1 truncate">{{ $documento->nombre }}</h4>
                                    <div class="flex flex-col xs:flex-row xs:items-center space-y-1 xs:space-y-0 xs:space-x-2 sm:space-x-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/10 text-black w-fit">
                                            {{ $documento->tipo_archivo_label }}
                                        </span>
                                        <span class="text-xs text-gray-500 xs:hidden">{{ $documento->descripcion ?? 'Documento requerido' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-end space-x-2 sm:space-x-3">
                                @php
                                    $documentoExistente = $documentosExistentes[$documento->id] ?? null;
                                    $documentoAprobado = $documentoExistente && isset($documentoExistente['aprobado']) && $documentoExistente['aprobado'] === true;
                                    $permitirSubida = !$documentoAprobado;
                                @endphp
                                @if ($editable && $permitirSubida)
                                    <label for="file_{{ $documento->id }}" class="cursor-pointer inline-flex items-center px-2 py-1.5 sm:px-3 sm:py-2 {{ $errors->has('documentos.' . $documento->id) ? 'bg-red-500 hover:bg-red-600' : 'bg-black hover:bg-gray-800' }} text-white text-xs font-medium rounded-lg transition-all duration-200 shadow-sm">
                                        <i class="fas fa-upload mr-1 sm:mr-2"></i>
                                        <span class="hidden xs:inline">{{ $documentoExistente ? 'Cambiar' : 'Subir' }}</span>
                                    </label>
                                    <input type="file" 
                                           id="file_{{ $documento->id }}" 
                                           name="documentos[{{ $documento->id }}]" 
                                           accept="{{ $documento->tipo_archivo }}" 
                                           class="hidden {{ $errors->has('documentos.' . $documento->id) ? 'border-red-500 bg-red-50' : '' }}"
                                           onchange="handleFileUpload(this, {{ $documento->id }})">
                                    @if($errors->has('documentos.' . $documento->id))
                                        <div class="mt-2 flex items-center text-red-600">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm font-medium">{{ $errors->first('documentos.' . $documento->id) }}</span>
                                        </div>
                                    @endif
                                @elseif($documentoAprobado)
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                        <i class="fas fa-lock mr-1"></i>
                                        <span class="hidden xs:inline">Aprobado</span>
                                    </span>
                                    <div class="text-xs text-green-600 max-w-16 sm:max-w-20 lg:max-w-24 truncate">
                                        No editable
                                    </div>
                                @endif
                                
                                @if($documentoExistente)
                                    <span id="status_{{ $documento->id }}" class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        <i class="fas fa-check mr-1"></i>
                                        <span class="hidden xs:inline">Subido</span>
                                    </span>
                                    <div id="filename_{{ $documento->id }}" class="text-xs text-green-600 max-w-16 sm:max-w-20 lg:max-w-24 truncate">
                                        {{ $documentoExistente['nombre_original'] ?? 'Documento' }}
                                    </div>
                                    
                                    <!-- Estado del documento (si existe) -->
                                    @if(isset($documentoExistente['aprobado']))
                                        @if($documentoExistente['aprobado'] === true)
                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                <span class="hidden xs:inline">Aprobado</span>
                                            </span>
                                        @elseif($documentoExistente['aprobado'] === false)
                                            <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                <span class="hidden xs:inline">Rechazado</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">
                                                <i class="fas fa-clock mr-1"></i>
                                                <span class="hidden xs:inline">Pendiente</span>
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <span id="status_{{ $documento->id }}" class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                        <i class="fas fa-clock mr-1"></i>
                                        <span class="hidden xs:inline">Pendiente</span>
                                    </span>
                                    <div id="filename_{{ $documento->id }}" class="hidden text-xs text-green-600 max-w-16 sm:max-w-20 lg:max-w-24 truncate"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Información adicional del documento (móvil) -->
                        <div class="block sm:hidden mt-3 p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="flex items-start text-xs text-gray-700">
                                <i class="fas fa-info-circle mr-2 text-[#9d2449] mt-0.5 flex-shrink-0"></i>
                                <span class="flex-1">{{ $documento->descripcion ?? 'Documento requerido para el trámite' }}</span>
                            </div>
                        </div>
                        
                        <!-- Información adicional del documento (desktop) -->
                        <div class="hidden sm:block mt-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="flex items-center text-xs text-gray-700">
                                <i class="fas fa-info-circle mr-2 text-[#9d2449]"></i>
                                <span class="flex-1">{{ $documento->descripcion ?? 'Documento requerido para el trámite' }}</span>
                            </div>
                        </div>
                        
                        <!-- Comentarios del documento (si existen) -->
                        @if($documentoExistente && isset($documentoExistente['observaciones']) && $documentoExistente['observaciones'])
                            @php
                                $comentario = $documentoExistente['observaciones'];
                                $comentarioLargo = strlen($comentario) > 100;
                            @endphp
                            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start text-xs text-amber-800 flex-1">
                                        <i class="fas fa-comment mr-2 mt-0.5 flex-shrink-0"></i>
                                        <div class="flex-1">
                                            <span class="font-medium">Observaciones del revisor:</span>
                                            <div class="mt-1">
                                                @if($comentarioLargo)
                                                    <span class="text-amber-700 comentario-preview">{{ Str::limit($comentario, 100) }}</span>
                                                    <span class="text-amber-700 comentario-completo hidden">{{ $comentario }}</span>
                                                @else
                                                    <span class="text-amber-700">{{ $comentario }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($comentarioLargo)
                                        <button type="button" 
                                                onclick="toggleComentario({{ $documento->id }})"
                                                class="ml-2 p-1 text-amber-600 hover:text-amber-800 hover:bg-amber-100 rounded transition-colors flex-shrink-0"
                                                title="Expandir comentario">
                                            <i class="fas fa-chevron-down text-xs" id="icon-{{ $documento->id }}"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- Información de cotejo (si existe) -->
                        @if($documentoExistente && isset($documentoExistente['fecha_cotejo']) && $documentoExistente['fecha_cotejo'])
                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center text-xs text-blue-700">
                                    <i class="fas fa-eye mr-2"></i>
                                    <span class="font-medium">Cotejado el:</span>
                                    <span class="ml-2">{{ \Carbon\Carbon::parse($documentoExistente['fecha_cotejo'])->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Mensaje de documento aprobado -->
                        @if($documentoAprobado)
                            <div class="mt-3 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-lock text-green-600 text-sm"></i>
                                    <span class="text-xs text-green-700 font-medium">
                                        Este documento está aprobado y no puede ser modificado
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-circle text-white text-xl"></i>
                        </div>
                        <p class="text-sm text-gray-600">
                            No hay documentos requeridos para
                            <span class="font-medium text-gray-800">{{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}</span>.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if($tramite)
    @include('tramites.partials.estado-seccion', ['seccion' => 'documentos', 'tramite' => $tramite])
@endif

