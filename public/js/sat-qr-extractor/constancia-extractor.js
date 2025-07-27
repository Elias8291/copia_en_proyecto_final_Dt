if (typeof ConstanciaExtractor === 'undefined') {

class ConstanciaExtractor {
    constructor(options = {}) {
        this.options = {
            debug: false,
            ...options
        };
    }

    async extract(file) {
        try {
            const validation = this.validateFile(file);
            if (!validation.valid) {
                return { success: false, error: validation.error };
            }

            const qrResult = await this.extractQRFromPDF(file);
            if (!qrResult.success) {
                return qrResult;
            }

            const satResult = await this.scrapeSATData(qrResult.url);
            if (!satResult.success) {
                return satResult;
            }

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

    validateFile(file) {
        if (!file) {
            return { valid: false, error: 'No se proporcionó archivo' };
        }

        if (file.type !== 'application/pdf') {
            return { valid: false, error: 'Solo se permiten archivos PDF' };
        }

        if (file.size > 5 * 1024 * 1024) {
            return { valid: false, error: 'El archivo es demasiado grande. Máximo 5MB' };
        }

        return { valid: true };
    }

    async extractQRFromPDF(file) {
        try {
            if (typeof window['pdfjs-dist/build/pdf'] === 'undefined') {
                throw new Error('PDF.js no está disponible');
            }
            
            if (typeof jsQR === 'undefined') {
                throw new Error('jsQR no está disponible');
            }
            
            const qrExtractor = new SimpleQRExtractor();
            const result = await qrExtractor.extractQRFromPDF(file);
            
            return result;
            
        } catch (error) {
            return {
                success: false,
                error: 'Error procesando PDF: ' + error.message
            };
        }
    }

    async scrapeSATData(url) {
        try {
            const satScraper = new SimpleSATScraper();
            const result = await satScraper.scrapeSATData(url);
            
            return result;
            
        } catch (error) {
            return {
                success: false,
                error: 'Error consultando SAT: ' + error.message
            };
        }
    }

    normalizeSATData(rawData) {
        const formData = rawData.form_data || rawData;
        
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

    normalizeFromSections(rawData) {
        const identificacion = rawData.identificacion || {};
        const ubicacion = rawData.ubicacion || {};
        const caracteristicas = rawData.caracteristicas_fiscales || {};
        
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

    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    async extractWithCallbacks(file, callbacks = {}) {
        const { onStart, onProgress, onSuccess, onError, onFinish } = callbacks;

        if (typeof onStart === 'function') {
            onStart();
        }

        try {
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
            if (typeof onFinish === 'function') {
                onFinish();
            }
        }
    }
}

window.ConstanciaExtractor = ConstanciaExtractor;

} 