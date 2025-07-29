@extends('layouts.app')

@section('title', 'Estado del Trámite')

@php
    $colorCirculo = match($estado) {
        'Aprobado' => 'bg-emerald-500',
        'Rechazado' => 'bg-red-500',
        'Para_Correccion' => 'bg-amber-500',
        'Cancelado' => 'bg-gray-500',
        'Por_Cotejar' => 'bg-purple-500',
        'En_Revision' => 'bg-blue-500',
        default => 'bg-yellow-400'
    };
    
    $colorBadge = match($estado) {
        'Aprobado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'Rechazado' => 'bg-red-50 text-red-700 border-red-200',
        'Para_Correccion' => 'bg-amber-50 text-amber-700 border-amber-200',
        'Cancelado' => 'bg-gray-50 text-gray-700 border-gray-300',
        'Por_Cotejar' => 'bg-purple-50 text-purple-700 border-purple-200',
        'En_Revision' => 'bg-blue-50 text-blue-700 border-blue-200',
        default => 'bg-yellow-50 text-yellow-800 border-yellow-200'
    };
@endphp

@section('content')
<div class="min-h-screen flex items-start justify-center p-4 sm:p-6 lg:p-8 pt-16">
    <div class="w-full max-w-7xl bg-white rounded-2xl shadow-2xl border border-gr ay-100 overflow-hidden">
        {{-- Header elegante --}}
        <div class="relative h-12 sm:h-20 lg:h-28 bg-gradient-to-br from-[#9d2449] via-[#8a203f] to-[#7a1d37] overflow-hidden">
            <div class="absolute inset-0 bg-black/10"></div>
        </div>
        
        {{-- Avatar elegante --}}
        <div class="relative -mt-16 sm:-mt-20 px-6 sm:px-8">
            <div class="flex justify-center">
                <div class="relative">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center shadow-xl border-4 border-white">
                        <svg class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-black rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            {{-- Título abajo del círculo --}}
            <div class="text-center mt-4">
                <h1 class="text-black text-xl sm:text-2xl lg:text-3xl font-bold tracking-wide">Estado del Trámite</h1>

        
        {{-- Contenido principal --}}
        <div class="px-6 sm:px-8 lg:px-12 py-8 sm:py-12">
            <div class="text-center mb-6">
                <div class="inline-flex items-center space-x-4 bg-black/5 rounded-full px-4 py-2 mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-[#9d2449] rounded-full"></div>
                        <span class="text-xs font-medium text-black/70">Folio</span>
                        <span class="text-xs font-bold text-black">{{ $tramite_id ?? session('tramite_id') }}</span>
                    </div>
                    <div class="w-px h-4 bg-black/20"></div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-block w-3 h-3 rounded-full {{ $colorCirculo }} shadow-sm"></span>
                        <span class="text-xs font-medium text-black/70">Estado:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold tracking-wide border {{ $colorBadge }} shadow-sm">
                            {{ $estado ?? 'En revisión' }}
                        </span>
                    </div>
                </div>
                
                @php
                    $colorCirculo = match($estado) {
                        'Aprobado' => 'bg-emerald-500',
                        'Rechazado' => 'bg-red-500',
                        'Para_Correccion' => 'bg-amber-500',
                        'Cancelado' => 'bg-gray-500',
                        'Por_Cotejar' => 'bg-purple-500',
                        'En_Revision' => 'bg-blue-500',
                        default => 'bg-yellow-400'
                    };
                    
                    $colorBadge = match($estado) {
                        'Aprobado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Rechazado' => 'bg-red-50 text-red-700 border-red-200',
                        'Para_Correccion' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'Cancelado' => 'bg-gray-50 text-gray-700 border-gray-300',
                        'Por_Cotejar' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'En_Revision' => 'bg-blue-50 text-blue-700 border-blue-200',
                        default => 'bg-yellow-50 text-yellow-800 border-yellow-200'
                    };
                @endphp
                

                

                



                @if($estado === 'Para_Correccion')
                    <div class="mt-6 p-6 bg-gradient-to-r from-[#9d2449]/5 to-[#8a203f]/5 border border-[#9d2449]/20 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#9d2449] mb-2">Su trámite requiere correcciones</h3>
                                <p class="text-gray-700 text-sm mb-4">Nuestro equipo administrativo ha revisado su documentación y detectó algunos aspectos que necesitan ser corregidos antes de continuar con el proceso.</p>
                                
                                <div class="bg-black/5 rounded-lg p-4 border border-black/10">
                                    <h4 class="text-black font-semibold text-sm mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Acciones requeridas:
                                    </h4>
                                    <ul class="text-gray-700 text-sm space-y-2">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-[#9d2449] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Revise las observaciones específicas realizadas por el equipo</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-[#9d2449] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Corrija la información o documentación señalada</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-[#9d2449] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                            <span>Vuelva a enviar el trámite una vez realizadas las correcciones</span>
                                        </li>
                                    </ul>
                                </div>
                                
                                <p class="text-[#9d2449] text-sm mt-4 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Una vez corregido, su trámite será procesado con prioridad.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'Por_Cotejar')
                    <div class="mt-6 p-6 bg-gradient-to-r from-[#9d2449]/5 to-[#8a203f]/5 border border-[#9d2449]/20 rounded-xl shadow-sm">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-[#9d2449]">Cita Programada - Cotejo Presencial</h3>
                                <p class="text-gray-700 text-sm">Su documentación será verificada por nuestro equipo especializado.</p>
                            </div>
                        </div>
                        
                        @if(isset($cita))
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                                <div class="bg-white rounded-lg p-4 border border-black/10 shadow-sm">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-[#9d2449] rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-black/60 font-medium">FECHA</p>
                                            <p class="text-sm font-bold text-black">{{ \Carbon\Carbon::parse($cita['fecha_cita'])->format('d/m/Y') }}</p>
                                            <p class="text-xs text-black/60">{{ \Carbon\Carbon::parse($cita['fecha_cita'])->format('H:i') }} hrs</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-lg p-4 border border-black/10 shadow-sm">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-[#9d2449] rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-black/60 font-medium">UBICACIÓN</p>
                                            <p class="text-sm font-bold text-black">Ciudad Administrativa</p>
                                            <p class="text-xs text-black/60">Edificio 1, Módulo Proveedores</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white rounded-lg p-4 border border-black/10 shadow-sm">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-[#9d2449] rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-black/60 font-medium">DOCUMENTOS</p>
                                            <p class="text-sm font-bold text-black">Originales</p>
                                            <p class="text-xs text-black/60">No copias</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-black/5 rounded-lg p-4 border border-black/10">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-[#9d2449] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-black font-semibold text-sm mb-1">Información importante:</p>
                                        <p class="text-gray-700 text-sm">Presentarse puntualmente con todos los documentos originales subidos al sistema</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($estado === 'Cancelado')
                    <div class="mt-6 p-6 bg-gradient-to-r from-black/5 to-black/10 border border-black/20 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-black mb-2">Trámite cancelado</h3>
                                <p class="text-gray-700 text-sm mb-4">Su trámite ha sido cancelado. Puede iniciar un nuevo proceso de registro cuando esté listo o consultar los motivos de la cancelación.</p>
                                <p class="text-black text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Para más información, contacte a nuestro equipo de soporte.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'En_Revision')
                    <div class="mt-6 p-6 bg-gradient-to-r from-[#9d2449]/5 to-[#8a203f]/5 border border-[#9d2449]/20 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#9d2449] mb-2">Trámite en revisión detallada</h3>
                                <p class="text-gray-700 text-sm mb-4">Su expediente está siendo revisado minuciosamente por nuestro equipo técnico. Estamos verificando que toda la documentación cumpla con los requisitos establecidos.</p>
                                <p class="text-[#9d2449] text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Este proceso puede tomar algunos días hábiles para garantizar la calidad de la revisión.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'Aprobado')
                    <div class="mt-6 p-6 bg-gradient-to-r from-[#9d2449]/5 to-[#8a203f]/5 border border-[#9d2449]/20 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#9d2449] mb-2">¡Felicidades! Su trámite ha sido aprobado.</h3>
                                <p class="text-gray-700 text-sm">Su registro está activo y puede acceder a todos los servicios del padrón.</p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'Rechazado')
                    <div class="mt-6 p-6 bg-gradient-to-r from-black/5 to-black/10 border border-black/20 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-black rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-black mb-2">Su trámite fue rechazado.</h3>
                                <p class="text-gray-700 text-sm">Revise las observaciones y corrija la información solicitada.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-6 bg-gradient-to-r from-[#9d2449]/5 to-[#8a203f]/5 border border-[#9d2449]/20 rounded-xl shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#9d2449] rounded-lg flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#9d2449] mb-2">Tiene un trámite pendiente</h3>
                                <p class="text-gray-700 text-sm mb-4">Su documentación está siendo revisada por nuestro equipo administrativo. Una vez que el trámite haya sido procesado, se le notificará el resultado y se habilitarán automáticamente los trámites que le puedan corresponder según su estado en el padrón.</p>
                                <p class="text-[#9d2449] text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Recibirá una notificación por correo electrónico cuando la revisión esté completa.
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