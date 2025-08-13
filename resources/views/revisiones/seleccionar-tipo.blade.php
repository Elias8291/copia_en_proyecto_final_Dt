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



            <!-- Opciones de tipo de revisión -->
            <div class="space-y-6">
                <div class="text-center mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">Selecciona el tipo de revisión</h2>
                    <p class="text-gray-600">Elige el método de revisión que vas a realizar para este trámite</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    @php
                        // Determinar qué tipo de revisión está activo basado en el status del trámite
                        $statusActivo = $tramite->status;
                        $revisionDigitalActiva = $statusActivo === 'Revision_Digital';
                        $revisionPresencialActiva = $statusActivo === 'Revision_Presencial';
                        $revisionDomiciliariaActiva = $statusActivo === 'Revision_Domiciliaria';
                    @endphp
                    
                    <!-- Tarjeta 1: Revisión Digital -->
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $revisionDigitalActiva ? 'border-gray-200' : 'border-gray-300' }} overflow-hidden {{ $revisionDigitalActiva ? 'hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2' : 'opacity-75 scale-95' }}">
                        <!-- Borde superior -->
                        @if($revisionDigitalActiva)
                            <div class="h-2 bg-gradient-to-r from-[#9d2449] to-[#8a1f40]"></div>
                        @else
                            <div class="h-2 bg-gradient-to-r from-gray-300 to-gray-400"></div>
                        @endif
                        
                        <div class="p-6">
                            @if(!$revisionDigitalActiva)
                                <!-- Tag No Disponible -->
                                <div class="flex justify-end mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        No Disponible
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Icono y contenido -->
                            <div class="flex items-start space-x-4">
                                @if($revisionDigitalActiva)
                                    <div class="bg-gradient-to-br from-[#9d2449] to-[#8a1f40] rounded-xl p-3 flex-shrink-0 shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="bg-gray-200 rounded-xl p-3 flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold {{ $revisionDigitalActiva ? 'text-gray-900' : 'text-gray-400' }} mb-2">Revisión Digital</h3>
                                    <p class="{{ $revisionDigitalActiva ? 'text-gray-600' : 'text-gray-400' }} text-sm leading-relaxed">
                                        Revisa los documentos y datos del trámite de forma digital. Evaluación completa sin contacto físico.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Botón -->
                            <div class="mt-6">
                                @if($revisionDigitalActiva)
                                    <form method="POST" action="{{ route('revisiones.iniciar', $tramite->id) }}" class="inline-block w-full">
                                        @csrf
                                        <input type="hidden" name="tipo_revision" value="Digital">
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-[#9d2449] to-[#8a1f40] text-white text-sm font-semibold rounded-xl hover:from-[#8a1f40] hover:to-[#7a1a37] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            Iniciar Revisión Digital
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed border-2 border-gray-200">
                                        No Disponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 2: Revisión Presencial -->
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $revisionPresencialActiva ? 'border-gray-200' : 'border-gray-300' }} overflow-hidden {{ $revisionPresencialActiva ? 'hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2' : 'opacity-75 scale-95' }}">
                        <!-- Borde superior -->
                        @if($revisionPresencialActiva)
                            <div class="h-2 bg-gradient-to-r from-[#8a1f40] to-[#7a1a37]"></div>
                        @else
                            <div class="h-2 bg-gradient-to-r from-gray-300 to-gray-400"></div>
                        @endif
                        
                        <div class="p-6">
                            @if(!$revisionPresencialActiva)
                                <!-- Tag No Disponible -->
                                <div class="flex justify-end mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        No Disponible
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Icono y contenido -->
                            <div class="flex items-start space-x-4">
                                @if($revisionPresencialActiva)
                                    <div class="bg-gradient-to-br from-[#8a1f40] to-[#7a1a37] rounded-xl p-3 flex-shrink-0 shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="bg-gray-200 rounded-xl p-3 flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold {{ $revisionPresencialActiva ? 'text-gray-900' : 'text-gray-400' }} mb-2">Revisión Presencial</h3>
                                    <p class="{{ $revisionPresencialActiva ? 'text-gray-600' : 'text-gray-400' }} text-sm leading-relaxed">
                                        Revisa el trámite en las instalaciones con el solicitante. Documentos originales.
                                        @if(isset($informacionCita) && $revisionPresencialActiva)
                                            <br><br><strong>Cita asignada:</strong><br>📅 {{ $informacionCita['fecha'] }} a las {{ $informacionCita['hora'] }}<br>👤 Quien debe presentarse: {{ isset($personaResponsable) ? $personaResponsable['nombre'] . ' (' . $personaResponsable['cargo'] . ')' : 'Por definir' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Botón -->
                            <div class="mt-6">
                                @if($revisionPresencialActiva)
                                    <form method="POST" action="{{ route('revisiones.iniciar', $tramite->id) }}" class="inline-block w-full">
                                        @csrf
                                        <input type="hidden" name="tipo_revision" value="Presencial">
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-[#8a1f40] to-[#7a1a37] text-white text-sm font-semibold rounded-xl hover:from-[#7a1a37] hover:to-[#6a1530] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            Iniciar Revisión Presencial
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed border-2 border-gray-200">
                                        No Disponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Revisión Domiciliaria -->
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $revisionDomiciliariaActiva ? 'border-gray-200' : 'border-gray-300' }} overflow-hidden {{ $revisionDomiciliariaActiva ? 'hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2' : 'opacity-75 scale-95' }}">
                        <!-- Borde superior -->
                        @if($revisionDomiciliariaActiva)
                            <div class="h-2 bg-gradient-to-r from-gray-400 to-gray-500"></div>
                        @else
                            <div class="h-2 bg-gradient-to-r from-gray-300 to-gray-400"></div>
                        @endif
                        
                        <div class="p-6">
                            @if(!$revisionDomiciliariaActiva)
                                <!-- Tag No Disponible -->
                                <div class="flex justify-end mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        No Disponible
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Icono y contenido -->
                            <div class="flex items-start space-x-4">
                                @if($revisionDomiciliariaActiva)
                                    <div class="bg-gradient-to-br from-gray-400 to-gray-500 rounded-xl p-3 flex-shrink-0 shadow-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="bg-gray-200 rounded-xl p-3 flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold {{ $revisionDomiciliariaActiva ? 'text-gray-900' : 'text-gray-400' }} mb-2">Revisión Domiciliaria</h3>
                                    <p class="{{ $revisionDomiciliariaActiva ? 'text-gray-600' : 'text-gray-400' }} text-sm leading-relaxed">
                                        Revisa el trámite en el domicilio del solicitante. Verificación in situ e inspección física.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Botón -->
                            <div class="mt-6">
                                @if($revisionDomiciliariaActiva)
                                    <form method="POST" action="{{ route('revisiones.iniciar', $tramite->id) }}" class="inline-block w-full">
                                        @csrf
                                        <input type="hidden" name="tipo_revision" value="Domiciliaria">
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-400 to-gray-500 text-white text-sm font-semibold rounded-xl hover:from-gray-500 hover:to-gray-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            Iniciar Revisión Domiciliaria
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed border-2 border-gray-200">
                                        No Disponible
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
</div>
@endsection 