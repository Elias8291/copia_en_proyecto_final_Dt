/**
 * Cargador de datos para formularios de trámite
 * Maneja la precarga de datos desde el ViewModel y errores de validación
 */

class TramiteDataLoader {
    constructor(viewModelData = null, validationErrors = []) {
        this.viewModelData = viewModelData;
        this.validationErrors = validationErrors;
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.loadFormData();
            this.highlightValidationErrors();
        });
    }

    loadFormData() {
        if (!this.viewModelData) return;

        this.loadDatosGenerales();
        this.loadDatosDomicilio();
    }

    loadDatosGenerales() {
        const datosGenerales = this.viewModelData.datosGenerales || {};
        
        this.setFieldValue('razon_social', datosGenerales.razon_social);
        this.setFieldValue('rfc', datosGenerales.rfc);
        this.setFieldValue('curp', datosGenerales.curp);
    }

    loadDatosDomicilio() {
        const datosDomicilio = this.viewModelData.datosDomicilio || {};
        
        this.setFieldValue('calle', datosDomicilio.calle);
        this.setFieldValue('numero_exterior', datosDomicilio.numero_exterior);
        this.setFieldValue('numero_interior', datosDomicilio.numero_interior);
        this.setFieldValue('colonia', datosDomicilio.asentamiento);
        this.setFieldValue('codigo_postal', datosDomicilio.codigo_postal);
        this.setFieldValue('municipio', datosDomicilio.municipio);
        this.setFieldValue('estado', datosDomicilio.estado);
    }

    setFieldValue(fieldId, value) {
        const field = document.getElementById(fieldId);
        
        // Solo cargar si no hay valores old() (errores de validación)
        if (field && !field.value && value) {
            field.value = value;
        }
    }

    highlightValidationErrors() {
        this.validationErrors.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('border-red-500');
            }
        });
    }

    // Método estático para crear instancia desde datos de Blade
    static fromBladeData(viewModelData, validationErrors) {
        return new TramiteDataLoader(viewModelData, validationErrors);
    }
}

// Exportar para uso global
window.TramiteDataLoader = TramiteDataLoader;
