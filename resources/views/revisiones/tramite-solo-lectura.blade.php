@extends('layouts.app')

@section('title', 'Ver Trámite - Solo Lectura')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Ver Trámite #{{ $tramite->id }} - Solo Lectura
                        </h1>
                        <p class="text-base text-gray-500 mt-1">Vista completa de los datos del trámite histórico</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Información del trámite -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm font-medium text-purple-800">Tipo de Trámite:</span>
                        <p class="text-purple-900">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-purple-800">Estado:</span>
                        <p class="text-purple-900">{{ $tramite->status }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-purple-800">Fecha de Creación:</span>
                        <p class="text-purple-900">{{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-purple-800">RFC:</span>
                        <p class="text-purple-900">{{ $tramite->proveedor->rfc }}</p>
                    </div>
                </div>
            </div>

            <!-- Datos Generales -->
            <div class="mb-6" data-section="datos_generales">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Datos Generales</h2>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @include('components.forms.datos-generales', [
                        'editable' => false, 
                        'datosConstancia' => $viewModel
                    ])
                </div>
            </div>

            <!-- Actividades Económicas -->
            <div class="mb-6" data-section="actividades">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Actividades Económicas</h2>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @include('components.forms.actividades-economicas', [
                        'editable' => false,
                        'datos' => $viewModel->getActividades()
                    ])
                </div>
            </div>

            <!-- Domicilio -->
            <div class="mb-6" data-section="domicilio">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Domicilio</h2>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @include('components.forms.domicilio', [
                        'editable' => false, 
                        'datosConstancia' => $viewModel
                    ])
                </div>
            </div>

            @if($viewModel->isPersonaMoral())
                <!-- Constitución -->
                <div class="mb-6" data-section="constitucion">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Constitución</h2>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.constitucion', [
                            'editable' => false,
                            'datos' => $viewModel->getConstitucion()
                        ])
                    </div>
                </div>

                <!-- Accionistas -->
                <div class="mb-6" data-section="accionistas">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Accionistas</h2>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.accionistas', [
                            'editable' => false,
                            'datos' => $viewModel->getAccionistas()
                        ])
                    </div>
                </div>

                <!-- Apoderado Legal -->
                <div class="mb-6" data-section="apoderado">
                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Apoderado Legal</h2>
                    </div>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.apoderado', [
                            'editable' => false,
                            'datos' => $viewModel->getApoderado()
                        ])
                    </div>
                </div>
            @endif

            <!-- Archivos -->
            <div class="mb-6" data-section="archivos">
                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Archivos</h2>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @include('components.forms.archivos-dinamicos', [
                        'editable' => false, 
                        'archivosRequeridos' => [],
                        'tipoPersona' => $viewModel->isPersonaMoral() ? 'Moral' : 'Física',
                        'archivosCargados' => $viewModel->getArchivos(),
                        'soloLectura' => true
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navegación flotante -->
<div class="fixed bottom-6 right-6 space-y-2 z-40">
    <button type="button" id="btn-prev" onclick="navigateSection('prev')" 
            class="w-12 h-12 bg-gray-600 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-up"></i>
    </button>
    <button type="button" id="btn-next" onclick="navigateSection('next')" 
            class="w-12 h-12 bg-purple-600 hover:bg-purple-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-down"></i>
    </button>
</div>

<script>
let currentSection = 0;
const sections = document.querySelectorAll('[data-section]');

function navigateSection(direction) {
    if (direction === 'prev' && currentSection > 0) {
        currentSection--;
    } else if (direction === 'next' && currentSection < sections.length - 1) {
        currentSection++;
    }
    
    sections[currentSection].scrollIntoView({ behavior: 'smooth', block: 'start' });
}

window.navigateSection = navigateSection;
</script>
@endsection 