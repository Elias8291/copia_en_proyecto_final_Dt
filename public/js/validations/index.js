import FormController from './formController.js';
import FieldValidator from './validator.js';

// Configuración de validaciones para el formulario de trámites
const tramiteFormConfig = [
    // ===== DATOS GENERALES (DatosGeneralesService) =====
    {
        selector: '[name="razon_social"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="razon_social_hidden"]',
        rules: [['maxLength', 255]]
    },
    {
        selector: '[name="rfc"]',
        rules: ['required', 'rfc']
    },
    {
        selector: '[name="rfc_hidden"]',
        rules: ['rfc']
    },
    {
        selector: '[name="tipo_persona"]',
        rules: ['required']
    },
    {
        selector: '[name="tipo_persona_hidden"]',
        rules: []
    },
    {
        selector: '[name="curp"]',
        rules: ['curp']
    },
    {
        selector: '[name="curp_hidden"]',
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

    // ===== DOMICILIO (DomicilioService) =====
    {
        selector: '[name="calle"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="entre_calle"]',
        rules: ['required', ['maxLength', 255], (value) => {
            // Entre calle debe tener texto, no puede estar vacío
            if (!value || value.trim() === '') {
                return 'El campo "Entre calle" es obligatorio';
            }
            return null;
        }]
    },
    {
        selector: '[name="y_calle"]',
        rules: ['required', ['maxLength', 255], (value) => {
            // Y calle debe tener texto, no puede estar vacío
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
        // Número interior es opcional, puede quedar null
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
        selector: '[name="colonia"]',
        rules: ['required', ['maxLength', 255]]
    },
    {
        selector: '[name="estado_id"]',
        rules: ['required']
    },
    {
        selector: '[name="latitud"]',
        rules: ['required', (value) => {
            // Validar que sea un número válido entre -90 y 90
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
            // Validar que sea un número válido entre -180 y 180
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

    // ===== ACTIVIDADES (ActividadesService) =====
    {
        selector: '[name="actividades_seleccionadas"]',
        rules: ['required', (value) => {
            // Validar que haya al menos una actividad seleccionada
            if (!value || value.trim() === '') {
                return 'Debe seleccionar al menos una actividad económica';
            }
            
            try {
                // El valor viene como JSON string, intentar parsearlo
                const actividades = JSON.parse(value);
                if (!Array.isArray(actividades) || actividades.length === 0) {
                    return 'Debe seleccionar al menos una actividad económica';
                }
                
                // Verificar que cada actividad tenga al menos un nombre
                const actividadesValidas = actividades.filter(actividad => 
                    actividad && (actividad.nombre || actividad.id)
                );
                
                if (actividadesValidas.length === 0) {
                    return 'Debe seleccionar al menos una actividad económica válida';
                }
                
                return null;
            } catch (error) {
                // Si no es JSON válido, verificar si es un string con actividades
                const actividades = value.split(',').filter(item => item.trim() !== '');
                if (actividades.length === 0) {
                    return 'Debe seleccionar al menos una actividad económica';
                }
                return null;
            }
        }]
    },

    // ===== CONSTITUCIÓN (ConstitucionService) - Solo para Personas Morales =====
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

    // ===== APODERADO (ApoderadoService) - Solo para Personas Morales =====
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

    // ===== ARCHIVOS (ArchivosService) =====
    // Los archivos se validan dinámicamente según el catálogo
    // Se incluyen en la validación del formulario
    
    // ===== CAMPOS ADICIONALES =====
    {
        selector: '[name="tipo_tramite"]',
        rules: ['required']
    },
    {
        selector: '[name="tipo_tramite_seleccionado"]',
        rules: []
    },
    {
        selector: '[name="acepto_terminos"]',
        rules: ['required', (value) => {
            if (!value || value !== '1') {
                return 'Debe aceptar los términos de servicio para continuar';
            }
            return null;
        }]
    }
];

// Variable global para el controlador de formulario
let formController;

// Inicializar validación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar validación para el formulario de trámites (solo una vez)
    formController = new FormController('#tramite-form', tramiteFormConfig);
    
    // Hacer disponible globalmente para debugging
    window.tramiteFormValidator = formController;
    
    // Configurar validación para campos dinámicos de accionistas
    setupAccionistasValidation();
    
    // Configurar validación para archivos
    setupArchivosValidation();
    
    // Hacer disponible globalmente las funciones
    window.calcularTotalPorcentajes = calcularTotalPorcentajes;
    window.actualizarEstadoArchivos = actualizarEstadoArchivos;
    
    // Hacer disponible la validación de archivos al enviar
    window.validateArchivosOnSubmit = () => formController.validateArchivosOnSubmit();
});

// Configurar validación para campos dinámicos de accionistas
function setupAccionistasValidation() {
    const accionistasContainer = document.querySelector('#accionistas-container');
    if (!accionistasContainer) return;

    // Observar cambios en el contenedor de accionistas
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                setupAccionistaFields();
            }
        });
    });

    observer.observe(accionistasContainer, { childList: true });
    setupAccionistaFields();
}

