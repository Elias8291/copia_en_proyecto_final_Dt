@extends('layouts.app')

@section('title', 'Trámite Histórico')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <!-- Header -->
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Trámite Histórico #{{ $tramite->id }}</h1>
                        <p class="text-base text-gray-500 mt-1">Consulta de datos del trámite - Solo lectura</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tramites.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Historial
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Información del trámite -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <span class="text-sm font-medium text-blue-800">Tipo de Trámite:</span>
                        <p class="text-blue-900">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Estado:</span>
                        <p class="text-blue-900">{{ $tramite->status }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Fecha de Creación:</span>
                        <p class="text-blue-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">RFC:</span>
                        <p class="text-blue-900">{{ $tramite->proveedor->rfc }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">Tipo de Persona:</span>
                        <p class="text-blue-900 font-semibold">
                            @if($viewModel->isPersonaMoral())
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded">Moral</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Física</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Separador Inicial -->
            <x-ui.separador-simple margin="my-8" color="border-indigo-300" />

            <!-- Datos Generales -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Datos Generales</h2>
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
                <h2 class="text-xl font-bold text-gray-800 mb-4">Actividades Económicas</h2>
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
                <h2 class="text-xl font-bold text-gray-800 mb-4">Domicilio</h2>
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
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Constitución</h2>
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
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Accionistas</h2>
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
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Apoderado Legal</h2>
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
                <h2 class="text-xl font-bold text-gray-800 mb-4">Archivos</h2>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @php
                        $archivos = $viewModel->getArchivos();
                        // Convertir Collection a array si es necesario
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
    </div>
</div>
@endsection 