class ArchivosEvaluacion {
    constructor() {
        this.init();
    }

    init() {
        this.cargarEstadoSeccion();
        this.configurarEventListeners();
    }

    configurarEventListeners() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-archivo-action]')) {
                const archivoId = e.target.getAttribute('data-archivo-id');
                const action = e.target.getAttribute('data-archivo-action');
                
                if (archivoId && action) {
                    this.evaluarArchivo(archivoId, action);
                }
            }
        });
    }

    async cargarEstadoSeccion() {
        const tramiteId = this.obtenerTramiteId();
        if (!tramiteId) return;

        try {
            const response = await fetch(`/revisiones/${tramiteId}/seccion/estado?seccion=archivos`);
            const data = await response.json();
            
            const comentarioField = document.getElementById('comentario_archivos');
            if (comentarioField && data.comentario) {
                comentarioField.value = data.comentario;
            }
            
            const sectionElement = document.querySelector('[data-section="archivos"]');
            if (sectionElement) {
                sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada', 'seccion-pendiente');
                
                if (data.evaluada) {
                    if (data.estado === 'Aprobado') {
                        sectionElement.classList.add('seccion-aprobada');
                    } else if (data.estado === 'Rechazado') {
                        sectionElement.classList.add('seccion-rechazada');
                    } else if (data.estado === 'Pendiente') {
                        sectionElement.classList.add('seccion-pendiente');
                    }
                } else {
                    sectionElement.classList.add('seccion-pendiente');
                }
            }
            
            const estadoEl = document.getElementById('estado_archivos');
            if (estadoEl) {
                if (data.evaluada) {
                    estadoEl.textContent = data.estado;
                    estadoEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        data.estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                        data.estado === 'Rechazado' ? 'bg-red-100 text-red-800' : 
                        data.estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-gray-100 text-gray-600'
                    }`;
                } else {
                    estadoEl.textContent = 'Pendiente';
                    estadoEl.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
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
                status === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
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
            
            // Solo actualizar el estado visual de la sección, sin enviar peticiones adicionales
            this.actualizarEstadoSeccionVisual();
            
            // Mostrar notificación de éxito
            if (typeof mostrarNotificacion === 'function') {
                mostrarNotificacion(`Archivo evaluado como ${decision}`, 'success');
            }
            
        } catch (error) {
            console.error('Error:', error);
            if (typeof mostrarNotificacion === 'function') {
                mostrarNotificacion('Error al evaluar el archivo', 'error');
            }
        }
    }

    actualizarEstadoSeccionVisual() {
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
    
        const comentarioField = document.getElementById('comentario_archivos');
        if (comentarioField) {
            comentarioField.value = comentarioSeccion;
        }
        
        // Solo actualizar el estado visual, sin enviar peticiones al servidor
        this.actualizarEstadoSeccionManual('archivos', decisionSeccion);
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
    
        const comentarioField = document.getElementById('comentario_archivos');
        if (comentarioField) {
            comentarioField.value = comentarioSeccion;
        }
        
        setTimeout(() => {
            if (typeof evaluarSeccion === 'function') {
                evaluarSeccion('archivos', decisionSeccion);
            } else {
                this.actualizarEstadoSeccionManual('archivos', decisionSeccion);
            }
        }, 100);
    }

    actualizarEstadoSeccionManual(seccion, estado) {
        const sectionElement = document.querySelector(`[data-section="${seccion}"]`);
        const estadoEl = document.getElementById(`estado_${seccion}`);
        
        if (sectionElement) {
            sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada', 'seccion-pendiente');
            if (estado === 'Aprobado') {
                sectionElement.classList.add('seccion-aprobada');
            } else if (estado === 'Rechazado') {
                sectionElement.classList.add('seccion-rechazada');
            } else if (estado === 'Pendiente') {
                sectionElement.classList.add('seccion-pendiente');
            }
        }
        
        if (estadoEl) {
            estadoEl.textContent = estado;
            estadoEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                estado === 'Rechazado' ? 'bg-red-100 text-red-800' : 
                estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                'bg-gray-100 text-gray-600'
            }`;
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