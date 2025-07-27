if (typeof SimpleQRExtractor === 'undefined') {
class SimpleQRExtractor {
    constructor() {
        this.pdfjsLib = window['pdfjs-dist/build/pdf'];
        if (this.pdfjsLib) {
            this.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://unpkg.com/pdfjs-dist@3.4.120/build/pdf.worker.min.js';
        }
    }

    async extractQRFromPDF(pdfFile) {
        try {
            if (!this.pdfjsLib) {
                throw new Error('PDF.js no está disponible');
            }

            if (typeof jsQR === 'undefined') {
                throw new Error('jsQR no está disponible');
            }

            const arrayBuffer = await this.readFileAsArrayBuffer(pdfFile);
            const pdf = await this.pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            const page = await pdf.getPage(1);
            const viewport = page.getViewport({ scale: 3.0 });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            await page.render(renderContext).promise;
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const qrCode = this.findQRCode(imageData);

            if (qrCode) {
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

    readFileAsArrayBuffer(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.onerror = (e) => reject(e);
            reader.readAsArrayBuffer(file);
        });
    }

    findQRCode(imageData) {
        try {
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code) {
                return code.data;
            }

            return this.findQRCodeWithScaling(imageData);

        } catch (error) {
            return null;
        }
    }

    findQRCodeWithScaling(imageData) {
        const scales = [0.5, 0.75, 1.25, 1.5];
        
        for (const scale of scales) {
            try {
                const tempCanvas = document.createElement('canvas');
                const tempContext = tempCanvas.getContext('2d');
                
                const scaledWidth = Math.floor(imageData.width * scale);
                const scaledHeight = Math.floor(imageData.height * scale);
                
                tempCanvas.width = scaledWidth;
                tempCanvas.height = scaledHeight;
                
                const scaledImageData = tempContext.createImageData(scaledWidth, scaledHeight);
                
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

window.SimpleQRExtractor = SimpleQRExtractor;
} 