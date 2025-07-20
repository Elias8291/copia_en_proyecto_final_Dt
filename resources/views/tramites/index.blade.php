@extends('layouts.app')

@section('title', 'Trámites - Lector de PDF con QR')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Lector de PDF con Código QR</h1>
            <p class="text-gray-600">Sube un archivo PDF para extraer automáticamente la URL del código QR</p>
        </div>

        <!-- Upload Area -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer">
                <div id="upload-content">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="text-lg font-medium text-gray-900 mb-2">Arrastra tu archivo PDF aquí</p>
                    <p class="text-sm text-gray-500 mb-4">o haz clic para seleccionar</p>
                    <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        Seleccionar archivo
                    </button>
                </div>
                <div id="file-info" class="hidden">
                    <svg class="mx-auto h-12 w-12 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p id="file-name" class="text-lg font-medium text-gray-900 mb-2"></p>
                    <p id="file-size" class="text-sm text-gray-500 mb-4"></p>
                    <button type="button" id="process-btn" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition-colors">
                        Procesar PDF
                    </button>
                    <button type="button" id="cancel-btn" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
            <input type="file" id="pdf-input" accept=".pdf" class="hidden">
        </div>

        <!-- Loading -->
        <div id="loading" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mr-3"></div>
                <p class="text-blue-800">Procesando PDF y extrayendo código QR...</p>
            </div>
        </div>

        <!-- Loading Scraping -->
        <div id="loading-scraping" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-600 mr-3"></div>
                <p class="text-purple-800">Extrayendo datos del SAT...</p>
            </div>
        </div>

        <!-- Results -->
        <div id="results" class="hidden">
            <!-- Success Result -->
            <div id="success-result" class="hidden bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-green-800 mb-2">¡Código QR extraído exitosamente!</h3>
                        <div class="bg-white border border-green-200 rounded-md p-4 mb-4">
                            <p class="text-sm text-gray-600 mb-2">URL encontrada:</p>
                            <p id="extracted-url" class="font-mono text-sm bg-gray-100 p-2 rounded break-all"></p>
                        </div>
                        <div class="flex space-x-3">
                            <button id="copy-url-btn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm">
                                Copiar URL
                            </button>
                            <button id="open-url-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                Abrir URL
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SAT Data Result -->
            <div id="sat-data-result" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-blue-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-blue-800 mb-4">Datos Extraídos del SAT</h3>
                        <div id="sat-data-content" class="space-y-4">
                            <!-- Los datos se llenarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Result -->
            <div id="error-result" class="hidden bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="h-6 w-6 text-red-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-red-800 mb-2">Error al procesar el PDF</h3>
                        <p id="error-message" class="text-red-700"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Instrucciones de uso</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-medium mr-3 mt-0.5">1</span>
                    Selecciona o arrastra un archivo PDF que contenga un código QR
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-medium mr-3 mt-0.5">2</span>
                    El archivo debe ser menor a 5MB
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-medium mr-3 mt-0.5">3</span>
                    Haz clic en "Procesar PDF" para extraer la URL del código QR
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-medium mr-3 mt-0.5">4</span>
                    Una vez extraída, podrás copiar o abrir la URL directamente
                </li>
            </ul>
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
    const copyUrlBtn = document.getElementById('copy-url-btn');
    const openUrlBtn = document.getElementById('open-url-btn');
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
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    });

    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        
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
            alert('Por favor selecciona un archivo PDF válido.');
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
            showError('Error de conexión. Por favor intenta nuevamente.');
            console.error('Error:', error);
        }
    });

    // Copy URL
    copyUrlBtn.addEventListener('click', async () => {
        const url = extractedUrl.textContent;
        try {
            await navigator.clipboard.writeText(url);
            copyUrlBtn.textContent = '¡Copiado!';
            copyUrlBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            copyUrlBtn.classList.add('bg-green-800');
            
            setTimeout(() => {
                copyUrlBtn.textContent = 'Copiar URL';
                copyUrlBtn.classList.remove('bg-green-800');
                copyUrlBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 2000);
        } catch (error) {
            alert('No se pudo copiar la URL. Por favor cópiala manualmente.');
        }
    });

    // Open URL
    openUrlBtn.addEventListener('click', () => {
        const url = extractedUrl.textContent;
        window.open(url, '_blank');
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
                console.log('📋 Form data:', data.sat_data.form_data);
                showSATData(data.sat_data);
            } else {
                console.warn('⚠️ No se pudieron extraer datos del SAT:', data.error || 'Error desconocido');
                console.log('🔍 Estructura de datos recibida:', JSON.stringify(data, null, 2));
                
                // Mostrar mensaje informativo al usuario
                showSATError('No se pudieron extraer datos adicionales del SAT. La URL del QR fue extraída correctamente.');
            }
        } catch (error) {
            hideLoadingScraping();
            console.error('❌ Error en scraping del SAT:', error);
            // No mostrar error al usuario, solo log para debugging
        }
    }

    // Función para mostrar los datos del SAT
    function showSATData(satData) {
        const formData = satData.form_data || {};
        
        let dataHTML = '';
        
        // Información básica
        if (formData.rfc || formData.razon_social || formData.tipo_persona) {
            dataHTML += `
                <div class="bg-white rounded-lg border border-blue-200 p-4">
                    <h4 class="font-semibold text-blue-800 mb-3">Información Básica</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        ${formData.rfc ? `<div><span class="font-medium text-gray-600">RFC:</span> <span class="text-gray-900">${formData.rfc}</span></div>` : ''}
                        ${formData.razon_social ? `<div><span class="font-medium text-gray-600">Razón Social:</span> <span class="text-gray-900">${formData.razon_social}</span></div>` : ''}
                        ${formData.tipo_persona ? `<div><span class="font-medium text-gray-600">Tipo:</span> <span class="text-gray-900">${formData.tipo_persona === 'fisica' ? 'Persona Física' : 'Persona Moral'}</span></div>` : ''}
                        ${formData.curp ? `<div><span class="font-medium text-gray-600">CURP:</span> <span class="text-gray-900">${formData.curp}</span></div>` : ''}
                        ${formData.estatus ? `<div><span class="font-medium text-gray-600">Estatus:</span> <span class="text-gray-900">${formData.estatus}</span></div>` : ''}
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
                        ${formData.regimen_fiscal ? `<div><span class="font-medium text-gray-600">Régimen Fiscal:</span> <span class="text-gray-900">${formData.regimen_fiscal}</span></div>` : ''}
                        ${formData.fecha_inicio ? `<div><span class="font-medium text-gray-600">Fecha de Inicio:</span> <span class="text-gray-900">${formData.fecha_inicio}</span></div>` : ''}
                        ${formData.fecha_actualizacion ? `<div><span class="font-medium text-gray-600">Última Actualización:</span> <span class="text-gray-900">${formData.fecha_actualizacion}</span></div>` : ''}
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
                        ${formData.entidad_federativa ? `<div><span class="font-medium text-gray-600">Estado:</span> <span class="text-gray-900">${formData.entidad_federativa}</span></div>` : ''}
                        ${formData.municipio ? `<div><span class="font-medium text-gray-600">Municipio:</span> <span class="text-gray-900">${formData.municipio}</span></div>` : ''}
                        ${formData.colonia ? `<div><span class="font-medium text-gray-600">Colonia:</span> <span class="text-gray-900">${formData.colonia}</span></div>` : ''}
                        ${formData.calle ? `<div><span class="font-medium text-gray-600">Calle:</span> <span class="text-gray-900">${formData.calle}</span></div>` : ''}
                        ${formData.numero_exterior ? `<div><span class="font-medium text-gray-600">Núm. Exterior:</span> <span class="text-gray-900">${formData.numero_exterior}</span></div>` : ''}
                        ${formData.codigo_postal ? `<div><span class="font-medium text-gray-600">Código Postal:</span> <span class="text-gray-900">${formData.codigo_postal}</span></div>` : ''}
                        ${formData.email ? `<div><span class="font-medium text-gray-600">Email:</span> <span class="text-gray-900">${formData.email}</span></div>` : ''}
                    </div>
                </div>
            `;
        }

        if (dataHTML) {
            satDataContent.innerHTML = dataHTML;
            satDataResult.classList.remove('hidden');
        } else {
            console.log('No hay datos del SAT para mostrar');
        }
    }

    // Función para mostrar error del SAT (informativo)
    function showSATError(message) {
        // Crear un elemento de información en lugar de error
        const infoHTML = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="h-5 w-5 text-yellow-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-medium text-yellow-800 mb-1">Información</h4>
                        <p class="text-sm text-yellow-700">${message}</p>
                    </div>
                </div>
            </div>
        `;
        
        satDataContent.innerHTML = infoHTML;
        satDataResult.classList.remove('hidden');
    }

    // Funciones para mostrar/ocultar loading del scraping
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