@extends('layouts.app')

@section('title', $titulo ?? 'Formulario de Trámite')

@section('content')
    <div class="min-h-screen bg-gradient-to-br">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 bg-white rounded-lg shadow-lg border border-gray-200 py-6">

            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                            @if ($tipo_tramite === 'inscripcion')
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @elseif($tipo_tramite === 'renovacion')
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-lg sm:text-xl font-bold text-slate-800 truncate">{{ $titulo }}</h1>
                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2">{{ $descripcion }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end sm:text-right">
                        <span
                            class="inline-block px-3 py-1 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white text-xs sm:text-sm font-medium rounded-full shadow-sm">
                            {{ ucfirst($tipo_tramite) }}
                        </span>
                        @if ($proveedor)
                            <p class="text-xs text-slate-500 mt-1 ml-3 sm:ml-0 truncate max-w-32 sm:max-w-none">
                                {{ $proveedor->razon_social }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <form id="formulario-tramite" method="POST" action="{{ route('tramites.store', $tipo_tramite) }}"
                enctype="multipart/form-data">
                @csrf

                @php
                    // Determinar tipo de persona basado en el RFC del usuario
                    $rfcUsuario = Auth::user()->rfc ?? ($datosSat['rfc'] ?? '');
                    $tipoPersona = 'Física'; // Default
                    
                    if ($rfcUsuario) {
                        // RFC de persona moral tiene 12 caracteres, persona física tiene 13
                        $tipoPersona = strlen($rfcUsuario) === 12 ? 'Moral' : 'Física';
                    }
                    
                    $totalSteps = $tipoPersona === 'Moral' ? 6 : 4;

                    $stepNames = [
                        1 => 'Datos Generales',
                        2 => 'Domicilio',
                        3 => $tipoPersona === 'Moral' ? 'Constitutivos' : 'Documentos',
                        4 => $tipoPersona === 'Moral' ? 'Apoderado' : 'Confirmación',
                        5 => $tipoPersona === 'Moral' ? 'Accionistas' : null,
                        6 => $tipoPersona === 'Moral' ? 'Documentos' : null,
                    ];
                @endphp
                
                <!-- Progress indicator elegante y responsive -->
                <div
                    class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6 mb-4 sm:mb-6">
                    <div class="hidden sm:block">
                        <div class="flex items-center justify-center text-sm">
                            <div class="flex items-center space-x-6 overflow-x-auto pb-2">
                                @for ($i = 1; $i <= $totalSteps; $i++)
                                    <div class="flex items-center text-slate-400 flex-shrink-0"
                                        data-step-indicator="{{ $i }}">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 border-2 border-slate-300 rounded-full text-xs transition-all duration-300">
                                            {{ $i }}</div>
                                        @if ($stepNames[$i])
                                            <span class="ml-2 font-medium whitespace-nowrap">{{ $stepNames[$i] }}</span>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="block sm:hidden">
                        <div class="flex items-center justify-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-full flex items-center justify-center text-white text-xs font-bold"
                                    id="mobile-current-step">
                                    1
                                </div>
                                <span class="text-sm font-medium text-slate-600" id="mobile-step-text">de
                                    {{ $totalSteps }}</span>
                            </div>
                            <div class="text-xs text-slate-500" id="mobile-step-name">{{ $stepNames[1] }}</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] h-2 rounded-full transition-all duration-500 ease-out"
                                style="width: {{ (1 / $totalSteps) * 100 }}%" id="progress-bar"></div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-6">

                    <!-- SECCIÓN 1: DATOS GENERALES -->
                    <div class="step-section" data-step="1">
                        @include('tramites.partials.datos-generales', [
                            'tipo' => $tipo_tramite, 
                            'proveedor' => $proveedor, 
                            'datosSat' => $datosSat, 
                            'editable' => true
                        ])
                    </div>

                    <!-- SECCIÓN 2: DOMICILIO -->
                    <div class="step-section hidden" data-step="2">
                        @include('tramites.partials.domicilio', [
                            'tipo' => $tipo_tramite, 
                            'proveedor' => $proveedor, 
                            'editable' => true
                        ])
                    </div>

                    @if ($tipoPersona === 'Moral')
                        <!-- SECCIÓN 3: DATOS CONSTITUTIVOS (Solo Persona Moral) -->
                        <div class="step-section hidden" data-step="3">
                            @include('tramites.partials.constitucion', [
                                'tipo' => $tipo_tramite, 
                                'proveedor' => $proveedor, 
                                'editable' => true
                            ])
                        </div>

                        <!-- SECCIÓN 4: APODERADO LEGAL (Solo Persona Moral) -->
                        <div class="step-section hidden" data-step="4">
                            @include('tramites.partials.apoderado', [
                                'tipo' => $tipo_tramite, 
                                'proveedor' => $proveedor
                            ])
                        </div>

                        <!-- SECCIÓN 5: ACCIONISTAS (Solo Persona Moral) -->
                        <div class="step-section hidden" data-step="5">
                            @include('tramites.partials.accionistas', [
                                'tipo' => $tipo_tramite, 
                                'proveedor' => $proveedor, 
                                'editable' => true
                            ])
                        </div>

                        <!-- SECCIÓN 6: DOCUMENTOS (Solo Persona Moral) -->
                        <div class="step-section hidden" data-step="6">
                            @include('tramites.partials.documentos', [
                                'tipo' => $tipo_tramite, 
                                'proveedor' => $proveedor, 
                                'editable' => true,
                                'tipoPersona' => $tipoPersona
                            ])
                        </div>
                    @else
                        <!-- SECCIÓN 3: DOCUMENTOS (Solo Persona Física) -->
                        <div class="step-section hidden" data-step="3">
                            @include('tramites.partials.documentos', [
                                'tipo' => $tipo_tramite, 
                                'proveedor' => $proveedor, 
                                'editable' => true,
                                'tipoPersona' => $tipoPersona
                            ])
                        </div>

                        <!-- SECCIÓN 4: CONFIRMACIÓN (Solo Persona Física) -->
                        <div class="step-section hidden" data-step="4">
                            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                                <!-- Encabezado con icono -->
                                <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                                            <i class="fas fa-check-circle text-xl"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-800">Confirmación</h2>
                                            <p class="text-sm text-gray-500 mt-1">Revise y confirme los datos ingresados</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-amber-800">Importante</h3>
                                            <p class="text-amber-700 text-sm mt-1">
                                                @if ($tipo_tramite === 'inscripcion')
                                                    Verifique que todos los datos sean correctos. Una vez
                                                    enviado, el proceso de inscripción será revisado por nuestro
                                                    equipo.
                                                @elseif($tipo_tramite === 'renovacion')
                                                    La renovación mantendrá su registro activo. Asegúrese de que
                                                    toda la información esté actualizada.
                                                @else
                                                    Los cambios realizados pueden requerir documentación de
                                                    soporte y revisión administrativa.
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="flex items-start space-x-3">
                                        <input type="checkbox" name="confirma_datos" required
                                            class="mt-1 h-4 w-4 text-[#9D2449] focus:ring-[#9D2449] border-slate-300 rounded">
                                        <span class="text-sm text-slate-700">
                                            Confirmo que los datos proporcionados son correctos y veraces.
                                            Entiendo que proporcionar información falsa puede resultar en la
                                            cancelación de mi registro.
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Botones de navegación mejorados -->
                <div class="mt-6 sm:mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
                        <button type="button" id="btn-anterior"
                            class="order-2 sm:order-1 w-full sm:w-auto px-4 sm:px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center"
                            disabled>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Anterior</span>
                        </button>

                        <div class="order-1 sm:order-2 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                            <button type="button" id="btn-guardar-borrador"
                                class="w-full sm:w-auto px-4 sm:px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 transition-all duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <span class="text-sm sm:text-base">Guardar Borrador</span>
                            </button>

                            <button type="button" id="btn-siguiente"
                                class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-xl hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                <span class="text-sm sm:text-base">Siguiente</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>

                            <button type="submit" id="btn-enviar"
                                class="hidden w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span class="text-sm sm:text-base">Enviar Trámite</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <!-- Módulos JavaScript separados -->
        <script src="{{ asset('js/tramites/formularios/formulario-navegacion.js') }}"></script>
        <script src="{{ asset('js/tramites/formularios/actividades-buscar.js') }}"></script>
        <script src="{{ asset('js/tramites/codigo-postal-handler.js') }}"></script>

                        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const totalSteps = {{ $totalSteps }};
                const stepNames = @json($stepNames);
                const tipoPersona = '{{ $tipoPersona }}';
                
                console.log('Formulario cargado:', {
                    totalSteps: totalSteps,
                    stepNames: stepNames,
                    tipoPersona: tipoPersona
                });
                
                console.log('Secciones encontradas:', document.querySelectorAll('.step-section').length);
                document.querySelectorAll('.step-section').forEach((section, index) => {
                    console.log(`Sección ${index + 1}:`, section.dataset.step, section);
                });
                
                // Inicializar navegación con lógica mejorada para botones
                window.formularioNavegacion = new FormularioNavegacion(totalSteps, stepNames, tipoPersona);
                window.actividadesBuscar = new ActividadesBuscar();
                window.codigoPostalHandler = new CodigoPostalHandler();       
            });
        </script>
    @endpush
@endsection 