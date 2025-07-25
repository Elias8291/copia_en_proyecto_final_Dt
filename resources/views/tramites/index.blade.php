@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#9D2449]/5 via-white to-[#B91C1C]/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden border border-gray-200/70">
                <div class="p-4 sm:p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="bg-gradient-to-br from-[#9D2449] via-[#B91C1C] to-[#7a1d37] rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Trámites Disponibles</h1>
                                <p class="text-xs sm:text-sm text-gray-500">Seleccione el tipo de trámite que desea realizar</p>
                            </div>
                        </div>

                        @if ($proveedor)
                            <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 bg-[#9D2449] rounded-full"></div>
                                        <span
                                            class="text-xs font-medium text-gray-600 truncate max-w-32 sm:max-w-none">{{ $proveedor->razon_social }}</span>
                                    </div>
                                    @php
                                        $estadoColor = match ($proveedor->estado_padron ?? 'Sin Estado') {
                                            'Activo' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'Pendiente' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'Vencido', 'Inactivo' => 'bg-red-100 text-red-700 border-red-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border {{ $estadoColor }} ml-2">
                                        {{ $proveedor->estado_padron ?? 'Sin Estado' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($globalTramites['tiene_tramite_pendiente'] && $globalTramites['tramite_pendiente'])
                    @php
                        $detalles = $globalTramites['tramite_pendiente'];
                        $tramite = $detalles['tramite'];
                        
                        // Variables para la vista estado
                        $tramite_id = str_pad($tramite->id, 4, '0', STR_PAD_LEFT);
                        $estado = $tramite->estado;
                        $paso_actual = $detalles['estado_descripcion'];
                        $historial = []; // Puedes agregar historial si está disponible
                    @endphp
                    
                    @include('tramites.estado', [
                        'tramite_id' => $tramite_id,
                        'estado' => $estado,
                        'paso_actual' => $paso_actual,
                        'historial' => $historial
                    ])
                @endif

                @if (!($globalTramites['tiene_tramite_pendiente'] ?? false))
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 p-4 sm:p-6 lg:mb-12">
                        @include('tramites.partials.tramite-card', [
                            'tipo' => 'inscripcion',
                            'titulo' => 'Inscripción al Padrón',
                            'descripcion' =>
                                'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                            'disponible' => $globalTramites['inscripcion'] ?? false,
                            'colorFrom' => 'from-[#9D2449]',
                            'colorTo' => 'to-[#B91C1C]',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
                        ])

                        @include('tramites.partials.tramite-card', [
                            'tipo' => 'renovacion',
                            'titulo' => 'Renovación de Registro',
                            'descripcion' =>
                                'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                            'disponible' => $globalTramites['renovacion'] ?? false,
                            'colorFrom' => 'from-[#B91C1C]',
                            'colorTo' => 'to-[#DC2626]',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>',
                        ])

                        @include('tramites.partials.tramite-card', [
                            'tipo' => 'actualizacion',
                            'titulo' => 'Actualización de Datos',
                            'descripcion' =>
                                'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                            'disponible' => $globalTramites['actualizacion'] ?? false,
                            'colorFrom' => 'from-[#DC2626]',
                            'colorTo' => 'to-[#EF4444]',
                            'icon' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>',
                        ])
                    </div>
                @endif

                @if (!($globalTramites['tiene_tramite_pendiente'] ?? false))
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 p-4 sm:p-6">
                        <div class="bg-white rounded-lg sm:rounded-xl shadow-md sm:shadow-lg border border-gray-200/70 p-4 sm:p-6">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                                <div class="bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-lg sm:rounded-xl p-1.5 sm:p-2 shadow-md mr-2 sm:mr-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Requisitos Generales
                            </h3>
                            <div class="space-y-2 sm:space-y-3">
                                <div
                                    class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-[#9D2449] rounded-full mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Constancia de Situación Fiscal vigente
                                        (SAT)</p>
                                </div>
                                <div
                                    class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-[#9D2449] rounded-full mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Documentación legal completa y actualizada
                                    </p>
                                </div>
                                <div
                                    class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-[#9D2449] rounded-full mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Información de contacto verificable</p>
                                </div>
                                <div
                                    class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-[#9D2449] rounded-full mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Actividades económicas definidas</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg sm:rounded-xl shadow-md sm:shadow-lg border border-gray-200/70 p-4 sm:p-6">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                                <div class="bg-gradient-to-br from-[#B91C1C] to-[#DC2626] rounded-lg sm:rounded-xl p-1.5 sm:p-2 shadow-md mr-2 sm:mr-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                Proceso de Trámite
                            </h3>
                            <div class="space-y-2 sm:space-y-3">
                                <div
                                    class="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div
                                        class="w-5 h-5 sm:w-6 sm:h-6 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        1</div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Cargar constancia de situación fiscal</p>
                                </div>
                                <div
                                    class="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div
                                        class="w-5 h-5 sm:w-6 sm:h-6 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        2</div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Completar formulario con datos precargados
                                    </p>
                                </div>
                                <div
                                    class="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div
                                        class="w-5 h-5 sm:w-6 sm:h-6 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        3</div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Adjuntar documentos requeridos</p>
                                </div>
                                <div
                                    class="flex items-center space-x-2 sm:space-x-3 p-2 sm:p-3 rounded-lg bg-gray-50 hover:bg-gray-100">
                                    <div
                                        class="w-5 h-5 sm:w-6 sm:h-6 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                        4</div>
                                    <p class="text-gray-700 text-xs sm:text-sm font-medium">Revisión y aprobación administrativa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($proveedor && isset($globalTramites['estado_vigencia']))
                    @php
                        $estadoVigencia = $globalTramites['estado_vigencia'];
                    @endphp

                    @if ($estadoVigencia === 'por_vencer')
                        <div
                            class="m-4 sm:m-6 lg:m-8 p-4 sm:p-6 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-300 rounded-lg sm:rounded-2xl shadow-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <p class="text-base sm:text-lg font-bold text-amber-800">Su registro está próximo a vencer</p>
                                    <p class="text-amber-700 mt-1 text-sm">
                                        Realice la renovación antes de la fecha de vencimiento para mantener activo su
                                        estado en el padrón.
                                        @if ($proveedor->fecha_vencimiento_padron)
                                            Vence el {{ $proveedor->fecha_vencimiento_padron->format('d/m/Y') }}.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($estadoVigencia === 'vencido')
                        <div
                            class="m-4 sm:m-6 lg:m-8 p-4 sm:p-6 bg-gradient-to-r from-red-50 to-pink-50 border border-red-300 rounded-lg sm:rounded-2xl shadow-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-red-500 to-pink-500 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3 sm:ml-4">
                                    <p class="text-base sm:text-lg font-bold text-red-800">Su registro ha vencido</p>
                                    <p class="text-red-700 mt-1 text-sm">
                                        Debe realizar una nueva inscripción para reactivar su estado en el padrón de
                                        proveedores.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if (!$proveedor)
                    <div
                        class="m-4 sm:m-6 lg:m-8 p-4 sm:p-6 bg-gradient-to-r from-[#9D2449]/10 to-[#B91C1C]/10 border border-[#9D2449]/20 rounded-lg sm:rounded-2xl shadow-lg backdrop-blur-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-lg sm:rounded-xl flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-base sm:text-lg font-bold text-[#9D2449]">Nuevo en el sistema</p>
                                <p class="text-slate-700 mt-1 text-sm">
                                    Complete el proceso de inscripción para registrarse como proveedor y acceder a todos los
                                    servicios del padrón.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endsection
