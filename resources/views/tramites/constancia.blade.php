@extends('layouts.app')

@section('title', 'Cargar Constancia de Situación Fiscal')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#9D2449]/5 via-white to-[#B91C1C]/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                <div class="p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('tramites.index') }}"
                                class="inline-flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors group">
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-800" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <div
                                class="bg-gradient-to-br from-[#9D2449] via-[#B91C1C] to-[#7a1d37] rounded-xl p-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Cargar Constancia de Situación Fiscal</h1>
                                <p class="text-sm text-gray-500">Suba su constancia del SAT para extraer automáticamente sus
                                    datos fiscales</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-[#9D2449] rounded-full"></div>
                                <span class="text-xs font-medium text-gray-600">{{ ucfirst($tipo) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div id="upload-area"
                        class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-gray-400 transition-colors">
                        <div id="upload-content">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-lg font-semibold text-gray-800 mb-2">Seleccione su constancia PDF</p>
                            <p class="text-sm text-gray-500 mb-4">Haga clic en el botón para seleccionar el archivo</p>
                            <button type="button" id="select-file-btn"
                                class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white px-6 py-3 rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                Seleccionar Archivo
                            </button>
                        </div>
                        <div id="file-info" class="hidden">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p id="file-name" class="text-lg font-semibold text-gray-800 mb-2"></p>
                            <p id="file-size" class="text-sm text-gray-500 mb-2"></p>
                            <p class="text-sm text-emerald-600 font-medium">Procesando automáticamente...</p>
                        </div>
                    </div>
                    <input type="file" id="pdf-input" accept=".pdf" class="hidden">
                </div>

                <div id="loading" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-4"></div>
                        <p class="text-blue-800 font-medium">Procesando PDF y extrayendo código QR...</p>
                    </div>
                </div>

                <div id="loading-scraping" class="hidden bg-purple-50 border border-purple-200 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-600 mr-4"></div>
                        <p class="text-purple-800 font-medium">Extrayendo datos fiscales del SAT...</p>
                    </div>
                </div>

                <div id="success-result"
                    class="hidden bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-xl p-4 mb-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-emerald-800">¡Datos extraídos exitosamente!</p>
                                <p class="text-xs text-emerald-600">Su información fiscal está lista para usar</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button id="view-data-btn" type="button"
                                class="inline-flex items-center px-3 py-1.5 bg-white/80 border border-emerald-300 text-emerald-700 rounded-lg hover:bg-white hover:shadow-sm transition-all text-xs font-medium">
                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Ver Datos
                            </button>
                            <button id="continue-main-btn" type="button"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-md hover:shadow-lg text-sm font-semibold">
                                <span>Continuar</span>
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- RFC Mismatch Error - Error específico de RFC no coincidente -->
                <div id="rfc-mismatch-error" class="hidden bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <div
                            class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <div class="flex-1 ml-4">
                            <h3 class="text-lg font-semibold text-orange-800 mb-2">RFC no coincide</h3>
                            <p class="text-orange-700 mb-3">
                                El RFC de la constancia no coincide con el RFC registrado en su cuenta de usuario.
                            </p>
                            <div class="bg-orange-100 border border-orange-200 rounded-lg p-3 mb-4">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-orange-800">RFC de su cuenta:</span>
                                        <span id="user-rfc-display" class="text-orange-700 font-mono"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium text-orange-800">RFC de la constancia:</span>
                                        <span id="constancia-rfc-display" class="text-orange-700 font-mono"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-orange-100 border border-orange-200 rounded-lg p-3 mb-4">
                                <p class="text-sm text-orange-800">
                                    <strong>Importante:</strong> Debe cargar la constancia de situación fiscal que
                                    corresponda al RFC registrado en su cuenta.
                                    Si necesita cambiar el RFC de su cuenta asiste al modulo de proveedores.
                                </p>
                            </div>
                            <div class="mt-4">
                                <button id="retry-rfc-btn" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-[#9D2449] text-white rounded-lg hover:bg-[#8B1E3F] transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Cargar Constancia Correcta
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
                                    <strong>Nota:</strong> Es obligatorio cargar una constancia de situación fiscal válida
                                    para continuar con el trámite.
                                    Por favor, intente nuevamente con un archivo PDF emitido por el SAT.
                                </p>
                            </div>
                            <div class="mt-4">
                                <button id="retry-upload-btn" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-[#9D2449] text-white rounded-lg hover:bg-[#8B1E3F] transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Intentar Nuevamente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario oculto para enviar datos del SAT -->
                <form id="continue-form" method="POST" action="{{ route('tramites.procesarConstancia', $tipo) }}"
                    class="hidden">
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
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Instrucciones
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <span
                                    class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">1</span>
                                <p class="text-xs text-slate-700">Descargue su constancia de situación fiscal del portal
                                    del SAT</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span
                                    class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">2</span>
                                <p class="text-xs text-slate-700">Haga clic en "Seleccionar Archivo" para subir el PDF
                                    (máximo 5MB)</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <span
                                    class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">3</span>
                                <p class="text-xs text-slate-700">El sistema extraerá automáticamente el código QR y sus
                                    datos fiscales</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span
                                    class="bg-[#9D2449] text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">4</span>
                                <p class="text-xs text-slate-700">Una vez extraídos, podrá continuar con el formulario
                                    precargado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="{{ asset('js/constancia-extractor.js') }}"></script>
            <script src="{{ asset('js/sat-modal.js') }}"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const extractor = new ConstanciaExtractor({
                        debug: true
                    });

                    const modal = new SATModal({
                        onContinue: function() {
                            const form = document.getElementById('continue-form');
                            if (form) {
                                form.submit();
                            }
                            this.hide();
                        }
                    });

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
                    const rfcMismatchError = document.getElementById('rfc-mismatch-error');
                    const retryRfcBtn = document.getElementById('retry-rfc-btn');
                    const userRfcDisplay = document.getElementById('user-rfc-display');
                    const constanciaRfcDisplay = document.getElementById('constancia-rfc-display');

                    if (selectFileBtn && pdfInput) {
                        selectFileBtn.addEventListener('click', () => pdfInput.click());

                        pdfInput.addEventListener('change', async (e) => {
                            const file = e.target.files[0];
                            if (file) {
                                await processFile(file);
                            }
                        });
                    }

                    if (viewDataBtn) {
                        viewDataBtn.addEventListener('click', () => {
                            if (window.currentSATData) {
                                modal.show(window.currentSATData, window.currentQRUrl);
                            }
                        });
                    }

                    if (continueMainBtn) {
                        continueMainBtn.addEventListener('click', () => {
                            const form = document.getElementById('continue-form');
                            if (form && window.currentSATData) {
                                fillFormInputs(window.currentSATData);
                                form.submit();
                            }
                        });
                    }

                    if (retryUploadBtn) {
                        retryUploadBtn.addEventListener('click', () => {
                            pdfInput.click();
                        });
                    }

                    if (retryRfcBtn) {
                        retryRfcBtn.addEventListener('click', () => {
                            pdfInput.click();
                        });
                    }

                    async function processFile(file) {
                        updateFileInfo(file);
                        hideResults();

                        await extractor.extractWithCallbacks(file, {
                            onStart: () => showLoading(),
                            onProgress: (message) => {
                                if (message.includes('SAT')) {
                                    showLoadingScraping();
                                    hideLoading();
                                }
                            },
                            onSuccess: (satData, qrUrl) => {
                                const userRFC = '{{ Auth::user()->rfc ?? '' }}';
                                const constanciaRFC = satData.rfc || '';

                                if (userRFC && constanciaRFC && userRFC.toUpperCase() !== constanciaRFC
                                    .toUpperCase()) {
                                    showRFCMismatchError(userRFC, constanciaRFC);
                                    hideLoadingScraping();
                                    return;
                                }

                                window.currentSATData = satData;
                                window.currentQRUrl = qrUrl;

                                showSuccess();
                                hideLoadingScraping();
                            },
                            onError: (error) => {
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

                    function showRFCMismatchError(userRFC, constanciaRFC) {
                        if (rfcMismatchError) rfcMismatchError.classList.remove('hidden');
                        if (userRfcDisplay) userRfcDisplay.textContent = userRFC;
                        if (constanciaRfcDisplay) constanciaRfcDisplay.textContent = constanciaRFC;
                    }

                    function hideResults() {
                        if (successResult) successResult.classList.add('hidden');
                        if (errorResult) errorResult.classList.add('hidden');
                        if (rfcMismatchError) rfcMismatchError.classList.add('hidden');
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
                });
            </script>
        @endpush
    @endsection
