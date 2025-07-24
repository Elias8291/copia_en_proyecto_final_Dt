/**
 * Manejo de comentarios generales del trámite
 */

class GeneralComments {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Bind form submission
        const form = document.getElementById('generalCommentForm');
        if (form) {
            form.addEventListener('submit', (e) => this.submitGeneralComment(e));
        }
    }

    /**
     * Enviar comentario general
     */
    async submitGeneralComment(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const comentario = formData.get('comentario');
        const decision = formData.get('decision');
        
        if (!comentario.trim()) {
            this.showError('Por favor, escribe un comentario.');
            return;
        }
        
        // Mostrar indicador de carga
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        this.setButtonLoading(submitBtn, true);
        
        try {
            const response = await this.sendComment(comentario, decision);
            
            if (response.success) {
                // Limpiar formulario
                form.reset();
                form.querySelector('input[value="comentar"]').checked = true;
                
                // Mostrar mensaje de éxito
                this.showSuccess('Comentario guardado exitosamente');
                
                // Si hay una decisión que cambia el estado, recargar la página
                if (decision !== 'comentar') {
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    // Agregar comentario a la lista dinámicamente
                    this.addCommentToList({
                        usuario: response.usuario || 'Revisor',
                        comentario: comentario,
                        decision: decision,
                        fecha: new Date().toLocaleString('es-ES')
                    });
                }
            } else {
                this.showError('Error al guardar el comentario: ' + (response.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error al procesar la solicitud');
        } finally {
            // Restaurar botón
            this.setButtonLoading(submitBtn, false, originalText);
        }
    }

    /**
     * Enviar comentario al servidor
     */
    async sendComment(comentario, decision) {
        const tramiteId = this.getTramiteId();
        const response = await fetch(`/revision/${tramiteId}/add-general-comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            },
            body: JSON.stringify({
                comment: comentario,
                decision: decision
            })
        });
        
        return await response.json();
    }

    /**
     * Agregar comentario general a la lista
     */
    addCommentToList(commentData) {
        // Buscar o crear la sección de comentarios anteriores
        let commentsSection = document.querySelector('.space-y-3');
        if (!commentsSection) {
            // Crear la sección si no existe
            const form = document.getElementById('generalCommentForm');
            const newSection = document.createElement('div');
            newSection.className = 'mb-6';
            newSection.innerHTML = `
                <h4 class="text-sm font-medium text-gray-700 mb-3">Comentarios anteriores:</h4>
                <div class="space-y-3"></div>
            `;
            form.parentNode.insertBefore(newSection, form);
            commentsSection = newSection.querySelector('.space-y-3');
        }
        
        // Crear elemento del comentario
        const commentElement = this.createCommentElement(commentData);
        
        // Agregar al inicio de la lista
        commentsSection.insertBefore(commentElement, commentsSection.firstChild);
    }

    /**
     * Crear elemento HTML del comentario
     */
    createCommentElement(commentData) {
        const commentElement = document.createElement('div');
        commentElement.className = 'bg-gray-50 rounded-lg p-4 border border-gray-200';
        
        const decisionBadge = this.getDecisionBadge(commentData.decision);
        
        commentElement.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-900">${commentData.usuario}</span>
                    ${decisionBadge}
                </div>
                <span class="text-xs text-gray-500">${commentData.fecha}</span>
            </div>
            <p class="text-sm text-gray-700">${commentData.comentario}</p>
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
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Aprobado
                </span>
            `,
            'rechazar': `
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Rechazado
                </span>
            `,
            'corregir': `
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Correcciones
                </span>
            `
        };
        
        return badges[decision] || '';
    }

    /**
     * Utilidades
     */
    getTramiteId() {
        // Extraer ID del trámite de la URL o elemento DOM
        const pathParts = window.location.pathname.split('/');
        return pathParts[pathParts.indexOf('revision') + 1];
    }

    getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    setButtonLoading(button, loading, originalText = 'Guardar Comentario') {
        if (loading) {
            button.disabled = true;
            button.textContent = 'Guardando...';
        } else {
            button.disabled = false;
            button.textContent = originalText;
        }
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Mostrar notificación
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Ocultar después de 3 segundos
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
    new GeneralComments();
});