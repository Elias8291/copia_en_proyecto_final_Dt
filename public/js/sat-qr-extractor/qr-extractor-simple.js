/**
 * Extractor simple de códigos QR desde PDF usando JavaScript
 * Versión limpia y funcional
 */

class SimpleQRExtractor {
    constructor() {
        this.pdfjsLib = window['pdfjs-dist/build/pdf'];
        if (this.pdfjsLib) {
            this.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.worker.min.js';
        }
    }

    /**
     * Extrae códigos QR de un archivo PDF
     * @param {File} pdfFile - Archivo PDF
     * @returns {Promise<Object>} - Resultado con URL del QR
     */
                    async extractQRFromPDF(pdfFile) {
                    try {
                        // Verificar dependencias
                        if (!this.pdfjsLib) {
                            throw new Error('PDF.js no está disponible');
                        }

                        if (typeof jsQR === 'undefined') {
                            throw new Error('jsQR no está disponible');
                        }

                        // Leer el archivo PDF
                        const arrayBuffer = await this.readFileAsArrayBuffer(pdfFile);

                        // Cargar el PDF
                        const pdf = await this.pdfjsLib.getDocument({ data: arrayBuffer }).promise;

                        // Procesar solo la primera página
                        const page = await pdf.getPage(1);

                        // Configurar viewport para alta resolución
                        const viewport = page.getViewport({ scale: 3.0 }); // Escala 3x para mejor detección

                        // Crear canvas para renderizar la página
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Renderizar página en canvas
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };

                        await page.render(renderContext).promise;

                        // Extraer datos de imagen del canvas
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                        // Buscar códigos QR en la imagen
                        const qrCode = this.findQRCode(imageData);

                        if (qrCode) {
                            // Validar que sea URL del SAT
                            if (qrCode.includes('siat.sat.gob.mx')) {
                                return {
                                    success: true,
                                    url: qrCode,
                                    message: 'Código QR extraído exitosamente'
                                };
                            } else {
                                return {
                                    success: false,
                                    error: 'URL encontrada no es del SAT oficial'
                                };
                            }
                        } else {
                            return {
                                success: false,
                                error: 'No se encontró código QR en el PDF'
                            };
                        }

                    } catch (error) {
                        return {
                            success: false,
                            error: 'Error procesando PDF: ' + error.message
                        };
                    }
                }

    /**
     * Lee un archivo como ArrayBuffer
     * @param {File} file - Archivo a leer
     * @returns {Promise<ArrayBuffer>}
     */
    readFileAsArrayBuffer(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.onerror = (e) => reject(e);
            reader.readAsArrayBuffer(file);
        });
    }

    /**
     * Busca códigos QR en ImageData usando jsQR
     * @param {ImageData} imageData - Datos de imagen
     * @returns {string|null} - URL del QR o null si no se encuentra
     */
                    findQRCode(imageData) {
                    try {
                        // Buscar códigos QR
                        const code = jsQR(imageData.data, imageData.width, imageData.height);

                        if (code) {
                            return code.data;
                        }

                        // Si no se encuentra, intentar con diferentes escalas
                        return this.findQRCodeWithScaling(imageData);

                    } catch (error) {
                        return null;
                    }
                }

    /**
     * Busca QR con diferentes escalas para mejorar detección
     * @param {ImageData} imageData - Datos de imagen original
     * @returns {string|null}
     */
    findQRCodeWithScaling(imageData) {
        const scales = [0.5, 0.75, 1.25, 1.5];
        
        for (const scale of scales) {
            try {
                // Crear canvas temporal para escalar
                const tempCanvas = document.createElement('canvas');
                const tempContext = tempCanvas.getContext('2d');
                
                const scaledWidth = Math.floor(imageData.width * scale);
                const scaledHeight = Math.floor(imageData.height * scale);
                
                tempCanvas.width = scaledWidth;
                tempCanvas.height = scaledHeight;
                
                // Crear ImageData escalado
                const scaledImageData = tempContext.createImageData(scaledWidth, scaledHeight);
                
                // Escalar imagen usando nearest neighbor
                for (let y = 0; y < scaledHeight; y++) {
                    for (let x = 0; x < scaledWidth; x++) {
                        const srcX = Math.floor(x / scale);
                        const srcY = Math.floor(y / scale);
                        
                        const srcIndex = (srcY * imageData.width + srcX) * 4;
                        const dstIndex = (y * scaledWidth + x) * 4;
                        
                        scaledImageData.data[dstIndex] = imageData.data[srcIndex];
                        scaledImageData.data[dstIndex + 1] = imageData.data[srcIndex + 1];
                        scaledImageData.data[dstIndex + 2] = imageData.data[srcIndex + 2];
                        scaledImageData.data[dstIndex + 3] = imageData.data[srcIndex + 3];
                    }
                }
                
                                            // Buscar QR en imagen escalada
                            const code = jsQR(scaledImageData.data, scaledWidth, scaledHeight);
                            if (code) {
                                return code.data;
                            }

                        } catch (error) {
                            continue;
                        }
        }
        
        return null;
    }
}

// Exportar para uso global
window.SimpleQRExtractor = SimpleQRExtractor; 