/**
 * Navegación y control de pasos del formulario de trámites
 */

// Evitar redeclaración
if (typeof FormularioNavegacion === "undefined") {
    class FormularioNavegacion {
        constructor(totalSteps, stepNames, tipoPersona = "Física") {
            this.currentStep = 1;
            this.totalSteps = totalSteps;
            this.stepNames = stepNames;
            this.tipoPersona = tipoPersona;

            this.btnAnterior = document.getElementById("btn-anterior");
            this.btnSiguiente = document.getElementById("btn-siguiente");
            this.btnEnviar = document.getElementById("btn-enviar");
            this.progressBar = document.getElementById("progress-bar");
            this.mobileCurrentStep = document.getElementById(
                "mobile-current-step"
            );
            this.mobileStepText = document.getElementById("mobile-step-text");
            this.mobileStepName = document.getElementById("mobile-step-name");

            this.init();
        }

        init() {
            this.setupEventListeners();
            this.updateStep();
        }

        setupEventListeners() {
            if (this.btnSiguiente) {
                this.btnSiguiente.addEventListener("click", () =>
                    this.nextStep()
                );
            }

            if (this.btnAnterior) {
                this.btnAnterior.addEventListener("click", () =>
                    this.prevStep()
                );
            }
        }

        nextStep() {
            // Validaciones deshabilitadas
            // if (!this.validateCurrentStep()) {
            //     this.showValidationMessage('Por favor complete todos los campos requeridos.');
            //     return;
            // }

            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                this.updateStep();
            }
        }

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.updateStep();
            }
        }

        updateStep() {
            this.hideAllSteps();
            this.showCurrentStep();
            this.updateProgressIndicators();
            this.updateButtons();
            this.updateProgressBar();
            this.updateMobileIndicators();
        }

        hideAllSteps() {
            document.querySelectorAll(".step-section").forEach((section) => {
                section.classList.add("hidden");
            });
        }

        showCurrentStep() {
            const currentSection = document.querySelector(
                `[data-step="${this.currentStep}"]`
            );
            if (currentSection) {
                currentSection.classList.remove("hidden");
                currentSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        }

        updateProgressIndicators() {
            document
                .querySelectorAll("[data-step-indicator]")
                .forEach((indicator, index) => {
                    const stepNum = index + 1;
                    const circle = indicator.querySelector("div");
                    const text = indicator.querySelector("span");

                    if (stepNum <= this.currentStep) {
                        circle.className =
                            "flex items-center justify-center w-8 h-8 border-2 border-[#9D2449] rounded-full bg-[#9D2449] text-white text-xs font-bold shadow-sm transition-all duration-300";
                        if (text)
                            text.className =
                                "ml-2 font-medium text-[#9D2449] whitespace-nowrap";
                    } else {
                        circle.className =
                            "flex items-center justify-center w-8 h-8 border-2 border-slate-300 rounded-full text-xs transition-all duration-300";
                        if (text)
                            text.className =
                                "ml-2 font-medium text-slate-400 whitespace-nowrap";
                    }
                });
        }

        updateButtons() {
            // Botón anterior
            if (this.btnAnterior) {
                this.btnAnterior.disabled = this.currentStep === 1;
            }

            // SIEMPRE mostrar el botón de envío (sin importar el paso)
            if (this.btnEnviar) {
                this.btnEnviar.classList.remove("hidden");
            }

            // Solo ocultar botón siguiente si hay un botón de navegación
            if (this.btnSiguiente && this.currentStep === this.totalSteps) {
                this.btnSiguiente.classList.add("hidden");
            } else if (this.btnSiguiente) {
                this.btnSiguiente.classList.remove("hidden");
            }
        }

        updateProgressBar() {
            if (this.progressBar) {
                const progress = (this.currentStep / this.totalSteps) * 100;
                this.progressBar.style.width = progress + "%";
            }
        }

        updateMobileIndicators() {
            if (this.mobileCurrentStep) {
                this.mobileCurrentStep.textContent = this.currentStep;
            }

            if (this.mobileStepText) {
                this.mobileStepText.textContent = `de ${this.totalSteps}`;
            }

            if (this.mobileStepName && this.stepNames[this.currentStep]) {
                this.mobileStepName.textContent =
                    this.stepNames[this.currentStep];
            }
        }

        // Método público para ir a un paso específico
        goToStep(stepNumber) {
            if (stepNumber >= 1 && stepNumber <= this.totalSteps) {
                this.currentStep = stepNumber;
                this.updateStep();
            }
        }

        // Método público para obtener el paso actual
        getCurrentStep() {
            return this.currentStep;
        }

        // Validar paso actual antes de continuar
        validateCurrentStep() {
            const currentSection = document.querySelector(
                `[data-step="${this.currentStep}"]`
            );
            if (!currentSection) return true;

            const requiredFields = currentSection.querySelectorAll(
                "[required]:not([readonly])"
            );
            let isValid = true;

            requiredFields.forEach((field) => {
                if (!field.value.trim()) {
                    field.classList.add("border-red-500", "ring-red-500");
                    isValid = false;
                } else {
                    field.classList.remove("border-red-500", "ring-red-500");
                }
            });

            return isValid;
        }

        // Mostrar mensaje de validación
        showValidationMessage(message) {
            // Remover mensaje anterior si existe
            const existingMessage = document.querySelector(
                ".validation-message"
            );
            if (existingMessage) {
                existingMessage.remove();
            }

            // Crear nuevo mensaje
            const messageDiv = document.createElement("div");
            messageDiv.className =
                "validation-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center";
            messageDiv.innerHTML = `
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>${message}</span>
        `;

            // Insertar antes de la sección actual
            const currentSection = document.querySelector(
                `[data-step="${this.currentStep}"]`
            );
            if (currentSection) {
                currentSection.insertBefore(
                    messageDiv,
                    currentSection.firstChild
                );

                // Remover después de 5 segundos
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.remove();
                    }
                }, 5000);
            }
        }
    }

    // Hacer disponible globalmente
    window.FormularioNavegacion = FormularioNavegacion;
}
