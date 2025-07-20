#!/usr/bin/env node
/**
 * Script para extraer códigos QR de archivos PDF usando Node.js
 * Requiere: npm install pdf-poppler jimp qrcode-reader
 */

const fs = require('fs');
const path = require('path');
const pdf = require('pdf-poppler');
const Jimp = require('jimp');
const QrCode = require('qrcode-reader');

async function extractQrFromPdf(pdfPath) {
    try {
        // Verificar que el archivo existe
        if (!fs.existsSync(pdfPath)) {
            return { success: false, error: "Archivo PDF no encontrado" };
        }

        // Configuración para convertir PDF a imágenes
        const options = {
            format: 'png',
            out_dir: path.dirname(pdfPath),
            out_prefix: 'temp_page',
            page: null // Convertir todas las páginas
        };

        // Convertir PDF a imágenes
        const pages = await pdf.convert(pdfPath, options);
        
        // Procesar cada página
        for (let i = 1; i <= pages.length; i++) {
            const imagePath = path.join(options.out_dir, `${options.out_prefix}-${i}.png`);
            
            try {
                // Leer imagen con Jimp
                const image = await Jimp.read(imagePath);
                
                // Crear instancia del lector QR
                const qr = new QrCode();
                
                // Promesa para leer QR
                const qrResult = await new Promise((resolve, reject) => {
                    qr.callback = (err, value) => {
                        if (err) {
                            reject(err);
                        } else {
                            resolve(value);
                        }
                    };
                    qr.decode(image.bitmap);
                });
                
                // Verificar si es una URL válida
                if (qrResult && qrResult.result && qrResult.result.startsWith('http')) {
                    // Limpiar archivos temporales
                    cleanupTempFiles(options.out_dir, options.out_prefix, pages.length);
                    return { success: true, url: qrResult.result };
                }
                
            } catch (pageError) {
                // Continuar con la siguiente página
                continue;
            } finally {
                // Limpiar archivo de página actual
                if (fs.existsSync(imagePath)) {
                    fs.unlinkSync(imagePath);
                }
            }
        }
        
        // Limpiar archivos temporales restantes
        cleanupTempFiles(options.out_dir, options.out_prefix, pages.length);
        
        return { success: false, error: "No se encontró código QR válido en el PDF" };
        
    } catch (error) {
        return { success: false, error: `Error procesando PDF: ${error.message}` };
    }
}

function cleanupTempFiles(dir, prefix, pageCount) {
    for (let i = 1; i <= pageCount; i++) {
        const filePath = path.join(dir, `${prefix}-${i}.png`);
        if (fs.existsSync(filePath)) {
            try {
                fs.unlinkSync(filePath);
            } catch (e) {
                // Ignorar errores de limpieza
            }
        }
    }
}

async function main() {
    if (process.argv.length !== 3) {
        console.log(JSON.stringify({ success: false, error: "Uso: node qr_extractor.js <ruta_pdf>" }));
        process.exit(1);
    }
    
    const pdfPath = process.argv[2];
    const result = await extractQrFromPdf(pdfPath);
    console.log(JSON.stringify(result));
}

if (require.main === module) {
    main().catch(error => {
        console.log(JSON.stringify({ success: false, error: error.message }));
        process.exit(1);
    });
}