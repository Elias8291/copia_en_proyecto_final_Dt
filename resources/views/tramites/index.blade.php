@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#9D2449]/5 via-white to-[#B91C1C]/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden border border-gray-200/70">
                <div class="p-4 sm:p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-gradient-to-br from-[#9D2449] via-[#B91C1C] to-[#7a1d37] rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-md">
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
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 p-4 sm:p-6 lg:mb-12">
                    
                    {{-- Inscripción al Padrón --}}
                    @include('tramites.partials.tramite-card', [
                        'tipo' => 'inscripcion',
                        'tramites' => $tramites,
                        'title' => 'Inscripción al Padrón',
                        'description' => 'Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.',
                        'gradient' => 'from-[#9D2449] to-[#B91C1C]',
                        'actionText' => 'Comenzar Inscripción',
                        'actionUrl' => route('tramites.cargar-constancia'),
                        'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>'
                    ])

                    {{-- Renovación de Registro --}}
                    @include('tramites.partials.tramite-card', [
                        'tipo' => 'renovacion',
                        'tramites' => $tramites,
                        'title' => 'Renovación de Registro',
                        'description' => 'Renueve su registro anual para mantener activo su estado en el padrón de proveedores.',
                        'gradient' => 'from-[#B91C1C] to-[#DC2626]',
                        'actionText' => 'Renovar Registro',
                        'actionUrl' => '#',
                        'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>'
                    ])

                    {{-- Actualización de Datos --}}
                    @include('tramites.partials.tramite-card', [
                        'tipo' => 'actualizacion',
                        'tramites' => $tramites,
                        'title' => 'Actualización de Datos',
                        'description' => 'Modifique su información registrada. Mantenga sus datos siempre actualizados.',
                        'gradient' => 'from-[#DC2626] to-[#EF4444]',
                        'actionText' => 'Actualizar Datos',
                        'actionUrl' => '#',
                        'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>'
                    ])

                </div>

            </div>
        </div>
    </div>
@endsection