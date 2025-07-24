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

                <input type="hidden" name="test_campo_1" value="test_value_1">
                <input type="hidden" name="test_campo_2" value="test_value_2">
                <input type="hidden" name="tipo_tramite_hidden" value="{{ $tipo_tramite }}">

                @php
                    $rfcUsuario = Auth::user()->rfc ?? ($datosSat['rfc'] ?? '');
                    $tipoPersona = 'Física'; // Default

                    if ($rfcUsuario) {
                        $tipoPersona = strlen($rfcUsuario) === 12 ? 'Moral' : 'Física';
                    }
                    $totalSteps = $tipoPersona === 'Moral' ? 6 : 4;

                    $stepNames =
                        $tipoPersona === 'Moral'
                            ? [
                                1 => 'Datos Generales',
                                2 => 'Domicilio',
                                3 => 'Constitutivos',
                                4 => 'Apoderado',
                                5 => 'Accionistas',
                                6 => 'Documentos',
                            ]
                            : [
                                1 => 'Datos Generales',
                                2 => 'Domicilio',
                                3 => 'Documentos',
                                4 => 'Confirmación',
                            ];
                @endphp


                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 mb-6">
                    <div class="flex items-center justify-between relative">
                        <!-- Línea de progreso de fondo -->
                        <div class="absolute top-4 left-0 right-0 h-0.5 bg-gray-200 z-0"></div>
                        <!-- Línea de progreso activa -->
                        <div class="absolute top-4 left-0 h-0.5 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] z-10 transition-all duration-500"
                            style="width: {{ (1 / $totalSteps) * 100 }}%" id="progress-line"></div>

                        @foreach ($stepNames as $stepNumber => $stepName)
                            <div class="flex flex-col items-center relative z-20 cursor-pointer group"
                                data-step="{{ $stepNumber }}" title="Ir a: {{ $stepName }}">
                
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold transition-all duration-300 step-circle group-hover:scale-110
                                    {{ $stepNumber === 1 ? 'bg-gradient-to-br from-[#9D2449] to-[#B91C1C] text-white shadow-md' : 'bg-gray-200 text-gray-500' }}"
                                    id="step-circle-{{ $stepNumber }}">
                                    <span class="step-number">{{ $stepNumber }}</span>
                                </div>

                
                                <div class="mt-2 text-center">
                                    <p class="text-xs font-medium transition-colors duration-300 step-label group-hover:text-[#9D2449] max-w-16 truncate
                                        {{ $stepNumber === 1 ? 'text-[#9D2449]' : 'text-gray-500' }}"
                                        id="step-label-{{ $stepNumber }}">
                                        {{ $stepName }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Paso <span id="current-step-text">1</span> de {{ $totalSteps }}:
                            <span class="font-semibold text-[#9D2449]" id="current-step-name">{{ $stepNames[1] }}</span>
                        </p>
                    </div>
                </div>

                <div class="space-y-8">

                    <div class="section-container step-section" data-step="1" id="step-section-1">
                        @include('tramites.partials.datos-generales', [
                            'tipo' => $tipo_tramite,
                            'proveedor' => $proveedor,
                            'datosSat' => $datosSat,
                            'editable' => true,
                        ])
                    </div>



                    <div class="section-container step-section" data-step="2" id="step-section-2">
                        @include('tramites.partials.domicilio', [
                            'tipo' => $tipo_tramite,
                            'proveedor' => $proveedor,
                            'editable' => true,
                        ])
                    </div>

                    @if ($tipoPersona === 'Moral')
                        <div class="section-container step-section" data-step="3" id="step-section-3">
                            @include('tramites.partials.constitucion', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'editable' => true,
                            ])
                        </div>

                        <div class="section-container step-section" data-step="4" id="step-section-4">
                            @include('tramites.partials.apoderado', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                            ])
                        </div>

                        <div class="section-container step-section" data-step="5" id="step-section-5">
                            @include('tramites.partials.accionistas', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'editable' => true,
                            ])
                        </div>

                        <div class="section-container step-section" data-step="6" id="step-section-6">
                            @include('tramites.partials.documentos', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'editable' => true,
                                'tipoPersona' => $tipoPersona,
                            ])
                        </div>
                    @else
                        <div class="section-container step-section" data-step="3" id="step-section-3">
                            @include('tramites.partials.documentos', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'editable' => true,
                                'tipoPersona' => $tipoPersona,
                            ])
                        </div>
                    @endif


                    <div class="section-container step-section" data-step="{{ $tipoPersona === 'Moral' ? 6 : 4 }}"
                        id="step-section-{{ $tipoPersona === 'Moral' ? 6 : 4 }}">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">

                            <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="h-14 w-14 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-800">Confirmación Final</h2>
                                        <p class="text-sm text-gray-600 mt-1">Revise cuidadosamente toda la información
                                            antes de enviar</p>
                                    </div>
                                </div>
                            </div>


                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Resumen de su Trámite</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-blue-800">Tipo de trámite:</span>
                                                <span class="text-blue-700 ml-2">{{ ucfirst($tipo_tramite) }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-blue-800">Tipo de persona:</span>
                                                <span class="text-blue-700 ml-2">{{ $tipoPersona }}</span>
                                            </div>
                                            @if ($proveedor)
                                                <div class="md:col-span-2">
                                                    <span class="font-medium text-blue-800">Empresa:</span>
                                                    <span class="text-blue-700 ml-2">{{ $proveedor->razon_social }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-6 h-6 text-amber-600 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-amber-800 mb-2">Información Importante</h3>
                                        <div class="space-y-2 text-sm text-amber-700">
                                            @if ($tipo_tramite === 'inscripcion')
                                                <p>• <strong>Proceso de revisión:</strong> Su solicitud será evaluada por
                                                    nuestro equipo técnico en un plazo de 5 a 10 días hábiles.</p>
                                                <p>• <strong>Documentación:</strong> Asegúrese de que todos los documentos
                                                    estén legibles y actualizados.</p>
                                                <p>• <strong>Notificaciones:</strong> Recibirá actualizaciones del proceso
                                                    por correo electrónico y SMS.</p>
                                                <p>• <strong>Vigencia:</strong> Una vez aprobado, su registro tendrá
                                                    vigencia de 1 año.</p>
                                            @elseif($tipo_tramite === 'renovacion')
                                                <p>• <strong>Continuidad del servicio:</strong> Su registro actual
                                                    permanecerá activo durante el proceso de renovación.</p>
                                                <p>• <strong>Documentos actualizados:</strong> Verifique que toda la
                                                    información esté actualizada al año en curso.</p>
                                                <p>• <strong>Tiempo de procesamiento:</strong> Las renovaciones se procesan
                                                    en 3 a 5 días hábiles.</p>
                                                <p>• <strong>Nueva vigencia:</strong> Su registro renovado tendrá vigencia
                                                    por 1 año adicional.</p>
                                            @else
                                                <p>• <strong>Modificaciones:</strong> Los cambios realizados pueden requerir
                                                    documentación de soporte adicional.</p>
                                                <p>• <strong>Revisión administrativa:</strong> Nuestro equipo verificará la
                                                    información actualizada.</p>
                                                <p>• <strong>Tiempo de procesamiento:</strong> Las modificaciones se
                                                    procesan en 3 a 7 días hábiles.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                                <h3 class="text-base font-semibold text-gray-800 mb-4">Términos y Condiciones</h3>
                                <div class="space-y-3 text-sm text-gray-700">
                                    <p>Al enviar este formulario, usted acepta y declara que:</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4">
                                        <li>Toda la información proporcionada es veraz, completa y actualizada.</li>
                                        <li>Comprende que proporcionar información falsa puede resultar en la cancelación
                                            inmediata de su registro.</li>
                                        <li>Autoriza la verificación de los datos proporcionados con las autoridades
                                            competentes.</li>
                                        <li>Se compromete a notificar cualquier cambio en la información registrada dentro
                                            de los 30 días siguientes.</li>
                                        <li>Acepta recibir comunicaciones oficiales relacionadas con su trámite por los
                                            medios proporcionados.</li>
                                        <li>Ha leído y acepta los términos del aviso de privacidad disponible en nuestro
                                            sitio web.</li>
                                    </ul>
                                </div>
                            </div>


                            <div class="space-y-4">
                                <label
                                    class="flex items-start space-x-3 p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="confirma_datos" required
                                        class="mt-1 h-5 w-5 text-[#9D2449] focus:ring-[#9D2449] border-gray-300 rounded">
                                    <span class="text-sm text-gray-700 leading-relaxed">
                                        <strong>Confirmo la veracidad de los datos:</strong> Declaro bajo protesta de decir
                                        verdad que todos los datos proporcionados en este formulario son correctos,
                                        completos y actualizados. Entiendo que cualquier información falsa o inexacta puede
                                        resultar en la cancelación de mi registro y las sanciones legales correspondientes.
                                    </span>
                                </label>

                                <label
                                    class="flex items-start space-x-3 p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="acepta_terminos" required
                                        class="mt-1 h-5 w-5 text-[#9D2449] focus:ring-[#9D2449] border-gray-300 rounded">
                                    <span class="text-sm text-gray-700 leading-relaxed">
                                        <strong>Acepto términos y condiciones:</strong> He leído, entendido y acepto todos
                                        los términos y condiciones mencionados anteriormente, así como el aviso de
                                        privacidad. Autorizo el tratamiento de mis datos personales conforme a la
                                        normatividad vigente.
                                    </span>
                                </label>

                                <label
                                    class="flex items-start space-x-3 p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="autoriza_comunicaciones" required
                                        class="mt-1 h-5 w-5 text-[#9D2449] focus:ring-[#9D2449] border-gray-300 rounded">
                                    <span class="text-sm text-gray-700 leading-relaxed">
                                        <strong>Autorizo comunicaciones:</strong> Consiento recibir notificaciones,
                                        actualizaciones y comunicaciones oficiales relacionadas con mi trámite a través de
                                        correo electrónico, SMS y otros medios de contacto proporcionados.
                                    </span>
                                </label>
                            </div>


                            <div class="mt-8 p-4 bg-green-50 border border-green-200 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-semibold text-green-800">¡Casi terminamos!</h4>
                                        <p class="text-sm text-green-700 mt-1">
                                            Una vez que envíe su solicitud, recibirá un número de folio para dar seguimiento
                                            a su trámite.
                                            Conserve este número para futuras consultas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mt-8 bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-medium text-gray-700">Progreso del formulario</span>
                        <span class="text-sm font-semibold text-[#9D2449]"
                            id="progress-percentage">{{ round((1 / $totalSteps) * 100) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ round((1 / $totalSteps) * 100) }}%" id="progress-bar-bottom"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                        <span>Paso <span id="current-step-bottom">1</span> de {{ $totalSteps }}</span>
                        <span id="current-step-name-bottom">{{ $stepNames[1] }}</span>
                    </div>
                </div>


                <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">

                        <button type="button" id="btn-anterior"
                            class="hidden w-full sm:w-auto px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 transition-all duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Anterior</span>
                        </button>


                        <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <button type="button" id="btn-guardar-borrador"
                                class="w-full sm:w-auto px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 transition-all duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <span class="text-sm sm:text-base">Guardar Borrador</span>
                            </button>
                        </div>


                        <button type="button" id="btn-siguiente"
                            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-xl hover:from-[#8a203f] hover:to-[#a91b1b] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <span class="text-sm sm:text-base" id="btn-siguiente-text">Siguiente</span>
                            <svg class="w-4 h-4 ml-2" id="btn-siguiente-icon" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>


                        <button type="button" id="btn-enviar" style="display: none;"
                            class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="text-sm sm:text-base">Enviar Trámite</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/stepper.css') }}">
        <link rel="stylesheet" href="{{ asset('css/form-validator.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('js/tramites/handlers/actividades-buscar.js') }}"></script>
        <script src="{{ asset('js/tramites/handlers/codigo-postal-handler.js') }}"></script>
        <script src="{{ asset('js/tramites/core/form-validator.js') }}"></script>
        <script src="{{ asset('js/tramites/core/form-stepper-manager.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof ActividadesBuscar !== 'undefined') {
                    window.actividadesBuscar = new ActividadesBuscar();
                }

                if (typeof TramiteFormManager !== 'undefined') {
                    const formElement = document.getElementById('formulario-tramite');
                    if (formElement) {
                        window.tramiteFormManager = new TramiteFormManager(formElement);
                    }
                }

                if (typeof CodigoPostalHandler !== 'undefined') {
                    window.codigoPostalHandler = new CodigoPostalHandler();
                }

                if (typeof FormStepperManager !== 'undefined') {
                    window.formStepperManager = new FormStepperManager({{ $totalSteps }}, '{{ $tipoPersona }}');
                }
            });
        </script>
    @endpush
@endsection
