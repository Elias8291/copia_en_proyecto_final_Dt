/**
 * Manejo de acciones de revisión del trámite
 */

class RevisionActions {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Bind action buttons
        document.addEventListener('click', (e) => {
            const button = e.target.closest('button[type="button"]');
            if (!button) return;

            const buttonText = button.textContent.trim();
            
            if (buttonText.includes('Aprobar Trámite')) {
                e.preventDefault();
                this.handleTramiteAction('aprobar');
            } else if (buttonText.includes('Rechazar Trámite')) {
                e.preventDefault();
                this.handleTramiteAction('rechazar');
            } else if (buttonText.includes('Solicitar Correcciones')) {
                e.preventDefault();
                this.handleTramiteAction('corregir');
            }
        });
    }

    /**
     * Manejar acciones del trámite
     */
    async handleTramiteAction(action) {
        const confirmMessages = {
            'aprobar': '¿Estás seguro de aprobar este trámite?',
            'rechazar': '¿Estás seguro de rechazar este trámite?',
            'corregir': '¿Estás seguro de solicitar correcciones para este trámite?'
        };

        const message = confirmMessages[action];
        if (!confirm(message)) {
            return;
        }

        // Solicitar observaciones si es necesario
        let observaciones = '';
        if (action === 'rechazar' || action === 'corregir') {
            observaciones = prompt('Ingresa las observaciones (opcional):') || '';
        }

        try {
            const response = await this.updateTramiteStatus(action, observaciones);
            
            if (response.success) {
                this.showSuccess(`Trámite ${this.getActionText(action)} exitosamente`);
                
                // Recargar página después de un breve delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                this.showError('Error al actualizar el trámite: ' + (response.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error:', error);
            this.showError('Error al procesar la solicitud');
        }
    }

    /**
     * Actualizar estado del trámite
     */
    async updateTramiteStatus(action, observaciones = '') {
        const tramiteId = this.getTramiteId();
        const estadoMap = {
            'aprobar': 'Aprobado',
            'rechazar': 'Rechazado',
            'corregir': 'Para_Correccion'
        };

        const response = await fetch(`/revision/${tramiteId}/estado`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            },
            body: JSON.stringify({
                estado: estadoMap[action],
                observaciones: observaciones
            })
        });

        return await response.json();
    }

    /**
     * Obtener texto de acción
     */
    getActionText(action) {
        const actionTexts = {
            'aprobar': 'aprobado',
            'rechazar': 'rechazado',
            'corregir': 'enviado para correcciones'
        };
        
        return actionTexts[action] || action;
    }

    /**
     * Utilidades
     */
    getTramiteId() {
        // Extraer ID del trámite de la URL
        const pathParts = window.location.pathname.split('/');
        return pathParts[pathParts.indexOf('revision') + 1];
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
    new RevisionActions();
});