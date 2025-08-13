@extends('layouts.app')

@section('title', 'Revisión Digital')

<meta name="tramite-id" content="{{ $tramite->id }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
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



        <!-- Campos ocultos para cada sección -->
        <input type="hidden" name="secciones[datos_generales][decision]" id="decision_datos_generales" value="Pendiente">
        <input type="hidden" name="secciones[datos_generales][comentario]" id="comentario_datos_generales_hidden" value="">
        
        <input type="hidden" name="secciones[actividades][decision]" id="decision_actividades" value="Pendiente">
        <input type="hidden" name="secciones[actividades][comentario]" id="comentario_actividades_hidden" value="">
        
        <input type="hidden" name="secciones[domicilio][decision]" id="decision_domicilio" value="Pendiente">
        <input type="hidden" name="secciones[domicilio][comentario]" id="comentario_domicilio_hidden" value="">
        
        @if($viewModel->isPersonaMoral())
            <input type="hidden" name="secciones[constitucion][decision]" id="decision_constitucion" value="Pendiente">
            <input type="hidden" name="secciones[constitucion][comentario]" id="comentario_constitucion_hidden" value="">
            
            <input type="hidden" name="secciones[accionistas][decision]" id="decision_accionistas" value="Pendiente">
            <input type="hidden" name="secciones[accionistas][comentario]" id="comentario_accionistas_hidden" value="">
            
            <input type="hidden" name="secciones[apoderado][decision]" id="decision_apoderado" value="Pendiente">
            <input type="hidden" name="secciones[apoderado][comentario]" id="comentario_apoderado_hidden" value="">
        @endif
        
        <input type="hidden" name="secciones[archivos][decision]" id="decision_archivos" value="Pendiente">
        <input type="hidden" name="secciones[archivos][comentario]" id="comentario_archivos_hidden" value="">
        
        <input type="hidden" name="comentario_general" id="comentario_general_hidden" value="">
            
        <div class="p-6">
            <!-- Información del trámite -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Inscripción</h3>
                        <p class="text-sm text-gray-600">
                            @if($viewModel->isPersonaMoral())
                                Persona Moral • 7 secciones
                            @else
                                Persona Física • 4 secciones
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Panel de Historial -->
            <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-[#9d2449] rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Historial ({{ $estadisticasHistorial['total'] }})</h3>
                        <p class="text-sm text-gray-600">RFC: {{ $tramite->proveedor->rfc }}</p>
                        <div class="flex space-x-4 mt-1">
                            <span class="text-xs text-green-600">{{ $estadisticasHistorial['aprobados'] }} aprobados</span>
                            <span class="text-xs text-red-600">{{ $estadisticasHistorial['rechazados'] }} rechazados</span>
                            <span class="text-xs text-orange-600">{{ $estadisticasHistorial['pendientes'] }} pendientes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Simbología de Estados -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex flex-wrap items-center gap-6 text-sm">
                    <span class="font-medium text-gray-700">Estados:</span>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-gray-600">Aprobado</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-gray-600">Pendiente</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-gray-600">Rechazado</span>
                    </div>
                </div>
            </div>

            <!-- Separador Inicial -->
            <x-ui.separador-simple margin="my-8" color="border-indigo-300" />

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
                        @include('components.tramites.cotejo-selector', [
                            'archivosSubidos' => $archivosSubidos,
                            'seccion' => 'datos_generales'
                        ])
                    </div>
                </div>
                
                <!-- Área de comentarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Comentarios - Datos Generales</h4>
                    </div>
                    <textarea 
                        id="comentario_datos_generales"
                        placeholder="Agregar observaciones sobre los datos generales..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="3"
                    ></textarea>
                </div>
                    
                <!-- Botones de decisión -->
                <x-revision.botones-evaluacion 
                    seccion="datos_generales"
                    titulo="Datos Generales"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            <x-ui.separador-simple margin="my-8" color="border-emerald-300" />

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
                            'datos' => $viewModel->getActividadesForm()
                        ])
                    </div>
                    <div id="cotejo_actividades" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                        <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Actividades</h3>
                        @include('components.tramites.cotejo-selector', [
                            'archivosSubidos' => $archivosSubidos,
                            'seccion' => 'actividades'
                        ])
                    </div>
                </div>
                
                <!-- Área de comentarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Comentarios - Actividades</h4>
                    </div>
                    <textarea 
                        id="comentario_actividades"
                        placeholder="Agregar observaciones sobre las actividades económicas..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="3"
                    ></textarea>
                </div>
                    
                <!-- Botones de decisión -->
                <x-revision.botones-evaluacion 
                    seccion="actividades"
                    titulo="Actividades Económicas"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            <x-ui.separador-simple margin="my-8" color="border-amber-300" />

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
                        @include('components.tramites.cotejo-selector', [
                            'archivosSubidos' => $archivosSubidos,
                            'seccion' => 'domicilio'
                        ])
                    </div>
                </div>
                
                <!-- Área de comentarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                    <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Comentarios - Domicilio</h4>
                    </div>
                    <textarea 
                        id="comentario_domicilio"
                        placeholder="Agregar observaciones sobre el domicilio..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="3"
                    ></textarea>
                </div>
                    
                <!-- Botones de decisión -->
                <x-revision.botones-evaluacion 
                    seccion="domicilio"
                    titulo="Domicilio"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            @if($viewModel->isPersonaMoral())
                <x-ui.separador-simple margin="my-8" color="border-purple-300" />

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
                                'datos' => $viewModel->getConstitucionForm()
                            ])
                        </div>
                        <div id="cotejo_constitucion" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                            <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Constitución</h3>
                            @include('components.tramites.cotejo-selector', [
                                'archivosSubidos' => $archivosSubidos,
                                'seccion' => 'constitucion'
                            ])
                        </div>
                    </div>
                    
                    <!-- Área de comentarios -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Comentarios - Constitución</h4>
                        </div>
                        <textarea 
                            id="comentario_constitucion"
                            placeholder="Agregar observaciones sobre la constitución..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                            rows="3"
                        ></textarea>
                    </div>
                        
                    <!-- Botones de decisión -->
                    <x-revision.botones-evaluacion 
                        seccion="constitucion"
                        titulo="Constitución"
                        style="compact"
                        textoAprobar="Aprobar Sección"
                        textoRechazar="Rechazar Sección"
                    />
                </div>

                <x-ui.separador-simple margin="my-8" color="border-rose-300" />

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
                                'datos' => $viewModel->getAccionistasForm()
                            ])
                        </div>
                        <div id="cotejo_accionistas" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                            <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Accionistas</h3>
                            @include('components.tramites.cotejo-selector', [
                                'archivosSubidos' => $archivosSubidos,
                                'seccion' => 'accionistas'
                            ])
                        </div>
                    </div>
                    
                    <!-- Área de comentarios -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Comentarios - Accionistas</h4>
                        </div>
                        <textarea 
                            id="comentario_accionistas"
                            placeholder="Agregar observaciones sobre los accionistas..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                            rows="3"
                        ></textarea>
                    </div>
                        
                    <!-- Botones de decisión -->
                    <x-revision.botones-evaluacion 
                        seccion="accionistas"
                        titulo="Accionistas"
                        style="compact"
                        textoAprobar="Aprobar Sección"
                        textoRechazar="Rechazar Sección"
                    />
                </div>

                <x-ui.separador-simple margin="my-8" color="border-cyan-300" />

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
                                'datos' => $viewModel->getApoderadoForm()
                            ])
                        </div>
                        <div id="cotejo_apoderado" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 min-h-[600px] hidden">
                            <h3 class="text-lg font-semibold text-gray-600 mb-4 border-b pb-2">Cotejo - Apoderado</h3>
                            @include('components.tramites.cotejo-selector', [
                                'archivosSubidos' => $archivosSubidos,
                                'seccion' => 'apoderado'
                            ])
                        </div>
                    </div>
                    
                    <!-- Área de comentarios -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mt-4">
                        <div class="mb-3">
                            <h4 class="text-sm font-medium text-gray-700">Comentarios - Apoderado</h4>
                        </div>
                        <textarea 
                            id="comentario_apoderado"
                            placeholder="Agregar observaciones sobre el apoderado legal..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                            rows="3"
                        ></textarea>
                    </div>
                        
                    <!-- Botones de decisión -->
                    <x-revision.botones-evaluacion 
                        seccion="apoderado"
                        titulo="Apoderado Legal"
                        style="compact"
                        textoAprobar="Aprobar Sección"
                        textoRechazar="Rechazar Sección"
                    />
                </div>
            @endif

            <x-ui.separador-simple margin="my-8" color="border-slate-400" />

            <!-- Archivos -->
            <div class="mb-6" data-section="archivos">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Archivos</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    @foreach($archivosSubidos as $archivo)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div class="flex-shrink-0">
                                    @if(str_contains(strtolower($archivo['tipo_archivo'] ?? ''), 'pdf'))
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @elseif(str_contains(strtolower($archivo['tipo_archivo'] ?? ''), 'image'))
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $archivo['nombre_original'] ?? 'Sin nombre' }}</p>
                                    <p class="text-xs text-gray-500">{{ $archivo['tipo_archivo'] ?? 'Sin tipo' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mb-3">
                            <span id="estado_archivo_{{ $archivo['id'] ?? 0 }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Pendiente
                            </span>
                            <div class="flex space-x-1">
                                <button onclick="evaluarArchivo({{ $archivo['id'] ?? 0 }}, 'Aprobado')" 
                                        class="p-1.5 text-green-600 hover:bg-green-50 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <button onclick="evaluarArchivo({{ $archivo['id'] ?? 0 }}, 'Rechazado')" 
                                        class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <textarea 
                            id="textarea_archivo_{{ $archivo['id'] ?? 0 }}"
                            placeholder="Comentario sobre este archivo..."
                            class="w-full px-2 py-1.5 border border-gray-200 rounded text-xs resize-none focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449]"
                            rows="2"
                        ></textarea>
                    </div>
                    @endforeach
                </div>
                
                <!-- Área de comentarios -->
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700">Comentarios - Archivos</h4>
                    </div>
                    <textarea 
                        id="comentario_archivos"
                        placeholder="Agregar observaciones sobre los archivos..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="3"
                    ></textarea>
                </div>
                    
                <!-- Botones de decisión -->
                <x-revision.botones-evaluacion 
                    seccion="archivos"
                    titulo="Archivos"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            <!-- Comentarios Generales -->
            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Comentarios Generales</h3>
                        <p class="text-sm text-gray-600">Observaciones generales sobre toda la revisión</p>
                    </div>
                    <textarea 
                        id="comentario_general"
                        placeholder="Agregar observaciones generales sobre el trámite..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                        rows="4"
                    ></textarea>
                </div>
            </div>

            <!-- Botones de Decisión Final -->
            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Decisión Final</h3>
                        <p class="text-sm text-gray-600">Tomar decisión final sobre el trámite</p>
                    </div>
                    
                    <x-revision.botones-decision-final 
                        :showAprobar="true"
                        :showCorrecciones="true"
                        :showRechazar="true"
                        layout="flex"
                    />
                </div>
            </div>
        </div>
        </div>
    </div>

<!-- CSS para estados de sección -->
<style>
.seccion-aprobada {
    border-left: 4px solid #10b981;
    background-color: #f0fdf4;
}

.seccion-rechazada {
    border-left: 4px solid #ef4444;
    background-color: #fef2f2;
}

.seccion-pendiente {
    border-left: 4px solid #eab308;
    background-color: #fefce8;
}

/* Estilos para notificaciones */
.fixed {
    position: fixed;
}

.top-4 {
    top: 1rem;
}

.right-4 {
    right: 1rem;
}

.p-4 {
    padding: 1rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.shadow-lg {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.z-50 {
    z-index: 50;
}

.text-white {
    color: white;
}

.transition-opacity {
    transition-property: opacity;
}

.duration-300 {
    transition-duration: 300ms;
}

.opacity-100 {
    opacity: 1;
}

.opacity-0 {
    opacity: 0;
}

.bg-green-500 {
    background-color: #10b981;
}

.bg-yellow-500 {
    background-color: #eab308;
}

.bg-red-500 {
    background-color: #ef4444;
}

.bg-blue-500 {
    background-color: #3b82f6;
}

.max-w-xs {
    max-width: 20rem;
}

.text-sm {
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.p-3 {
    padding: 0.75rem;
}
</style>

<!-- JavaScript -->
<script>
window.esPersonaMoral = @json($viewModel->isPersonaMoral());
</script>

<!-- Modal de Confirmación -->
<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-decision"
    title="Confirmar acción"
    message="¿Está seguro que desea realizar esta acción?"
    confirmText="Confirmar"
    cancelText="Cancelar"
    confirmClass="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-blue-500"
/>

<!-- Modal de Éxito -->
<x-ui.modals.modal-exito />

<script src="{{ asset('js/revision/evaluacion-secciones.js') }}"></script>
<script src="{{ asset('js/revision/archivos-tiempo-real.js') }}"></script>
<script src="{{ asset('js/revision/cargar-estados.js') }}"></script>
<script src="{{ asset('js/revision/decisiones-finales.js') }}"></script>

<script>
// Función para mostrar notificaciones (compacta)
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Acortar mensajes largos
    let mensajeCorto = mensaje;
    if (mensaje.length > 50) {
        mensajeCorto = mensaje.substring(0, 47) + '...';
    }
    
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 p-3 rounded-lg shadow-lg z-50 text-white transition-opacity duration-300 max-w-xs text-sm ${
        tipo === 'success' ? 'bg-green-500' :
        tipo === 'warning' ? 'bg-yellow-500' :
        tipo === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    div.textContent = mensajeCorto;
    
    document.body.appendChild(div);
    
    // Mostrar notificación
    setTimeout(() => div.classList.add('opacity-100'), 100);
    
    // Ocultar después de 2.5 segundos
    setTimeout(() => {
        div.classList.add('opacity-0');
        setTimeout(() => document.body.removeChild(div), 300);
    }, 2500);
}

// Hacer la función disponible globalmente
window.mostrarNotificacion = mostrarNotificacion;

// Inicializar sistema de carga de estados
document.addEventListener('DOMContentLoaded', function() {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    if (tramiteId) {
        window.revisionDigitalEstados = new RevisionDigitalEstados(tramiteId);
    }
});
</script>
@endsection