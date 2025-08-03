@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Nuevo Trámite</h1>
                        <p class="text-base text-gray-500 mt-1">Complete todos los pasos para crear un nuevo trámite</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('tramites.index') }}" 
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
            <form method="POST" action="{{ route('tramites.store') }}" enctype="multipart/form-data" class="space-y-8" id="tramite-form">
                @csrf

                <!-- Datos Generales -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="0" id="section-0">
                    @include('components.forms.datos-generales', [
                        'editable' => true, 
                        'datosConstancia' => $viewModel
                    ])
                </div>

                <!-- Actividades Económicas -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="1" id="section-1">
                    @include('components.forms.actividades-economicas', ['editable' => true])
                </div>

                <!-- Domicilio -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="2" id="section-2">
                    @include('components.forms.domicilio', [
                        'editable' => true, 
                        'datosConstancia' => $viewModel
                    ])
                </div>

                <!-- Constitución -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="3" id="section-3">
                    @include('components.forms.constitucion', ['editable' => true])
                </div>

                <!-- Accionistas -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="4" id="section-4">
                    @include('components.forms.accionistas', ['editable' => true])
                </div>

                <!-- Apoderado Legal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="5" id="section-5">
                    @include('components.forms.apoderado', ['editable' => true])
                </div>

                <!-- Archivos -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="6" id="section-6">
                    @include('components.forms.archivos', ['editable' => true])
                </div>

                <!-- Botón de envío -->
                <div class="flex justify-center bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <button type="submit" class="bg-[#9d2449] hover:bg-[#8a1f40] text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Trámite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="fixed bottom-6 right-6 space-y-2 z-40">
    <button type="button" id="btn-prev" onclick="navigateSection('prev')" 
            class="w-12 h-12 bg-gray-600 hover:bg-gray-700 text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-up"></i>
    </button>
    <button type="button" id="btn-next" onclick="navigateSection('next')" 
            class="w-12 h-12 bg-[#9d2449] hover:bg-[#8a1f40] text-white rounded-full shadow-lg flex items-center justify-center transition-colors">
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

// Auto-completar campos con datos de la constancia
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($viewModel))
        // Auto-completar datos generales
        if (document.getElementById('razon_social')) {
            document.getElementById('razon_social').value = '{{ $viewModel->getDatosGenerales()["razon_social"] ?? "" }}';
        }
        if (document.getElementById('rfc')) {
            document.getElementById('rfc').value = '{{ $viewModel->getDatosGenerales()["rfc"] ?? "" }}';
        }
        if (document.getElementById('curp')) {
            document.getElementById('curp').value = '{{ $viewModel->getDatosGenerales()["curp"] ?? "" }}';
        }
        
        // Auto-completar domicilio
        @php
            $datosDomicilio = $viewModel->getDatosDomicilioForm();
        @endphp
        if (document.getElementById('calle')) {
            document.getElementById('calle').value = '{{ $datosDomicilio["calle"] ?? "" }}';
        }
        if (document.getElementById('numero_exterior')) {
            document.getElementById('numero_exterior').value = '{{ $datosDomicilio["numero_exterior"] ?? "" }}';
        }
        if (document.getElementById('numero_interior')) {
            document.getElementById('numero_interior').value = '{{ $datosDomicilio["numero_interior"] ?? "" }}';
        }
        if (document.getElementById('colonia')) {
            document.getElementById('colonia').value = '{{ $datosDomicilio["asentamiento"] ?? "" }}';
        }
        if (document.getElementById('codigo_postal')) {
            document.getElementById('codigo_postal').value = '{{ $datosDomicilio["codigo_postal"] ?? "" }}';
        }
        if (document.getElementById('municipio')) {
            document.getElementById('municipio').value = '{{ $datosDomicilio["municipio"] ?? "" }}';
        }
        if (document.getElementById('estado')) {
            document.getElementById('estado').value = '{{ $datosDomicilio["estado"] ?? "" }}';
        }
    @endif
});
</script>
@endsection 