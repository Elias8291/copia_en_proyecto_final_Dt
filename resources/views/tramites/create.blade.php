@extends('layouts.app')



@section('content')
<style>
    .step-content {
        display: none;
    }
    
    .step-content.active {
        display: block;
    }
    
    /* Animaciones personalizadas para el indicador de progreso */
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
    
    /* Efecto de brillo para el botón de envío */
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
    
    /* Efecto de confeti para el éxito */
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

    // Configurar el formulario con optimizaciones
    const tramiteForm = document.getElementById('tramite-form');
    
    if (tramiteForm) {
        tramiteForm.addEventListener('submit', function(e) {
            console.log('Tramite Form: Iniciando envío del formulario');
            
            const btnEnviar = document.getElementById('btn-enviar-tramite-final');
            if (btnEnviar) {
                btnEnviar.disabled = true;
                btnEnviar.classList.add('btn-enviar-loading');
                btnEnviar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
            }
            
            // Mostrar indicador de progreso
            mostrarIndicadorProgreso();
            
            // Timeout reducido a 10 segundos (más realista)
            setTimeout(() => {
                if (btnEnviar && btnEnviar.disabled) {
                    console.warn('Tramite Form: El formulario está tardando más de lo esperado');
                    actualizarIndicadorProgreso('Procesando archivos...', 75, 'Finalizando proceso...');
                }
            }, 5000); // 5 segundos
            
            // Timeout de seguridad reducido a 15 segundos
            setTimeout(() => {
                if (btnEnviar && btnEnviar.disabled) {
                    console.error('Tramite Form: El formulario parece estar colgado, reactivando botón');
                    btnEnviar.disabled = false;
                    btnEnviar.classList.remove('btn-enviar-loading');
                    btnEnviar.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Enviar Trámite
                    `;
                    
                    ocultarIndicadorProgreso(false);
                    
                    // Mostrar mensaje de error más específico
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4';
                    errorDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-700">
                                    <strong>Procesamiento lento:</strong> El envío está tardando más de lo normal. Esto puede deberse a:
                                </p>
                                <ul class="text-sm text-yellow-600 mt-1 ml-4 list-disc">
                                    <li>Archivos grandes siendo procesados</li>
                                    <li>Alta carga del servidor</li>
                                    <li>Conexión lenta a internet</li>
                                </ul>
                                <p class="text-sm text-yellow-700 mt-2">
                                    <strong>Recomendación:</strong> Espere unos segundos más. Si el problema persiste, intente nuevamente.
                                </p>
                            </div>
                        </div>
                    `;
                    
                    // Insertar el mensaje antes del formulario
                    tramiteForm.insertBefore(errorDiv, tramiteForm.firstChild);
                    
                    // Remover el mensaje después de 15 segundos
                    setTimeout(() => {
                        if (errorDiv.parentNode) {
                            errorDiv.remove();
                        }
                    }, 15000);
                }
            }, 15000); // 15 segundos de timeout
            
            // Detectar cuando el formulario se envía exitosamente
            // Esto se ejecutará cuando la página se recargue con éxito
            window.addEventListener('beforeunload', function() {
                // Si llegamos aquí, significa que el formulario se está enviando
                console.log('Tramite Form: Formulario enviándose...');
            });
        });
    }
    
    // Función para mostrar indicador de progreso
    function mostrarIndicadorProgreso() {
        const progressDiv = document.createElement('div');
        progressDiv.id = 'progress-indicator';
        progressDiv.className = 'fixed top-0 left-0 w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white z-50 shadow-lg';
        progressDiv.innerHTML = `
            <div class="flex items-center justify-center py-3 px-4">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="animate-spin rounded-full h-6 w-6 border-4 border-white border-t-transparent"></div>
                        <div class="absolute inset-0 rounded-full h-6 w-6 border-2 border-blue-300 animate-pulse"></div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold" id="progress-text">Iniciando envío...</span>
                        <span class="text-xs opacity-75" id="progress-subtitle">Por favor espere...</span>
                    </div>
                </div>
                <div class="ml-6 w-40 bg-blue-700 rounded-full h-3 shadow-inner">
                    <div class="bg-white h-3 rounded-full transition-all duration-300 shadow-sm" id="progress-bar" style="width: 10%"></div>
                </div>
                <div class="ml-4 text-xs font-medium" id="progress-percentage">10%</div>
            </div>
        `;
        document.body.appendChild(progressDiv);
        
        // Animar progreso inicial más rápido y con más feedback
        setTimeout(() => {
            actualizarIndicadorProgreso('Validando datos del formulario...', 25, 'Verificando información...');
        }, 200);
        
        setTimeout(() => {
            actualizarIndicadorProgreso('Procesando información del proveedor...', 45, 'Gestionando datos...');
        }, 600);
        
        setTimeout(() => {
            actualizarIndicadorProgreso('Guardando archivos...', 70, 'Procesando documentos...');
        }, 1000);
        
        setTimeout(() => {
            actualizarIndicadorProgreso('Finalizando trámite...', 90, 'Completando proceso...');
        }, 1400);
    }
    
    // Función para actualizar indicador de progreso
    function actualizarIndicadorProgreso(texto, porcentaje, subtitulo = '') {
        const progressText = document.getElementById('progress-text');
        const progressSubtitle = document.getElementById('progress-subtitle');
        const progressBar = document.getElementById('progress-bar');
        const progressPercentage = document.getElementById('progress-percentage');
        
        if (progressText) progressText.textContent = texto;
        if (progressSubtitle && subtitulo) progressSubtitle.textContent = subtitulo;
        if (progressBar) progressBar.style.width = porcentaje + '%';
        if (progressPercentage) progressPercentage.textContent = porcentaje + '%';
        
        // Efecto de pulso en el botón de envío
        const btnEnviar = document.getElementById('btn-enviar-tramite-final');
        if (btnEnviar) {
            btnEnviar.classList.add('animate-pulse');
        }
    }
    
    // Función para ocultar indicador de progreso con efecto de éxito
    function ocultarIndicadorProgreso(conExito = false) {
        const progressDiv = document.getElementById('progress-indicator');
        if (progressDiv) {
            if (conExito) {
                // Efecto de éxito antes de ocultar
                progressDiv.className = 'fixed top-0 left-0 w-full bg-gradient-to-r from-green-600 to-green-800 text-white z-50 shadow-lg transition-all duration-500';
                progressDiv.innerHTML = `
                    <div class="flex items-center justify-center py-3 px-4">
                        <div class="flex items-center space-x-4">
                            <div class="text-2xl">✅</div>
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold">¡Trámite enviado exitosamente!</span>
                                <span class="text-xs opacity-75">Redirigiendo...</span>
                            </div>
                        </div>
                    </div>
                `;
                
                // Ocultar después de mostrar el éxito
                setTimeout(() => {
                    progressDiv.remove();
                }, 1500);
            } else {
                progressDiv.remove();
            }
        }
        
        // Remover efecto de pulso del botón
        const btnEnviar = document.getElementById('btn-enviar-tramite-final');
        if (btnEnviar) {
            btnEnviar.classList.remove('animate-pulse', 'btn-enviar-loading');
        }
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
    
    // Detectar si hay mensaje de éxito en la sesión SOLO si viene de un envío exitoso
    @if(session('success') && session('tramite_creado') === true)
        // Mostrar efecto de éxito solo si el trámite se creó exitosamente
        mostrarEfectoExito();
    @endif
});

