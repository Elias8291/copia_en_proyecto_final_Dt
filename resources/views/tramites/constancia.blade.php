@extends('layouts.app')

@section('title', 'Cargar Constancia de Situación Fiscal')

@section('content')
<div class="min-h-screen bg-gradient-to-br">
    <div class="max-w-4xl mx-auto">
        <!-- Header mejorado -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/50 p-6 mb-6">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 mb-2">Cargar Constancia de Situación Fiscal</h1>
                    <p class="text-slate-600">Suba su constancia del SAT para extraer automáticamente sus datos fiscales y continuar con el trámite de {{ ucfirst($tipo) }}</p>
                    <div class="mt-3 inline-flex items-center px-3 py-1 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white text-sm font-medium rounded-full shadow-sm">
                        <span>{{ ucfirst($tipo) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Area - Tamaño reducido -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-3 mb-3">
            <div id="upload-area" class="border-2 border-dashed border-slate-300 rounded-lg p-5 text-center hover:border-[#9D2449] transition-colors cursor-pointer">
                <div id="upload-content">
                    <div class="w-14 h-14 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <p class="text-base font-semibold text-slate-800 mb-1">Arrastre su constancia PDF aquí</p>
                    <p class="text-xs text-slate-500 mb-3">o haga clic para seleccionar el archivo</p>
                    <button type="button" class="bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white px-5 py-2 rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
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
                    <p id="file-size" class="text-xs text-slate-500 mb-3"></p>
                    <div class="flex justify-center space-x-3">
                        <button type="button" id="process-btn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-4 py-2 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                            Procesar Constancia
                        </button>
                        <button type="button" id="cancel-btn" class="bg-slate-500 text-white px-4 py-2 rounded-lg hover:bg-slate-600 transition-all duration-200 text-sm font-medium">
                            Cancelar
                        </button>
                    </div>
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

        <!-- Results -->
        <div id="results" class="hidden">
            <!-- Success Result -->
            <div id="success-result" class="hidden bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 ml-4">
                        <h3 class="text-lg font-semibold text-emerald-800 mb-2">¡Constancia procesada exitosamente!</h3>
                        <div class="bg-white border border-emerald-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-slate-600 mb-2">URL del código QR extraída:</p>
                            <p id="extracted-url" class="font-mono text-sm bg-slate-100 p-3 rounded-lg break-all text-slate-700"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SAT Data Result -->
            <div id="sat-data-result" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 ml-4">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">Datos Fiscales Extraídos</h3>
                        <div id="sat-data-content" class="space-y-4">
                            <!-- Los datos se llenarán dinámicamente -->
                        </div>
                        
                        <!-- Botón para continuar al trámite -->
                        <div class="mt-6 pt-4 border-t border-blue-200">
                            <form id="continue-form" method="POST" action="{{ route('tramites.procesarConstancia', $tipo) }}">
                                @csrf
                                <!-- Campos ocultos que se llenarán con los datos del SAT -->
                                <input type="hidden" name="sat_rfc" id="sat-rfc-input">
                                <input type="hidden" name="sat_nombre" id="sat-nombre-input">
                                <input type="hidden" name="sat_tipo_persona" id="sat-tipo-persona-input">
                                <input type="hidden" name="sat_curp" id="sat-curp-input">
                                <input type="hidden" name="sat_cp" id="sat-cp-input">
                                <input type="hidden" name="sat_colonia" id="sat-colonia-input">
                                <input type="hidden" name="sat_nombre_vialidad" id="sat-nombre-vialidad-input">
                                <input type="hidden" name="sat_numero_exterior" id="sat-numero-exterior-input">
                                <input type="hidden" name="sat_numero_interior" id="sat-numero-interior-input">
                                
                                <button type="submit" class="w-full bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white px-6 py-3 rounded-lg hover:from-[#8B1E3F] hover:to-[#A91B1B] transition-all duration-200 shadow-md hover:shadow-lg font-semibold flex items-center justify-center">
                                    <span>Continuar con el Trámite</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Result -->
            <div id="error-result" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 ml-4">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Error al procesar la constancia</h3>
                        <p id="error-message" class="text-red-700"></p>
                        <div class="mt-4">
                            <a href="{{ route('tramites.formulario', $tipo) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                Continuar sin datos automáticos
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-slate-50 rounded-xl border border-slate-200/50 p-5">
            <h3 class="text-base font-semibold text-slate-800 mb-3 flex items-center">
                <div class="w-6 h-6 bg-slate-200 rounded-lg flex items-center justify-center mr-2">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <p class="text-xs text-slate-700">Arrastre o seleccione el archivo PDF (máximo 5MB)</p>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const pdfInput = document.getElementById('pdf-input');
    const uploadContent = document.getElementById('upload-content');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const processBtn = document.getElementById('process-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const loading = document.getElementById('loading');
    const results = document.getElementById('results');
    const successResult = document.getElementById('success-result');
    const errorResult = document.getElementById('error-result');
    const extractedUrl = document.getElementById('extracted-url');
    const errorMessage = document.getElementById('error-message');
    const loadingScraping = document.getElementById('loading-scraping');
    const satDataResult = document.getElementById('sat-data-result');
    const satDataContent = document.getElementById('sat-data-content');

    let selectedFile = null;

    // Click to upload
    uploadArea.addEventListener('click', () => {
        if (!selectedFile) {
            pdfInput.click();
        }
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('border-[#9D2449]', 'bg-slate-50');
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-[#9D2449]', 'bg-slate-50');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-[#9D2449]', 'bg-slate-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelection(files[0]);
        }
    });

    // File input change
    pdfInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelection(e.target.files[0]);
        }
    });

    // Handle file selection
    function handleFileSelection(file) {
        if (file.type !== 'application/pdf') {
            alert('Por favor seleccione un archivo PDF válido.');
            return;
        }

        if (file.size > 5 * 1024 * 1024) { // 5MB
            alert('El archivo es demasiado grande. Máximo 5MB permitido.');
            return;
        }

        selectedFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        uploadContent.classList.add('hidden');
        fileInfo.classList.remove('hidden');
        hideResults();
    }

    // Cancel file selection
    cancelBtn.addEventListener('click', () => {
        selectedFile = null;
        pdfInput.value = '';
        uploadContent.classList.remove('hidden');
        fileInfo.classList.add('hidden');
        hideResults();
    });

    // Process PDF
    processBtn.addEventListener('click', async () => {
        if (!selectedFile) return;

        showLoading();
        hideResults();

        const formData = new FormData();
        formData.append('pdf', selectedFile);

        try {
            const response = await fetch('/api/extract-qr-url', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();
            hideLoading();

            if (data.success) {
                showSuccess(data.url);
                
                // Automáticamente hacer scraping de los datos del SAT
                await scrapeSATData(data.url);
            } else {
                showError(data.error || 'Error desconocido al procesar el PDF');
            }
        } catch (error) {
            hideLoading();
            showError('Error de conexión. Por favor intente nuevamente.');
            console.error('Error:', error);
        }
    });

    // Helper functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showLoading() {
        loading.classList.remove('hidden');
    }

    function hideLoading() {
        loading.classList.add('hidden');
    }

    function showSuccess(url) {
        extractedUrl.textContent = url;
        successResult.classList.remove('hidden');
        errorResult.classList.add('hidden');
        results.classList.remove('hidden');
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorResult.classList.remove('hidden');
        successResult.classList.add('hidden');
        results.classList.remove('hidden');
    }

    function hideResults() {
        results.classList.add('hidden');
        successResult.classList.add('hidden');
        errorResult.classList.add('hidden');
        satDataResult.classList.add('hidden');
    }

    // Función para hacer scraping de los datos del SAT
    async function scrapeSATData(url) {
        // Verificar si es una URL del SAT
        if (!url.includes('siat.sat.gob.mx')) {
            console.log('URL no es del SAT, saltando scraping');
            showError('La constancia debe ser del SAT. Por favor verifique el archivo.');
            return;
        }

        console.log('🔍 Iniciando scraping del SAT para URL:', url);
        showLoadingScraping();

        try {
            const response = await fetch('/api/scrape-sat-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ url: url })
            });

            console.log('📡 Respuesta del servidor:', response.status, response.statusText);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('📊 Datos recibidos del servidor:', data);
            
            hideLoadingScraping();

            if (data.success && data.sat_data && data.sat_data.success) {
                console.log('✅ Datos del SAT extraídos exitosamente');
                showSATData(data.sat_data);
            } else {
                console.warn('⚠️ No se pudieron extraer datos del SAT:', data.error || 'Error desconocido');
                showError('No se pudieron extraer los datos fiscales automáticamente. Puede continuar llenando el formulario manualmente.');
            }
        } catch (error) {
            hideLoadingScraping();
            console.error('❌ Error en scraping del SAT:', error);
            showError('Error al procesar los datos fiscales. Puede continuar con el formulario manual.');
        }
    }

    // Función para mostrar los datos del SAT
    function showSATData(satData) {
        const formData = satData.form_data || {};
        
        // Llenar los campos ocultos del formulario
        document.getElementById('sat-rfc-input').value = formData.rfc || '';
        document.getElementById('sat-nombre-input').value = formData.razon_social || '';
        document.getElementById('sat-tipo-persona-input').value = formData.tipo_persona || '';
        document.getElementById('sat-curp-input').value = formData.curp || '';
        document.getElementById('sat-cp-input').value = formData.codigo_postal || '';
        document.getElementById('sat-colonia-input').value = formData.colonia || '';
        document.getElementById('sat-nombre-vialidad-input').value = formData.calle || '';
        document.getElementById('sat-numero-exterior-input').value = formData.numero_exterior || '';
        document.getElementById('sat-numero-interior-input').value = formData.numero_interior || '';
        
        let dataHTML = '';
        
        // Información básica
        if (formData.rfc || formData.razon_social || formData.tipo_persona) {
            dataHTML += `
                <div class="bg-white rounded-lg border border-blue-200 p-4">
                    <h4 class="font-semibold text-blue-800 mb-3">Información Básica</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        ${formData.rfc ? `<div><span class="font-medium text-slate-600">RFC:</span> <span class="text-slate-900">${formData.rfc}</span></div>` : ''}
                        ${formData.razon_social ? `<div><span class="font-medium text-slate-600">Razón Social:</span> <span class="text-slate-900">${formData.razon_social}</span></div>` : ''}
                        ${formData.tipo_persona ? `<div><span class="font-medium text-slate-600">Tipo:</span> <span class="text-slate-900">${formData.tipo_persona === 'fisica' ? 'Persona Física' : 'Persona Moral'}</span></div>` : ''}
                        ${formData.curp ? `<div><span class="font-medium text-slate-600">CURP:</span> <span class="text-slate-900">${formData.curp}</span></div>` : ''}
                        ${formData.estatus ? `<div><span class="font-medium text-slate-600">Estatus:</span> <span class="text-slate-900">${formData.estatus}</span></div>` : ''}
                    </div>
                </div>
            `;
        }

        // Información fiscal
        if (formData.regimen_fiscal || formData.fecha_inicio) {
            dataHTML += `
                <div class="bg-white rounded-lg border border-blue-200 p-4">
                    <h4 class="font-semibold text-blue-800 mb-3">Información Fiscal</h4>
                    <div class="grid grid-cols-1 gap-3 text-sm">
                        ${formData.regimen_fiscal ? `<div><span class="font-medium text-slate-600">Régimen Fiscal:</span> <span class="text-slate-900">${formData.regimen_fiscal}</span></div>` : ''}
                        ${formData.fecha_inicio ? `<div><span class="font-medium text-slate-600">Fecha de Inicio:</span> <span class="text-slate-900">${formData.fecha_inicio}</span></div>` : ''}
                        ${formData.fecha_actualizacion ? `<div><span class="font-medium text-slate-600">Última Actualización:</span> <span class="text-slate-900">${formData.fecha_actualizacion}</span></div>` : ''}
                    </div>
                </div>
            `;
        }

        // Información de ubicación
        if (formData.entidad_federativa || formData.codigo_postal || formData.municipio) {
            dataHTML += `
                <div class="bg-white rounded-lg border border-blue-200 p-4">
                    <h4 class="font-semibold text-blue-800 mb-3">Domicilio Fiscal</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        ${formData.entidad_federativa ? `<div><span class="font-medium text-slate-600">Estado:</span> <span class="text-slate-900">${formData.entidad_federativa}</span></div>` : ''}
                        ${formData.municipio ? `<div><span class="font-medium text-slate-600">Municipio:</span> <span class="text-slate-900">${formData.municipio}</span></div>` : ''}
                        ${formData.colonia ? `<div><span class="font-medium text-slate-600">Colonia:</span> <span class="text-slate-900">${formData.colonia}</span></div>` : ''}
                        ${formData.calle ? `<div><span class="font-medium text-slate-600">Calle:</span> <span class="text-slate-900">${formData.calle}</span></div>` : ''}
                        ${formData.numero_exterior ? `<div><span class="font-medium text-slate-600">Núm. Exterior:</span> <span class="text-slate-900">${formData.numero_exterior}</span></div>` : ''}
                        ${formData.codigo_postal ? `<div><span class="font-medium text-slate-600">Código Postal:</span> <span class="text-slate-900">${formData.codigo_postal}</span></div>` : ''}
                        ${formData.email ? `<div><span class="font-medium text-slate-600">Email:</span> <span class="text-slate-900">${formData.email}</span></div>` : ''}
                    </div>
                </div>
            `;
        }

        if (dataHTML) {
            satDataContent.innerHTML = dataHTML;
            satDataResult.classList.remove('hidden');
        } else {
            showError('No se encontraron datos válidos en la constancia.');
        }
    }

    function showLoadingScraping() {
        loadingScraping.classList.remove('hidden');
    }

    function hideLoadingScraping() {
        loadingScraping.classList.add('hidden');
    }
});
</script>
@endpush
@endsection