@extends('layouts.app')

@section('title', 'Ejemplo - Componentes Reutilizables')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">📋 Ejemplo de Componentes Reutilizables</h1>
            <p class="text-gray-600">
                Este es un ejemplo de cómo usar <code class="bg-gray-100 px-2 py-1 rounded">ConstanciaExtractor</code> 
                y <code class="bg-gray-100 px-2 py-1 rounded">SATModal</code> en cualquier vista.
            </p>
        </div>

        <!-- Upload Area -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">📤 Cargar Constancia Fiscal</h2>
            
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mb-2">Selecciona tu constancia del SAT</h3>
                <p class="text-sm text-gray-500 mb-4">Solo archivos PDF, máximo 5MB</p>
                
                <button id="selectFileBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Seleccionar Archivo
                </button>
                
                <input type="file" id="fileInput" accept=".pdf" class="hidden">
                
                <!-- Estado del archivo -->
                <div id="fileStatus" class="mt-4 hidden">
                    <p id="fileName" class="text-sm font-medium text-gray-900"></p>
                    <p id="fileSize" class="text-xs text-gray-500"></p>
                </div>
            </div>
        </div>

        <!-- Loading States -->
        <div id="loadingIndicator" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-3"></div>
                <p id="loadingMessage" class="text-blue-800 font-medium">Procesando...</p>
            </div>
        </div>

        <!-- Error Display -->
        <div id="errorDisplay" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="w-5 h-5 text-red-400 mt-0.5 mr-3">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Error al procesar</h3>
                    <p id="errorMessage" class="text-sm text-red-700 mt-1"></p>
                </div>
            </div>
        </div>

        <!-- Success Display -->
        <div id="successDisplay" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="w-5 h-5 text-green-400 mt-0.5 mr-3">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-green-800">¡Datos extraídos exitosamente!</h3>
                    <p class="text-sm text-green-700 mt-1">Se han obtenido los datos fiscales de la constancia</p>
                    <div class="mt-3">
                        <button id="showModalBtn" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Ver Datos Extraídos
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Código de ejemplo -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">💻 Código de este ejemplo:</h3>
            <pre class="bg-gray-800 text-green-400 p-4 rounded overflow-x-auto text-sm"><code>// 1. Incluir componentes
&lt;script src="/js/constancia-extractor.js"&gt;&lt;/script&gt;
&lt;script src="/js/sat-modal.js"&gt;&lt;/script&gt;

// 2. Inicializar
const extractor = new ConstanciaExtractor();
const modal = new SATModal({
    onContinue: () => alert('¡Continuar con el proceso!')
});

// 3. Usar
const datos = await extractor.extract(file);
if (datos.success) {
    modal.show(datos.sat_data, datos.qr_url);
}</code></pre>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Incluir componentes reutilizables -->
<script src="{{ asset('js/constancia-extractor.js') }}"></script>
<script src="{{ asset('js/sat-modal.js') }}"></script>

<script>
    // Variables para este ejemplo
    let extractor = null;
    let satModal = null;
    let currentData = null;

    // Inicializar cuando el DOM está listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Inicializando ejemplo de componentes reutilizables...');
        
        // Crear instancias de los componentes
        extractor = new ConstanciaExtractor({ debug: true });
        
        satModal = new SATModal({
            showUrl: true,
            onContinue: function() {
                alert('🎉 ¡Aquí continuarías con tu proceso específico!\n\nDatos disponibles: ' + Object.keys(currentData || {}).join(', '));
                this.hide();
            },
            onClose: function() {
                console.log('📊 Modal cerrado');
            }
        });

        // Configurar eventos
        setupEvents();
        
        console.log('✅ Componentes inicializados correctamente');
    });

    function setupEvents() {
        // Botón para seleccionar archivo
        document.getElementById('selectFileBtn').addEventListener('click', () => {
            document.getElementById('fileInput').click();
        });

        // Cuando se selecciona un archivo
        document.getElementById('fileInput').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                processFile(file);
            }
        });

        // Botón para mostrar modal con datos
        document.getElementById('showModalBtn').addEventListener('click', () => {
            if (currentData) {
                satModal.show(currentData.sat_data, currentData.qr_url);
            }
        });
    }

    async function processFile(file) {
        console.log('📁 Procesando archivo:', file.name);
        
        // Mostrar información del archivo
        showFileInfo(file);
        hideAllStates();

        // Procesar usando el extractor reutilizable
        const result = await extractor.extractWithCallbacks(file, {
            onStart: () => {
                showLoading('Iniciando procesamiento...');
            },
            onProgress: (message) => {
                showLoading(message);
            },
            onSuccess: (satData, qrUrl) => {
                console.log('✅ Datos extraídos:', satData);
                
                // Guardar datos para usar después
                currentData = { sat_data: satData, qr_url: qrUrl };
                
                // Mostrar estado de éxito
                showSuccess();
                
                // Mostrar modal automáticamente
                setTimeout(() => {
                    satModal.show(satData, qrUrl);
                }, 500);
            },
            onError: (error) => {
                console.error('❌ Error:', error);
                showError(error);
            },
            onFinish: () => {
                hideLoading();
            }
        });
    }

    function showFileInfo(file) {
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const fileStatus = document.getElementById('fileStatus');

        if (fileName) fileName.textContent = file.name;
        if (fileSize) fileSize.textContent = formatFileSize(file.size);
        if (fileStatus) fileStatus.classList.remove('hidden');
    }

    function showLoading(message) {
        const indicator = document.getElementById('loadingIndicator');
        const messageEl = document.getElementById('loadingMessage');
        
        if (indicator) indicator.classList.remove('hidden');
        if (messageEl) messageEl.textContent = message;
    }

    function hideLoading() {
        const indicator = document.getElementById('loadingIndicator');
        if (indicator) indicator.classList.add('hidden');
    }

    function showSuccess() {
        const display = document.getElementById('successDisplay');
        if (display) display.classList.remove('hidden');
    }

    function showError(message) {
        const display = document.getElementById('errorDisplay');
        const messageEl = document.getElementById('errorMessage');
        
        if (display) display.classList.remove('hidden');
        if (messageEl) messageEl.textContent = message;
    }

    function hideAllStates() {
        const states = ['successDisplay', 'errorDisplay'];
        states.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>
@endpush 