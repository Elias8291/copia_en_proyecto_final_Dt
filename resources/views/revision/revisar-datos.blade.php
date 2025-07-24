@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/revision-panels.css') }}">
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Revisión de Datos del Trámite</h1>
                            <div class="flex items-center space-x-4 mt-1">
                                <p class="text-sm text-gray-500">ID: #{{ $tramite->id }}</p>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                <p class="text-sm text-gray-500">
                                    {{ $tramite->tipo_tramite === 'Inscripcion' ? 'Inscripción' : ($tramite->tipo_tramite === 'Renovacion' ? 'Renovación' : 'Actualización') }}
                                </p>
                                <span class="w-1 h-1 bg-gray-400 rounded-full"></span>
                                <p class="text-sm text-gray-500">{{ $tramite->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span
                            class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium shadow-sm border
                            {{ $tramite->estado === 'Pendiente'
                                ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                : ($tramite->estado === 'En_Revision'
                                    ? 'bg-blue-100 text-blue-800 border-blue-200'
                                    : ($tramite->estado === 'Aprobado'
                                        ? 'bg-green-100 text-green-800 border-green-200'
                                        : 'bg-red-100 text-red-800 border-red-200')) }}">
                            {{ str_replace('_', ' ', $tramite->estado) }}
                        </span>
                        <a href="{{ route('revision.index') }}"
                            class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9D2449] transition-all duration-200 hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Datos Generales -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Datos Generales</h3>
                    </div>

                    @include('revision.partials.datos-generales', [
                        'tramite' => $tramite,
                        'proveedor' => $tramite->proveedor,
                        'editable' => true,
                    ])
                </div>

                <div class="flex items-center justify-center py-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-6">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <!-- Domicilio -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Domicilio</h3>
                    </div>

                    @include('revision.partials.domicilio', [
                        'tramite' => $tramite,
                        'direccion' => $tramite->direcciones->first(),
                        'editable' => true,
                    ])
                </div>

                <div class="flex items-center justify-center py-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-6">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <!-- Actividades -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Actividades</h3>
                    </div>

                    @include('revision.partials.actividades', [
                        'tramite' => $tramite,
                        'actividades' => $tramite->actividades ?? [],
                        'editable' => true,
                    ])
                </div>

                <div class="flex items-center justify-center py-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                    <div class="px-6">
                        <div class="w-2 h-2 bg-slate-400 rounded-full"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent"></div>
                </div>

                <!-- Documentos -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Documentos</h3>
                    </div>

                    @include('revision.partials.documentos', [
                        'tramite' => $tramite,
                        'documentos' => $tramite->archivos ?? [],
                        'editable' => true,
                    ])
                </div>

                <div class="flex items-center justify-center py-8">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-[#9D2449] to-transparent opacity-50">
                    </div>
                    <div class="px-8">
                        <div class="w-3 h-3 bg-[#9D2449] rounded-full shadow-lg"></div>
                    </div>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-[#9D2449] to-transparent opacity-50">
                    </div>
                </div>

                <!-- Acciones de Revisión -->
                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Acciones de Revisión</h3>
                            <p class="text-sm text-gray-500 mt-1">Seleccione la acción a realizar con este trámite</p>
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mt-4">
                                <!-- Aprobar -->
                                <button type="button"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 focus:outline-none transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4 L19 7" />
                                    </svg>
                                    Aprobar Trámite
                                </button>

                                <!-- Rechazar -->
                                <button type="button"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2xl" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Rechazar Trámite
                                </button>

                                <!-- Solicitar Correcciones -->
                                <button type="button"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white rounded-xl hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2xl" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Solicitar Correcciones
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
