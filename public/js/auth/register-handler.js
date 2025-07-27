let satDataGlobal = null;

function determinarTipoPersona(rfc) {
    if (!rfc || typeof rfc !== "string") return "Física";
    const rfcLimpio = rfc.trim().toUpperCase();
    const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
    if (!rfcRegex.test(rfcLimpio)) return "Física";
    if (rfcLimpio.length === 13) return "Física";
    if (rfcLimpio.length === 12) return "Moral";
    return "Física";
}

class RegisterHandler {
    constructor() {
        this.extractor = new ConstanciaExtractor({ debug: true });
    }
    async processFile(file) {
        try {
            const result = await this.extractor.extract(file);
            if (result.success) {
                return {
                    success: true,
                    sat_data: result.sat_data,
                    qr_url: result.qr_url,
                };
            } else {
                return {
                    success: false,
                    error: result.error,
                };
            }
        } catch (error) {
            return {
                success: false,
                error: "Error interno: " + error.message,
            };
        }
    }
}

window.uploadFile = async function (input) {
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const handler = new RegisterHandler();
        const fileNameEl = document.getElementById("fileName");
        if (fileNameEl) fileNameEl.textContent = file.name;
        const processingStatus = document.getElementById("processingStatus");
        if (processingStatus) processingStatus.classList.remove("hidden");
        const result = await handler.processFile(file);
        if (processingStatus) processingStatus.classList.add("hidden");
        if (result.success) {
            satDataGlobal = result.sat_data;
            fillHiddenInputs(result.sat_data);
            showSatDataModal(result.sat_data);
            showRegistrationForm();
        } else {
            mostrarModalError("Error al procesar el archivo", result.error || "No se pudo extraer la información del código QR. Verifica que el archivo contenga un código QR válido de la constancia fiscal del SAT.");
        }
    }
};

function fillHiddenInputs(satData) {
    const fields = {
        sat_rfc: "rfc",
        sat_nombre: "nombre",
        sat_tipo_persona: "tipo_persona",
    };
    Object.entries(fields).forEach(([fieldId, dataKey]) => {
        const element = document.getElementById(fieldId);
        if (element && satData[dataKey]) {
            element.value = satData[dataKey];
        }
    });
    
    if (satData.email) {
        const emailField = document.getElementById("email");
        if (emailField) {
            emailField.value = satData.email;
        }
    }
}

function showRegistrationForm() {
    const registrationForm = document.getElementById("registrationForm");
    const uploadArea = document.getElementById("uploadArea");
    const actionButton = document.getElementById("actionButton");
    
    if (registrationForm) registrationForm.classList.remove("hidden");
    if (uploadArea) uploadArea.classList.add("hidden");
    
    if (actionButton) {
        const actionText = actionButton.querySelector("span");
        if (actionText) actionText.textContent = "Registrarse";
    }
}

window.handleActionButton = function () {
    const input = document.getElementById("document");
    const registrationForm = document.getElementById("registrationForm");
    if (registrationForm && !registrationForm.classList.contains("hidden")) {
        submitRegistrationWithSatData();
    } else {
        input?.click();
    }
};

function submitRegistrationWithSatData() {
    const form = document.querySelector("form");
    if (!form) return;
    const email = document.getElementById("email")?.value?.trim();
    const password = document.getElementById("password")?.value;
    const passwordConfirmation = document.getElementById("password_confirmation")?.value;
    if (!email) {
        document.getElementById("email")?.focus();
        return;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        document.getElementById("email")?.focus();
        return;
    }
    if (!password) {
        document.getElementById("password")?.focus();
        return;
    }
    if (password !== passwordConfirmation) {
        document.getElementById("password_confirmation")?.focus();
        return;
    }
    if (satDataGlobal) {
        const rfc = satDataGlobal.rfc || "";
        const tipoPersona = determinarTipoPersona(rfc);
        document.getElementById("sat_rfc").value = rfc;
        document.getElementById("sat_nombre").value = satDataGlobal.nombre || "";
        document.getElementById("sat_tipo_persona").value = tipoPersona;
        document.getElementById("sat_email").value = satDataGlobal.email || "";
    }
    showLoading("Registrando usuario...");
    form.submit();
}

function showLoading(message) {
    const button = document.getElementById("actionButton");
    if (button) {
        button.disabled = true;
        button.querySelector("span").textContent = message || "Procesando...";
    }
}

if (typeof window.togglePassword === 'undefined') {
    window.togglePassword = function (fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + "-toggle-icon");
        if (field && icon) {
            const isPassword = field.type === "password";
            field.type = isPassword ? "text" : "password";
            const eyeIcon =
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 01 6 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            const eyeOffIcon =
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 01 12 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01 1.563-3.029m5.858.908a3 3 0 11 4.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m6.121-6.121A9.97 9.97 0 01 21 12c0 .906-.117 1.785-.337 2.625m-3.846 6.321L9.878 9.878"></path>';
            icon.innerHTML = isPassword ? eyeOffIcon : eyeIcon;
        }
    };
}

window.showSatDataFromForm = function () {
    if (satDataGlobal) {
        if (typeof showSatDataModal === 'function') {
            showSatDataModal(satDataGlobal);
        }
    }
};

if (typeof window !== 'undefined') {
    window.showSatDataFromForm = window.showSatDataFromForm || function () {
        if (satDataGlobal) {
            if (typeof showSatDataModal === 'function') {
                showSatDataModal(satDataGlobal);
            }
        }
    };
}
