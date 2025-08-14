/**
 * Validador específico para formularios de corrección de trámites
 * Se encarga de validar secciones rechazadas y archivos de corrección (solo visual)
 */

class CorreccionFormValidator {
    constructor() {
        this.init();
    }

    init() {
        console.log('CorreccionFormValidator: Iniciando validador de corrección (solo visual)');
        this.setupValidation();
        this.setupRealTimeValidation();
    }

    setupValidation() {
        const form = document.getElementById('tramite-form');
        if (!form) {
            console.warn('CorreccionFormValidator: Formulario no encontrado');
            return;
        }

        // Interceptar el envío del formulario (solo mostrar advertencias)
        form.addEventListener('submit', (e) => {
            console.log('CorreccionFormValidator: Validando formulario antes del envío (solo advertencias)');
            
            const isValid = this.validateForm();
            
            // Solo mostrar advertencia si hay errores, pero permitir envío
            if (!isValid) {
                this.showValidationWarning();
            }
            
            console.log('CorreccionFormValidator: Formulario permitiendo envío (con o sin errores)');
            return true; // Siempre permitir envío
        });
    }

    setupRealTimeValidation() {
        // Validación en tiempo real para campos requeridos (solo visual)
        const requiredFields = document.querySelectorAll('[required], .required');
        requiredFields.forEach(field => {
            field.addEventListener('input', () => this.validateField(field));
            field.addEventListener('blur', () => this.validateField(field));
        });

        // Validación en tiempo real para archivos de corrección (solo visual)
        const archivosCorreccion = document.querySelectorAll('input[name^="documentos_correccion"]');
        archivosCorreccion.forEach(input => {
            input.addEventListener('change', () => this.validateArchivoCorreccion(input));
        });
    }

    validateForm() {
        console.log('CorreccionFormValidator: Iniciando validación completa del formulario (solo visual)');
        
        const seccionesValid = this.validateSeccionesRechazadas();
        const archivosValid = this.validateArchivosCorreccion();
        
        console.log(`CorreccionFormValidator: Resultado validación - Secciones: ${seccionesValid}, Archivos: ${archivosValid}`);
        
        return seccionesValid && archivosValid;
    }

    validateSeccionesRechazadas() {
        const seccionesRechazadas = document.querySelectorAll('[data-section]');
        let allValid = true;

        seccionesRechazadas.forEach(section => {
            const sectionName = section.dataset.section;
            const isValid = this.validateSection(section, sectionName);
            
            if (!isValid) {
                allValid = false;
            }
        });

        return allValid;
    }

    validateSection(section, sectionName) {
        console.log(`CorreccionFormValidator: Validando sección: ${sectionName}`);
        
        let isValid = true;
        const requiredFields = section.querySelectorAll('[required], .required');

        requiredFields.forEach(field => {
            const fieldValid = this.validateField(field);
            if (!fieldValid) {
                isValid = false;
            }
        });

        // Actualizar indicador visual de la sección
        this.updateSectionIndicator(section, isValid);

        console.log(`CorreccionFormValidator: Sección ${sectionName} - Válida: ${isValid}`);
        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const isValid = value !== '';

        if (isValid) {
            field.classList.remove('border-red-500');
            this.clearFieldError(field);
        } else {
            field.classList.add('border-red-500');
            this.showFieldWarning(field, 'Este campo es recomendado');
        }

        return isValid;
    }

    validateArchivosCorreccion() {
        console.log('CorreccionFormValidator: Validando archivos de corrección (solo visual)');
        
        const archivosRechazados = document.querySelectorAll('.archivo-item .bg-red-100, .archivo-item .text-red-600');
        let allValid = true;

        archivosRechazados.forEach(archivoRechazado => {
            const container = archivoRechazado.closest('.archivo-item');
            const fileInput = container?.querySelector('input[type="file"]');
            
            if (fileInput) {
                const isValid = this.validateArchivoCorreccion(fileInput);
                if (!isValid) {
                    allValid = false;
                }
            }
        });

        console.log(`CorreccionFormValidator: Archivos de corrección - Válidos: ${allValid}`);
        return allValid;
    }

