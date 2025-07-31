@extends('layouts.app')

@section('title', 'Estado del Trámite')

@section('content')
@php
    // Buscar cita activa para este trámite
    $citaActiva = null;
    $citaPasada = false;
    $fechaActual = now();
    $infoReagendamientos = null;
    
    if (isset($tramite) && $tramite) {
        $citaActiva = \App\Models\Cita::where('tramite_id', $tramite->id)
            ->whereIn('estado', ['Programada', 'Confirmada', 'Reagendada'])
            ->first();
            
        if ($citaActiva && $citaActiva->fecha_cita < $fechaActual) {
            $citaPasada = true;
        }
        
        // Obtener información de reagendamientos
        $citaService = app(\App\Services\CitaService::class);
        $infoReagendamientos = $citaService->obtenerInfoReagendamientos($tramite);
    }
    
    $colorCirculo = match($estado ?? 'En_Revision') {
        'Aprobado' => 'bg-emerald-500',
        'Rechazado' => 'bg-red-500',
        'Para_Correccion' => 'bg-amber-500',
        'Cancelado' => 'bg-gray-500',
        'Por_Cotejar' => 'bg-purple-500',
        'En_Revision' => 'bg-blue-500',
        'Pendiente' => 'bg-slate-400',
        default => 'bg-yellow-400'
    };
    
    $colorBadge = match($estado ?? 'En_Revision') {
        'Aprobado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Rechazado' => 'bg-red-50 text-red-700 border-red-200',
        'Para_Correccion' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Cancelado' => 'bg-gray-50 text-gray-700 border-gray-300',
        'Por_Cotejar' => 'bg-purple-50 text-purple-700 border-purple-200',
        'En_Revision' => 'bg-blue-50 text-blue-700 border-blue-200',
        'Pendiente' => 'bg-slate-100 text-slate-600 border-slate-300',
        default => 'bg-yellow-50 text-yellow-800 border-yellow-200'
    };
