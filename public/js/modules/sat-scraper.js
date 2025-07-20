/**
 * Módulo para scraping de datos del SAT
 * Maneja la extracción y procesamiento de datos desde URLs del SAT
 */
class SATScraper {
    constructor() {
        this.baseUrl = "/api";
        this.csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
    }

    /**
     * Extrae datos del SAT desde una URL de QR
     */
    async scrapeFromUrl(url) {
        try {
            const response = await fetch(`${this.baseUrl}/scrape-sat-data`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
                body: JSON.stringify({ url }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("Error scraping SAT data:", error);
            throw error;
        }
    }

    /**
     * Procesa y extrae datos del PDF con QR
     */
    async extractFromPdf(formData) {
        try {
            const response = await fetch(`${this.baseUrl}/extract-qr-url`, {
                method: "POST",
                body: formData,
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": this.csrfToken,
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error("Error extracting from PDF:", error);
            throw error;
        }
    }

    /**
     * Valida si una URL es del SAT
     */
    isValidSATUrl(url) {
        try {
            const urlObj = new URL(url);
            return urlObj.hostname.includes("siat.sat.gob.mx");
        } catch {
            return false;
        }
    }

    /**
     * Extrae RFC de una URL del SAT
     */
    extractRFCFromUrl(url) {
        try {
            const match = url.match(/D3=([^&_]+)/);
            if (match) {
                const parts = match[1].split("_");
                return parts[0];
            }
            return null;
        } catch {
            return null;
        }
    }

    /**
     * Formatea datos para mostrar en la interfaz
     */
    formatDataForDisplay(satData) {
        if (!satData || !satData.success) {
            return null;
        }

        const formData = satData.form_data || {};

        return {
            rfc: formData.rfc || "",
            razon_social: formData.razon_social || "",
            tipo_persona: formData.tipo_persona || "",
            regimen_fiscal: formData.regimen_fiscal || "",
            estatus: formData.estatus || "",
            fecha_inicio: formData.fecha_inicio || "",
            fecha_actualizacion: formData.fecha_actualizacion || "",
            curp: formData.curp || "",
            email: formData.email || "",
            codigo_postal: formData.codigo_postal || "",
            entidad_federativa: formData.entidad_federativa || "",
            municipio: formData.municipio || "",
            colonia: formData.colonia || "",
            calle: formData.calle || "",
            numero_exterior: formData.numero_exterior || "",
            numero_interior: formData.numero_interior || "",
        };
    }

    /**
     * Genera HTML para mostrar los datos extraídos
     */
    generateDataPreviewHTML(satData, rawSatData) {
        if (!satData) {
            return `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h4 class="font-semibold text-yellow-800 mb-2">Información:</h4>
                    <p class="text-yellow-700 text-sm">No se pudieron extraer datos estructurados del QR. Puedes consultar la URL manualmente o intentar con otro archivo.</p>
                    ${
                        rawSatData && rawSatData.error
                            ? `
                        <p class="text-red-600 text-xs mt-2">Error: ${rawSatData.error}</p>
                    `
                            : ""
                    }
                </div>
            `;
        }

        const fields = [
            { key: "rfc", label: "RFC" },
            { key: "razon_social", label: "Razón Social" },
            {
                key: "tipo_persona",
                label: "Tipo",
                transform: (val) =>
                    val === "fisica" ? "Persona Física" : "Persona Moral",
            },
            { key: "regimen_fiscal", label: "Régimen" },
            { key: "estatus", label: "Estatus" },
            { key: "entidad_federativa", label: "Estado" },
            { key: "codigo_postal", label: "CP" },
            { key: "email", label: "Email" },
        ];

        const fieldsHTML = fields
            .filter((field) => satData[field.key])
            .map((field) => {
                const value = field.transform
                    ? field.transform(satData[field.key])
                    : satData[field.key];
                return `<div><span class="font-medium">${field.label}:</span> ${value}</div>`;
            })
            .join("");

        return `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-green-800 mb-3">Datos Extraídos del SAT:</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    ${fieldsHTML}
                </div>
                
                ${
                    rawSatData && rawSatData.success
                        ? this.generateRawDataHTML(rawSatData)
                        : ""
                }
            </div>
        `;
    }

    /**
     * Genera HTML para mostrar datos completos extraídos
     */
    generateRawDataHTML(rawSatData) {
        const sections = [
            { key: "identificacion", label: "Identificación" },
            { key: "ubicacion", label: "Ubicación" },
            {
                key: "caracteristicas_fiscales",
                label: "Características Fiscales",
            },
        ];

        const sectionsHTML = sections
            .filter((section) => rawSatData[section.key])
            .map(
                (section) => `
                <div>
                    <strong>${section.label}:</strong>
                    <pre class="text-xs mt-1 whitespace-pre-wrap">${JSON.stringify(
                        rawSatData[section.key],
                        null,
                        2
                    )}</pre>
                </div>
            `
            )
            .join("");

        return `
            <div class="mt-4 pt-3 border-t border-green-300">
                <details class="cursor-pointer">
                    <summary class="text-sm font-medium text-green-700 hover:text-green-800">
                        Ver datos completos extraídos
                    </summary>
                    <div class="mt-2 text-xs bg-white rounded p-3 border">
                        <div class="space-y-2">
                            ${sectionsHTML}
                        </div>
                    </div>
                </details>
            </div>
        `;
    }
}

// Hacer disponible globalmente
window.SATScraper = SATScraper;
