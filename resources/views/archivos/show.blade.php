@extends('layouts.app')

@section('title', 'Detalles del Catálogo de Archivo')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        Detalles del Catálogo de Archivo
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">
                        Información completa del catálogo de archivo
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('archivos.edit', $archivo) }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#9d2449] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#8a1f40] focus:bg-[#8a1f40] active:bg-[#7a1b38] focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('archivos.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del Catálogo -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <!-- Header del catálogo -->
                <div class="flex items-start space-x-4 mb-6">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-lg bg-[#9d2449]/10 flex items-center justify-center">
                            <svg class="h-8 w-8 text-[#9d2449]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $archivo->nombre }}</h2>
                        <p class="text-gray-600">{{ $archivo->descripcion }}</p>
                        <div class="flex items-center space-x-4 mt-3">
                            @if($archivo->es_visible)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Visible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293-4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Oculto
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Detalles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información General -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Información General
                        </h3>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre del Catálogo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $archivo->nombre }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $archivo->descripcion }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Persona</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $archivo->tipo_persona }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Formato de Archivo</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ strtoupper($archivo->tipo_archivo) }}
                                </span>
                            </dd>
                        </div>
                    </div>

                    <!-- Información Técnica -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                            Información Técnica
                        </h3>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                @if($archivo->es_visible)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Visible en el sistema
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293-4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        Oculto del sistema
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $archivo->created_at->format('d/m/Y H:i:s') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $archivo->updated_at->format('d/m/Y H:i:s') }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID del Catálogo</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $archivo->id }}</dd>
                        </div>
                    </div>
                </div>

                <!-- Archivos Asociados -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Archivos Asociados
                    </h3>
                    
                    @if($archivo->archivos->count() > 0)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-3">
                                Este catálogo tiene {{ $archivo->archivos->count() }} {{ $archivo->archivos->count() == 1 ? 'archivo asociado' : 'archivos asociados' }}.
                            </p>
                            <div class="text-xs text-gray-500">
                                <strong>Nota:</strong> No se puede eliminar un catálogo que tenga archivos asociados.
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-700">
                                Este catálogo no tiene archivos asociados y puede ser eliminado de forma segura.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 