class FormStepperManager {
    constructor(totalSteps, tipoPersona) {
        this.currentStep = 1;
        this.totalSteps = totalSteps;
        this.tipoPersona = tipoPersona;
        this.stepConfig = {
            'Física': {
                steps: [1, 2, 3, 4],
                names: {
                    1: 'Datos Generales',
                    2: 'Domicilio',
                    3: 'Documentos',
                    4: 'Confirmación'
                }
            },
            'Moral': {
                steps: [1, 2, 3, 4, 5, 6],
                names: {
                    1: 'Datos Generales',
                    2: 'Domicilio',
                    3: 'Constitutivos',
                    4: 'Apoderado',
                    5: 'Accionistas',
                    6: 'Documentos'
                }
            }
        };
        this.init();
    }

    init() {
        this.initializeStepper();
        this.setupStepperNavigation();
        this.setupTipoPersonaHandler();
        this.setupFormValidation();
    }

    initializeStepper() {
        document.querySelectorAll('.step-section').forEach((section, index) => {
            if (index === 0) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
        this.updateStepperUI();
    }

    setupStepperNavigation() {
        const btnAnterior = document.getElementById('btn-anterior');
        const btnSiguiente = document.getElementById('btn-siguiente');
        const btnEnviar = document.getElementById('btn-enviar');

        btnAnterior?.addEventListener('click', () => this.goToPreviousStep());
        btnSiguiente?.addEventListener('click', () => this.goToNextStep());
        btnEnviar?.addEventListener('click', () => this.submitForm());

        document.querySelectorAll('[data-step]').forEach(stepElement => {
            if (stepElement.classList.contains('step-circle') || stepElement.querySelector('.step-circle')) {
                stepElement.style.cursor = 'pointer';
                stepElement.addEventListener('click', () => {
                    const targetStep = parseInt(stepElement.getAttribute('data-step'));
                    if (targetStep <= this.currentStep || this.validateCurrentStep()) {
                        this.goToStep(targetStep);
                    }
                });
            }
        });
    }

    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) return;

        const currentSection = document.getElementById(`step-section-${this.currentStep}`);
        if (currentSection) {
            currentSection.style.display = 'none';
        }

        const newSection = document.getElementById(`step-section-${stepNumber}`);
        if (newSection) {
            newSection.style.display = 'block';
            this.currentStep = stepNumber;
            this.updateStepperUI();

            newSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    goToNextStep() {
        if (this.validateCurrentStep()) {
            if (this.currentStep < this.totalSteps) {
                this.goToStep(this.currentStep + 1);
            } else {
                document.getElementById('btn-siguiente').style.display = 'none';
                document.getElementById('btn-enviar').style.display = 'block';
            }
        }
    }

    goToPreviousStep() {
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep - 1);
        }
    }

    validateCurrentStep() {
        if (window.tramiteValidator) {
            const currentSection = document.getElementById(`step-section-${this.currentStep}`);
            if (!currentSection) return true;
            
            return window.tramiteValidator.validateTramiteStep(this.currentStep, currentSection);
        }
        
        return true;
    }

    updateStepperUI() {
        const config = this.stepConfig[this.tipoPersona];
        const progressLine = document.getElementById('progress-line');
        const currentStepText = document.getElementById('current-step-text');
        const currentStepName = document.getElementById('current-step-name');
        const btnAnterior = document.getElementById('btn-anterior');
        const btnSiguiente = document.getElementById('btn-siguiente');
        const btnSiguienteText = document.getElementById('btn-siguiente-text');

        const progressBarBottom = document.getElementById('progress-bar-bottom');
        const progressPercentage = document.getElementById('progress-percentage');
        const currentStepBottom = document.getElementById('current-step-bottom');
        const currentStepNameBottom = document.getElementById('current-step-name-bottom');

        const progressPercent = (this.currentStep / this.totalSteps) * 100;

        if (progressLine) {
            progressLine.style.width = `${progressPercent}%`;
        }

        if (progressBarBottom) {
            progressBarBottom.style.width = `${progressPercent}%`;
        }
        if (progressPercentage) {
            progressPercentage.textContent = `${Math.round(progressPercent)}%`;
        }
        if (currentStepBottom) {
            currentStepBottom.textContent = this.currentStep;
        }
        if (currentStepNameBottom) {
            currentStepNameBottom.textContent = config.names[this.currentStep] || '';
        }

        if (currentStepText) currentStepText.textContent = this.currentStep;
        if (currentStepName) currentStepName.textContent = config.names[this.currentStep] || '';

        config.steps.forEach(stepNum => {
            const circle = document.getElementById(`step-circle-${stepNum}`);
            const label = document.getElementById(`step-label-${stepNum}`);
            const numberSpan = circle?.querySelector('.step-number');

            if (circle && label) {
                if (stepNum <= this.currentStep) {
                    circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold transition-all duration-300 step-circle group-hover:scale-110 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] text-white shadow-md';
                    label.className = 'text-xs font-medium transition-colors duration-300 step-label group-hover:text-[#9D2449] max-w-16 truncate text-[#9D2449]';
                    if (numberSpan) numberSpan.style.display = 'block';
                } else {
                    circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold transition-all duration-300 step-circle group-hover:scale-110 bg-gray-200 text-gray-500';
                    label.className = 'text-xs font-medium transition-colors duration-300 step-label group-hover:text-[#9D2449] max-w-16 truncate text-gray-500';
                    if (numberSpan) numberSpan.style.display = 'block';
                }
            }
        });

