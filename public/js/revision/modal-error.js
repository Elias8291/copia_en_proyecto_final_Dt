/**
 * Modal Error - Manejo del modal de error personalizado
 */

class ModalError {
    constructor() {
        this.modal = document.getElementById('modal-error-custom');
        this.init();
    }
    
    init() {
        if (this.modal) {
            this.setupEventListeners();
        }
    }
    
    setupEventListeners() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.close();
            }
        });
        
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });
    }
    
    close() {
        this.modal.classList.add('hidden');
    }
    
    show(titulo, mensaje) {
        const titleElement = this.modal.querySelector('[data-modal-title]');
        const messageElement = this.modal.querySelector('[data-modal-message]');
        
        if (titleElement) titleElement.textContent = titulo;
        if (messageElement) messageElement.textContent = mensaje;
        
        this.modal.classList.remove('hidden');
    }
}

function closeErrorModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.modalError = new ModalError();
}); 