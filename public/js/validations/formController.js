import FieldValidator from './validator.js';

class FormController {
    constructor(formSelector, fieldConfigs) {
        this.form = document.querySelector(formSelector);
        this.fieldConfigs = fieldConfigs;
        this.validators = new Map();
        this.isInitialized = false;
        
        if (this.form) {
            this.init();
        }
    }

    init() {
        if (this.isInitialized) return;
        
        this.setupValidators();
        this.setupEventListeners();
        this.isInitialized = true;
    }

    // Configurar validadores para cada campo
    setupValidators() {
        this.fieldConfigs.forEach(config => {
            const field = this.form.querySelector(config.selector);
            if (field) {
                const validator = new FieldValidator(field, config.rules);
                this.validators.set(field.name, validator);
            }
        });
    }

    // Configurar eventos de validación en tiempo real
    setupEventListeners() {
        this.validators.forEach((validator, fieldName) => {
            const field = validator.field;
            
            // Validar en tiempo real mientras escribe
            field.addEventListener('input', () => {
                validator.validate();
            });

            // Validar al perder el foco
            field.addEventListener('blur', () => {
                validator.validate();
            });

            // Limpiar error al empezar a escribir
            field.addEventListener('focus', () => {
                if (validator.isValid) {
                    validator.clearError();
                }
            });
        });

        // Validar todo el formulario antes de enviar
        this.form.addEventListener('submit', (e) => {
            console.log('FormController: Iniciando validación de envío del formulario');
            
            try {
                // Validar campos del formulario
                const allFieldsValid = this.validateAll();
                console.log('FormController: Validación de campos:', allFieldsValid);
                
                if (!allFieldsValid) {
                    console.log('FormController: Validación de campos falló, bloqueando envío');
                    e.preventDefault();
                    e.stopPropagation();
                    this.showAllErrors();
                    return false;
                }
                
                // Validar archivos antes de enviar
                const archivosValid = this.validateArchivosOnSubmit();
                console.log('FormController: Validación de archivos:', archivosValid);
                
                if (!archivosValid) {
                    console.log('FormController: Validación de archivos falló, bloqueando envío');
                    e.preventDefault();
                    e.stopPropagation();
                    this.showArchivosErrors();
                    return false;
                }
                
                console.log('FormController: Todas las validaciones pasaron, permitiendo envío');
                
            } catch (error) {
                console.error('FormController: Error durante validación:', error);
                e.preventDefault();
                e.stopPropagation();
                alert('Error durante la validación del formulario. Por favor, revise los datos ingresados.');
                return false;
            }
        });

        // Interceptar la función global navigateStep para validación
        this.interceptNavigateStep();
    }

    // Interceptar la función global navigateStep
    interceptNavigateStep() {
        const originalNavigateStep = window.navigateStep;
        
        window.navigateStep = (direction) => {
            // Solo validar en dirección 'next'
            if (direction === 'next') {
                // Validar el paso actual antes de avanzar
                if (!this.validateCurrentStep()) {
                    this.showCurrentStepErrors();
                    return false; // Bloquear navegación
                }
                
                // Obtener información del tipo de persona una sola vez
                const isPersonaMoral = document.querySelector('[name="tipo_persona"]')?.value === 'Moral' || 
                                     document.querySelector('[name="tipo_persona_hidden"]')?.value === 'Moral';
                const currentStep = this.getCurrentStep();
                
                // Validación específica para el paso de actividades (paso 1)
                if (currentStep === 1) {
                    const actividadesField = this.form.querySelector('[name="actividades_seleccionadas"]');
                    if (actividadesField) {
                        const actividadesValue = actividadesField.value;
                        try {
                            const actividades = JSON.parse(actividadesValue);
                            if (!Array.isArray(actividades) || actividades.length === 0) {
                                this.showCurrentStepErrors();
                                return false;
                            }
                        } catch (error) {
                            this.showCurrentStepErrors();
                            return false;
                        }
                    }
                }
                
                // Validación específica para el paso de accionistas (paso 4)
                if (currentStep === 4) {
                    const totalPorcentaje = window.calcularTotalPorcentajes ? window.calcularTotalPorcentajes() : 0;
                    if (totalPorcentaje !== 100) {
                        this.showCurrentStepErrors();
                        return false;
                    }
                }
                
                // Validación específica para el paso de documentos (paso 6 para personas morales, paso 3 para físicas)
                const documentosStep = isPersonaMoral ? 6 : 3;
                
                if (currentStep === documentosStep) {
                    const estadoArchivos = window.actualizarEstadoArchivos ? window.actualizarEstadoArchivos() : { completado: false };
                    if (!estadoArchivos.completado) {
                        this.showCurrentStepErrors();
                        return false;
                    }
                }
                
                // Validación específica para términos de servicio (solo en el último paso)
                const ultimoPaso = isPersonaMoral ? 7 : 4;
                
                if (currentStep === ultimoPaso) {
                    const terminosCheckbox = document.querySelector('[name="acepto_terminos"]');
                    if (terminosCheckbox && !terminosCheckbox.checked) {
                        this.showCurrentStepErrors();
                        return false;
                    }
                }
            }
            
            // Si es válido o es retroceso, continuar con la navegación original
            if (originalNavigateStep) {
                return originalNavigateStep(direction);
            }
            
            return true;
        };
    }

