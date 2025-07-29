/**
 * Revision Digital UI - Funciones específicas de la interfaz
 */

class RevisionDigitalUI {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupTextProtection();
    }
    
    setupTextProtection() {
        const textarea = document.getElementById('comentario_general');
        if (textarea) {
            this.protegerTextoPreestablecido(textarea);
        }
    }
    
    protegerTextoPreestablecido(textarea) {
        const textoPreestablecido = 'Observación de revisión digital: ';
        
        if (!textarea.value.includes(textoPreestablecido)) {
            textarea.value = textoPreestablecido + textarea.value;
        }
        
        textarea.addEventListener('input', function() {
            if (!this.value.startsWith(textoPreestablecido)) {
                this.value = textoPreestablecido + this.value.substring(textoPreestablecido.length);
            }
        });
        
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                const cursorPos = this.selectionStart;
                if (cursorPos <= textoPreestablecido.length) {
                    e.preventDefault();
                }
            }
        });
    }
    
    capturarComentarioGeneral(formId) {
        const comentarioGeneral = document.getElementById('comentario_general').value;
        const comentarioHidden = document.getElementById('comentario_general_' + formId.replace('form_', ''));
        if (comentarioHidden) {
            comentarioHidden.value = comentarioGeneral;
        }
    }
    
    async showConfirmModalWithValidation(title, message, formId) {
        let validacionExitosa = false;
        
        if (formId === 'form_por_cotejar') {
            if (window.revisionDigital) {
                validacionExitosa = await window.revisionDigital.validarEnviarACotejoBD();
            } else {
                return;
            }
        } else if (formId === 'form_rechazar') {
            if (window.revisionDigital) {
                validacionExitosa = window.revisionDigital.validarRechazarTramite();
            } else {
                return;
            }
        } else if (formId === 'form_para_correccion') {
            if (window.revisionDigital) {
                validacionExitosa = window.revisionDigital.validarParaCorreccion();
            } else {
                return;
            }
        }
        
        if (!validacionExitosa) {
            return;
        }
        
        this.capturarComentarioGeneral(formId);
        
        if (typeof window.showConfirmModal === 'function') {
            window.showConfirmModal(title, message, formId);
        } else {
            const modal = document.getElementById('modal-confirmacion');
            const titleElement = modal.querySelector('#modal-headline-modal-confirmacion');
            const messageElement = modal.querySelector('#modal-message-modal-confirmacion');
            
            if (titleElement) titleElement.textContent = title;
            if (messageElement) messageElement.textContent = message;
            
            modal.classList.remove('hidden');
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.revisionDigitalUI = new RevisionDigitalUI();
});

function protegerTextoPreestablecido() {
    if (window.revisionDigitalUI) {
        window.revisionDigitalUI.setupTextProtection();
    }
}

function capturarComentarioGeneral(formId) {
    if (window.revisionDigitalUI) {
        window.revisionDigitalUI.capturarComentarioGeneral(formId);
    }
}

function showConfirmModalWithValidation(title, message, formId) {
    if (window.revisionDigitalUI) {
        window.revisionDigitalUI.showConfirmModalWithValidation(title, message, formId);
    }
} 