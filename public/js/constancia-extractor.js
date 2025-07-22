/**
 * ConstanciaExtractor - Componente Simple y Reutilizable
 * Basado en el sistema que SÍ funciona en constancia.blade.php
 * 
 * Uso:
 * const extractor = new ConstanciaExtractor();
 * const datos = await extractor.extract(file);
 */

// Evitar redeclaración si ya existe
if (typeof ConstanciaExtractor === 'undefined') {

class ConstanciaExtractor {
    constructor(options = {}) {
        this.options = {
            debug: false,
            ...options
        };
    }

    /**
     * Extrae datos de una constancia fiscal (método principal)
     * @param {File} file - Archivo PDF de la constancia
     * @returns {Promise<Object>} - Datos extraídos o error
     */
    async extract(file) {
        try {
            // Validar archivo
            const validation = this.validateFile(file);
            if (!validation.valid) {
                return { success: false, error: validation.error };
            }

            // Paso 1: Extraer QR del PDF
            const qrResult = await this.extractQRFromPDF(file);
            if (!qrResult.success) {
                return qrResult;
            }

            // Paso 2: Hacer scraping del SAT con la URL extraída
            const satResult = await this.scrapeSATData(qrResult.url);
            if (!satResult.success) {
                return satResult;
            }

            // Retornar datos limpios
            return {
                success: true,
                qr_url: qrResult.url,
                sat_data: satResult.sat_data
            };

        } catch (error) {
            return {
                success: false,
                error: 'Error interno: ' + error.message
            };
        }
    }

    /**
     * Valida que el archivo sea correcto
     * @param {File} file 
     * @returns {Object} validation result
     */
    validateFile(file) {
        if (!file) {
            return { valid: false, error: 'No se proporcionó archivo' };
        }

        if (file.type !== 'application/pdf') {
            return { valid: false, error: 'Solo se permiten archivos PDF' };
        }

        if (file.size > 5 * 1024 * 1024) { // 5MB
            return { valid: false, error: 'El archivo es demasiado grande. Máximo 5MB' };
        }

        return { valid: true };
    }

    /**
     * Paso 1: Extrae QR del PDF (usando la API que funciona)
     * @param {File} file 
     * @returns {Promise<Object>}
     */
    async extractQRFromPDF(file) {
        const formData = new FormData();
        formData.append('pdf', file);

        try {
            const response = await fetch('/api/extract-qr-url', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();

            if (data.success && data.url) {
                return {
                    success: true,
                    url: data.url
                };
            } else {
                return {
                    success: false,
                    error: data.error || 'No se encontró código QR en el documento'
                };
            }

        } catch (error) {
            return {
                success: false,
                error: 'Error al extraer QR: ' + error.message
            };
        }
    }

    /**
     * Paso 2: Hace scraping del SAT (usando la API que funciona)
     * @param {string} url 
     * @returns {Promise<Object>}
     */
    async scrapeSATData(url) {
        // Validar que sea URL del SAT
        if (!url.includes('siat.sat.gob.mx')) {
            return {
                success: false,
                error: 'La constancia debe ser del SAT oficial'
            };
        }

        try {
            const response = await fetch('/api/scrape-sat-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify({ url: url })
            });

            const data = await response.json();

            if (data.success && data.sat_data && data.sat_data.success) {
                return {
                    success: true,
                    sat_data: this.normalizeSATData(data.sat_data)
                };
            } else {
                return {
                    success: false,
                    error: data.error || 'No se pudieron extraer los datos del SAT'
                };
            }

        } catch (error) {
            return {
                success: false,
                error: 'Error al consultar SAT: ' + error.message
            };
        }
    }

    /**
     * Normaliza los datos del SAT para uso uniforme
     * @param {Object} rawData 
     * @returns {Object}
     */
    normalizeSATData(rawData) {
        // Usar los datos del formulario si están disponibles, sino usar los datos raw
        const formData = rawData.form_data || rawData;
        
        return {
            rfc: formData.rfc || '',
            nombre: formData.razon_social || formData.nombre || '',
            curp: formData.curp || '',
            regimen_fiscal: formData.regimen_fiscal || '',
            estatus: formData.estatus || '',
            entidad_federativa: formData.entidad_federativa || '',
            municipio: formData.municipio || '',
            email: formData.email || '',
            tipo_persona: formData.tipo_persona || '',
            cp: formData.codigo_postal || formData.cp || '',
            colonia: formData.colonia || '',
            nombre_vialidad: formData.calle || formData.nombre_vialidad || '',
            numero_exterior: formData.numero_exterior || '',
            numero_interior: formData.numero_interior || ''
        };
    }

    /**
     * Obtiene el token CSRF
     * @returns {string}
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Método con callbacks para manejar UI
     * @param {File} file 
     * @param {Object} callbacks - { onStart, onProgress, onSuccess, onError, onFinish }
     * @returns {Promise<Object>}
     */
    async extractWithCallbacks(file, callbacks = {}) {
        const { onStart, onProgress, onSuccess, onError, onFinish } = callbacks;

        // Callback de inicio
        if (typeof onStart === 'function') {
            onStart();
        }

        try {
            // Progreso: Validando archivo
            if (typeof onProgress === 'function') {
                onProgress('Validando archivo...');
            }

            const validation = this.validateFile(file);
            if (!validation.valid) {
                if (typeof onError === 'function') {
                    onError(validation.error);
                }
                return { success: false, error: validation.error };
            }

            // Progreso: Extrayendo QR
            if (typeof onProgress === 'function') {
                onProgress('Extrayendo código QR del PDF...');
            }

            const qrResult = await this.extractQRFromPDF(file);
            if (!qrResult.success) {
                if (typeof onError === 'function') {
                    onError(qrResult.error);
                }
                return qrResult;
            }

            // Progreso: Consultando SAT
            if (typeof onProgress === 'function') {
                onProgress('Obteniendo datos fiscales del SAT...');
            }

            const satResult = await this.scrapeSATData(qrResult.url);
            if (!satResult.success) {
                if (typeof onError === 'function') {
                    onError(satResult.error);
                }
                return satResult;
            }

            // Éxito
            const finalResult = {
                success: true,
                qr_url: qrResult.url,
                sat_data: satResult.sat_data
            };

            if (typeof onSuccess === 'function') {
                onSuccess(finalResult.sat_data, finalResult.qr_url);
            }

            return finalResult;

        } catch (error) {
            const errorMessage = 'Error interno: ' + error.message;
            
            if (typeof onError === 'function') {
                onError(errorMessage);
            }

            return { success: false, error: errorMessage };

        } finally {
            // Callback final (siempre se ejecuta)
            if (typeof onFinish === 'function') {
                onFinish();
            }
        }
    }
}

// Exportar para uso global
window.ConstanciaExtractor = ConstanciaExtractor;

} // Fin del if de protección contra redeclaración 