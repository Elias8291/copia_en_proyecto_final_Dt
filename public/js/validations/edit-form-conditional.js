// Script para manejar validaciones condicionales en el formulario de edición
document.addEventListener('DOMContentLoaded', function() {
    console.log('EditFormConditional: Iniciando validaciones condicionales para formulario de edición');
    
    // Función para validar solo las secciones que están en estado "Rechazado"
    function validateOnlyRejectedSections() {
        const form = document.getElementById('tramite-form');
        if (!form) return;
        
        // Obtener todas las secciones del formulario
        const sections = document.querySelectorAll('[data-section]');
        let isValid = true;
        
        sections.forEach(section => {
            const sectionName = section.dataset.section;
            const sectionFields = section.querySelectorAll('input, select, textarea');
            
            // Solo validar campos de secciones rechazadas
            if (section.classList.contains('border-red-500')) {
                console.log(`EditFormConditional: Validando sección rechazada: ${sectionName}`);
                
                sectionFields.forEach(field => {
                    if (field.name && field.hasAttribute('required')) {
                        const value = field.value.trim();
                        if (!value) {
                            isValid = false;
                            showFieldError(field, 'Este campo es obligatorio para la corrección');
                        } else {
                            clearFieldError(field);
                        }
                    }
                });
            } else {
                // Para secciones no rechazadas, limpiar errores
                sectionFields.forEach(field => {
                    clearFieldError(field);
                });
            }
        });
        
        return isValid;
    }
    
    // Función para mostrar error en un campo
    function showFieldError(field, message) {
        // Remover error anterior
        clearFieldError(field);
        
        // Agregar clase de error
        field.classList.add('border-red-500', 'field-error');
        
        // Crear mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'mt-1 text-sm text-red-600 field-error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${message}`;
        
        // Insertar después del campo
        field.parentNode.appendChild(errorDiv);
    }
    
    // Función para limpiar error de un campo
    function clearFieldError(field) {
        field.classList.remove('border-red-500', 'field-error');
        
        const errorMessage = field.parentNode.querySelector('.field-error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }
    
    // Función para validar actividades específicamente
    function validateActividades() {
        const actividadesInput = document.getElementById('actividades-json');
        if (!actividadesInput) return true;
        
        const value = actividadesInput.value;
        if (!value || value.trim() === '') {
            showFieldError(actividadesInput, 'Debe seleccionar al menos una actividad económica');
            return false;
        }
        
        try {
            const actividades = JSON.parse(value);
            if (!Array.isArray(actividades) || actividades.length === 0) {
                showFieldError(actividadesInput, 'Debe seleccionar al menos una actividad económica');
                return false;
            }
            
            clearFieldError(actividadesInput);
            return true;
        } catch (error) {
            showFieldError(actividadesInput, 'Formato de actividades inválido');
            return false;
        }
    }
    
    // Función para validar archivos de corrección
    function validateArchivosCorreccion() {
        const archivosInputs = document.querySelectorAll('input[type="file"]');
        let isValid = true;
        
        archivosInputs.forEach(input => {
            const file = input.files[0];
            if (file) {
                // Validar tipo de archivo
                const fileName = file.name;
                const fileExtension = fileName.split('.').pop().toLowerCase();
                const expectedType = input.getAttribute('accept')?.replace('.', '') || '';
                
                if (expectedType && fileExtension !== expectedType) {
                    showFieldError(input, `El archivo debe ser de tipo ${expectedType.toUpperCase()}`);
                    isValid = false;
                } else {
                    clearFieldError(input);
                }
                
                // Validar tamaño (máximo 10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (file.size > maxSize) {
                    showFieldError(input, 'El archivo es demasiado grande. Máximo: 10MB');
                    isValid = false;
                }
            }
        });
        
        return isValid;
    }
    
    // Interceptar el envío del formulario
    const form = document.getElementById('tramite-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('EditFormConditional: Validando formulario antes del envío');
            
            let isValid = true;
            
            // Validar solo secciones rechazadas
            isValid = validateOnlyRejectedSections() && isValid;
            
            // Validar actividades si la sección está rechazada
            const actividadesSection = document.querySelector('[data-section="actividades"]');
            if (actividadesSection && actividadesSection.classList.contains('border-red-500')) {
                isValid = validateActividades() && isValid;
            }
            
            // Validar archivos de corrección
            isValid = validateArchivosCorreccion() && isValid;
            
            if (!isValid) {
                console.log('EditFormConditional: Validación falló, bloqueando envío');
                e.preventDefault();
                e.stopPropagation();
                
                // Mostrar mensaje general de error
                showGeneralError('Por favor, corrija los errores marcados antes de continuar');
                
                return false;
            }
            
            console.log('EditFormConditional: Validación exitosa, permitiendo envío');
            return true;
        });
    }
    
    // Función para mostrar error general
    function showGeneralError(message) {
        // Remover mensaje anterior
        const existingError = document.querySelector('.general-error-message');
        if (existingError) {
            existingError.remove();
        }
        
        // Crear mensaje de error general
        const errorDiv = document.createElement('div');
        errorDiv.className = 'general-error-message bg-red-50 border border-red-200 rounded-lg p-4 mb-6';
        errorDiv.innerHTML = `
            <div class="flex items-start">
                <svg class="w-4 h-4 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800 mb-1">Error de validación:</p>
                    <p class="text-sm text-red-700">${message}</p>
                </div>
            </div>
        `;
        
        // Insertar al inicio del formulario
        const formContent = document.querySelector('.p-6');
        if (formContent) {
            formContent.insertBefore(errorDiv, formContent.firstChild);
        }
        
        // Hacer scroll al error
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Configurar validación en tiempo real para campos editables
    function setupRealTimeValidation() {
        const editableFields = document.querySelectorAll('[data-section] input, [data-section] select, [data-section] textarea');
        
        editableFields.forEach(field => {
            if (field.name) {
                // Validar al perder el foco
                field.addEventListener('blur', function() {
                    const section = field.closest('[data-section]');
                    if (section && section.classList.contains('border-red-500')) {
                        validateField(field);
                    }
                });
                
                // Limpiar error al empezar a escribir
                field.addEventListener('focus', function() {
                    clearFieldError(field);
                });
            }
        });
    }
    
    // Función para validar un campo individual
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        
        // Validaciones específicas por tipo de campo
        if (field.hasAttribute('required') && !value) {
            showFieldError(field, 'Este campo es obligatorio');
            return false;
        }
        
        // Validar email
        if (fieldName.includes('correo') || fieldName.includes('email')) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (value && !emailRegex.test(value)) {
                showFieldError(field, 'Ingrese un correo electrónico válido');
                return false;
            }
        }
        
        // Validar RFC
        if (fieldName.includes('rfc')) {
            const rfcRegex = /^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
            if (value && !rfcRegex.test(value.toUpperCase())) {
                showFieldError(field, 'Ingrese un RFC válido');
                return false;
            }
        }
        
        // Validar teléfono
        if (fieldName.includes('telefono')) {
            const phoneRegex = /^[0-9\s\-\+\(\)]{10,}$/;
            if (value && !phoneRegex.test(value)) {
                showFieldError(field, 'Ingrese un número de teléfono válido');
                return false;
            }
        }
        
        // Validar código postal
        if (fieldName.includes('codigo_postal')) {
            const postalRegex = /^[0-9]{5}$/;
            if (value && !postalRegex.test(value)) {
                showFieldError(field, 'Ingrese un código postal válido (5 dígitos)');
                return false;
            }
        }
        
        clearFieldError(field);
        return true;
    }
    
    // Inicializar validación en tiempo real
    setupRealTimeValidation();
    
    // Hacer funciones disponibles globalmente
    window.validateOnlyRejectedSections = validateOnlyRejectedSections;
    window.validateActividades = validateActividades;
    window.validateArchivosCorreccion = validateArchivosCorreccion;
    window.showFieldError = showFieldError;
    window.clearFieldError = clearFieldError;
    
    console.log('EditFormConditional: Validaciones condicionales inicializadas');
});
