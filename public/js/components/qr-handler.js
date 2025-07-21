/**
 * QR Handler
 * Maneja la lectura y procesamiento de códigos QR para extraer datos del SAT
 * @version 2.0.0
 */
class QRHandler {
    constructor(config = {}) {
        // Configuración por defecto
        this.config = {
            debug: false,
            autoStart: false,
            retryAttempts: 3,
            retryDelay: 1000,
            maxFileSize: 5 * 1024 * 1024, // 5MB
            supportedFormats: ['pdf', 'jpg', 'jpeg', 'png'],
            ...config
        };

        // Estado interno
        this.qrReader = null;
        this.validator = null;
        this.scraper = null;
        this.lastScannedData = null;
        this.isProcessing = false;
        this.currentAttempt = 0;

        // Callbacks
        this.onDataScanned = null;
        this.onError = null;
        this.onProgress = null;

        this.init();
    }

    /**
     * Inicializa el handler
     */
    init() {
        try {
            this.setupEventListeners();
            this._debug('✅ QRHandler inicializado correctamente');
        } catch (error) {
            this._error('❌ Error durante la inicialización:', error);
        }
    }

    /**
     * Inicializa con dependencias externas
     */
    async initialize(QRReader, SATValidator, SATScraper) {
        try {
            this._validateRequiredElements();
            
            // Instanciar dependencias
            this.qrReader = new QRReader(SATValidator, SATScraper, this.config);
            this.validator = SATValidator;
            this.scraper = SATScraper;

            // Configurar comportamiento personalizado
            this._setupCustomBehavior();
            
            this._debug('🔧 Dependencias inicializadas correctamente');
            return true;
        } catch (error) {
            this._error('❌ Error inicializando dependencias:', error);
            if (this.onError) {
                this.onError(error.message);
            }
            return false;
        }
    }

    /**
     * Valida elementos requeridos en el DOM
     */
    _validateRequiredElements() {
        const requiredElements = ['qrResult', 'pdfCanvas'];
        const missingElements = requiredElements.filter(id => !document.getElementById(id));
        
        if (missingElements.length > 0) {
            throw new Error(`Elementos DOM faltantes: ${missingElements.join(', ')}`);
        }
    }

    /**
     * Configura comportamiento personalizado del QRReader
     */
    _setupCustomBehavior() {
        const self = this;

        // Sobrescribir handleFile para mejor control
        const originalHandleFile = this.qrReader.handleFile;
        this.qrReader.handleFile = async function(file) {
            return await self._handleFileWithRetry.call(self, originalHandleFile, file);
        };

        // Sobrescribir onValidQRFound
        this.qrReader.onValidQRFound = async function(url) {
            return await self._processQRUrl.call(self, url);
        };

        // Sobrescribir showSatData
        this.qrReader.showSatData = function() {
            return self._generateDataDisplay.call(self);
        };

        // Sobrescribir getLastScrapedData
        this.qrReader.getLastScrapedData = async function() {
            return self.lastScannedData || null;
        };
    }

    /**
     * Maneja archivos con reintentos automáticos
     */
    async _handleFileWithRetry(originalHandleFile, file) {
        this.currentAttempt = 0;
        
        while (this.currentAttempt < this.config.retryAttempts) {
            try {
                this._updateProgress('processing', this.currentAttempt + 1);
                
                // Validar archivo antes de procesar
                const validation = this._validateFile(file);
                if (!validation.valid) {
                    throw new Error(validation.error);
                }

                const result = await originalHandleFile.call(this.qrReader, file);

                if (result && result.success) {
                    const scrapedData = await this.qrReader.getLastScrapedData();

                    if (scrapedData && scrapedData.details) {
                        this.lastScannedData = scrapedData;
                        this._updateProgress('success');
                        
                        if (this.onDataScanned) {
                            this.onDataScanned(this.lastScannedData);
                        }

                        this._debug('📊 Datos extraídos exitosamente:', scrapedData);
                        return { success: true, data: this.lastScannedData };
                    }
                }

                throw new Error(result?.error || 'Error al procesar el archivo');
                
            } catch (error) {
                this.currentAttempt++;
                this._debug(`⚠️ Intento ${this.currentAttempt} fallido:`, error.message);

                if (this.currentAttempt >= this.config.retryAttempts) {
                    this._updateProgress('error', this.currentAttempt);
                    if (this.onError) {
                        this.onError(error.message);
                    }
                    return { success: false, error: error.message };
                }

                // Esperar antes del siguiente intento
                await this._delay(this.config.retryDelay);
            }
        }
    }

    /**
     * Procesa URL del código QR
     */
    async _processQRUrl(url) {
        try {
            this._debug('🔍 Procesando URL del QR:', url);
            
            const scrapedData = await this.scraper.scrapeData(url);
            
            if (scrapedData && scrapedData.success && scrapedData.data) {
                this.lastScannedData = scrapedData.data;
                this._debug('✅ Datos del SAT obtenidos exitosamente');
                return { success: true, data: scrapedData.data };
            }
            
            throw new Error(scrapedData?.error || 'Error al obtener datos del SAT');
        } catch (error) {
            this._error('❌ Error procesando URL del QR:', error);
            return { success: false, error: error.message };
        }
    }

