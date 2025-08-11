/**
 * Modal Manager - Gestor universal de modales
 * Maneja apertura, cierre y funcionalidades comunes de todos los modales
 */
class ModalManager {
    constructor(modalId, options = {}) {
        this.modal = document.getElementById(modalId);
        this.options = {
            closeOnOutsideClick: true,
            autoFocus: true,
            ...options
        };
        this.init();
    }

    init() {
        if (!this.modal) return;
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Cerrar con botón X
        const closeBtn = this.modal.querySelector('[data-modal-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }

        // Cerrar con botón cancelar
        const cancelBtn = this.modal.querySelector('[data-modal-cancel]');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => this.close());
        }

        // Cerrar al hacer clic fuera del modal
        if (this.options.closeOnOutsideClick) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.close();
                }
            });
        }

        // Cerrar con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.close();
            }
        });
    }

    open() {
        this.modal?.classList.remove('hidden');
        if (this.options.autoFocus) {
            // Enfocar primer input o botón del modal
            const firstFocusable = this.modal.querySelector('input, button, select, textarea');
            firstFocusable?.focus();
        }
        this.onOpen();
    }

    close() {
        this.modal?.classList.add('hidden');
        this.onClose();
    }

    // Métodos para sobrescribir en clases hijas
    onOpen() {}
    onClose() {}

    isOpen() {
        return this.modal && !this.modal.classList.contains('hidden');
    }
}

// Exportar para uso global
window.ModalManager = ModalManager;

