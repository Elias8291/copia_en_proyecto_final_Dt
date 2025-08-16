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


            <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">

                <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] p-6 text-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">Estado del Trámite</h1>
                    <p class="text-white/90">Trámite #{{ $tramitePendiente->id }}</p>
                </div>
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
                    @elseif($tramitePendiente->status === 'Revision_Digital')
                        <!-- Información de Revisión Digital -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center space-x-2 mb-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                <p class="text-sm text-blue-700 font-medium">
                                    <strong>En revisión digital:</strong> Su trámite está siendo evaluado
                                </p>
                            </div>
                            
                            @if($tramitePendiente->revisorDigital)
                                <div class="bg-white border border-blue-200 rounded-lg p-3 mt-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-800">Su trámite será revisado por:</p>
                                            <p class="text-sm text-blue-700">{{ $tramitePendiente->revisorDigital->nombre ?? 'Revisor Digital' }}</p>
                                            <p class="text-xs text-blue-600">Revisor Digital Asignado</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-white border border-blue-200 rounded-lg p-3 mt-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Nota:</strong> Su trámite será asignado próximamente a un revisor digital
                                    </p>
                                </div>
                            @endif
                        </div>
                    @elseif($tramitePendiente->status === 'Revision_Presencial')
                        <div class="bg-gradient-to-r from-blue-50 to-sky-50 border border-blue-200 rounded-xl p-4 mb-4 shadow-sm">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                <h4 class="text-sm font-bold text-blue-800">🏢 Revisión Presencial</h4>
                            </div>
                            
                            @if($citaAsignada)
                                @if($citaVencida ?? false)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                                        <div class="text-center">
                                            <h4 class="text-lg font-bold text-red-600">❌ Cita Vencida</h4>
                                            <p class="text-base text-red-700">{{ $citaAsignada->fecha_cita->format('d/m/Y H:i') }}</p>
                                            <p class="text-sm text-red-600 mt-2">No asistió a la cita. Se reagendará automáticamente.</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Información de la cita -->
                                    <div class="bg-white/80 rounded-lg p-3 mb-3">
                                        <div class="text-center">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-2">
                                                ✅ Cita Confirmada
                                            </div>
                                            <p class="text-lg font-bold text-gray-800">{{ $citaAsignada->fecha_cita->format('d/m/Y H:i') }}</p>
                                            @if($citaAsignada->asignadoA)
                                                <p class="text-sm text-gray-600">Le atenderá: <strong>{{ $citaAsignada->asignadoA->nombre }}</strong></p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Ubicación simple -->
                                    <div class="bg-white/80 rounded-lg p-3 mb-3">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="text-sm">📍</span>
                                            <span class="text-sm font-semibold text-gray-800">Ubicación:</span>
                                        </div>
                                        <div class="text-xs text-gray-700 ml-6">
                                            <p><strong>Módulo 1 de Proveedores</strong></p>
                                            <p>Ciudad Administrativa, Edificio José Vasconcelos, Piso 1</p>
                                            <p class="text-gray-500 mt-1">💡 Llegue 15 minutos antes</p>
                                        </div>
                                    </div>

                                    <!-- Quién debe asistir -->
                                    <div class="bg-white/80 rounded-lg p-3">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="text-sm">👤</span>
                                            <span class="text-sm font-semibold text-gray-800">Debe asistir:</span>
                                        </div>
                                        <div class="text-xs text-gray-700 ml-6 space-y-1">
                                            @if($tramitePendiente->proveedor && $tramitePendiente->proveedor->tipo_persona === 'Moral')
                                                <p>• <strong>Representante Legal</strong> con credencial vigente</p>
                                            @else
                                                <p>• <strong>Titular</strong> con credencial vigente</p>
                                            @endif
                                            <p>• Todos los documentos originales para cotejo</p>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="bg-white/80 rounded-lg p-3">
                                    <p class="text-sm text-blue-700">
                                        ⏳ <strong>Su cita será programada pronto</strong>
                                    </p>
                                </div>
                            @endif
                        </div>
                    @elseif($tramitePendiente->status === 'Revision_Domiciliaria')
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-4 shadow-sm">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-green-800">Revisión Domiciliaria</h3>
                                    <p class="text-sm text-green-600">Se realizará una visita a su domicilio</p>
                                </div>
                            </div>
                            
                            @if($citaAsignada)
                                @if($citaVencida ?? false)
                                    <div class="text-center">
                                        <div class="text-red-600 mb-3">
                                            <h4 class="text-xl font-bold">Visita Vencida</h4>
                                            <p class="text-lg">{{ $citaAsignada->fecha_cita->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                            <p class="text-sm text-red-700">
                                                <strong>No se realizó la visita.</strong> Se reprogramará automáticamente.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center mb-4">
                                        <h4 class="text-xl font-bold text-green-700 mb-2">Visita Programada</h4>
                                        <p class="text-2xl font-bold text-green-800 mb-4">{{ $citaAsignada->fecha_cita->format('d/m/Y H:i') }}</p>
                                        
                                        @if($revisorDomiciliario)
                                            <div class="bg-white rounded-lg p-4 mb-4 border border-green-200">
                                                <p class="text-sm text-green-700 mb-1">Le visitará:</p>
                                                <p class="text-lg font-semibold text-green-800">{{ $revisorDomiciliario->nombre }}</p>
                                                <p class="text-xs text-green-600">Revisor Domiciliario</p>
                                            </div>
                                        @elseif($citaAsignada && $citaAsignada->asignadoA)
                                            <div class="bg-white rounded-lg p-4 mb-4 border border-green-200">
                                                <p class="text-sm text-green-700 mb-1">Le visitará:</p>
                                                <p class="text-lg font-semibold text-green-800">{{ $citaAsignada->asignadoA->nombre }}</p>
                                                <p class="text-xs text-green-600">Revisor Domiciliario</p>
                                            </div>
                                        @endif
                                        
                                        <div class="bg-green-100 rounded-lg p-4 text-left">
                                            <h5 class="font-semibold text-green-800 mb-2">📋 Importante:</h5>
                                            <ul class="text-sm text-green-700 space-y-1">
                                                <li>• Esté disponible en su domicilio registrado</li>
                                                <li>• Tenga lista toda su documentación</li>
                                                <li>• El revisor verificará los documentos en persona</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="text-center bg-green-100 rounded-lg p-4">
                                    <p class="text-sm text-green-700">
                                        <strong>En proceso:</strong> Se programará una visita domiciliaria próximamente
                                    </p>
                                </div>
                            @endif
                        </div>
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




        @else
            {{-- No hay trámites pendientes - Mostrar estado del proveedor --}}
            @if($proveedores && $proveedores->isNotEmpty())
                @php
                    $proveedor = $proveedores->first();
                    $diasRestantes = null;
                    $requiereRenovacion = false;
                    $requiereActualizacion = false;
                    $requiereInscripcion = false;
                    
                    // Verificar si hay trámites rechazados recientes
                    $tramiteRechazado = \App\Models\Tramite::where('proveedor_id', $proveedor->id)
                        ->where('status', 'Rechazado')
                        ->orderBy('created_at', 'desc')
                        ->first();
                    
                    if ($proveedor->fecha_vencimiento_padron) {
                        $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                        $diasRestantes = $fechaVencimiento->diffInDays(now(), false);
                        
                        // Lógica ajustada según los requerimientos
                        if ($proveedor->estado_padron === 'Activo') {
                            // Si está activo, solo mostrar renovación/actualización 7 días antes
                            if ($diasRestantes <= 7 && $diasRestantes >= 0) {
                                $requiereActualizacion = true;
                                $requiereRenovacion = true;
                            }
                        } elseif ($proveedor->estado_padron === 'Vencido' || $tramiteRechazado) {
                            // Si está vencido o tiene trámite rechazado, requiere inscripción
                            $requiereInscripcion = true;
                        }
                    } else {
                        // Si no tiene fecha de vencimiento, probablemente necesite inscripción
                        $requiereInscripcion = true;
                    }
                @endphp
                
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6">
                    <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] p-6 text-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white mb-2">Estado del Proveedor</h1>
                        <p class="text-white/90">{{ $proveedor->razon_social }}</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">PV Número</p>
                                <p class="text-sm font-bold text-gray-800">{{ $proveedor->pv_numero ?? 'No asignado' }}</p>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-10 h-10 {{ $proveedor->estado_padron === 'Activo' ? 'bg-green-100' : ($proveedor->estado_padron === 'Vencido' ? 'bg-red-100' : 'bg-yellow-100') }} rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 {{ $proveedor->estado_padron === 'Activo' ? 'text-green-600' : ($proveedor->estado_padron === 'Vencido' ? 'text-red-600' : 'text-yellow-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Estado</p>
                                <p class="text-sm font-bold {{ $proveedor->estado_padron === 'Activo' ? 'text-green-800' : ($proveedor->estado_padron === 'Vencido' ? 'text-red-800' : 'text-yellow-800') }}">
                                    {{ $proveedor->estado_padron }}
                                </p>
                            </div>
                            
                            <div class="text-center">
                                <div class="w-10 h-10 {{ $diasRestantes !== null && $diasRestantes <= 7 ? 'bg-red-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5 {{ $diasRestantes !== null && $diasRestantes <= 7 ? 'text-red-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Vencimiento</p>
                                @if($proveedor->fecha_vencimiento_padron)
                                    <p class="text-sm font-bold {{ $diasRestantes !== null && $diasRestantes <= 7 ? 'text-red-800' : 'text-gray-800' }}">
                                        {{ \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') }}
                                    </p>
                                    @if($diasRestantes !== null)
                                        <p class="text-xs {{ $diasRestantes <= 7 ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ $diasRestantes > 0 ? $diasRestantes . ' días restantes' : ($diasRestantes == 0 ? 'Vence hoy' : 'Vencido hace ' . abs($diasRestantes) . ' días') }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-sm font-bold text-gray-800">No definida</p>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Alertas y acciones según el estado --}}
                        @if($requiereActualizacion && $requiereRenovacion)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                    <p class="text-sm text-red-700 font-medium">
                                        <strong>¡Acción requerida!</strong> Su registro vence en {{ $diasRestantes <= 0 ? 'menos de 24 horas' : $diasRestantes . ' días' }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <a href="{{ route('tramites.create', ['tipo' => 'actualizacion']) }}" 
                                       class="inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Actualización
                                    </a>
                                    <a href="{{ route('tramites.create', ['tipo' => 'renovacion']) }}" 
                                       class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Renovación
                                    </a>
                                </div>
                            </div>
                        @elseif($requiereInscripcion)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <p class="text-sm text-yellow-700 font-medium">
                                        @if($tramiteRechazado)
                                            <strong>Trámite rechazado:</strong> Debe realizar una nueva inscripción
                                        @elseif($proveedor->estado_padron === 'Vencido')
                                            <strong>Registro vencido:</strong> Debe realizar una nueva inscripción
                                        @else
                                            <strong>Inscripción requerida:</strong> Complete su registro como proveedor
                                        @endif
                                    </p>
                                </div>
                                @if($tramiteRechazado)
                                    <div class="bg-white border border-yellow-200 rounded-lg p-3 mt-3">
                                        <p class="text-xs text-yellow-700 mb-2"><strong>Motivo del rechazo:</strong></p>
                                        <p class="text-xs text-yellow-600">{{ $tramiteRechazado->observaciones ?? 'No se especificaron observaciones' }}</p>
                                    </div>
                                @endif
                                <div class="mt-3 text-center">
                                    <a href="{{ route('tramites.create', ['tipo' => 'inscripcion']) }}" 
                                       class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Nueva Inscripción
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <p class="text-sm text-green-700 font-medium">
                                        <strong>Estado óptimo:</strong> Su registro está activo y en orden
                                    </p>
                                </div>
                                @if($proveedor->estado_padron === 'Activo' && $diasRestantes > 7)
                                    <div class="mt-2">
                                        <p class="text-xs text-green-600">
                                            Las opciones de renovación y actualización estarán disponibles 7 días antes del vencimiento
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Usuario sin proveedor asociado --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                    <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] p-6 text-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white mb-2">Bienvenido al Sistema</h1>
                        <p class="text-white/90">Complete su registro como proveedor</p>
                    </div>
                    
                    <div class="p-8 text-center">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <p class="text-sm text-blue-700 font-medium">
                                    <strong>Inscripción requerida:</strong> Debe registrarse como proveedor para acceder al sistema
                                </p>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">¿Qué puede hacer?</h3>
                            <ul class="text-sm text-gray-600 space-y-2 text-left max-w-md mx-auto">
                                <li class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Registrarse como proveedor del gobierno</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Obtener su número PV (Proveedor Verificado)</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Participar en licitaciones gubernamentales</span>
                                </li>
                            </ul>
                        </div>
                        
                        <a href="{{ route('tramites.create', ['tipo' => 'inscripcion']) }}" 
                           class="inline-flex items-center px-6 py-3 bg-[#9D2449] hover:bg-[#B91C1C] text-white font-semibold rounded-lg transition-colors duration-200 mb-4">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Iniciar Inscripción
                        </a>
                        
                        <div class="text-center">
                            <a href="{{ route('tramites.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endif

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