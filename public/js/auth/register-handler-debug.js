/**
 * Register Handler - Debug Version
 * Versión con debug extenso para identificar problemas
 */

console.log('🔧 [DEBUG] Cargando RegisterHandlerDebug...');

class RegisterHandlerDebug {
    constructor(options = {}) {
        console.log('🔧 [DEBUG] Constructor iniciado con opciones:', options);
        
        this.config = {
            maxFileSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['application/pdf'],
            debug: true,
            ...options
        };

        // Estado interno
        this.selectedFile = null;
        this.isProcessing = false;
        this.satHandler = null;
        this.satData = null;

        console.log('🔧 [DEBUG] Configuración final:', this.config);

        // Esperar a que el DOM esté completamente cargado
        if (document.readyState === 'loading') {
            console.log('🔧 [DEBUG] DOM aún cargando, esperando...');
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            console.log('🔧 [DEBUG] DOM ya cargado, inicializando...');
            this.init();
        }
    }

    init() {
        console.log('🔧 [DEBUG] Iniciando RegisterHandlerDebug...');
        
        try {
            // Verificar elementos DOM
            this.checkDOMElements();
            
            // Inicializar elementos
            this.initializeElements();
            
            // Verificar dependencias
            this.checkDependencies();
            
            // Configurar eventos
            this.setupEventListeners();
            
            console.log('✅ [DEBUG] RegisterHandlerDebug inicializado correctamente');
        } catch (error) {
            console.error('❌ [DEBUG] Error inicializando RegisterHandlerDebug:', error);
        }
    }

    checkDOMElements() {
        console.log('🔧 [DEBUG] Verificando elementos DOM...');
        
        const requiredElements = [
            'uploadArea',
            'document', 
            'fileName',
            'processingStatus',
            'registrationForm',
            'actionButton'
        ];

        const found = [];
        const missing = [];

        requiredElements.forEach(elementId => {
            const element = document.getElementById(elementId);
            if (element) {
                found.push(elementId);
                console.log(`✅ [DEBUG] Elemento encontrado: ${elementId}`, element);
            } else {
                missing.push(elementId);
                console.error(`❌ [DEBUG] Elemento faltante: ${elementId}`);
            }
        });

        console.log('📊 [DEBUG] Resumen DOM:', { found, missing });
        
        if (missing.length > 0) {
            console.warn('⚠️ [DEBUG] Elementos faltantes detectados. El sistema puede no funcionar correctamente.');
        }
    }

    checkDependencies() {
        console.log('🔧 [DEBUG] Verificando dependencias...');
        
        // Verificar SATConstanciaHandler
        if (typeof SATConstanciaHandler === 'undefined') {
            console.error('❌ [DEBUG] SATConstanciaHandler no encontrado. Verifica que el script sat-constancia-handler.js se haya cargado.');
            this.satHandler = null;
        } else {
            try {
                this.satHandler = new SATConstanciaHandler();
                console.log('✅ [DEBUG] SATConstanciaHandler inicializado:', this.satHandler);
            } catch (error) {
                console.error('❌ [DEBUG] Error creando SATConstanciaHandler:', error);
                this.satHandler = null;
            }
        }

        // Verificar fetch API
        if (typeof fetch === 'undefined') {
            console.error('❌ [DEBUG] Fetch API no disponible');
        } else {
            console.log('✅ [DEBUG] Fetch API disponible');
        }

        // Verificar FormData
        if (typeof FormData === 'undefined') {
            console.error('❌ [DEBUG] FormData no disponible');
        } else {
            console.log('✅ [DEBUG] FormData disponible');
        }
    }

