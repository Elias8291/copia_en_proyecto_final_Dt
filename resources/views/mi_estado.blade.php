@extends('layouts.app')

@section('title', 'Mis Datos')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <!-- Header -->
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Mis Datos</h1>
                        <p class="text-base text-gray-500 mt-1">Información personal y del último trámite realizado</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($proveedor)
                <!-- Información del Proveedor -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-sm font-medium text-blue-800">Razón Social:</span>
                            <p class="text-blue-900 font-semibold">{{ $proveedor->razon_social ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-blue-800">RFC:</span>
                            <p class="text-blue-900 font-mono">{{ $proveedor->rfc ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-blue-800">Estado en Padrón:</span>
                            <p class="text-blue-900 font-semibold">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($proveedor->estado_padron === 'vigente') bg-green-100 text-green-800 @elseif($proveedor->estado_padron === 'vencido') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst($proveedor->estado_padron ?? 'Desconocido') }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-blue-800">Tipo de Persona:</span>
                            <p class="text-blue-900 font-semibold">
                                @if($proveedor->tipo_persona === 'Moral')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded">Moral</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Física</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if($ultimoTramite && $viewModel)
                    <!-- Información del Último Trámite -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center space-x-3 mb-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-amber-800">Último Trámite Realizado</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm font-medium text-amber-800">Tipo de Trámite:</span>
                                <p class="text-amber-900">{{ $ultimoTramite->tipo_tramite }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-amber-800">Estado:</span>
                                <p class="text-amber-900">{{ $ultimoTramite->status }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-amber-800">Fecha:</span>
                                <p class="text-amber-900">{{ $ultimoTramite->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Separador -->
                    <x-ui.separador-simple margin="my-8" color="border-indigo-300" />

                                        <!-- Datos del Último Trámite -->
                    <div class="mb-8">
                        <!-- Datos Generales -->
                        <div class="mb-8">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                @include('components.forms.datos-generales', [
                                    'editable' => false, 
                                    'datosConstancia' => $viewModel
                                ])
                            </div>
                        </div>

                        <x-ui.separador-simple margin="my-8" color="border-emerald-300" />

                        <!-- Actividades Económicas -->
                        <div class="mb-8">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                @include('components.forms.actividades-economicas', [
                                    'editable' => false,
                                    'datos' => $viewModel->getActividadesForm()
                                ])
                            </div>
                        </div>

                        <x-ui.separador-simple margin="my-8" color="border-amber-300" />

                        <!-- Domicilio -->
                        <div class="mb-8">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                @include('components.forms.domicilio', [
                                    'editable' => false, 
                                    'datosConstancia' => $viewModel
                                ])
                            </div>
                        </div>

                        @if($viewModel->isPersonaMoral())
                            <x-ui.separador-simple margin="my-8" color="border-purple-300" />

                            <!-- Constitución -->
                            <div class="mb-8">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                    @include('components.forms.constitucion', [
                                        'editable' => false,
                                        'datos' => $viewModel->getConstitucionForm()
                                    ])
                                </div>
                            </div>

                            <x-ui.separador-simple margin="my-8" color="border-rose-300" />

                            <!-- Accionistas -->
                            <div class="mb-8">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                    @include('components.forms.accionistas', [
                                        'editable' => false,
                                        'datos' => $viewModel->getAccionistasForm()
                                    ])
                                </div>
                            </div>

                            <x-ui.separador-simple margin="my-8" color="border-cyan-300" />

                            <!-- Apoderado Legal -->
                            <div class="mb-8">
                                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                    @include('components.forms.apoderado', [
                                        'editable' => false,
                                        'datos' => $viewModel->getApoderadoForm()
                                    ])
                                </div>
                            </div>
                        @endif

                        <x-ui.separador-simple margin="my-8" color="border-slate-400" />

                        <!-- Archivos -->
                        <div class="mb-6">
                            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                                @php
                                    $archivos = $viewModel->getArchivos();
                                    $archivosArray = is_array($archivos) ? $archivos : $archivos->toArray();
                                @endphp
                                @include('components.forms.archivos-dinamicos', [
                                    'editable' => false, 
                                    'archivosRequeridos' => [],
                                    'tipoPersona' => $viewModel->isPersonaMoral() ? 'Moral' : 'Física',
                                    'archivosCargados' => $archivosArray,
                                    'soloLectura' => true
                                ])
                            </div>
                        </div>
                    </div>

                    <!-- Historial de Trámites -->
                    <div class="mt-12">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Historial de Trámites</h2>
                        
                        @if($historialTramites->count() > 0)
                            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                                <div class="p-6">
                                    <ol class="relative border-s border-gray-200">
                                        @foreach($historialTramites as $index => $tramite)
                                            <li class="{{ $index < count($historialTramites) - 1 ? 'mb-10' : '' }} ms-6">
                                                <span class="absolute flex items-center justify-center w-6 h-6 bg-[#9d2449]/10 rounded-full -start-3 ring-8 ring-white border-2 border-[#9d2449]/20">
                                                    <svg class="w-3 h-3 text-[#9d2449]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                                    </svg>
                                                </span>
                                                
                                                <div class="flex items-center mb-1">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $tramite->datosGenerales->first()->razon_social ?? 'Sin datos' }}</h3>
                                                    <span class="bg-[#9d2449]/10 text-[#9d2449] text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm ms-3">
                                                        {{ ucfirst($tramite->tipo_tramite) }}
                                                    </span>
                                                    @php
                                                        $estadoColors = [
                                                            'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                                            'Revision_Digital' => 'bg-blue-100 text-blue-800',
                                                            'Revision_Presencial' => 'bg-purple-100 text-purple-800',
                                                            'Revision_Domiciliaria' => 'bg-indigo-100 text-indigo-800',
                                                            'Para_Correccion' => 'bg-orange-100 text-orange-800',
                                                            'Aprobado' => 'bg-green-100 text-green-800',
                                                            'Rechazado' => 'bg-red-100 text-red-800',
                                                            'Cancelado' => 'bg-gray-100 text-gray-800'
                                                        ];
                                                        $estadoColor = $estadoColors[$tramite->status] ?? 'bg-gray-100 text-gray-800';
                                                        $estadoLabels = [
                                                            'Pendiente' => 'Pendiente',
                                                            'Revision_Digital' => 'Revisión Digital',
                                                            'Revision_Presencial' => 'Revisión Presencial',
                                                            'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                                                            'Para_Correccion' => 'Para Corrección',
                                                            'Aprobado' => 'Aprobado',
                                                            'Rechazado' => 'Rechazado',
                                                            'Cancelado' => 'Cancelado'
                                                        ];
                                                        $estadoLabel = $estadoLabels[$tramite->status] ?? $tramite->status;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoColor }} ms-2">
                                                        {{ $estadoLabel }}
                                                    </span>
                                                </div>
                                                
                                                <time class="block mb-2 text-sm font-normal leading-none text-gray-400">
                                                    {{ $tramite->created_at->format('d/m/Y H:i') }}
                                                </time>
                                                
                                                @if($tramite->observaciones && !empty(trim($tramite->observaciones)))
                                                    <p class="mb-4 text-base font-normal text-gray-500">
                                                        {{ $tramite->observaciones }}
                                                    </p>
                                                @endif
                                                
                                                <!-- Información de los Oficios -->
                                                @if($tramite->oficios && count($tramite->oficios) > 0)
                                                    <div class="mb-4">
                                                        <div class="flex items-center mb-3">
                                                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-blue-800">
                                                                Oficios Generados ({{ count($tramite->oficios) }})
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="space-y-2">
                                                            @foreach($tramite->oficios as $oficio)
                                                                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                                    <div class="flex items-center justify-between">
                                                                        <div class="flex items-center space-x-2">
                                                                            <span class="text-sm font-medium text-blue-800">
                                                                                {{ $oficio['numero_oficio'] }}
                                                                            </span>
                                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                                {{ $oficio['estado'] }}
                                                                            </span>
                                                                        </div>
                                                                        @if($oficio['url'])
                                                                            <a href="{{ $oficio['url'] }}" 
                                                                               target="_blank"
                                                                               class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 border border-blue-200 rounded-md hover:bg-blue-200 hover:text-blue-800 transition-colors duration-200">
                                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                </svg>
                                                                                Descargar
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                    <div class="mt-2 text-xs text-blue-600">
                                                                        Generado: {{ \Carbon\Carbon::parse($oficio['fecha_oficio'])->format('d/m/Y H:i') }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('tramites.estado', $tramite->id) }}" 
                                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-[#9d2449] bg-white border border-[#9d2449]/20 rounded-lg hover:bg-[#9d2449]/5 hover:text-[#8a1f40] focus:z-10 focus:ring-4 focus:outline-none focus:ring-[#9d2449]/20 focus:text-[#8a1f40] transition-all duration-200">
                                                        <svg class="w-3.5 h-3.5 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/>
                                                            <path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                                                        </svg>
                                                        Ver estado
                                                    </a>
                                                    
                                                    <a href="{{ route('tramites.historico', $tramite->id) }}" 
                                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-200 transition-all duration-200">
                                                        <svg class="w-3.5 h-3.5 me-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Ver datos
                                                    </a>
                                                    
                                                    @if($tramite->oficios && count($tramite->oficios) > 0)
                                                        <a href="{{ route('oficios.por-tramite', $tramite->id) }}" 
                                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-200 transition-all duration-200">
                                                            <svg class="w-3.5 h-3.5 me-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            Ver todos los oficios ({{ count($tramite->oficios) }})
                                                        </a>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-gray-500 font-medium">No hay trámites en el historial</p>
                                <p class="text-gray-400 text-sm mt-1">Realiza tu primer trámite para verlo aquí</p>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Mensaje cuando no hay trámites -->
                    <div class="text-center py-12">
                        <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay trámites realizados</h3>
                        <p class="text-gray-500">Aún no has realizado ningún trámite. Ve a la sección de Trámites para comenzar.</p>
                        <div class="mt-4">
                            <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Ir a Trámites
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <!-- Mensaje cuando no hay proveedor -->
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos de proveedor</h3>
                    <p class="text-gray-500">No se encontraron datos de proveedor asociados a tu usuario.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 