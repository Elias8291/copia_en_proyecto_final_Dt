/**
 * Validaciones condicionales para formulario de edición
 * Se aplican solo a secciones rechazadas en modo corrección
 */

document.addEventListener('DOMContentLoaded', function() {
    // Solo activar en modo corrección
    if (!window.modoCorreccion) {
        return;
    }

    console.log('EditFormConditional: Iniciando validación condicional para modo corrección');

    // Obtener secciones rechazadas
    const seccionesRechazadas = document.querySelectorAll('[data-section]');
    
    seccionesRechazadas.forEach(section => {
        const sectionName = section.dataset.section;
        setupSectionValidation(section, sectionName);
    });

    // Configurar validación del formulario (sin bloquear envío)
    setupFormValidation();
    
    // Configurar validación de archivos de corrección (sin bloquear envío)
    setupArchivosCorreccionValidation();
});

function setupSectionValidation(section, sectionName) {
    console.log(`EditFormConditional: Configurando validación para sección: ${sectionName}`);
    
    // Validar campos requeridos en la sección (solo visual)
    const requiredFields = section.querySelectorAll('[required], .required');
    
    requiredFields.forEach(field => {
        field.addEventListener('input', () => validateSection(section, sectionName));
        field.addEventListener('change', () => validateSection(section, sectionName));
        field.addEventListener('blur', () => validateSection(section, sectionName));
    });

    // Validar archivos en la sección (solo visual)
    const fileInputs = section.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', () => validateSection(section, sectionName));
    });

    // Validación inicial
    validateSection(section, sectionName);
}

function validateSection(section, sectionName) {
    let isValid = true;
    const errors = [];

    console.log(`EditFormConditional: Validando sección: ${sectionName}`);

    // Validar campos requeridos (solo visual)
    const requiredFields = section.querySelectorAll('[required], .required');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            errors.push(`${field.name || field.id} es requerido`);
            field.classList.add('border-red-500');
            console.log(`EditFormConditional: Campo requerido vacío: ${field.name || field.id}`);
        } else {
            field.classList.remove('border-red-500');
        }
    });

    // Validar archivos requeridos (solo visual)
    const fileInputs = section.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        const isRejected = input.closest('.archivo-item')?.querySelector('.bg-red-100, .text-red-600');
        if (isRejected && (!input.files || input.files.length === 0)) {
            isValid = false;
            errors.push('Archivo rechazado requiere nuevo documento');
            input.classList.add('border-red-500');
            console.log(`EditFormConditional: Archivo rechazado sin corrección: ${input.name}`);
        } else {
            input.classList.remove('border-red-500');
        }
    });

    // Actualizar indicador visual de la sección
    updateSectionIndicator(section, isValid, errors);

    console.log(`EditFormConditional: Sección ${sectionName} - Válida: ${isValid}, Errores: ${errors.length}`);

    return isValid;
}

