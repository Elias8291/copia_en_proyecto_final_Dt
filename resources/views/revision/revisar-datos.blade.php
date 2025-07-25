@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/revision-panels.css') }}">
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="w-full max-w-full mx-auto px-3 py-2 sm:max-w-7xl sm:px-4 sm:py-4 lg:px-6 lg:py-6">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                <!-- Encabezado -->
                <div class="p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Revisión de Datos del Trámite</h1>
                                <p class="text-sm text-gray-500">Revisa y valida la información del trámite.</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium shadow-sm border
                                {{ $tramite->estado === 'Pendiente'
                                    ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                    : ($tramite->estado === 'En_Revision'
                                        ? 'bg-blue-100 text-blue-800 border-blue-200'
                                        : ($tramite->estado === 'Aprobado'
                                            ? 'bg-green-100 text-green-800 border-green-200'
                                            : 'bg-red-100 text-red-800 border-red-200')) }}">
                                {{ str_replace('_', ' ', $tramite->estado) }}
                            </span>
                            <a href="{{ route('revision.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-3 sm:space-y-4 lg:space-y-6">
                <!-- Datos Generales -->
                <div class="bg-white rounded-lg shadow border border-gray-200 p-3 sm:rounded-xl sm:p-4 lg:p-6">
                    <div class="flex items-center space-x-2 mb-3 sm:space-x-3 sm:mb-4 lg:mb-6">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 sm:w-8 sm:h-8 lg:w-10 lg:h-10">
                            <svg class="w-3 h-3 text-white sm:w-4 sm:h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 sm:text-base lg:text-lg">Datos Generales</h3>
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
                    <!-- Panel de Revisión Compacto -->
                    <div class="mt-3 pt-3 border-t border-gray-100 sm:mt-4 sm:pt-4 lg:mt-6">
                        <div class="bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                            <!-- Header Compacto -->
                            <div class="flex items-center justify-between px-2 py-2 bg-gray-50 border-b border-gray-100 rounded-t-lg sm:px-3 sm:py-2.5">
                                <div class="flex items-center space-x-1 sm:space-x-2">
                                    <div class="w-4 h-4 bg-slate-600 rounded-md flex items-center justify-center flex-shrink-0 sm:w-5 sm:h-5 lg:w-6 lg:h-6">
                                        <svg class="w-2.5 h-2.5 text-white sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-700 sm:text-sm">Revisión</span>
                                </div>
                                <span id="estado_visual_datos_generales" class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <div class="w-1.5 h-1.5 bg-amber-400 rounded-full mr-1"></div>
                                    <span class="sm:inline hidden">Pendiente</span>
                                    <span class="sm:hidden">Pend.</span>
                                </span>
                            </div>
                            <!-- Comentario Existente Compacto -->
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
                            <!-- Controles Compactos -->
                            <div class="p-2 space-y-2 sm:p-4 sm:space-y-3">
                                <!-- Botones de Decisión Compactos -->
                                <div class="flex flex-col items-stretch space-y-2 sm:flex-row sm:items-center sm:justify-center sm:space-y-0 sm:space-x-2">
                                    <button type="button" id="btn_aprobar_datos_generales" class="inline-flex items-center justify-center px-3 py-2 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors w-full sm:w-auto sm:py-1.5">
                                        <svg class="w-3 h-3 mr-1.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aprobar
                                    </button>
                                    <button type="button" id="btn_rechazar_datos_generales" class="inline-flex items-center justify-center px-3 py-2 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors w-full sm:w-auto sm:py-1.5">
                                        <svg class="w-3 h-3 mr-1.5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rechazar
                                    </button>
                                    <input type="hidden" id="aprobado_datos_generales" value="" data-seccion="datos_generales">
                                </div>
                                <!-- Campo de Comentario Compacto -->
                                <div class="space-y-1 sm:space-y-2">
                                    <label for="comentario_datos_generales" class="block text-xs font-medium text-gray-600">Observaciones</label>
                                    <div class="relative">
                                        <textarea id="comentario_datos_generales" data-seccion="datos_generales" rows="3" class="block w-full px-3 py-2 pr-14 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none sm:pr-20" placeholder="Escriba sus observaciones..."></textarea>
                                        <button type="button" class="absolute bottom-2 right-2 inline-flex items-center px-2 py-1 bg-[#9D2449] text-white rounded text-xs font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors" onclick="guardarComentarioSeccion('datos_generales')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="hidden sm:inline">Guardar</span>
                                            <span class="sm:hidden">OK</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Mensaje de Estado Compacto -->
                                <div id="estado_comentario_datos_generales" class="text-xs text-center font-medium"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center py-4 sm:py-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-4 sm:px-6">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <!-- Domicilio -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Domicilio</h3>
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
                    <!-- Panel de Revisión Compacto -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <!-- Header Compacto -->
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

                            <!-- Comentario Existente Compacto -->
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

                            <!-- Controles Compactos -->
                            <div class="p-3 sm:p-4 space-y-3">
                                <!-- Botones de Decisión Compactos -->
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

                                <!-- Campo de Comentario Compacto -->
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

                                <!-- Mensaje de Estado Compacto -->
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

                <!-- Actividades -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
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
                    <!-- Panel de Revisión Compacto -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <!-- Header Compacto -->
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

                            <!-- Comentario Existente Compacto -->
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

                            <!-- Controles Compactos -->
                            <div class="p-4 space-y-3">
                                <!-- Botones de Decisión Compactos -->
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

                                <!-- Campo de Comentario Compacto -->
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

                                <!-- Mensaje de Estado Compacto -->
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

                <!-- Documentos -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
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
                    <!-- Panel de Revisión Compacto -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                            <!-- Header Compacto -->
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

                            <!-- Comentario Existente Compacto -->
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

                            <!-- Controles Compactos -->
                            <div class="p-4 space-y-3">
                                <!-- Botones de Decisión Compactos -->
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

                                <!-- Campo de Comentario Compacto -->
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

                                <!-- Mensaje de Estado Compacto -->
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

                <!-- Comentario General del Trámite -->
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Comentario General</h3>
                    </div>

                    <!-- Comentarios existentes -->
                    @if (isset($tramite->comentarios_revision) && $tramite->comentarios_revision)
                        <!-- Sección de comentarios eliminada -->
                    @endif

                    <!-- Formulario para nuevo comentario -->
                    <form id="generalCommentForm" onsubmit="submitGeneralComment(event)" class="flex-1">
                        <label for="general_comment" class="block text-sm sm:text-base font-semibold text-gray-900 mb-2">
                            Comentario General del Trámite
                        </label>
                        <textarea id="general_comment" name="comentario" rows="4" 
                            class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none text-gray-800 text-sm sm:text-base"
                            placeholder="Escribe aquí tus observaciones generales sobre este trámite..." required></textarea>
                        <!-- Botón de Guardar Comentario eliminado -->
                    </form>

                    <!-- Acciones de Revisión -->
                    <div class="border-t border-gray-200 pt-4 sm:pt-6 mt-4 sm:mt-6">
                        <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-2">Acciones de Revisión</h4>
                        <p class="text-xs sm:text-sm text-gray-500 mb-4">Seleccione la acción a realizar con este trámite</p>

                        <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3 lg:space-x-4">
                            <!-- Aprobar -->
                            <button type="button" onclick="submitTramiteAction('aprobar')"
                                class="inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-green-700 text-white rounded-lg font-semibold tracking-wide shadow-md text-sm hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-700 focus:ring-offset-2 transition-all duration-200 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4 L19 7" />
                                </svg>
                                <span class="hidden sm:inline">Aprobar Trámite</span>
                                <span class="sm:hidden">Aprobar</span>
                            </button>
                            <!-- Rechazar -->
                            <button type="button" onclick="submitTramiteAction('rechazar')"
                                class="inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-red-700 text-white rounded-lg font-semibold tracking-wide shadow-md text-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-700 focus:ring-offset-2 transition-all duration-200 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="hidden sm:inline">Rechazar Trámite</span>
                                <span class="sm:hidden">Rechazar</span>
                            </button>
                            <!-- Solicitar Correcciones -->
                            <button type="button" onclick="submitTramiteAction('corregir')"
                                class="inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-yellow-500 text-gray-900 rounded-lg font-semibold tracking-wide shadow-md text-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="hidden lg:inline">Solicitar Correcciones</span>
                                <span class="lg:hidden">Correcciones</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/revision/revisar-datos.js') }}"></script>
    <script>
        window.tramiteId = {{ $tramite->id }};
        window.csrfToken = "{{ csrf_token() }}";
        window.revisionSeccionComentarioRoute = "{{ route('revision.seccion.comentario') }}";
    </script>
@endpush
