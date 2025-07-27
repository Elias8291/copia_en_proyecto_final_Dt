/**
 * Scraper simple del SAT usando JavaScript
 * Versión limpia y funcional
 */

class SimpleSATScraper {
    constructor() {
        this.baseUrl = '/api/scrape-sat-data';
    }

    /**
     * Scrapea datos del SAT desde una URL
     * @param {string} url - URL del QR del SAT
     * @returns {Promise<Object>} - Datos del SAT
     */
                    async scrapeSATData(url) {
                    try {
                        // Validar que sea URL del SAT
                        if (!url.includes('siat.sat.gob.mx')) {
                            return {
                                success: false,
                                error: 'La URL debe ser del SAT oficial'
                            };
                        }

                        // Obtener token CSRF
                        const csrfToken = this.getCSRFToken();

                        // Hacer petición al servidor
                        const response = await fetch(this.baseUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ url: url })
                        });

                        const data = await response.json();

                        if (data.success && data.sat_data) {
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
                    // Usar los datos del formulario si están disponibles
                    const formData = rawData.form_data || rawData;

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
     * Obtiene el token CSRF
     * @returns {string}
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }
}

// Exportar para uso global
window.SimpleSATScraper = SimpleSATScraper; 