@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/revision-panels.css') }}">
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Container principal con responsive design -->
        <div class="w-full mx-auto px-2 py-2 xs:px-3 xs:py-3 sm:px-4 sm:py-4 md:px-6 md:py-6 lg:px-8 lg:py-8 xl:max-w-7xl xl:px-10 xl:py-10">
            
            <!-- Header principal -->
            <div class="bg-white rounded-lg xs:rounded-xl sm:rounded-2xl shadow-lg border border-gray-200/70 overflow-hidden mb-4 xs:mb-6 sm:mb-8">
                <div class="p-3 xs:p-4 sm:p-6 lg:p-8 border-b border-gray-200/70">
                    <div class="flex flex-col xs:flex-row xs:items-center xs:justify-between gap-3 xs:gap-4">
                        <!-- Título y descripción -->
                        <div class="flex items-center space-x-2 xs:space-x-3 sm:space-x-4">
                            <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-lg xs:rounded-xl p-2 xs:p-3 shadow-md flex-shrink-0">
                                <svg class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h1 class="text-lg xs:text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 leading-tight">Revisión de Datos del Trámite</h1>
                                <p class="text-xs xs:text-sm text-gray-500 mt-1">Revisa y valida la información del trámite.</p>
                            </div>
                        </div>
                        
                        <!-- Estado y botón volver -->
                        <div class="flex flex-col xs:flex-row items-stretch xs:items-center space-y-2 xs:space-y-0 xs:space-x-3">
                            <span class="inline-flex items-center justify-center px-2 xs:px-3 py-1 xs:py-1.5 rounded-full text-xs xs:text-sm font-medium shadow-sm border
                                {{ $tramite->estado === 'Pendiente'
                                    ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                    : ($tramite->estado === 'En_Revision'
                                        ? 'bg-blue-100 text-blue-800 border-blue-200'
                                        : ($tramite->estado === 'Aprobado'
                                            ? 'bg-green-100 text-green-800 border-green-200'
                                            : 'bg-red-100 text-red-800 border-red-200')) }}">
                                <span class="hidden xs:inline">{{ str_replace('_', ' ', $tramite->estado) }}</span>
                                <span class="xs:hidden">{{ str_replace('_', ' ', $tramite->estado) }}</span>
                            </span>
                            <a href="{{ route('revision.index') }}"
                                class="inline-flex items-center justify-center px-3 xs:px-4 py-2 text-xs xs:text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                                <svg class="w-3 h-3 xs:w-4 xs:h-4 mr-1 xs:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                </svg>
                                <span class="hidden xs:inline">Volver</span>
                                <span class="xs:hidden">←</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal con espaciado responsivo -->
            <div class="space-y-3 xs:space-y-4 sm:space-y-6 lg:space-y-8">
                
                <!-- Sección Datos Generales -->
                <div class="bg-white rounded-lg xs:rounded-xl sm:rounded-2xl shadow-md border border-gray-200 p-3 xs:p-4 sm:p-6 lg:p-8">
                    <!-- Header de sección -->
                    <div class="flex items-center space-x-2 xs:space-x-3 sm:space-x-4 mb-3 xs:mb-4 sm:mb-6">
                        <div class="w-5 h-5 xs:w-6 xs:h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10 bg-black rounded-lg xs:rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-2.5 h-2.5 xs:w-3 xs:h-3 sm:w-4 sm:h-4 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-sm xs:text-base sm:text-lg lg:text-xl font-semibold text-gray-900">Datos Generales</h3>
                    </div>
                    <div id="datosGeneralesGrid">
                        <div id="datosGeneralesMain" class="transition-all duration-300">
                            @include('revision.partials.datos-generales', [
                                'tramite' => $tramite,
                                'proveedor' => $tramite->proveedor,
                                'editable' => true,
                            ])
                        </div>
                    </div>
                    <!-- Panel de revisión -->
                    <div class="mt-3 xs:mt-4 sm:mt-6 pt-3 xs:pt-4 sm:pt-6 border-t border-gray-100">
                        <div class="bg-gray-50 rounded-lg xs:rounded-xl border border-gray-200 shadow-sm">
                            <!-- Header del panel de revisión -->
                            <div class="flex items-center justify-between px-2 xs:px-3 sm:px-4 py-2 xs:py-2.5 bg-gray-50 border-b border-gray-100 rounded-t-lg xs:rounded-t-xl">
                                <div class="flex items-center space-x-1 xs:space-x-2">
                                    <div class="w-4 h-4 xs:w-5 xs:h-5 sm:w-6 sm:h-6 bg-slate-600 rounded-md flex items-center justify-center flex-shrink-0">
                                        <svg class="w-2.5 h-2.5 xs:w-3 xs:h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-xs xs:text-sm font-medium text-gray-700">Revisión</span>
                                </div>
                                <span id="estado_visual_datos_generales" class="inline-flex items-center px-2 xs:px-3 py-0.5 xs:py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <div class="w-1.5 h-1.5 xs:w-2 xs:h-2 bg-amber-400 rounded-full mr-1"></div>
                                    <span class="hidden xs:inline">Pendiente</span>
                                    <span class="xs:hidden">Pend.</span>
                                </span>
                            </div>
                            <div id="comentario_box_datos_generales" class="px-2 py-2 bg-blue-50 border-l-4 border-blue-400" style="display:none;">
                                <div class="flex items-start space-x-1 sm:space-x-2">
                                    <div id="icono_comentario_datos_generales" class="flex-shrink-0 mt-0.5">
                                        <svg class="w-3 h-3 text-blue-500 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-blue-800 mb-1">Observación</p>
                                        <p id="comentario_texto_datos_generales" class="text-xs text-blue-700 break-words"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Contenido del panel de revisión -->
                            <div class="p-2 xs:p-3 sm:p-4 lg:p-6 space-y-2 xs:space-y-3">
                                <!-- Botones de acción -->
                                <div class="flex flex-col xs:flex-row items-stretch xs:items-center justify-center space-y-2 xs:space-y-0 xs:space-x-2">
                                    <button type="button" id="btn_aprobar_datos_generales" 
                                        class="inline-flex items-center justify-center px-3 xs:px-4 py-2 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs xs:text-sm hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors w-full xs:w-auto">
                                        <svg class="w-3 h-3 xs:w-4 xs:h-4 mr-1 xs:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="hidden xs:inline">Aprobar</span>
                                        <span class="xs:hidden">✓</span>
                                    </button>
                                    <button type="button" id="btn_rechazar_datos_generales" 
                                        class="inline-flex items-center justify-center px-3 xs:px-4 py-2 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs xs:text-sm hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors w-full xs:w-auto">
                                        <svg class="w-3 h-3 xs:w-4 xs:h-4 mr-1 xs:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="hidden xs:inline">Rechazar</span>
                                        <span class="xs:hidden">✗</span>
                                    </button>
                                    <input type="hidden" id="aprobado_datos_generales" value="" data-seccion="datos_generales">
                                </div>
                                
                                <!-- Área de comentarios -->
                                <div class="space-y-1 xs:space-y-2">
                                    <label for="comentario_datos_generales" class="block text-xs xs:text-sm font-medium text-gray-600">Observaciones</label>
                                    <div class="relative">
                                        <textarea id="comentario_datos_generales" data-seccion="datos_generales" rows="3" 
                                            class="block w-full px-3 py-2 pr-12 xs:pr-16 sm:pr-20 border border-gray-300 rounded-md text-xs xs:text-sm placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none" 
                                            placeholder="Escriba sus observaciones..."></textarea>
                                        <button type="button" 
                                            class="absolute bottom-2 right-2 inline-flex items-center px-2 xs:px-3 py-1 bg-[#9D2449] text-white rounded text-xs font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors" 
                                            onclick="guardarComentarioSeccion('datos_generales')">
                                            <svg class="w-3 h-3 xs:w-4 xs:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="hidden xs:inline">Guardar</span>
                                            <span class="xs:hidden">OK</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Estado del comentario -->
                                <div id="estado_comentario_datos_generales" class="text-xs xs:text-sm text-center font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Separador responsivo -->
                <div class="flex items-center justify-center py-3 xs:py-4 sm:py-6 lg:py-8">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-3 xs:px-4 sm:px-6 lg:px-8">
                        <div class="w-1.5 h-1.5 xs:w-2 xs:h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <!-- Sección Domicilio -->
                <div class="bg-white rounded-lg xs:rounded-xl sm:rounded-2xl shadow-md border border-gray-200 p-3 xs:p-4 sm:p-6 lg:p-8">
                    <!-- Header de sección -->
                    <div class="flex items-center space-x-2 xs:space-x-3 sm:space-x-4 mb-3 xs:mb-4 sm:mb-6">
                        <div class="w-6 h-6 xs:w-8 xs:h-8 sm:w-10 sm:h-10 bg-black rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 xs:w-4 xs:h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm xs:text-base sm:text-lg lg:text-xl font-semibold text-gray-900">Domicilio</h3>
                    </div>
                    <div id="domicilioGrid">
                        <div id="domicilioMain" class="transition-all duration-300">
                            @include('revision.partials.domicilio', [
                                'tramite' => $tramite,
                                'direccion' => $tramite->direcciones->first(),
                                'editable' => true,
                            ])
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            
                            <div
                                class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100 rounded-t-lg">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-slate-600 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Revisión</span>
                                </div>
                                <span id="estado_visual_domicilio"
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <div class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></div>
                                    Pendiente
                                </span>
                            </div>

                            
                            <div id="comentario_box_domicilio" class="px-4 py-3 bg-blue-50 border-l-3 border-blue-400"
                                style="display:none;">
                                <div class="flex items-start space-x-2">
                                    <div id="icono_comentario_domicilio" class="flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-blue-800 mb-1">Observación</p>
                                        <p id="comentario_texto_domicilio" class="text-xs text-blue-700"></p>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-3 sm:p-4 space-y-3">
                                
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2">
                                    <button type="button" id="btn_aprobar_domicilio"
                                        class="inline-flex items-center justify-center px-3 py-2 sm:py-1.5 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors"
                                        onclick="setAprobado('domicilio', true)">
                                        <svg class="w-3 h-3 mr-1.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aprobar
                                    </button>
                                    <button type="button" id="btn_rechazar_domicilio"
                                        class="inline-flex items-center justify-center px-3 py-2 sm:py-1.5 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors"
                                        onclick="setAprobado('domicilio', false)">
                                        <svg class="w-3 h-3 mr-1.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rechazar
                                    </button>
                                    <input type="hidden" id="aprobado_domicilio" value="" data-seccion="domicilio">
                                </div>

                                
                                <div class="space-y-2">
                                    <label for="comentario_domicilio" class="block text-xs font-medium text-gray-600">
                                        Observaciones
                                    </label>
                                    <div class="relative">
                                        <textarea id="comentario_domicilio" data-seccion="domicilio" rows="3"
                                            class="block w-full px-3 py-2 pr-16 sm:pr-20 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"
                                            placeholder="Escriba sus observaciones..."></textarea>
                                        <button type="button"
                                            class="absolute bottom-2 right-2 inline-flex items-center px-2 py-1 bg-[#9D2449] text-white rounded text-xs font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors"
                                            onclick="guardarComentarioSeccion('domicilio')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="hidden sm:inline">Guardar</span>
                                            <span class="sm:hidden">OK</span>
                                        </button>
                                    </div>
                                </div>

                                
                                <div id="estado_comentario_domicilio" class="text-xs text-center font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center py-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-6">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-black rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Actividades</h3>
                    </div>
                    <div id="actividadesGrid">
                        <div id="actividadesMain" class="transition-all duration-300">
                            @include('revision.partials.actividades', [
                                'tramite' => $tramite,
                                'actividades' => $tramite->actividades ?? [],
                                'editable' => true,
                            ])
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            
                            <div
                                class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100 rounded-t-lg">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-slate-600 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Revisión</span>
                                </div>
                                <span id="estado_visual_actividades"
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <div class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></div>
                                    Pendiente
                                </span>
                            </div>

                            
                            <div id="comentario_box_actividades" class="px-4 py-3 bg-blue-50 border-l-3 border-blue-400"
                                style="display:none;">
                                <div class="flex items-start space-x-2">
                                    <div id="icono_comentario_actividades" class="flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-blue-800 mb-1">Observación</p>
                                        <p id="comentario_texto_actividades" class="text-xs text-blue-700"></p>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-4 space-y-3">
                                
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" id="btn_aprobar_actividades"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors"
                                        onclick="setAprobado('actividades', true)">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aprobar
                                    </button>
                                    <button type="button" id="btn_rechazar_actividades"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors"
                                        onclick="setAprobado('actividades', false)">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rechazar
                                    </button>
                                    <input type="hidden" id="aprobado_actividades" value=""
                                        data-seccion="actividades">
                                </div>

                                
                                <div class="space-y-2">
                                    <label for="comentario_actividades" class="block text-xs font-medium text-gray-600">
                                        Observaciones
                                    </label>
                                    <div class="relative">
                                        <textarea id="comentario_actividades" data-seccion="actividades" rows="2"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"
                                            placeholder="Escriba sus observaciones..."></textarea>
                                        <button type="button"
                                            class="absolute bottom-2 right-2 inline-flex items-center px-2 py-1 bg-[#9D2449] text-white rounded text-xs font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors"
                                            onclick="guardarComentarioSeccion('actividades')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Guardar
                                        </button>
                                    </div>
                                </div>

                                
                                <div id="estado_comentario_actividades" class="text-xs text-center font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center py-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-6">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-black rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Documentos</h3>
                    </div>
                    <div id="documentosGrid">
                        <div id="documentosMain" class="transition-all duration-300">
                            @include('revision.partials.documentos', [
                                'tramite' => $tramite,
                                'documentos' => $tramite->archivos ?? [],
                                'editable' => true,
                            ])
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            
                            <div
                                class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100 rounded-t-lg">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 bg-slate-600 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Revisión</span>
                                </div>
                                <span id="estado_visual_documentos"
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <div class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></div>
                                    Pendiente
                                </span>
                            </div>

                            
                            <div id="comentario_box_documentos" class="px-4 py-3 bg-blue-50 border-l-3 border-blue-400"
                                style="display:none;">
                                <div class="flex items-start space-x-2">
                                    <div id="icono_comentario_documentos" class="flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-blue-800 mb-1">Observación</p>
                                        <p id="comentario_texto_documentos" class="text-xs text-blue-700"></p>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="p-4 space-y-3">
                                
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" id="btn_aprobar_documentos"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors"
                                        onclick="setAprobado('documentos', true)">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aprobar
                                    </button>
                                    <button type="button" id="btn_rechazar_documentos"
                                        class="inline-flex items-center px-3 py-1.5 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors"
                                        onclick="setAprobado('documentos', false)">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rechazar
                                    </button>
                                    <input type="hidden" id="aprobado_documentos" value=""
                                        data-seccion="documentos">
                                </div>

                                
                                <div class="space-y-2">
                                    <label for="comentario_documentos" class="block text-xs font-medium text-gray-600">
                                        Observaciones
                                    </label>
                                    <div class="relative">
                                        <textarea id="comentario_documentos" data-seccion="documentos" rows="2"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"
                                            placeholder="Escriba sus observaciones..."></textarea>
                                        <button type="button"
                                            class="absolute bottom-2 right-2 inline-flex items-center px-2 py-1 bg-[#9D2449] text-white rounded text-xs font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors"
                                            onclick="guardarComentarioSeccion('documentos')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Guardar
                                        </button>
                                    </div>
                                </div>

                                
                                <div id="estado_comentario_documentos" class="text-xs text-center font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center py-6 sm:py-8">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-[#9D2449] to-transparent opacity-50"></div>
                    <div class="px-6 sm:px-8">
                        <div class="w-3 h-3 bg-[#9D2449] rounded-full shadow-lg"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-[#9D2449] to-transparent opacity-50"></div>
                </div>

                
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white border border-gray-300 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Comentario General</h3>
                    </div>

                    @if (isset($tramite->comentarios_revision) && $tramite->comentarios_revision)
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        {{ $tramite->comentarios_revision }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('revision.comentario-general') }}" method="POST" class="bg-white rounded-lg border border-gray-200 shadow-sm">
                        @csrf
                        <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">
                        <div class="p-4 space-y-3">
                            <div class="space-y-2">
                                <label for="comentario_general" class="block text-sm font-medium text-gray-700">
                                    Comentario General del Trámite
                                </label>
                                <div class="relative">
                                    <textarea id="comentario_general" name="comentario" rows="4"
                                        class="block w-full px-3 py-2 pr-20 border border-gray-300 rounded-md text-sm placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"
                                        placeholder="Escriba un comentario general sobre el trámite...">{{ old('comentario', $tramite->comentarios_revision ?? '') }}</textarea>
                                    <button type="submit"
                                        class="absolute bottom-2 right-2 inline-flex items-center px-3 py-1 bg-[#9D2449] text-white rounded text-sm font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Guardar
                                    </button>
                                </div>
                            </div>
                            @if(session('comentario_success'))
                                <div class="text-sm text-center font-medium text-emerald-600">
                                    <div class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ session('comentario_success') }}
                                    </div>
                                </div>
                            @endif
                            @if(session('comentario_error'))
                                <div class="text-sm text-center font-medium text-red-600">
                                    <div class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ session('comentario_error') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>

                    <x-modal-confirmacion 
                        id="modal-confirmacion"
                        title="Confirmar acción"
                        message="¿Está seguro que desea realizar esta acción?"
                        confirm-text="Confirmar"
                        cancel-text="Cancelar"
                    />

                    <x-modal-exito 
                        id="modal-exito"
                        title="¡Trámite procesado!"
                        message="El trámite ha sido procesado exitosamente."
                        accept-text="Ir al listado"
                        :redirect-url="route('revision.index')"
                    />

                    <x-modal-exito 
                        id="modal-cita-agendada"
                        title="¡Cita Agendada!"
                        message="Se ha agendado automáticamente una cita para cotejo presencial."
                        accept-text="Ver Cita"
                        :redirect-url="route('citas.index')"
                    />

                    <x-modal-error 
                        id="modal-error"
                        title="Error"
                        message="Ha ocurrido un error al procesar su solicitud."
                        button-text="OK"
                    />

                    <x-modal-eliminar 
                        id="modal-eliminar"
                        title="Confirmar eliminación"
                        message="¿Está seguro que desea eliminar este elemento? Esta acción no se puede deshacer."
                        confirm-text="Eliminar"
                        cancel-text="Cancelar"
                    />

                    
                    <div class="border-t border-gray-200 pt-4 sm:pt-6 mt-4 sm:mt-6">
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-2">Acciones de Revisión</h4>
                        <p class="text-xs sm:text-sm text-gray-500 mb-4">Seleccione la acción a realizar con este trámite</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            
                            <div class="w-full">
                                <form id="form_por_cotejar" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="nuevo_estado" value="Por_Cotejar">
                                    <input type="hidden" name="observaciones" id="observaciones_por_cotejar">
                                    <button type="button" onclick="showConfirmModal('Aceptar y Enviar a Cotejo Presencial', '¿Está seguro que desea aceptar y enviar a cotejo presencial este trámite?', 'form_por_cotejar')"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-blue-600 text-white rounded-lg font-semibold tracking-wide shadow-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="hidden sm:inline">Aceptar y Enviar a Cotejo Presencial</span>
                                        <span class="sm:hidden">Cotejar</span>
                                    </button>
                                </form>
                                <p class="mt-2 text-xs text-gray-500 text-center leading-relaxed">
                                    Acepta el trámite y lo envía para validación presencial de documentos
                                </p>
                            </div>
                            
                            <div class="w-full">
                                <form id="form_rechazar" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="nuevo_estado" value="Rechazado">
                                    <input type="hidden" name="observaciones" id="observaciones_rechazar">
                                    <button type="button" onclick="showConfirmModal('Rechazar Trámite', '¿Está seguro que desea rechazar este trámite?', 'form_rechazar')"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-red-700 text-white rounded-lg font-semibold tracking-wide shadow-md text-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-700 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="hidden sm:inline">Rechazar Trámite</span>
                                        <span class="sm:hidden">Rechazar</span>
                                    </button>
                                </form>
                                <p class="mt-2 text-xs text-gray-500 text-center leading-relaxed">
                                    Rechaza definitivamente el trámite por incumplimiento de requisitos
                                </p>
                            </div>
                            
                            <div class="w-full">
                                <form id="form_para_correccion" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="nuevo_estado" value="Para_Correccion">
                                    <input type="hidden" name="observaciones" id="observaciones_para_correccion">
                                    <button type="button" onclick="showConfirmModal('Para Corrección', '¿Está seguro que desea solicitar correcciones para este trámite?', 'form_para_correccion')"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-yellow-500 text-gray-900 rounded-lg font-semibold tracking-wide shadow-md text-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="hidden lg:inline">Para Corrección</span>
                                        <span class="lg:hidden">Corregir</span>
                                    </button>
                                </form>
                                <p class="mt-2 text-xs text-gray-500 text-center leading-relaxed">
                                    Solicita correcciones al solicitante para completar el trámite
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/revision/revision-digital.js') }}"></script>
    <script>
        window.tramiteId = {{ $tramite->id }};
        window.csrfToken = "{{ csrf_token() }}";
        window.revisionSeccionComentarioRoute = "{{ route('revision.seccion.comentario') }}";
    </script>
@endpush
