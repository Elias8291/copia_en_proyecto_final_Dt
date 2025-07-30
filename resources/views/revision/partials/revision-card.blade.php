@props([
    'tipo',
    'titulo',
    'descripcion',
    'disponible' => true,
    'colorFrom' => 'from-primary',
    'colorTo' => 'to-primary-dark',
    'icon',
    'url' => '#',
    'estado' => 'Disponible',
    'estadoColor' => 'bg-green-100 text-green-700',
    'estadoIcon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'botonTexto' => 'Iniciar',
    'botonIcon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    'deshabilitado' => false,
    'documentos' => null,
    'documentosCount' => 0,
    'mostrarInfo' => false,
    'infoTexto' => ''
])

@php
    $borderColor = match ($tipo) {
        'revision-digital' => $disponible ? 'border-blue-300' : 'border-blue-200/40',
        'cotejo-presencial' => $disponible ? 'border-orange-300' : 'border-orange-200/40',
        'cotejo-domiciliario' => $disponible ? 'border-purple-300' : 'border-purple-200/40',
        default => $disponible ? 'border-gray-300' : 'border-gray-200/40',
    };
@endphp

<div class="bg-white rounded-xl shadow-lg border-2 {{ $borderColor }} overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:scale-105 {{ $disponible ? '' : 'opacity-75' }}">
    <div class="p-8">
        <!-- Icono pequeño sin fondo -->
        <div class="flex justify-start mb-6">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        </div>

        <!-- Título y descripción alineados a la izquierda -->
        <div class="text-left mb-6">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $titulo }}</h3>
            <p class="text-sm text-gray-600">{{ $descripcion }}</p>
        </div>

        <!-- Estado alineado a la izquierda -->
        <div class="flex justify-start mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $estadoColor }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $estadoIcon }}" />
                </svg>
                {{ $estado }}
            </span>
        </div>

        <!-- Contenido del Card -->
        <div class="space-y-4">
            @if($mostrarInfo)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-gray-600 mt-0.5 flex-shrink-0" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-xs text-gray-700">
                            <strong>{{ $infoTexto }}</strong>
                        </div>
                    </div>
                </div>
            @endif

            @if($documentos && $documentos->count() > 0)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Documentos para cotejar:</h4>
                    <div class="space-y-2 max-h-32 overflow-y-auto">
                        @foreach ($documentos as $archivo)
                            <div class="flex items-center justify-between gap-2 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="truncate">{{ $archivo->catalogoArchivo->nombre ?? $archivo->nombre_original ?? 'Documento' }}</span>
                                </div>
                                <a href="{{ $archivo->getUrlVisualizacionAttribute() }}" target="_blank"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded hover:bg-gray-200 transition-colors flex-shrink-0">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if($documentosCount > 3)
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <span class="text-xs text-gray-600">+{{ $documentosCount - 3 }} documentos más</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Botón negro -->
            <div class="pt-4">
                @if($deshabilitado)
                    <button disabled
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $botonIcon }}" />
                        </svg>
                        {{ $botonTexto }}
                    </button>
                @else
                    <a href="{{ $url }}"
                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-900 border border-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $botonIcon }}" />
                        </svg>
                        {{ $botonTexto }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div> 
 
 
 
 
 