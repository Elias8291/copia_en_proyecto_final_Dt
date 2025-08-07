class FileUtils {
    static formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    static isPDF(file) {
        return file && file.type === 'application/pdf';
    }

    static isValidSize(file, maxSizeMB = 5) {
        if (!file) return false;
        const maxSizeBytes = maxSizeMB * 1024 * 1024;
        return file.size <= maxSizeBytes;
    }

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

window.FileUtils = FileUtils;