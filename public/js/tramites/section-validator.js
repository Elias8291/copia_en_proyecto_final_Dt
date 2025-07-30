/**
 * SectionValidator - Validador de secciones de formularios
 */

class SectionValidator {
    constructor() {
        this.validator = new FormValidator();
        this.currentStep = 1;
        this.totalSteps = 0;
        this.sections = {};
        this.init();
    }

    init() {
        this.setupSections();
        this.setupEventListeners();
        this.updateProgress();
        // this.debugSections(); // Debug temporal - comentado
    }

    setupSections() {
        const sections = document.querySelectorAll('.form-section, .step-section');
        this.totalSteps = sections.length;

        sections.forEach((section, index) => {
            const step = index + 1;
            this.sections[step] = {
                element: section,
                isValid: false,
                fields: this.getFieldsInSection(section),
                type: this.getSectionType(section)
            };
        });
    }

    getSectionType(section) {
        const sectionId = section.id || '';
        const sectionClasses = section.className || '';
        
        const types = ['datos-generales', 'direccion', 'actividades', 'documentos', 'apoderado', 'accionistas', 'datos-constitutivos'];
        
        for (const type of types) {
            if (sectionId.includes(type) || sectionClasses.includes(type)) {
                return type;
            }
        }
        
        return 'general';
    }

