@extends('layouts.app')

@section('title', 'Cargar Constancia de Situación Fiscal')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-primary to-primary-dark px-6 py-6">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('tramites.index') }}" 
                           class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-xl font-bold text-white">Cargar Constancia Fiscal</h1>
                            <p class="text-white/80 text-sm">Suba su constancia del SAT para extraer datos automáticamente</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    
                    <!-- Upload Area -->
                    <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition-colors">
                        <div id="upload-content">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Seleccione su constancia PDF</h3>
                            <p class="text-gray-500 text-sm mb-6">Haga clic para seleccionar el archivo</p>
                            <button type="button" id="select-file-btn" 
                                    class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                Seleccionar Archivo
                            </button>
                        </div>
                        
                        <div id="file-info" class="hidden">
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-green-600 font-medium">Procesando automáticamente...</p>
                        </div>
                    </div>
                    
                    <input type="file" id="pdf-input" accept=".pdf" class="hidden">

                    <!-- Loading State -->
                    <div id="loading" class="hidden mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-center">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-3"></div>
                            <p class="text-blue-800 font-medium">Procesando constancia fiscal...</p>
                        </div>
                    </div>

                    <!-- Success Result -->
                    <div id="success-result" class="hidden mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">¡Datos extraídos exitosamente!</p>
                                    <p class="text-xs text-green-600">Su información fiscal está lista</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="view-data-btn" type="button" onclick="showSatDataModal(satDataGlobal)"
                                        class="px-3 py-1.5 bg-white border border-green-300 text-green-700 rounded-lg hover:bg-green-50 transition-colors text-xs font-medium">
                                    Ver Datos
                                </button>
                                <button id="continue-btn" type="button"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium">
                                    Continuar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Error Result -->
                    <div id="error-result" class="hidden mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-red-800 mb-2">Error al procesar</h3>
                                <p id="error-message" class="text-red-700 mb-3"></p>
                                <button id="retry-btn" type="button"
                                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                                    Intentar Nuevamente
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Form -->
                    <form id="continue-form" method="POST" action="{{ route('tramites.procesarConstancia', $tipo) }}" class="hidden" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="pdf_file" id="pdf-file-input" accept=".pdf" class="hidden">
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
                    <div class="mt-6 bg-gray-50 rounded-lg p-4">
                        <h3 class="text-base font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Instrucciones
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div class="space-y-2">
                                <div class="flex items-start space-x-2">
                                    <span class="bg-primary text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">1</span>
                                    <p>Descargue su constancia del portal del SAT</p>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="bg-primary text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">2</span>
                                    <p>Haga clic en "Seleccionar Archivo" para subir el PDF</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-start space-x-2">
                                    <span class="bg-primary text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">3</span>
                                    <p>El sistema extraerá automáticamente los datos fiscales</p>
                                </div>
                                <div class="flex items-start space-x-2">
                                    <span class="bg-primary text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-semibold flex-shrink-0 mt-0.5">4</span>
                                    <p>Revise los datos y continúe con el formulario</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('components.sat-data-modal')

        @push('scripts')
            <script src="https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.min.js"></script>
            <script src="https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.worker.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
            <script src="{{ asset('js/sat-qr-extractor/qr-extractor-simple.js') }}"></script>
            <script src="{{ asset('js/sat-qr-extractor/sat-scraper-simple.js') }}"></script>
            <script src="{{ asset('js/sat-qr-extractor/constancia-extractor.js') }}"></script>
            
            <script>
                let satDataGlobal = null;
                let currentPdfFile = null;
                
                document.addEventListener('DOMContentLoaded', function() {
                    const selectFileBtn = document.getElementById('select-file-btn');
                    const pdfInput = document.getElementById('pdf-input');
                    const uploadContent = document.getElementById('upload-content');
                    const fileInfo = document.getElementById('file-info');
                    const loading = document.getElementById('loading');
                    const successResult = document.getElementById('success-result');
                    const errorResult = document.getElementById('error-result');
                    const errorMessage = document.getElementById('error-message');
                    const continueBtn = document.getElementById('continue-btn');
                    const retryBtn = document.getElementById('retry-btn');

                    selectFileBtn.addEventListener('click', () => pdfInput.click());

                    pdfInput.addEventListener('change', async (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            currentPdfFile = file;
                            await processFile(file);
                        }
                    });

                    continueBtn.addEventListener('click', () => {
                        if (satDataGlobal && currentPdfFile) {
                            fillFormAndSubmit();
                        }
                    });

                    retryBtn.addEventListener('click', () => {
                        pdfInput.click();
                    });

                    async function processFile(file) {
                        updateFileInfo();
                        hideResults();
                        showLoading();

                        try {
                            const extractor = new ConstanciaExtractor();
                            const result = await extractor.extract(file);

                            if (result.success) {
                                satDataGlobal = result.sat_data;
                                showSuccess();
                            } else {
                                showError(result.error);
                            }
                        } catch (error) {
                            showError('Error al procesar el archivo: ' + error.message);
                        } finally {
                            hideLoading();
                        }
                    }

                    function updateFileInfo() {
                        uploadContent.classList.add('hidden');
                        fileInfo.classList.remove('hidden');
                    }

                    function showLoading() {
                        loading.classList.remove('hidden');
                    }

                    function hideLoading() {
                        loading.classList.add('hidden');
                    }

                    function showSuccess() {
                        successResult.classList.remove('hidden');
                    }

                    function showError(message) {
                        errorResult.classList.remove('hidden');
                        errorMessage.textContent = message;
                    }

                    function hideResults() {
                        successResult.classList.add('hidden');
                        errorResult.classList.add('hidden');
                    }

                    function fillFormAndSubmit() {
                        const form = document.getElementById('continue-form');
                        const pdfFileInput = document.getElementById('pdf-file-input');
                        
                        // Crear un nuevo FileList con el archivo actual
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(currentPdfFile);
                        pdfFileInput.files = dataTransfer.files;
                        
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
                            if (element && satDataGlobal[dataKey]) {
                                element.value = satDataGlobal[dataKey];
                            }
                        });

                        form.submit();
                    }
                });

                // Funciones básicas del modal
                window.showSatDataModal = function(satData) {
                    if (!satData) {
                        alert('No hay datos disponibles');
                        return;
                    }
                    
                    document.getElementById('modal-rfc').textContent = satData.rfc || 'No disponible';
                    document.getElementById('modal-nombre').textContent = satData.nombre || 'No disponible';
                    document.getElementById('modal-tipo-persona').textContent = satData.tipo_persona || 'No disponible';
                    document.getElementById('modal-curp').textContent = satData.curp || 'No disponible';
                    document.getElementById('modal-regimen-fiscal').textContent = satData.regimen_fiscal || 'No disponible';
                    document.getElementById('modal-estatus').textContent = satData.estatus || 'No disponible';
                    document.getElementById('modal-entidad').textContent = satData.entidad_federativa || 'No disponible';
                    
                    const calle = satData.nombre_vialidad || 'No disponible';
                    const numero = (satData.numero_exterior || '') + (satData.numero_interior ? ' Int. ' + satData.numero_interior : '');
                    const colonia = satData.colonia || 'No disponible';
                    const cp = satData.cp || 'No disponible';
                    const municipio = satData.municipio || 'No disponible';
                    
                    const domicilio = `${calle} ${numero}, ${colonia}, CP ${cp}, ${municipio}`;
                    document.getElementById('modal-domicilio').textContent = domicilio;

                    const modal = document.getElementById('satDataModal');
                    modal.classList.remove('hidden');
                    
                    setTimeout(() => {
                        modal.querySelector('.bg-black').classList.add('bg-opacity-50');
                        modal.querySelector('.bg-white').classList.add('animate-fadeInUp');
                    }, 10);
                };

                window.closeSatDataModal = function() {
                    const modal = document.getElementById('satDataModal');
                    
                    modal.querySelector('.bg-black').classList.remove('bg-opacity-50');
                    modal.querySelector('.bg-white').classList.remove('animate-fadeInUp');
                    
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 200);
                };

                window.confirmSatData = function() {
                    closeSatDataModal();
                    
                    if (satDataGlobal && currentPdfFile) {
                        const form = document.getElementById('continue-form');
                        if (form) {
                            const pdfFileInput = document.getElementById('pdf-file-input');
                            
                            // Crear un nuevo FileList con el archivo actual
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(currentPdfFile);
                            pdfFileInput.files = dataTransfer.files;
                            
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
                                if (element && satDataGlobal[dataKey]) {
                                    element.value = satDataGlobal[dataKey];
                                }
                            });
                            
                            form.submit();
                        }
                    }
                };
            </script>
        @endpush
    @endsection
