@extends('layouts.app')

@section('title', 'Cargar Constancia')

@section('content')
    @include('components.loading-modal')
    @include('components.modals.general.error')
    @include('components.sat-data-modal')

    @php
        $mostrarFormulario = old('sat_rfc') || $errors->any();
    @endphp

    <div class="min-h-screen bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden border border-gray-200/70">
                <div class="p-4 sm:p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-gradient-to-br from-[#9D2449] via-[#B91C1C] to-[#7a1d37] rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Cargar Constancia</h1>
                                <p class="text-xs sm:text-sm text-gray-500">
                                    Sube tu constancia de situación fiscal para continuar con el trámite de 
                                    <strong>{{ ucfirst($tipo) }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 lg:p-8">
                    <!-- Información importante -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-blue-800 mb-1">Información Importante</h3>
                                <p class="text-sm text-blue-700 mb-2">
                                    Para continuar con el trámite de <strong>{{ ucfirst($tipo) }}</strong>, necesitamos que subas tu constancia de situación fiscal vigente. 
                                    Este documento es obligatorio y debe estar en formato PDF. Los datos se extraerán automáticamente.
                                </p>
                                <div class="bg-white border border-blue-200 rounded-md p-2">
                                    <p class="text-xs text-blue-600 font-medium">
                                        <strong>Tipo de trámite:</strong> {{ ucfirst($tipo) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('error') || $errors->any())
                        <div class="flex justify-center mb-6">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-100 border border-red-200 shadow-sm animate-fadeInUp">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs text-red-700 font-medium">
                                    @if (session('error'))
                                        {{ session('error') }}
                                    @else
                                        @if ($errors->has('document'))
                                            Formato de archivo no válido o archivo dañado
                                        @elseif($errors->has('sat_file'))
                                            No se pudo procesar el archivo fiscal
                                        @elseif($errors->has('sat_rfc'))
                                            ⚠️ La constancia no le pertenece
                                        @else
                                            Por favor, corrija los errores en los campos marcados
                                        @endif
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($errors->has('sat_rfc'))
                        <div class="mb-6">
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-red-800 mb-1">
                                            ⚠️ Constancia No Válida
                                        </h3>
                                        <p class="text-sm text-red-700 mb-2">
                                            La constancia que intentó cargar no le pertenece. Solo puede cargar constancias de su propia persona o empresa.
                                        </p>
                                        <div class="bg-white border border-red-200 rounded-md p-3">
                                            <p class="text-xs text-red-600 font-medium">
                                                <strong>Motivo:</strong> El RFC de la constancia no coincide con su RFC registrado en el sistema.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Formulario de carga -->
                    <form action="{{ route('tramites.procesar-constancia') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Área de carga de archivo -->
                        <div id="uploadArea" class="transition-all duration-300 ease-in-out min-h-[80px] {{ $mostrarFormulario ? 'hidden' : '' }}">
                            <div class="mt-1">
                                <label for="document" class="block text-xs font-medium text-gray-700 mb-0.5">
                                    <span class="block md:inline">Constancia de Situación Fiscal</span>
                                    <span class="text-xs text-gray-500 block md:inline md:ml-1">(PDF, máx. 5MB)</span>
                                </label>
                                <div class="relative">
                                    <input type="file" id="document" name="document" accept=".pdf" required class="hidden" onchange="uploadFile(this)">
                                    <label for="document" class="group flex flex-col items-center justify-center w-full h-16 border-2 border-dashed @error('document') border-red-300 bg-red-50/30 @elseif($errors->has('sat_file')) border-red-300 bg-red-50/30 @else border-[#9D2449]/20 bg-[#9D2449]/5 @enderror hover:border-[#9D2449] rounded-lg transition-all duration-300 cursor-pointer hover:bg-[#9D2449]/10">
                                        <div class="flex flex-col md:flex-row items-center space-y-0.5 md:space-y-0 md:space-x-2 px-3">
                                            <div class="transform group-hover:scale-110 transition-transform duration-300">
                                                @error('document')
                                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($errors->has('sat_file'))
                                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-[#9D2449]/70 group-hover:text-[#9D2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                @enderror
                                            </div>
                                            <div class="text-center md:text-left">
                                                <p class="@error('document') text-red-500 @elseif($errors->has('sat_file')) text-red-500 @else text-[#9D2449]/70 group-hover:text-[#9D2449] @enderror font-medium text-xs mb-0">
                                                    Haga clic para seleccionar archivo
                                                </p>
                                                <p class="text-xs @error('document') text-red-400 @elseif($errors->has('sat_file')) text-red-400 @else text-gray-500 @enderror" id="fileName">
                                                    PDF con QR
                                                </p>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                @error('document')
                                    <div class="mt-1">
                                        <span class="text-xs text-red-500 font-medium" id="document-error">{{ $message }}</span>
                                    </div>
                                @enderror
                                @error('sat_file')
                                    <div class="mt-1">
                                        <span class="text-xs text-red-500 font-medium" id="sat-file-error">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div id="processingStatus" class="hidden mt-3">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#9D2449]"></div>
                                        <span class="text-xs text-[#9D2449] font-medium">Extrayendo datos fiscales...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos extraídos -->
                        <div id="extractedData" class="hidden space-y-4">
                            <input type="hidden" id="qr_url" name="qr_url" value="{{ old('qr_url') }}">
                            <input type="hidden" id="sat_rfc" name="sat_rfc" value="{{ old('sat_rfc') }}">
                            <input type="hidden" id="sat_nombre" name="sat_nombre" value="{{ old('sat_nombre') }}">
                            <input type="hidden" id="sat_tipo_persona" name="sat_tipo_persona" value="{{ old('sat_tipo_persona') }}">
                            <input type="hidden" id="sat_email" name="sat_email" value="{{ old('sat_email') }}">
                            <input type="hidden" id="sat_curp" name="sat_curp" value="{{ old('sat_curp') }}">
                            <!-- Datos del domicilio -->
                            <input type="hidden" id="sat_calle" name="sat_calle" value="{{ old('sat_calle') }}">
                            <input type="hidden" id="sat_numero_exterior" name="sat_numero_exterior" value="{{ old('sat_numero_exterior') }}">
                            <input type="hidden" id="sat_numero_interior" name="sat_numero_interior" value="{{ old('sat_numero_interior') }}">
                            <input type="hidden" id="sat_colonia" name="sat_colonia" value="{{ old('sat_colonia') }}">
                            <input type="hidden" id="sat_cp" name="sat_cp" value="{{ old('sat_cp') }}">
                            <input type="hidden" id="sat_municipio" name="sat_municipio" value="{{ old('sat_municipio') }}">
                            <input type="hidden" id="sat_entidad_federativa" name="sat_entidad_federativa" value="{{ old('sat_entidad_federativa') }}">



                            <div class="flex justify-end mb-3">
                                <button type="button" onclick="showSatDataFromForm()" class="inline-flex items-center space-x-1.5 px-3 py-1.5 text-xs font-medium text-[#9D2449] bg-white border border-[#9D2449] rounded-md hover:bg-[#9D2449]/5 hover:border-[#9D2449]/80 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>Ver datos completos</span>
                                </button>
                            </div>
                        </div>

                        <!-- Requisitos del documento -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-800 mb-3">Requisitos del documento:</h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Formato PDF</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Tamaño máximo: 5 MB</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Debe contener código QR válido</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Documento vigente y legible</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span><strong>Importante:</strong> El RFC de la constancia debe coincidir con su RFC registrado</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('tramites.index') }}" 
                               class="flex-1 sm:flex-none px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 text-center">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white font-medium rounded-lg hover:from-[#8a1f40] hover:to-[#a51d1d] focus:outline-none focus:ring-2 focus:ring-[#9D2449] focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span>Continuar</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('vendor/pdfjs-dist/pdf.min.js') }}"></script>
    <script src="{{ asset('vendor/pdfjs-dist/pdf.worker.min.js') }}"></script>
    <script src="{{ asset('vendor/jsQR/jsQR.min.js') }}"></script>
    <script src="{{ asset('js/sat-qr-extractor/qr-extractor-simple.js') }}"></script>
    <script src="{{ asset('js/sat-qr-extractor/sat-scraper-simple.js') }}"></script>
    <script src="{{ asset('js/sat-qr-extractor/constancia-extractor.js') }}"></script>
    <script>
        let satDataGlobal = null;

        function determinarTipoPersona(rfc) {
            if (!rfc || typeof rfc !== "string") return "Física";
            const rfcLimpio = rfc.trim().toUpperCase();
            const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
            if (!rfcRegex.test(rfcLimpio)) return "Física";
            if (rfcLimpio.length === 13) return "Física";
            if (rfcLimpio.length === 12) return "Moral";
            return "Física";
        }

        class ConstanciaHandler {
            constructor() {
                this.extractor = new ConstanciaExtractor({ debug: true });
            }
            
            async processFile(file) {
                try {
                    const result = await this.extractor.extract(file);
                    if (result.success) {
                        return {
                            success: true,
                            sat_data: result.sat_data,
                            qr_url: result.qr_url,
                        };
                    } else {
                        return {
                            success: false,
                            error: result.error,
                        };
                    }
                } catch (error) {
                    return {
                        success: false,
                        error: "Error interno: " + error.message,
                    };
                }
            }
        }

        window.uploadFile = async function (input) {
            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const handler = new ConstanciaHandler();
                const fileNameEl = document.getElementById("fileName");
                if (fileNameEl) fileNameEl.textContent = file.name;
                const processingStatus = document.getElementById("processingStatus");
                if (processingStatus) processingStatus.classList.remove("hidden");
                const result = await handler.processFile(file);
                if (processingStatus) processingStatus.classList.add("hidden");
                if (result.success) {
                    satDataGlobal = result.sat_data;
                    fillHiddenInputs(result.sat_data);
                    showSatDataModal(result.sat_data);
                    showExtractedData();
                } else {
                    alert("Error al procesar el archivo: " + (result.error || "No se pudo extraer la información del código QR. Verifica que el archivo contenga un código QR válido de la constancia fiscal del SAT."));
                }
            }
        };

        function fillHiddenInputs(satData) {
            const fields = {
                sat_rfc: "rfc",
                sat_nombre: "nombre",
                sat_tipo_persona: "tipo_persona",
                sat_curp: "curp",
                sat_email: "email",
                sat_calle: "nombre_vialidad",
                sat_numero_exterior: "numero_exterior",
                sat_numero_interior: "numero_interior",
                sat_colonia: "colonia",
                sat_cp: "cp",
                sat_municipio: "municipio",
                sat_entidad_federativa: "entidad_federativa",
            };
            
            Object.entries(fields).forEach(([fieldId, dataKey]) => {
                const element = document.getElementById(fieldId);
                if (element && satData[dataKey]) {
                    element.value = satData[dataKey];
                }
            });
        }

        function showExtractedData() {
            const extractedData = document.getElementById("extractedData");
            const uploadArea = document.getElementById("uploadArea");
            
            if (extractedData) extractedData.classList.remove("hidden");
            if (uploadArea) uploadArea.classList.add("hidden");
        }

        // Función que necesita el modal
        window.showRegistrationForm = function() {
            showExtractedData();
        };

        window.showSatDataFromForm = function () {
            if (satDataGlobal) {
                if (typeof showSatDataModal === 'function') {
                    showSatDataModal(satDataGlobal);
                }
            }
        };
    </script>
    @endpush
@endsection 