function updateSectionIndicator(section, isValid, errors) {
    const indicator = section.querySelector('.bg-red-50');
    if (!indicator) return;

    if (isValid) {
        indicator.classList.remove('bg-red-50', 'border-red-200');
        indicator.classList.add('bg-green-50', 'border-green-200');
        
        const badge = indicator.querySelector('.bg-red-600');
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

function setupFormValidation() {
    const form = document.getElementById('tramite-form');
    if (!form) return;

    console.log('EditFormConditional: Configurando validación del formulario (sin bloquear envío)');

    // Solo mostrar advertencias, no bloquear envío
    form.addEventListener('submit', function(e) {
        console.log('EditFormConditional: Validando formulario antes del envío (solo advertencias)');
        
        const isValid = validateAllSections();
        const archivosValid = validateArchivosCorreccion();
        
        console.log(`EditFormConditional: Validación completa - Secciones: ${isValid}, Archivos: ${archivosValid}`);
        
        // Solo mostrar advertencia si hay errores, pero permitir envío
        if (!isValid || !archivosValid) {
            showValidationWarning();
        }
        
        console.log('EditFormConditional: Formulario permitiendo envío (con o sin errores)');
        return true; // Siempre permitir envío
    });
}

function validateAllSections() {
    const seccionesRechazadas = document.querySelectorAll('[data-section]');
    let allValid = true;

    seccionesRechazadas.forEach(section => {
        const sectionName = section.dataset.section;
        if (!validateSection(section, sectionName)) {
            allValid = false;
        }
    });

    return allValid;
}

function setupArchivosCorreccionValidation() {
    console.log('EditFormConditional: Configurando validación de archivos de corrección (solo visual)');
    
    const archivosCorreccion = document.querySelectorAll('input[name^="documentos_correccion"]');
    archivosCorreccion.forEach(input => {
        input.addEventListener('change', function() {
            validateArchivoCorreccion(this);
        });
    });
}

function validateArchivoCorreccion(input) {
    const file = input.files[0];
    const container = input.closest('.border-dashed');
    
    if (!container) return true;
    
    // Limpiar mensajes anteriores
    clearArchivoCorreccionMessages(container);
    
    if (!file) {
        showArchivoCorreccionWarning(container, 'Se recomienda subir un archivo para corregir el documento rechazado');
        return false;
    }
    
    // Validar tipo de archivo
    const allowedTypes = ['pdf', 'png', 'jpg', 'jpeg'];
    const fileExtension = file.name.split('.').pop().toLowerCase();
    
    if (!allowedTypes.includes(fileExtension)) {
        showArchivoCorreccionWarning(container, 'Se recomienda usar archivos de tipo: PDF, PNG, JPG, JPEG');
        return false;
    }
    
    // Validar tamaño (10MB máximo)
    const maxSize = 10 * 1024 * 1024;
    if (file.size > maxSize) {
        showArchivoCorreccionWarning(container, 'Se recomienda archivos menores a 10MB');
        return false;
    }
    
    // Archivo válido
    showArchivoCorreccionSuccess(container, 'Archivo válido');
    return true;
}

function validateArchivosCorreccion() {
    console.log('EditFormConditional: Validando archivos de corrección (solo visual)');
    
    const archivosRechazados = document.querySelectorAll('.archivo-item .bg-red-100, .archivo-item .text-red-600');
    let allValid = true;
    
    archivosRechazados.forEach(archivoRechazado => {
        const container = archivoRechazado.closest('.archivo-item');
        const fileInput = container?.querySelector('input[type="file"]');
        
        if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
            allValid = false;
            showArchivoCorreccionWarning(container, 'Se recomienda subir un archivo corregido para reemplazar el documento rechazado');
            console.log(`EditFormConditional: Archivo rechazado sin corrección: ${fileInput.name}`);
        }
    });
    
    console.log(`EditFormConditional: Validación de archivos de corrección - Válida: ${allValid}`);
    return allValid;
}

function showArchivoCorreccionWarning(container, message) {
    if (!container) return;
    
    const warningDiv = document.createElement('div');
    warningDiv.className = 'mt-2 text-sm text-yellow-600 archivo-correccion-warning';
    warningDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${message}`;
    
    container.appendChild(warningDiv);
    
    container.classList.add('border-yellow-500');
    container.classList.remove('border-green-500', 'border-red-500', 'border-gray-300');
}

function showArchivoCorreccionSuccess(container, message) {
    if (!container) return;
    
    const successDiv = document.createElement('div');
    successDiv.className = 'mt-2 text-sm text-green-600 archivo-correccion-success';
    successDiv.innerHTML = `<i class="fas fa-check mr-1"></i>${message}`;
    
    container.appendChild(successDiv);
    
    container.classList.add('border-green-500');
    container.classList.remove('border-yellow-500', 'border-red-500', 'border-gray-300');
}

function clearArchivoCorreccionMessages(container) {
    if (!container) return;
    
    const existingWarning = container.querySelector('.archivo-correccion-warning');
    const existingSuccess = container.querySelector('.archivo-correccion-success');
    
    if (existingWarning) {
        existingWarning.remove();
    }
    if (existingSuccess) {
        existingSuccess.remove();
    }
}

function showValidationWarning() {
    console.log('EditFormConditional: Mostrando advertencia de validación');
    
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

// Función global para validar antes del envío (siempre retorna true)
window.validateFormBeforeSubmit = function() {
    console.log('EditFormConditional: Validación global antes del envío (siempre permitir)');
    return true; // Siempre permitir envío
};

// Función global para validar archivo de corrección
window.validateArchivoCorreccion = validateArchivoCorreccion;
