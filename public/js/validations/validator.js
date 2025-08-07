import ValidationRules from './rules.js';
import { applyErrorClasses, removeErrorClasses, toggleErrorMessage } from './tailwind-config.js';

class FieldValidator {
    constructor(field, rules) {
        this.field = field;
        this.rules = rules;
        this.errorContainer = this.createErrorContainer();
        this.isValid = true;
    }

    // Crear contenedor de error si no existe
    createErrorContainer() {
        let container = this.field.parentNode.querySelector('.error-message');
        if (!container) {
            container = document.createElement('div');
            container.className = 'error-message text-red-600 text-sm mt-1 hidden';
            // Buscar el contenedor correcto para insertar el mensaje
            const targetContainer = this.findErrorContainer();
            targetContainer.appendChild(container);
        }
        return container;
    }

    // Encontrar el contenedor correcto para el mensaje de error
    findErrorContainer() {
        // Buscar hacia arriba hasta encontrar un contenedor adecuado
        let container = this.field.parentNode;
        
        // Si el contenedor actual es adecuado, usarlo
        if (this.isErrorContainer(container)) {
            return container;
        }
        
        // Buscar hacia arriba
        while (container && container !== document.body) {
            if (this.isErrorContainer(container)) {
                return container;
            }
            container = container.parentNode;
        }
        
        // Fallback: buscar el contenedor más cercano que sea un div
        container = this.field.parentNode;
        while (container && container !== document.body) {
            if (container.tagName === 'DIV') {
                return container;
            }
            container = container.parentNode;
        }
        
        // Último fallback: usar el contenedor padre del campo
        return this.field.parentNode;
    }

    // Verificar si un contenedor es adecuado para el mensaje de error
    isErrorContainer(container) {
        // Debe ser un div
        if (container.tagName !== 'DIV') return false;
        
        // Debe tener clases específicas que indiquen que es un contenedor de campo
        const errorContainerClasses = [
            'form-group', 'field-container', 'mb-4', 'mb-6', 
            'col-span-1', 'col-span-2', 'grid', 'flex',
            'bg-white', 'p-6', 'overflow-hidden', 'shadow-xl',
            'step-content', 'space-y-6'
        ];
        
        return errorContainerClasses.some(className => container.classList.contains(className));
    }

    // Validar campo con todas sus reglas
    validate() {
        const value = this.field.value;
        let error = null;

        // Aplicar cada regla en orden
        for (const rule of this.rules) {
            if (typeof rule === 'string' && ValidationRules[rule]) {
                error = ValidationRules[rule](value);
            } else if (typeof rule === 'function') {
                error = rule(value);
            } else if (Array.isArray(rule) && ValidationRules[rule[0]]) {
                const ruleName = rule[0];
                const params = rule.slice(1);
                error = ValidationRules[ruleName](...params)(value);
            }
            
            if (error) break; // Parar en el primer error
        }

        this.showError(error);
        this.isValid = !error;
        return !error;
    }

    // Mostrar u ocultar error
    showError(message) {
        if (message) {
            toggleErrorMessage(this.errorContainer, true, message);
            applyErrorClasses(this.field);
        } else {
            toggleErrorMessage(this.errorContainer, false);
            removeErrorClasses(this.field);
        }
    }

    // Limpiar error
    clearError() {
        this.showError(null);
        this.isValid = true;
    }
}

export default FieldValidator; 