/**
 * Componente reutilizable para efectos de carga en botones y formularios
 * Uso: LoadingEffects.showButtonLoading(button, 'Procesando...')
 */
class LoadingEffects {
    static buttonStates = new Map();

    /**
     * Aplicar estado de carga a un botón
     * @param {HTMLElement} button - El botón
     * @param {string} loadingText - Texto a mostrar durante la carga
     * @param {string} spinnerType - Tipo de spinner: 'simple', 'dots', 'pulse'
     */
    static showButtonLoading(button, loadingText = 'Cargando...', spinnerType = 'simple') {
        if (!button) return;
        
        // Guardar estado original
        const originalState = {
            innerHTML: button.innerHTML,
            disabled: button.disabled,
            classList: Array.from(button.classList)
        };
        
        this.buttonStates.set(button, originalState);
        
        // Aplicar estado de carga
        button.disabled = true;
        button.innerHTML = this.getLoadingHTML(loadingText, spinnerType);
        
        // Agregar clase de carga
        button.classList.add('loading-state');
    }

    /**
     * Restaurar el estado original del botón
     * @param {HTMLElement} button - El botón a restaurar
     */
    static hideButtonLoading(button) {
        if (!button || !this.buttonStates.has(button)) return;
        
        const originalState = this.buttonStates.get(button);
        
        // Restaurar estado original
        button.innerHTML = originalState.innerHTML;
        button.disabled = originalState.disabled;
        button.className = originalState.classList.join(' ');
        
        // Limpiar del mapa
        this.buttonStates.delete(button);
    }

    /**
     * Generar HTML para diferentes tipos de carga
     * @param {string} text - Texto a mostrar
     * @param {string} type - Tipo de spinner
     * @returns {string} HTML del loading
     */
    static getLoadingHTML(text, type = 'simple') {
        const spinners = {
            simple: `
                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `,
            dots: `
                <div class="flex space-x-1 mr-2">
                    <div class="w-2 h-2 bg-current rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-current rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            `,
            pulse: `
                <div class="w-4 h-4 bg-current rounded-full animate-pulse mr-2"></div>
            `
        };

        return `${spinners[type] || spinners.simple}<span>${text}</span>`;
    }

    /**
     * Aplicar loading a un formulario completo
     * @param {HTMLFormElement} form - El formulario
     * @param {string} loadingText - Texto para los botones
     */
    static showFormLoading(form, loadingText = 'Procesando...') {
        if (!form) return;

        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(button => {
            this.showButtonLoading(button, loadingText);
        });

        // Deshabilitar todos los inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (!input.dataset.originalDisabled) {
                input.dataset.originalDisabled = input.disabled;
            }
            input.disabled = true;
        });

        form.classList.add('form-loading');
    }

    /**
     * Restaurar formulario del estado de carga
     * @param {HTMLFormElement} form - El formulario
     */
    static hideFormLoading(form) {
        if (!form) return;

        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(button => {
            this.hideButtonLoading(button);
        });

        // Restaurar inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.dataset.originalDisabled !== undefined) {
                input.disabled = input.dataset.originalDisabled === 'true';
                delete input.dataset.originalDisabled;
            }
        });

        form.classList.remove('form-loading');
    }

    /**
     * Auto-timeout para limpiar estados de carga
     * @param {HTMLElement} element - Elemento (botón o formulario)
     * @param {number} timeout - Tiempo en milisegundos (default: 10000)
     */
    static setAutoTimeout(element, timeout = 10000) {
        setTimeout(() => {
            if (element.tagName === 'FORM') {
                this.hideFormLoading(element);
            } else {
                this.hideButtonLoading(element);
            }
        }, timeout);
    }
}

// Hacer disponible globalmente
window.LoadingEffects = LoadingEffects;

// Funciones de conveniencia globales
window.showButtonLoading = (button, text, type) => LoadingEffects.showButtonLoading(button, text, type);
window.hideButtonLoading = (button) => LoadingEffects.hideButtonLoading(button);
window.showFormLoading = (form, text) => LoadingEffects.showFormLoading(form, text);
window.hideFormLoading = (form) => LoadingEffects.hideFormLoading(form);
