class ArchivosEvaluacion {
    constructor() {
        this.init();
    }

    init() {
        this.cargarEstadosArchivos();
    }

    async cargarEstadosArchivos() {
        const tramiteId = this.obtenerTramiteId();
        if (!tramiteId) return;

        try {
            const response = await fetch(`/archivos/tramite/${tramiteId}`);
            const data = await response.json();

            if (data.success && data.data) {
                data.data.forEach(archivo => {
                    this.actualizarEstadoArchivo(archivo.id, archivo.status, archivo.comentario_revision);
                });
                
                // Cargar estado de la sección de archivos
                await this.cargarEstadoSeccionArchivos();
                
                // Evaluar automáticamente la sección
                this.evaluarSeccionDocumentos();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async cargarEstadoSeccionArchivos() {
        const tramiteId = this.obtenerTramiteId();
        if (!tramiteId) return;

        try {
            const response = await fetch(`/revisiones/${tramiteId}/seccion/estado?seccion=archivos`);
            const data = await response.json();
            
            if (data.evaluada) {
                const comentarioField = document.getElementById('comentario_archivos');
                if (comentarioField && data.comentario) {
                    comentarioField.value = data.comentario;
                }
                
                const sectionElement = document.querySelector('[data-section="archivos"]');
                if (sectionElement) {
                    sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
                    sectionElement.classList.add(data.estado === 'Aprobado' ? 'seccion-aprobada' : 'seccion-rechazada');
                }
                
                const estadoEl = document.getElementById('estado_archivos');
                if (estadoEl) {
                    estadoEl.textContent = data.estado;
                    estadoEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        data.estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                        data.estado === 'Rechazado' ? 'bg-red-100 text-red-800' : 
                        'bg-gray-100 text-gray-600'
                    }`;
                }
            }
        } catch (error) {
            console.error('Error al cargar estado de sección archivos:', error);
        }
    }

    actualizarEstadoArchivo(archivoId, status, comentario) {
        const estadoEl = document.getElementById(`estado_archivo_${archivoId}`);
        const textarea = document.getElementById(`textarea_archivo_${archivoId}`);
        
        if (estadoEl) {
            estadoEl.textContent = status;
            estadoEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                status === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                status === 'Rechazado' ? 'bg-red-100 text-red-800' : 
                'bg-gray-100 text-gray-600'
            }`;
        }
        
        if (textarea && comentario) {
            textarea.value = comentario;
        }
    }

    async evaluarArchivo(archivoId, decision) {
        const textarea = document.getElementById(`textarea_archivo_${archivoId}`);
        const comentario = textarea ? textarea.value.trim() : '';
        
        this.actualizarEstadoArchivo(archivoId, decision, comentario);
        
        try {
            const response = await fetch(`/archivos/${archivoId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ status: decision, comentario_revision: comentario })
            });
            
            if (!response.ok) throw new Error('Error en la petición');
            
            this.evaluarSeccionDocumentos();
            
        } catch (error) {
            console.error('Error:', error);
        }
    }

    evaluarSeccionDocumentos() {
        const archivos = document.querySelectorAll('[id^="estado_archivo_"]');
        const estados = Array.from(archivos).map(el => el.textContent.trim());
        
        let decisionSeccion = 'Aprobado';
        let comentarioSeccion = 'Todos los documentos están correctos';
        
        if (estados.includes('Rechazado')) {
            decisionSeccion = 'Rechazado';
            comentarioSeccion = 'Algunos documentos requieren corrección';
        } else if (estados.includes('Pendiente')) {
            decisionSeccion = 'Pendiente';
            comentarioSeccion = 'Faltan documentos por revisar';
        }
        
        // Usar la función global que ya existe
        if (typeof evaluarSeccion === 'function') {
            evaluarSeccion('archivos', decisionSeccion);
        }
        
        // Actualizar comentario de la sección
        const comentarioField = document.getElementById('comentario_archivos');
        if (comentarioField) {
            comentarioField.value = comentarioSeccion;
        }
    }

    obtenerTramiteId() {
        return document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.archivosEvaluacion = new ArchivosEvaluacion();
});

window.evaluarArchivo = (archivoId, decision) => {
    return window.archivosEvaluacion?.evaluarArchivo(archivoId, decision);
}; 