    initializeElements() {
        console.log('🔧 [DEBUG] Inicializando elementos DOM...');
        
        this.elements = {
            // Upload area
            uploadArea: document.getElementById('uploadArea'),
            documentInput: document.getElementById('document'),
            fileName: document.getElementById('fileName'),
            processingStatus: document.getElementById('processingStatus'),
            
            // Form elements
            registrationForm: document.getElementById('registrationForm'),
            
            // SAT fields (hidden)
            satRfc: document.getElementById('satRfc'),
            satNombre: document.getElementById('satNombre'),
            satTipoPersona: document.getElementById('satTipoPersona'),
            
            // Form fields
            emailInput: document.getElementById('email'),
            passwordInput: document.getElementById('password'),
            passwordConfirmInput: document.getElementById('password_confirmation'),
            
            // Buttons
            actionButton: document.getElementById('actionButton'),
            actionText: document.getElementById('actionText')
        };

        // Log de cada elemento
        Object.entries(this.elements).forEach(([key, element]) => {
            if (element) {
                console.log(`✅ [DEBUG] Elemento ${key} encontrado:`, element);
            } else {
                console.warn(`⚠️ [DEBUG] Elemento ${key} no encontrado`);
            }
        });
    }

    setupEventListeners() {
        console.log('🔧 [DEBUG] Configurando event listeners...');
        
        // Event listener para el input de archivo
        if (this.elements.documentInput) {
            this.elements.documentInput.addEventListener('change', (e) => {
                console.log('📁 [DEBUG] Archivo seleccionado:', e.target.files);
                this.handleFileInput(e);
            });
            console.log('✅ [DEBUG] Event listener para documentInput configurado');
        } else {
            console.error('❌ [DEBUG] No se pudo configurar event listener para documentInput - elemento no encontrado');
        }

        // Event listener para el área de upload (click)
        if (this.elements.uploadArea) {
            this.elements.uploadArea.addEventListener('click', (e) => {
                console.log('🔧 [DEBUG] Click en uploadArea:', e.target);
                if (!this.isProcessing && this.elements.documentInput) {
                    console.log('📁 [DEBUG] Abriendo selector de archivos...');
                    this.elements.documentInput.click();
                }
            });
            console.log('✅ [DEBUG] Event listener para uploadArea configurado');
        }

        // Event listener para el botón de acción
        if (this.elements.actionButton) {
            this.elements.actionButton.addEventListener('click', (e) => {
                console.log('🔧 [DEBUG] Click en actionButton:', e);
                this.handleActionButton(e);
            });
            console.log('✅ [DEBUG] Event listener para actionButton configurado');
        }

        // Drag and drop
        this.setupDragAndDrop();

        // Funciones globales
        window.showSATSuccess = () => {
            console.log('🎉 [DEBUG] showSATSuccess llamada');
            this.handleSATSuccess();
        };
        
        window.showSATError = (message) => {
            console.log('❌ [DEBUG] showSATError llamada con:', message);
            this.handleSATError(message);
        };

        console.log('✅ [DEBUG] Todos los event listeners configurados');
    }

