/**
 * Sistema de Validación de Documentos - Tipos de Archivo
 */

class DocumentosValidator {
    constructor() {
        this.tiposArchivo = {
            'pdf': {
                mimeTypes: ['application/pdf'],
                extensions: ['.pdf'],
                label: 'PDF'
            },
            'png': {
                mimeTypes: ['image/png'],
                extensions: ['.png'],
                label: 'PNG'
            },
            'jpg': {
                mimeTypes: ['image/jpeg', 'image/jpg'],
                extensions: ['.jpg', '.jpeg'],
                label: 'JPG'
            },
            'jpeg': {
                mimeTypes: ['image/jpeg'],
                extensions: ['.jpeg', '.jpg'],
                label: 'JPEG'
            },
            'doc': {
                mimeTypes: ['application/msword'],
                extensions: ['.doc'],
                label: 'DOC'
            },
            'docx': {
                mimeTypes: ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                extensions: ['.docx'],
                label: 'DOCX'
            },
            'xls': {
                mimeTypes: ['application/vnd.ms-excel'],
                extensions: ['.xls'],
                label: 'XLS'
            },
            'xlsx': {
                mimeTypes: ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                extensions: ['.xlsx'],
                label: 'XLSX'
            }
        };
        
        this.init();
    }
    
    init() {
        this.setupFileInputs();
    }
    
    setupFileInputs() {
        // Configurar todos los inputs de archivo existentes
        document.querySelectorAll('input[type="file"][name*="documentos"]').forEach(input => {
            this.setupFileInput(input);
        });
        
        // Observar cambios en el DOM para nuevos inputs
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        const newInputs = node.querySelectorAll ? node.querySelectorAll('input[type="file"][name*="documentos"]') : [];
                        newInputs.forEach(input => this.setupFileInput(input));
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    setupFileInput(input) {
        const documentoId = this.getDocumentoId(input);
        const tipoArchivo = this.getTipoArchivo(documentoId);
        
        if (!tipoArchivo) {
            console.warn(`No se pudo determinar el tipo de archivo para el documento ${documentoId}`);
            return;
        }
        
        // Configurar el accept del input para filtrar en el explorador de archivos
        const acceptTypes = this.getAcceptTypesForExplorer(tipoArchivo);
        input.accept = acceptTypes.join(',');
        
        // Agregar event listener para validación
        input.addEventListener('change', (e) => this.validateFile(e.target, tipoArchivo));
        
        // Agregar información visual del tipo de archivo
        this.addFileTypeInfo(input, tipoArchivo);
    }
    
    getDocumentoId(input) {
        const name = input.name;
        const match = name.match(/documentos\[(\d+)\]/);
        return match ? match[1] : null;
    }
    
    getTipoArchivo(documentoId) {
        // Buscar el tipo de archivo en el DOM
        const documentoContainer = document.querySelector(`[data-documento-id="${documentoId}"]`);
        if (!documentoContainer) return null;
        
        // Buscar el span que contiene el tipo de archivo
        const tipoSpan = documentoContainer.querySelector('.inline-flex.items-center.px-2.py-1.rounded-full.text-xs.font-medium.bg-black\\/10.text-black');
        if (tipoSpan) {
            const tipoText = tipoSpan.textContent.trim().toLowerCase();
            return tipoText;
        }
        
        return null;
    }
    
    getAcceptTypes(tipoArchivo) {
        const tipo = this.tiposArchivo[tipoArchivo.toLowerCase()];
        if (!tipo) return [];
        
        return [...tipo.mimeTypes, ...tipo.extensions];
    }

    getAcceptTypesForExplorer(tipoArchivo) {
        const tipo = this.tiposArchivo[tipoArchivo.toLowerCase()];
        if (!tipo) return [];
        
        // Para el explorador de archivos, usar tanto MIME types como extensiones
        // Los navegadores modernos soportan ambos formatos
        const acceptTypes = [];
        
        // Agregar MIME types
        tipo.mimeTypes.forEach(mimeType => {
            acceptTypes.push(mimeType);
        });
        
        // Agregar extensiones con punto
        tipo.extensions.forEach(ext => {
            acceptTypes.push(ext);
        });
        
        return acceptTypes;
    }
    
    validateFile(input, expectedType) {
        const file = input.files[0];
        if (!file) return;
        
        const isValid = this.isValidFileType(file, expectedType);
        
        if (!isValid) {
            this.showFileTypeError(input, expectedType);
            input.value = ''; // Limpiar el input
            return false;
        }
        
        this.hideFileTypeError(input);
        this.updateFileStatus(input, file.name);
        return true;
    }
    
    isValidFileType(file, expectedType) {
        const tipo = this.tiposArchivo[expectedType.toLowerCase()];
        if (!tipo) return false;
        
        // Verificar por MIME type
        if (tipo.mimeTypes.includes(file.type)) {
            return true;
        }
        
        // Verificar por extensión
        const fileName = file.name.toLowerCase();
        return tipo.extensions.some(ext => fileName.endsWith(ext));
    }
    
    showFileTypeError(input, expectedType) {
        const documentoId = this.getDocumentoId(input);
        const tipo = this.tiposArchivo[expectedType.toLowerCase()];
        const label = tipo ? tipo.label : expectedType.toUpperCase();
        
        // Crear o actualizar mensaje de error
        let errorDiv = input.parentElement.querySelector('.file-type-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'file-type-error mt-2 flex items-center text-red-600';
            input.parentElement.appendChild(errorDiv);
        }
        
        errorDiv.innerHTML = `
            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium">Solo se permiten archivos ${label}</span>
        `;
        
        // Agregar clase de error al input
        input.classList.add('border-red-500', 'bg-red-50');
    }
    
    hideFileTypeError(input) {
        const errorDiv = input.parentElement.querySelector('.file-type-error');
        if (errorDiv) {
            errorDiv.remove();
        }
        
        // Remover clases de error del input
        input.classList.remove('border-red-500', 'bg-red-50');
    }
    
    updateFileStatus(input, fileName) {
        const documentoId = this.getDocumentoId(input);
        const statusSpan = document.getElementById(`status_${documentoId}`);
        const filenameDiv = document.getElementById(`filename_${documentoId}`);
        
        if (statusSpan) {
            statusSpan.className = 'inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full';
            statusSpan.innerHTML = `
                <i class="fas fa-check mr-1"></i>
                <span class="hidden xs:inline">Subido</span>
            `;
        }
        
        if (filenameDiv) {
            filenameDiv.textContent = fileName;
            filenameDiv.classList.remove('hidden');
        }
    }
    
    addFileTypeInfo(input, tipoArchivo) {
        const tipo = this.tiposArchivo[tipoArchivo.toLowerCase()];
        if (!tipo) return;
        
        // Agregar información visual del tipo de archivo permitido
        const label = input.parentElement.querySelector('label[for="' + input.id + '"]');
        if (label) {
            const tipoInfo = document.createElement('span');
            tipoInfo.className = 'ml-2 text-xs text-gray-500';
            tipoInfo.textContent = `(${tipo.label})`;
            label.appendChild(tipoInfo);
        }
    }
}

// Funciones globales para compatibilidad
window.validateFileType = function(file, expectedType) {
    if (!window.documentosValidator) return true;
    return window.documentosValidator.isValidFileType(file, expectedType);
};

window.getExpectedFileType = function(documentoId) {
    if (!window.documentosValidator) return null;
    const input = document.querySelector(`input[name="documentos[${documentoId}]"]`);
    if (!input) return null;
    return window.documentosValidator.getTipoArchivo(documentoId);
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.documentosValidator = new DocumentosValidator();
}); 