    /**
     * Genera display de datos extraídos
     */
    _generateDataDisplay() {
        try {
            if (!this.lastScannedData) {
                return { success: false, error: 'No hay datos disponibles para mostrar' };
            }

            const content = this.scraper.generateModalContent(this.lastScannedData);
            
            // Actualizar modal si existe
            const satDataContent = document.getElementById('satDataContent');
            if (satDataContent) {
                satDataContent.innerHTML = content;
            }

            this._debug('📋 Display de datos generado exitosamente');
            return { success: true, content: content };
        } catch (error) {
            this._error('❌ Error generando display de datos:', error);
            return { success: false, error: error.message };
        }
    }

    /**
     * Valida archivo antes de procesar
     */
    _validateFile(file) {
        if (!file) {
            return { valid: false, error: 'No se ha seleccionado ningún archivo' };
        }

        if (file.size > this.config.maxFileSize) {
            return { 
                valid: false, 
                error: `El archivo es demasiado grande (máximo ${this.config.maxFileSize / 1024 / 1024}MB)` 
            };
        }

        const extension = file.name.split('.').pop()?.toLowerCase();
        if (!this.config.supportedFormats.includes(extension)) {
            return { 
                valid: false, 
                error: `Formato no soportado. Use: ${this.config.supportedFormats.join(', ')}` 
            };
        }

        return { valid: true };
    }

    /**
     * Actualiza progreso de procesamiento
     */
    _updateProgress(status, attempt = null) {
        if (this.onProgress) {
            this.onProgress({ status, attempt, maxAttempts: this.config.retryAttempts });
        }
    }

    /**
     * Configura event listeners
     */
    setupEventListeners() {
        // Event listeners personalizados pueden ir aquí
        this._debug('🎧 Event listeners configurados');
    }

    // Métodos públicos de configuración
    setOnDataScanned(callback) {
        this.onDataScanned = callback;
    }

    setOnError(callback) {
        this.onError = callback;
    }

    setOnProgress(callback) {
        this.onProgress = callback;
    }

    // Métodos públicos principales
    async handleFile(file) {
        if (this.isProcessing) {
            this._debug('⚠️ Ya hay un archivo en procesamiento');
            return { success: false, error: 'Ya hay un archivo siendo procesado' };
        }

        if (!this.qrReader) {
            throw new Error('QRHandler no ha sido inicializado correctamente');
        }

        try {
            this.isProcessing = true;
            const result = await this.qrReader.handleFile(file);
            return result;
        } catch (error) {
            this._error('❌ Error manejando archivo:', error);
            if (this.onError) {
                this.onError(error.message);
            }
            return { success: false, error: error.message };
        } finally {
            this.isProcessing = false;
        }
    }

    showSatData() {
        return this._generateDataDisplay();
    }

    getLastScannedData() {
        return this.lastScannedData;
    }

    getRfcFromData() {
        return this.lastScannedData?.details?.rfc || null;
    }

    reset() {
        this.lastScannedData = null;
        this.isProcessing = false;
        this.currentAttempt = 0;
        this._debug('🔄 Handler reseteado');
    }

    // Métodos utilitarios
    async _delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    _debug(message, data = null) {
        if (this.config.debug) {
            console.log(`[QRHandler] ${message}`, data || '');
        }
    }

    _error(message, error = null) {
        if (this.config.debug) {
            console.error(`[QRHandler] ${message}`, error || '');
        }
    }

    /**
     * Genera HTML mejorado para mostrar datos del SAT
     */
    generateSatDataHtml(details) {
        if (!details) {
            return '<div class="text-center text-gray-500">No hay datos disponibles</div>';
        }

        return `
            <div class="space-y-4">
                <!-- Información General -->
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        Información General
                    </h3>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-32">RFC:</span>
                            <span class="text-sm sm:text-base font-mono text-gray-900 break-all">${details.rfc || 'No disponible'}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-32">Tipo:</span>
                            <span class="text-sm sm:text-base text-gray-900">${details.tipoPersona || 'No especificado'}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-start">
                            <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-32">${details.tipoPersona === 'Moral' ? 'Razón Social:' : 'Nombre:'}</span>
                            <span class="text-sm sm:text-base text-gray-900 break-words">
                                ${details.tipoPersona === 'Moral' ? 
                                    (details.razonSocial || 'No disponible') : 
                                    (details.nombreCompleto || 'No disponible')}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Domicilio Fiscal -->
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        Domicilio Fiscal
                    </h3>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <div class="flex flex-col">
                            <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1">Dirección:</span>
                            <span class="text-sm sm:text-base text-gray-900 break-words">${details.direccion || 'No disponible'}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-20">CP:</span>
                                <span class="text-sm sm:text-base font-mono text-gray-900">${details.codigoPostal || 'No disponible'}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-16">Estado:</span>
                                <span class="text-sm sm:text-base text-gray-900">${details.entidadFederativa || 'No disponible'}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                        <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        Información Fiscal
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-20">Estatus:</span>
                            <span class="text-sm sm:text-base text-gray-900">${details.estatus || 'No disponible'}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center">
                            <span class="text-xs sm:text-sm text-gray-500 font-medium mb-1 sm:mb-0 sm:w-20">Registro:</span>
                            <span class="text-sm sm:text-base text-gray-900">${details.fechaRegistro || 'No disponible'}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

// Hacer la clase disponible globalmente
if (typeof window !== 'undefined') {
    window.QRHandler = QRHandler;
} 
