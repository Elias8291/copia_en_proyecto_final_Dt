/**
 * Manejador de registro con extracción de datos SAT
 */
class RegisterHandler {
    constructor() {
        this.extractor = null;
        this.satModal = null;
        this.datosSAT = null;
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeComponents();
            this.bindEvents();
        });
    }

    initializeComponents() {
        console.log('🚀 Inicializando componentes...');

        this.extractor = new ConstanciaExtractor({
            debug: true
        });

        this.satModal = new SATModal({
            onContinue: () => {
                console.log('🔄 Botón continuar presionado en modal');
                this.satModal.hide();
                this.continueWithRegistration();
            },
            onClose: () => {
                console.log('🔄 Modal cerrado');
            }
        });

        console.log('✅ Componentes inicializados');
    }

    bindEvents() {
        // Eventos globales
        window.uploadFile = (input) => this.uploadFile(input);
        window.handleActionButton = () => this.handleActionButton();
        window.togglePassword = (fieldId) => this.togglePassword(fieldId);
        window.continueWithRegistration = () => this.continueWithRegistration();
    }

    uploadFile(input) {
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            this.updateFileName(file.name);
            this.processFile(file);
        }
    }

    handleActionButton() {
        const input = document.getElementById('document');
        if (input?.files?.length > 0) {
            this.processFile(input.files[0]);
        } else {
            input?.click();
        }
    }

    togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-toggle-icon');

        if (field && icon) {
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';

            const eyeIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            const eyeOffIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0712 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 711.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m6.121-6.121A9.97 9.97 0 0721 12c0 .906-.117 1.785-.337 2.625m-3.846 6.321L9.878 9.878"></path>';

            icon.innerHTML = isPassword ? eyeOffIcon : eyeIcon;
        }
    }

    async processFile(file) {
        console.log('🚀 Procesando archivo:', file.name);

        await this.extractor.extractWithCallbacks(file, {
            onStart: () => {
                console.log('📋 Iniciando extracción...');
                this.showProcessingIndicator(true);
            },
            onProgress: (message) => {
                console.log('📋 Progreso:', message);
            },
            onSuccess: (satData, qrUrl) => {
                console.log('✅ ¡Éxito! Datos extraídos:', satData);
                this.datosSAT = satData;
                this.fillHiddenInputs(satData);
                this.showSuccessMessage('¡Datos extraídos exitosamente! Completando formulario...');
                
                setTimeout(() => {
                    this.continueWithRegistration();
                }, 1500);
            },
            onError: (error) => {
                console.error('❌ Error:', error);
                this.showErrorMessage(error);
            },
            onFinish: () => {
                console.log('🏁 Proceso terminado');
                this.showProcessingIndicator(false);
            }
        });
    }

    continueWithRegistration() {
        console.log('📝 Iniciando proceso de registro...');

        const registrationForm = document.getElementById('registrationForm');
        const uploadArea = document.getElementById('uploadArea');
        const actionButton = document.getElementById('actionButton');

        if (registrationForm && uploadArea) {
            // Animación de transición
            uploadArea.style.transform = 'translateY(-20px)';
            uploadArea.style.opacity = '0.5';

            setTimeout(() => {
                registrationForm.classList.remove('hidden');
                registrationForm.style.transform = 'translateY(20px)';
                registrationForm.style.opacity = '0';

                setTimeout(() => {
                    registrationForm.style.transform = 'translateY(0)';
                    registrationForm.style.opacity = '1';
                }, 50);
            }, 200);

            // Actualizar botón
            this.updateActionButton(actionButton);
        }

        console.log('📝 Formulario de registro mostrado');
    }

    updateActionButton(actionButton) {
        if (actionButton) {
            const actionText = actionButton.querySelector('span');
            const actionIcon = actionButton.querySelector('svg');

            if (actionText) actionText.textContent = 'Registrarse';
            if (actionIcon) {
                actionIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>';
            }
        }
    }

    updateFileName(fileName) {
        const el = document.getElementById('fileName');
        if (el) el.textContent = fileName;
    }

    showProcessingIndicator(show) {
        const indicator = document.getElementById('processingStatus');
        if (indicator) {
            indicator.classList.toggle('hidden', !show);
        } else if (show) {
            this.createProcessingIndicator();
        }
    }

    createProcessingIndicator() {
        const uploadArea = document.getElementById('uploadArea');
        if (uploadArea) {
            const processingDiv = document.createElement('div');
            processingDiv.id = 'processingStatus';
            processingDiv.className = 'mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3';
            processingDiv.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-primary"></div>
                    <span class="text-xs text-primary font-medium">Extrayendo datos fiscales automáticamente...</span>
                </div>
            `;
            uploadArea.appendChild(processingDiv);
        }
    }

    fillHiddenInputs(satData) {
        const fields = {
            'satRfc': 'rfc',
            'satNombre': 'nombre',
            'satCurp': 'curp',
            'satRegimenFiscal': 'regimen_fiscal',
            'satEstatus': 'estatus',
            'satEntidadFederativa': 'entidad_federativa',
            'satMunicipio': 'municipio',
            'satEmail': 'email',
            'satTipoPersona': 'tipo_persona',
            'satCp': 'cp',
            'satColonia': 'colonia',
            'satNombreVialidad': 'nombre_vialidad',
            'satNumeroExterior': 'numero_exterior',
            'satNumeroInterior': 'numero_interior'
        };

        Object.entries(fields).forEach(([fieldId, dataKey]) => {
            const element = document.getElementById(fieldId);
            if (element && satData[dataKey]) {
                element.value = satData[dataKey];
            }
        });

        console.log('📋 Campos llenados con datos SAT');
    }

    showSuccessMessage(message) {
        this.showNotification(message, 'success');
    }

    showErrorMessage(error) {
        this.showNotification(error, 'error');
    }

    showNotification(message, type = 'success') {
        const isSuccess = type === 'success';
        const bgColor = isSuccess ? 'bg-emerald-500' : 'bg-red-500';
        const icon = isSuccess 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
        const title = isSuccess ? '¡Éxito!' : 'Error al procesar archivo';

        const notificationDiv = document.createElement('div');
        notificationDiv.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
        
        notificationDiv.innerHTML = `
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
                <div>
                    <h4 class="font-semibold">${title}</h4>
                    <p class="text-sm">${message}</p>
                </div>
                ${!isSuccess ? '<button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>' : ''}
            </div>
        `;

        document.body.appendChild(notificationDiv);

        // Animación de entrada
        setTimeout(() => {
            notificationDiv.classList.remove('translate-x-full');
            notificationDiv.classList.add('translate-x-0');
        }, 10);

        // Auto-remover
        const timeout = isSuccess ? 3000 : 5000;
        setTimeout(() => {
            notificationDiv.classList.add('translate-x-full');
            setTimeout(() => notificationDiv.remove(), 300);
        }, timeout);
    }
}

// Inicializar
new RegisterHandler();