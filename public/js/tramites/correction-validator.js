    
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
        if (!this.isCorrectionMode()) {
            return;
        }

        this.observeFormChanges();
        
        this.validateCorrections();
        
        this.interceptTermsValidation();
    }

    isCorrectionMode() {
        return window.modoCorreccion === true || document.querySelector('[data-correction-mode="true"]');
    }

    observeFormChanges() {
        const form = document.getElementById('tramite-form');
        if (!form) return;

        form.addEventListener('input', () => {
            setTimeout(() => this.validateCorrections(), 100);
        });

        form.addEventListener('change', () => {
            setTimeout(() => this.validateCorrections(), 100);
        });

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

        // No bloquear el envío por validaciones visuales: sólo exigir términos aceptados
        const allCorrectionsComplete = sectionsValid && filesValid;
        this.updateSubmitButton(termsAccepted);

        return allCorrectionsComplete;
    }

    validateRequiredSections() {
        const requiredSections = this.getRequiredSections();
        let allSectionsValid = true;

        for (const section of requiredSections) {
            if (!this.isSectionValid(section)) {
                allSectionsValid = false;
                break;
            }
        }

        return allSectionsValid;
    }

    getRequiredSections() {
        const sectionElements = document.querySelectorAll('[data-step]');
        const sections = [];

        sectionElements.forEach(element => {
            const stepData = element.dataset.step;
            if (stepData !== undefined) {
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

        return [...new Set(sections)];
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
        
        if (fileInput.files && fileInput.files.length > 0) {
            return true;
        }


        if (this.isCorrectionMode() && fileConfig.isRejected) {
            return false;
        }

        const existingFileIndicator = fileConfig.container.querySelector('[data-existing-file], .file-link, a[href*="storage"]');
        const rejectedIndicator = fileConfig.container.querySelector('.bg-red-100, .text-red-600') || 
                                 fileConfig.container.textContent.includes('Rechazado');
        
        if (existingFileIndicator && !rejectedIndicator) {
            return true;
        }
        
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
        const originalFunction = window.validarTerminosYCondicionesFinal;
        
        window.validarTerminosYCondicionesFinal = () => {
            if (originalFunction) {
                originalFunction();
            }
            
            this.validateCorrections();
        };

        const originalLocalFunction = window.validarTerminosYCondiciones;
        
        window.validarTerminosYCondiciones = () => {
            if (originalLocalFunction) {
                originalLocalFunction();
            }
            
            this.validateCorrections();
        };
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const correctionMode = document.querySelector('[data-correction-mode]') || 
                          window.modoCorreccion === true;
    
    if (correctionMode) {
        window.modoCorreccion = true;
    }
});

window.correctionValidator = new CorrectionValidator();
