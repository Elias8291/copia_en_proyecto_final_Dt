/**
 * Validador de formularios para el proceso de inscripción
 * Maneja la validación de campos requeridos y formatos
 */

class ValidacionFormulario {
    /**
     * Constructor del validador
     * @param {FormularioInscripcion} formulario - Instancia del formulario principal
     */
    constructor(formulario) {
        this.formulario = formulario;
    }
    
    /**
     * Valida los campos requeridos de una sección
     * @param {HTMLElement} seccionElemento - Elemento DOM de la sección a validar
     * @returns {Boolean} Indica si la sección es válida
     */
    validarSeccion(seccionElemento) {
        if (!seccionElemento || seccionElemento.classList.contains('hidden')) {
            return true; // Si la sección no existe o está oculta, se considera válida
        }
        
        let esValido = true;
        const camposRequeridos = seccionElemento.querySelectorAll('[required]');
        
        // Validar cada campo requerido
        for (let campo of camposRequeridos) {
            if (!this._validarCampo(campo)) {
                this.formulario.navegacion.mostrarErrorCampo(campo);
                esValido = false;
                break; // Detener en el primer error
            }
        }
        
        return esValido;
    }
    
    /**
     * Valida un campo individual
     * @param {HTMLElement} campo - Campo a validar
     * @returns {Boolean} Indica si el campo es válido
     * @private
     */
    _validarCampo(campo) {
        // Validación básica para campos vacíos
        if (!campo.value.trim()) {
            return false;
        }
        
        // Validación específica para checkboxes
        if (campo.type === 'checkbox' && !campo.checked) {
            return false;
        }
        
        // Validación de patrones si existe el atributo pattern
        if (campo.pattern && !new RegExp(campo.pattern).test(campo.value)) {
            return false;
        }
        
        // Validación específica para campos de porcentaje de accionistas
        if (campo.name && campo.name.includes('[porcentaje]')) {
            const porcentaje = parseFloat(campo.value);
            if (isNaN(porcentaje) || porcentaje < 0 || porcentaje > 100) {
                return false;
            }
        }
        
        // Validación para campos de email
        if (campo.type === 'email' && campo.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(campo.value)) {
                return false;
            }
        }
        
        // Validación para campos de teléfono
        if (campo.name && campo.name.includes('telefono') && campo.value) {
            const telefonoRegex = /^[\d\s\-\+\(\)]+$/;
            if (!telefonoRegex.test(campo.value)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Valida el formulario completo antes del envío
     * @returns {Boolean} Indica si el formulario es válido
     */
    validarFormularioCompleto() {
        const secciones = document.querySelectorAll('.form-section:not(.hidden)');
        let esValido = true;
        
        for (let seccion of secciones) {
            if (!this.validarSeccion(seccion)) {
                esValido = false;
                break;
            }
        }
        
        return esValido;
    }
    
    /**
     * Valida que el porcentaje total de accionistas sea 100%
     * @returns {Boolean} Indica si el porcentaje es válido
     */
    validarPorcentajeAccionistas() {
        const camposPorcentaje = document.querySelectorAll('input[name*="[porcentaje]"]');
        let totalPorcentaje = 0;
        
        camposPorcentaje.forEach(campo => {
            const valor = parseFloat(campo.value) || 0;
            totalPorcentaje += valor;
        });
        
        // Permitir un margen de error de ±0.01 para problemas de precisión flotante
        return Math.abs(totalPorcentaje - 100) <= 0.01;
    }
}

// Exportar para uso en otros módulos
window.ValidacionFormulario = ValidacionFormulario; 