@endphp
<div class="min-h-screen flex items-start justify-center p-2 sm:p-4 pt-4 sm:pt-6">
    <div class="w-full max-w-6xl bg-white rounded-xl shadow-xl border border-gray-100">
        {{-- Header con color primario --}}
        <div class="h-16 bg-gradient-to-r from-[#9d2449] to-[#8a203f] relative">
            <div class="absolute inset-0 bg-black/10"></div>
        </div>
        
                {{-- Avatar elegante con color primario --}}
        <div class="relative -mt-6 sm:-mt-8 px-4 sm:px-8">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center shadow-xl border-4 border-white">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            
            {{-- Título elegante --}}
            <div class="text-center mt-4">
                <h1 class="text-[#9d2449] text-2xl font-bold">Estado del Trámite</h1>
            </div>
        </div>

        {{-- Contenido principal --}}
        <div class="px-4 sm:px-8 py-2 sm:py-3">
            
            {{-- Información elegante con estado --}}
            <div class="mb-2 sm:mb-4 text-center">
                <div class="inline-flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-6 bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100 rounded-xl px-4 sm:px-8 py-3 sm:py-4 border border-slate-200 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full shadow-sm"></div>
                        <span class="text-sm text-slate-600 font-medium">Folio:</span>
                        <span class="text-sm font-bold text-slate-800">{{ $tramite_id ?? session('tramite_id') }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-5 bg-slate-300"></div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-slate-600 font-medium">Tipo:</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $tramite->tipo_tramite ?? 'Registro' }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-5 bg-slate-300"></div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-slate-600 font-medium">Estado:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border-2 {{ $colorBadge }} shadow-sm">
                            {{ $estado ?? 'En revisión' }}
                        </span>
                    </div>
                        </div>
                </div>
                

                
                {{-- Barra de progreso compacta y elegante --}}
                <div class="mt-1 sm:mt-2 mb-2 sm:mb-4">
                    <div class="bg-gradient-to-br from-slate-50 to-gray-50 rounded-lg p-3 sm:p-4 border border-slate-200 shadow-sm">
                        <h3 class="text-xs sm:text-sm font-bold text-[#9d2449] mb-2 sm:mb-3 text-center">Progreso del Trámite</h3>
                        
                        <div class="relative">
                            {{-- Barra de progreso --}}
                            <div class="w-full bg-slate-200 rounded-full h-1.5 sm:h-2 mb-2 sm:mb-3">
                                @php
                                    $progress = match($estado ?? 'En_Revision') {
                                        'Pendiente' => 20,
                                        'En_Revision' => 40,
                                        'Por_Cotejar' => 60,
                                        'Para_Correccion' => 50,
                                        'Aprobado' => 100,
                                        'Rechazado' => 100,
                                        'Cancelado' => 100,
                                        default => 30
                                    };
                                @endphp
                                <div class="bg-gradient-to-r from-[#9d2449] to-[#8a203f] h-1.5 sm:h-2 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                            </div>
                            
                            {{-- Etapas del proceso --}}
                            <div class="grid grid-cols-4 gap-1 sm:gap-2">
                                <div class="text-center">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center mx-auto mb-1 shadow-sm">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-slate-600">Enviado</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 {{ $estado === 'En_Revision' || in_array($estado, ['Por_Cotejar', 'Para_Correccion', 'Aprobado', 'Rechazado']) ? 'bg-gradient-to-br from-[#9d2449] to-[#8a203f]' : 'bg-slate-300' }} rounded-full flex items-center justify-center mx-auto mb-1 shadow-sm">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-slate-600">Revisión</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 {{ in_array($estado, ['Por_Cotejar', 'Aprobado', 'Rechazado']) ? 'bg-gradient-to-br from-[#9d2449] to-[#8a203f]' : 'bg-slate-300' }} rounded-full flex items-center justify-center mx-auto mb-1 shadow-sm">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-slate-600">Cotejo</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-5 h-5 sm:w-6 sm:h-6 {{ $estado === 'Aprobado' ? 'bg-emerald-500' : ($estado === 'Rechazado' ? 'bg-red-500' : 'bg-slate-300') }} rounded-full flex items-center justify-center mx-auto mb-1 shadow-sm">
                                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-medium text-slate-600">Finalizado</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estados específicos --}}
                @if(($estado ?? '') === 'Para_Correccion')
                    <div class="mt-2 p-5 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-amber-800 mb-2">Se requieren correcciones</h3>
                                <p class="text-gray-700 text-sm mb-4">Su trámite necesita ajustes antes de continuar con el proceso.</p>
                                
                                <div class="bg-white rounded-lg p-4 border border-amber-200">
                                    <h4 class="text-amber-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        Correcciones necesarias:
                                    </h4>
                                    <p class="text-gray-700 text-sm">{{ $tramite->correcciones_texto ?? 'Revisar documentación enviada' }}</p>
                                </div>
                                
                                <p class="text-amber-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Complete las correcciones para continuar.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(($estado ?? '') === 'Por_Cotejar')
                    <div class="mt-2 p-5 bg-gradient-to-r from-slate-50 to-gray-50 border border-slate-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-600 to-gray-700 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                @if($citaPasada && $infoReagendamientos && !$infoReagendamientos['puede_reagendar'])
                                    <div class="text-center sm:text-left">
                                        <div class="flex items-center justify-center sm:justify-start mb-3">
                                            <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-800">Cita Cancelada</h3>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-4">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-gray-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-gray-700 text-sm font-medium mb-1">Se ha alcanzado el límite máximo de reagendamientos</p>
                                                    <p class="text-gray-600 text-sm">Su cita ha sido cancelada. Podrá comenzar un nuevo trámite en las próximas horas</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($citaActiva && $citaActiva->estado === 'Reagendada')
                                    <div class="text-center sm:text-left">
                                        <div class="flex items-center justify-center sm:justify-start mb-3">
                                            <div class="w-8 h-8 bg-[#9d2449] rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-[#9d2449]">Cita Reagendada</h3>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-4">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-[#9d2449] mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-gray-700 text-sm font-medium mb-1">Su cita ha sido reagendada automáticamente</p>
                                                    <p class="text-gray-600 text-sm">Debe asistir con documentos originales en la nueva fecha programada</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center sm:text-left">
                                        <div class="flex items-center justify-center sm:justify-start mb-3">
                                            <div class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-gray-800">Cita Programada</h3>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-4">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-gray-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-gray-700 text-sm font-medium mb-1">Su cita para verificación presencial ha sido programada</p>
                                                    <p class="text-gray-600 text-sm">Debe asistir con documentos originales en la fecha y hora indicadas</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(!$citaPasada || ($infoReagendamientos && $infoReagendamientos['puede_reagendar']))
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-gray-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Detalles de la cita:
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-gray-500 rounded-full mr-3 flex-shrink-0"></span>
                                            <div>
                                                <span class="text-gray-700 text-sm font-medium">Fecha y hora:</span>
                                                <span class="text-gray-700 text-sm ml-2">
                                                    @if($citaActiva && isset($citaActiva->fecha_cita))
                                                        {{ \Carbon\Carbon::parse($citaActiva->fecha_cita)->format('d/m/Y H:i') }}
                                                        @if($citaPasada)
                                                                <span class="text-red-600 font-medium"> (Vencida)</span>
                                                        @endif
                                                    @else
                                                        Por confirmar
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                            @if($citaActiva && $citaActiva->estado === 'Reagendada')
                                            <div class="flex items-center">
                                                <span class="w-2 h-2 bg-[#9d2449] rounded-full mr-3 flex-shrink-0"></span>
                                                <div>
                                                    <span class="text-gray-700 text-sm font-medium">Estado:</span>
                                                    <span class="text-[#9d2449] text-sm ml-2 font-medium">Reagendada automáticamente</span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                        <div class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <div>
                                                <span class="text-gray-700 text-sm font-medium">Ubicación:</span>
                                                    <span class="text-gray-700 text-sm ml-2">Módulo de Proveedores, Edificio 1</span>
                                                </div>
                                        </div>
                                        <div class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <div>
                                                <span class="text-gray-700 text-sm font-medium">Dirección:</span>
                                                    <span class="text-gray-700 text-sm ml-2">Ciudad Administrativa, Tlalixtac de Cabrera, Oaxaca</span>
                                        </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($citaPasada)
                                    @if($infoReagendamientos && $infoReagendamientos['puede_reagendar'])
                                    <div class="mt-4 bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center justify-center sm:justify-start mb-4">
                                            <div class="w-10 h-10 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-gray-800 font-bold text-lg">Cita Vencida</h5>
                                                <p class="text-gray-600 text-sm">Procesamiento automático en curso</p>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                                <div class="flex items-center mb-3">
                                                    <svg class="w-5 h-5 text-[#9d2449] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <h6 class="text-gray-800 font-semibold">Reagendamiento Automático</h6>
                                                </div>
                                                <p class="text-gray-700 text-sm mb-3">Su cita será reagendada automáticamente en las próximas horas.</p>
                                                <div class="bg-gray-50 rounded p-3">
                                                    <p class="text-gray-700 text-xs font-medium">Recibirá una notificación por correo electrónico con la nueva fecha.</p>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                                                <div class="flex items-center mb-3">
                                                    <svg class="w-5 h-5 text-[#9d2449] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                    <h6 class="text-gray-800 font-semibold">Notificación</h6>
                                                </div>
                                                <p class="text-gray-700 text-sm mb-3">Revise su correo electrónico para más información sobre el estado de su trámite.</p>
                                                <div class="bg-gray-50 rounded p-3">
                                                    <p class="text-gray-700 text-xs font-medium">El sistema procesará automáticamente su solicitud.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="mt-4 bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <div class="flex items-center justify-center sm:justify-start mb-4">
                                            <div class="w-10 h-10 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm mr-3">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h5 class="text-gray-800 font-bold text-lg">Cita Cancelada</h5>
                                                <p class="text-gray-600 text-sm">Límite de reagendamientos alcanzado</p>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-4">
                                            <div class="flex items-center mb-3">
                                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                                <h6 class="text-gray-800 font-semibold">Trámite Cancelado</h6>
                                            </div>
                                            <p class="text-gray-700 text-sm mb-3">Se ha alcanzado el límite máximo de reagendamientos. Su cita ha sido cancelada.</p>
                                            <div class="bg-gray-50 rounded p-3">
                                                <p class="text-gray-700 text-xs font-medium">En las próximas horas podrá comenzar un nuevo trámite.</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-center sm:justify-start">
                                            <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-6 py-3 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a203f] transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                                Iniciar Nuevo Trámite
                                            </a>
                                        </div>
                                        </div>
                                    @endif
                                @endif
                                
                                @if(!$citaPasada || ($infoReagendamientos && $infoReagendamientos['puede_reagendar']))
                                <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h5 class="text-gray-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        Información importante:
                                    </h5>
                                    
                                    @php
                                        $presentarse = app(\App\Services\ProveedorService::class)->getQuienDebePresentarse($tramite);
                                    @endphp
                                    
                                    <div class="mb-4">
                                        <p class="text-gray-700 text-sm font-medium mb-1">Quien debe presentarse:</p>
                                        <p class="text-[#9d2449] text-sm font-medium">{{ $presentarse }}</p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-gray-700 text-sm font-medium mb-2">Documentos requeridos:</p>
                                        <ul class="text-gray-700 text-sm space-y-1">
                                            @php
                                                // Determinar tipo de persona para los documentos
                                                $rfc = $tramite->proveedor->rfc ?? '';
                                                $tipoPersona = (strlen($rfc) === 12) ? 'Moral' : 'Física';
                                                $documentos = \App\Services\DocumentosService::getDocumentosPorTipoPersona($tipoPersona);
                                            @endphp
                                            
                                            @if($documentos->count() > 0)
                                                @foreach($documentos as $documento)
                                                    <li class="flex items-start">
                                                        <span class="w-1.5 h-1.5 bg-[#9d2449] rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <span class="text-gray-700">{{ $documento->nombre }}</span>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="flex items-start">
                                                    <span class="w-1.5 h-1.5 bg-[#9d2449] rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                    <span class="text-gray-700">Documentos originales del trámite</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <span class="w-1.5 h-1.5 bg-[#9d2449] rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                    <span class="text-gray-700">Identificación oficial vigente</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <span class="w-1.5 h-1.5 bg-[#9d2449] rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                    <span class="text-gray-700">Comprobante de domicilio</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                
                                @if(!$citaPasada || ($infoReagendamientos && $infoReagendamientos['puede_reagendar']))
                                <p class="text-gray-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Llegue 15 minutos antes de su cita programada.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @elseif(($estado ?? '') === 'Cancelado')
                    <div class="mt-2 p-5 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Trámite cancelado</h3>
                                <p class="text-gray-700 text-sm mb-4">El trámite ha sido cancelado por el solicitante o por el sistema.</p>
                                
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-gray-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Información adicional:
                                    </h4>
                                    <p class="text-gray-700 text-sm">Si considera que esto es un error, contacte al administrador del sistema.</p>
                                </div>
                                
                                <p class="text-gray-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Para iniciar un nuevo trámite, regrese al formulario.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(($estado ?? '') === 'En_Revision')
                    <div class="mt-2 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-blue-800 mb-2">En revisión técnica</h3>
                                <p class="text-gray-700 text-sm mb-4">Su documentación está siendo analizada por nuestro equipo especializado.</p>
                                
                                <div class="bg-white rounded-lg p-4 border border-blue-200">
                                    <h4 class="text-blue-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Proceso actual:
                                    </h4>
                                    <ul class="text-gray-700 text-sm space-y-2">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Análisis de documentación</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Verificación de requisitos</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Validación de información</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <p class="text-blue-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Tiempo estimado: 2-3 días hábiles.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(($estado ?? '') === 'Aprobado')
                    <div class="mt-2 p-5 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-emerald-800 mb-2">¡Trámite aprobado!</h3>
                                <p class="text-gray-700 text-sm mb-4">Su solicitud ha sido procesada exitosamente y aprobada por las autoridades correspondientes.</p>
                                
                                <div class="bg-white rounded-lg p-4 border border-emerald-200">
                                    <h4 class="text-emerald-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                        Próximos pasos:
                                    </h4>
                                    <ul class="text-gray-700 text-sm space-y-2">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Recibirá notificación oficial</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Documentos disponibles en línea</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Proceso completado</span>
                                        </li>
                                    </ul>
                                                </div>
                                
                                <p class="text-emerald-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    ¡Felicitaciones! Su trámite ha sido completado exitosamente.
                                </p>
                                
                                <div class="mt-4 flex justify-center sm:justify-start">
                                    <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white text-sm font-medium rounded-lg hover:from-emerald-600 hover:to-green-700 transition-all duration-200 shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Iniciar Nuevo Trámite
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                                @elseif(($estado ?? '') === 'Pendiente')
                    <div class="mt-2 p-4 sm:p-5 bg-gradient-to-r from-slate-50 to-gray-50 border border-slate-200 rounded-lg shadow-sm h-32 sm:h-36 flex items-center">
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 text-center sm:text-left w-full">
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-400 to-gray-500 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-bold text-slate-700 mb-1">¡Trámite enviado correctamente!</h3>
                                <p class="text-gray-600 text-xs mb-1">Ha sido enviado para revisión técnica.</p>
                                
                                <p class="text-gray-600 text-xs leading-relaxed">
                                    <span class="font-medium text-slate-700">Tiempo estimado:</span> 1-2 días hábiles
                                </p>
                                
                                <p class="text-slate-600 text-xs mt-1 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Recibirá notificación por email.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(($estado ?? '') === 'Rechazado')
                    <div class="mt-2 p-5 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Trámite rechazado</h3>
                                <p class="text-gray-700 text-sm mb-4">Su solicitud no cumple con los requisitos establecidos por las autoridades.</p>
                                
                                @if($tramite->observaciones)
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-4">
                                    <h4 class="text-gray-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        Comentarios del trámite:
                                    </h4>
                                    <p class="text-gray-700 text-sm bg-gray-50 rounded p-3 border-l-4 border-[#9d2449]">{{ $tramite->observaciones }}</p>
                                </div>
                                @endif
                                
                                <p class="text-gray-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Puede iniciar un nuevo trámite con la información corregida.
                                </p>
                                
                                <div class="mt-4 flex justify-center sm:justify-start">
                                    <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-6 py-3 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a203f] transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Iniciar Nuevo Trámite
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-2 p-5 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-4 text-center sm:text-left">
                            <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-slate-600 rounded-lg flex items-center justify-center shadow-sm flex-shrink-0 mx-auto sm:mx-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Estado no definido</h3>
                                <p class="text-gray-700 text-sm mb-4">El estado de su trámite no está disponible en este momento.</p>
                                
                                <div class="bg-white rounded-lg p-4 border border-gray-200">
                                    <h4 class="text-gray-800 font-semibold text-sm mb-3 flex items-center justify-center sm:justify-start">
                                        <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Recomendación:
                                    </h4>
                                    <p class="text-gray-700 text-sm">Contacte al administrador del sistema para obtener más información sobre el estado de su trámite.</p>
                                </div>
                                
                                <p class="text-gray-700 text-sm mt-3 font-medium flex items-center justify-center sm:justify-start">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Para asistencia técnica, contacte al soporte.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 