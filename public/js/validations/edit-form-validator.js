import FormController from './formController.js';
import FieldValidator from './validator.js';

// Configuración de validaciones específica para el formulario de edición
const editFormConfig = [
    // ===== DATOS GENERALES (solo si está rechazado) =====
    {
        selector: '[name="razon_social"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="rfc"]',
        rules: ['required', 'rfc']
    },
    {
        selector: '[name="tipo_persona"]',
        rules: ['required']
    },
    {
        selector: '[name="curp"]',
        rules: ['curp']
    },
    {
        selector: '[name="pagina_web"]',
        rules: ['url', ['maxLength', 255]]
    },
    {
        selector: '[name="telefono"]',
        rules: ['required', 'phone', ['maxLength', 50]]
    },
    {
        selector: '[name="nombre_contacto"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="cargo"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="correo_contacto"]',
        rules: ['required', 'email', ['maxLength', 255]]
    },
    {
        selector: '[name="telefono_contacto"]',
        rules: ['required', 'phone', ['maxLength', 50]]
    },

    // ===== DOMICILIO (solo si está rechazado) =====
    {
        selector: '[name="calle"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="entre_calle"]',
        rules: ['required', ['maxLength', 255], (value) => {
            if (!value || value.trim() === '') {
                return 'El campo "Entre calle" es obligatorio';
            }
            return null;
        }]
    },
    {
        selector: '[name="y_calle"]',
        rules: ['required', ['maxLength', 255], (value) => {
            if (!value || value.trim() === '') {
                return 'El campo "Y calle" es obligatorio';
            }
            return null;
        }]
    },
    {
        selector: '[name="numero_exterior"]',
        rules: ['required', ['maxLength', 20]]
    },
    {
        selector: '[name="numero_interior"]',
        rules: [['maxLength', 20]]
    },
    {
        selector: '[name="colonia"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="codigo_postal"]',
        rules: ['required', 'postalCode']
    },
    {
        selector: '[name="municipio"]',
        rules: ['required', ['maxLength', 100]]
    },
    {
        selector: '[name="asentamiento"]',
        rules: ['required', ['maxLength', 100]]
    },
    {
        selector: '[name="estado_id"]',
        rules: ['required']
    },
    {
        selector: '[name="latitud"]',
        rules: ['required', (value) => {
            if (!value || value.trim() === '') {
                return 'La latitud es obligatoria';
            }
            const lat = parseFloat(value);
            if (isNaN(lat)) {
                return 'La latitud debe ser un número válido';
            }
            if (lat < -90 || lat > 90) {
                return 'La latitud debe estar entre -90 y 90';
            }
            return null;
        }]
    },
    {
        selector: '[name="longitud"]',
        rules: ['required', (value) => {
            if (!value || value.trim() === '') {
                return 'La longitud es obligatoria';
            }
            const lng = parseFloat(value);
            if (isNaN(lng)) {
                return 'La longitud debe ser un número válido';
            }
            if (lng < -180 || lng > 180) {
                return 'La longitud debe estar entre -180 y 180';
            }
            return null;
        }]
    },

    // ===== ACTIVIDADES (solo si está rechazado) =====
    {
        selector: '[name="actividades_seleccionadas"]',
        rules: ['required', (value) => {
            if (!value || value.trim() === '') {
                return 'Debe seleccionar al menos una actividad económica';
            }
            
            try {
                const actividades = JSON.parse(value);
                if (!Array.isArray(actividades) || actividades.length === 0) {
                    return 'Debe seleccionar al menos una actividad económica';
                }
                
                const actividadesValidas = actividades.filter(actividad => 
                    actividad && (actividad.nombre || actividad.id)
                );
                
                if (actividadesValidas.length === 0) {
                    return 'Debe seleccionar al menos una actividad económica válida';
                }
                
                return null;
            } catch (error) {
                const actividades = value.split(',').filter(item => item.trim() !== '');
                if (actividades.length === 0) {
                    return 'Debe seleccionar al menos una actividad económica';
                }
                return null;
            }
        }]
    },

    // ===== CONSTITUCIÓN (solo para Personas Morales, si está rechazado) =====
    {
        selector: '[name="estado_id_constitucion"]',
        rules: ['required']
    },
    {
        selector: '[name="numero_escritura_constitutiva"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="fecha_constitucion"]',
        rules: ['required', 'notFuture']
    },
    {
        selector: '[name="nombre_notario"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="numero_notario"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="numero_registro_publico"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="fecha_inscripcion"]',
        rules: ['required', 'notFuture', (value) => {
            const fechaConstitucion = document.querySelector('[name="fecha_constitucion"]')?.value;
            if (fechaConstitucion && value) {
                const fecha1 = new Date(value);
                const fecha2 = new Date(fechaConstitucion);
                return fecha1 >= fecha2 ? null : 'Debe ser posterior o igual a la fecha de constitución';
            }
            return null;
        }]
    },

    // ===== APODERADO (solo para Personas Morales, si está rechazado) =====
    {
        selector: '[name="nombre_apoderado"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="rfc_apoderado"]',
        rules: ['required', 'rfc']
    },
    {
        selector: '[name="numero_escritura_constitutiva_poder"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="numero_registro_publico_poder"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="fecha_inscripcion_poder"]',
        rules: ['required', 'notFuture']
    },
    {
        selector: '[name="nombre_notario_poder"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="numero_notario_poder"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="numero_escritura_poder"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="fecha_poder"]',
        rules: ['required', 'notFuture']
    },

    // ===== CAMPOS ADICIONALES =====
    {
        selector: '[name="tipo_tramite"]',
        rules: ['required']
    }
];

