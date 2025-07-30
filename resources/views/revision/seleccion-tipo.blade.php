@extends('layouts.app')

@section('title', 'Tipos de Revisión Disponibles')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div class="w-full max-w-full mx-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200/70 p-8 -mt-4">
                <div class="p-6 border-b border-gray-200/70">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Tipos de Revisión Disponibles</h1>
                                <p class="text-base text-gray-500 mt-1">Seleccione el tipo de revisión que desea realizar para el trámite #{{ $tramite->id }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl border border-gray-200/50 p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'Proveedor N/A') }}
                                            </span>
                                            <span class="text-xs text-gray-500">Trámite #{{ $tramite->id }}</span>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $estadoColor = match ($tramite->estado) {
                                            'Pendiente' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'En_Revision' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Aprobado' => 'bg-green-50 text-green-700 border-green-200',
                                            'Por_Cotejar' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border {{ $estadoColor }} ml-3">
                                        {{ str_replace('_', ' ', $tramite->estado) }}
                                    </span>
                                </div>
                            </div>
                            
                            <a href="{{ route('revision.index') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        @php
                            $isPendiente = $tramite->estado === 'Pendiente';
                            $isParaCorreccion = $tramite->estado === 'Para_Correcion';
                            $isDisponible = $isPendiente && !$isParaCorreccion;
                            $botonTexto = $isParaCorreccion ? 'Revisión Bloqueada' : ($isPendiente ? 'Iniciar Revisión' : 'No Disponible');
                            $estadoTexto = $isParaCorreccion ? 'Bloqueado' : ($isPendiente ? 'Por Realizar' : 'Realizado');
                            $estadoColor = $isParaCorreccion ? 'bg-red-100 text-red-700' : ($isPendiente ? 'bg-gray-100 text-gray-700' : 'bg-green-100 text-green-700');
                            $isDeshabilitado = !$isPendiente || $isParaCorreccion;
                        @endphp
                        
                        @include('revision.partials.revision-card', [
                            'tipo' => 'revision-digital',
                            'titulo' => 'Revisión Digital',
                            'descripcion' => 'Revisa documentos y datos en línea de forma completa. Valida información del proveedor, actividades económicas y documentos digitalizados.',
                            'disponible' => $isDisponible,
                            'colorFrom' => 'from-gray-700',
                            'colorTo' => 'to-gray-800',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />',
                            'url' => route('revision.revisar', ['tramite' => $tramite->id, 'tipo' => 'revision-digital']),
                            'estado' => $estadoTexto,
                            'estadoColor' => $estadoColor,
                            'estadoIcon' => $isParaCorreccion ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z' : (in_array($tramite->estado, ['Por_Cotejar', 'En_Revision']) ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : ($isPendiente ? 'M13 10V3L4 14h7v7l9-11h-7z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z')),
                            'botonTexto' => $botonTexto,
                            'botonIcon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'deshabilitado' => $isDeshabilitado
                        ])

                        @include('revision.partials.revision-card', [
                            'tipo' => 'cotejo-presencial',
                            'titulo' => 'Cotejo Presencial',
                            'descripcion' => 'Verificar identidad del representante y cotejar documentos físicos originales en las instalaciones.',
                            'disponible' => $tramite->estado === 'Por_Cotejar' && $tramite->estado !== 'Para_Correcion',
                            'colorFrom' => 'from-gray-600',
                            'colorTo' => 'to-gray-700',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />',
                            'url' => $tramite->estado === 'Por_Cotejar' && $tramite->estado !== 'Para_Correcion' ? "/revision/{$tramite->id}/documentos-presencial" : '#',
                            'estado' => $tramite->estado === 'Para_Correcion' ? 'Bloqueado' : ($tramite->estado === 'Por_Cotejar' ? 'Por Realizar' : 'No Disponible'),
                            'estadoColor' => $tramite->estado === 'Para_Correcion' ? 'bg-red-100 text-red-700' : ($tramite->estado === 'Por_Cotejar' ? 'bg-gray-100 text-gray-700' : 'bg-gray-100 text-gray-500'),
                            'estadoIcon' => $tramite->estado === 'Para_Correcion' ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z' : ($tramite->estado === 'Por_Cotejar' ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z' : 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'),
                            'botonTexto' => $tramite->estado === 'Para_Correcion' ? 'Cotejo Bloqueado' : ($tramite->estado === 'Por_Cotejar' ? 'Iniciar Cotejo' : 'No Disponible'),
                            'botonIcon' => $tramite->estado === 'Por_Cotejar' ? 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' : 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                            'deshabilitado' => $tramite->estado !== 'Por_Cotejar' || $tramite->estado === 'Para_Correcion',
                            'documentos' => \App\Services\DocumentosService::getCatalogo2Archivos($tramite),
                            'documentosCount' => \App\Services\DocumentosService::getCatalogo2Count($tramite),
                            'mostrarInfo' => \App\Services\DocumentosService::getCatalogo2() ? true : false,
                            'infoTexto' => \App\Services\DocumentosService::getCatalogo2() ? "Documentos del catálogo: " . \App\Services\DocumentosService::getCatalogo2()->nombre : 'No hay documentos del catálogo 2 disponibles'
                        ])

                        @php
                            $isCotejoDomiciliario = $tramite->estado === 'cotejo_domiciliario';
                            $yaSeHizoPresencial = in_array($tramite->estado, ['cotejo_domiciliario', 'En_Revision', 'Completado']);
                            $isDisponible = $isCotejoDomiciliario;
                            $isDeshabilitado = !$isCotejoDomiciliario;
                            $estadoTexto = $isCotejoDomiciliario ? 'Por Realizar' : ($yaSeHizoPresencial ? 'Realizado' : 'No Disponible');
                            $estadoColor = $isCotejoDomiciliario ? 'bg-gray-100 text-gray-700' : ($yaSeHizoPresencial ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500');
                            $botonTexto = $isCotejoDomiciliario ? 'Ver Ubicación' : 'No Disponible';
                            $url = $isCotejoDomiciliario ? route('revision.cotejo-domiciliario', $tramite->id) : '#';
                            $infoTexto = $isCotejoDomiciliario 
                                ? 'Funcionalidad en desarrollo: Esta opción estará disponible próximamente para realizar verificaciones directamente en el domicilio del proveedor.'
                                : 'El cotejo domiciliario solo está disponible cuando el trámite esté en estado de cotejo domiciliario.';
                        @endphp
                        
                        @include('revision.partials.revision-card', [
                            'tipo' => 'cotejo-domiciliario',
                            'titulo' => 'Cotejo Domiciliario',
                            'descripcion' => 'Verificación en el domicilio del proveedor. Agendar visita domiciliaria y validar instalaciones físicas.',
                            'disponible' => $isDisponible,
                            'colorFrom' => 'from-gray-800',
                            'colorTo' => 'to-gray-900',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
                            'url' => $url,
                            'estado' => $estadoTexto,
                            'estadoColor' => $estadoColor,
                            'estadoIcon' => $isCotejoDomiciliario ? 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
                            'botonTexto' => $botonTexto,
                            'botonIcon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                            'deshabilitado' => $isDeshabilitado,
                            'mostrarInfo' => true,
                            'infoTexto' => $infoTexto
                        ])
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
