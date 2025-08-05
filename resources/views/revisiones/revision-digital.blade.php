@extends('layouts.app')

@section('title', 'Revisión Digital')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            @php
                                $tipoRevisionLabel = match($tipoRevision) {
                                    'Digital' => 'Revisión Digital',
                                    'Presencial' => 'Revisión Presencial', 
                                    'Domiciliaria' => 'Revisión Domiciliaria',
                                    default => 'Revisión'
                                };
                            @endphp
                            {{ $tipoRevisionLabel }} - Trámite #{{ $tramite->id }}
                        </h1>
                        <p class="text-base text-gray-500 mt-1">{{ strtolower($tipoRevisionLabel) }} de documentos y datos del trámite</p>
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
            <!-- Información del trámite y revisión -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm font-medium text-blue-800">Tipo de Trámite:</span>
                        <p class="text-blue-900">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Estado:</span>
                        <p class="text-blue-900">{{ $tramite->status }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Fecha de Creación:</span>
                        <p class="text-blue-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Tipo de Revisión:</span>
                        <p class="text-blue-900">{{ $tipoRevisionLabel }}</p>
                    </div>
                </div>
            </div>

            @if($revision)
            <!-- Información de la revisión guardada -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-green-800">Revisión Guardada</h3>
                            <p class="text-sm text-green-700">
                                {{ $revision->tipo_revision_label }} - Iniciada el {{ $revision->fecha_inicio->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $revision->estado_label }}
                    </span>
                </div>
            </div>
            @else
            <!-- Revisión temporal (no guardada) -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-blue-800">{{ $tipoRevisionLabel }} en Progreso</h3>
                            <p class="text-sm text-blue-700">La revisión se guardará al finalizar el proceso</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        En Progreso
                    </span>
                </div>
            </div>
            @endif

            <!-- Panel de Historial -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Historial de Trámites</h3>
                            <p class="text-sm text-gray-500">RFC: {{ $tramite->proveedor->rfc }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleHistorial()" 
                            class="flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        <span id="toggle_text_historial">Mostrar Historial</span>
                    </button>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    <div class="bg-gray-50 p-3 rounded-lg text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $estadisticasHistorial['total'] }}</div>
                        <div class="text-xs text-gray-500">Total</div>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $estadisticasHistorial['aprobados'] }}</div>
                        <div class="text-xs text-green-600">Aprobados</div>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $estadisticasHistorial['rechazados'] }}</div>
                        <div class="text-xs text-red-600">Rechazados</div>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $estadisticasHistorial['pendientes'] }}</div>
                        <div class="text-xs text-yellow-600">Pendientes</div>
                    </div>
                </div>

                <!-- Lista de trámites históricos -->
                <div id="contenido_historial" class="hidden">
                    @if($historialTramites->count() > 0)
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($historialTramites as $tramiteHistorico)
                                <div class="border border-gray-200 rounded-lg p-4 {{ $tramiteHistorico['id'] == $tramite->id ? 'bg-blue-50 border-blue-300' : 'bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @switch($tramiteHistorico['status'])
                                                        @case('Aprobado')
                                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            </div>
                                                            @break
                                                        @case('Rechazado')
                                                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </div>
                                                            @break
                                                        @default
                                                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </div>
                                                    @endswitch
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2">
                                                        <h4 class="text-sm font-medium text-gray-900">
                                                            Trámite #{{ $tramiteHistorico['id'] }}
                                                            @if($tramiteHistorico['id'] == $tramite->id)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                                    Actual
                                                                </span>
                                                            @endif
                                                        </h4>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $tramiteHistorico['status'] == 'Aprobado' ? 'bg-green-100 text-green-800' : ($tramiteHistorico['status'] == 'Rechazado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                            {{ $tramiteHistorico['status'] }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-500 truncate">{{ $tramiteHistorico['razon_social'] }}</p>
                                                    <div class="flex items-center text-xs text-gray-400 mt-1">
                                                        <span>{{ $tramiteHistorico['tipo_tramite'] }}</span>
                                                        <span class="mx-2">•</span>
                                                        <span>{{ $tramiteHistorico['created_at']->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($tramiteHistorico['id'] != $tramite->id)
                                            <div class="flex-shrink-0 ml-4">
                                                <a href="{{ route('revisiones.ver-historico', $tramiteHistorico['id']) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                                                                                         <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                     </svg>
                                                    Ver Trámite
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500">No hay trámites anteriores para este RFC</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Datos Generales -->
            <div class="mb-6" data-section="datos_generales">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Datos Generales</h2>
                    <button type="button" onclick="toggleCotejo('datos_generales')" 
                            class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span id="toggle_text_datos_generales">Mostrar Cotejo</span>
                    </button>
                </div>
                <div id="content_datos_generales" class="grid grid-cols-1 gap-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        @include('components.forms.datos-generales', [
                            'editable' => false, 
                            'datosConstancia' => $viewModel
                        ])
                    </div>
                    <div id="cotejo_datos_generales" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                        <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Datos Generales</h3>
                        @include('components.cotejo-selector', [
                            'archivosSubidos' => $archivosSubidos,
                            'seccion' => 'datos_generales'
                        ])
                    </div>
                </div>
                
                <!-- Área de Decisión por Sección -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Decisión - Datos Generales</h4>
                        <span class="text-xs text-gray-500">Sección 1/6</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                        <textarea 
                            placeholder="Agregar observaciones específicas para datos generales..."
                            class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            rows="2"></textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Aprobar Sección</span>
                        </button>
                        
                        <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Rechazar Sección</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actividades Económicas -->
            <div class="mb-6" data-section="actividades">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Actividades Económicas</h2>
                    <button type="button" onclick="toggleCotejo('actividades')" 
                            class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span id="toggle_text_actividades">Mostrar Cotejo</span>
                    </button>
                </div>
                <div id="content_actividades" class="grid grid-cols-1 gap-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        @include('components.forms.actividades-economicas', [
                            'editable' => false,
                            'datos' => $viewModel->getActividades()
                        ])
                    </div>
                    <div id="cotejo_actividades" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                        <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Actividades</h3>
                        @include('components.cotejo-selector', [
                            'archivosSubidos' => $archivosSubidos,
                            'seccion' => 'actividades'
                        ])
                    </div>
                </div>
                
                <!-- Área de Decisión por Sección -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Decisión - Actividades Económicas</h4>
                        <span class="text-xs text-gray-500">Sección 2/6</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                        <textarea 
                            placeholder="Agregar observaciones específicas para actividades económicas..."
                            class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            rows="2"></textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Aprobar Sección</span>
                        </button>
                        
                        <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Rechazar Sección</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Domicilio -->
            <div class="mb-6" data-section="domicilio">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Domicilio</h2>
                    <button type="button" onclick="toggleCotejo('domicilio')" 
                            class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span id="toggle_text_domicilio">Mostrar Cotejo</span>
                    </button>
                </div>
                <div id="content_domicilio" class="grid grid-cols-1 gap-6">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        @include('components.forms.domicilio', [
                            'editable' => false, 
                            'datosConstancia' => $viewModel
                        ])
                    </div>
                    <div id="cotejo_domicilio" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                        <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Domicilio</h3>
                        @include('components.cotejo-selector', [
                            'archivosSubidos' => $archivosSubidos,
                            'seccion' => 'domicilio'
                        ])
                    </div>
                </div>
                
                <!-- Área de Decisión por Sección -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Decisión - Domicilio</h4>
                        <span class="text-xs text-gray-500">Sección 3/6</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                        <textarea 
                            placeholder="Agregar observaciones específicas para domicilio..."
                            class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            rows="2"></textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Aprobar Sección</span>
                        </button>
                        
                        <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Rechazar Sección</span>
                        </button>
                    </div>
                </div>
            </div>

            @if($viewModel->isPersonaMoral())
                <!-- Constitución -->
                <div class="mb-6" data-section="constitucion">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Constitución</h2>
                        <button type="button" onclick="toggleCotejo('constitucion')" 
                                class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span id="toggle_text_constitucion">Mostrar Cotejo</span>
                        </button>
                    </div>
                    <div id="content_constitucion" class="grid grid-cols-1 gap-6">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                            @include('components.forms.constitucion', [
                                'editable' => false,
                                'datos' => $viewModel->getConstitucion()
                            ])
                        </div>
                        <div id="cotejo_constitucion" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                            <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Constitución</h3>
                            @include('components.cotejo-selector', [
                                'archivosSubidos' => $archivosSubidos,
                                'seccion' => 'constitucion'
                            ])
                        </div>
                    </div>
                    
                    <!-- Área de Decisión por Sección -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Decisión - Constitución</h4>
                            <span class="text-xs text-gray-500">Sección 4/6</span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                            <textarea 
                                placeholder="Agregar observaciones específicas para constitución..."
                                class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                rows="2"></textarea>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Aprobar Sección</span>
                            </button>
                            
                            <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Rechazar Sección</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Accionistas -->
                <div class="mb-6" data-section="accionistas">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                        <button type="button" onclick="toggleCotejo('accionistas')" 
                                class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span id="toggle_text_accionistas">Mostrar Cotejo</span>
                        </button>
                    </div>
                    <div id="content_accionistas" class="grid grid-cols-1 gap-6">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                            @include('components.forms.accionistas', [
                                'editable' => false,
                                'datos' => $viewModel->getAccionistas()
                            ])
                        </div>
                        <div id="cotejo_accionistas" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                            <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Accionistas</h3>
                            @include('components.cotejo-selector', [
                                'archivosSubidos' => $archivosSubidos,
                                'seccion' => 'accionistas'
                            ])
                        </div>
                    </div>
                    
                    <!-- Área de Decisión por Sección -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Decisión - Accionistas</h4>
                            <span class="text-xs text-gray-500">Sección 5/6</span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                            <textarea 
                                placeholder="Agregar observaciones específicas para accionistas..."
                                class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                rows="2"></textarea>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Aprobar Sección</span>
                            </button>
                            
                            <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Rechazar Sección</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Apoderado Legal -->
                <div class="mb-6" data-section="apoderado">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Apoderado Legal</h2>
                        <button type="button" onclick="toggleCotejo('apoderado')" 
                                class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span id="toggle_text_apoderado">Mostrar Cotejo</span>
                        </button>
                    </div>
                    <div id="content_apoderado" class="grid grid-cols-1 gap-6">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                            @include('components.forms.apoderado', [
                                'editable' => false,
                                'datos' => $viewModel->getApoderado()
                            ])
                        </div>
                        <div id="cotejo_apoderado" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                            <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Apoderado</h3>
                            @include('components.cotejo-selector', [
                                'archivosSubidos' => $archivosSubidos,
                                'seccion' => 'apoderado'
                            ])
                        </div>
                    </div>
                    
                    <!-- Área de Decisión por Sección -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Decisión - Apoderado Legal</h4>
                            <span class="text-xs text-gray-500">Sección 6/6</span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                            <textarea 
                                placeholder="Agregar observaciones específicas para apoderado legal..."
                                class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                rows="2"></textarea>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Aprobar Sección</span>
                            </button>
                            
                            <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Rechazar Sección</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Archivos -->
                <div class="mb-6" data-section="archivos">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Archivos</h2>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        @include('components.forms.archivos-dinamicos', [
                            'editable' => false, 
                            'archivosRequeridos' => [],
                            'tipoPersona' => 'Moral',
                            'archivosCargados' => $viewModel->getArchivos()
                        ])
                    </div>
                    
                    <!-- Área de Decisión por Sección -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Decisión - Archivos</h4>
                            <span class="text-xs text-gray-500">Sección Final</span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                            <textarea 
                                placeholder="Agregar observaciones específicas para archivos..."
                                class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                rows="2"></textarea>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Aprobar Sección</span>
                            </button>
                            
                            <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Rechazar Sección</span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <!-- Archivos -->
                <div class="mb-6" data-section="archivos">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Archivos</h2>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        @include('components.forms.archivos-dinamicos', [
                            'editable' => false, 
                            'archivosRequeridos' => [],
                            'tipoPersona' => 'Física',
                            'archivosCargados' => $viewModel->getArchivos()
                        ])
                    </div>
                    
                    <!-- Área de Decisión por Sección -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Decisión - Archivos</h4>
                            <span class="text-xs text-gray-500">Sección Final</span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-600 mb-2">Comentarios de esta sección:</label>
                            <textarea 
                                placeholder="Agregar observaciones específicas para archivos..."
                                class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                rows="2"></textarea>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="button" class="flex-1 bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Aprobar Sección</span>
                            </button>
                            
                            <button type="button" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition-colors text-sm flex items-center justify-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Rechazar Sección</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Panel de decisión -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Decisión de Revisión</h3>
                
                <form action="{{ route('revisiones.finalizar', $tramite->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <!-- Campo oculto para el tipo de revisión -->
                    <input type="hidden" name="tipo_revision" value="{{ $tipoRevision }}">
                    
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones (opcional)
                        </label>
                        <textarea 
                            id="observaciones" 
                            name="observaciones" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600"
                            placeholder="Ingresa cualquier observación o comentario sobre la revisión..."
                        ></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button 
                            type="submit" 
                            name="decision" 
                            value="aprobado"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Aprobar Trámite</span>
                        </button>
                        
                        <button 
                            type="submit" 
                            name="decision" 
                            value="rechazado"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Rechazar Trámite</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="fixed bottom-6 right-6 space-y-2 z-40">
    <button type="button" id="btn-prev" onclick="navigateSection('prev')" 
            class="w-12 h-12 bg-gray-600 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-up"></i>
    </button>
    <button type="button" id="btn-next" onclick="navigateSection('next')" 
            class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-down"></i>
    </button>
