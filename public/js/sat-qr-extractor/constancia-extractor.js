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
        try {
            // Verificar si las dependencias están disponibles
            if (typeof window['pdfjs-dist/build/pdf'] === 'undefined') {
                throw new Error('PDF.js no está disponible');
            }
            
            if (typeof jsQR === 'undefined') {
                throw new Error('jsQR no está disponible');
            }
            
            // Crear extractor simple
            const qrExtractor = new SimpleQRExtractor();
            
            // Extraer QR
            const result = await qrExtractor.extractQRFromPDF(file);
            
            return result;
            
        } catch (error) {
            return {
                success: false,
                error: 'Error procesando PDF: ' + error.message
            };
        }
    }



    /**
     * Paso 2: Hace scraping del SAT usando JavaScript
     * @param {string} url 
     * @returns {Promise<Object>}
     */
    async scrapeSATData(url) {
        try {
            // Crear scraper simple
            const satScraper = new SimpleSATScraper();
            
            // Scrapear datos del SAT
            const result = await satScraper.scrapeSATData(url);
            
            return result;
            
        } catch (error) {
            return {
                success: false,
                error: 'Error consultando SAT: ' + error.message
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
        
        // Si no hay form_data, intentar extraer datos de las secciones
        if (!rawData.form_data && rawData.identificacion) {
            return this.normalizeFromSections(rawData);
        }
        
        const normalized = {
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
        
        return normalized;
    }

    /**
     * Normaliza datos desde las secciones del SAT
     * @param {Object} rawData 
     * @returns {Object}
     */
    normalizeFromSections(rawData) {
        const identificacion = rawData.identificacion || {};
        const ubicacion = rawData.ubicacion || {};
        const caracteristicas = rawData.caracteristicas_fiscales || {};
        
        // Construir nombre completo para persona física
        let nombre = '';
        if (rawData.tipo_persona === 'fisica') {
            const nombreParts = [
                identificacion.nombre || '',
                identificacion.apellido_paterno || '',
                identificacion.apellido_materno || ''
            ].filter(part => part.trim());
            nombre = nombreParts.join(' ');
        } else {
            nombre = identificacion.denominacion_o_razon_social || '';
        }
        
        return {
            rfc: rawData.rfc || '',
            nombre: nombre,
            curp: rawData.curp_validado || identificacion.curp || '',
            regimen_fiscal: caracteristicas.regimen || '',
            estatus: caracteristicas.situacion_del_contribuyente || '',
            entidad_federativa: ubicacion.entidad_federativa || '',
            municipio: ubicacion.municipio_o_delegacion || '',
            email: ubicacion.correo_electronico || '',
            tipo_persona: rawData.tipo_persona || '',
            cp: ubicacion.cp || '',
            colonia: ubicacion.colonia || ubicacion.localidad || '',
            nombre_vialidad: ubicacion.nombre_de_la_vialidad || '',
            numero_exterior: ubicacion.numero_exterior || '',
            numero_interior: ubicacion.numero_interior || ''
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