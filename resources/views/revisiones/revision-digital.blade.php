@extends('layouts.app')

@section('title', 'Revisión Digital')

<meta name="tramite-id" content="{{ $tramite->id }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        

        @php
            $tipoRevisionLabel = match($tipoRevision) {
                'Digital' => 'Revisión Digital',
                'Presencial' => 'Revisión Presencial', 
                'Domiciliaria' => 'Revisión Domiciliaria',
                default => 'Revisión'
            };
        @endphp
        

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
                        <div class="flex items-center gap-4 mt-1">
                            <p class="text-base text-gray-500">{{ strtolower($tipoRevisionLabel) }} de documentos y datos del trámite</p>
                            @if($tramite->correcciones_count > 0)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span class="text-sm font-medium text-amber-600">
                                        {{ $tramite->correcciones_count }} revisión{{ $tramite->correcciones_count !== 1 ? 'es' : '' }}
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-green-600">Primera revisión</span>
                                </div>
                            @endif
                        </div>
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
            
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-12 h-12 bg-blue-200 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
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



            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 sm:p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-3 sm:gap-6 text-sm">
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

            @if($historialTramites && $historialTramites->count() > 0)
            <div class="mb-8">
                <div class="bg-white rounded-lg shadow-md border border-gray-200">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <button type="button" onclick="toggleSection('historial')" 
                                class="flex items-center text-left hover:bg-gray-50 transition-colors rounded-lg p-2 -m-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Historial de Trámites - RFC: {{ $rfc }} ({{ $historialTramites->count() }})</span>
                            <svg id="historial-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Ordenar:</label>
                            <select onchange="cambiarOrdenHistorial(this.value)" 
                                    class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="reciente" {{ ($ordenHistorial ?? 'reciente') === 'reciente' ? 'selected' : '' }}>
                                    📅 Más recientes
                                </option>
                                <option value="pasados" {{ ($ordenHistorial ?? 'reciente') === 'pasados' ? 'selected' : '' }}>
                                    📜 Más antiguos
                                </option>
                            </select>
                        </div>
                    </div>
                    <div id="historial-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                        @php            
                            $tramitesPorProveedor = $historialTramites->groupBy('proveedor_id');
                        @endphp
                        
                        <div class="space-y-6">
                            @foreach($tramitesPorProveedor as $proveedorId => $tramitesDelProveedor)
                            @php
                                $proveedorActual = $tramitesDelProveedor->first()->proveedor;
                                $esProveedorPrincipal = $proveedorActual->id === $tramite->proveedor->id;
                            @endphp
                                    
                            <div class="bg-white rounded-lg border-l-4 {{ $esProveedorPrincipal ? 'border-l-[#9d2449] bg-red-50' : 'border-l-gray-400 bg-gray-50' }} shadow-sm">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                {{ $proveedorActual->razon_social }}
                                                @if($esProveedorPrincipal)
                                                    <span class="ml-2 text-xs bg-[#9d2449] text-white px-2 py-1 rounded">ACTUAL</span>
                                                @endif
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                PV: {{ $proveedorActual->pv_numero ?? 'No asignado' }} | RFC: {{ $proveedorActual->rfc }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm text-gray-600">
                                            <div>{{ $tramitesDelProveedor->count() }} trámite{{ $tramitesDelProveedor->count() !== 1 ? 's' : '' }}</div>
                                            <div class="text-xs">{{ $proveedorActual->estado_padron }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6 space-y-3">
                                    @foreach($tramitesDelProveedor as $tramiteHistorial)
                                    @php
                                        $status = $tramiteHistorial->status ?? 'Pendiente';
                                        $statusColor = [
                                            'Aprobado' => 'bg-green-100 text-green-800 border-green-200',
                                            'Rechazado' => 'bg-red-100 text-red-800 border-red-200',
                                            'Para_Correccion' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'En_Revision' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Revision_Digital' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'Revision_Presencial' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                            'Revision_Domiciliaria' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                            'Pendiente' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        ][$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200">
                                        <div class="flex-grow">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="font-semibold text-gray-900 mb-1">{{ $tramiteHistorial->tipo_tramite ?? 'Trámite' }}</div>
                                                    <div class="text-sm text-gray-600 space-y-1">
                                                        @php
                                                            $fechaTramite = $tramiteHistorial->fecha_finalizacion ?? $tramiteHistorial->fecha_inicio;
                                                        @endphp
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <span>
                                                                @if($fechaTramite)
                                                                    {{ \Carbon\Carbon::parse($fechaTramite)->format('d/m/Y H:i') }}
                                                                @else
                                                                    {{ $tramiteHistorial->created_at ? $tramiteHistorial->created_at->format('d/m/Y H:i') : 'Fecha no disponible' }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            <span>ID: #{{ $tramiteHistorial->id }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-3 ml-4">
                                                    <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $statusColor }}">
                                                        {{ str_replace('_', ' ', $status) }}
                                                    </span>
                                                    @if($tramiteHistorial->id)
                                                    <a href="{{ route('proveedores.tramite-detalles', $tramiteHistorial->id) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#9d2449] to-[#7a1a37] rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Ver →
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <x-ui.separador-simple margin="my-8" color="border-indigo-300" />

            <div class="mb-8" data-section="datos_generales">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-800">Datos Generales</h2>
                        <span id="estado_datos_generales" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                    </div>
                    <button type="button" onclick="toggleCotejo('datos_generales')" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
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
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6 mt-6">
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
                    
                <x-revision.botones-evaluacion 
                    seccion="datos_generales"
                    titulo="Datos Generales"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            <x-ui.separador-simple margin="my-8" color="border-emerald-300" />

            <div class="mb-8" data-section="actividades">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-800">Actividades Económicas</h2>
                        <span id="estado_actividades" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                    </div>
                    <button type="button" onclick="toggleCotejo('actividades')" 
                            class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
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
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-800">Domicilio</h2>
                        <span id="estado_domicilio" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                    </div>
                    <button type="button" onclick="toggleCotejo('domicilio')" 
                            class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
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

                <div class="mb-8" data-section="constitucion">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-gray-800">Constitución</h2>
                            <span id="estado_constitucion" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                        </div>
                        <button type="button" onclick="toggleCotejo('constitucion')" 
                                class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
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
                        
                    <x-revision.botones-evaluacion 
                        seccion="constitucion"
                        titulo="Constitución"
                        style="compact"
                        textoAprobar="Aprobar Sección"
                        textoRechazar="Rechazar Sección"
                    />
                </div>

                <x-ui.separador-simple margin="my-8" color="border-rose-300" />

                <div class="mb-8" data-section="accionistas">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                            <span id="estado_accionistas" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                        </div>
                        <button type="button" onclick="toggleCotejo('accionistas')" 
                                class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
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

                    <x-revision.botones-evaluacion 
                        seccion="accionistas"
                        titulo="Accionistas"
                        style="compact"
                        textoAprobar="Aprobar Sección"
                        textoRechazar="Rechazar Sección"
                    />
                </div>

                <x-ui.separador-simple margin="my-8" color="border-cyan-300" />

                <div class="mb-8" data-section="apoderado">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-gray-800">Apoderado Legal</h2>
                            <span id="estado_apoderado" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                        </div>
                        <button type="button" onclick="toggleCotejo('apoderado')" 
                                class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
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

            <div class="mb-6" data-section="archivos">
                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl font-bold text-gray-800">Archivos</h2>
                        <span id="estado_archivos" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                    </div>
                </div>
                
                <div class="space-y-4 mb-6">
                    @foreach($archivosSubidos as $archivo)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow" data-archivo-id="{{ $archivo['id'] ?? 0 }}">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
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
                            
                            <div class="flex items-center gap-3">
                                <span id="estado_archivo_{{ $archivo['id'] ?? 0 }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Pendiente
                                </span>
                                <a href="{{ route('revisiones.mostrar-archivo', $archivo['id'] ?? 0) }}" 
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 1 1 6 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver
                                </a>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <textarea 
                                id="textarea_archivo_{{ $archivo['id'] ?? 0 }}"
                                placeholder="Comentario sobre este archivo..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 resize-none text-sm"
                                rows="3"
                            ></textarea>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    data-archivo-id="{{ $archivo['id'] ?? 0 }}" 
                                    data-decision="Aprobado"
                                    class="evaluar-archivo-btn inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Aprobar
                            </button>
                            <button type="button" 
                                    data-archivo-id="{{ $archivo['id'] ?? 0 }}" 
                                    data-decision="Rechazado"
                                    class="evaluar-archivo-btn inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Rechazar
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
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
                    
                <x-revision.botones-evaluacion 
                    seccion="archivos"
                    titulo="Archivos"
                    style="compact"
                    textoAprobar="Aprobar Sección"
                    textoRechazar="Rechazar Sección"
                />
            </div>

            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
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

            <div class="mt-8">
                <div class="bg-white border border-gray-200 rounded-lg p-4 sm:p-6">
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

            @if($historialTramites && $historialTramites->count() > 0)
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-md border border-gray-200">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <button type="button" onclick="toggleSection('historial')" 
                                class="flex items-center text-left hover:bg-gray-50 transition-colors rounded-lg p-2 -m-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Historial de Trámites - RFC: {{ $rfc }} ({{ $historialTramites->count() }})</span>
                            <svg id="historial-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600">Ordenar:</label>
                            <select onchange="cambiarOrdenHistorial(this.value)" 
                                    class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="reciente" {{ ($ordenHistorial ?? 'reciente') === 'reciente' ? 'selected' : '' }}>
                                    📅 Más recientes
                                </option>
                                <option value="pasados" {{ ($ordenHistorial ?? 'reciente') === 'pasados' ? 'selected' : '' }}>
                                    📜 Más antiguos
                                </option>
                            </select>
                        </div>
                    </div>
                    <div id="historial-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                        @php            
                            $tramitesPorProveedor = $historialTramites->groupBy('proveedor_id');
                        @endphp
                        
                        <div class="space-y-6">
                            @foreach($tramitesPorProveedor as $proveedorId => $tramitesDelProveedor)
                            @php
                                $proveedorActual = $tramitesDelProveedor->first()->proveedor;
                                $esProveedorPrincipal = $proveedorActual->id === $tramite->proveedor->id;
                            @endphp
                                    
                            <div class="bg-white rounded-lg border-l-4 {{ $esProveedorPrincipal ? 'border-l-[#9d2449] bg-red-50' : 'border-l-gray-400 bg-gray-50' }} shadow-sm">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                {{ $proveedorActual->razon_social }}
                                                @if($esProveedorPrincipal)
                                                    <span class="ml-2 text-xs bg-[#9d2449] text-white px-2 py-1 rounded">ACTUAL</span>
                                                @endif
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                PV: {{ $proveedorActual->pv_numero ?? 'No asignado' }} | RFC: {{ $proveedorActual->rfc }}
                                            </p>
                                        </div>
                                        <div class="text-right text-sm text-gray-600">
                                            <div>{{ $tramitesDelProveedor->count() }} trámite{{ $tramitesDelProveedor->count() !== 1 ? 's' : '' }}</div>
                                            <div class="text-xs">{{ $proveedorActual->estado_padron }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6 space-y-3">
                                    @foreach($tramitesDelProveedor as $tramiteHistorial)
                                    @php
                                        $status = $tramiteHistorial->status ?? 'Pendiente';
                                        $statusColor = [
                                            'Aprobado' => 'bg-green-100 text-green-800 border-green-200',
                                            'Rechazado' => 'bg-red-100 text-red-800 border-red-200',
                                            'Para_Correccion' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'En_Revision' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Revision_Digital' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'Revision_Presencial' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                            'Revision_Domiciliaria' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                            'Pendiente' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        ][$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200">
                                        <div class="flex-grow">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-grow">
                                                    <div class="font-semibold text-gray-900 mb-1">{{ $tramiteHistorial->tipo_tramite ?? 'Trámite' }}</div>
                                                    <div class="text-sm text-gray-600 space-y-1">
                                                        @php
                                                            $fechaTramite = $tramiteHistorial->fecha_finalizacion ?? $tramiteHistorial->fecha_inicio;
                                                        @endphp
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                            <span>
                                                                @if($fechaTramite)
                                                                    {{ \Carbon\Carbon::parse($fechaTramite)->format('d/m/Y H:i') }}
                                                                @else
                                                                    {{ $tramiteHistorial->created_at ? $tramiteHistorial->created_at->format('d/m/Y H:i') : 'Fecha no disponible' }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            <span>ID: #{{ $tramiteHistorial->id }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-3 ml-4">
                                                    <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $statusColor }}">
                                                        {{ str_replace('_', ' ', $status) }}
                                                    </span>
                                                    @if($tramiteHistorial->id)
                                                    <a href="{{ route('proveedores.tramite-detalles', $tramiteHistorial->id) }}" 
                                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#9d2449] to-[#7a1a37] rounded-lg hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Ver →
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        </div>
    </div>

<style>
.seccion-aprobada {
    border-left: 4px solid rgb(16, 185, 129);
    background-color: rgb(240, 253, 244);
}

.seccion-rechazada {
    border-left: 4px solid rgb(239, 68, 68);
    background-color: rgb(254, 242, 242);
}

.seccion-pendiente {
    border-left: 4px solid rgb(234, 179, 8);
    background-color: rgb(254, 252, 232);
}

</style>

<script>
window.esPersonaMoral = JSON.parse('{{ json_encode($viewModel->isPersonaMoral()) }}');
</script>


<x-ui.modals.modal-confirmacion 
    id="modal-confirmacion-decision"
    title="Confirmar acción"
    message="¿Está seguro que desea realizar esta acción?"
    confirmText="Confirmar"
    cancelText="Cancelar"
    confirmClass="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
    cancelClass="bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-blue-500"
/>

<x-ui.modals.modal-exito />



<script src="{{ asset('js/revision/evaluacion-secciones.js') }}"></script>
<script src="{{ asset('js/revision/archivos-tiempo-real.js') }}"></script>
<script src="{{ asset('js/revision/cargar-estados.js') }}"></script>

<script>
     
function mostrarNotificacion(mensaje, tipo = 'info') {     
    let mensajeCorto = mensaje;
    if (mensaje.length > 50) {
        mensajeCorto = mensaje.substring(0, 47) + '...';
    }
    
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 p-3 rounded-lg shadow-lg z-50 text-white transition-all duration-300 max-w-xs text-sm opacity-0 ${
        tipo === 'success' ? 'bg-green-500' :
        tipo === 'warning' ? 'bg-yellow-500' :
        tipo === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    div.textContent = mensajeCorto;
    
    document.body.appendChild(div);
    
    setTimeout(() => div.classList.remove('opacity-0'), 100);
 
    setTimeout(() => {
        div.classList.add('opacity-0');
        setTimeout(() => document.body.removeChild(div), 300);
    }, 2500);
}

window.mostrarNotificacion = mostrarNotificacion;

function toggleSection(sectionName) {
    const content = document.getElementById(sectionName + '-content');
    const icon = document.getElementById(sectionName + '-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function cambiarOrdenHistorial(orden) {
    const url = new URL(window.location);
    url.searchParams.set('orden_historial', orden);
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    if (tramiteId) {
        window.revisionDigitalEstados = new RevisionDigitalEstados(tramiteId);
    }
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.evaluar-archivo-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.evaluar-archivo-btn');
            const archivoId = button.getAttribute('data-archivo-id');
            const decision = button.getAttribute('data-decision');
            
            console.log('Botón clickeado:', archivoId, decision);
            
            if (window.archivosEvaluacion && archivoId && decision) {
                window.archivosEvaluacion.evaluarArchivo(archivoId, decision);
            }
        }
    });
});
</script>
@endsection