// Variable global para el controlador de formulario de edición
let editFormController;

// Inicializar validación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar validación para el formulario de edición
    editFormController = new FormController('#tramite-form', editFormConfig);
    
    // Hacer disponible globalmente para debugging
    window.editFormValidator = editFormController;
    
    // Configurar validación para campos dinámicos de accionistas
    setupEditAccionistasValidation();
    
    // Configurar validación para archivos de corrección
    setupEditArchivosValidation();
    
    console.log('EditFormValidator: Sistema de validación inicializado para formulario de edición');
});

// Configurar validación para campos dinámicos de accionistas en edición
function setupEditAccionistasValidation() {
    const accionistasContainer = document.querySelector('#accionistas-container');
    if (!accionistasContainer) return;

    // Observar cambios en el contenedor de accionistas
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                setupEditAccionistaFields();
            }
        });
    });

    observer.observe(accionistasContainer, { childList: true });
    setupEditAccionistaFields();
}

// Configurar validación para campos de accionistas en edición
function setupEditAccionistaFields() {
    const accionistaFields = document.querySelectorAll('[name^="accionistas"][name$="[nombre]"]');
    
    accionistaFields.forEach(field => {
        const row = field.closest('.accionista-row');
        if (row && !row.dataset.validatorSetup) {
            
            // Validar nombre
            const nombreField = row.querySelector('[name$="[nombre]"]');
            if (nombreField) {
                const validator = new FieldValidator(nombreField, ['required', ['maxLength', 255]]);
                setupFieldEvents(nombreField, validator);
            }
            
            // Validar RFC
            const rfcField = row.querySelector('[name$="[rfc]"]');
            if (rfcField) {
                const validator = new FieldValidator(rfcField, ['required', 'rfc']);
                setupFieldEvents(rfcField, validator);
            }
            
            // Validar porcentaje de participación
            const porcentajeField = row.querySelector('[name$="[porcentaje_participacion]"]');
            if (porcentajeField) {
                const validator = new FieldValidator(porcentajeField, ['required', 'percentage', (value) => {
                    const totalPorcentaje = calcularTotalPorcentajes();
                    if (totalPorcentaje !== 100) {
                        return `La suma total de porcentajes debe ser 100%. Actual: ${totalPorcentaje.toFixed(2)}%`;
                    }
                    return null;
                }]);
                setupFieldEvents(porcentajeField, validator);
            }
            
            row.dataset.validatorSetup = 'true';
        }
    });
}

