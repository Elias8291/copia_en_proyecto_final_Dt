@extends('layouts.auth')

@section('title', 'Registro - Padrón de Proveedores de Oaxaca')

@push('styles')
<style>
    .border-3 {
        border-width: 3px;
    }
    
    .form-disabled {
        transition: all 0.3s ease-out;
        filter: grayscale(0.3);
    }
</style>
@endpush

@section('content')
<!-- Modal para mostrar datos del SAT -->
@include('components.modal-sat-datos')

<!-- Modal de Éxito Reutilizable -->
@include('components.modal-success')

<form method="POST" action="{{ route('register') }}" class="space-y-6" enctype="multipart/form-data">
    @csrf
    <!-- Header con Logo -->
    <div class="text-center mb-3">
        <div class="flex flex-col items-center justify-center mb-2">
            <div class="w-14 h-14 flex items-center justify-center mb-2 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full p-2">
                <img src="{{ asset('images/logoprin.jpg') }}" alt="Logo Estado de Oaxaca" class="w-full h-full object-contain rounded-full">
            </div>
            <div class="text-center space-y-1">
                <span class="text-primary font-bold text-sm block tracking-wide">ADMINISTRACIÓN</span>
                <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Gobierno del Estado de Oaxaca</span>
            </div>
        </div>
        
        <div class="space-y-1 mb-2">
            <h1 class="text-lg font-bold text-gray-800 leading-tight">Registro de Proveedor</h1>
            <p class="text-gray-600 text-xs leading-tight max-w-xs mx-auto">
                Suba su Constancia de Situación Fiscal con QR
            </p>
        </div>
    </div>

    <!-- Mensajes de error del servidor -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-2 py-1.5 rounded-lg mb-2">
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Área de subida de PDF -->
    <div id="uploadArea" class="transition-all duration-300 ease-in-out min-h-[80px]">
        <div class="mt-1">
            <label for="document" class="block text-xs font-medium text-gray-700 mb-0.5">
                <span class="block md:inline">Constancia de Situación Fiscal</span>
                <span class="text-xs text-gray-500 block md:inline md:ml-1">(PDF o Imagen)</span>
            </label>
            <div class="relative">
                <input type="file" id="document" name="document" accept=".pdf,.png,.jpg,.jpeg" required class="hidden">
                <label for="document" class="group flex flex-col items-center justify-center w-full h-16 border-2 border-dashed border-primary/20 hover:border-primary rounded-lg transition-all duration-300 cursor-pointer bg-primary-50/30 hover:bg-primary-50">
                    <div class="flex flex-col md:flex-row items-center space-y-0.5 md:space-y-0 md:space-x-2 px-3">
                        <div class="transform group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-4 h-4 text-primary/70 group-hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-primary/70 group-hover:text-primary font-medium text-xs mb-0">
                                Haga clic para seleccionar archivo
                            </p>
                            <p class="text-xs text-gray-500" id="fileName">
                                PDF o Imagen con QR (Máximo 5MB)
                            </p>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <!-- Área de previsualización (oculta) -->
        <div id="previewArea" class="hidden">
            <div class="hidden">
                <div id="qrResult"></div>
                <canvas id="pdfCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Formulario de registro (inicialmente oculto) -->
    <div id="registrationForm" class="hidden space-y-2 transition-all duration-300 ease-in-out">
        <input type="hidden" id="qrUrl" name="qr_url">
        
        <!-- Campos ocultos para datos del SAT -->
        <input type="hidden" id="satRfc" name="sat_rfc">
        <input type="hidden" id="satNombre" name="sat_nombre">
        <input type="hidden" id="satTipoPersona" name="sat_tipo_persona">
        <input type="hidden" id="satCurp" name="sat_curp">
        <input type="hidden" id="satCp" name="sat_cp">
        <input type="hidden" id="satColonia" name="sat_colonia">
        <input type="hidden" id="satNombreVialidad" name="sat_nombre_vialidad">
        <input type="hidden" id="satNumeroExterior" name="sat_numero_exterior">
        <input type="hidden" id="satNumeroInterior" name="sat_numero_interior">
        
        <!-- Botón Ver Datos del SAT -->
        <button type="button" 
                id="verDatosBtn"
                onclick="showSatModal()"
                class="hidden inline-flex items-center text-xs bg-white hover:bg-primary-50 text-primary font-medium py-1 px-2 rounded-lg transition-all duration-300 shadow-sm hover:shadow border border-primary/20 hover:border-primary/40">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Ver Datos del SAT
        </button>

        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 mb-0.5">Correo Electrónico</label>
            <div class="relative">
                <input type="email" id="email" name="email" required 
                       class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm"
                       placeholder="ejemplo@correo.com">
                <div id="emailValidationIcon" class="absolute right-2 top-1/2 -translate-y-1/2 hidden"></div>
                </div>
            <!-- Área para validación -->
            <div class="h-6 relative">
                <div id="emailValidation" class="absolute top-0 left-0 w-full opacity-0 transform translate-y-1 transition-all duration-200"></div>
            </div>
        </div>

        <!-- Contraseñas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-3">
            <div>
                <label for="password" class="block text-xs font-medium text-gray-700 mb-0.5">Contraseña</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required 
                           class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm"
                           placeholder="••••••••">
                    <button type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password-toggle-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-0.5">Confirmar Contraseña</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required 
                           class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors duration-300 text-sm"
                           placeholder="••••••••">
                    <div class="absolute right-8 top-1/2 -translate-y-1/2">
                        <div id="passwordMatchIcon" class="hidden"></div>
                    </div>
                    <button type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="password_confirmation-toggle-icon">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Área para validación de contraseñas -->
        <div class="h-6 relative">
            <div id="passwordMatchValidation" class="absolute top-0 left-0 w-full opacity-0 transform translate-y-1 transition-all duration-200"></div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="space-y-2 pt-3">
        <button type="button" id="actionButton" onclick="handleActionButton()" class="group w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 relative overflow-hidden text-sm">
            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative flex items-center justify-center space-x-2">
                <svg id="actionIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span id="actionText">Siguiente</span>
            </div>
        </button>

        <a href="{{ url('/') }}" class="group w-full bg-white hover:bg-gray-50 text-primary hover:text-primary-dark font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 border-2 border-primary/20 hover:border-primary/40 relative overflow-hidden inline-flex items-center justify-center text-sm">
            <div class="relative flex items-center justify-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Volver al Inicio</span>
            </div>
        </a>
    </div>

    <!-- Enlace de recuperación de contraseña -->
    <div class="text-center mt-3 relative z-10">
        <a href="{{ route('password.request') }}" 
           class="text-gray-500 hover:text-primary text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-2 hover:bg-gray-50 py-1.5 px-3 rounded-lg">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/>
            </svg>
            <span>¿Olvidaste tu contraseña?</span>
        </a>
    </div>
