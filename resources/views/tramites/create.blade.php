@extends('layouts.app')

@section('content')
<style>
    .border-red-500 {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .field-error {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .field-error:focus {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
    }
</style>
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
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

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

                @if($tipoPersona === 'Moral')
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
                        @include('components.forms.archivos-dinamicos', [
                            'editable' => true, 
                            'archivosRequeridos' => $archivosRequeridos,
                            'tipoPersona' => $tipoPersona
                        ])
                    </div>
                @else
                    <!-- Archivos -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6" data-section="3" id="section-3">
                        @include('components.forms.archivos-dinamicos', [
                            'editable' => true, 
                            'archivosRequeridos' => $archivosRequeridos,
                            'tipoPersona' => $tipoPersona
                        ])
                    </div>
                @endif

                <!-- Botón de envío -->
                <div class="flex justify-center bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <button type="submit" id="btn-enviar-tramite" class="bg-[#9d2449] hover:bg-[#8a1f40] text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Trámite
                    </button>
                    
                    <!-- Botón de prueba temporal -->
                    <button type="button" onclick="enviarPrueba()" class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors duration-200 text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-flask mr-2"></i>
                        Prueba Sin Validaciones
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
console.log('Create tramite script loaded v1.2'); // Debug log with version

let currentSection = 0;
const sections = document.querySelectorAll('[data-section]');
const tipoPersona = '{{ $tipoPersona }}';

function navigateSection(direction) {
    if (direction === 'prev' && currentSection > 0) {
        currentSection--;
    } else if (direction === 'next' && currentSection < sections.length - 1) {
        currentSection++;
    }
    
    sections[currentSection].scrollIntoView({ behavior: 'smooth', block: 'start' });
}

window.navigateSection = navigateSection;

// Función para enviar formulario de prueba
function enviarPrueba() {
    console.log('Enviando formulario de prueba...');
    
    const form = document.getElementById('tramite-form');
    const formData = new FormData(form);
    
    // Cambiar la acción del formulario
    form.action = '{{ route("tramites.test-store") }}';
    
    // Enviar el formulario
    form.submit();
}

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Create tramite: DOM loaded v1.2'); // Debug log with version
    
    // Mostrar errores de validación si existen
    @if($errors->any())
        console.error('Errores de validación encontrados:', @json($errors->all()));
        alert('Errores de validación: ' + @json($errors->all()).join(', '));
    @endif
    
    // Cargar datos de la constancia si están disponibles
    @if(isset($viewModel))
        const razonSocial = document.getElementById('razon_social');
        const rfc = document.getElementById('rfc');
        const curp = document.getElementById('curp');
        
        if (razonSocial) {
            razonSocial.value = '{{ $viewModel->getDatosGenerales()["razon_social"] ?? "" }}';
        }
        if (rfc) {
            rfc.value = '{{ $viewModel->getDatosGenerales()["rfc"] ?? "" }}';
        }
        if (curp) {
            curp.value = '{{ $viewModel->getDatosGenerales()["curp"] ?? "" }}';
        }
        
        @php
            $datosDomicilio = $viewModel->getDatosDomicilioForm();
        @endphp
        
        const calle = document.getElementById('calle');
        const numeroExterior = document.getElementById('numero_exterior');
        const numeroInterior = document.getElementById('numero_interior');
        const colonia = document.getElementById('colonia');
        const codigoPostal = document.getElementById('codigo_postal');
        const municipio = document.getElementById('municipio');
        const estado = document.getElementById('estado');
        
        if (calle) {
            calle.value = '{{ $datosDomicilio["calle"] ?? "" }}';
        }
        if (numeroExterior) {
            numeroExterior.value = '{{ $datosDomicilio["numero_exterior"] ?? "" }}';
        }
        if (numeroInterior) {
            numeroInterior.value = '{{ $datosDomicilio["numero_interior"] ?? "" }}';
        }
        if (colonia) {
            colonia.value = '{{ $datosDomicilio["asentamiento"] ?? "" }}';
        }
        if (codigoPostal) {
            codigoPostal.value = '{{ $datosDomicilio["codigo_postal"] ?? "" }}';
        }
        if (municipio) {
            municipio.value = '{{ $datosDomicilio["municipio"] ?? "" }}';
        }
        if (estado) {
            estado.value = '{{ $datosDomicilio["estado"] ?? "" }}';
        }
    @endif

    // Solo mostrar estado de carga al enviar
    try {
        const tramiteForm = document.getElementById('tramite-form');
        console.log('Tramite form found:', tramiteForm); // Debug log
        
        if (tramiteForm) {
            tramiteForm.addEventListener('submit', function(e) {
                console.log('Formulario enviado - validando datos...');
                
                // Validar que todos los archivos requeridos estén presentes
                const fileInputs = document.querySelectorAll('input[type="file"]');
                const requiredFiles = [];
                
                fileInputs.forEach(input => {
                    if (input.hasAttribute('required') || input.name.includes('documentos')) {
                        if (!input.files || input.files.length === 0) {
                            requiredFiles.push(input.name);
                        }
                    }
                });
                
                if (requiredFiles.length > 0) {
                    e.preventDefault();
                    alert('Faltan archivos requeridos: ' + requiredFiles.join(', '));
                    return false;
                }
                
                const btnEnviar = document.getElementById('btn-enviar-tramite');
                if (btnEnviar) {
                    btnEnviar.disabled = true;
                    btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
                }
            });
            console.log('Submit event listener added successfully'); // Debug log
        } else {
            console.warn('Tramite form not found - this might be expected if the form is not present on this page');
        }
    } catch (error) {
        console.error('Error setting up form event listener:', error);
    }
});


</script>
@endsection 