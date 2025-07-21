/**
 * Register Handler
 * Maneja el registro de proveedores con carga automática de datos del SAT
 * @version 2.0.0
 */

class RegisterHandler {
    constructor(options = {}) {
        // Configuración
        this.config = {
            maxFileSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['application/pdf'],
            requiredSATFields: ['sat_rfc', 'sat_nombre'],
            ...options
        };

        // Estado interno
        this.selectedFile = null;
        this.isProcessing = false;
        this.satHandler = null;
        this.satData = null;

        // Referencias DOM
        this.initializeElements();
        
        // Inicializar SAT handler
        this.satHandler = new SATConstanciaHandler();
        
        // Configurar eventos
        this.setupEventListeners();
    }

    /**
     * Inicializa referencias a elementos DOM
     */
    initializeElements() {
        this.elements = {
            // Upload area
            uploadArea: document.getElementById('uploadArea'),
            documentInput: document.getElementById('document'),
            fileName: document.getElementById('fileName'),
            processingStatus: document.getElementById('processingStatus'),
            previewArea: document.getElementById('previewArea'),
            
            // Form elements
            registrationForm: document.getElementById('registrationForm'),
            qrUrl: document.getElementById('qrUrl'),
            
            // SAT fields (hidden)
            satRfc: document.getElementById('satRfc'),
            satNombre: document.getElementById('satNombre'),
            satTipoPersona: document.getElementById('satTipoPersona'),
            
            // Form fields
            emailInput: document.getElementById('email'),
            passwordInput: document.getElementById('password'),
            passwordConfirmInput: document.getElementById('password_confirmation'),
            
            // Buttons
            verDatosBtn: document.getElementById('verDatosBtn'),
            actionButton: document.getElementById('actionButton'),
            actionText: document.getElementById('actionText'),
            actionIcon: document.getElementById('actionIcon')
        };

        // Verificar elementos críticos
        const requiredElements = ['uploadArea', 'documentInput', 'actionButton'];
        for (const elementName of requiredElements) {
            if (!this.elements[elementName]) {
                console.error(`Elemento requerido no encontrado: ${elementName}`);
            }
        }
    }

    /**
     * Configura todos los event listeners
     */
    setupEventListeners() {
        // Input de archivo
        this.elements.documentInput?.addEventListener('change', (e) => this.handleFileInput(e));
        
        // Botón principal de acción
        this.elements.actionButton?.addEventListener('click', (e) => this.handleActionButton(e));
        
        // Botón ver datos del SAT
        this.elements.verDatosBtn?.addEventListener('click', () => this.showSATData());

        // Validación de email en tiempo real
        this.elements.emailInput?.addEventListener('input', () => this.validateEmail());
        
        // Validación de contraseñas
        this.elements.passwordInput?.addEventListener('input', () => this.validatePasswords());
        this.elements.passwordConfirmInput?.addEventListener('input', () => this.validatePasswords());

        // Drag and drop
        this.setupDragAndDrop();

        // Función global para mostrar éxito desde el SAT handler
        window.showSATSuccess = () => this.handleSATSuccess();
        
        // Override de showError del SAT handler
        window.showSATError = (message) => this.handleSATError(message);
    }

