@extends('layouts.app')

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showErrorModal('error-modal', 'Error', '{!! session("error") !!}');
    });
</script>
@endif  

@section('content')
<style>
    .step-content {
        display: none;
    }
    
    .step-content.active {
        display: block;
    } 
    @keyframes progressPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    @keyframes successBounce {
        0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
        40%, 43% { transform: translate3d(0, -30px, 0); }
        70% { transform: translate3d(0, -15px, 0); }
        90% { transform: translate3d(0, -4px, 0); }
    }
    
    .progress-pulse {
        animation: progressPulse 2s infinite;
    }
    
    .success-bounce {
        animation: successBounce 1s ease-out;
    }
    
    .btn-enviar-loading {
        position: relative;
        overflow: hidden;
    }
    
    .btn-enviar-loading::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-enviar-loading:hover::before {
        left: 100%;
    }
    
    .confetti {
        position: fixed;
        width: 10px;
        height: 10px;
        background: #f00;
        animation: confetti-fall 3s linear infinite;
    }
    
    @keyframes confetti-fall {
        0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
        100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
    }
</style>

<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" @if(isset($modoCorreccion) && $modoCorreccion) data-correction-mode="true" @endif>
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    @if(isset($modoCorreccion) && $modoCorreccion)
                        <div class="bg-gradient-to-br from-gray-700 to-gray-900 rounded-xl p-3 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        @if(isset($modoCorreccion) && $modoCorreccion)
                            <h1 class="text-2xl font-bold text-gray-800">Corrección de Trámite</h1>
                            <p class="text-base text-gray-500 mt-1">Complete las correcciones solicitadas</p>
                        @else
                            <h1 class="text-2xl font-bold text-gray-800">Nuevo Trámite</h1>
                            <p class="text-base text-gray-500 mt-1">Complete todos los pasos para crear un nuevo trámite</p>
                        @endif
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
            @if (session('success') && session('tramite_creado') === true)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            @if(isset($modoCorreccion) && $modoCorreccion && isset($seccionesParaCorregir))
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Secciones que Requieren Corrección
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p class="mb-2">Las siguientes secciones necesitan ser corregidas:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($seccionesParaCorregir as $seccion)
                                        <li>
                                            <strong>{{ $seccion['nombre'] }}</strong>
                                            @if($seccion['comentario'])
                                                <div class="mt-1 ml-4 text-xs bg-white rounded p-2 border border-blue-200">
                                                    {{ $seccion['comentario'] }}
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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

            @if(isset($modoCorreccion) && $modoCorreccion && isset($tramite))
                <form method="POST" action="{{ route('tramites.update', $tramite->id) }}" enctype="multipart/form-data" id="tramite-form">
                    @csrf
                    @method('PUT')
            @else
                <form method="POST" action="{{ route('tramites.store') }}" enctype="multipart/form-data" id="tramite-form">
                    @csrf
            @endif
                
                <input type="hidden" name="tipo_tramite" value="{{ session('tipo_tramite_seleccionado') }}">
                
                @php
                    if (isset($modoCorreccion) && $modoCorreccion && isset($seccionesParaCorregir)) {
                        $steps = [];
                        $stepIndex = 0;
                        
                        foreach ($seccionesParaCorregir as $seccion) {
                            switch ($seccion['seccion']) {
                                case 'datos_generales':
                                    $steps[] = [
                                        'title' => 'Datos Generales',
                                        'description' => 'Información básica del proveedor',
                                        'seccion' => 'datos_generales',
                                        'step_index' => $stepIndex++
                                    ];
                                    break;
                                case 'actividades':
                                    $steps[] = [
                                        'title' => 'Actividades',
                                        'description' => 'Actividades económicas',
                                        'seccion' => 'actividades',
                                        'step_index' => $stepIndex++
                                    ];
                                    break;
                                case 'domicilio':
                                    $steps[] = [
                                        'title' => 'Domicilio',
                                        'description' => 'Dirección fiscal',
                                        'seccion' => 'domicilio',
                                        'step_index' => $stepIndex++
                                    ];
                                    break;
                                case 'constitucion':
                                    if ($tipoPersona === 'Moral') {
                                        $steps[] = [
                                            'title' => 'Constitución',
                                            'description' => 'Datos de constitución',
                                            'seccion' => 'constitucion',
                                            'step_index' => $stepIndex++
                                        ];
                                    }
                                    break;
                                case 'accionistas':
                                    if ($tipoPersona === 'Moral') {
                                        $steps[] = [
                                            'title' => 'Accionistas',
                                            'description' => 'Información de accionistas',
                                            'seccion' => 'accionistas',
                                            'step_index' => $stepIndex++
                                        ];
                                    }
                                    break;
                                case 'apoderado':
                                    if ($tipoPersona === 'Moral') {
                                        $steps[] = [
                                            'title' => 'Apoderado',
                                            'description' => 'Apoderado legal',
                                            'seccion' => 'apoderado',
                                            'step_index' => $stepIndex++
                                        ];
                                    }
                                    break;
                                case 'archivos':
                                    $steps[] = [
                                        'title' => 'Documentos',
                                        'description' => 'Archivos requeridos',
                                        'seccion' => 'archivos',
                                        'step_index' => $stepIndex++
                                    ];
                                    break;
                            }
                        }
                        
                        $steps[] = [
                            'title' => 'Confirmar Correcciones',
                            'description' => 'Confirmación final',
                            'seccion' => 'confirmacion',
                            'step_index' => $stepIndex++
                        ];
                        
                        $totalSteps = count($steps);
                    } else {
                        $totalSteps = $tipoPersona === 'Moral' ? 8 : 5;
                        $steps = [
                            [
                                'title' => 'Datos Generales',
                                'description' => 'Información básica del proveedor',
                                'seccion' => 'datos_generales',
                                'step_index' => 0
                            ],
                            [
                                'title' => 'Actividades',
                                'description' => 'Actividades económicas',
                                'seccion' => 'actividades',
                                'step_index' => 1
                            ],
                            [
                                'title' => 'Domicilio',
                                'description' => 'Dirección fiscal',
                                'seccion' => 'domicilio',
                                'step_index' => 2
                            ]
                        ];
                        
                        if ($tipoPersona === 'Moral') {
                            $steps[] = ['title' => 'Constitución', 'description' => 'Datos de constitución', 'seccion' => 'constitucion', 'step_index' => 3];
                            $steps[] = ['title' => 'Accionistas', 'description' => 'Información de accionistas', 'seccion' => 'accionistas', 'step_index' => 4];
                            $steps[] = ['title' => 'Apoderado', 'description' => 'Apoderado legal', 'seccion' => 'apoderado', 'step_index' => 5];
                            $steps[] = ['title' => 'Documentos', 'description' => 'Archivos requeridos', 'seccion' => 'archivos', 'step_index' => 6];
                            $steps[] = ['title' => 'Términos y Condiciones', 'description' => 'Confirmación final', 'seccion' => 'terminos', 'step_index' => 7];
                        } else {
                            $steps[] = ['title' => 'Documentos', 'description' => 'Archivos requeridos', 'seccion' => 'archivos', 'step_index' => 3];
                            $steps[] = ['title' => 'Términos y Condiciones', 'description' => 'Confirmación final', 'seccion' => 'terminos', 'step_index' => 4];
                        }
                    }
                @endphp

                <x-navigation.steps :steps="$steps" :current-step="0" :total-steps="$totalSteps" />

                @foreach($steps as $index => $step)
                    <div class="step-content {{ $index === 0 ? 'active' : '' }}" data-step="{{ $index }}">
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            @switch($step['seccion'])
                                @case('datos_generales')
                                    @include('components.forms.datos-generales', [
                                        'editable' => true, 
                                        'datosConstancia' => $viewModel
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('actividades')
                                    @include('components.forms.actividades-economicas', [
                                        'editable' => true,
                                        'actividadesSeleccionadas' => $viewModel
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('domicilio')
                                    @include('components.forms.domicilio', [
                                        'editable' => true, 
                                        'datosConstancia' => $viewModel
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('constitucion')
                                    @include('components.forms.constitucion', [
                                        'editable' => true,
                                        'datosConstitucion' => $viewModel ?? null
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('accionistas')
                                    @include('components.forms.accionistas', [
                                        'editable' => true,
                                        'accionistas' => $viewModel ?? null
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('apoderado')
                                    @include('components.forms.apoderado', [
                                        'editable' => true,
                                        'datosApoderado' => $viewModel ?? null
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('archivos')
                                    @include('components.forms.archivos-dinamicos', [
                                        'editable' => true, 
                                        'archivosRequeridos' => $archivosRequeridos,
                                        'tipoPersona' => $tipoPersona,
                                        'modoCorreccion' => isset($modoCorreccion) ? $modoCorreccion : false,
                                        'tramite' => isset($tramite) ? $tramite : null
                                    ])
                                    
                                    @if(isset($modoCorreccion) && $modoCorreccion)
                                        @include('components.revision.comentario-revisor', ['comentario' => $step['comentario'] ?? ''])
                                    @endif
                                    @break
                                
                                @case('terminos')
                                @case('confirmacion')
                                    <div class="mb-6">
                                        @if($step['seccion'] === 'confirmacion')
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirmar Correcciones</h3>
                                            <p class="text-gray-600 mb-4">Revise las correcciones realizadas antes de enviar:</p>
                                            
                                            @if(isset($resumenCorrecciones) && isset($seccionesParaCorregir))
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                                    <h4 class="font-medium text-gray-800 mb-2">Secciones que se están corrigiendo:</h4>
                                                    <ul class="text-sm text-gray-600 space-y-1">
                                                        @foreach($seccionesParaCorregir as $seccion)
                                                            <li>• {{ $seccion['nombre'] }}</li>
                                                        @endforeach
                                                    </ul>
                                                    @if(isset($resumenCorrecciones['total_correcciones']) && $resumenCorrecciones['total_correcciones'] > 0)
                                                        <p class="text-xs text-gray-500 mt-2">
                                                            Total de elementos a corregir: {{ $resumenCorrecciones['total_correcciones'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Términos y Condiciones</h3>
                                            <p class="text-gray-600 mb-4">Antes de enviar su trámite, por favor lea y acepte los siguientes términos y condiciones:</p>
                                        @endif
                                        
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
                                                He leído y acepto los términos y condiciones del trámite
                                            </label>
                                        </div>
                                        
                                        @error('acepto_terminos')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endforeach

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

<script type="module" src="{{ asset('js/validations/index.js') }}"></script>
<script src="{{ asset('js/tramites/data-loader.js') }}"></script>
<script src="{{ asset('js/tramites/create-form.js') }}"></script>
<script src="{{ asset('js/tramites/correction-validator.js') }}"></script>
<script src="{{ asset('js/revision/archivos-tiempo-real.js') }}"></script>

@if(isset($viewModel))
@php
    $datosDomicilio = $viewModel->getDatosDomicilioForm();
    $validationErrors = $errors->any() ? $errors->keys()->toArray() : [];
    
    $viewModelData = [
        'datosGenerales' => [
            'razon_social' => $viewModel->getDatosGenerales()['razon_social'] ?? '',
            'rfc' => $viewModel->getDatosGenerales()['rfc'] ?? '',
            'curp' => $viewModel->getDatosGenerales()['curp'] ?? ''
        ],
        'datosDomicilio' => [
            'calle' => $datosDomicilio['calle'] ?? '',
            'numero_exterior' => $datosDomicilio['numero_exterior'] ?? '',
            'numero_interior' => $datosDomicilio['numero_interior'] ?? '',
            'asentamiento' => $datosDomicilio['asentamiento'] ?? '',
            'codigo_postal' => $datosDomicilio['codigo_postal'] ?? '',
            'municipio' => $datosDomicilio['municipio'] ?? '',
            'estado' => $datosDomicilio['estado'] ?? ''
        ]
    ];
@endphp
<script>
window.viewModelData = JSON.parse('{{ json_encode($viewModelData) }}');
window.validationErrors = JSON.parse('{{ json_encode($validationErrors) }}');

document.addEventListener('DOMContentLoaded', function() {
    TramiteDataLoader.fromBladeData(window.viewModelData, window.validationErrors);
});
</script>
@endif

@if(session('success') && session('tramite_creado') === true)
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.tramiteCreado = true;
});
</script>
@endif

@if(isset($modoCorreccion) && $modoCorreccion)
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.modoCorreccion = true;
    console.log('Modo corrección activado');
});
</script>
@endif

@endsection 