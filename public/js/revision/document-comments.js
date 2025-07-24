/**
 * Manejo de comentarios de documentos
 */

class DocumentComments {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Bind toggle comment sections
        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="toggleCommentSection"]')) {
                e.preventDefault();
                const button = e.target.closest('button');
                const documentId = this.extractDocumentId(button.getAttribute('onclick'));
                this.toggleCommentSection(documentId);
            }
        });

        // Bind approve/reject buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="approveDocument"]')) {
                e.preventDefault();
                const button = e.target.closest('button');
                const onclickAttr = button.getAttribute('onclick');
                const matches = onclickAttr.match(/approveDocument\('([^']+)',\s*(true|false)\)/);
                if (matches) {
                    const documentId = matches[1];
                    const approved = matches[2] === 'true';
                    this.approveDocument(documentId, approved);
                }
            }
        });

        // Bind comment form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.getAttribute('onsubmit')?.includes('submitComment')) {
                e.preventDefault();
                const form = e.target;
                const documentId = this.extractDocumentIdFromForm(form);
                this.submitComment(e, documentId);
            }
        });
    }

    /**
     * Mostrar/ocultar la sección de comentarios
     */
    toggleCommentSection(documentId) {
        const section = document.getElementById(`commentSection-${documentId}`);
        if (section) {
            section.classList.toggle('hidden');
            
            // Si se está mostrando, hacer scroll hacia la sección
            if (!section.classList.contains('hidden')) {
                section.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    }

    /**
     * Aprobar/rechazar documento directamente
     */
    async approveDocument(documentId, approved) {
        const action = approved ? 'aprobar' : 'rechazar';
        const message = approved ? '¿Estás seguro de aprobar este documento?' : '¿Estás seguro de rechazar este documento?';
        
        if (confirm(message)) {
            try {
                await this.updateDocumentStatus(documentId, approved ? 'aprobado' : 'rechazado', '');
            } catch (error) {
                console.error('Error:', error);
                this.showError('Error al procesar la solicitud');
            }
        }
    }

    /**
     * Enviar comentario de documento
     */
    async submitComment(event, documentId) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const comentario = formData.get('comentario');
        const decision = formData.get('decision');
        
        if (!comentario.trim()) {
            this.showError('Por favor, escribe un comentario.');
            return;
        }
        
        try {
            const response = await this.saveDocumentComment(documentId, comentario, decision);
            
            if (response.success) {
                // Limpiar formulario
                form.reset();
                form.querySelector('input[value="pendiente"]').checked = true;
                
                // Ocultar sección de comentarios
                this.toggleCommentSection(documentId);
                
                // Agregar comentario a la lista
                this.addCommentToList(documentId, {
                    usuario: response.usuario || 'Revisor',
                    comentario: comentario,
                    decision: decision,
                    fecha: new Date().toLocaleString('es-ES')
                });
                
                // Si hay una decisión, actualizar el estado del documento
                if (decision !== 'pendiente') {
                    await this.updateDocumentStatus(documentId, decision === 'aprobar' ? 'aprobado' : 'rechazado', comentario);
                }
                
                this.showSuccess('Comentario guardado exitosamente');
            } else {
                this.showError('Error al guardar el comentario: ' + (response.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error al procesar la solicitud');
        }
    }

    /**
     * Actualizar el estado del documento
     */
    async updateDocumentStatus(documentId, status, comment) {
        const response = await fetch(`/revision/documento/${documentId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            },
            body: JSON.stringify({
                status: status,
                comment: comment
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Actualizar la UI para reflejar el cambio
            location.reload(); // O actualizar dinámicamente
        } else {
            this.showError('Error al actualizar el estado del documento: ' + (data.message || 'Error desconocido'));
        }
    }

    /**
     * Guardar comentario de documento
     */
    async saveDocumentComment(documentId, comment, decision) {
        const response = await fetch(`/revision/documento/${documentId}/add-comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            },
            body: JSON.stringify({
                comment: comment,
                decision: decision
            })
        });
        
        return await response.json();
    }

    /**
     * Agregar comentario a la lista dinámicamente
     */
    addCommentToList(documentId, commentData) {
        const commentsList = document.getElementById(`commentsList-${documentId}`);
        if (!commentsList) return;
        
        // Remover mensaje de "no hay comentarios" si existe
        const noCommentsMsg = commentsList.querySelector('.italic');
        if (noCommentsMsg) {
            noCommentsMsg.remove();
        }
        
        // Crear elemento del comentario
        const commentElement = this.createCommentElement(commentData);
        
        // Agregar al inicio de la lista
        commentsList.insertBefore(commentElement, commentsList.firstChild);
    }

    /**
     * Crear elemento HTML del comentario
     */
    createCommentElement(commentData) {
        const commentElement = document.createElement('div');
        commentElement.className = 'bg-gray-50 rounded-lg p-3 border border-gray-200';
        
        const decisionBadge = this.getDecisionBadge(commentData.decision);
        
        commentElement.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-medium text-gray-900">${commentData.usuario}</span>
                    ${decisionBadge}
                </div>
                <span class="text-xs text-gray-500">${commentData.fecha}</span>
            </div>
            <p class="text-xs text-gray-700">${commentData.comentario}</p>
        `;
        
        return commentElement;
    }

    /**
     * Obtener badge de decisión
     */
    getDecisionBadge(decision) {
        const badges = {
            'aprobar': `
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check mr-1"></i>Aprobado
                </span>
            `,
            'rechazar': `
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times mr-1"></i>Rechazado
                </span>
            `
        };
        
        return badges[decision] || '';
    }

    /**
     * Utilidades
     */
    extractDocumentId(onclickString) {
        const match = onclickString.match(/'([^']+)'/);
        return match ? match[1] : null;
    }

    extractDocumentIdFromForm(form) {
        const onsubmitAttr = form.getAttribute('onsubmit');
        const match = onsubmitAttr.match(/'([^']+)'/);
        return match ? match[1] : null;
    }

    getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new DocumentComments();
});