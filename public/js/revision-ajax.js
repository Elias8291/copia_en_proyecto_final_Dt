// Revision AJAX Functions
class RevisionAjax {
    constructor() {
        this.baseUrl = '/revisiones';
        this.tramiteId = null;
        this.init();
    }

    init() {
        const metaTramite = document.querySelector('meta[name="tramite-id"]');
        if (metaTramite) {
            this.tramiteId = metaTramite.getAttribute('content');
        }
    }

    // Comentarios de sección
    async guardarComentarioSeccion(seccionId, comentario) {
        if (!this.tramiteId) return false;
        
        try {
            const response = await fetch(`${this.baseUrl}/${this.tramiteId}/seccion/${seccionId}/comentario`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comentario })
            });
            
            return response.ok;
        } catch (error) {
            console.error('Error al guardar comentario:', error);
            return false;
        }
    }

    // Evaluación de sección
    async evaluarSeccion(seccionId, estado) {
        if (!this.tramiteId) return false;
        
        try {
            const response = await fetch(`${this.baseUrl}/${this.tramiteId}/seccion/${seccionId}/evaluar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ estado })
            });
            
            return response.ok;
        } catch (error) {
            console.error('Error al evaluar sección:', error);
            return false;
        }
    }

    // Comentarios de documento
    async guardarComentarioDocumento(archivoId, comentario) {
        if (!this.tramiteId) return false;
        
        try {
            const response = await fetch(`${this.baseUrl}/${this.tramiteId}/documento/${archivoId}/comentario`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comentario })
            });
            
            return response.ok;
        } catch (error) {
            console.error('Error al guardar comentario documento:', error);
            return false;
        }
    }

    // Evaluación de documento
    async evaluarDocumento(archivoId, estado) {
        if (!this.tramiteId) return false;
        
        try {
            const response = await fetch(`${this.baseUrl}/${this.tramiteId}/documento/${archivoId}/evaluar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ estado })
            });
            
            return response.ok;
        } catch (error) {
            console.error('Error al evaluar documento:', error);
            return false;
        }
    }

    // Agendar cita
    async agendarCita(fecha, hora) {
        if (!this.tramiteId) return false;
        
        try {
            const response = await fetch(`${this.baseUrl}/${this.tramiteId}/agendar-cita`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ fecha, hora })
            });
            
            return response.ok;
        } catch (error) {
            console.error('Error al agendar cita:', error);
            return false;
        }
    }

    // Finalizar revisión
    async finalizarRevision(decision, comentarioGeneral = '') {
        if (!this.tramiteId) return false;
        
        try {
            const response = await fetch(`${this.baseUrl}/${this.tramiteId}/finalizar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ decision, comentario_general: comentarioGeneral })
            });
            
            return response.ok;
        } catch (error) {
            console.error('Error al finalizar revisión:', error);
            return false;
        }
    }
}

// Instancia global
window.revisionAjax = new RevisionAjax(); 