// Configurar validación para campos de accionistas
function setupAccionistaFields() {
    const accionistaFields = document.querySelectorAll('[name^="accionistas"][name$="[nombre]"]');
    
    accionistaFields.forEach(field => {
        const row = field.closest('.accionista-row');
        if (row && !row.dataset.validatorSetup) {
            
            // Validar nombre (AccionistasService)
            const nombreField = row.querySelector('[name$="[nombre]"]');
            if (nombreField) {
                const validator = new FieldValidator(nombreField, ['required', ['maxLength', 255]]);
                setupFieldEvents(nombreField, validator);
            }
            
            // Validar RFC (AccionistasService)
            const rfcField = row.querySelector('[name$="[rfc]"]');
            if (rfcField) {
                const validator = new FieldValidator(rfcField, ['required', 'rfc']);
                setupFieldEvents(rfcField, validator);
            }
            
            // Validar porcentaje de participación (AccionistasService)
            const porcentajeField = row.querySelector('[name$="[porcentaje_participacion]"]');
            if (porcentajeField) {
                const validator = new FieldValidator(porcentajeField, ['required', 'percentage', (value) => {
                    // Validar que la suma total de porcentajes sea 100%
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

// Configurar eventos para campos dinámicos
function setupFieldEvents(field, validator) {
    field.addEventListener('input', () => validator.validate());
    field.addEventListener('blur', () => validator.validate());
    field.addEventListener('focus', () => {
        if (validator.isValid) {
            validator.clearError();
        }
    });
}

// Validar archivos según el catálogo dinámico
function setupArchivosValidation() {
    // Configurar validación para todos los inputs de archivo
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                validateArchivo(input, files[0]);
            }
            actualizarEstadoArchivos();
        });
    });
    
    // Observar cambios dinámicos en el contenedor de archivos
    const archivosContainer = document.querySelector('.grid');
    if (archivosContainer) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    const newFileInputs = archivosContainer.querySelectorAll('input[type="file"]');
                    newFileInputs.forEach(input => {
                        input.addEventListener('change', function(e) {
                            const files = e.target.files;
                            if (files && files.length > 0) {
                                validateArchivo(input, files[0]);
                            }
                            actualizarEstadoArchivos();
                        });
                    });
                }
            });
        });
        
        observer.observe(archivosContainer, { childList: true, subtree: true });
    }
    
    // Validación inicial
    actualizarEstadoArchivos();
}

// Validar archivo individual
function validateArchivo(fileInput, file) {
    const fileName = file.name;
    const fileSize = file.size;
    const fileExtension = fileName.split('.').pop().toLowerCase();
    
    // Limpiar errores anteriores
    clearFileError(fileInput);
    
    // Obtener el tipo de archivo esperado del input
    const expectedType = fileInput.getAttribute('accept')?.replace('.', '') || '';
    
    // Validar tipo de archivo
    if (expectedType && fileExtension !== expectedType) {
        showFileError(fileInput, `Debe ser un archivo ${expectedType.toUpperCase()}`);
        return false;
    }
    
    // Validar tamaño según el tipo de archivo
    const maxSizes = {
        'pdf': 10 * 1024 * 1024,    // 10MB para PDF
        'mp4': 50 * 1024 * 1024,    // 50MB para MP4
        'png': 5 * 1024 * 1024,     // 5MB para PNG
        'mp3': 10 * 1024 * 1024,    // 10MB para MP3
        'jpg': 5 * 1024 * 1024,     // 5MB para JPG
        'jpeg': 5 * 1024 * 1024     // 5MB para JPEG
    };
    
    const maxSize = maxSizes[fileExtension] || 10 * 1024 * 1024; // 10MB por defecto
    
    if (fileSize > maxSize) {
        const maxSizeMB = (maxSize / (1024 * 1024)).toFixed(0);
        showFileError(fileInput, `El archivo no puede ser mayor a ${maxSizeMB}MB`);
        return false;
    }
    
    // Mostrar éxito
    showFileSuccess(fileInput, 'Archivo válido');
    return true;
}