    setupDragAndDrop() {
        if (!this.elements.uploadArea) {
            console.warn('⚠️ [DEBUG] No se puede configurar drag & drop - uploadArea no encontrado');
            return;
        }

        console.log('🔧 [DEBUG] Configurando drag & drop...');

        const dragEvents = ['dragenter', 'dragover', 'dragleave', 'drop'];
        
        dragEvents.forEach(eventName => {
            this.elements.uploadArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log(`📥 [DEBUG] Evento drag & drop: ${eventName}`);
            });
        });

        this.elements.uploadArea.addEventListener('drop', (e) => {
            console.log('📥 [DEBUG] Archivo dropeado:', e.dataTransfer.files);
            if (!this.isProcessing) {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.handleFileSelection(files[0]);
                }
            }
        });

        console.log('✅ [DEBUG] Drag & drop configurado');
    }

    handleFileInput(e) {
        console.log('📁 [DEBUG] handleFileInput iniciado:', e.target.files);
        
        if (e.target.files.length > 0 && !this.isProcessing) {
            console.log('📁 [DEBUG] Procesando archivo seleccionado...');
            this.handleFileSelection(e.target.files[0]);
        } else if (this.isProcessing) {
            console.warn('⚠️ [DEBUG] Ya hay un archivo en procesamiento');
        } else {
            console.warn('⚠️ [DEBUG] No hay archivos seleccionados');
        }
    }

    handleFileSelection(file) {
        console.log('📁 [DEBUG] handleFileSelection iniciado con archivo:', {
            name: file.name,
            size: file.size,
            type: file.type,
            lastModified: new Date(file.lastModified)
        });

        if (this.isProcessing) {
            console.warn('⚠️ [DEBUG] Ya hay un archivo en procesamiento, ignorando...');
            return;
        }

        // Validar archivo
        const validation = this.validateFile(file);
        console.log('📁 [DEBUG] Resultado de validación:', validation);
        
        if (!validation.valid) {
            console.error('❌ [DEBUG] Archivo no válido:', validation.error);
            this.showError(validation.error);
            return;
        }

        // Actualizar UI
        this.selectedFile = file;
        this.updateFileName(file.name);
        this.showProcessing();
        
        // Procesar archivo
        console.log('🔄 [DEBUG] Iniciando procesamiento del archivo...');
        this.processFile();
    }

    validateFile(file) {
        console.log('🔍 [DEBUG] Validando archivo:', file);
        
        if (!this.config.allowedTypes.includes(file.type)) {
            return {
                valid: false,
                error: `Tipo de archivo no permitido: ${file.type}. Permitidos: ${this.config.allowedTypes.join(', ')}`
            };
        }

        if (file.size > this.config.maxFileSize) {
            return {
                valid: false,
                error: `Archivo demasiado grande: ${file.size} bytes. Máximo: ${this.config.maxFileSize} bytes`
            };
        }

        return { valid: true };
    }

    async processFile() {
        console.log('🔄 [DEBUG] processFile iniciado');
        
        if (!this.selectedFile || this.isProcessing) {
            console.warn('⚠️ [DEBUG] No hay archivo o ya está procesando');
            return;
        }

        this.isProcessing = true;
        console.log('🔄 [DEBUG] Estado de procesamiento: true');

        try {
            // Crear FormData
            const formData = new FormData();
            formData.append('pdf', this.selectedFile);
            console.log('📁 [DEBUG] FormData creado:', formData);

            // Obtener CSRF token
            const csrfToken = this.getCSRFToken();
            console.log('🔐 [DEBUG] CSRF Token:', csrfToken ? 'Encontrado' : 'No encontrado');

            // Realizar petición
            console.log('🌐 [DEBUG] Realizando petición a /api/extract-qr-url...');
            
            const response = await fetch('/api/extract-qr-url', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            console.log('🌐 [DEBUG] Respuesta recibida:', {
                status: response.status,
                statusText: response.statusText,
                ok: response.ok
            });

            const data = await response.json();
            console.log('🌐 [DEBUG] Datos de respuesta:', data);

            if (data.success) {
                console.log('✅ [DEBUG] QR extraído exitosamente, procesando con SAT...');
                if (this.satHandler) {
                    await this.satHandler.scrapeSATData(data.url);
                } else {
                    console.error('❌ [DEBUG] SATHandler no disponible');
                    this.handleSATError('Error interno: SATHandler no inicializado');
                }
            } else {
                console.error('❌ [DEBUG] Error extrayendo QR:', data.error);
                this.handleSATError(data.error || 'Error desconocido al procesar el PDF');
            }
        } catch (error) {
            console.error('❌ [DEBUG] Error en processFile:', error);
            this.handleSATError('Error de conexión. Por favor intente nuevamente.');
        } finally {
            this.isProcessing = false;
            this.hideProcessing();
            console.log('🔄 [DEBUG] Estado de procesamiento: false');
        }
    }

    handleSATSuccess() {
        console.log('🎉 [DEBUG] handleSATSuccess iniciado');
        
        if (this.satHandler) {
            this.satData = this.satHandler.getSummary();
            console.log('📊 [DEBUG] Datos SAT obtenidos:', this.satData);
            
            if (this.satData && this.satData.rfc && this.satData.rfc !== 'No disponible') {
                this.showRegistrationForm();
                console.log('✅ [DEBUG] Formulario de registro mostrado');
            } else {
                console.warn('⚠️ [DEBUG] Datos SAT incompletos:', this.satData);
                this.handleSATError('Los datos extraídos del SAT están incompletos');
            }
        } else {
            console.error('❌ [DEBUG] SATHandler no disponible en handleSATSuccess');
        }
    }

    handleSATError(message) {
        console.error('❌ [DEBUG] handleSATError:', message);
        this.showError(message);
        
        // Reset
        if (this.elements.documentInput) {
            this.elements.documentInput.value = '';
        }
        this.selectedFile = null;
        this.resetFileName();
    }

    handleActionButton(e) {
        console.log('🔧 [DEBUG] handleActionButton iniciado:', e);
        e.preventDefault();
        
        const buttonState = this.elements.actionButton?.dataset.state || 'upload';
        console.log('🔧 [DEBUG] Estado del botón:', buttonState);
        
        if (buttonState === 'upload' || !buttonState) {
            console.log('📁 [DEBUG] Activando selector de archivos...');
            if (this.elements.documentInput) {
                this.elements.documentInput.click();
            } else {
                console.error('❌ [DEBUG] documentInput no disponible');
            }
        }
    }

    showRegistrationForm() {
        console.log('📝 [DEBUG] showRegistrationForm iniciado');
        
        if (this.elements.registrationForm) {
            this.elements.registrationForm.classList.remove('hidden');
            console.log('✅ [DEBUG] Formulario de registro visible');
            
            // Actualizar botón
            if (this.elements.actionButton) {
                this.elements.actionButton.dataset.state = 'register';
                console.log('🔧 [DEBUG] Estado del botón cambiado a register');
            }
            
            if (this.elements.actionText) {
                this.elements.actionText.textContent = 'Crear Cuenta';
                console.log('🔧 [DEBUG] Texto del botón cambiado');
            }
        } else {
            console.error('❌ [DEBUG] registrationForm no encontrado');
        }
    }

    showProcessing() {
        console.log('⏳ [DEBUG] showProcessing iniciado');
        if (this.elements.processingStatus) {
            this.elements.processingStatus.classList.remove('hidden');
            console.log('✅ [DEBUG] Indicador de procesamiento visible');
        }
    }

    hideProcessing() {
        console.log('⏳ [DEBUG] hideProcessing iniciado');
        if (this.elements.processingStatus) {
            this.elements.processingStatus.classList.add('hidden');
            console.log('✅ [DEBUG] Indicador de procesamiento oculto');
        }
    }

    updateFileName(name) {
        console.log('📝 [DEBUG] updateFileName:', name);
        if (this.elements.fileName) {
            this.elements.fileName.textContent = name;
            console.log('✅ [DEBUG] Nombre de archivo actualizado');
        }
    }

    resetFileName() {
        console.log('🔄 [DEBUG] resetFileName iniciado');
        if (this.elements.fileName) {
            this.elements.fileName.textContent = 'PDF o Imagen con QR (Máximo 5MB)';
            console.log('✅ [DEBUG] Nombre de archivo reseteado');
        }
    }

    showError(message) {
        console.error('❌ [DEBUG] showError:', message);
        
        // Mostrar en consola para debug
        alert(`Error: ${message}`);
        
        // También mostrar en la página si es posible
        let errorElement = document.getElementById('register-error');
        if (!errorElement && this.elements.uploadArea) {
            errorElement = document.createElement('div');
            errorElement.id = 'register-error';
            errorElement.className = 'bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg mb-3 text-sm';
            this.elements.uploadArea.parentNode?.insertBefore(errorElement, this.elements.uploadArea);
        }
        
        if (errorElement) {
            errorElement.innerHTML = `
                <div class="flex items-center">
                    <span>❌ ${message}</span>
                </div>
            `;
            errorElement.classList.remove('hidden');
            console.log('✅ [DEBUG] Elemento de error mostrado');
        }
    }

    getCSRFToken() {
        const tokenElement = document.querySelector('meta[name="csrf-token"]');
        const token = tokenElement ? tokenElement.getAttribute('content') : '';
        console.log('🔐 [DEBUG] CSRF Token obtenido:', token ? '✅ Disponible' : '❌ No encontrado');
        return token;
    }
}

// Hacer disponible globalmente
window.RegisterHandlerDebug = RegisterHandlerDebug;
console.log('✅ [DEBUG] RegisterHandlerDebug disponible globalmente'); 