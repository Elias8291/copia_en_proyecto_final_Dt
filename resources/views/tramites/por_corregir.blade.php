@extends('layouts.app')

@section('title', 'Trámite Requiere Correcciones')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

            @if($tramitePendiente)
            <!-- Tarjeta Principal del Trámite -->
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <!-- Header Elegante -->
                    <div class="bg-gradient-to-br from-red-600 to-red-800 p-6 relative overflow-hidden border-b border-gray-200">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/20 to-red-600/30"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-center text-center">
                                <div class="space-y-3">
                                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg mx-auto">
                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-bold text-white mb-1">Trámite #{{ $tramitePendiente->id }}</h1>
                                        <p class="text-lg text-gray-200 font-medium">{{ ucfirst($tramitePendiente->tipo_tramite) }}</p>
                                    </div>
                                    @php
                                        $statusEnum = \App\Enums\TramiteStatus::tryFrom($tramitePendiente->status);
                                        $statusLabel = $statusEnum ? $statusEnum->label() : $tramitePendiente->status;
                                    @endphp
                                    <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm font-semibold text-white border border-white/20 shadow-sm">
                                        <svg class="w-4 h-4 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ $statusLabel }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido Principal -->
                    <div class="p-6">

                        <!-- Información del Trámite -->
                        <div class="space-y-6 mb-6">
                            <!-- Datos del Trámite -->
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                                <div class="text-center mb-6">
                                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center shadow-md mx-auto mb-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Información del Trámite</h3>
                                    <p class="text-gray-600">Detalles de su solicitud</p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div class="text-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v4a2 2 0 002 2h4a2 2 0 002-2v-4m-6 0v4a2 2 0 002 2h4a2 2 0 002-2v-4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Tipo de Trámite</p>
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
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Paso Actual</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $tramitePendiente->paso_actual ?? 1 }}</p>
                                    </div>

                                    <div class="text-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Estado Actual</p>
                                        <p class="text-sm font-bold text-red-600">Para Corrección</p>
                                    </div>
                                </div>

                                <!-- Estado Actual Detallado -->
                                <div class="mt-6 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-4 border border-red-200">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                        <p class="text-sm text-gray-700 font-medium">
                                            <strong>Requiere correcciones:</strong> Su trámite necesita ajustes antes de continuar
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Sección de Correcciones -->
                            <div class="bg-red-50 rounded-xl p-6 border border-red-200 shadow-sm">
                                <div class="text-center mb-6">
                                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center shadow-md mx-auto mb-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Correcciones Requeridas</h3>
                                    <p class="text-gray-600">Revise los detalles de las correcciones necesarias</p>
                                </div>
                                
                                @if($tramitePendiente->observaciones)
                                <div class="bg-white rounded-lg p-4 border border-red-200 mb-4">
                                    <h4 class="font-semibold text-gray-800 mb-2">Observaciones del Revisor:</h4>
                                    <p class="text-sm text-gray-700">{{ $tramitePendiente->observaciones }}</p>
                                </div>
                                @endif

                                <div class="bg-white rounded-lg p-4 border border-red-200">
                                    <h4 class="font-semibold text-gray-800 mb-3">Acciones Requeridas:</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span class="text-white text-xs font-bold">1</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Revisar Documentación</p>
                                                <p class="text-xs text-gray-600">Verifique que todos los documentos estén completos y legibles</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3">
                                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span class="text-white text-xs font-bold">2</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Realizar Correcciones</p>
                                                <p class="text-xs text-gray-600">Complete o corrija la información según las observaciones</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3">
                                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span class="text-white text-xs font-bold">3</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Reenviar Trámite</p>
                                                <p class="text-xs text-gray-600">Una vez corregido, envíe nuevamente para revisión</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-center space-x-4 pt-6 pb-6">
                        <a href="{{ route('tramites.edit', $tramitePendiente->id) }}" 
                           class="inline-flex items-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Corregir Trámite
                        </a>
                        
                        <a href="{{ route('tramites.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Volver a Trámites
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- No hay trámite pendiente -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden border border-gray-200/70">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">No hay trámites para corregir</h2>
                    <p class="text-gray-600 mb-6">Puede iniciar un nuevo trámite desde la página principal.</p>
                    <a href="{{ route('tramites.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] hover:from-[#8a1f40] hover:to-[#a51d1d] text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Ir a Trámites
                    </a>
                </div>
            </div>
        @endif

        </div>
    </div>
@endsection 