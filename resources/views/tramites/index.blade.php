@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 bg-gradient-to-b from-white to-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        @php
            // Verificar si hay trámite pendiente
            $tieneTramitePendiente = false;
            $tramitePendiente = null;
            $tipoTramitePendiente = null;
            $rfc = auth()->user()->rfc ?? null;

            if ($rfc) {
                $rfcService = app(\App\Services\RfcProveedorService::class);
                $tramitePendiente = $rfcService->obtenerTramitePendiente($rfc);
                $tieneTramitePendiente = $tramitePendiente !== null;
                if ($tramitePendiente) {
                    $tipoTramitePendiente = strtolower($tramitePendiente->tipo_tramite);
                }
            }

            $brandFrom = '#9d2449';
            $brandVia  = '#8a1f40';
            $brandTo   = '#7a1a37';
        @endphp

        {{-- Mensajes de estado --}}
        <div class="space-y-3 mb-6">
            @if(session('warning'))
                <div class="rounded-xl border border-amber-200/70 bg-amber-50/70 p-4 shadow-sm backdrop-blur supports-backdrop-blur">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500 text-white shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        <p class="text-sm text-amber-800/90">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="rounded-xl border border-emerald-200/70 bg-emerald-50 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm text-emerald-800/90">{{ session('success') }}</p>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl border border-rose-200/70 bg-rose-50 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 text-white shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm text-rose-700/90">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Encabezado principal --}}
        <div class="bg-white/80 border border-gray-200 rounded-2xl shadow-sm backdrop-blur supports-backdrop-blur">
            <div class="p-6 sm:p-8 border-b border-gray-200/70">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="rounded-2xl p-3 shadow-lg bg-gradient-to-br from-[{{ $brandFrom }}] via-[{{ $brandVia }}] to-[{{ $brandTo }}]">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">Trámites Disponibles</h1>
                            <p class="mt-1 text-sm sm:text-base text-gray-600">
                                @if($tieneTramitePendiente)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-amber-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @if($tipoTramitePendiente)
                                            Tiene un trámite de {{ ucfirst($tipoTramitePendiente) }} en proceso
                                        @else
                                            Tiene un trámite en proceso
                                        @endif
                                    </span>
                                @else
                                    Seleccione el tipo de trámite que desea realizar
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($tieneTramitePendiente)
                        <div class="lg:w-1/2">
                            <div class="rounded-xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 p-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-amber-900">
                                            @if($tipoTramitePendiente)
                                                Tiene un trámite de <strong>{{ ucfirst($tipoTramitePendiente) }}</strong> en proceso
                                            @else
                                                Tiene un trámite en proceso
                                            @endif
                                        </p>
                                        <p class="text-xs text-amber-700">Consulte el estado de su trámite actual antes de iniciar uno nuevo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tarjetas de trámites --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 p-6 sm:p-8">
                {{-- Inscripción al Padrón --}}
                @include('tramites.partials.tramite-card', [
                    'tipo' => 'inscripcion',
                    'tramites' => $tramites,
                    'title' => 'Inscripción al Padrón',
                    'description' => 'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                    'gradient' => 'from-[#9d2449] to-[#8a1f40]',
                    'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>'
                ])

                {{-- Renovación de Registro --}}
                @include('tramites.partials.tramite-card', [
                    'tipo' => 'renovacion',
                    'tramites' => $tramites,
                    'title' => 'Renovación de Registro',
                    'description' => 'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                    'gradient' => 'from-[#8a1f40] to-[#7a1a37]',
                    'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>'
                ])

                {{-- Actualización de Datos --}}
                @include('tramites.partials.tramite-card', [
                    'tipo' => 'actualizacion',
                    'tramites' => $tramites,
                    'title' => 'Actualización de Datos',
                    'description' => 'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                    'gradient' => 'from-[#9d2449] to-[#7a1a37]',
                    'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>'
                ])
            </div>
        </div>

        {{-- Historial de Trámites --}}
        <div class="mt-8">
            <div class="rounded-2xl border border-gray-200 bg-white/80 shadow-sm backdrop-blur supports-backdrop-blur">
                <div class="p-6 sm:p-8 border-b border-gray-200/70">
                    <div class="flex items-center gap-4">
                        <div class="rounded-2xl p-3 shadow-lg bg-gradient-to-br from-[{{ $brandFrom }}] via-[{{ $brandVia }}] to-[{{ $brandTo }}]">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Historial de Trámites</h2>
                            <p class="text-sm sm:text-base text-gray-600">Registro de todos sus trámites realizados</p>
                        </div>
                    </div>
                </div>

                @if($historialTramites->isNotEmpty())
                    <div class="p-6 sm:p-8">
                        <ol class="relative border-s border-gray-200">
                            @foreach($historialTramites as $index => $tramite)
                                <li class="ms-6 {{ $index < count($historialTramites) - 1 ? 'mb-10' : '' }}">
                                    <span class="absolute -start-3 flex h-7 w-7 items-center justify-center rounded-full border-2 border-[{{ $brandFrom }}] bg-[{{ $brandFrom }}]/10 text-[{{ $brandFrom }}] ring-8 ring-white">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                                    </span>

                                    <div class="mb-1 flex flex-wrap items-center gap-2">
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $tramite['razon_social'] }}</h3>

                                        <span class="inline-flex items-center rounded-full bg-[{{ $brandFrom }}]/10 px-2.5 py-0.5 text-xs font-medium text-[{{ $brandFrom }}]">
                                            {{ ucfirst($tramite['tipo_tramite']) }}
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
                                            $estadoColor = $estadoColors[$tramite['status']] ?? 'bg-gray-100 text-gray-800';
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
                                            $estadoLabel = $estadoLabels[$tramite['status']] ?? $tramite['status'];
                                        @endphp

                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] sm:text-xs font-medium {{ $estadoColor }}">
                                            {{ $estadoLabel }}
                                        </span>
                                    </div>

                                    <time class="block text-xs sm:text-sm font-normal leading-none text-gray-500">{{ $tramite['created_at']->format('d/m/Y H:i') }}</time>

                                    @if(isset($tramite['observaciones']) && !empty(trim($tramite['observaciones'])))
                                        <p class="mt-3 text-sm sm:text-base text-gray-600">{{ $tramite['observaciones'] }}</p>
                                    @endif

                                    {{-- Información del Oficio --}}
                                    @if(isset($tramite['oficio']) && $tramite['oficio'])
                                        <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                <div class="flex items-center gap-2">
                                                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span class="text-sm font-medium text-blue-900">Oficio: {{ $tramite['oficio']['numero_oficio'] }}</span>
                                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">{{ $tramite['oficio']['estado'] }}</span>
                                                </div>
                                                @if($tramite['oficio']['url'])
                                                    <a href="{{ $tramite['oficio']['url'] }}" target="_blank" class="inline-flex items-center rounded-lg border border-blue-300 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                                        <svg class="mr-2 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        Descargar
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="mt-2 text-xs text-blue-700">Generado: {{ \Carbon\Carbon::parse($tramite['oficio']['fecha_oficio'])->format('d/m/Y H:i') }}</div>
                                        </div>
                                    @endif

                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('tramites.estado', $tramite['id']) }}" class="inline-flex items-center rounded-xl border border-[{{ $brandFrom }}]/20 bg-white px-4 py-2 text-sm font-medium text-[{{ $brandFrom }}] transition hover:bg-[{{ $brandFrom }}]/5 hover:text-[{{ $brandVia }}] focus:outline-none focus:ring-4 focus:ring-[{{ $brandFrom }}]/20">
                                            <svg class="mr-2.5 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M14.707 7.793a1 1 0 0 0-1.414 0L11 10.086V1.5a1 1 0 0 0-2 0v8.586L6.707 7.793a1 1 0 1 0-1.414 1.414l4 4a1 1 0 0 0 1.416 0l4-4a1 1 0 0 0-.002-1.414Z"/><path d="M18 12h-2.55l-2.975 2.975a3.5 3.5 0 0 1-4.95 0L4.55 12H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2Zm-3 5a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/></svg>
                                            Ver detalles
                                        </a>

                                        @if(isset($tramite['oficio']) && $tramite['oficio'] && $tramite['oficio']['url'])
                                            <a href="{{ route('oficios.por-tramite', $tramite['id']) }}" class="inline-flex items-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                                <svg class="mr-2.5 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                Ver oficios
                                            </a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @else
                    {{-- Mensaje cuando no hay historial --}}
                    <div class="p-8 text-center">
                        <div class="mx-auto flex max-w-md items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-lg font-semibold text-gray-900">Sin historial de trámites</h3>
                                <p class="text-sm text-gray-600">Aún no ha realizado ningún trámite. Comience seleccionando uno de los tipos disponibles arriba.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Mejora visual para las tarjetas del include: tramites.partials.tramite-card --}}
{{-- Ejemplo de estilos sugeridos (ajuste dentro del partial):
<div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white/90 p-5 shadow-sm transition hover:shadow-lg focus-within:ring-4 focus-within:ring-[#9d2449]/20">
    <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-gradient-to-br from-[#9d2449]/10 via-[#8a1f40]/10 to-[#7a1a37]/10 blur-2xl"></div>
    <div class="flex items-start gap-4">
        <div class="shrink-0 rounded-xl p-3 shadow bg-gradient-to-br {{ $gradient }}">
            {!! $icon !!}
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
        </div>
    </div>
    <div class="mt-4 flex items-center gap-2">
        <a href="{{ route('tramites.crear', ['tipo' => $tipo]) }}" class="inline-flex items-center rounded-xl bg-gradient-to-r {{ $gradient }} px-4 py-2 text-sm font-semibold text-white shadow transition group-hover:translate-y-[-1px]">
            Iniciar trámite
        </a>
        @if(isset($tramites[$tipo]))
            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs text-gray-600">{{ $tramites[$tipo] }} en curso</span>
        @endif
    </div>
</div>
--}}
@endsection