// Mostrar error en archivo
function showFileError(fileInput, message) {
    clearFileError(fileInput);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-red-600 text-sm mt-1 file-error-message';
    errorDiv.textContent = message;
    
    const container = fileInput.closest('.bg-white') || fileInput.parentNode;
    container.appendChild(errorDiv);
    
    // Marcar el input como inválido
    fileInput.classList.add('border-red-500');
}

// Mostrar éxito en archivo
function showFileSuccess(fileInput, message) {
    clearFileError(fileInput);
    
    const successDiv = document.createElement('div');
    successDiv.className = 'text-green-600 text-sm mt-1 file-success-message';
    successDiv.textContent = message;
    
    const container = fileInput.closest('.bg-white') || fileInput.parentNode;
    container.appendChild(successDiv);
    
    // Marcar el input como válido
    fileInput.classList.remove('border-red-500');
    fileInput.classList.add('border-green-500');
    
    // Remover mensaje de éxito después de 2 segundos
    setTimeout(() => {
        if (successDiv.parentNode) {
            successDiv.remove();
        }
        fileInput.classList.remove('border-green-500');
    }, 2000);
}

// Limpiar errores de archivo
function clearFileError(fileInput) {
    const container = fileInput.closest('.bg-white') || fileInput.parentNode;
    const existingError = container.querySelector('.file-error-message');
    const existingSuccess = container.querySelector('.file-success-message');
    
    if (existingError) {
        existingError.remove();
    }
    if (existingSuccess) {
        existingSuccess.remove();
    }
    
    fileInput.classList.remove('border-red-500', 'border-green-500');
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

// Actualizar estado general de archivos
function actualizarEstadoArchivos() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    let archivosCargados = 0;
    let archivosRequeridos = 0;
    let archivosValidos = 0;
    
    fileInputs.forEach(input => {
        archivosRequeridos++;
        
        if (input.files && input.files.length > 0) {
            archivosCargados++;
            
            // Verificar si el archivo es válido
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = file.size;
            const fileExtension = fileName.split('.').pop().toLowerCase();
            const expectedType = input.getAttribute('accept')?.replace('.', '') || '';
            
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
            
            if (fileExtension === expectedType && fileSize <= maxSize) {
                archivosValidos++;
            }
        }
    });
    
    // Actualizar indicador visual si existe
    const estadoContainer = document.getElementById('estado-archivos');
    const progresoElement = document.getElementById('archivos-progreso');
    
    if (estadoContainer && progresoElement) {
        progresoElement.textContent = `${archivosCargados} de ${archivosRequeridos}`;
        
        if (archivosCargados === archivosRequeridos && archivosValidos === archivosRequeridos) {
            estadoContainer.className = 'mt-4 p-3 bg-green-50 border border-green-200 rounded-lg';
            const estadoText = estadoContainer.querySelector('p');
            if (estadoText) {
                estadoText.innerHTML = '<strong>✅ Completado:</strong> Todos los archivos obligatorios han sido cargados correctamente.';
            }
        } else if (archivosCargados === archivosRequeridos) {
            estadoContainer.className = 'mt-4 p-3 bg-orange-50 border border-orange-200 rounded-lg';
            const estadoText = estadoContainer.querySelector('p');
            if (estadoText) {
                estadoText.innerHTML = `<strong>⚠️ Atención:</strong> ${archivosCargados} de ${archivosRequeridos} archivos cargados, pero algunos tienen errores.`;
            }
        } else {
            estadoContainer.className = 'mt-4 p-3 bg-red-50 border border-red-200 rounded-lg';
            const estadoText = estadoContainer.querySelector('p');
            if (estadoText) {
                estadoText.innerHTML = `<strong>❌ Pendiente:</strong> ${archivosCargados} de ${archivosRequeridos} archivos obligatorios cargados.`;
            }
        }
    }
    
    return {
        cargados: archivosCargados,
        requeridos: archivosRequeridos,
        validos: archivosValidos,
        completado: archivosCargados === archivosRequeridos && archivosValidos === archivosRequeridos
    };
}

// Hacer funciones disponibles globalmente
window.calcularTotalPorcentajes = calcularTotalPorcentajes;
window.actualizarEstadoArchivos = actualizarEstadoArchivos; 