    getFieldsInSection(section) {
        const fields = [];
        const inputs = section.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.name && !input.disabled) {
                const dataValidate = input.getAttribute('data-validate');
                const isRequired = input.hasAttribute('required') || 
                                 (dataValidate && dataValidate.includes('required'));
                
                fields.push({
                    element: input,
                    rules: this.validator.parseValidationRules(dataValidate),
                    required: isRequired
                });
            }
        });

        return fields;
    }

    setupEventListeners() {
        this.setupFieldListeners();
        this.setupNavigationListeners();
        this.setupDynamicElements();
    }

    setupFieldListeners() {
        Object.values(this.sections).forEach(section => {
            section.fields.forEach(field => {
                ['blur', 'input'].forEach(eventType => {
                    field.element.addEventListener(eventType, () => {
                        if (this.shouldValidate()) {
                            this.validateField(field);
                            this.updateSectionValidity(section);
                        }
                    });
                });

                if (field.element.type === 'date' || field.element.type === 'file') {
                    field.element.addEventListener('change', () => {
                        if (this.shouldValidate()) {
                            this.validateField(field);
                            this.updateSectionValidity(section);
                        }
                    });
                }
            });
        });
    }

    shouldValidate() {
        return !(this.currentStep === 1);
    }

    setupNavigationListeners() {
        const buttons = {
            'btn-siguiente': () => this.nextStep(),
            'btn-anterior': () => this.previousStep(),
            'btn-enviar': (e) => this.validateAndSubmit(e)
        };

        Object.entries(buttons).forEach(([id, handler]) => {
            const button = document.getElementById(id);
            if (button) button.addEventListener('click', handler);
        });
    }

    setupDynamicElements() {
        const observer = new MutationObserver(() => {
            this.revalidateDynamicSections();
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }

    revalidateDynamicSections() {
        const dynamicTypes = ['actividades', 'accionistas', 'documentos'];
        Object.values(this.sections).forEach(section => {
            if (dynamicTypes.includes(section.type)) {
                this.updateSectionValidity(section);
            }
        });
    }

    validateField(field) {
        // Si el campo está deshabilitado, no validar
        if (field.element.disabled) {
            console.log(`Campo ${field.element.name} está deshabilitado, omitiendo validación`);
            return true;
        }
        
        const isValid = this.validator.validateField(field.element, field.rules);
        field.isValid = isValid;
        
        if (!this.shouldValidate()) {
            this.validator.hideError(field.element);
        }
        
        return isValid;
    }

    updateSectionValidity(section) {
        let allFieldsValid = true;
        
        section.fields.forEach(field => {
            if (field.required) {
                const isValid = this.validateField(field);
                if (!isValid) allFieldsValid = false;
            }
        });

        if (['actividades', 'accionistas', 'documentos'].includes(section.type)) {
            allFieldsValid = allFieldsValid && this.validator.validateSection(section.type, section.element);
        }

        section.isValid = allFieldsValid;
        
        if (this.shouldValidate()) {
            this.updateSectionIndicator(section);
        }
    }

    updateSectionIndicator(section) {
        const sectionElement = section.element;
        const stepNumber = parseInt(sectionElement.getAttribute('data-step')) || 1;
        
        if (stepNumber === 1 && this.currentStep === 1) {
            sectionElement.classList.remove('border-green-200', 'bg-green-50', 'border-red-200', 'bg-red-50');
            return;
        }
        
        if (stepNumber <= this.currentStep || section.isValid !== undefined) {
            const classes = section.isValid 
                ? ['border-green-200', 'bg-green-50'] 
                : ['border-red-200', 'bg-red-50'];
            
            sectionElement.classList.remove('border-green-200', 'bg-green-50', 'border-red-200', 'bg-red-50');
            sectionElement.classList.add(...classes);
        } else {
            sectionElement.classList.remove('border-green-200', 'bg-green-50', 'border-red-200', 'bg-red-50');
        }
    }

    /**
     * Método de debug para verificar el estado de las secciones
     */
    debugSections() {
        console.log('=== DEBUG SECTIONS ===');
        console.log('Total steps:', this.totalSteps);
        console.log('Current step:', this.currentStep);
        
        Object.entries(this.sections).forEach(([step, section]) => {
            console.log(`Step ${step}:`, {
                type: section.type,
                isValid: section.isValid,
                fieldsCount: section.fields.length,
                element: section.element.className
            });
        });
        
        // Verificar sección de accionistas específicamente
        const accionistasSection = Object.values(this.sections).find(s => s.type === 'accionistas');
        if (accionistasSection) {
            const cards = accionistasSection.element.querySelectorAll('.accionista-card');
            console.log('Accionistas cards found:', cards.length);
            
            cards.forEach((card, index) => {
                const nombre = card.querySelector('input[name*="nombre"]');
                const rfc = card.querySelector('input[name*="rfc"]');
                const porcentaje = card.querySelector('input[name*="porcentaje"]');
                
                console.log(`Card ${index + 1}:`, {
                    nombre: nombre?.value || 'no encontrado',
                    rfc: rfc?.value || 'no encontrado',
                    porcentaje: porcentaje?.value || 'no encontrado'
                });
            });
        }
    }

    isSectionApproved(section) {
        console.log(`=== CHECKING IF SECTION ${section.type} IS APPROVED ===`);
        
        // Verificar si todos los campos de entrada están deshabilitados
        const allInputs = section.element.querySelectorAll('input:not([type="hidden"])');
        const disabledInputs = section.element.querySelectorAll('input:not([type="hidden"])[disabled]');
        
        console.log(`Campos totales: ${allInputs.length}, Campos deshabilitados: ${disabledInputs.length}`);
        
        // Si hay campos y todos están deshabilitados, la sección está aprobada
        if (allInputs.length > 0 && disabledInputs.length === allInputs.length) {
            console.log(`✅ Sección ${section.type} está aprobada (todos los campos deshabilitados)`);
            return true;
        }
        
        // También verificar si hay campos con la clase opacity-50 (indicador visual de deshabilitado)
        const opacityInputs = section.element.querySelectorAll('input.opacity-50');
        if (opacityInputs.length > 0 && opacityInputs.length === allInputs.length) {
            console.log(`✅ Sección ${section.type} está aprobada (todos los campos con opacity-50)`);
            return true;
        }
        
        console.log(`❌ Sección ${section.type} NO está aprobada`);
        return false;
    }

    validateCurrentSection() {
        const currentSection = this.sections[this.currentStep];
        if (!currentSection) return true;

        console.log(`=== VALIDATING CURRENT SECTION: ${currentSection.type} ===`);

        // Si la sección está aprobada, omitir validación
        if (this.isSectionApproved(currentSection)) {
            console.log(`✅ Sección ${currentSection.type} está aprobada, omitiendo validación`);
            return true;
        }

        console.log(`❌ Sección ${currentSection.type} NO está aprobada, procediendo con validación`);

        // Validación específica de sección
        if (this.validator.validateSection(currentSection.type, currentSection.element)) {
            return true;
        }

        // Validación de campos individuales
        let allValid = true;
        currentSection.fields.forEach(field => {
            if (field.required) {
                const isValid = this.validator.validateField(field.element);
                if (!isValid) {
                    allValid = false;
                }
            }
        });

        return allValid;
    }

    nextStep() {
        const isValid = this.validateCurrentSection();
        
        if (!isValid) {
            this.showError();
            return false;
        }

        if (this.currentStep < this.totalSteps) {
            this.navigateToStep(this.currentStep + 1);
        }

        return true;
    }

    previousStep() {
        if (this.currentStep > 1) {
            this.navigateToStep(this.currentStep - 1);
        }
    }

    navigateToStep(step) {
        this.hideCurrentSection();
        this.currentStep = step;
        this.showCurrentSection();
        this.updateProgress();
        this.updateButtons();
    }

    hideCurrentSection() {
        const currentSection = this.sections[this.currentStep];
        if (currentSection) {
            currentSection.element.classList.add('hidden');
        }
    }

    showCurrentSection() {
        const currentSection = this.sections[this.currentStep];
        if (currentSection) {
            currentSection.element.classList.remove('hidden');
        }
    }

    updateProgress() {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        
        if (progressBar) {
            const percentage = (this.currentStep / this.totalSteps) * 100;
            progressBar.style.width = `${percentage}%`;
        }
        
        if (progressText) {
            progressText.textContent = `Paso ${this.currentStep} de ${this.totalSteps}`;
        }
    }

    updateButtons() {
        const buttons = {
            'btn-anterior': this.currentStep === 1,
            'btn-siguiente': this.currentStep === this.totalSteps,
            'btn-enviar': this.currentStep === this.totalSteps
        };

        Object.entries(buttons).forEach(([id, shouldHide]) => {
            const button = document.getElementById(id);
            if (button) button.classList.toggle('hidden', shouldHide);
        });
    }

    showError() {
        // Recolectar información específica sobre los errores
        const errorDetails = this.collectErrorDetails();
        
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-md';
        
        let errorMessage = 'Por favor, complete los siguientes campos requeridos:';
        if (errorDetails.length > 0) {
            errorMessage += '<br><br>' + errorDetails.map(detail => `• ${detail}`).join('<br>');
        }
        
        notification.innerHTML = `
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <div class="font-medium mb-1">Error de Validación</div>
                    <div class="text-sm">${errorMessage}</div>
                </div>
            </div>
        `;

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 5000);

        const currentSection = this.sections[this.currentStep];
        if (currentSection) {
            currentSection.element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    collectErrorDetails() {
        const errors = [];
        
        Object.values(this.sections).forEach(section => {
            // Si la sección está aprobada, omitir validación
            if (this.isSectionApproved(section)) {
                return;
            }

            if (section.type === 'accionistas') {
                const cards = section.element.querySelectorAll('.accionista-card');
                if (cards.length === 0) {
                    errors.push('Debe agregar al menos un accionista');
                } else {
                    cards.forEach((card, index) => {
                        const nombre = card.querySelector('input[name*="nombre"]');
                        const rfc = card.querySelector('input[name*="rfc"]');
                        const porcentaje = card.querySelector('input[name*="porcentaje"]');
                        
                        if (!nombre || !nombre.value.trim()) {
                            errors.push(`Accionista ${index + 1}: Nombre requerido`);
                        }
                        if (!rfc || !rfc.value.trim()) {
                            errors.push(`Accionista ${index + 1}: RFC requerido`);
                        }
                        if (!porcentaje || !porcentaje.value) {
                            errors.push(`Accionista ${index + 1}: Porcentaje requerido`);
                        }
                    });
                }
            } else if (section.type === 'documentos') {
                const documentosRequeridos = section.element.querySelectorAll('[data-documento-id]');
                let documentosPendientes = 0;
                let documentosCompletados = 0;
                const documentosFaltantes = [];

                documentosRequeridos.forEach((documento, index) => {
                    const documentoId = documento.getAttribute('data-documento-id');
                    const fileInput = document.getElementById(`file_${documentoId}`);
                    
                    // Verificar si el documento está aprobado o subido
                    const aprobadoBadge = documento.querySelector('.bg-green-100.text-green-700');
                    const subidoBadge = documento.querySelector('.bg-green-100.text-green-800');
                    const isAprobado = aprobadoBadge && aprobadoBadge.textContent.includes('Aprobado');
                    const isSubido = subidoBadge && subidoBadge.textContent.includes('Subido');
                    
                    if (isAprobado || isSubido) {
                        // Documento ya está completado
                        documentosCompletados++;
                    } else if (fileInput) {
                        // Documento pendiente, verificar si tiene archivo
                        documentosPendientes++;
                        
                        const hasFile = fileInput.files && fileInput.files.length > 0;
                        if (hasFile) {
                            documentosCompletados++;
                        } else {
                            // Obtener el nombre del documento
                            const nombreDocumento = documento.querySelector('h4');
                            const nombre = nombreDocumento ? nombreDocumento.textContent.trim() : `Documento ${index + 1}`;
                            documentosFaltantes.push(nombre);
                        }
                    } else {
                        // No hay file input, documento pendiente
                        documentosPendientes++;
                        const nombreDocumento = documento.querySelector('h4');
                        const nombre = nombreDocumento ? nombreDocumento.textContent.trim() : `Documento ${index + 1}`;
                        documentosFaltantes.push(nombre);
                    }
                });

                if (documentosCompletados === 0) {
                    errors.push('Debe subir al menos un documento');
                } else if (documentosFaltantes.length > 0) {
                    errors.push(`Documentos faltantes: ${documentosFaltantes.join(', ')}`);
                }
            } else {
                section.fields.forEach(field => {
                    // Si el campo está deshabilitado, no validar
                    if (field.element.disabled) {
                        return;
                    }
                    
                    if (field.required && (!field.element.value || field.element.value.trim() === '')) {
                        const label = this.getFieldLabel(field.element);
                        errors.push(`${label} es requerido`);
                    }
                });
            }
        });
        
        return errors;
    }

    getFieldLabel(field) {
        // Intentar obtener el label del campo
        const label = field.closest('.form-group')?.querySelector('label');
        if (label) {
            return label.textContent.replace('*', '').trim();
        }
        
        // Fallback: usar el nombre del campo
        const name = field.name || field.id || 'Campo';
        return name.replace(/[\[\]]/g, '').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    validateAndSubmit() {
        console.log('=== VALIDATE AND SUBMIT DEBUG ===');
        let allValid = true;
        const errors = [];

        Object.values(this.sections).forEach((section, index) => {
            console.log(`Validating section ${index + 1}:`, section.type);
            
            // Si la sección está aprobada, omitir validación
            if (this.isSectionApproved(section)) {
                console.log(`✅ Sección ${section.type} está aprobada, omitiendo validación`);
                return;
            }

            console.log(`❌ Sección ${section.type} NO está aprobada, procediendo con validación`);

            let sectionValid = true;

            // Validación de campos individuales
            section.fields.forEach(field => {
                if (field.required) {
                    const isValid = this.validator.validateField(field.element);
                    console.log(`Field ${field.element.name}:`, isValid);
                    if (!isValid) {
                        sectionValid = false;
                    }
                }
            });

            // Validación específica de sección
            if (['actividades', 'accionistas', 'documentos'].includes(section.type)) {
                const sectionValidation = this.validator.validateSection(section.type, section.element);
                console.log(`Section ${section.type} validation:`, sectionValidation);
                sectionValid = sectionValid && sectionValidation;
            }

            if (!sectionValid) {
                allValid = false;
            }
        });

        console.log('Final validation result:', allValid);

        if (!allValid) {
            const errorDetails = this.collectErrorDetails();
            this.showError(errorDetails);
            return false;
        }

        return true;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.sectionValidator = new SectionValidator();
}); 