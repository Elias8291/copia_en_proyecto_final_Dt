@extends('layouts.app')

@section('title', 'Cargar Constancia de Situación Fiscal')

@section('content')
    <div class="min-h-screen bg-gradient-to-br">
        <div class="max-w-4xl mx-auto">
            <!-- Header compacto -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/50 p-4 mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">Cargar Constancia de Situación Fiscal</h1>
                        <p class="text-sm text-slate-600">Suba su constancia del SAT para extraer automáticamente sus datos fiscales</p>
                    </div>
                    <div class="ml-auto">
                        <span class="inline-flex items-center px-2 py-1 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white text-xs font-medium rounded-full">
                            {{ ucfirst($tipo) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Upload Area -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 mb-3">
                <div id="upload-area" class="border-2 border-dashed border-slate-300 rounded-lg p-5 text-center">
                    <div id="upload-content">
                        <div class="w-14 h-14 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <p class="text-base font-semibold text-slate-800 mb-1">Seleccione su constancia PDF</p>
                        <p class="text-xs text-slate-500 mb-3">Haga clic en el botón para seleccionar el archivo</p>
                        <button type="button" id="select-file-btn" class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white px-5 py-2 rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                            Seleccionar Archivo
                        </button>
                    </div>
                    <div id="file-info" class="hidden">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p id="file-name" class="text-base font-semibold text-slate-800 mb-1"></p>
                        <p id="file-size" class="text-xs text-slate-500 mb-2"></p>
                        <p class="text-xs text-emerald-600 font-medium">Procesando automáticamente...</p>
                    </div>
                </div>
                <input type="file" id="pdf-input" accept=".pdf" class="hidden">
            </div>

            <!-- Loading States -->
            <div id="loading" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-3"></div>
                    <p class="text-blue-800 font-medium text-sm">Procesando PDF y extrayendo código QR...</p>
                </div>
            </div>

            <div id="loading-scraping" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-purple-600 mr-3"></div>
                    <p class="text-purple-800 font-medium text-sm">Extrayendo datos fiscales del SAT...</p>
                </div>
            </div>

            <!-- Success State - Botón principal para continuar -->
            <div id="success-result" class="hidden bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-4">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 ml-4">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-2">¡Datos fiscales extraídos exitosamente!</h3>
                        <p class="text-emerald-700 mb-4">Sus datos fiscales han sido procesados correctamente. Puede continuar con el trámite.</p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button id="view-data-btn" type="button" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Datos Extraídos
                            </button>
                            <button id="continue-main-btn" type="button"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-md hover:shadow-lg font-semibold text-base">
                                <span>Continuar con el Trámite</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Result - SIN botón de continuar sin datos -->
            <div id="error-result" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 ml-4">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Error al procesar la constancia</h3>
                        <p id="error-message" class="text-red-700 mb-3"></p>
                        <div class="bg-red-100 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>Nota:</strong> Es obligatorio cargar una constancia de situación fiscal válida para continuar con el trámite.
                                Por favor, intente nuevamente con un archivo PDF emitido por el SAT.
                            </p>
                        </div>
                        <div class="mt-4">
                            <button id="retry-upload-btn" type="button"
                                class="inline-flex items-center px-4 py-2 bg-[#9D2449] text-white rounded-lg hover:bg-[#8B1E3F] transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Intentar Nuevamente
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario oculto para enviar datos del SAT -->
            <form id="continue-form" method="POST" action="{{ route('tramites.procesarConstancia', $tipo) }}" class="hidden">
                @csrf
                <input type="hidden" name="sat_rfc" id="sat-rfc-input">
                <input type="hidden" name="sat_nombre" id="sat-nombre-input">
                <input type="hidden" name="sat_tipo_persona" id="sat-tipo-persona-input">
                <input type="hidden" name="sat_curp" id="sat-curp-input">
                <input type="hidden" name="sat_cp" id="sat-cp-input">
                <input type="hidden" name="sat_colonia" id="sat-colonia-input">
                <input type="hidden" name="sat_nombre_vialidad" id="sat-nombre-vialidad-input">
                <input type="hidden" name="sat_numero_exterior" id="sat-numero-exterior-input">
                <input type="hidden" name="sat_numero_interior" id="sat-numero-interior-input">
            </form>

            <!-- Instructions -->
            <div class="bg-slate-50 rounded-xl border border-slate-200/50 p-5">
                <h3 class="text-base font-semibold text-slate-800 mb-3 flex items-center">
                    <div class="w-6 h-6 bg-slate-200 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Instrucciones
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <span class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">1</span>
                            <p class="text-xs text-slate-700">Descargue su constancia de situación fiscal del portal del SAT</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">2</span>
                            <p class="text-xs text-slate-700">Haga clic en "Seleccionar Archivo" para subir el PDF (máximo 5MB)</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <span class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">3</span>
                            <p class="text-xs text-slate-700">El sistema extraerá automáticamente el código QR y sus datos fiscales</p>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">4</span>
                            <p class="text-xs text-slate-700">Una vez extraídos, podrá continuar con el formulario precargado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Usar componentes reutilizables -->
        <script src="{{ asset('js/constancia-extractor.js') }}"></script>
        <script src="{{ asset('js/sat-modal.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Inicializando página de constancia con componentes reutilizables...');
                
                // Inicializar componentes reutilizables
                const extractor = new ConstanciaExtractor({ debug: true });
                
                const modal = new SATModal({
                    onContinue: function() {
                        // Continuar con el trámite (enviar formulario)
                        const form = document.getElementById('continue-form');
                        if (form) {
                            form.submit();
                        }
                        this.hide();
                    }
                });

                // Referencias DOM
                const selectFileBtn = document.getElementById('select-file-btn');
                const pdfInput = document.getElementById('pdf-input');
                const uploadContent = document.getElementById('upload-content');
                const fileInfo = document.getElementById('file-info');
                const fileName = document.getElementById('file-name');
                const fileSize = document.getElementById('file-size');
                const loading = document.getElementById('loading');
                const loadingScraping = document.getElementById('loading-scraping');
                const successResult = document.getElementById('success-result');
                const errorResult = document.getElementById('error-result');
                const errorMessage = document.getElementById('error-message');
                const viewDataBtn = document.getElementById('view-data-btn');
                const continueMainBtn = document.getElementById('continue-main-btn');
                const retryUploadBtn = document.getElementById('retry-upload-btn');

                // Evento para seleccionar archivo
                if (selectFileBtn && pdfInput) {
                    selectFileBtn.addEventListener('click', () => pdfInput.click());
                    
                    pdfInput.addEventListener('change', async (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            await processFile(file);
                        }
                    });
                }

                // Evento para ver datos
                if (viewDataBtn) {
                    viewDataBtn.addEventListener('click', () => {
                        if (window.currentSATData) {
                            modal.show(window.currentSATData, window.currentQRUrl);
                        }
                    });
                }

                // Evento para continuar (botón principal)
                if (continueMainBtn) {
                    continueMainBtn.addEventListener('click', () => {
                        const form = document.getElementById('continue-form');
                        if (form && window.currentSATData) {
                            fillFormInputs(window.currentSATData);
                            form.submit();
                        }
                    });
                }

                // Evento para reintentar
                if (retryUploadBtn) {
                    retryUploadBtn.addEventListener('click', () => {
                        pdfInput.click();
                    });
                }

                // Función principal de procesamiento
                async function processFile(file) {
                    console.log('📄 Procesando archivo:', file.name);
                    
                    // Actualizar UI
                    updateFileInfo(file);
                    hideResults();

                    // Procesar con componentes reutilizables
                    await extractor.extractWithCallbacks(file, {
                        onStart: () => showLoading(),
                        onProgress: (message) => {
                            if (message.includes('SAT')) {
                                showLoadingScraping();
                                hideLoading();
                            }
                        },
                        onSuccess: (satData, qrUrl) => {
                            console.log('✅ Datos extraídos exitosamente:', satData);
                            
                            // Guardar datos globalmente
                            window.currentSATData = satData;
                            window.currentQRUrl = qrUrl;
                            
                            // Mostrar éxito
                            showSuccess();
                            hideLoadingScraping();
                        },
                        onError: (error) => {
                            console.error('❌ Error:', error);
                            showError(error);
                            hideLoading();
                            hideLoadingScraping();
                        },
                        onFinish: () => {
                            hideLoading();
                            hideLoadingScraping();
                        }
                    });
                }

                // Funciones auxiliares
                function updateFileInfo(file) {
                    if (fileName) fileName.textContent = file.name;
                    if (fileSize) fileSize.textContent = formatFileSize(file.size);
                    if (uploadContent) uploadContent.classList.add('hidden');
                    if (fileInfo) fileInfo.classList.remove('hidden');
                }

                function showLoading() {
                    if (loading) loading.classList.remove('hidden');
                }

                function hideLoading() {
                    if (loading) loading.classList.add('hidden');
                }

                function showLoadingScraping() {
                    if (loadingScraping) loadingScraping.classList.remove('hidden');
                }

                function hideLoadingScraping() {
                    if (loadingScraping) loadingScraping.classList.add('hidden');
                }

                function showSuccess() {
                    if (successResult) successResult.classList.remove('hidden');
                }

                function showError(message) {
                    if (errorResult) errorResult.classList.remove('hidden');
                    if (errorMessage) errorMessage.textContent = message;
                }

                function hideResults() {
                    if (successResult) successResult.classList.add('hidden');
                    if (errorResult) errorResult.classList.add('hidden');
                }

                function fillFormInputs(satData) {
                    const fields = {
                        'sat-rfc-input': 'rfc',
                        'sat-nombre-input': 'nombre',
                        'sat-tipo-persona-input': 'tipo_persona',
                        'sat-curp-input': 'curp',
                        'sat-cp-input': 'cp',
                        'sat-colonia-input': 'colonia',
                        'sat-nombre-vialidad-input': 'nombre_vialidad',
                        'sat-numero-exterior-input': 'numero_exterior',
                        'sat-numero-interior-input': 'numero_interior'
                    };

                    Object.entries(fields).forEach(([fieldId, dataKey]) => {
                        const element = document.getElementById(fieldId);
                        if (element && satData[dataKey]) {
                            element.value = satData[dataKey];
                        }
                    });
                }

                function formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                }

                console.log('✅ Sistema de constancia inicializado con componentes reutilizables');
            });
        </script>
    @endpush
@endsection