</div>

<script>
let currentSection = 0;
const sections = document.querySelectorAll('[data-section]');

function navigateSection(direction) {
    if (direction === 'prev' && currentSection > 0) {
        currentSection--;
    } else if (direction === 'next' && currentSection < sections.length - 1) {
        currentSection++;
    }
    
    sections[currentSection].scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function toggleCotejo(seccion) {
    const content = document.getElementById(`content_${seccion}`);
    const cotejo = document.getElementById(`cotejo_${seccion}`);
    const toggleText = document.getElementById(`toggle_text_${seccion}`);
    
    if (cotejo.classList.contains('hidden')) {
        // Mostrar cotejo
        cotejo.classList.remove('hidden');
        content.classList.remove('grid-cols-1');
        content.classList.add('lg:grid-cols-2');
        toggleText.textContent = 'Ocultar Cotejo';
    } else {
        // Ocultar cotejo
        cotejo.classList.add('hidden');
        content.classList.remove('lg:grid-cols-2');
        content.classList.add('grid-cols-1');
        toggleText.textContent = 'Mostrar Cotejo';
    }
}

function toggleHistorial() {
    const contenido = document.getElementById('contenido_historial');
    const toggleText = document.getElementById('toggle_text_historial');
    
    if (contenido.classList.contains('hidden')) {
        contenido.classList.remove('hidden');
        toggleText.textContent = 'Ocultar Historial';
    } else {
        contenido.classList.add('hidden');
        toggleText.textContent = 'Mostrar Historial';
    }
}

window.navigateSection = navigateSection;
window.toggleCotejo = toggleCotejo;
window.toggleHistorial = toggleHistorial;
</script>
@endsection 