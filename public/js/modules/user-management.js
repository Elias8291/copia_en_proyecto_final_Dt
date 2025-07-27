/**
 * Módulo de Gestión de Usuarios
 * Funcionalidades para el sistema CRUD de usuarios
 */
class UserManagement {
    constructor() {
        this.initializeEventListeners();
        this.initializeFormValidation();
        this.initializeRFCValidation();
        this.initializePasswordConfirmation();
    }

    /**
     * Inicializar event listeners
     */
    initializeEventListeners() {
        // Auto-convertir RFC a mayúsculas
        const rfcInputs = document.querySelectorAll('input[name="rfc"]');
        rfcInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.toUpperCase();
            });
        });

        // Confirmación de eliminación
        const deleteForms = document.querySelectorAll('form[action*="/users/"][method="DELETE"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!confirm('¿Está seguro de que desea eliminar este usuario?')) {
                    e.preventDefault();
                }
            });
        });

        // Mostrar/ocultar mensajes de éxito/error
        this.initializeFlashMessages();
    }

    /**
     * Inicializar validación de formularios
     */
    initializeFormValidation() {
        const forms = document.querySelectorAll('form[action*="/users"]');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Inicializar validación de RFC
     */
    initializeRFCValidation() {
        const rfcInputs = document.querySelectorAll('input[name="rfc"]');
        rfcInputs.forEach(input => {
            input.addEventListener('blur', (e) => {
                this.validateRFC(e.target);
            });
        });
    }

    /**
     * Inicializar confirmación de contraseña
     */
    initializePasswordConfirmation() {
        const passwordInputs = document.querySelectorAll('input[name="password"]');
        const confirmationInputs = document.querySelectorAll('input[name="password_confirmation"]');

        passwordInputs.forEach((input, index) => {
            const confirmationInput = confirmationInputs[index];
            if (confirmationInput) {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length > 0) {
                        confirmationInput.parentNode.style.display = 'block';
                    } else {
                        confirmationInput.parentNode.style.display = 'none';
                        confirmationInput.value = '';
                    }
                });
            }
        });
    }

    /**
     * Inicializar mensajes flash
     */
    initializeFlashMessages() {
        // Auto-ocultar mensajes de éxito después de 5 segundos
        const successMessages = document.querySelectorAll('.alert-success, .bg-green-50');
        successMessages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s ease-out';
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 500);
            }, 5000);
        });

        // Auto-ocultar mensajes de error después de 8 segundos
        const errorMessages = document.querySelectorAll('.alert-danger, .bg-red-50');
        errorMessages.forEach(message => {
            setTimeout(() => {
                message.style.transition = 'opacity 0.5s ease-out';
                message.style.opacity = '0';
                setTimeout(() => {
                    message.remove();
                }, 500);
            }, 8000);
        });
    }

    /**
     * Validar formulario
     */
    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'Este campo es obligatorio');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        // Validar email
        const emailField = form.querySelector('input[type="email"]');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                this.showFieldError(emailField, 'El formato del email no es válido');
                isValid = false;
            }
        }

        // Validar contraseña
        const passwordField = form.querySelector('input[name="password"]');
        const confirmField = form.querySelector('input[name="password_confirmation"]');
        
        if (passwordField && passwordField.value) {
            if (passwordField.value.length < 8) {
                this.showFieldError(passwordField, 'La contraseña debe tener al menos 8 caracteres');
                isValid = false;
            }
            
            if (confirmField && passwordField.value !== confirmField.value) {
                this.showFieldError(confirmField, 'Las contraseñas no coinciden');
                isValid = false;
            }
        }

        return isValid;
    }

    /**
     * Validar RFC
     */
    validateRFC(input) {
        const rfc = input.value.trim();
        const rfcRegex = /^[A-Z&Ñ]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
        
        if (rfc && !rfcRegex.test(rfc)) {
            this.showFieldError(input, 'El formato del RFC no es válido');
            return false;
        } else {
            this.clearFieldError(input);
            return true;
        }
    }

    /**
     * Mostrar error de campo
     */
    showFieldError(field, message) {
        field.classList.add('border-red-300');
        
        // Remover mensaje de error existente
        const existingError = field.parentNode.querySelector('.text-red-600');
        if (existingError) {
            existingError.remove();
        }
        
        // Crear nuevo mensaje de error
        const errorElement = document.createElement('p');
        errorElement.className = 'mt-1 text-sm text-red-600';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
    }

    /**
     * Limpiar error de campo
     */
    clearFieldError(field) {
        field.classList.remove('border-red-300');
        const errorElement = field.parentNode.querySelector('.text-red-600');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Mostrar modal de confirmación
     */
    showConfirmationModal(message, callback) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
        modal.innerHTML = `
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Acción</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">${message}</p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="confirmBtn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Confirmar
                        </button>
                        <button id="cancelBtn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        document.getElementById('confirmBtn').addEventListener('click', () => {
            callback(true);
            document.body.removeChild(modal);
        });
        
        document.getElementById('cancelBtn').addEventListener('click', () => {
            callback(false);
            document.body.removeChild(modal);
        });
    }

    /**
     * Mostrar notificación
     */
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const iconColor = type === 'success' ? 'text-green-600' : 'text-red-600';
        
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${bgColor} text-white transform transition-all duration-300 translate-x-full`;
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' ? 
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' :
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                    }
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-remover después de 3 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.userManagement = new UserManagement();
});

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UserManagement;
} 