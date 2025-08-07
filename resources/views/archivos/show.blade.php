@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10,9 9,9 8,9"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Detalles del Archivo</h1>
                        <p class="text-base text-gray-500 mt-1">Información completa del archivo</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('archivos.download', $archivo) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar
                    </a>
                    <a href="{{ route('archivos.edit', $archivo) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Editar
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Información Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Archivo -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 bg-gradient-to-br from-[#B4325E] to-[#93264B] text-white rounded-xl shadow-lg flex items-center justify-center font-bold text-2xl">
                                    @php
                                        $extension = strtoupper($archivo->extension ?? 'DOC');
                                        $icon = match($extension) {
                                            'PDF' => '📄',
                                            'DOC', 'DOCX' => '📝',
                                            'JPG', 'JPEG', 'PNG' => '🖼️',
                                            'XLS', 'XLSX' => '📊',
                                            default => '📁'
                                        };
                                    @endphp
                                    <span>{{ $icon }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $archivo->nombre_original }}</h2>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ strtoupper($archivo->extension ?? 'DOC') }}
                                    </span>
                                    @if($archivo->tamaño)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ number_format($archivo->tamaño / 1024, 1) }} KB
                                    </span>
                                    @endif
                                    @if($archivo->catalogoArchivo)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $archivo->catalogoArchivo->nombre }}
                                    </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Creado: {{ $archivo->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    @if($archivo->fecha_revision)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Revisado: {{ $archivo->fecha_revision->format('d/m/Y H:i') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado</h3>
                        @if($archivo->status === 'Aprobado')
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-lg font-medium text-green-800">Aprobado</span>
                            </div>
                        @elseif($archivo->status === 'Rechazado')
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-lg font-medium text-red-800">Rechazado</span>
                            </div>
                        @else
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-lg font-medium text-yellow-800">Pendiente</span>
                            </div>
                        @endif
                        
                        @if($archivo->revisor)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600">Revisado por:</p>
                            <p class="text-sm font-medium text-gray-900">{{ $archivo->revisor->nombre }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información Detallada -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información del Proveedor -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Proveedor
                    </h3>
                    @if($archivo->proveedor)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nombre</p>
                            <p class="text-sm text-gray-900">{{ $archivo->proveedor->nombre }}</p>
                        </div>
                        @if($archivo->proveedor->rfc)
                        <div>
                            <p class="text-sm font-medium text-gray-500">RFC</p>
                            <p class="text-sm text-gray-900">{{ $archivo->proveedor->rfc }}</p>
                        </div>
                        @endif
                        @if($archivo->proveedor->correo)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Correo</p>
                            <p class="text-sm text-gray-900">{{ $archivo->proveedor->correo }}</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Sin proveedor asignado</p>
                    @endif
                </div>

                <!-- Información del Trámite -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Trámite
                    </h3>
                    @if($archivo->tramite)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Folio</p>
                            <p class="text-sm text-gray-900">{{ $archivo->tramite->folio }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Estado</p>
                            <p class="text-sm text-gray-900">{{ $archivo->tramite->status ?? 'Sin estado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Fecha de creación</p>
                            <p class="text-sm text-gray-900">{{ $archivo->tramite->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-sm text-gray-500">Sin trámite asignado</p>
                    @endif
                </div>
            </div>

            <!-- Observaciones y Comentarios -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($archivo->observaciones_documento)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Observaciones
                    </h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $archivo->observaciones_documento }}</p>
                </div>
                @endif

                @if($archivo->comentario_revision)
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Comentario de Revisión
                    </h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $archivo->comentario_revision }}</p>
                </div>
                @endif
            </div>

            <!-- Información Técnica -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Información Técnica
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">ID del Archivo</p>
                        <p class="text-sm text-gray-900">{{ $archivo->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nombre del Archivo</p>
                        <p class="text-sm text-gray-900">{{ $archivo->nombre_archivo }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Extensión</p>
                        <p class="text-sm text-gray-900">{{ strtoupper($archivo->extension ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tamaño</p>
                        <p class="text-sm text-gray-900">{{ $archivo->tamaño ? number_format($archivo->tamaño) . ' bytes' : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('archivos.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a la lista
                </a>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('archivos.download', $archivo) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar
                    </a>
                    <a href="{{ route('archivos.edit', $archivo) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#9d2449] border border-transparent rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 