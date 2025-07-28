/**
 * Sistema de Revisión de Documentos - Optimizado
 */

class DocumentoReview {
    constructor() {
        this.csrfToken = window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.processingDocuments = new Set(); // Evitar múltiples requests simultáneos
        this.init();
    }
    
    init() {
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        // Event listeners para formularios de documentos
        document.querySelectorAll('.documento-review-form').forEach(form => {
            form.addEventListener('submit', (e) => this.handleSubmit(e));
        });
        
        // Event listeners para textareas
        document.addEventListener('click', (e) => {
            if (e.target.name === 'comentario' && e.target.tagName === 'TEXTAREA') {
                this.setupTextarea(e.target);
            }
        });
    }
    
    toggleComment(documentoId) {
        const form = document.getElementById(`comment-form-${documentoId}`);
        const isHidden = form.classList.contains('hidden');
        
        if (isHidden) {
            form.classList.remove('hidden');
            const textarea = form.querySelector('textarea');
            this.setupTextarea(textarea);
            textarea.focus();
        } else {
            form.classList.add('hidden');
            form.querySelector('textarea').value = '';
        }
    }
    
    setupTextarea(textarea) {
        if (!textarea.value || textarea.value.trim() === '') {
            textarea.value = 'Observación de revisión digital: ';
        }
        
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        this.protectTextarea(textarea);
    }
    
    protectTextarea(textarea) {
        const prefix = 'Observación de revisión digital: ';
        
        if (!textarea.value.includes(prefix)) {
            textarea.value = prefix + textarea.value;
        }
        
        textarea.addEventListener('input', function() {
            if (!this.value.startsWith(prefix)) {
                this.value = prefix + this.value.substring(prefix.length);
            }
        });
        
        textarea.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                if (this.selectionStart <= prefix.length) {
                    e.preventDefault();
                }
            }
        });
    }
    
    async handleSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const documentoId = form.getAttribute('data-documento-id');
        
        // Evitar múltiples requests simultáneos
        if (this.processingDocuments.has(documentoId)) {
            return;
        }
        
        const textarea = form.querySelector('textarea[name="comentario"]');
        const decisionRadio = form.querySelector('input[name="decision_documento"]:checked');
        
        if (!decisionRadio) {
            this.showError('Debe seleccionar si aprueba o rechaza el documento');
            return;
        }
        
        const comentario = textarea.value;
        const aprobado = decisionRadio.value === '1';
        
        // Marcar como procesando
        this.processingDocuments.add(documentoId);
        this.showLoading(documentoId);
        
        try {
            // Guardar comentario y estado en una sola operación
            const response = await this.saveDocumentoReview(documentoId, comentario, aprobado);
            
            if (response.success) {
                // Actualizar UI
                this.updateDocumentUI(documentoId, aprobado);
                this.toggleComment(documentoId);
                this.showSuccess('Documento actualizado correctamente');
            } else {
                throw new Error(response.message || 'Error al guardar');
            }
            
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error al guardar la revisión del documento');
        } finally {
            // Remover del procesamiento
            this.processingDocuments.delete(documentoId);
            this.hideLoading(documentoId);
        }
    }
    
    async saveDocumentoReview(documentoId, comentario, aprobado) {
        const response = await fetch(`/revision/documento/${documentoId}/completo`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({ 
                comentario,
                aprobado 
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }
    
    showLoading(documentoId) {
        const form = document.getElementById(`comment-form-${documentoId}`);
        if (!form) return;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Guardando...
            `;
        }
    }
    
    hideLoading(documentoId) {
        const form = document.getElementById(`comment-form-${documentoId}`);
        if (!form) return;
        
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Guardar';
        }
    }
    
    updateDocumentUI(documentoId, aprobado) {
        const documentoDiv = document.querySelector(`[data-documento-id="${documentoId}"]`);
        if (!documentoDiv) return;
        
        const estadoSpan = documentoDiv.querySelector('.estado-documento');
        if (!estadoSpan) return;
        
        const estados = {
            true: {
                className: 'estado-documento inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 sm:px-2',
                html: `
                    <svg class="w-2.5 h-2.5 mr-0.5 sm:w-3 sm:h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="hidden sm:inline">Aprobado</span>
                    <span class="sm:hidden">OK</span>
                `
            },
            false: {
                className: 'estado-documento inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 sm:px-2',
                html: `
                    <svg class="w-2.5 h-2.5 mr-0.5 sm:w-3 sm:h-3 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="hidden sm:inline">Rechazado</span>
                    <span class="sm:hidden">X</span>
                `
            }
        };
        
        const estado = estados[aprobado];
        estadoSpan.className = estado.className;
        estadoSpan.innerHTML = estado.html;
    }
    
    showSuccess(message) {
        // Crear notificación de éxito
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    showError(message) {
        // Crear notificación de error
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.documentoReview = new DocumentoReview();
});

// Funciones globales para compatibilidad
function toggleDocumentComment(documentoId) {
    if (window.documentoReview) {
        window.documentoReview.toggleComment(documentoId);
    }
} 