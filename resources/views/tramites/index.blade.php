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

    <!-- Historial de Trámites -->
    <div class="mt-8 max-w-7xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 border-b border-gray-200/70">
                                            <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Historial de Trámites</h2>
                            <p class="text-base text-gray-500 mt-1">Registro de todos sus trámites realizados</p>
                        </div>
                    </div>
                </div>

                @if($historialTramites->isNotEmpty())
                    <div class="p-6">
                        <ol class="relative border-s border-gray-200">
                            @foreach($historialTramites as $index => $tramite)
                                <li class="{{ $index < count($historialTramites) - 1 ? 'mb-10' : '' }} ms-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 bg-[#9d2449]/10 rounded-full -start-3 ring-8 ring-white border-2 border-[#9d2449]/20">
                                        <svg class="w-3 h-3 text-[#9d2449]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                        </svg>
                                    </span>
                                    
                                    <div class="flex items-center mb-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $tramite['razon_social'] }}</h3>
                                        <span class="bg-[#9d2449]/10 text-[#9d2449] text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm ms-3">
                                            {{ ucfirst($tramite['tipo_tramite']) }}
                                        </span>
                                        @php
                                            $estadoColors = [
                                                'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                                'Revision_Digital' => 'bg-blue-100 text-blue-800',
                                                'Revision_Presencial' => 'bg-purple-100 text-purple-800',
                                                'Revision_Domiciliaria' => 'bg-indigo-100 text-indigo-800',
                                                'Para_Correccion' => 'bg-orange-100 text-orange-800',
                                                'Aprobado' => 'bg-green-100 text-green-800',
                                                'Rechazado' => 'bg-red-100 text-red-800',
                                                'Cancelado' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $estadoColor = $estadoColors[$tramite['status']] ?? 'bg-gray-100 text-gray-800';
                                            $estadoLabels = [
                                                'Pendiente' => 'Pendiente',
                                                'Revision_Digital' => 'Revisión Digital',
                                                'Revision_Presencial' => 'Revisión Presencial',
                                                'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                                                'Para_Correccion' => 'Para Corrección',
                                                'Aprobado' => 'Aprobado',
                                                'Rechazado' => 'Rechazado',
                                                'Cancelado' => 'Cancelado'
                                            ];
                                            $estadoLabel = $estadoLabels[$tramite['status']] ?? $tramite['status'];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoColor }} ms-2">
                                            {{ $estadoLabel }}
                                        </span>
                                    </div>
                                    
                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                        {{ $tramite['created_at']->format('d/m/Y H:i') }}
                                    </time>
                                    
                                    @if(isset($tramite['observaciones']) && !empty(trim($tramite['observaciones'])))
                                        <p class="mb-4 text-base font-normal text-gray-500">
                                            {{ $tramite['observaciones'] }}
                                        </p>
                                    @endif
                                    
                                    <a href="{{ route('tramites.estado', $tramite['id']) }}" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-[#9d2449] bg-white border border-[#9d2449]/20 rounded-lg hover:bg-[#9d2449]/5 hover:text-[#8a1f40] focus:z-10 focus:ring-4 focus:outline-none focus:ring-[#9d2449]/20 focus:text-[#8a1f40] transition-all duration-200">
                                        <svg class="w-3.5 h-3.5 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                                            <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                        </svg>
                                        Ver detalles
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @else
                    <!-- Mensaje cuando no hay historial -->
                    <div class="p-6 text-center">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-800">
                                        Sin historial de trámites
                                    </h3>
                                    <p class="text-gray-600">
                                        Aún no ha realizado ningún trámite. Comience seleccionando uno de los tipos disponibles arriba.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection