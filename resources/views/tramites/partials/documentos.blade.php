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

<div class="max-w-7xl mx-auto space-y-8" {{ $attributes }}>
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
        <!-- Encabezado con icono -->
        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-100">
            <div
                class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-file-upload text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Documentos</h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if ($editable)
                        Documentos requeridos para {{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}
                    @else
                        Documentos del trámite registrado
                        ({{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }})
                    @endif
                </p>
            </div>
        </div>

        <!-- Barra de Progreso de Documentos -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progreso de documentos</span>
                <span class="text-sm font-semibold text-[#9D2449]" id="documentos-progress-text">0/{{ $documentosRequeridos->count() }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] h-2.5 rounded-full transition-all duration-500" 
                     style="width: 0%" id="documentos-progress-bar"></div>
            </div>
        </div>

        <!-- Lista de Documentos -->
        <div class="space-y-6">
            @if ($documentosRequeridos->count() > 0)
                @foreach ($documentosRequeridos as $documento)
                    <div
                        class="bg-white border-2 rounded-lg p-6 transition-all duration-300 border-gray-300 hover:border-[#9d2449] hover:shadow-md">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="relative">
                                    @php
                                        $iconClass = match (strtolower($documento->tipo_archivo)) {
                                            'pdf' => 'fas fa-file-pdf text-red-600',
                                            'png', 'jpg', 'jpeg' => 'fas fa-file-image text-blue-600',
                                            'mp3' => 'fas fa-file-audio text-purple-600',
                                            'mp4' => 'fas fa-file-video text-green-600',
                                            default => 'fas fa-file text-gray-600',
                                        };
                                    @endphp
                                    <i class="{{ $iconClass }} text-2xl mr-3"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $documento->nombre }}</h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $documento->descripcion ?? 'Documento requerido' }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="text-xs text-gray-600">
                                            Tipo: {{ $documento->tipo_persona_label }}
                                        </span>
                                        <span class="mx-2 text-gray-400">•</span>
                                        <span class="text-xs text-gray-600">
                                            Formato: {{ $documento->tipo_archivo_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if ($editable)
                                    <label for="file_{{ $documento->id }}"
                                        class="cursor-pointer text-[#9d2449] hover:text-[#7a1d37] text-xs underline">
                                        <i class="fas fa-upload mr-1"></i>
                                        Subir
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
                                <span id="status_{{ $documento->id }}" class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                    <i class="fas fa-clock mr-1"></i>
                                    Pendiente
                                </span>
                                <div id="filename_{{ $documento->id }}" class="hidden text-xs text-green-600 max-w-32 truncate"></div>
                            </div>
                        </div>

                        <!-- Información adicional del documento -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="{{ $iconClass }} mr-2"></i>
                                <span>{{ $documento->descripcion ?? 'Documento requerido para el trámite' }}</span>
                                @if ($documento->tipo_persona === 'Ambas')
                                    <span class="ml-auto text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        Aplica para ambos tipos
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <i class="fas fa-exclamation-circle text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">
                            No hay documentos requeridos para
                            {{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