// Configurar eventos para campos de validación
function setupFieldEvents(field, validator) {
    field.addEventListener('input', () => {
        validator.validate();
    });

    field.addEventListener('blur', () => {
        validator.validate();
    });

    field.addEventListener('focus', () => {
        if (validator.isValid) {
            validator.clearError();
        }
    });
}

// Configurar validación para archivos de corrección
function setupEditArchivosValidation() {
    const archivosInputs = document.querySelectorAll('input[type="file"]');
    
    archivosInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateEditArchivo(this);
        });
    });
}

// Validar archivo individual en edición
function validateEditArchivo(input) {
    const file = input.files[0];
    if (!file) return;

    const fileName = file.name;
    const fileSize = file.size;
    const fileExtension = fileName.split('.').pop().toLowerCase();
    const acceptAttribute = input.getAttribute('accept') || '';
    const expectedTypes = acceptAttribute.split(',').map(type => type.trim().replace('.', ''));
    
    // Validar tipo y tamaño
    const maxSizes = {
        'pdf': 10 * 1024 * 1024,
        'mp4': 50 * 1024 * 1024,
        'png': 5 * 1024 * 1024,
        'mp3': 10 * 1024 * 1024,
        'jpg': 5 * 1024 * 1024,
        'jpeg': 5 * 1024 * 1024
    };
    
    const maxSize = maxSizes[fileExtension] || 10 * 1024 * 1024;
    
    // Limpiar errores anteriores
    clearFileError(input);
    
    // Validar tipo de archivo
    if (expectedTypes.length > 0 && !expectedTypes.includes(fileExtension)) {
        const allowedTypes = expectedTypes.map(type => type.toUpperCase()).join(', ');
        showFileError(input, `El archivo debe ser de tipo ${allowedTypes}`);
        return false;
    }
    
    // Validar tamaño
    if (fileSize > maxSize) {
        showFileError(input, `El archivo es demasiado grande. Máximo: ${(maxSize / (1024 * 1024)).toFixed(1)}MB`);
        return false;
    }
    
    // Mostrar éxito
    showFileSuccess(input, 'Archivo válido');
    return true;
}

// Mostrar error en archivo
function showFileError(input, message) {
    const container = input.closest('.border-dashed');
    if (!container) return;
    
    // Remover mensajes anteriores
    clearFileError(input);
    
    // Agregar clase de error
    container.classList.add('border-red-500');
    container.classList.remove('border-blue-300', 'border-green-500');
    
    // Crear mensaje de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'mt-2 text-sm text-red-600';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-1"></i>${message}`;
    
    container.appendChild(errorDiv);
}

// Mostrar éxito en archivo
function showFileSuccess(input, message) {
    const container = input.closest('.border-dashed');
    if (!container) return;
    
    // Remover mensajes anteriores
    clearFileError(input);
    
    // Agregar clase de éxito
    container.classList.add('border-green-500');
    container.classList.remove('border-blue-300', 'border-red-500');
    
    // Crear mensaje de éxito
    const successDiv = document.createElement('div');
    successDiv.className = 'mt-2 text-sm text-green-600';
    successDiv.innerHTML = `<i class="fas fa-check mr-1"></i>${message}`;
    
    container.appendChild(successDiv);
}

// Limpiar error de archivo
function clearFileError(input) {
    const container = input.closest('.border-dashed');
    if (!container) return;
    
    // Remover mensajes de error/éxito
    const existingError = container.querySelector('.text-red-600');
    const existingSuccess = container.querySelector('.text-green-600');
    
    if (existingError) {
        existingError.remove();
    }
    if (existingSuccess) {
        existingSuccess.remove();
    }
    
    container.classList.remove('border-red-500', 'border-green-500');
}

// Calcular el total de porcentajes de participación de accionistas
function calcularTotalPorcentajes() {
    const porcentajeFields = document.querySelectorAll('[name$="[porcentaje_participacion]"]');
    let total = 0;
    
    porcentajeFields.forEach(field => {
        const value = parseFloat(field.value) || 0;
        total += value;
    });
    
    return total;
}

// Hacer funciones disponibles globalmente
window.calcularTotalPorcentajes = calcularTotalPorcentajes;
window.validateEditArchivo = validateEditArchivo;