    validateArchivoCorreccion(input) {
        const file = input.files[0];
        const container = input.closest('.border-dashed');
        
        if (!container) return true;

        // Limpiar mensajes anteriores
        this.clearArchivoMessages(container);

        if (!file) {
            input.classList.add('border-yellow-500');
            container.classList.add('border-yellow-500');
            container.classList.remove('border-green-500', 'border-red-500');
            this.showArchivoWarning(container, 'Se recomienda subir un archivo para corregir el documento rechazado');
            return false;
        }

        // Validar tipo de archivo
        const allowedTypes = ['pdf', 'png', 'jpg', 'jpeg'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            input.classList.add('border-yellow-500');
            container.classList.add('border-yellow-500');
            container.classList.remove('border-green-500', 'border-red-500');
            this.showArchivoWarning(container, 'Se recomienda usar archivos de tipo: PDF, PNG, JPG, JPEG');
            return false;
        }

        // Validar tamaño (10MB máximo)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            input.classList.add('border-yellow-500');
            container.classList.add('border-yellow-500');
            container.classList.remove('border-green-500', 'border-red-500');
            this.showArchivoWarning(container, 'Se recomienda archivos menores a 10MB');
            return false;
        }

        // Archivo válido
        input.classList.remove('border-yellow-500', 'border-red-500');
        container.classList.remove('border-yellow-500', 'border-red-500');
        container.classList.add('border-green-500');
        this.showArchivoSuccess(container, 'Archivo válido');
        
        // Actualizar nombre del archivo
        this.updateFileName(input, file.name);
        
        return true;
    }

    updateSectionIndicator(section, isValid) {
        const indicator = section.querySelector('.bg-red-50, .bg-green-50');
        if (!indicator) return;

        if (isValid) {
            indicator.classList.remove('bg-red-50', 'border-red-200');
            indicator.classList.add('bg-green-50', 'border-green-200');
            
            const badge = indicator.querySelector('.bg-red-600, .bg-green-600');
            if (badge) {
                badge.classList.remove('bg-red-600');
                badge.classList.add('bg-green-600');
                badge.textContent = 'Completo';
            }
        } else {
            indicator.classList.remove('bg-green-50', 'border-green-200');
            indicator.classList.add('bg-red-50', 'border-red-200');
            
            const badge = indicator.querySelector('.bg-green-600, .bg-red-600');
            if (badge) {
                badge.classList.remove('bg-green-600');
                badge.classList.add('bg-red-600');
                badge.textContent = 'Incompleto';
            }
        }
    }

    showFieldWarning(field, message) {
        this.clearFieldError(field);
        
        const warningDiv = document.createElement('div');
        warningDiv.className = 'mt-1 text-sm text-yellow-600 field-warning-message';
        warningDiv.textContent = message;
        
        const container = field.closest('.form-group') || field.parentNode;
        container.appendChild(warningDiv);
    }

    clearFieldError(field) {
        const container = field.closest('.form-group') || field.parentNode;
        const existingError = container.querySelector('.field-error-message, .field-warning-message');
        if (existingError) {
            existingError.remove();
        }
    }

    showArchivoWarning(container, message) {
        const warningDiv = document.createElement('div');
        warningDiv.className = 'mt-2 text-sm text-yellow-600 archivo-warning-message';
        warningDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${message}`;
        
        container.appendChild(warningDiv);
    }

    showArchivoSuccess(container, message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'mt-2 text-sm text-green-600 archivo-success-message';
        successDiv.innerHTML = `<i class="fas fa-check mr-1"></i>${message}`;
        
        container.appendChild(successDiv);
    }

    clearArchivoMessages(container) {
        const existingWarning = container.querySelector('.archivo-warning-message');
        const existingSuccess = container.querySelector('.archivo-success-message');
        
        if (existingWarning) {
            existingWarning.remove();
        }
        if (existingSuccess) {
            existingSuccess.remove();
        }
    }

    updateFileName(input, fileName) {
        const nameElement = input.parentNode.querySelector('.file-name');
        if (nameElement) {
            nameElement.textContent = `Archivo seleccionado: ${fileName}`;
            nameElement.classList.remove('hidden');
        }
    }

    showValidationWarning() {
        console.log('CorreccionFormValidator: Mostrando advertencia de validación');
        
        // Mostrar mensaje de advertencia (no error)
        const warningMessage = document.createElement('div');
        warningMessage.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        warningMessage.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span>Advertencia: Algunas correcciones pueden estar incompletas, pero el envío continuará.</span>
            </div>
        `;

        document.body.appendChild(warningMessage);

        // Remover mensaje después de 5 segundos
        setTimeout(() => {
            if (warningMessage.parentNode) {
                warningMessage.parentNode.removeChild(warningMessage);
            }
        }, 5000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    if (window.modoCorreccion) {
        window.correccionValidator = new CorreccionFormValidator();
    }
});

// Función global para validación manual (siempre retorna true)
window.validateCorreccionForm = function() {
    console.log('CorreccionFormValidator: Validación manual (siempre permitir)');
    return true; // Siempre permitir envío
};
