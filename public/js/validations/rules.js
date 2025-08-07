// Reglas de validación reutilizables
const ValidationRules = {
    // Validación de campo requerido
    required: (value) => {
        return value.trim() !== '' ? null : 'Este campo es obligatorio';
    },

    // Validación de email
    email: (value) => {
        if (!value) return null; // Si está vacío, no validar (usar required para eso)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(value) ? null : 'Formato de email inválido';
    },

    // Validación de RFC
    rfc: (value) => {
        if (!value) return null;
        const rfcRegex = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
        return rfcRegex.test(value.toUpperCase()) ? null : 'Formato de RFC inválido';
    },

    // Validación de CURP
    curp: (value) => {
        if (!value) return null;
        const curpRegex = /^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/;
        return curpRegex.test(value.toUpperCase()) ? null : 'Formato de CURP inválido';
    },

    // Validación de teléfono
    phone: (value) => {
        if (!value) return null;
        const phoneRegex = /^[0-9\s\(\)\-\+]+$/;
        return phoneRegex.test(value) ? null : 'Solo números, espacios, paréntesis, guiones y signos +';
    },

    // Validación de código postal
    postalCode: (value) => {
        if (!value) return null;
        const postalRegex = /^[0-9]{5}$/;
        return postalRegex.test(value) ? null : 'Debe tener 5 dígitos numéricos';
    },

    // Validación de URL
    url: (value) => {
        if (!value) return null;
        try {
            new URL(value);
            return null;
        } catch {
            return 'URL inválida';
        }
    },

    // Validación de longitud mínima
    minLength: (min) => (value) => {
        if (!value) return null;
        return value.length >= min ? null : `Mínimo ${min} caracteres`;
    },

    // Validación de longitud máxima
    maxLength: (max) => (value) => {
        if (!value) return null;
        return value.length <= max ? null : `Máximo ${max} caracteres`;
    },

    // Validación de número entre rangos
    between: (min, max) => (value) => {
        if (!value) return null;
        const num = parseFloat(value);
        return !isNaN(num) && num >= min && num <= max ? null : `Debe estar entre ${min} y ${max}`;
    },

    // Validación de porcentaje (0-100)
    percentage: (value) => {
        if (!value) return null;
        const num = parseFloat(value);
        return !isNaN(num) && num >= 0 && num <= 100 ? null : 'Debe estar entre 0 y 100';
    },

    // Validación de fecha no futura
    notFuture: (value) => {
        if (!value) return null;
        const date = new Date(value);
        const today = new Date();
        today.setHours(23, 59, 59, 999);
        return date <= today ? null : 'La fecha no puede ser futura';
    },

    // Validación de fecha posterior a otra
    afterDate: (otherDateField) => (value) => {
        if (!value) return null;
        const otherDate = document.querySelector(`[name="${otherDateField}"]`)?.value;
        if (!otherDate) return null;
        const date1 = new Date(value);
        const date2 = new Date(otherDate);
        return date1 >= date2 ? null : 'Debe ser posterior o igual a la fecha de constitución';
    }
};

export default ValidationRules; 