    // Validar todos los campos
    validateAll() {
        let allValid = true;
        this.validators.forEach(validator => {
            if (!validator.validate()) {
                allValid = false;
            }
        });
        return allValid;
    }

    // Validar solo campos del paso actual
    validateCurrentStep() {
        const currentStep = this.getCurrentStep();
        let allValid = true;
        let hasFieldsInStep = false;
        
        this.validators.forEach((validator, fieldName) => {
            const field = validator.field;
            
            if (this.isFieldInCurrentStep(field, currentStep)) {
                hasFieldsInStep = true;
                
                const fieldValid = validator.validate();
                
                if (!fieldValid) {
                    allValid = false;
                }
            }
        });
        
        // Si no hay campos en este paso, considerar válido
        if (!hasFieldsInStep) {
            return true;
        }
        
        return allValid;
    }

    // Obtener paso actual
    getCurrentStep() {
        const activeStep = document.querySelector('.step-content.active');
        return activeStep ? parseInt(activeStep.dataset.step) : 0;
    }

    // Verificar si un campo pertenece al paso actual
    isFieldInCurrentStep(field, currentStep) {
        const stepContent = field.closest('.step-content');
        if (!stepContent) {
            return false;
        }
        
        const fieldStep = parseInt(stepContent.dataset.step);
        const isInStep = fieldStep === currentStep;
        return isInStep;
    }

