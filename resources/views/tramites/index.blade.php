@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header Section -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Trámites Disponibles</h1>
                        <p class="text-sm text-slate-600 mt-1">Seleccione el tipo de trámite que desea realizar</p>
                    </div>
                </div>

                @if($proveedor)
                    <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                        <div class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Proveedor</div>
                        <div class="text-sm font-medium text-slate-800 mt-1">{{ $proveedor->razon_social }}</div>
                        <div class="flex items-center mt-1">
                            @php
                                $estadoColor = match($proveedor->estado_padron ?? 'Sin Estado') {
                                    'Activo' => 'bg-emerald-100 text-emerald-800',
                                    'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'Vencido', 'Inactivo' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $estadoColor }}">
                                {{ $proveedor->estado_padron ?? 'Sin Estado' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mensaje informativo -->
            @if(isset($globalTramites['message']) && $globalTramites['message'])
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">Información importante</p>
                            <p class="text-sm text-blue-700 mt-1">{{ $globalTramites['message'] }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tramites Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {{-- Inscripción --}}
            @include('tramites.partials.tramite-card', [
                'tipo' => 'inscripcion',
                'titulo' => 'Inscripción al Padrón',
                'descripcion' => 'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                'disponible' => $globalTramites['inscripcion'] ?? false,
                'colorFrom' => 'from-emerald-600',
                'colorTo' => 'to-emerald-700',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>'
            ])

            {{-- Renovación --}}
            @include('tramites.partials.tramite-card', [
                'tipo' => 'renovacion',
                'titulo' => 'Renovación de Registro',
                'descripcion' => 'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                'disponible' => $globalTramites['renovacion'] ?? false,
                'colorFrom' => 'from-blue-600',
                'colorTo' => 'to-blue-700',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'
            ])

            {{-- Actualización --}}
            @include('tramites.partials.tramite-card', [
                'tipo' => 'actualizacion',
                'titulo' => 'Actualización de Datos',
                'descripcion' => 'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                'disponible' => $globalTramites['actualizacion'] ?? false,
                'colorFrom' => 'from-amber-600',
                'colorTo' => 'to-amber-700',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
            ])
        </div>

        <!-- Información adicional -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Requisitos generales -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Requisitos Generales
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <span class="inline-block w-2 h-2 bg-[#9D2449] rounded-full mt-2 flex-shrink-0"></span>
                        <p class="text-sm text-slate-700">Constancia de Situación Fiscal vigente (SAT)</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="inline-block w-2 h-2 bg-[#9D2449] rounded-full mt-2 flex-shrink-0"></span>
                        <p class="text-sm text-slate-700">Documentación legal completa y actualizada</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="inline-block w-2 h-2 bg-[#9D2449] rounded-full mt-2 flex-shrink-0"></span>
                        <p class="text-sm text-slate-700">Información de contacto verificable</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="inline-block w-2 h-2 bg-[#9D2449] rounded-full mt-2 flex-shrink-0"></span>
                        <p class="text-sm text-slate-700">Actividades económicas definidas</p>
                    </div>
                </div>
            </div>

            <!-- Proceso de trámite -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    Proceso de Trámite
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-[#9D2449] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">1</div>
                        <p class="text-sm text-slate-700">Cargar constancia de situación fiscal</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-[#9D2449] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">2</div>
                        <p class="text-sm text-slate-700">Completar formulario con datos precargados</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-[#9D2449] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">3</div>
                        <p class="text-sm text-slate-700">Adjuntar documentos requeridos</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-[#9D2449] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">4</div>
                        <p class="text-sm text-slate-700">Revisión y aprobación administrativa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estado de vigencia (si aplica) -->
        @if($proveedor && isset($globalTramites['estado_vigencia']))
            @php
                $estadoVigencia = $globalTramites['estado_vigencia'];
            @endphp
            
            @if($estadoVigencia === 'por_vencer')
                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-amber-800">Su registro está próximo a vencer</p>
                            <p class="text-sm text-amber-700 mt-1">
                                Realice la renovación antes de la fecha de vencimiento para mantener activo su estado en el padrón.
                                @if($proveedor->fecha_vencimiento_padron)
                                    Vence el {{ $proveedor->fecha_vencimiento_padron->format('d/m/Y') }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($estadoVigencia === 'vencido')
                <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">Su registro ha vencido</p>
                            <p class="text-sm text-red-700 mt-1">
                                Debe realizar una nueva inscripción para reactivar su estado en el padrón de proveedores.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Sin proveedor asociado --}}
        @if(!$proveedor)
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">Nuevo en el sistema</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Complete el proceso de inscripción para registrarse como proveedor y acceder a todos los servicios del padrón.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
