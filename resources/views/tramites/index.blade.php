@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Header Principal -->
            <div class="w-full max-w-7xl mx-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200/70 p-8 -mt-4 mb-8">
                <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                    <div class="p-6 border-b border-gray-200/70">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-800">Trámites Disponibles</h1>
                                    <p class="text-base text-gray-500 mt-1">Seleccione el tipo de trámite que desea realizar</p>
                                </div>
                            </div>

                        @if ($proveedor)
                            <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                                <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl border border-gray-200/50 p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700">{{ $proveedor->razon_social }}</span>
                                                <span class="text-xs text-gray-500">Proveedor registrado</span>
                                            </div>
                                        </div>
                                        @php
                                            $estadoColor = match ($proveedor->estado_padron ?? 'Sin Estado') {
                                                'Activo' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Pendiente' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Vencido', 'Inactivo' => 'bg-red-50 text-red-700 border-red-200',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border {{ $estadoColor }} ml-3">
                                            {{ $proveedor->estado_padron ?? 'Sin Estado' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estado del Trámite Pendiente -->
            @if ($globalTramites['tiene_tramite_pendiente'] && $globalTramites['tramite_pendiente'])
                @php
                    $detalles = $globalTramites['tramite_pendiente'];
                    $tramite = $detalles['tramite'];
                    
                    $tramite_id = str_pad($tramite->id, 4, '0', STR_PAD_LEFT);
                    $estado = $tramite->estado;
                    $paso_actual = $detalles['estado_descripcion'];
                    $historial = [];
                @endphp
                
                @include('tramites.estado', [
                    'tramite_id' => $tramite_id,
                    'estado' => $estado,
                    'paso_actual' => $paso_actual,
                    'historial' => $historial
                ])
            @endif

            <!-- Contenedor de Tarjetas de Trámites -->
            @if (!($globalTramites['tiene_tramite_pendiente'] ?? false))
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @include('tramites.partials.tramite-card', [
                                'tipo' => 'inscripcion',
                                'titulo' => 'Inscripción al Padrón',
                                'descripcion' => 'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                                'disponible' => $globalTramites['inscripcion'] ?? false,
                                'colorFrom' => 'from-primary',
                                'colorTo' => 'to-primary-dark',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
                            ])

                            @include('tramites.partials.tramite-card', [
                                'tipo' => 'renovacion',
                                'titulo' => 'Renovación de Registro',
                                'descripcion' => 'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                                'disponible' => $globalTramites['renovacion'] ?? false,
                                'colorFrom' => 'from-primary-dark',
                                'colorTo' => 'to-primary',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
                            ])

                            @include('tramites.partials.tramite-card', [
                                'tipo' => 'actualizacion',
                                'titulo' => 'Actualización de Datos',
                                'descripcion' => 'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                                'disponible' => $globalTramites['actualizacion'] ?? false,
                                'colorFrom' => 'from-primary',
                                'colorTo' => 'to-primary-dark',
                                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                            ])
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
