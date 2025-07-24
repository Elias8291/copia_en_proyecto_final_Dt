class TramiteFormValidatorBase {
    constructor(options = {}) {
        this.config = {
            showErrors: true,
            scrollToError: true,
            errorClass: "error-field",
            errorMessageClass: "error-message",
            errorDuration: 5000,
            ...options,
        };

        this.validators = new Map();
        this.errorMessages = new Map();
        this.customRules = new Map();

        this.init();
    }

    init() {
        this.setupDefaultValidators();
        this.setupDefaultMessages();
    }

    setupDefaultValidators() {
        this.addValidator("required", (value, element) => {
            if (element.type === "checkbox" || element.type === "radio") {
                return element.checked;
            }
            return value && value.trim().length > 0;
        });

        this.addValidator("email", (value) => {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return !value || emailRegex.test(value);
        });

        this.addValidator("rfc", (value) => {
            if (!value) return true;
            const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
            return rfcRegex.test(value.toUpperCase());
        });

        this.addValidator("curp", (value) => {
            if (!value) return true;
            const curpRegex = /^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/;
            return curpRegex.test(value.toUpperCase());
        });

        this.addValidator("phone", (value) => {
            if (!value) return true;
            const phoneRegex = /^[0-9]{10}$/;
            return phoneRegex.test(value.replace(/\D/g, ""));
        });

        this.addValidator("postal", (value) => {
            if (!value) return true;
            const postalRegex = /^[0-9]{5}$/;
            return postalRegex.test(value);
        });

        this.addValidator("minLength", (value, element, params) => {
            if (!value) return true;
            const minLength = parseInt(params) || 0;
            return value.length >= minLength;
        });

        this.addValidator("maxLength", (value, element, params) => {
            if (!value) return true;
            const maxLength = parseInt(params) || Infinity;
            return value.length <= maxLength;
        });

        this.addValidator("file", (value, element) => {
            if (!element.files || element.files.length === 0) {
                return !element.hasAttribute("required");
            }
            return true;
        });

        this.addValidator("select", (value, element) => {
            return value && value !== "" && value !== "0";
        });

        this.addValidator("url", (value) => {
            if (!value) return true;
            try {
                new URL(value);
                return true;
            } catch {
                return false;
            }
        });
    }

    setupDefaultMessages() {
        this.errorMessages.set("required", "Este campo es obligatorio");
        this.errorMessages.set("email", "Ingrese un email válido");
        this.errorMessages.set("rfc", "Ingrese un RFC válido");
        this.errorMessages.set("curp", "Ingrese una CURP válida");
        this.errorMessages.set(
            "phone",
            "Ingrese un teléfono válido (10 dígitos)"
        );
        this.errorMessages.set("postal", "Ingrese un código postal válido");
        this.errorMessages.set(
            "minLength",
            "Debe tener al menos {0} caracteres"
        );
        this.errorMessages.set("maxLength", "No debe exceder {0} caracteres");
        this.errorMessages.set("file", "Debe seleccionar un archivo");
        this.errorMessages.set("select", "Debe seleccionar una opción");
        this.errorMessages.set(
            "url",
            "Ingrese una URL válida (ej: https://ejemplo.com)"
        );
    }

    addValidator(name, validatorFunction) {
        this.validators.set(name, validatorFunction);
    }

    addErrorMessage(validatorName, message) {
        this.errorMessages.set(validatorName, message);
    }

    validateField(element) {
        const errors = [];
        const value = element.value;
        const rules = this.getFieldRules(element);

        for (const rule of rules) {
            const validator = this.validators.get(rule.name);
            if (validator && !validator(value, element, rule.params)) {
                const message = this.getErrorMessage(rule.name, rule.params);
                errors.push(message);
                break;
            }
        }

        this.displayFieldErrors(element, errors);
        return errors.length === 0;
    }

    getFieldRules(element) {
        const rules = [];

        if (element.hasAttribute("required")) {
            rules.push({ name: "required" });
        }

        if (element.type === "email") {
            rules.push({ name: "email" });
        }

        const dataRules = element.dataset.validate;
        if (dataRules) {
            const rulesList = dataRules.split("|");
            for (const rule of rulesList) {
                const [name, params] = rule.split(":");
                rules.push({ name: name.trim(), params });
            }
        }

        if (element.classList.contains("validate-rfc")) {
            rules.push({ name: "rfc" });
        }
        if (element.classList.contains("validate-curp")) {
            rules.push({ name: "curp" });
        }
        if (element.classList.contains("validate-phone")) {
            rules.push({ name: "phone" });
        }
        if (element.classList.contains("validate-postal")) {
            rules.push({ name: "postal" });
        }

        if (element.tagName === "SELECT") {
            rules.push({ name: "select" });
        }

        if (element.type === "file") {
            rules.push({ name: "file" });
        }

        return rules;
    }

    getErrorMessage(validatorName, params) {
        let message = this.errorMessages.get(validatorName) || "Campo inválido";

        if (params && message.includes("{0}")) {
            message = message.replace("{0}", params);
        }

        return message;
    }

    displayFieldErrors(element, errors) {
        this.clearFieldErrors(element);

        if (errors.length > 0 && this.config.showErrors) {
            element.classList.add(this.config.errorClass);

            const container = element.closest(".field-container");
            if (container) {
                container.classList.add("has-error");
                container.classList.remove("has-success");
            }

            const errorDiv = document.createElement("div");
            errorDiv.className = `${this.config.errorMessageClass} animate-fade-in`;
            errorDiv.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800">${errors[0]}</p>
                    </div>
                </div>
            `;

            element.parentNode.insertBefore(errorDiv, element.nextSibling);

            if (this.config.errorDuration > 0) {
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, this.config.errorDuration);
            }

            if (this.config.scrollToError) {
                element.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }
    }

    clearFieldErrors(element) {
        element.classList.remove(this.config.errorClass);

        const container = element.closest(".field-container");
        if (container) {
            container.classList.remove("has-error");
            if (element.value && element.value.trim()) {
                container.classList.add("has-success");
            }
        }

        const nextElement = element.nextSibling;
        if (
            nextElement &&
            nextElement.classList &&
            nextElement.classList.contains(this.config.errorMessageClass)
        ) {
            nextElement.remove();
        }
    }

    validateSection(sectionElement) {
        const fields = sectionElement.querySelectorAll(
            "input, select, textarea"
        );
        let isValid = true;
        let firstErrorField = null;

        for (const field of fields) {
            const fieldValid = this.validateField(field);
            if (!fieldValid && isValid) {
                isValid = false;
                firstErrorField = field;
            }
        }

        if (!isValid && firstErrorField && this.config.scrollToError) {
            firstErrorField.focus();
        }

        return isValid;
    }

    validateForm(formElement) {
        const sections = formElement.querySelectorAll(".step-section");
        let isValid = true;
        let firstErrorSection = null;

        for (const section of sections) {
            const sectionValid = this.validateSection(section);
            if (!sectionValid && isValid) {
                isValid = false;
                firstErrorSection = section;
            }
        }

        return {
            isValid,
            firstErrorSection,
        };
    }

    setupRealTimeValidation(container) {
        const fields = container.querySelectorAll("input, select, textarea");

        fields.forEach((field) => {
            if (
                field.id === "buscador-actividad" ||
                field.id === "actividades-validation"
            ) {
                return;
            }

            field.addEventListener("blur", () => {
                setTimeout(() => {
                    this.validateField(field);
                }, 150);
            });

            field.addEventListener("input", () => {
                this.clearFieldErrors(field);
            });

            if (field.tagName === "SELECT") {
                field.addEventListener("change", () => {
                    setTimeout(() => {
                        this.validateField(field);
                    }, 150);
                });
            }
        });
    }

    showErrorSummary(errors, container) {
        if (errors.length === 0) return;

        const summaryDiv = document.createElement("div");
        summaryDiv.className = "error-summary";
        summaryDiv.innerHTML = `
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-red-900 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Se encontraron errores en el formulario
                    </h3>
                    <p class="text-sm text-red-700 mb-3">Por favor, corrija los siguientes campos antes de continuar:</p>
                    <ul class="space-y-2">
                        ${errors
                            .map(
                                (error) => `
                            <li class="flex items-start space-x-2">
                                <div class="w-1.5 h-1.5 bg-red-500 rounded-full mt-2 flex-shrink-0"></div>
                                <span class="text-sm text-red-800">${error}</span>
                            </li>
                        `
                            )
                            .join("")}
                    </ul>
                </div>
            </div>
        `;

        container.insertBefore(summaryDiv, container.firstChild);

        setTimeout(() => {
            if (summaryDiv.parentNode) {
                summaryDiv.remove();
            }
        }, this.config.errorDuration);
    }

    getValidationStats(formElement) {
        const fields = formElement.querySelectorAll("input, select, textarea");
        let totalFields = 0;
        let validFields = 0;
        let requiredFields = 0;
        let validRequiredFields = 0;

        fields.forEach((field) => {
            totalFields++;
            if (field.hasAttribute("required")) {
                requiredFields++;
            }

            if (this.validateField(field)) {
                validFields++;
                if (field.hasAttribute("required")) {
                    validRequiredFields++;
                }
            }
        });

        return {
            totalFields,
            validFields,
            requiredFields,
            validRequiredFields,
            completionPercentage: Math.round((validFields / totalFields) * 100),
            requiredCompletionPercentage:
                requiredFields > 0
                    ? Math.round((validRequiredFields / requiredFields) * 100)
                    : 100,
        };
    }
}

class TramiteFormValidator extends TramiteFormValidatorBase {
    constructor(options = {}) {
        super(options);
        this.setupTramiteValidators();
        this.setupTramiteMessages();
    }

    setupTramiteValidators() {
        this.addValidator("actividades", (value, element) => {
            const actividadesContainer = document.getElementById(
                "actividades-seleccionadas"
            );
            if (!actividadesContainer) {
                return false;
            }

            const containerText = actividadesContainer.textContent || "";
            const hasEmptyMessage = containerText.includes(
                "No se han seleccionado actividades económicas"
            );

            const hiddenInputs = document.querySelectorAll(
                'input[name="actividades[]"]'
            );
            const actividadesItems = actividadesContainer.querySelectorAll(
                'button[onclick*="removeActividad"]'
            );

            const isValid =
                !hasEmptyMessage &&
                (hiddenInputs.length > 0 || actividadesItems.length > 0);

            return isValid;
        });

        this.addValidator("documentos", (value, element) => {
            const documentosRequeridos = document.querySelectorAll(
                'input[type="file"][required]'
            );
            let allValid = true;

            documentosRequeridos.forEach((doc) => {
                if (!doc.files || doc.files.length === 0) {
                    allValid = false;
                }
            });

            return allValid;
        });

        this.addValidator("rfc-persona", (value, element) => {
            if (!value) return true;

            const rfc = value.toUpperCase();
            const tipoPersonaInput = document.querySelector(
                'input[name="tipo_persona"]'
            );
            const tipoPersona = tipoPersonaInput ? tipoPersonaInput.value : "";

            if (tipoPersona === "Moral" && rfc.length !== 12) {
                return false;
            }
            if (tipoPersona === "Física" && rfc.length !== 13) {
                return false;
            }

            return true;
        });
    }

    setupTramiteMessages() {
        this.addErrorMessage(
            "actividades",
            "Debe seleccionar al menos una actividad económica"
        );
        this.addErrorMessage(
            "documentos",
            "Debe cargar todos los documentos requeridos"
        );
        this.addErrorMessage(
            "rfc-persona",
            "El RFC no corresponde al tipo de persona seleccionado"
        );
    }

    validateTramiteStep(stepNumber, stepElement) {
        let isValid = true;
        const errors = [];

        switch (stepNumber) {
            case 1:
                isValid = this.validateDatosGenerales(stepElement, errors);
                break;
            case 2:
                isValid = this.validateDomicilio(stepElement, errors);
                break;
            case 3:
                isValid = this.validateDocumentosOConstitutivos(
                    stepElement,
                    errors
                );
                break;
            case 4:
                isValid = this.validateApoderadoOConfirmacion(
                    stepElement,
                    errors
                );
                break;
            case 5:
                isValid = this.validateAccionistas(stepElement, errors);
                break;
            case 6:
                isValid = this.validateDocumentos(stepElement, errors);
                break;
        }

        if (!isValid && this.config.showErrors) {
            this.showErrorSummary(errors, stepElement);
        }

        return isValid;
    }

    validateDatosGenerales(stepElement, errors) {
        const isValid = this.validateSection(stepElement);

        const rfcField = stepElement.querySelector('input[name="rfc"]');
        if (
            rfcField &&
            !this.validators.get("rfc-persona")(rfcField.value, rfcField)
        ) {
            errors.push(this.getErrorMessage("rfc-persona"));
        }

        const actividadesField = stepElement.querySelector(
            "#actividades-validation"
        );
        if (actividadesField) {
            const actividadesValid = this.validators.get("actividades")(
                "",
                actividadesField
            );
            if (!actividadesValid) {
                errors.push(this.getErrorMessage("actividades"));
                this.showActividadesError();
            }
        }

        return isValid && errors.length === 0;
    }

    validateDomicilio(stepElement, errors) {
        return this.validateSection(stepElement);
    }

    validateDocumentosOConstitutivos(stepElement, errors) {
        return this.validateSection(stepElement);
    }

    validateApoderadoOConfirmacion(stepElement, errors) {
        const isValid = this.validateSection(stepElement);

        const confirmacionCheckboxes = stepElement.querySelectorAll(
            'input[type="checkbox"][required]'
        );
        if (confirmacionCheckboxes.length > 0) {
            confirmacionCheckboxes.forEach((checkbox) => {
                if (!checkbox.checked) {
                    errors.push(
                        "Debe aceptar todos los términos y condiciones"
                    );
                }
            });
        }

        return isValid && errors.length === 0;
    }

    validateAccionistas(stepElement, errors) {
        return this.validateSection(stepElement);
    }

    validateDocumentos(stepElement, errors) {
        const isValid = this.validateSection(stepElement);

        if (!this.validators.get("documentos")("", null)) {
            errors.push(this.getErrorMessage("documentos"));
        }

        return isValid && errors.length === 0;
    }

    showActividadesError() {
        const container = document.getElementById("actividades-seleccionadas");
        if (!container) return;

        const existingError = container.querySelector(
            ".actividades-error-message"
        );
        if (existingError) {
            existingError.remove();
        }

        const errorDiv = document.createElement("div");
        errorDiv.className =
            "actividades-error-message error-message animate-fade-in mt-3";
        errorDiv.innerHTML = `
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800">Debe seleccionar al menos una actividad económica</p>
                    <p class="text-xs text-red-600 mt-1">Use el buscador para encontrar y agregar actividades</p>
                </div>
            </div>
        `;

        container.appendChild(errorDiv);

        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 8000);

        container.scrollIntoView({ behavior: "smooth", block: "center" });
    }
}

window.TramiteFormValidatorBase = TramiteFormValidatorBase;
window.TramiteFormValidator = TramiteFormValidator;

document.addEventListener("DOMContentLoaded", function () {
    const tramiteForm = document.getElementById("formulario-tramite");
    if (tramiteForm) {
        window.tramiteValidator = new TramiteFormValidator({
            showErrors: true,
            scrollToError: true,
            errorDuration: 5000,
        });
    }
});
