@extends('layouts.app')

@section('title', 'Revisión Digital')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <!-- Header de Revisión -->
        @php
            $tipoRevisionLabel = match($tipoRevision) {
                'Digital' => 'Revisión Digital',
                'Presencial' => 'Revisión Presencial', 
                'Domiciliaria' => 'Revisión Domiciliaria',
                default => 'Revisión'
            };
        @endphp
        
        <!-- Header de Revisión -->
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $tipoRevisionLabel }} - Trámite #{{ $tramite->id }}</h1>
                        <p class="text-base text-gray-500 mt-1">{{ strtolower($tipoRevisionLabel) }} de documentos y datos del trámite</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('revisiones.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Información del trámite -->
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

            <!-- Panel de Historial -->
            <div class="bg-gradient-to-r from-slate-50 to-gray-50 border border-gray-200/60 rounded-xl p-5 mb-8 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#9d2449] to-[#7a1a37] rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Historial de Trámites</h3>
                            <p class="text-sm text-gray-600">RFC: {{ $tramite->proveedor->rfc }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="toggleHistorial()" 
                            class="flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm rounded-lg transition-all duration-200 border border-gray-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2 transition-transform duration-200" id="toggle_icon_historial" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                        <span id="toggle_text_historial">Ver Historial</span>
                    </button>
                </div>

                <!-- Estadísticas simplificadas -->
                <div class="grid grid-cols-4 gap-3 mb-5">
                    <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg text-center border border-gray-100 shadow-sm">
                        <div class="text-xl font-bold text-gray-800">{{ $estadisticasHistorial['total'] }}</div>
                        <div class="text-xs text-gray-500 font-medium">Total</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg text-center border border-emerald-100 shadow-sm">
                        <div class="text-xl font-bold text-emerald-600">{{ $estadisticasHistorial['aprobados'] }}</div>
                        <div class="text-xs text-emerald-600 font-medium">Aprobados</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg text-center border border-rose-100 shadow-sm">
                        <div class="text-xl font-bold text-rose-600">{{ $estadisticasHistorial['rechazados'] }}</div>
                        <div class="text-xs text-rose-600 font-medium">Rechazados</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm p-4 rounded-lg text-center border border-amber-100 shadow-sm">
                        <div class="text-xl font-bold text-amber-600">{{ $estadisticasHistorial['pendientes'] }}</div>
                        <div class="text-xs text-amber-600 font-medium">Pendientes</div>
                    </div>
                </div>

                <!-- Lista de trámites simplificada -->
                <div id="contenido_historial" class="hidden">
                    @if($historialTramites->count() > 0)
                        <div class="space-y-2 max-h-80 overflow-y-auto">
                            @foreach($historialTramites as $tramiteHistorico)
                                <div class="bg-white/90 backdrop-blur-sm border border-gray-200/60 rounded-lg p-3 hover:shadow-md transition-all duration-200 {{ $tramiteHistorico['id'] == $tramite->id ? 'ring-2 ring-[#9d2449]/20 border-[#9d2449]/30' : '' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3 flex-1">
                                            @php
                                                $statusConfig = match($tramiteHistorico['status']) {
                                                    'Aprobado' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'M5 13l4 4L19 7'],
                                                    'Rechazado' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                                    default => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
                                                };
                                            @endphp
                                            <div class="w-6 h-6 {{ $statusConfig['bg'] }} rounded-lg flex items-center justify-center">
                                                <svg class="w-3 h-3 {{ $statusConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-semibold text-gray-800">Trámite #{{ $tramiteHistorico['id'] }}</span>
                                                    @if($tramiteHistorico['id'] == $tramite->id)
                                                        <span class="px-2 py-0.5 bg-[#9d2449] text-white text-xs font-medium rounded-full">Actual</span>
                                                    @endif
                                                    <span class="px-2 py-0.5 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-xs font-medium rounded-full">
                                                        {{ $tramiteHistorico['status'] }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-600 truncate mt-0.5">{{ $tramiteHistorico['razon_social'] }} • {{ $tramiteHistorico['created_at']->format('d/m/Y') }}</p>
                                            </div>
                                        </div>
                                        @if($tramiteHistorico['id'] != $tramite->id)
                                            <a href="{{ route('revisiones.ver-historico', $tramiteHistorico['id']) }}" 
                                               class="ml-3 flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 text-xs font-medium rounded-lg transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-white/50 rounded-lg border-2 border-dashed border-gray-200">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 font-medium">No hay trámites anteriores para este RFC</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Separador Inicial -->
            <x-separador-simple margin="my-8" color="border-indigo-300" />

            <!-- Datos Generales -->
            <div class="mb-8" data-section="datos_generales">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Datos Generales</h2>
                    <button type="button" onclick="toggleCotejo('datos_generales')" 
                            class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
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
                
                <x-revision.area-decision 
                    seccion="datos_generales" 
                    titulo="Decisión - Datos Generales"
                    numero-seccion="Sección 1/6"
                    placeholder="Agregar observaciones específicas para datos generales..." 
                />
            </div>

            <x-separador-simple margin="my-8" color="border-emerald-300" />

            <!-- Actividades Económicas -->
            <div class="mb-8" data-section="actividades">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Actividades Económicas</h2>
                    <button type="button" onclick="toggleCotejo('actividades')" 
                            class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
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
                
                <x-revision.area-decision 
                    seccion="actividades" 
                    titulo="Decisión - Actividades Económicas"
                    numero-seccion="Sección 2/6"
                    placeholder="Agregar observaciones específicas para actividades económicas..." 
                />
            </div>

            <x-separador-simple margin="my-8" color="border-amber-300" />

            <!-- Domicilio -->
            <div class="mb-8" data-section="domicilio">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Domicilio</h2>
                    <button type="button" onclick="toggleCotejo('domicilio')" 
                            class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
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
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="domicilio">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <h4 class="text-sm font-medium text-gray-700">Decisión - Domicilio</h4>
                            <span id="estado_domicilio" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                Pendiente
                            </span>
                        </div>
                        <span class="text-xs text-gray-500">Sección 3/6</span>
                    </div>
                    
                    <x-revision.textarea-comentarios 
                        seccion="domicilio"
                        placeholder="Agregar observaciones específicas para domicilio..."
                        label-text="Comentarios de esta sección:"
                        :rows="2"
                    />
                    
                    <x-revision.botones-evaluacion 
                        seccion="domicilio"
                        titulo="Sección"
                        style="compact"
                        size="sm"
                    />
                </div>
            </div>

            @if($viewModel->isPersonaMoral())
                <x-separador-simple margin="my-8" color="border-purple-300" />

                <!-- Constitución -->
                <div class="mb-8" data-section="constitucion">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Constitución</h2>
                        <button type="button" onclick="toggleCotejo('constitucion')" 
                                class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
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
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="constitucion">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-700">Decisión - Constitución</h4>
                                <span id="estado_constitucion" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Pendiente
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Sección 4/6</span>
                        </div>
                        
                        <x-revision.textarea-comentarios 
                            seccion="constitucion"
                            placeholder="Agregar observaciones específicas para constitución..."
                            label-text="Comentarios de esta sección:"
                            :rows="2"
                        />
                        
                        <x-revision.botones-evaluacion 
                            seccion="constitucion"
                            titulo="Sección"
                            style="compact"
                            size="sm"
                        />
                    </div>
                </div>

                <x-separador-simple margin="my-8" color="border-rose-300" />

                <!-- Accionistas -->
                <div class="mb-8" data-section="accionistas">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                        <button type="button" onclick="toggleCotejo('accionistas')" 
                                class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
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
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="accionistas">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-700">Decisión - Accionistas</h4>
                                <span id="estado_accionistas" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Pendiente
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Sección 5/6</span>
                        </div>
                        
                        <x-revision.textarea-comentarios 
                            seccion="accionistas"
                            placeholder="Agregar observaciones específicas para accionistas..."
                            label-text="Comentarios de esta sección:"
                            :rows="2"
                        />
                        
                        <x-revision.botones-evaluacion 
                            seccion="accionistas"
                            titulo="Sección"
                            style="compact"
                            size="sm"
                        />
                    </div>
                </div>

                <x-separador-simple margin="my-8" color="border-cyan-300" />

                <!-- Apoderado Legal -->
                <div class="mb-8" data-section="apoderado">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Apoderado Legal</h2>
                        <button type="button" onclick="toggleCotejo('apoderado')" 
                                class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
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
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="apoderado">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-700">Decisión - Apoderado Legal</h4>
                                <span id="estado_apoderado" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Pendiente
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Sección 6/6</span>
                        </div>
                        
                        <x-revision.textarea-comentarios 
                            seccion="apoderado"
                            placeholder="Agregar observaciones específicas para apoderado legal..."
                            label-text="Comentarios de esta sección:"
                            :rows="2"
                        />
                        
                        <x-revision.botones-evaluacion 
                            seccion="apoderado"
                            titulo="Sección"
                            style="compact"
                            size="sm"
                        />
                    </div>
                </div>

                <x-separador-simple margin="my-8" color="border-slate-400" />

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
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="archivos">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-700">Decisión - Archivos</h4>
                                <span id="estado_archivos" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Pendiente
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Sección Final</span>
                        </div>
                        
                        <x-revision.textarea-comentarios 
                            seccion="archivos"
                            placeholder="Agregar observaciones específicas para archivos..."
                            label-text="Comentarios de esta sección:"
                            :rows="2"
                        />
                        
                        <x-revision.botones-evaluacion 
                            seccion="archivos"
                            titulo="Sección"
                            style="compact"
                            size="sm"
                        />
                    </div>
                </div>
            @else
                <x-separador-simple margin="my-8" color="border-slate-400" />

                <!-- Archivos -->
                <div class="mb-6" data-section="archivos">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
                        @include('components.forms.archivos-dinamicos', [
                            'editable' => false, 
                            'archivosRequeridos' => [],
                            'tipoPersona' => 'Física',
                            'archivosCargados' => $viewModel->getArchivos()
                        ])
                    </div>
                    
                    <!-- Área de Decisión por Sección -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4" data-seccion="archivos">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-sm font-medium text-gray-700">Decisión - Archivos</h4>
                                <span id="estado_archivos_fisica" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Pendiente
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Sección Final</span>
                        </div>
                        
                        <x-revision.textarea-comentarios 
                            seccion="archivos_fisica"
                            placeholder="Agregar observaciones específicas para archivos..."
                            label-text="Comentarios de esta sección:"
                            :rows="2"
                        />
                        
                        <x-revision.botones-evaluacion 
                            seccion="archivos"
                            titulo="Sección"
                            style="compact"
                            size="sm"
                        />
                    </div>
                </div>
            @endif

            <x-separador-simple margin="my-12" color="border-blue-400" />

            <!-- Panel de decisión final -->
            @php
                $secciones = ['datos_generales', 'actividades', 'domicilio'];
                if($viewModel->isPersonaMoral()) {
                    $secciones = array_merge($secciones, ['constitucion', 'accionistas', 'apoderado']);
                }
                $secciones[] = 'archivos';
            @endphp
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Decisión Final</h3>
                
                <form id="formRevisionCompleta" action="{{ route('revisiones.procesar-digital', $tramite->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tipo_revision" value="{{ $tipoRevision }}">
                    
                    <!-- Campos ocultos para las decisiones de cada sección -->
                    <div id="decisiones-secciones" class="hidden">
                        @foreach($secciones as $seccion)
                        <input type="hidden" name="secciones[{{ $seccion }}][decision]" id="decision_{{ $seccion }}" value="Pendiente">
                        <input type="hidden" name="secciones[{{ $seccion }}][comentario]" id="comentario_{{ $seccion }}" value="">
                        @endforeach
                    </div>
                    
                    <!-- Observaciones generales -->
                    <div>
                        <label for="observaciones_generales" class="block text-sm font-medium text-gray-700 mb-2">
                            Comentarios generales
                        </label>
                        <textarea 
                            id="observaciones_generales" 
                            name="observaciones_generales" 
                            rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Comentarios sobre la revisión..."
                        ></textarea>
                    </div>

                    <!-- Botones de decisión final -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <button 
                            type="submit" 
                            name="decision_final" 
                            value="aprobado"
                            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded text-sm"
                        >
                            ✓ Aprobar / Agendar Cita
                        </button>
                        
                        <button 
                            type="submit" 
                            name="decision_final" 
                            value="correcciones"
                            class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-3 rounded text-sm"
                        >
                            📝 Correcciones
                        </button>
                        
                        <button 
                            type="submit" 
                            name="decision_final" 
                            value="rechazado"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-3 rounded text-sm"
                        >
                            ✗ Rechazar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<!-- JavaScript externo para funciones de revisión -->
<script src="{{ asset('js/revision-digital.js') }}"></script>
@endsection 