</form>

<!-- Scripts -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.min.js"></script>
<script src="/js/scrapers/sat-scraper.js"></script>
<script src="/js/validators/sat-validator.js"></script>
<script src="/js/components/qr-reader.js"></script>
<script src="/js/components/qr-handler.js"></script>
<script src="/js/components/global-loading.js"></script>
<script src="/js/validators/form-validator.js"></script>

<script>
    // Configurar PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.worker.min.js';
    
    // Variables globales
    let documentProcessed = false;
    let qrHandler = null;

    // Configuración para register.blade.php
    const config = {
        fileNameElement: 'fileName',
        previewAreaElement: 'previewArea',
        uploadAreaElement: 'uploadArea',
        registrationFormElement: 'registrationForm',
        verDatosBtnElement: 'verDatosBtn',
        qrUrlElement: 'qrUrl'
    };

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', async () => {
        // Verificar si se debe mostrar el modal de éxito
        @if(session('show_success_modal'))
            setTimeout(() => {
                showSuccessModalFromSession({
                    show_success_modal: true,
                    modal_title: "{{ session('modal_title') }}",
                    modal_message: "{{ session('modal_message') }}",
                    modal_button_text: "{{ session('modal_button_text') }}",
                    modal_redirect_to: "{{ session('modal_redirect_to') }}"
                });
            }, 500);
        @endif

        try {
            // Verificar dependencias
            if (typeof QRHandler === 'undefined' || typeof QRReader === 'undefined' || 
                typeof SATValidator === 'undefined' || typeof SATScraper === 'undefined') {
                throw new Error('Las clases necesarias no están disponibles');
            }
            
            // Inicializar QRHandler
            qrHandler = new QRHandler(config);
            const initialized = await qrHandler.initialize(QRReader, SATValidator, SATScraper);
            
            if (!initialized) {
                throw new Error('No se pudo inicializar el QRHandler');
            }

            // Configurar callbacks
            qrHandler.setOnDataScanned((data) => {
                hideLoading();
                
                // Llenar campos del SAT
                    if (data.details) {
                        document.getElementById('satRfc').value = data.details.rfc || '';
                        document.getElementById('satNombre').value = data.details.razonSocial || data.details.nombreCompleto || '';
                        document.getElementById('satTipoPersona').value = data.details.tipoPersona || '';
                        document.getElementById('satCurp').value = data.details.curp || '';
                        document.getElementById('satCp').value = data.details.cp || '';
                        document.getElementById('satColonia').value = data.details.colonia || '';
                        document.getElementById('satNombreVialidad').value = data.details.nombreVialidad || '';
                        document.getElementById('satNumeroExterior').value = data.details.numeroExterior || '';
                        document.getElementById('satNumeroInterior').value = data.details.numeroInterior || '';
                }
                
                // Mostrar formulario
                document.getElementById('uploadArea').classList.add('hidden');
                document.getElementById('registrationForm').classList.remove('hidden');
                document.getElementById('verDatosBtn').classList.remove('hidden');

                // Cambiar botón
                changeButtonToRegister();

                // Autocompletar email si está disponible
                if (data.details && data.details.email) {
                    document.getElementById('email').value = data.details.email;
                }
            });

            qrHandler.setOnError((error) => {
                console.error('Error en QRHandler:', error);
                hideLoading();
                showError(error);
                resetUpload();
            });

            window.qrHandler = qrHandler;

        } catch (error) {
            console.error('Error durante la inicialización:', error);
            showError('Error al inicializar el procesador: ' + error.message);
        }
    });

    // Variable para prevenir doble envío
    let isRegistering = false;

    // Manejar botón de acción
    window.handleActionButton = function() {
        if (isRegistering) return;

        if (!documentProcessed) {
            const fileInput = document.getElementById('document');
            if (!fileInput.files[0]) {
                showError('Por favor, seleccione un documento antes de continuar.');
                return;
            }
            showError('Por favor, seleccione y procese su Constancia de Situación Fiscal primero.');
        } else {
            const form = document.querySelector('form');
            if (validateForm()) {
                isRegistering = true;
                showLoading('Registrando Usuario', 'Creando cuenta y enviando verificación...');
                form.submit();
            } else {
                showError('Por favor, complete todos los campos requeridos.');
            }
        }
    };

    // Cambiar botón a modo registro
    function changeButtonToRegister() {
        const actionButton = document.getElementById('actionButton');
        const actionText = document.getElementById('actionText');
        const actionIcon = document.getElementById('actionIcon');
        
        if (actionButton && actionText && actionIcon) {
            actionText.textContent = 'REGISTRARSE';
            actionIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            `;
            documentProcessed = true;
        }
    }

    // Validar formulario
    function validateForm() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required]');
        
        let allFilled = true;
        inputs.forEach(input => {
            if (!input.value.trim()) allFilled = false;
        });

        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordsMatch = password && passwordConfirmation ? 
            password.value === passwordConfirmation.value : true;

        const satDataValid = documentProcessed && 
            document.getElementById('satRfc').value && 
            document.getElementById('satNombre').value;

        return allFilled && passwordsMatch && satDataValid;
    }

    // Mostrar modal con datos del SAT
    window.showSatModal = function() {
        const modal = document.getElementById('satDataModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            const result = qrHandler.showSatData();
            if (result.success) {
                document.getElementById('satDataContent').innerHTML = result.content;
            } else {
                showError('Error al mostrar los datos: ' + result.error);
            }
        }
    };

    // Cerrar modal
    window.closeSatModal = function() {
        const modal = document.getElementById('satDataModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    };

    // Procesar archivo
    document.getElementById('document').addEventListener('change', async (event) => {
        const file = event.target.files[0];
        if (!file) return;

        try {
            const isPDF = file.type === 'application/pdf';
            const isImage = file.type.startsWith('image/');
            
            if (!isPDF && !isImage) {
                throw new Error('El archivo debe ser un PDF o una imagen (JPG, PNG).');
            }

            if (file.size > 5 * 1024 * 1024) {
                throw new Error('El archivo no debe exceder los 5MB.');
            }

            document.getElementById('fileName').textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;

            showLoading('Procesando Documento', 'Validando constancia con el SAT...');

            if (!qrHandler) {
                throw new Error('El procesador no está inicializado');
            }

            await qrHandler.handleFile(file);

        } catch (error) {
            console.error('Error:', error);
            hideLoading();
            showError(error.message || 'Error al procesar el documento');
            resetUpload();
        }
    });

    // Validación en tiempo real
    document.addEventListener('input', function(e) {
        if (e.target.id === 'email') {
            const email = e.target.value.trim();
            if (email.length > 3) {
                clearTimeout(window.emailValidationTimeout);
                window.emailValidationTimeout = setTimeout(() => {
                    window.formValidator.validateEmail(email);
                }, 300);
            } else {
                window.formValidator.clearValidationMessage('email');
            }
        } else if (e.target.id === 'password' || e.target.id === 'password_confirmation') {
            window.formValidator.validatePasswordMatch();
        }
    });

    // Mostrar error
    function showError(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-sm';
        notification.innerHTML = `
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Reset upload
    function resetUpload() {
        const fileInput = document.getElementById('document');
        if (fileInput) fileInput.value = '';

        document.getElementById('fileName').textContent = 'PDF o Imagen con QR (Máximo 5MB)';
        document.getElementById('uploadArea').classList.remove('hidden');
        document.getElementById('registrationForm').classList.add('hidden');
        document.getElementById('verDatosBtn').classList.add('hidden');
        
        // Limpiar campos
        document.getElementById('qrUrl').value = '';
        document.getElementById('satRfc').value = '';
        document.getElementById('satNombre').value = '';
        
        if (qrHandler) qrHandler.reset();
        
        const actionButton = document.getElementById('actionButton');
        const actionText = document.getElementById('actionText');
        const actionIcon = document.getElementById('actionIcon');
        
        if (actionButton && actionText && actionIcon) {
            actionText.textContent = 'SIGUIENTE';
            actionIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>`;
            documentProcessed = false;
        }
    }

    // Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-toggle-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        input.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="r" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}
</script>
@endsection 