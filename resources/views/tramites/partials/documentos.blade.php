@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true, 'tipoPersona' => 'Física'])

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
    } catch (\Exception $e) {
        \Log::error('Error cargando documentos: ' . $e->getMessage());
        $documentosRequeridos = collect(); // Colección vacía como fallback
    }
@endphp

@push('scripts')
    <script src="{{ asset('js/tramites/handlers/documentos-handler.js') }}"></script>
@endpush

<div class="space-y-8" {{ $attributes }}>
    <!-- Información del Trámite -->
   
    <div>
        <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
            Documentos Requeridos
            <span class="text-xs text-gray-500 font-normal">
                ({{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }})
            </span>
        </h4>



        <!-- Lista de Documentos -->
        <div class="space-y-4">
            @if ($documentosRequeridos->count() > 0)
                @foreach ($documentosRequeridos as $documento)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 transition-all duration-300 hover:border-[#9d2449] hover:shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm mr-4">
                                    @php
                                        $iconClass = match (strtolower($documento->tipo_archivo)) {
                                            'pdf' => 'fas fa-file-pdf text-white',
                                            'png', 'jpg', 'jpeg' => 'fas fa-file-image text-white',
                                            'mp3' => 'fas fa-file-audio text-white',
                                            'mp4' => 'fas fa-file-video text-white',
                                            default => 'fas fa-file text-white',
                                        };
                                    @endphp
                                    <i class="{{ $iconClass }} text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $documento->nombre }}</h4>
                                    <div class="hidden sm:flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/10 text-black">
                                            {{ $documento->tipo_archivo_label }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 sm:hidden">{{ $documento->descripcion ?? 'Documento requerido' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                @if ($editable)
                                    <label for="file_{{ $documento->id }}"
                                        class="cursor-pointer inline-flex items-center px-2 py-2 sm:px-3 sm:py-2 bg-black text-white text-xs font-medium rounded-lg hover:bg-gray-800 transition-all duration-200 shadow-sm">
                                        <i class="fas fa-upload sm:mr-2"></i>
                                        <span class="hidden sm:inline">Subir</span>
                                    </label>
                                    @php
                                        $acceptTypes = match ($documento->tipo_archivo) {
                                            'png' => '.png,.jpg,.jpeg',
                                            'jpg', 'jpeg' => '.jpg,.jpeg,.png',
                                            'pdf' => '.pdf',
                                            'mp3' => '.mp3',
                                            'mp4' => '.mp4,.avi,.mov,.wmv',
                                            default => '.' . $documento->tipo_archivo
                                        };
                                    @endphp
                                    <input type="file" id="file_{{ $documento->id }}"
                                        name="documentos[{{ $documento->id }}]"
                                        accept="{{ $acceptTypes }}" 
                                        class="hidden"
                                        onchange="handleFileUpload(this, {{ $documento->id }})">
                                @endif
                                <span id="status_{{ $documento->id }}" class="inline-flex items-center px-2 py-1 sm:px-3 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span class="hidden sm:inline">Pendiente</span>
                                </span>
                                <div id="filename_{{ $documento->id }}" class="hidden text-xs text-green-600 max-w-24 truncate"></div>
                            </div>
                        </div>

                        <!-- Información adicional del documento -->
                        <div class="hidden sm:block mt-4 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="flex items-center text-xs text-gray-700">
                                <i class="fas fa-info-circle mr-2 text-[#9d2449]"></i>
                                <span class="flex-1">{{ $documento->descripcion ?? 'Documento requerido para el trámite' }}</span>
                            </div>
                        </div>
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
