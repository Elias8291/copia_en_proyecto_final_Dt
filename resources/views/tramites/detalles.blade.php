@extends('layouts.app')

@section('title', 'Detalles del Trámite')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="w-full max-w-7xl mx-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200/70 p-8 -mt-4 mb-8">
            <div class="p-6 border-b border-gray-200/70">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Detalles del Trámite #{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}</h1>
                            <p class="text-base text-gray-500 mt-1">Información completa del trámite</p>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                        <a href="{{ route('tramites.historial') }}" 
                           class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-gray-500 to-gray-600 border border-gray-500 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver al Historial
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal con Todos los Formularios -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <!-- Header con Información del Trámite -->
            <div class="border-b border-gray-200 pb-6 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Trámite #{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}</h2>
                            <p class="text-base text-gray-500">{{ ucfirst(str_replace('_', ' ', $tramite->tipo_tramite)) }}</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                        @php
                            $estadoColor = match ($tramite->estado) {
                                'Pendiente' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'Por_Cotejar' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'En_Revision' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'Aprobado' => 'bg-green-50 text-green-700 border-green-200',
                                'Rechazado' => 'bg-red-50 text-red-700 border-red-200',
                                'Cancelado' => 'bg-gray-50 text-gray-700 border-gray-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $estadoColor }}">
                            {{ ucfirst(str_replace('_', ' ', $tramite->estado)) }}
                        </span>
                        <div class="text-sm text-gray-500">
                            Creado: {{ $tramite->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido de los Formularios -->
            <div class="space-y-8">
                <!-- Datos Generales -->
                @include('tramites.partials.datos-generales', [
                    'tramite' => $tramite,
                    'proveedor' => $tramite->proveedor,
                    'datosSat' => [
                        'rfc' => $tramite->datosGenerales?->rfc ?? '',
                        'razon_social' => $tramite->datosGenerales?->razon_social ?? '',
                        'curp' => $tramite->datosGenerales?->curp ?? '',
                        'cp' => $tramite->direcciones->first()?->codigo_postal ?? '',
                        'colonia' => $tramite->direcciones->first()?->asentamiento ?? '',
                        'nombre_vialidad' => $tramite->direcciones->first()?->calle ?? '',
                        'numero_exterior' => $tramite->direcciones->first()?->numero_exterior ?? '',
                        'numero_interior' => $tramite->direcciones->first()?->numero_interior ?? ''
                    ],
                    'editable' => false,
                    'tipo' => $tramite->tipo_tramite
                ])

                <!-- Actividades Económicas -->
                @include('tramites.partials.actividades-economicas', [
                    'tramite' => $tramite,
                    'proveedor' => $tramite->proveedor,
                    'editable' => false,
                    'tipo' => $tramite->tipo_tramite
                ])

                <!-- Domicilio -->
                @include('tramites.partials.domicilio', [
                    'tramite' => $tramite,
                    'proveedor' => $tramite->proveedor,
                    'datosSat' => [
                        'cp' => $tramite->direcciones->first()?->codigo_postal ?? '',
                        'colonia' => $tramite->direcciones->first()?->asentamiento ?? '',
                        'nombre_vialidad' => $tramite->direcciones->first()?->calle ?? '',
                        'numero_exterior' => $tramite->direcciones->first()?->numero_exterior ?? '',
                        'numero_interior' => $tramite->direcciones->first()?->numero_interior ?? ''
                    ],
                    'editable' => false,
                    'tipo' => $tramite->tipo_tramite,
                    'direccion' => $tramite->direcciones->first()
                ])

                <!-- Constitución (solo para personas morales) -->
                @if($tramite->proveedor->tipo_persona === 'Moral')
                    @include('tramites.partials.constitucion', [
                        'tramite' => $tramite,
                        'proveedor' => $tramite->proveedor,
                        'editable' => false,
                        'tipo' => $tramite->tipo_tramite
                    ])

                    <!-- Apoderado Legal -->
                    @include('tramites.partials.apoderado', [
                        'tramite' => $tramite,
                        'proveedor' => $tramite->proveedor,
                        'editable' => false,
                        'tipo' => $tramite->tipo_tramite
                    ])

                    <!-- Accionistas -->
                    @include('tramites.partials.accionistas', [
                        'tramite' => $tramite,
                        'proveedor' => $tramite->proveedor,
                        'editable' => false,
                        'tipo' => $tramite->tipo_tramite
                    ])
                @endif

                <!-- Documentos -->
                @include('tramites.partials.documentos', [
                    'tramite' => $tramite,
                    'proveedor' => $tramite->proveedor,
                    'editable' => false,
                    'tipo' => $tramite->tipo_tramite
                ])
            </div>
        </div>
    </div>
</div>
@endsection 