@extends('layouts.app')

@section('title', 'Estado del Trámite')

@section('content')
<div class="min-h-screen ">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200/70 p-8">
            {{-- Encabezado --}}
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-emerald-300 rounded-full flex items-center justify-center">
                    <svg class="w-9 h-9 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Estado del Trámite</h1>
                    <p class="text-sm text-gray-500">Folio: <span class="font-mono">{{ $tramite_id ?? session('tramite_id') }}</span></p>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center space-x-3">
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
                            'Aprobado' => 'bg-emerald-100 text-emerald-700',
                            'Rechazado' => 'bg-red-100 text-red-700',
                            'Para_Correccion' => 'bg-amber-100 text-amber-700',
                            'Cancelado' => 'bg-gray-100 text-gray-700',
                            'Por_Cotejar' => 'bg-purple-100 text-purple-700',
                            'En_Revision' => 'bg-blue-100 text-blue-700',
                            default => 'bg-yellow-100 text-yellow-800'
                        };
                    @endphp
                    
                    <span class="inline-block w-3 h-3 rounded-full {{ $colorCirculo }}"></span>
                    <span class="text-lg font-semibold text-gray-800">Estado: </span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide {{ $colorBadge }}">
                        {{ $estado ?? 'En revisión' }}
                    </span>
                </div>
                @if($estado === 'Para_Correccion')
                    <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-300 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-amber-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-amber-800 font-semibold">⚠️ Su trámite requiere correcciones</p>
                                <p class="text-amber-700 text-sm mt-1">Nuestro equipo administrativo ha revisado su documentación y detectó algunos aspectos que necesitan ser corregidos antes de continuar con el proceso.</p>
                                
                                <div class="mt-3 p-3 bg-amber-100 rounded-lg border border-amber-200">
                                    <p class="text-amber-800 font-medium text-sm mb-2">📋 Acciones requeridas:</p>
                                    <ul class="text-amber-700 text-xs space-y-1">
                                        <li>• Revise las observaciones específicas realizadas por el equipo</li>
                                        <li>• Corrija la información o documentación señalada</li>
                                        <li>• Vuelva a enviar el trámite una vez realizadas las correcciones</li>
                                    </ul>
                                </div>
                                
                                <p class="text-amber-600 text-xs mt-3 font-medium">🔄 Una vez corregido, su trámite será procesado con prioridad.</p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'Por_Cotejar')
                    <div class="mt-4 p-5 bg-white border-2 border-gray-200 rounded-xl shadow-lg">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Cita Programada - Cotejo Presencial</h3>
                                <p class="text-gray-600 text-xs">Su documentación será verificada por nuestro equipo especializado.</p>
                            </div>
                        </div>
                        
                        @if(isset($cita))
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium">FECHA</p>
                                    <p class="text-base font-bold text-primary">{{ \Carbon\Carbon::parse($cita['fecha_cita'])->format('d/m/Y') }}</p>
                                    <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($cita['fecha_cita'])->format('H:i') }} hrs</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium">UBICACIÓN</p>
                                    <p class="text-xs font-bold text-primary">Ciudad Administrativa</p>
                                    <p class="text-xs text-gray-600">Edificio 1, Módulo Proveedores</p>
                                </div>
                                
                                <div class="text-center">
                                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 font-medium">DOCUMENTOS</p>
                                    <p class="text-xs font-bold text-primary">Originales</p>
                                    <p class="text-xs text-gray-600">No copias</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <div class="flex items-center space-x-2 text-xs text-gray-700">
                                    <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span class="font-medium">Presentarse puntualmente con todos los documentos originales subidos al sistema</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($estado === 'Cancelado')
                    <div class="mt-4 p-4 bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-300 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-gray-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-gray-800 font-semibold">❌ Trámite cancelado</p>
                                <p class="text-gray-700 text-sm mt-1">Su trámite ha sido cancelado. Puede iniciar un nuevo proceso de registro cuando esté listo o consultar los motivos de la cancelación.</p>
                                <p class="text-gray-600 text-xs mt-2 font-medium">📞 Para más información, contacte a nuestro equipo de soporte.</p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'En_Revision')
                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-blue-800 font-semibold">🔍 Trámite en revisión detallada</p>
                                <p class="text-blue-700 text-sm mt-1">Su expediente está siendo revisado minuciosamente por nuestro equipo técnico. Estamos verificando que toda la documentación cumpla con los requisitos establecidos.</p>
                                <p class="text-blue-600 text-xs mt-2 font-medium">⏱️ Este proceso puede tomar algunos días hábiles para garantizar la calidad de la revisión.</p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'Aprobado')
                    <div class="mt-4 p-4 bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-emerald-800 font-semibold">¡Felicidades! Su trámite ha sido aprobado.</p>
                                <p class="text-emerald-700 text-sm mt-1">Su registro está activo y puede acceder a todos los servicios del padrón.</p>
                            </div>
                        </div>
                    </div>
                @elseif($estado === 'Rechazado')
                    <div class="mt-4 p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-red-800 font-semibold">Su trámite fue rechazado.</p>
                                <p class="text-red-700 text-sm mt-1">Revise las observaciones y corrija la información solicitada.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-blue-800 font-semibold">Tiene un trámite pendiente</p>
                                <p class="text-blue-700 text-sm mt-1">Su documentación está siendo revisada por nuestro equipo administrativo. Una vez que el trámite haya sido procesado, se le notificará el resultado y se habilitarán automáticamente los trámites que le puedan corresponder según su estado en el padrón.</p>
                                <p class="text-blue-600 text-xs mt-2 font-medium">💡 Recibirá una notificación por correo electrónico cuando la revisión esté completa.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-8">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-base font-medium text-blue-800">Paso actual:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide bg-blue-100 text-blue-800">
                        {{ $paso_actual ?? 'Recepción de documentos' }}
                    </span>
                </div>
            </div>

 
            <div class="flex gap-4 justify-center mt-8">
                <a href="{{ route('tramites.index') }}" class="px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition font-semibold shadow-md">Ver Mis Trámites</a>
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">Ir al Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection 