@extends('layouts.app')

@section('title', 'Estado del Trámite')

@section('content')
<div class="min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

        @if($tramitePendiente)
            @php
                $statusEnum = \App\Enums\TramiteStatus::tryFrom($tramitePendiente->status);
                $statusLabel = $statusEnum ? $statusEnum->label() : $tramitePendiente->status;
            @endphp

            <!-- Estado del Trámite -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
                <!-- Header -->
                <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] p-6 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">Estado del Trámite</h1>
                    <p class="text-white/90">Trámite #{{ $tramitePendiente->id }}</p>
                </div>

                <!-- Información del Trámite -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v4a2 2 0 002 2h4a2 2 0 002-2v-4m-6 0v4a2 2 0 002 2h4a2 2 0 002-2v-4"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tipo</p>
                            <p class="text-sm font-bold text-gray-800">{{ ucfirst($tramitePendiente->tipo_tramite) }}</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Fecha de Inicio</p>
                            <p class="text-sm font-bold text-gray-800">{{ $tramitePendiente->fecha_inicio->format('d/m/Y') }}</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Estado Actual</p>
                            <p class="text-sm font-bold text-gray-800">{{ $statusLabel }}</p>
                        </div>
                    </div>

                    <!-- Estado Actual -->
                    @if($tramitePendiente->status === 'Para_Correccion')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <p class="text-sm text-red-700 font-medium">
                                    <strong>Requiere corrección:</strong> Su solicitud necesita ajustes antes de continuar
                                </p>
                            </div>
                        </div>

                        @if($tramitePendiente->observaciones)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-red-800 mb-2">Observaciones del Revisor:</h4>
                            <p class="text-sm text-red-700">{{ $tramitePendiente->observaciones }}</p>
                        </div>
                        @endif

                        <!-- Botón de Corrección -->
                        <div class="text-center">
                            <a href="{{ route('tramites.edit', $tramitePendiente->id) }}" 
                               class="inline-flex items-center px-6 py-3 bg-[#9D2449] hover:bg-[#B91C1C] text-white font-semibold rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Corregir Trámite
                            </a>
                        </div>
                    @elseif($tramitePendiente->status === 'Aprobado')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <p class="text-sm text-green-700 font-medium">
                                    <strong>¡Felicidades!</strong> Su trámite ha sido aprobado
                                </p>
                            </div>
                        </div>

                        @if($tramitePendiente->proveedor && $tramitePendiente->proveedor->pv_numero)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-blue-800 mb-2">Información del Proveedor:</h4>
                            <p class="text-sm text-blue-700">
                                <strong>Número PV:</strong> {{ $tramitePendiente->proveedor->pv_numero }}
                            </p>
                            <p class="text-sm text-blue-700 mt-1">
                                <strong>Estado:</strong> {{ $tramitePendiente->proveedor->estado_padron }}
                            </p>
                        </div>

                        <!-- Botón para ver información pública -->
                        <div class="text-center">
                            <a href="{{ route('proveedores.publico', $tramitePendiente->proveedor->id) }}" 
                               target="_blank"
                               class="inline-flex items-center px-6 py-3 bg-[#9D2449] hover:bg-[#B91C1C] text-white font-semibold rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Ver Información Pública
                            </a>
                        </div>
                        @endif
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                                <p class="text-sm text-yellow-700 font-medium">
                                    <strong>En proceso:</strong> Su solicitud está siendo revisada
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información de Cita (si existe) -->
            @if($citaAsignada)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Información de Cita</h3>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        @if($citaVencida)
                            <div class="text-red-600 mb-4">
                                <h4 class="text-xl font-bold">Cita Vencida</h4>
                                <p class="text-lg">{{ $citaAsignada->fecha_cita->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-red-700">
                                    <strong>No asistió a la cita.</strong> Se reagendará automáticamente.
                                </p>
                            </div>
                        @else
                            <div class="text-green-600 mb-4">
                                <h4 class="text-xl font-bold">Cita Confirmada</h4>
                                <p class="text-lg">{{ $citaAsignada->fecha_cita->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-sm text-green-700">
                                    <strong>Importante:</strong> Debe llevar su documentación.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        @else
            <!-- No hay trámite pendiente -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">No hay trámites pendientes</h2>
                    <p class="text-gray-600 mb-6">Puede iniciar un nuevo trámite desde la página principal.</p>
                    <a href="{{ route('tramites.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#9D2449] hover:bg-[#B91C1C] text-white font-semibold rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Ir a Trámites
                    </a>
                </div>
            </div>
        @endif

        <!-- Botón de Volver -->
        <div class="text-center mt-6">
            <a href="{{ route('tramites.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver a Trámites
            </a>
        </div>

    </div>
</div>
@endsection