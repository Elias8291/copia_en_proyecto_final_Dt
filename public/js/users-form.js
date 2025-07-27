// Users Form Handler
document.addEventListener('DOMContentLoaded', function() {
    // Convertir RFC a mayúsculas automáticamente
    const rfcInput = document.getElementById('rfc');
    if (rfcInput) {
        rfcInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Validación de contraseña en tiempo real
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    if (passwordInput && passwordConfirmationInput) {
        function validatePassword() {
            const password = passwordInput.value;
            const confirmation = passwordConfirmationInput.value;
            
            // Remover clases anteriores
            passwordInput.classList.remove('border-red-300', 'border-green-300');
            passwordConfirmationInput.classList.remove('border-red-300', 'border-green-300');
            
            // Validar longitud mínima
            if (password.length > 0 && password.length < 8) {
                passwordInput.classList.add('border-red-300');
                showPasswordError('La contraseña debe tener al menos 8 caracteres');
            } else if (password.length >= 8) {
                passwordInput.classList.add('border-green-300');
                hidePasswordError();
            }
            
            // Validar confirmación
            if (confirmation.length > 0 && password !== confirmation) {
                passwordConfirmationInput.classList.add('border-red-300');
                showPasswordConfirmationError('Las contraseñas no coinciden');
            } else if (confirmation.length > 0 && password === confirmation) {
                passwordConfirmationInput.classList.add('border-green-300');
                hidePasswordConfirmationError();
            }
        }
        
        passwordInput.addEventListener('input', validatePassword);
        passwordConfirmationInput.addEventListener('input', validatePassword);
    }

    // Mostrar/ocultar errores de contraseña
    function showPasswordError(message) {
        let errorElement = document.getElementById('password-error');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.id = 'password-error';
            errorElement.className = 'mt-1 text-sm text-red-600';
            passwordInput.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    function hidePasswordError() {
        const errorElement = document.getElementById('password-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    function showPasswordConfirmationError(message) {
        let errorElement = document.getElementById('password-confirmation-error');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.id = 'password-confirmation-error';
            errorElement.className = 'mt-1 text-sm text-red-600';
            passwordConfirmationInput.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    function hidePasswordConfirmationError() {
        const errorElement = document.getElementById('password-confirmation-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Validación de email en tiempo real
    const emailInput = document.getElementById('correo');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            this.classList.remove('border-red-300', 'border-green-300');
            
            if (email.length > 0 && !emailRegex.test(email)) {
                this.classList.add('border-red-300');
                showEmailError('El formato del correo electrónico no es válido');
            } else if (email.length > 0 && emailRegex.test(email)) {
                this.classList.add('border-green-300');
                hideEmailError();
            }
        });
    }

    function showEmailError(message) {
        let errorElement = document.getElementById('email-error');
        if (!errorElement) {
            errorElement = document.createElement('p');
            errorElement.id = 'email-error';
            errorElement.className = 'mt-1 text-sm text-red-600';
            emailInput.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    function hideEmailError() {
        const errorElement = document.getElementById('email-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    // Confirmación antes de eliminar
    const deleteButtons = document.querySelectorAll('button[type="submit"][onclick*="confirm"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    // Auto-hide de mensajes de éxito/error después de 5 segundos
    const successMessages = document.querySelectorAll('.bg-green-50');
    const errorMessages = document.querySelectorAll('.bg-red-50');
    
    function autoHideMessages(messages) {
        messages.forEach(message => {
            setTimeout(() => {
                if (message.parentElement) {
                    message.parentElement.remove();
                }
            }, 5000);
        });
    }
    
    autoHideMessages(successMessages);
    autoHideMessages(errorMessages);
}); 