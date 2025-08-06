@extends('layouts.app')

@section('title', 'Seleccionar Tipo de Revisión')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Seleccionar Tipo de Revisión</h1>
                        <p class="text-base text-gray-500 mt-1">Trámite #{{ $tramite->id }} - {{ $tramite->getRazonSocial() ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('revisiones.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Información del trámite -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm font-medium text-blue-800">Tipo de Trámite:</span>
                        <p class="text-blue-900">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">RFC:</span>
                        <p class="text-blue-900">{{ $tramite->proveedor->rfc ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Fecha de Creación:</span>
                        <p class="text-blue-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($revisionExistente)
            <!-- Alerta de revisión existente -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-amber-800 mb-2">Revisión en Proceso</h3>
                        <p class="text-sm text-amber-700 mb-3">
                            Ya existe una {{ $revisionExistente->tipo_revision_label }} en estado "{{ $revisionExistente->estado_label }}" 
                            iniciada el {{ $revisionExistente->fecha_inicio->format('d/m/Y H:i') }}.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('revisiones.revisar', ['tramite' => $tramite->id, 'tipo_revision' => $revisionExistente->tipo_revision]) }}" 
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                                Continuar Revisión Existente
                            </a>
                            <span class="text-sm text-amber-700 self-center">o selecciona un nuevo tipo de revisión abajo</span>
                    </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Opciones de tipo de revisión -->
            <div class="space-y-6">
                <div class="text-center mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Selecciona el tipo de revisión</h2>
                    <p class="text-gray-600">Elige el método de revisión que vas a realizar para este trámite</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Revisión Digital -->
                    <form action="{{ route('revisiones.iniciar', $tramite->id) }}" method="POST" class="group">
                        @csrf
                        <input type="hidden" name="tipo_revision" value="Digital">
                        <button type="submit" class="w-full h-full">
                            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 hover:shadow-lg transition-all duration-300 group-hover:bg-blue-50">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Revisión Digital</h3>
                                    <p class="text-sm text-gray-600 mb-4">Revisa los documentos y datos del trámite de forma digital</p>
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Revisión de documentos
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Validación de datos
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Sin contacto físico
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </form>

                    <!-- Revisión Presencial -->
                    <form action="{{ route('revisiones.iniciar', $tramite->id) }}" method="POST" class="group">
                        @csrf
                        <input type="hidden" name="tipo_revision" value="Presencial">
                        <button type="submit" class="w-full h-full">
                            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-green-500 hover:shadow-lg transition-all duration-300 group-hover:bg-green-50">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Revisión Presencial</h3>
                                    <p class="text-sm text-gray-600 mb-4">Revisa el trámite en las instalaciones con el solicitante</p>
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Revisión en oficina
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Documentos originales
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Entrevista directa
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </form>

                    <!-- Revisión Domiciliaria -->
                    <form action="{{ route('revisiones.iniciar', $tramite->id) }}" method="POST" class="group">
                        @csrf
                        <input type="hidden" name="tipo_revision" value="Domiciliaria">
                        <button type="submit" class="w-full h-full">
                            <div class="bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-purple-500 hover:shadow-lg transition-all duration-300 group-hover:bg-purple-50">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Revisión Domiciliaria</h3>
                                    <p class="text-sm text-gray-600 mb-4">Revisa el trámite en el domicilio del solicitante</p>
                                    <div class="text-xs text-gray-500 space-y-1">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Visita al domicilio
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Verificación in situ
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Inspección física
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>

                <!-- Información adicional -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-800 mb-2">Información importante:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Una vez seleccionado el tipo de revisión, se creará un registro de revisión para este trámite.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Podrás cambiar el tipo de revisión si es necesario, pero se registrará como un nuevo intento.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Al finalizar la revisión, podrás aprobar o rechazar el trámite con observaciones.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 