    // Mostrar todos los errores
    showAllErrors() {
        this.validators.forEach(validator => {
            validator.validate();
        });
        
        // Hacer scroll al primer error
        const firstError = this.form.querySelector('.border-red-500');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Mostrar errores del paso actual
    showCurrentStepErrors() {
        const currentStep = this.getCurrentStep();
        
        this.validators.forEach((validator, fieldName) => {
            const field = validator.field;
            if (this.isFieldInCurrentStep(field, currentStep)) {
                validator.validate();
            }
        });
        
        // Hacer scroll al primer error del paso actual
        const currentStepContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        const firstError = currentStepContent?.querySelector('.border-red-500');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Limpiar todos los errores
    clearAllErrors() {
        this.validators.forEach(validator => {
            validator.clearError();
        });
    }

    // Validar campo específico
    validateField(fieldName) {
        const validator = this.validators.get(fieldName);
        return validator ? validator.validate() : true;
    }

    // Obtener estado de validación
    getValidationState() {
        const state = {};
        this.validators.forEach((validator, fieldName) => {
            state[fieldName] = validator.isValid;
        });
        return state;
    }

    // Validar archivos al enviar el formulario
    validateArchivosOnSubmit() {
        try {
            console.log('FormController: Iniciando validación de archivos');
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let allValid = true;
            let archivosCargados = 0;
            let archivosRequeridos = 0;
            
            console.log(`FormController: Encontrados ${fileInputs.length} inputs de archivos`);
            
            fileInputs.forEach((input, index) => {
                // Solo contar archivos que realmente sean requeridos
                const isRequired = input.hasAttribute('required') || input.closest('.required-file');
                
                if (isRequired) {
                    archivosRequeridos++;
                    console.log(`FormController: Archivo ${index + 1} es requerido`);
                    
                    if (input.files && input.files.length > 0) {
                        archivosCargados++;
                        const file = input.files[0];
                        console.log(`FormController: Validando archivo: ${file.name}`);
                        
                        // Validar archivo individual
                        if (!this.validateArchivoIndividual(input, file)) {
                            allValid = false;
                            console.log(`FormController: Archivo ${file.name} no válido`);
                        }
                    } else {
                        // Archivo requerido no cargado
                        allValid = false;
                        console.log(`FormController: Archivo requerido ${index + 1} no cargado`);
                        this.marcarArchivoComoError(input, 'Este archivo es obligatorio');
                    }
                } else {
                    console.log(`FormController: Archivo ${index + 1} no es requerido`);
                }
            });
            
            console.log(`FormController: Archivos cargados: ${archivosCargados}/${archivosRequeridos}`);
            console.log(`FormController: Validación de archivos resultado: ${allValid}`);
            
            return allValid;
            
        } catch (error) {
            console.error('FormController: Error durante validación de archivos:', error);
            return true; // En caso de error, permitir el envío para que lo maneje el servidor
        }
    }

    // Validar archivo individual
    validateArchivoIndividual(fileInput, file) {
        const fileName = file.name;
        const fileSize = file.size;
        const fileExtension = fileName.split('.').pop().toLowerCase();
        const expectedType = fileInput.getAttribute('accept')?.replace('.', '') || '';
        
        // Validar tipo de archivo
        if (expectedType && fileExtension !== expectedType) {
            this.marcarArchivoComoError(fileInput, `Debe ser un archivo ${expectedType.toUpperCase()}`);
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
            this.marcarArchivoComoError(fileInput, `El archivo no puede ser mayor a ${maxSizeMB}MB`);
            return false;
        }
        
        // Archivo válido
        this.marcarArchivoComoValido(fileInput);
        return true;
    }

    // Marcar archivo como error
    marcarArchivoComoError(fileInput, message) {
        // Limpiar mensajes anteriores
        this.limpiarMensajesArchivo(fileInput);
        
        // Crear mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-600 text-sm mt-1 file-submit-error';
        errorDiv.textContent = message;
        
        const container = fileInput.closest('.bg-white') || fileInput.parentNode;
        container.appendChild(errorDiv);
        
        // Marcar el input como inválido
        fileInput.classList.add('border-red-500');
        
        // Marcar el contenedor como error
        const cardContainer = fileInput.closest('.bg-white');
        if (cardContainer) {
            cardContainer.classList.add('border-red-500');
            cardContainer.classList.remove('border-gray-300');
        }
    }

    // Marcar archivo como válido
    marcarArchivoComoValido(fileInput) {
        // Limpiar mensajes anteriores
        this.limpiarMensajesArchivo(fileInput);
        
        // Marcar el input como válido
        fileInput.classList.remove('border-red-500');
        
        // Marcar el contenedor como válido
        const cardContainer = fileInput.closest('.bg-white');
        if (cardContainer) {
            cardContainer.classList.remove('border-red-500');
            cardContainer.classList.add('border-gray-300');
        }
    }

    // Limpiar mensajes de archivo
    limpiarMensajesArchivo(fileInput) {
        const container = fileInput.closest('.bg-white') || fileInput.parentNode;
        const existingError = container.querySelector('.file-submit-error');
        if (existingError) {
            existingError.remove();
        }
    }

    // Mostrar errores de archivos
    showArchivosErrors() {
        // Hacer scroll al primer error de archivo
        const firstFileError = this.form.querySelector('.file-submit-error');
        if (firstFileError) {
            firstFileError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Mostrar mensaje general de error
        this.mostrarMensajeErrorArchivos();
    }

    // Mostrar mensaje general de error de archivos
    mostrarMensajeErrorArchivos() {
        // Crear o actualizar mensaje de error general
        let errorContainer = document.getElementById('archivos-error-general');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'archivos-error-general';
            errorContainer.className = 'mt-4 p-4 bg-red-50 border border-red-200 rounded-lg';
            
            // Insertar al inicio del contenedor de archivos
            const archivosContainer = document.querySelector('.grid');
            if (archivosContainer) {
                archivosContainer.parentNode.insertBefore(errorContainer, archivosContainer);
            }
        }
        
        errorContainer.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <p class="text-sm text-red-700">
                    <strong>Error en archivos:</strong> Por favor, corrija los errores en los archivos antes de continuar.
                </p>
            </div>
        `;
        
        // Remover mensaje después de 5 segundos
        setTimeout(() => {
            if (errorContainer.parentNode) {
                errorContainer.remove();
            }
        }, 5000);
    }
}

export default FormController; 