@extends('layouts.app')



@section('content')
<style>
    .step-content {
        display: none;
    }
    
    .step-content.active {
        display: block;
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

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="text-red-800 font-semibold mb-2">Errores de validación:</h3>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('tramites.store') }}" enctype="multipart/form-data" id="tramite-form">
                @csrf
                
                <!-- Campo oculto para tipo de trámite -->
                <input type="hidden" name="tipo_tramite" value="{{ session('tipo_tramite_seleccionado') }}">
                
                @php
                    $totalSteps = $tipoPersona === 'Moral' ? 7 : 4;
                    $steps = [
                        [
                            'title' => 'Datos Generales',
                            'description' => 'Información básica del proveedor'
                        ],
                        [
                            'title' => 'Actividades',
                            'description' => 'Actividades económicas'
                        ],
                        [
                            'title' => 'Domicilio',
                            'description' => 'Dirección fiscal'
                        ]
                    ];
                    
                    if ($tipoPersona === 'Moral') {
                        $steps[] = ['title' => 'Constitución', 'description' => 'Datos de constitución'];
                        $steps[] = ['title' => 'Accionistas', 'description' => 'Información de accionistas'];
                        $steps[] = ['title' => 'Apoderado', 'description' => 'Apoderado legal'];
                    }
                    
                    $steps[] = ['title' => 'Documentos', 'description' => 'Archivos requeridos'];
                @endphp

                <!-- Componente de Steps -->
                <x-navigation.steps :steps="$steps" :current-step="0" :total-steps="$totalSteps" />

                <!-- Contenido de los pasos -->
                <div class="step-content active" data-step="0">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.datos-generales', [
                            'editable' => true, 
                            'datosConstancia' => $viewModel
                        ])
                    </div>
                </div>

                <div class="step-content" data-step="1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.actividades-economicas', ['editable' => true])
                    </div>
                </div>

                <div class="step-content" data-step="2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @include('components.forms.domicilio', [
                            'editable' => true, 
                            'datosConstancia' => $viewModel
                        ])
                    </div>
                </div>

                @if($tipoPersona === 'Moral')
                    <div class="step-content" data-step="3">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.constitucion', [
                                'editable' => true,
                                'datosConstitucion' => $viewModel ?? null
                            ])
                        </div>
                    </div>

                    <div class="step-content" data-step="4">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.accionistas', [
                                'editable' => true,
                                'accionistas' => $viewModel ?? null
                            ])
                        </div>
                    </div>

                    <div class="step-content" data-step="5">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.apoderado', [
                                'editable' => true,
                                'datosApoderado' => $viewModel ?? null
                            ])
                        </div>
                    </div>

                    <div class="step-content" data-step="6">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.archivos-dinamicos', [
                                'editable' => true, 
                                'archivosRequeridos' => $archivosRequeridos,
                                'tipoPersona' => $tipoPersona
                            ])
                            
                            <!-- Sección de Términos y Condiciones -->
                            <div class="mt-8">
                                @include('components.forms.seccion-terminos', ['editable' => true])
                            </div>
                        </div>
                    </div>
                @else
                    <div class="step-content" data-step="3">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @include('components.forms.archivos-dinamicos', [
                                'editable' => true, 
                                'archivosRequeridos' => $archivosRequeridos,
                                'tipoPersona' => $tipoPersona
                            ])
                            
                            <!-- Sección de Términos y Condiciones -->
                            <div class="mt-8">
                                @include('components.forms.seccion-terminos', ['editable' => true])
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Navegación entre pasos -->
                <div data-step-navigation></div>
            </form>
        </div>
    </div>
</div>

<x-ui.modals.modal-confirmacion id="modal-confirmacion-tramite"
    title="Confirmar envío"
    message="¿Está seguro que desea enviar el trámite?"
    confirmText="Sí, enviar"
    cancelText="Cancelar"
/>

<!-- Modal de Términos de Servicio -->
@include('components.modals.terminos-servicio', ['id' => 'modal-terminos-servicio'])

<!-- Sistema de validación -->
<script type="module" src="{{ asset('js/validations/index.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos de la constancia solo si no hay valores old() (errores de validación)
    @if(isset($viewModel))
        const razonSocial = document.getElementById('razon_social');
        const rfc = document.getElementById('rfc');
        const curp = document.getElementById('curp');
        
        // Solo cargar datos de constancia si no hay valores old() (errores de validación)
        if (razonSocial && !razonSocial.value) {
            razonSocial.value = '{{ $viewModel->getDatosGenerales()["razon_social"] ?? "" }}';
        }
        if (rfc && !rfc.value) {
            rfc.value = '{{ $viewModel->getDatosGenerales()["rfc"] ?? "" }}';
        }
        if (curp && !curp.value) {
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
        
        // Solo cargar datos de constancia si no hay valores old() (errores de validación)
        if (calle && !calle.value) {
            calle.value = '{{ $datosDomicilio["calle"] ?? "" }}';
        }
        if (numeroExterior && !numeroExterior.value) {
            numeroExterior.value = '{{ $datosDomicilio["numero_exterior"] ?? "" }}';
        }
        if (numeroInterior && !numeroInterior.value) {
            numeroInterior.value = '{{ $datosDomicilio["numero_interior"] ?? "" }}';
        }
        if (colonia && !colonia.value) {
            colonia.value = '{{ $datosDomicilio["asentamiento"] ?? "" }}';
        }
        if (codigoPostal && !codigoPostal.value) {
            codigoPostal.value = '{{ $datosDomicilio["codigo_postal"] ?? "" }}';
        }
        if (municipio && !municipio.value) {
            municipio.value = '{{ $datosDomicilio["municipio"] ?? "" }}';
        }
        if (estado && !estado.value) {
            estado.value = '{{ $datosDomicilio["estado"] ?? "" }}';
        }
    @endif

    // Configurar el formulario
    const tramiteForm = document.getElementById('tramite-form');
    
    if (tramiteForm) {
        tramiteForm.addEventListener('submit', function(e) {
            const btnEnviar = document.getElementById('btn-enviar-tramite');
            if (btnEnviar) {
                btnEnviar.disabled = true;
                btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            }
        });
    }
    
    // Mostrar errores de validación en campos específicos
    @if($errors->any())
        @foreach($errors->keys() as $field)
            var field = document.querySelector('[name="{{ $field }}"]');
            if (field) {
                field.classList.add('border-red-500');
            }
        @endforeach
    @endif
    
    // Asegurar que el mapa se redimensione correctamente cuando se navegue al step de domicilio
    window.addEventListener('stepChanged', function(event) {
        const currentStep = event.detail?.currentStep;
        // El step de domicilio es el step 2 (índice 2)
        if (currentStep === 2) {
            setTimeout(() => {
                const mapContainer = document.getElementById('mapa');
                if (mapContainer) {
                    // Forzar un redimensionamiento del mapa
                    const map = mapContainer._leaflet_map;
                    if (map) {
                        map.invalidateSize();
                    }
                }
            }, 200);
        }
    });
});
</script>
@endsection 