@extends('layouts.app')

@section('title', 'Seleccionar Tipo de Revisión')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Contenido Principal -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
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

            @if($revisionExistente)
            <!-- Alerta de revisión existente -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-medium text-amber-800">
                                Ya existe una <strong>{{ $revisionExistente->tipo_revision_label }}</strong> en estado "{{ $revisionExistente->estado_label }}" 
                                iniciada el {{ $revisionExistente->fecha_inicio->format('d/m/Y H:i') }}.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 mt-3 justify-center">
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
            </div>
            @endif

            <!-- Opciones de tipo de revisión -->
            <div class="space-y-6">
                <div class="text-center mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Selecciona el tipo de revisión</h2>
                    <p class="text-gray-600">Elige el método de revisión que vas a realizar para este trámite</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 px-6 pb-6 {{ count($tiposDisponibles) === 1 ? 'justify-items-center' : '' }}">
                    
                    {{-- Revisión Digital --}}
                    @if(in_array('Digital', $tiposDisponibles))
                        @include('tramites.partials.tramite-card', [
                            'tipo' => 'revision_digital',
                            'tramites' => [
                                'revision_digital' => [
                                    'activo' => true,
                                    'pendiente' => false,
                                    'accion' => '',
                                    'motivo' => ''
                                ]
                            ],
                            'title' => 'Revisión Digital',
                            'description' => 'Revisa los documentos y datos del trámite de forma digital. Evaluación completa sin contacto físico.',
                            'gradient' => 'from-[#9d2449] to-[#8a1f40]',
                            'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>',
                            'actionText' => 'Iniciar Revisión Digital',
                            'actionUrl' => route('revisiones.iniciar', $tramite->id),
                            'formData' => [
                                'tipo_revision' => 'Digital'
                            ]
                        ])
                    @endif

                    {{-- Revisión Presencial --}}
                    @if(in_array('Presencial', $tiposDisponibles))
                        @include('tramites.partials.tramite-card', [
                            'tipo' => 'revision_presencial',
                            'tramites' => [
                                'revision_presencial' => [
                                    'activo' => true,
                                    'pendiente' => false,
                                    'accion' => '',
                                    'motivo' => ''
                                ]
                            ],
                            'title' => 'Revisión Presencial',
                            'description' => 'Revisa el trámite en las instalaciones con el solicitante. Documentos originales.' . 
                                ($informacionCita ? '<br><br><strong>Cita asignada:</strong><br>📅 ' . $informacionCita['fecha'] . ' a las ' . $informacionCita['hora'] . '<br>👤 Quien debe presentarse: ' . ($personaResponsable ? $personaResponsable['nombre'] . ' (' . $personaResponsable['cargo'] . ')' : 'Por definir') : ''),
                            'gradient' => 'from-[#8a1f40] to-[#7a1a37]',
                            'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>',
                            'actionText' => 'Iniciar Revisión Presencial',
                            'actionUrl' => route('revisiones.iniciar', $tramite->id),
                            'formData' => [
                                'tipo_revision' => 'Presencial'
                            ]
                        ])
                    @endif

                    {{-- Revisión Domiciliaria --}}
                    @if(in_array('Domiciliaria', $tiposDisponibles))
                        @include('tramites.partials.tramite-card', [
                            'tipo' => 'revision_domiciliaria',
                            'tramites' => [
                                'revision_domiciliaria' => [
                                    'activo' => true,
                                    'pendiente' => false,
                                    'accion' => '',
                                    'motivo' => ''
                                ]
                            ],
                            'title' => 'Revisión Domiciliaria',
                            'description' => 'Revisa el trámite en el domicilio del solicitante. Verificación in situ e inspección física.',
                            'gradient' => 'from-[#9d2449] to-[#7a1a37]',
                            'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>',
                            'actionText' => 'Iniciar Revisión Domiciliaria',
                            'actionUrl' => route('revisiones.iniciar', $tramite->id),
                            'formData' => [
                                'tipo_revision' => 'Domiciliaria'
                            ]
                        ])
                    @endif

                </div>

                <!-- Información adicional -->
                <div class="mt-8 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-medium text-gray-800 mb-3">Información importante:</h3>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#9D2449] mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Una vez seleccionado el tipo de revisión, se creará un registro de revisión para este trámite.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#9D2449] mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Podrás cambiar el tipo de revisión si es necesario, pero se registrará como un nuevo intento.
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-[#9D2449] mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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