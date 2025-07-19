/**
 * Validador de Formularios
 * Maneja validaciones en tiempo real para formularios
 */
class FormValidator {
    constructor() {
        this.validationState = {
            email: { valid: null, checking: false },
            rfc: { valid: null, checking: false },
            passwordMatch: { valid: null, strongPassword: null }
        };
    }

    /** Validar email del lado del cliente */
    async validateEmail(email) {
        if (!email || email.length < 5) {
            this.clearValidationMessage('email');
            return;
        }

        // Validación básica del email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(email);
        
        this.validationState.email.valid = isValid;
        
        if (isValid) {
            this.showValidationMessage('email', 'Formato de email válido', true);
        } else {
            this.showValidationMessage('email', 'Formato de email inválido', false);
        }
    }

    /** Validar RFC del lado del cliente */
    async validateRfc(rfc) {
        if (!rfc || rfc.length < 10) {
            this.validationState.rfc.valid = null;
            return;
        }

        // Validación básica del RFC (formato general)
        const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9][A-Z1-9][0-9A]$/;
        const isValid = rfcRegex.test(rfc.toUpperCase()) && rfc.length >= 12 && rfc.length <= 13;
        
        this.validationState.rfc.valid = isValid;
        
        // No mostramos mensaje visual para RFC por ahora, solo guardamos el estado
        console.log(RFC ${rfc}: ${isValid ? 'válido' : 'inválido'});
    }

    /** Validar coincidencia de contraseñas */
    validatePasswordMatch() {
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        
        if (!password || !passwordConfirmation) return;

        const passwordValue = password.value;
        const confirmationValue = passwordConfirmation.value;
        
        if (passwordValue.length === 0 && confirmationValue.length === 0) {
            this.clearPasswordMatchValidation();
            return;
        }
        
        if (passwordValue.length === 0 || confirmationValue.length === 0) {
            this.clearPasswordMatchValidation();
            return;
        }
        
        const passwordsMatch = passwordValue === confirmationValue;
        const passwordStrong = this.validatePasswordStrength(passwordValue);
        
        this.showPasswordMatchValidation(passwordsMatch, passwordStrong);
    }

    /** Validar fortaleza de contraseña */
    validatePasswordStrength(password) {
        if (password.length < 8) return { valid: false, message: 'Mínimo 8 caracteres' };
        
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        const hasSpecialChar = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        
        const criteriaMet = [hasUpperCase, hasLowerCase, hasNumbers, hasSpecialChar].filter(Boolean).length;
        
        if (criteriaMet >= 3) {
            return { valid: true, message: 'Contraseña segura' };
        } else if (criteriaMet >= 2) {
            return { valid: true, message: 'Contraseña aceptable' };
        } else {
            return { valid: false, message: 'Contraseña débil' };
        }
    }

    /** Mostrar mensaje de validación */
    showValidationMessage(field, message, isValid) {
        if (field !== 'email') return;
        
        const validationDiv = document.getElementById(${field}Validation);
        const iconDiv = document.getElementById(${field}ValidationIcon);
        const inputField = document.getElementById(field);
        
        if (!validationDiv) return;

        validationDiv.innerHTML = 
            <div class="flex items-center space-x-1 text-xs">
                <svg class="w-3 h-3 ${isValid ? 'text-green-500' : 'text-red-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${isValid 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                    }
                </svg>
                <span class="${isValid ? 'text-green-600' : 'text-red-600'}">${message}</span>
            </div>
        ;
        
        validationDiv.style.opacity = '1';
        validationDiv.style.transform = 'translateY(0)';

        if (iconDiv) {
            iconDiv.innerHTML = 
                <svg class="w-4 h-4 ${isValid ? 'text-green-500' : 'text-red-500'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${isValid 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                    }
                </svg>
            ;
            iconDiv.classList.remove('hidden');
        }

        if (inputField) {
            inputField.classList.remove('border-red-300', 'border-green-300');
            inputField.classList.add(isValid ? 'border-green-300' : 'border-red-300');
        }
    }

    /** Mostrar loading de validación */
    showValidationLoading(field, show) {
        if (field !== 'email') return;
        
        const iconDiv = document.getElementById(${field}ValidationIcon);
        
        if (!iconDiv) return;

        if (show) {
            iconDiv.innerHTML = 
                <div class="w-4 h-4 border-2 border-gray-300 border-t-primary rounded-full animate-spin"></div>
            ;
            iconDiv.classList.remove('hidden');
        }
    }

    /** Limpiar mensaje de validación */
    clearValidationMessage(field) {
        if (field !== 'email') {
            if (field === 'rfc' && this.validationState[field]) {
                this.validationState[field].valid = null;
            }
            return;
        }
        
        const validationDiv = document.getElementById(${field}Validation);
        const iconDiv = document.getElementById(${field}ValidationIcon);
        const inputField = document.getElementById(field);
        
        if (validationDiv) {
            validationDiv.style.opacity = '0';
            validationDiv.style.transform = 'translateY(4px)';
        }
        
        if (iconDiv) {
            iconDiv.classList.add('hidden');
        }
        
        if (inputField) {
            inputField.classList.remove('border-red-300', 'border-green-300');
        }
        
        this.validationState[field].valid = null;
    }

    /** Mostrar validación de contraseñas */
    showPasswordMatchValidation(passwordsMatch, passwordStrength) {
        const validationDiv = document.getElementById('passwordMatchValidation');
        const iconDiv = document.getElementById('passwordMatchIcon');
        const passwordInput = document.getElementById('password');
        const confirmationInput = document.getElementById('password_confirmation');
        
        if (!validationDiv || !iconDiv) return;

        let message = '';
        let isValid = false;
        let iconColor = '';
        let borderColor = '';

        if (passwordsMatch && passwordStrength.valid) {
            message = ✅ Las contraseñas coinciden - ${passwordStrength.message};
            isValid = true;
            iconColor = 'text-green-500';
            borderColor = 'border-green-300';
        } else if (passwordsMatch && !passwordStrength.valid) {
            message = ⚠️ Las contraseñas coinciden - ${passwordStrength.message};
            isValid = false;
            iconColor = 'text-yellow-500';
            borderColor = 'border-yellow-300';
        } else {
            message = '❌ Las contraseñas no coinciden';
            isValid = false;
            iconColor = 'text-red-500';
            borderColor = 'border-red-300';
        }

        validationDiv.innerHTML = 
            <div class="flex items-center space-x-1 text-xs">
                <span class="${isValid && passwordsMatch ? 'text-green-600' : 'text-red-600'}">${message}</span>
            </div>
        ;
        
        validationDiv.style.opacity = '1';
        validationDiv.style.transform = 'translateY(0)';

        iconDiv.innerHTML = 
            <svg class="w-4 h-4 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${passwordsMatch 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
                }
            </svg>
        ;
        iconDiv.classList.remove('hidden');

        if (passwordInput && confirmationInput) {
            [passwordInput, confirmationInput].forEach(input => {
                input.classList.remove('border-red-300', 'border-green-300', 'border-yellow-300');
                input.classList.add(borderColor);
            });
        }

        this.validationState.passwordMatch = { 
            valid: passwordsMatch && passwordStrength.valid,
            strongPassword: passwordStrength.valid 
        };
    }

    /** Limpiar validación de contraseñas */
    clearPasswordMatchValidation() {
        const validationDiv = document.getElementById('passwordMatchValidation');
        const iconDiv = document.getElementById('passwordMatchIcon');
        const passwordInput = document.getElementById('password');
        const confirmationInput = document.getElementById('password_confirmation');
        
        if (validationDiv) {
            validationDiv.style.opacity = '0';
            validationDiv.style.transform = 'translateY(4px)';
        }
        
        if (iconDiv) {
            iconDiv.classList.add('hidden');
        }
        
        if (passwordInput && confirmationInput) {
            [passwordInput, confirmationInput].forEach(input => {
                input.classList.remove('border-red-300', 'border-green-300', 'border-yellow-300');
            });
        }
        
        this.validationState.passwordMatch = { valid: null, strongPassword: null };
    }
}

// Crear instancia global
window.formValidator = new FormValidator();

// Exponer validationState globalmente para compatibilidad
window.validationState = window.formValidator.validationState;  