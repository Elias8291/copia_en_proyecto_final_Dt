/**
 * File Utilities
 * Funciones comunes para manejo de archivos
 */
class FileUtils {
    /**
     * Formatea el tamaño de archivo en formato legible
     * @param {number} bytes - Tamaño en bytes
     * @returns {string} Tamaño formateado
     */
    static formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Valida si un archivo es PDF
     * @param {File} file - Archivo a validar
     * @returns {boolean} True si es PDF válido
     */
    static isPDF(file) {
        return file && file.type === 'application/pdf';
    }

    /**
     * Valida el tamaño máximo del archivo
     * @param {File} file - Archivo a validar
     * @param {number} maxSizeMB - Tamaño máximo en MB (default: 5)
     * @returns {boolean} True si el tamaño es válido
     */
    static isValidSize(file, maxSizeMB = 5) {
        if (!file) return false;
        const maxSizeBytes = maxSizeMB * 1024 * 1024;
        return file.size <= maxSizeBytes;
    }

    /**
     * Valida un archivo PDF completo
     * @param {File} file - Archivo a validar
     * @param {number} maxSizeMB - Tamaño máximo en MB
     * @returns {Object} Resultado de validación
     */
    static validatePDF(file, maxSizeMB = 5) {
        const result = {
            valid: true,
            errors: []
        };

        if (!file) {
            result.valid = false;
            result.errors.push('No se ha seleccionado ningún archivo');
            return result;
        }

        if (!this.isPDF(file)) {
            result.valid = false;
            result.errors.push('El archivo debe ser un PDF válido');
        }

        if (!this.isValidSize(file, maxSizeMB)) {
            result.valid = false;
            result.errors.push(`El archivo es demasiado grande. Máximo ${maxSizeMB}MB permitido`);
        }

        return result;
    }

    /**
     * Crea un preview del archivo seleccionado
     * @param {File} file - Archivo seleccionado
     * @returns {Object} Información del archivo
     */
    static getFileInfo(file) {
        if (!file) return null;

        return {
            name: file.name,
            size: this.formatFileSize(file.size),
            type: file.type,
            lastModified: new Date(file.lastModified).toLocaleDateString()
        };
    }
}

// Hacer disponible globalmente
window.FileUtils = FileUtils;