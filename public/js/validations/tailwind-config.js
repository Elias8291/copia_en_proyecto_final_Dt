// Configuración de clases Tailwind para el sistema de validación

export const TailwindClasses = {
    // Clases para campos con error
    fieldError: 'border-red-500 focus:border-red-500 focus:ring-red-500',
    
    // Clases para campos válidos
    fieldValid: 'border-green-500 focus:border-green-500 focus:ring-green-500',
    
    // Clases para mensajes de error
    errorMessage: 'text-red-600 text-sm mt-1',
    errorMessageHidden: 'text-red-600 text-sm mt-1 hidden',
    errorMessageVisible: 'text-red-600 text-sm mt-1',
    
    // Clases para botones deshabilitados
    buttonDisabled: 'opacity-50 cursor-not-allowed',
    
    // Clases para contenedores de campos
    fieldContainer: 'relative',
    
    // Clases para indicadores de validación
    validationIndicator: 'absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 rounded-full',
    validationIndicatorValid: 'bg-green-500',
    validationIndicatorInvalid: 'bg-red-500',
    
    // Clases para grupos de campos con error
    fieldGroupError: 'border-l-4 border-red-500 pl-4',
    
    // Clases para campos dinámicos
    dynamicField: 'border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50',
    dynamicFieldError: 'border-red-500 bg-red-50',
    
    // Clases para contadores de caracteres
    charCounter: 'text-xs text-gray-500 text-right mt-1',
    charCounterNearLimit: 'text-yellow-600',
    charCounterAtLimit: 'text-red-600',
    
    // Clases para campos requeridos
    requiredField: 'after:content-["_*"] after:text-red-500 after:font-bold',
    
    // Clases para campos opcionales
    optionalField: 'after:content-["_(opcional)"] after:text-gray-500 after:text-xs after:italic'
};

// Función para aplicar clases de error a un campo
export function applyErrorClasses(field) {
    field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
}

// Función para remover clases de error de un campo
export function removeErrorClasses(field) {
    field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
}

// Función para aplicar clases de éxito a un campo
export function applySuccessClasses(field) {
    field.classList.add('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
}

// Función para remover clases de éxito de un campo
export function removeSuccessClasses(field) {
    field.classList.remove('border-green-500', 'focus:border-green-500', 'focus:ring-green-500');
}

// Función para crear un mensaje de error con Tailwind
export function createErrorMessage(message) {
    const errorSpan = document.createElement('span');
    errorSpan.className = TailwindClasses.errorMessage;
    errorSpan.textContent = message;
    return errorSpan;
}

// Función para mostrar/ocultar mensaje de error
export function toggleErrorMessage(element, show, message = '') {
    if (show) {
        element.classList.remove('hidden');
        if (message) {
            element.textContent = message;
        }
    } else {
        element.classList.add('hidden');
    }
} 