    /**
     * Configura drag and drop
     */
    setupDragAndDrop() {
        if (!this.elements.uploadArea) return;

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.elements.uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            this.elements.uploadArea.addEventListener(eventName, () => {
                if (!this.isProcessing) {
                    this.elements.uploadArea.classList.add('border-blue-400', 'bg-blue-50');
                }
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            this.elements.uploadArea.addEventListener(eventName, () => {
                this.elements.uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            });
        });

        this.elements.uploadArea.addEventListener('drop', (e) => {
            if (!this.isProcessing) {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.handleFileSelection(files[0]);
                }
            }
        });
    }

    /**
     * Maneja el cambio en el input de archivo
     */
    handleFileInput(e) {
        if (e.target.files.length > 0 && !this.isProcessing) {
            this.handleFileSelection(e.target.files[0]);
        }
    }

    /**
     * Maneja la selección de un archivo
     */
    async handleFileSelection(file) {
        if (this.isProcessing) return;

        // Validar archivo
        const validation = this.validateFile(file);
        if (!validation.valid) {
            this.showError(validation.error);
            return;
        }

        // Actualizar UI
        this.selectedFile = file;
        this.updateFileName(file.name);
        this.showProcessing();
        
        // Procesar automáticamente
        await this.processFile();
    }

    /**
     * Valida el archivo seleccionado
     */
    validateFile(file) {
        if (!this.config.allowedTypes.includes(file.type)) {
            return {
                valid: false,
                error: 'Por favor seleccione un archivo PDF válido.'
            };
        }

        if (file.size > this.config.maxFileSize) {
            return {
                valid: false,
                error: 'El archivo es demasiado grande. Máximo 5MB permitido.'
            };
        }

        return { valid: true };
    }

    /**
     * Procesa el archivo seleccionado
     */
    async processFile() {
        if (!this.selectedFile || this.isProcessing) return;

        this.isProcessing = true;

        try {
            // Crear FormData para envío
            const formData = new FormData();
            formData.append('pdf', this.selectedFile);

            // Extraer QR del PDF
            const response = await fetch('/api/extract-qr-url', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();

            if (data.success) {
                // Procesar datos del SAT
                await this.satHandler.scrapeSATData(data.url);
            } else {
                this.handleSATError(data.error || 'Error desconocido al procesar el PDF');
            }
        } catch (error) {
            this.handleSATError('Error de conexión. Por favor intente nuevamente.');
            console.error('Error procesando archivo:', error);
        } finally {
            this.isProcessing = false;
            this.hideProcessing();
        }
    }

    /**
     * Maneja el éxito del procesamiento del SAT
     */
    handleSATSuccess() {
        // Obtener datos del SAT handler
        this.satData = this.satHandler.getSummary();
        
        if (this.satData) {
            // Llenar campos ocultos
            this.fillSATFields();
            
            // Mostrar formulario de registro
            this.showRegistrationForm();
            
            // Actualizar botón de acción
            this.updateActionButton('register', 'Crear Cuenta', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z');
            
            // Mostrar botón para ver datos
            if (this.elements.verDatosBtn) {
                this.elements.verDatosBtn.classList.remove('hidden');
            }

            // Scroll al formulario
            this.scrollToForm();
        }
    }

    /**
     * Maneja errores del procesamiento del SAT
     */
    handleSATError(message) {
        this.showError(message);
        this.hideProcessing();
        
        // Resetear archivo para permitir reintentar
        if (this.elements.documentInput) {
            this.elements.documentInput.value = '';
        }
        this.selectedFile = null;
        this.resetFileName();
    }

    /**
     * Llena los campos del SAT con los datos extraídos
     */
    fillSATFields() {
        if (!this.satData) return;

        // Campos ocultos del SAT
        if (this.elements.satRfc) this.elements.satRfc.value = this.satData.rfc || '';
        if (this.elements.satNombre) this.elements.satNombre.value = this.satData.razonSocial || '';
        if (this.elements.satTipoPersona) this.elements.satTipoPersona.value = this.satData.tipoPersona || '';
        
        console.log('📊 Datos del SAT cargados:', {
            rfc: this.satData.rfc,
            nombre: this.satData.razonSocial,
            tipo: this.satData.tipoPersona
        });
    }

    /**
     * Muestra el formulario de registro
     */
    showRegistrationForm() {
        if (this.elements.registrationForm) {
            this.elements.registrationForm.classList.remove('hidden');
            this.elements.registrationForm.classList.add('animate-fade-in');
        }
        
        // Hacer el área de upload más pequeña
        if (this.elements.uploadArea) {
            this.elements.uploadArea.classList.add('form-disabled');
        }
    }

    /**
     * Maneja el botón de acción principal
     */
    handleActionButton(e) {
        e.preventDefault();
        
        const buttonState = this.elements.actionButton?.dataset.state || 'upload';
        
        switch (buttonState) {
            case 'upload':
                // Trigger file selection
                if (this.elements.documentInput) {
                    this.elements.documentInput.click();
                }
                break;
            case 'register':
                this.handleRegistration();
                break;
            default:
                console.warn('Estado de botón desconocido:', buttonState);
        }
    }

    /**
     * Maneja el proceso de registro
     */
    handleRegistration() {
        // Validar formulario
        if (!this.validateForm()) {
            return;
        }

        // Mostrar loading en botón
        this.showButtonLoading();
        
        // Enviar formulario
        const form = this.elements.registrationForm?.closest('form');
        if (form) {
            form.submit();
        }
    }

    /**
     * Valida todo el formulario
     */
    validateForm() {
        let isValid = true;
        
        // Validar datos del SAT
        if (!this.satData || !this.satData.rfc) {
            this.showError('Es necesario cargar una constancia de situación fiscal válida');
            isValid = false;
        }
        
        // Validar email
        if (!this.validateEmail()) {
            isValid = false;
        }
        
        // Validar contraseñas
        if (!this.validatePasswords()) {
            isValid = false;
        }
        
        return isValid;
    }

    /**
     * Muestra los datos del SAT en el modal
     */
    showSATData() {
        if (this.satHandler) {
            this.satHandler.showModal();
        }
    }

    // Métodos de UI
    showProcessing() {
        if (this.elements.processingStatus) {
            this.elements.processingStatus.classList.remove('hidden');
        }
    }

    hideProcessing() {
        if (this.elements.processingStatus) {
            this.elements.processingStatus.classList.add('hidden');
        }
    }

    updateFileName(name) {
        if (this.elements.fileName) {
            this.elements.fileName.textContent = name;
        }
    }

    resetFileName() {
        if (this.elements.fileName) {
            this.elements.fileName.textContent = 'PDF o Imagen con QR (Máximo 5MB)';
        }
    }

    updateActionButton(state, text, iconPath) {
        if (this.elements.actionButton) {
            this.elements.actionButton.dataset.state = state;
        }
        if (this.elements.actionText) {
            this.elements.actionText.textContent = text;
        }
        if (this.elements.actionIcon && iconPath) {
            this.elements.actionIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"></path>`;
        }
    }

    showButtonLoading() {
        if (this.elements.actionButton) {
            this.elements.actionButton.innerHTML = `
                <div class="relative flex items-center justify-center space-x-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    <span>Registrando...</span>
                </div>
            `;
            this.elements.actionButton.disabled = true;
        }
    }

    showError(message) {
        // Crear o actualizar elemento de error
        let errorElement = document.getElementById('register-error');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.id = 'register-error';
            errorElement.className = 'bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg mb-3 text-sm';
            this.elements.uploadArea?.parentNode?.insertBefore(errorElement, this.elements.uploadArea);
        }
        
        errorElement.innerHTML = `
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
        errorElement.classList.remove('hidden');
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            errorElement.classList.add('hidden');
        }, 5000);
    }

    scrollToForm() {
        if (this.elements.registrationForm) {
            this.elements.registrationForm.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
        }
    }

    // Validaciones específicas
    validateEmail() {
        const email = this.elements.emailInput?.value || '';
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(email);
        
        // Actualizar UI de validación si existe
        const validationElement = document.getElementById('emailValidation');
        const iconElement = document.getElementById('emailValidationIcon');
        
        if (validationElement && iconElement) {
            if (email.length > 0) {
                if (isValid) {
                    validationElement.className = 'absolute top-0 left-0 w-full opacity-100 transform translate-y-0 transition-all duration-200 text-xs text-green-600';
                    validationElement.textContent = '✓ Email válido';
                    iconElement.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                } else {
                    validationElement.className = 'absolute top-0 left-0 w-full opacity-100 transform translate-y-0 transition-all duration-200 text-xs text-red-600';
                    validationElement.textContent = '✗ Email inválido';
                    iconElement.innerHTML = '<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                }
                iconElement.classList.remove('hidden');
            } else {
                validationElement.className = 'absolute top-0 left-0 w-full opacity-0 transform translate-y-1 transition-all duration-200';
                iconElement.classList.add('hidden');
            }
        }
        
        return isValid;
    }

    validatePasswords() {
        const password = this.elements.passwordInput?.value || '';
        const confirmPassword = this.elements.passwordConfirmInput?.value || '';
        
        const passwordsMatch = password === confirmPassword;
        const passwordStrong = password.length >= 8;
        
        // Actualizar UI de validación
        const validationElement = document.getElementById('passwordMatchValidation');
        const iconElement = document.getElementById('passwordMatchIcon');
        
        if (validationElement && iconElement && confirmPassword.length > 0) {
            if (passwordsMatch && passwordStrong) {
                validationElement.className = 'absolute top-0 left-0 w-full opacity-100 transform translate-y-0 transition-all duration-200 text-xs text-green-600';
                validationElement.textContent = '✓ Las contraseñas coinciden';
                iconElement.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                iconElement.classList.remove('hidden');
            } else {
                validationElement.className = 'absolute top-0 left-0 w-full opacity-100 transform translate-y-0 transition-all duration-200 text-xs text-red-600';
                validationElement.textContent = passwordsMatch ? '✗ Contraseña muy corta (mín. 8 caracteres)' : '✗ Las contraseñas no coinciden';
                iconElement.innerHTML = '<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                iconElement.classList.remove('hidden');
            }
        }
        
        return passwordsMatch && passwordStrong;
    }

    // Utilidades
    getCSRFToken() {
        const tokenElement = document.querySelector('meta[name="csrf-token"]');
        return tokenElement ? tokenElement.getAttribute('content') : '';
    }

    reset() {
        this.selectedFile = null;
        this.isProcessing = false;
        this.satData = null;
        
        // Limpiar campos
        if (this.elements.documentInput) {
            this.elements.documentInput.value = '';
        }
        
        // Ocultar formulario
        if (this.elements.registrationForm) {
            this.elements.registrationForm.classList.add('hidden');
        }
        
        // Resetear botón
        this.updateActionButton('upload', 'Siguiente', 'M9 5l7 7-7 7');
        
        // Resetear SAT handler
        if (this.satHandler) {
            this.satHandler.reset();
        }
    }
}

// Hacer disponible globalmente
window.RegisterHandler = RegisterHandler; 