        if (btnAnterior) {
            btnAnterior.style.display = this.currentStep > 1 ? 'flex' : 'none';
        }

        if (btnSiguiente && btnSiguienteText) {
            if (this.currentStep === this.totalSteps) {
                btnSiguienteText.textContent = 'Finalizar';
                btnSiguiente.className = 'w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center';
            } else {
                btnSiguienteText.textContent = 'Siguiente';
                btnSiguiente.className = 'w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-[#9D2449] to-[#B91C1C] text-white rounded-xl hover:from-[#8a203f] hover:to-[#a91b1b] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center';
            }
        }
    }

    async submitForm() {
        if (this.validateCurrentStep()) {
            const form = document.getElementById('formulario-tramite');
            if (form) {
                const formData = new FormData(form);
                try {
                    // Agregar el token CSRF si no está presente
                    if (!formData.has('_token')) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                         document.querySelector('input[name="_token"]')?.value;
                        if (csrfToken) {
                            formData.append('_token', csrfToken);
                        }
                    }
                    
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    console.log('Respuesta del backend:', data); // DEPURACIÓN
                    console.log('Status de respuesta:', response.status); // DEPURACIÓN
                    
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.errors) {
                        this.showValidationErrors(data.errors);
                    } else if (data.message) {
                        console.error('Error del servidor:', data.message);
                        alert('Error: ' + data.message);
                    } else {
                        console.error('Respuesta completa:', data);
                        alert('Error desconocido. Revisa la consola para más detalles.');
                    }
                } catch (err) {
                    console.error('Error en submitForm:', err);
                    alert('Error inesperado al enviar el formulario: ' + err.message);
                }
            }
        }
    }

    showValidationErrors(errors) {
        // Limpiar errores previos
        document.querySelectorAll('.form-error').forEach(el => el.remove());
        let algunErrorMostrado = false;
        let primerCampoConError = null;
        for (const campo in errors) {
            // Soporte para campos anidados tipo 'documentos.1'
            let selector = `[name="${campo}"]`;
            if (campo.includes('.')) {
                // Ejemplo: documentos.1 => documentos[1]
                selector = `[name="${campo.replace(/\.(\d+)/g, '[$1]')}"]`;
            }
            const input = document.querySelector(selector);
            if (input) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'form-error text-xs text-red-600 mt-1';
                errorDiv.innerText = errors[campo][0];
                input.classList.add('border-red-500');
                input.parentNode.insertBefore(errorDiv, input.nextSibling);
                if (!primerCampoConError) {
                    primerCampoConError = input;
                }
                algunErrorMostrado = true;
            }
        }
        // Saltar al paso del primer error y enfocar el campo
        if (primerCampoConError) {
            // Buscar el contenedor de step-section más cercano
            const stepSection = primerCampoConError.closest('.step-section');
            if (stepSection && stepSection.id) {
                const match = stepSection.id.match(/step-section-(\d+)/);
                if (match) {
                    const stepNum = parseInt(match[1]);
                    if (stepNum && stepNum !== this.currentStep) {
                        this.goToStep(stepNum);
                    }
                }
            }
            setTimeout(() => { primerCampoConError.focus(); }, 300);
        }
        if (!algunErrorMostrado) {
            alert('Error de validación, pero no se pudo asociar a un campo. Revisa la consola.');
        }
    }

    setupTipoPersonaHandler() {
        const rfcInput = document.querySelector('input[name="rfc"]');
        const tipoPersonaInput = document.querySelector('input[name="tipo_persona"]');

        const actualizarTipoPersona = () => {
            if (rfcInput && tipoPersonaInput) {
                const rfc = rfcInput.value.trim().toUpperCase();
                let nuevoTipoPersona = 'Física';

                if (rfc.length === 12) {
                    nuevoTipoPersona = 'Moral';
                } else if (rfc.length === 13) {
                    nuevoTipoPersona = 'Física';
                }

                if (nuevoTipoPersona !== this.tipoPersona) {
                    this.tipoPersona = nuevoTipoPersona;
                    this.totalSteps = this.tipoPersona === 'Moral' ? 6 : 4;
                    this.currentStep = 1;

                    tipoPersonaInput.value = this.tipoPersona;
                    this.initializeStepper();
                }
            }
        };

        if (rfcInput) {
            rfcInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.toUpperCase();
                actualizarTipoPersona();
            });

            rfcInput.addEventListener('blur', actualizarTipoPersona);
            setTimeout(actualizarTipoPersona, 100);
        }
    }

    setupFormValidation() {
        setTimeout(() => {
            if (window.tramiteValidator) {
                document.querySelectorAll('.step-section').forEach(section => {
                    window.tramiteValidator.setupRealTimeValidation(section);
                });
            }
        }, 500);
    }
}

window.FormStepperManager = FormStepperManager;