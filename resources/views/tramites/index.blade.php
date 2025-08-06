@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto">
        
        @php
            // Verificar si hay trámite pendiente
            $tieneTramitePendiente = false;
            $tramitePendiente = null;
            $tipoTramitePendiente = null;
            $rfc = auth()->user()->rfc ?? null;
            
            if ($rfc) {
                $rfcService = app(\App\Services\RfcProveedorService::class);
                $tramitePendiente = $rfcService->obtenerTramitePendiente($rfc);
                $tieneTramitePendiente = $tramitePendiente !== null;
                if ($tramitePendiente) {
                    $tipoTramitePendiente = strtolower($tramitePendiente->tipo_tramite);
                }
            }
        @endphp

        @if(session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Mensajes de Sesión -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido Principal -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200/70">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Trámites Disponibles</h1>
                            <p class="text-base text-gray-500 mt-1">
                                @if($tieneTramitePendiente)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-amber-50 text-amber-700 border border-amber-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if($tipoTramitePendiente)
                                            Tiene un trámite de {{ ucfirst($tipoTramitePendiente) }} en proceso
                                        @else
                                            Tiene un trámite en proceso
                                        @endif
                                    </span>
                                @else
                                    Seleccione el tipo de trámite que desea realizar
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($tieneTramitePendiente)
                <div class="col-span-full mb-4">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-4">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-amber-800">
                                        @if($tipoTramitePendiente)
                                            Tiene un trámite de <strong>{{ ucfirst($tipoTramitePendiente) }}</strong> en proceso
                                        @else
                                            Tiene un trámite en proceso
                                        @endif
                                    </p>
                                    <p class="text-xs text-amber-600 mt-1">Consulte el estado de su trámite actual antes de iniciar uno nuevo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 px-6 pb-6">
                
                {{-- Inscripción al Padrón --}}
                @include('tramites.partials.tramite-card', [
                    'tipo' => 'inscripcion',
                    'tramites' => $tramites,
                    'title' => 'Inscripción al Padrón',
                    'description' => 'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                    'gradient' => 'from-[#9d2449] to-[#8a1f40]',
                    'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>'
                ])

                {{-- Renovación de Registro --}}
                @include('tramites.partials.tramite-card', [
                    'tipo' => 'renovacion',
                    'tramites' => $tramites,
                    'title' => 'Renovación de Registro',
                    'description' => 'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                    'gradient' => 'from-[#8a1f40] to-[#7a1a37]',
                    'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>'
                ])

                {{-- Actualización de Datos --}}
                @include('tramites.partials.tramite-card', [
                    'tipo' => 'actualizacion',
                    'tramites' => $tramites,
                    'title' => 'Actualización de Datos',
                    'description' => 'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                    'gradient' => 'from-[#9d2449] to-[#7a1a37]',
                    'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>'
                ])

            </div>
        </div>
    </div>
</div>
@endsection