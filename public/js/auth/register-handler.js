// Variable global para almacenar datos del SAT
let satDataGlobal = null;

/**
 * Determinar tipo de persona basándose en la longitud del RFC
 * @param {string} rfc - RFC del contribuyente
 * @returns {string} - 'Física' o 'Moral'
 */
function determinarTipoPersona(rfc) {
    if (!rfc || typeof rfc !== "string") {
        console.warn("⚠️ RFC inválido para determinar tipo de persona:", rfc);
        return "Física"; // Default a Física
    }

    // Limpiar el RFC (quitar espacios y convertir a mayúsculas)
    const rfcLimpio = rfc.trim().toUpperCase();

    // Validar que sea un RFC válido (solo letras y números)
    const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
    if (!rfcRegex.test(rfcLimpio)) {
        console.warn("⚠️ RFC no tiene formato válido:", rfcLimpio);
        return "Física"; // Default a Física si el formato es inválido
    }

    if (rfcLimpio.length === 13) {
        // RFC de 13 caracteres = Persona Física
        console.log("✅ RFC de 13 caracteres → Persona Física");
        return "Física";
    } else if (rfcLimpio.length === 12) {
        // RFC de 12 caracteres = Persona Moral
        console.log("✅ RFC de 12 caracteres → Persona Moral");
        return "Moral";
    } else {
        // Longitud no estándar, defaultear a Física
        console.warn(
            "⚠️ RFC con longitud no estándar (" +
                rfcLimpio.length +
                " caracteres) → Default a Persona Física"
        );
        return "Física";
    }
}

/**
 * Manejador simplificado para extracción de datos fiscales del SAT
 */
class RegisterHandler {
    constructor() {
        this.extractor = new ConstanciaExtractor({ debug: true });
    }

    /**
     * Procesa un archivo PDF para extraer datos fiscales del SAT
     * @param {File} file - Archivo PDF de la constancia fiscal
     * @returns {Promise<Object>} - Datos extraídos del SAT o error
     */
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

// Función global para el upload
window.uploadFile = async function (input) {
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        const handler = new RegisterHandler();

        // Actualizar nombre del archivo
        const fileNameEl = document.getElementById("fileName");
        if (fileNameEl) fileNameEl.textContent = file.name;

        // Mostrar indicador de procesamiento
        const processingStatus = document.getElementById("processingStatus");
        if (processingStatus) processingStatus.classList.remove("hidden");

        // Procesar archivo
        const result = await handler.processFile(file);

        // Ocultar indicador
        if (processingStatus) processingStatus.classList.add("hidden");

        if (result.success) {
            // Guardar datos del SAT globalmente
            satDataGlobal = result.sat_data;

            // Llenar campos ocultos
            fillHiddenInputs(result.sat_data);

            // Mostrar modal con los datos del SAT
            showSatDataModal(result.sat_data);
        } else {
            // Mostrar modal de error con título y descripción personalizados
            const titulo = "Error al procesar el archivo";
            const descripcion =
                result.error ||
                "No se pudo extraer la información del código QR. Verifica que el archivo contenga un código QR válido de la constancia fiscal del SAT.";
            mostrarModalError(titulo, descripcion);
        }
    }
};

// Función para llenar campos ocultos
function fillHiddenInputs(satData) {
    const fields = {
        sat_rfc: "rfc",
        sat_nombre: "nombre",
        sat_tipo_persona: "tipo_persona",
        // No llenar sat_email - el usuario debe introducir su propio email
    };

    Object.entries(fields).forEach(([fieldId, dataKey]) => {
        const element = document.getElementById(fieldId);
        if (element && satData[dataKey]) {
            element.value = satData[dataKey];
        }
    });
}

// Función para mostrar formulario de registro
function showRegistrationForm() {
    const registrationForm = document.getElementById("registrationForm");
    const actionButton = document.getElementById("actionButton");

    if (registrationForm) {
        registrationForm.classList.remove("hidden");
    }

    if (actionButton) {
        const actionText = actionButton.querySelector("span");
        if (actionText) actionText.textContent = "Registrarse";
    }
}

// Función global para el botón de acción
window.handleActionButton = function () {
    const input = document.getElementById("document");
    const registrationForm = document.getElementById("registrationForm");

    if (registrationForm && !registrationForm.classList.contains("hidden")) {
        // Si el formulario ya está visible, enviar con datos del SAT
        submitRegistrationWithSatData();
    } else {
        // Si no, abrir selector de archivo
        input?.click();
    }
};

// Función para enviar registro con datos del SAT
function submitRegistrationWithSatData() {
    const form = document.querySelector("form");

    if (!form) {
        alert("❌ No se encontró el formulario");
        return;
    }

    // Validaciones básicas del lado cliente
    const email = document.getElementById("email")?.value?.trim();
    const password = document.getElementById("password")?.value;
    const passwordConfirmation = document.getElementById(
        "password_confirmation"
    )?.value;

    if (!email) {
        alert("❌ El correo electrónico es obligatorio");
        document.getElementById("email")?.focus();
        return;
    }

    // Validar formato de correo
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("❌ Por favor ingrese un correo electrónico con formato válido");
        document.getElementById("email")?.focus();
        return;
    }

    if (!password) {
        alert("❌ La contraseña es obligatoria");
        document.getElementById("password")?.focus();
        return;
    }

    if (password !== passwordConfirmation) {
        alert("❌ Las contraseñas no coinciden");
        document.getElementById("password_confirmation")?.focus();
        return;
    }

    // Si hay datos del SAT, llenar los campos antes de enviar
    if (satDataGlobal) {
        console.log(
            "🔍 Llenando datos del SAT en el formulario:",
            satDataGlobal
        );

        // Llenar campos ocultos con datos del SAT
        const rfc = satDataGlobal.rfc || "";
        const tipoPersona = determinarTipoPersona(rfc);

        // Establecer valores en campos ocultos
        document.getElementById("sat_rfc").value = rfc;
        document.getElementById("sat_nombre").value =
            satDataGlobal.nombre || "";
        document.getElementById("sat_tipo_persona").value = tipoPersona;
        document.getElementById("sat_email").value = satDataGlobal.email || "";

        console.log("✅ Datos del SAT establecidos en el formulario");
    }

    // Mostrar loading
    showLoading("Registrando usuario...");

    // Enviar formulario normalmente (sin AJAX)
    form.submit();
}

// Función de loading simple
function showLoading(message) {
    const button = document.getElementById("actionButton");
    if (button) {
        button.disabled = true;
        button.querySelector("span").textContent = message || "Procesando...";
    }
}

// Función global para toggle de contraseña
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
