/**
 * Validador de correcciones para trámites
 * Verifica que todas las correcciones requeridas estén completas
 */

class CorrectionValidator {
    constructor() {
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.setupValidation();
        });
    }

    setupValidation() {
        // Solo activar en modo corrección
        if (!this.isCorrectionMode()) {
            return;
        }

        // Observar cambios en el formulario
        this.observeFormChanges();
        
        // Validar estado inicial
        this.validateCorrections();
        
        // Interceptar la función de validación de términos
        this.interceptTermsValidation();
    }

    isCorrectionMode() {
        return window.modoCorreccion === true || document.querySelector('[data-correction-mode="true"]');
    }

    observeFormChanges() {
        const form = document.getElementById('tramite-form');
        if (!form) return;

        // Observar cambios en inputs
        form.addEventListener('input', () => {
            setTimeout(() => this.validateCorrections(), 100);
        });

        // Observar cambios en selects
        form.addEventListener('change', () => {
            setTimeout(() => this.validateCorrections(), 100);
        });

        // Observar archivos subidos
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', () => {
                setTimeout(() => this.validateCorrections(), 200);
            });
        });
    }

    validateCorrections() {
        if (!this.isCorrectionMode()) {
            return true;
        }

        const sectionsValid = this.validateRequiredSections();
        const filesValid = this.validateRequiredFiles();
        const termsAccepted = this.areTermsAccepted();

        const allCorrectionsComplete = sectionsValid && filesValid;

        console.log('CorrectionValidator: Estado de validación', {
            sectionsValid,
            filesValid,
            termsAccepted,
            allCorrectionsComplete
        });

        // Actualizar estado del botón
        this.updateSubmitButton(allCorrectionsComplete && termsAccepted);

        return allCorrectionsComplete;
    }

    validateRequiredSections() {
        const requiredSections = this.getRequiredSections();
        let allSectionsValid = true;

        for (const section of requiredSections) {
            if (!this.isSectionValid(section)) {
                allSectionsValid = false;
                console.log(`CorrectionValidator: Sección ${section} incompleta`);
                break;
            }
        }

        return allSectionsValid;
    }

    getRequiredSections() {
        // Obtener secciones que requieren corrección desde el DOM
        const sectionElements = document.querySelectorAll('[data-step]');
        const sections = [];

        sectionElements.forEach(element => {
            const stepData = element.dataset.step;
            if (stepData !== undefined) {
                // Determinar el tipo de sección basado en el contenido
                if (element.querySelector('[name*="razon_social"], [name*="rfc"]')) {
                    sections.push('datos_generales');
                } else if (element.querySelector('[name*="actividades"]')) {
                    sections.push('actividades');
                } else if (element.querySelector('[name*="calle"], [name*="codigo_postal"]')) {
                    sections.push('domicilio');
                } else if (element.querySelector('[name*="numero_escritura"]')) {
                    sections.push('constitucion');
                } else if (element.querySelector('[name*="accionistas"]')) {
                    sections.push('accionistas');
                } else if (element.querySelector('[name*="nombre_apoderado"]')) {
                    sections.push('apoderado');
                } else if (element.querySelector('input[type="file"]')) {
                    sections.push('archivos');
                }
            }
        });

        return [...new Set(sections)]; // Remover duplicados
    }

    isSectionValid(section) {
        switch (section) {
            case 'datos_generales':
                return this.validateDatosGenerales();
            case 'actividades':
                return this.validateActividades();
            case 'domicilio':
                return this.validateDomicilio();
            case 'constitucion':
                return this.validateConstitucion();
            case 'accionistas':
                return this.validateAccionistas();
            case 'apoderado':
                return this.validateApoderado();
            case 'archivos':
                return this.validateArchivos();
            default:
                return true;
        }
    }

    validateDatosGenerales() {
        const razonSocial = document.getElementById('razon_social');
        const rfc = document.getElementById('rfc');
        
        return razonSocial?.value?.trim() && rfc?.value?.trim();
    }

    validateActividades() {
        const actividadesContainer = document.querySelector('[data-actividades-container]');
        if (!actividadesContainer) return true;

        const selectedActivities = actividadesContainer.querySelectorAll('input[name*="actividades"]:checked');
        return selectedActivities.length > 0;
    }

    validateDomicilio() {
        const calle = document.getElementById('calle');
        const codigoPostal = document.getElementById('codigo_postal');
        const numeroExterior = document.getElementById('numero_exterior');
        
        return calle?.value?.trim() && codigoPostal?.value?.trim() && numeroExterior?.value?.trim();
    }

    validateConstitucion() {
        const numeroEscritura = document.querySelector('[name="numero_escritura_constitutiva"]');
        const fechaConstitucion = document.querySelector('[name="fecha_constitucion"]');
        
        return numeroEscritura?.value?.trim() && fechaConstitucion?.value?.trim();
    }

    validateAccionistas() {
        const accionistasContainer = document.querySelector('[data-accionistas-container]');
        if (!accionistasContainer) return true;

        const accionistas = accionistasContainer.querySelectorAll('[data-accionista-row]');
        return accionistas.length > 0;
    }

    validateApoderado() {
        const nombreApoderado = document.querySelector('[name="nombre_apoderado"]');
        const rfcApoderado = document.querySelector('[name="rfc_apoderado"]');
        
        return nombreApoderado?.value?.trim() && rfcApoderado?.value?.trim();
    }

    validateRequiredFiles() {
        const requiredFiles = this.getRequiredFiles();
        let allFilesValid = true;

        for (const fileConfig of requiredFiles) {
            if (!this.isFileValid(fileConfig)) {
                allFilesValid = false;
                console.log(`CorrectionValidator: Archivo ${fileConfig.name} faltante o inválido`);
                break;
            }
        }

        return allFilesValid;
    }

    getRequiredFiles() {
        const fileContainers = document.querySelectorAll('[data-archivo-container] .archivo-item, .archivo-item, [data-archivo-item]');
        const requiredFiles = [];

        fileContainers.forEach(container => {
            const fileInput = container.querySelector('input[type="file"]');
            
            // En modo corrección, solo validar archivos que fueron rechazados
            if (this.isCorrectionMode()) {
                const statusIndicator = container.querySelector('.bg-red-100, .text-red-600, [data-status="Rechazado"]');
                const isRejected = statusIndicator || container.textContent.includes('Rechazado');
                
                if (fileInput && isRejected) {
                    requiredFiles.push({
                        input: fileInput,
                        name: fileInput.name,
                        container: container,
                        isRejected: true
                    });
                }
            } else {
                // Modo creación normal
                const isRequired = container.dataset.required === 'true' || 
                                 container.querySelector('.text-red-500') ||
                                 container.querySelector('[required]');
                
                if (fileInput && isRequired) {
                    requiredFiles.push({
                        input: fileInput,
                        name: fileInput.name,
                        container: container,
                        isRejected: false
                    });
                }
            }
        });

        return requiredFiles;
    }

    validateArchivos() {
        return this.validateRequiredFiles();
    }

    isFileValid(fileConfig) {
        const fileInput = fileConfig.input;
        
        // Verificar si hay archivo nuevo subido
        if (fileInput.files && fileInput.files.length > 0) {
            console.log(`CorrectionValidator: Archivo ${fileConfig.name} - nuevo archivo subido`);
            return true;
        }

        // En modo corrección, si es un archivo rechazado, DEBE tener nuevo archivo
        if (this.isCorrectionMode() && fileConfig.isRejected) {
            console.log(`CorrectionValidator: Archivo ${fileConfig.name} - rechazado, requiere nuevo archivo`);
            return false;
        }

        // Verificar si hay archivo existente válido (para archivos no rechazados)
        const existingFileIndicator = fileConfig.container.querySelector('[data-existing-file], .file-link, a[href*="storage"]');
        const rejectedIndicator = fileConfig.container.querySelector('.bg-red-100, .text-red-600') || 
                                 fileConfig.container.textContent.includes('Rechazado');
        
        // Si hay archivo existente y no está rechazado
        if (existingFileIndicator && !rejectedIndicator) {
            console.log(`CorrectionValidator: Archivo ${fileConfig.name} - archivo existente válido`);
            return true;
        }

        console.log(`CorrectionValidator: Archivo ${fileConfig.name} - no válido`, {
            hasNewFile: fileInput.files && fileInput.files.length > 0,
            hasExistingFile: !!existingFileIndicator,
            isRejected: rejectedIndicator,
            isCorrectionMode: this.isCorrectionMode()
        });
        
        return false;
    }

    areTermsAccepted() {
        const termsCheckbox = document.getElementById('acepto_terminos');
        return termsCheckbox ? termsCheckbox.checked : true;
    }

    updateSubmitButton(isValid) {
        const submitButton = document.getElementById('btn-enviar-tramite-final');
        if (!submitButton) return;

        if (isValid) {
            submitButton.disabled = false;
            submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitButton.classList.add('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
            
            // Actualizar texto si es modo corrección
            if (this.isCorrectionMode()) {
                const buttonText = submitButton.querySelector('svg').nextSibling;
                if (buttonText) {
                    buttonText.textContent = ' Enviar Correcciones';
                }
            }
        } else {
            submitButton.disabled = true;
            submitButton.classList.remove('bg-[#9d2449]', 'hover:bg-[#8a1f40]');
            submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        }
    }

    interceptTermsValidation() {
        // Interceptar la función global de validación de términos
        const originalFunction = window.validarTerminosYCondicionesFinal;
        
        window.validarTerminosYCondicionesFinal = () => {
            // Ejecutar validación original de términos
            if (originalFunction) {
                originalFunction();
            }
            
            // Ejecutar nuestra validación de correcciones
            this.validateCorrections();
        };

        // También interceptar la función local
        const originalLocalFunction = window.validarTerminosYCondiciones;
        
        window.validarTerminosYCondiciones = () => {
            if (originalLocalFunction) {
                originalLocalFunction();
            }
            
            this.validateCorrections();
        };
    }
}

// Configurar para modo corrección
document.addEventListener('DOMContentLoaded', function() {
    // Detectar si estamos en modo corrección
    const correctionMode = document.querySelector('[data-correction-mode]') || 
                          window.modoCorreccion === true;
    
    if (correctionMode) {
        window.modoCorreccion = true;
        console.log('CorrectionValidator: Modo corrección detectado');
    }
});

// Inicializar validador
window.correctionValidator = new CorrectionValidator();
