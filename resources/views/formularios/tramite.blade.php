@extends('layouts.app')

@section('title', $titulo ?? 'Formulario de Trámite')

@section('content')
<div class="min-h-screen bg-gradient-to-br">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        @if($tipo_tramite === 'inscripcion')
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        @elseif($tipo_tramite === 'renovacion')
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-lg sm:text-xl font-bold text-slate-800 truncate">{{ $titulo }}</h1>
                        <p class="text-xs sm:text-sm text-slate-600 line-clamp-2">{{ $descripcion }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between sm:justify-end sm:text-right">
                    <span class="inline-block px-3 py-1 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white text-xs sm:text-sm font-medium rounded-full shadow-sm">
                        {{ ucfirst($tipo_tramite) }}
                    </span>
                    @if($proveedor)
                        <p class="text-xs text-slate-500 mt-1 ml-3 sm:ml-0 truncate max-w-32 sm:max-w-none">{{ $proveedor->razon_social }}</p>
                    @endif
                </div>
            </div>
        </div>

        <form id="formulario-tramite" method="POST" action="{{ route('formularios.store', $tipo_tramite) }}" enctype="multipart/form-data">
            @csrf
            
            @php
                $tipoPersona = $proveedor->tipo_persona ?? 'Física';
                $totalSteps = $tipoPersona === 'Moral' ? 6 : 4; 
                
                $stepNames = [
                    1 => 'Datos Generales',
                    2 => 'Domicilio',
                    3 => $tipoPersona === 'Moral' ? 'Constitutivos' : 'Documentos',
                    4 => $tipoPersona === 'Moral' ? 'Apoderado' : 'Confirmación',
                    5 => $tipoPersona === 'Moral' ? 'Accionistas' : null,
                    6 => $tipoPersona === 'Moral' ? 'Documentos' : null,
                ];
            @endphp
                        <!-- Progress indicator elegante y responsive -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="hidden sm:block">
                    <div class="flex items-center justify-center text-sm">
                        <div class="flex items-center space-x-6 overflow-x-auto pb-2">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex items-center text-slate-400 flex-shrink-0" data-step-indicator="{{ $i }}">
                                    <div class="flex items-center justify-center w-8 h-8 border-2 border-slate-300 rounded-full text-xs transition-all duration-300">{{ $i }}</div>
                                    @if($stepNames[$i])
                                        <span class="ml-2 font-medium whitespace-nowrap">{{ $stepNames[$i] }}</span>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                
                <div class="block sm:hidden">
                    <div class="flex items-center justify-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white text-xs font-bold" id="mobile-current-step">
                                1
                            </div>
                            <span class="text-sm font-medium text-slate-600" id="mobile-step-text">de {{ $totalSteps }}</span>
                        </div>
                        <div class="text-xs text-slate-500" id="mobile-step-name">{{ $stepNames[1] }}</div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <div class="bg-slate-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] h-2 rounded-full transition-all duration-500 ease-out" 
                             style="width: {{ (1 / $totalSteps) * 100 }}%" id="progress-bar"></div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 sm:space-y-6">
                
                <div class="step-section" data-step="1">
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                            <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="truncate">Datos Generales</span>
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6 space-y-6">
                            
                            <div>
                                <h4 class="text-sm font-medium text-slate-700 mb-4 pb-2 border-b border-slate-100">Información Básica</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 mb-2">Razón Social *</label>
                                        <input type="text" name="razon_social" required 
                                               value="{{ old('razon_social', $datosSat['razon_social'] ?? $proveedor->razon_social ?? '') }}"
                                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm text-slate-600 mb-2">RFC *</label>
                                        <input type="text" name="rfc" required readonly
                                               value="{{ old('rfc', $datosSat['rfc'] ?? $proveedor->rfc ?? '') }}"
                                               class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed">
                                        <p class="text-xs text-slate-500 mt-1">El RFC no puede modificarse</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm text-slate-600 mb-2">Tipo de Persona *</label>
                                        <input type="hidden" name="tipo_persona" value="{{ $tipoPersona }}">
                                        <div class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-600 flex items-center">
                                            <span>{{ $tipoPersona === 'Física' ? 'Persona Física' : 'Persona Moral' }}</span>
                                            <svg class="w-4 h-4 ml-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1">Determinado automáticamente por el RFC</p>
                                    </div>

                                    @if($tipoPersona === 'Física')
                                        <div>
                                            <label class="block text-sm text-slate-600 mb-2">CURP</label>
                                            <input type="text" name="curp" maxlength="18"
                                                   value="{{ old('curp', $datosSat['curp'] ?? $proveedor->curp ?? '') }}"
                                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                        </div>
                                    @endif

                                    <div class="{{ $tipoPersona === 'Física' ? '' : 'md:col-span-2' }}">
                                        <label class="block text-sm text-slate-600 mb-2">Página Web</label>
                                        <input type="url" name="pagina_web" 
                                               value="{{ old('pagina_web', $proveedor->pagina_web ?? '') }}"
                                               placeholder="https://www.ejemplo.com"
                                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-slate-700 mb-4 pb-2 border-b border-slate-100">Actividades Económicas</h4>
                                <div class="space-y-4">
                                    <div class="relative">
                                        <label class="block text-sm text-slate-600 mb-2">Buscar Actividad</label>
                                        <div class="relative">
                                            <input type="text" id="buscar-actividad" 
                                                   placeholder="Escriba para buscar actividades económicas..."
                                                   class="w-full px-4 py-3 pl-10 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div id="actividades-container" class="space-y-2">
                                        <div class="text-sm text-slate-500 text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <p>No hay actividades económicas agregadas</p>
                                            <p class="text-xs">Use el buscador para agregar actividades</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-slate-700 mb-4 pb-2 border-b border-slate-100">Información de Contacto</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 mb-2">Cargo</label>
                                        <input type="text" name="cargo" 
                                               value="{{ old('cargo', $proveedor->cargo ?? '') }}"
                                               placeholder="Ej: Director General, Representante Legal"
                                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm text-slate-600 mb-2">Correo Electrónico</label>
                                        <input type="email" name="email_contacto" 
                                               value="{{ old('email_contacto', $proveedor->email_contacto ?? '') }}"
                                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm text-slate-600 mb-2">Teléfono</label>
                                        <input type="tel" name="telefono" 
                                               value="{{ old('telefono', $proveedor->telefono ?? '') }}"
                                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-[#9D2449]/20 focus:border-[#9D2449] transition-all duration-200 bg-white">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="step-section hidden" data-step="2">
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                            <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="truncate">Domicilio</span>
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <x-formularios.domicilio :tipo="$tipo_tramite" :proveedor="$proveedor" />
                        </div>
                    </div>
                </div>

                @if($tipoPersona === 'Moral')

                    <div class="step-section hidden" data-step="3">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <span class="truncate">Datos Constitutivos</span>
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <x-formularios.constitucion :tipo="$tipo_tramite" :proveedor="$proveedor" />
                            </div>
                        </div>
                    </div>

                    <div class="step-section hidden" data-step="4">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                        </svg>
                                    </div>
                                    <span class="truncate">Apoderado Legal</span>
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <x-formularios.apoderado :tipo="$tipo_tramite" :proveedor="$proveedor" />
                            </div>
                        </div>
                    </div>

                    <div class="step-section hidden" data-step="5">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="truncate">Accionistas</span>
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <x-formularios.accionistas :tipo="$tipo_tramite" :proveedor="$proveedor" />
                            </div>
                        </div>
                    </div>

                    <div class="step-section hidden" data-step="6">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <span class="truncate">Documentos</span>
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <x-formularios.documentos :tipo="$tipo_tramite" :proveedor="$proveedor" />
                            </div>
                        </div>
                    </div>
                @else
                    <div class="step-section hidden" data-step="3">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-rose-500 to-rose-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <span class="truncate">Documentos</span>
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <x-formularios.documentos :tipo="$tipo_tramite" :proveedor="$proveedor" />
                            </div>
                        </div>
                    </div>

                    <div class="step-section hidden" data-step="4">
                        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
                            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200/50">
                                <h3 class="text-base sm:text-lg font-semibold text-slate-800 flex items-center">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="truncate">Confirmación</span>
                                </h3>
                            </div>
                            <div class="p-4 sm:p-6">
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-amber-800">Importante</h3>
                                            <p class="text-amber-700 text-sm mt-1">
                                                @if($tipo_tramite === 'inscripcion')
                                                    Verifique que todos los datos sean correctos. Una vez enviado, el proceso de inscripción será revisado por nuestro equipo.
                                                @elseif($tipo_tramite === 'renovacion')
                                                    La renovación mantendrá su registro activo. Asegúrese de que toda la información esté actualizada.
                                                @else
                                                    Los cambios realizados pueden requerir documentación de soporte y revisión administrativa.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="flex items-start space-x-3">
                                        <input type="checkbox" name="confirma_datos" required 
                                               class="mt-1 h-4 w-4 text-[#9D2449] focus:ring-[#9D2449] border-slate-300 rounded">
                                        <span class="text-sm text-slate-700">
                                            Confirmo que los datos proporcionados son correctos y veraces. Entiendo que proporcionar información falsa puede resultar en la cancelación de mi registro.
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <div class="mt-6 sm:mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                    <button type="button" id="btn-anterior" class="order-2 sm:order-1 w-full sm:w-auto px-4 sm:px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center" disabled>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="text-sm sm:text-base">Anterior</span>
                    </button>
                    
                    <div class="order-1 sm:order-2 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <button type="button" id="btn-guardar-borrador" class="w-full sm:w-auto px-4 sm:px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 transition-all duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Guardar Borrador</span>
                        </button>
                        
                        <button type="button" id="btn-siguiente" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-xl hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <span class="text-sm sm:text-base">Siguiente</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                        
                        <button type="submit" id="btn-enviar" class="hidden w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Enviar Trámite</span>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = {{ $totalSteps }};
    const stepNames = @json($stepNames);
    
    const btnAnterior = document.getElementById('btn-anterior');
    const btnSiguiente = document.getElementById('btn-siguiente');
    const btnEnviar = document.getElementById('btn-enviar');
    const progressBar = document.getElementById('progress-bar');
    const mobileCurrentStep = document.getElementById('mobile-current-step');
    const mobileStepText = document.getElementById('mobile-step-text');
    const mobileStepName = document.getElementById('mobile-step-name');
    
    const buscarActividad = document.getElementById('buscar-actividad');
    const actividadesContainer = document.getElementById('actividades-container');
    
    if (buscarActividad) {
        buscarActividad.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length > 2) {
                console.log('Buscando actividades para:', searchTerm);
            }
        });
    }
    
    function updateStep() {
        document.querySelectorAll('.step-section').forEach(section => {
            section.classList.add('hidden');
        });
        
        const currentSection = document.querySelector(`[data-step="${currentStep}"]`);
        if (currentSection) {
            currentSection.classList.remove('hidden');
            currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        document.querySelectorAll('[data-step-indicator]').forEach((indicator, index) => {
            const stepNum = index + 1;
            const circle = indicator.querySelector('div');
            const text = indicator.querySelector('span');
            
            if (stepNum <= currentStep) {
                circle.className = 'flex items-center justify-center w-8 h-8 border-2 border-[#9D2449] rounded-full bg-[#9D2449] text-white text-xs font-bold shadow-sm transition-all duration-300';
                if (text) text.className = 'ml-2 font-medium text-[#9D2449] whitespace-nowrap';
            } else {
                circle.className = 'flex items-center justify-center w-8 h-8 border-2 border-slate-300 rounded-full text-xs transition-all duration-300';
                if (text) text.className = 'ml-2 font-medium text-slate-400 whitespace-nowrap';
            }
        });
        
        if (mobileCurrentStep) mobileCurrentStep.textContent = currentStep;
        if (mobileStepText) mobileStepText.textContent = `de ${totalSteps}`;
        if (mobileStepName && stepNames[currentStep]) {
            mobileStepName.textContent = stepNames[currentStep];
        }
        
        btnAnterior.disabled = currentStep === 1;
        
        if (currentStep === totalSteps) {
            btnSiguiente.classList.add('hidden');
            btnEnviar.classList.remove('hidden');
        } else {
            btnSiguiente.classList.remove('hidden');
            btnEnviar.classList.add('hidden');
        }
        
        const progress = (currentStep / totalSteps) * 100;
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
    }
    
    btnSiguiente.addEventListener('click', function() {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStep();
        }
    });
    
    btnAnterior.addEventListener('click', function() {
        if (currentStep > 1) {
            currentStep--;
            updateStep();
        }
    });
    
    updateStep();
});
</script>
@endpush
@endsection 