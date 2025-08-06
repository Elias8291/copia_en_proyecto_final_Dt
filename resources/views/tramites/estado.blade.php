@extends('layouts.app')

@section('title', 'Estado del Trámite')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">



                @if($tramitePendiente)
            <!-- Tarjeta Principal del Trámite -->
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <!-- Header Elegante -->
                    <div class="bg-gradient-to-br from-[#9D2449] to-[#B91C1C] p-6 relative overflow-hidden border-b border-gray-200">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#8a1f40]/20 to-[#9D2449]/30"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-center text-center">
                                <div class="space-y-3">
                                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-lg mx-auto">
                                        <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $statusLabel }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido Principal -->
                    <div class="p-6">

                    

                    

                    <!-- Cita Asignada -->
                    @if($citaAsignada)
                    <div class="space-y-6 mb-6">
                        <!-- Header de la Cita -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-[#9D2449] rounded-lg flex items-center justify-center shadow-md mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V7a2 2 0 012-2h4a2 2 0 012 2v4m-6 0v4a2 2 0 002 2h4a2 2 0 002-2v-4m-6 0v4a2 2 0 002 2h4a2 2 0 002-2v-4"></path>
                                    </svg>
                                </div>
                                
                                @if($citaVencida)
                                    <h3 class="text-xl font-bold text-red-600 mb-2">Cita Vencida</h3>
                                    <p class="text-gray-700 font-medium text-lg mb-4">{{ $citaAsignada->fecha_cita->format('d/m/Y') }} a las {{ $citaAsignada->fecha_cita->format('H:i') }}</p>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                        <p class="text-sm text-red-700">
                                            <strong>No asistió a la cita.</strong> Su fecha venció pero la cita se reagendará automáticamente.
                                        </p>
                                    </div>
                                    @if($intentosRestantes > 0)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p class="text-sm text-blue-700">
                                                @if($intentosRestantes == 1)
                                                    <strong>Último intento disponible.</strong> Se le reagendará una nueva cita automáticamente en el transcurso del día. <strong>Se le notificará.</strong>
                                                @else
                                                    <strong>Intentos restantes:</strong> {{ $intentosRestantes }} de 2. Se le reagendará una nueva cita automáticamente en el transcurso del día. <strong>Se le notificará.</strong>
                                                @endif
                                            </p>
                                        </div>
                                    @else
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                            <p class="text-sm text-red-700">
                                                <strong>Sin intentos restantes.</strong> Ha agotado sus oportunidades de cita digital.
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Cita Confirmada</h3>
                                    <p class="text-gray-700 font-medium text-lg mb-4">{{ $citaAsignada->fecha_cita->format('d/m/Y') }} a las {{ $citaAsignada->fecha_cita->format('H:i') }}</p>
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <p class="text-sm text-gray-700">
                                            <strong>Importante:</strong> Debe llevar su documentación que subió al sistema.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Información de Ubicación y Hora -->
                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                                <div class="space-y-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Ubicación</p>
                                        <p class="text-sm font-bold text-gray-800">Ciudad Administrativa</p>
                                        <p class="text-xs text-gray-500">Edificio 1, José Vasconcelos</p>
                                    </div>
                                </div>
                                <div class="space-y-3 border-l border-r border-gray-200">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Módulo</p>
                                        <p class="text-sm font-bold text-gray-800">Proveedores</p>
                                        <p class="text-xs text-gray-500">Piso 1</p>
                                    </div>
                                </div>
                                <div class="space-y-3 border-r border-gray-200">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Hora de Cita</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $citaAsignada->fecha_cita->format('H:i') }}</p>
                                        <p class="text-xs text-gray-500">{{ $citaAsignada->fecha_cita->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Quien debe presentarse</p>
                                        <p class="text-sm font-bold text-gray-800">
                                            @if($personaResponsable)
                                                {{ $personaResponsable['nombre'] }}
                                            @else
                                                Por definir
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @if($personaResponsable)
                                                {{ $personaResponsable['tipo'] }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información del Atendiente -->
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-[#9D2449] rounded-lg flex items-center justify-center shadow-sm mx-auto mb-3">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-1">Será atendido por</h4>
                                <p class="text-xl font-bold text-[#9D2449]">
                                    @if($citaAsignada->asignadoA)
                                        @php
                                            $nombres = explode(' ', $citaAsignada->asignadoA->nombre);
                                            $primerosDos = array_slice($nombres, 0, 2);
                                            echo implode(' ', $primerosDos);
                                        @endphp
                                    @else
                                        Por asignar
                                    @endif
                                </p>
                                @if($citaAsignada->asignadoA && $citaAsignada->asignadoA->cargo)
                                    <p class="text-sm text-gray-600 mt-1">{{ $citaAsignada->asignadoA->cargo }}</p>
                                @endif
                            </div>
                        </div>
                        

                        

                    </div>
                    @endif

                    

                    <!-- Botón de Acción -->
                    <div class="flex justify-center pt-6">
                        <a href="{{ route('tramites.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-[#9D2449] hover:bg-[#B91C1C] text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
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
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">No hay trámites pendientes</h2>
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