// Función para mostrar efecto de éxito
function mostrarEfectoExito() {
    // Crear confeti
    crearConfeti();
    
    // Crear overlay de éxito
    const successOverlay = document.createElement('div');
    successOverlay.id = 'success-overlay';
    successOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    successOverlay.innerHTML = `
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 text-center shadow-2xl transform transition-all duration-500 scale-95 success-bounce">
            <div class="text-6xl mb-4 success-bounce">🎉</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">¡Trámite Creado Exitosamente!</h3>
            <p class="text-gray-600 mb-6">Su trámite ha sido procesado y enviado correctamente.</p>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-green-700">
                    <strong>Estado:</strong> Procesado y enviado correctamente
                </p>
            </div>
            <button onclick="cerrarEfectoExito()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition-colors duration-200">
                Continuar
            </button>
        </div>
    `;
    
    document.body.appendChild(successOverlay);
    
    // Animar entrada
    setTimeout(() => {
        const modal = successOverlay.querySelector('div');
        modal.classList.remove('scale-95');
        modal.classList.add('scale-100');
    }, 100);
}

// Función para crear confeti
function crearConfeti() {
    const colors = ['#f00', '#0f0', '#00f', '#ff0', '#f0f', '#0ff'];
    
    for (let i = 0; i < 50; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDelay = Math.random() * 3 + 's';
            confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
            
            document.body.appendChild(confetti);
            
            // Remover confeti después de la animación
            setTimeout(() => {
                if (confetti.parentNode) {
                    confetti.remove();
                }
            }, 5000);
        }, i * 100);
    }
}

// Función para cerrar efecto de éxito
function cerrarEfectoExito() {
    const successOverlay = document.getElementById('success-overlay');
    if (successOverlay) {
        const modal = successOverlay.querySelector('div');
        modal.classList.remove('scale-100');
        modal.classList.add('scale-95');
        
        setTimeout(() => {
            successOverlay.remove();
        }, 300);
    }
}
</script>

<script src="{{ asset('js/revision/archivos-tiempo-real.js') }}"></script>
@endsection 