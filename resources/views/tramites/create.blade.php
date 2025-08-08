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

            <!-- Información del proveedor según tipo de trámite -->
            @if(isset($infoProveedor))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Gestión del Proveedor - {{ ucfirst($infoProveedor['tipo_tramite']) }}
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p class="mb-2">{{ $infoProveedor['mensaje_usuario'] }}</p>
                                
                                @if($infoProveedor['proveedor_existente'])
                                    <div class="bg-white rounded-md p-3 mt-3">
                                        <h4 class="font-medium text-blue-800 mb-2">Proveedor Existente:</h4>
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <span class="font-medium">Número PV:</span> 
                                                <span class="text-blue-600">{{ $infoProveedor['proveedor_existente']['pv_numero'] }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Estado:</span> 
                                                <span class="px-2 py-1 rounded text-xs {{ $infoProveedor['proveedor_existente']['esta_vigente'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $infoProveedor['proveedor_existente']['estado_padron'] }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Fecha Registro:</span> 
                                                <span class="text-blue-600">{{ $infoProveedor['proveedor_existente']['fecha_registro'] ? $infoProveedor['proveedor_existente']['fecha_registro']->format('d/m/Y') : 'N/A' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Fecha Vencimiento:</span> 
                                                <span class="text-blue-600">{{ $infoProveedor['proveedor_existente']['fecha_vencimiento'] ? $infoProveedor['proveedor_existente']['fecha_vencimiento']->format('d/m/Y') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                    $totalSteps = $tipoPersona === 'Moral' ? 8 : 5;
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
                    $steps[] = ['title' => 'Términos y Condiciones', 'description' => 'Confirmación final'];
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
                        </div>
                    </div>

                    <div class="step-content" data-step="7">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <!-- Sección de Términos y Condiciones -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Términos y Condiciones</h3>
                                <p class="text-gray-600 mb-4">Antes de enviar su trámite, por favor lea y acepte los siguientes términos y condiciones:</p>
                                
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 max-h-64 overflow-y-auto">
                                    <div class="text-sm text-gray-700 space-y-3">
                                        <p><strong>1. Veracidad de la Información:</strong> Declaro bajo protesta de decir verdad que toda la información proporcionada en este trámite es veraz, completa y actualizada.</p>
                                        
                                        <p><strong>2. Documentación:</strong> Me comprometo a proporcionar toda la documentación requerida y a mantenerla actualizada durante el proceso de trámite.</p>
                                        
                                        <p><strong>3. Responsabilidad:</strong> Entiendo que soy responsable de la veracidad y completitud de toda la información proporcionada.</p>
                                        
                                        <p><strong>4. Confidencialidad:</strong> Autorizo el tratamiento de mis datos personales conforme a la Ley de Protección de Datos Personales.</p>
                                        
                                        <p><strong>5. Notificaciones:</strong> Acepto recibir notificaciones relacionadas con este trámite a través de los medios proporcionados.</p>
                                        
                                        <p><strong>6. Cumplimiento Normativo:</strong> Me comprometo a cumplir con todas las disposiciones legales y reglamentarias aplicables.</p>
                                        
                                        <p><strong>7. Revisión:</strong> Entiendo que el trámite será revisado y puedo ser contactado para aclaraciones o correcciones.</p>
                                        
                                        <p><strong>8. Finalización:</strong> El trámite se considerará completo una vez que toda la información y documentación sea validada.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" id="acepto_terminos" name="acepto_terminos" value="1" 
                                           class="mt-1 h-4 w-4 text-[#9d2449] border-gray-300 rounded focus:ring-[#9d2449] focus:ring-2"
                                           {{ old('acepto_terminos') ? 'checked' : '' }}>
                                    <label for="acepto_terminos" class="text-sm text-gray-700">
                                        He leído y acepto los <a href="#" onclick="abrirModalTerminos()" class="text-[#9d2449] hover:underline">términos y condiciones</a> del trámite
                                    </label>
                                </div>
                                
                                @error('acepto_terminos')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
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
                        </div>
                    </div>

                    <div class="step-content" data-step="4">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <!-- Sección de Términos y Condiciones -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Términos y Condiciones</h3>
                                <p class="text-gray-600 mb-4">Antes de enviar su trámite, por favor lea y acepte los siguientes términos y condiciones:</p>
                                
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 max-h-64 overflow-y-auto">
                                    <div class="text-sm text-gray-700 space-y-3">
                                        <p><strong>1. Veracidad de la Información:</strong> Declaro bajo protesta de decir verdad que toda la información proporcionada en este trámite es veraz, completa y actualizada.</p>
                                        
                                        <p><strong>2. Documentación:</strong> Me comprometo a proporcionar toda la documentación requerida y a mantenerla actualizada durante el proceso de trámite.</p>
                                        
                                        <p><strong>3. Responsabilidad:</strong> Entiendo que soy responsable de la veracidad y completitud de toda la información proporcionada.</p>
                                        
                                        <p><strong>4. Confidencialidad:</strong> Autorizo el tratamiento de mis datos personales conforme a la Ley de Protección de Datos Personales.</p>
                                        
                                        <p><strong>5. Notificaciones:</strong> Acepto recibir notificaciones relacionadas con este trámite a través de los medios proporcionados.</p>
                                        
                                        <p><strong>6. Cumplimiento Normativo:</strong> Me comprometo a cumplir con todas las disposiciones legales y reglamentarias aplicables.</p>
                                        
                                        <p><strong>7. Revisión:</strong> Entiendo que el trámite será revisado y puedo ser contactado para aclaraciones o correcciones.</p>
                                        
                                        <p><strong>8. Finalización:</strong> El trámite se considerará completo una vez que toda la información y documentación sea validada.</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" id="acepto_terminos" name="acepto_terminos" value="1" 
                                           class="mt-1 h-4 w-4 text-[#9d2449] border-gray-300 rounded focus:ring-[#9d2449] focus:ring-2"
                                           {{ old('acepto_terminos') ? 'checked' : '' }}>
                                    <label for="acepto_terminos" class="text-sm text-gray-700">
                                        He leído y acepto los <a href="#" onclick="abrirModalTerminos()" class="text-[#9d2449] hover:underline">términos y condiciones</a> del trámite
                                    </label>
                                </div>
                                
                                @error('acepto_terminos')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
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

// Función para abrir el modal de términos
function abrirModalTerminos() {
    const modal = document.getElementById('modal-terminos-servicio');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

// Función global para validar términos y condiciones (usada por steps.blade.php)
window.validarTerminosYCondicionesFinal = function() {
    const checkboxTerminos = document.getElementById('acepto_terminos');
    const btnEnviarFinal = document.getElementById('btn-enviar-tramite-final');
    
    if (checkboxTerminos && btnEnviarFinal) {
        if (checkboxTerminos.checked) {
            // Habilitar botón
            btnEnviarFinal.disabled = false;
            btnEnviarFinal.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btnEnviarFinal.classList.add('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
        } else {
            // Deshabilitar botón
            btnEnviarFinal.disabled = true;
            btnEnviarFinal.classList.remove('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
            btnEnviarFinal.classList.add('bg-gray-400', 'cursor-not-allowed');
        }
    }
};

// Función para validar términos y condiciones (para el botón dinámico)
function validarTerminosYCondiciones() {
    const checkboxTerminos = document.getElementById('acepto_terminos');
    const btnEnviarFinal = document.getElementById('btn-enviar-tramite-final');
    
    if (checkboxTerminos && btnEnviarFinal) {
        if (checkboxTerminos.checked) {
            // Habilitar botón dinámico
            btnEnviarFinal.disabled = false;
            btnEnviarFinal.classList.remove('bg-gray-400', 'cursor-not-allowed');
            btnEnviarFinal.classList.add('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
        } else {
            // Deshabilitar botón dinámico
            btnEnviarFinal.disabled = true;
            btnEnviarFinal.classList.remove('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
            btnEnviarFinal.classList.add('bg-gray-400', 'cursor-not-allowed');
        }
    }
}

// Agregar event listener para el checkbox de términos
document.addEventListener('DOMContentLoaded', function() {
    const checkboxTerminos = document.getElementById('acepto_terminos');
    if (checkboxTerminos) {
        checkboxTerminos.addEventListener('change', validarTerminosYCondiciones);
        // Validar estado inicial
        validarTerminosYCondiciones();
    }
    
    // Observar cambios en el DOM para detectar cuando se agrega el botón dinámico
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                const btnEnviarFinal = document.getElementById('btn-enviar-tramite-final');
                if (btnEnviarFinal) {
                    validarTerminosYCondiciones();
                }
            }
        });
    });
    
    // Observar cambios en el contenedor de navegación
    const navigationContainer = document.querySelector('[data-step-navigation]');
    if (navigationContainer) {
        observer.observe(navigationContainer, { childList: true, subtree: true });
    }
});

// Script para archivos en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    // Simular tramite ID (en creación será null, pero funciona para editar)
    window.tramiteId = null; // Será null al crear, se asignará después del envío
});
</script>

<script src="{{ asset('js/revision/archivos-tiempo-real.js') }}"></script>
@endsection 