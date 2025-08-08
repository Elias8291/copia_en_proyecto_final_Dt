class CargarEstadosArchivos {
    constructor(tramiteId) {
        this.tramiteId = tramiteId;
        this.init();
    }

    init() {
        this.cargarEstadosArchivos();
    }

    async cargarEstadosArchivos() {
        try {
            const response = await fetch(`/archivos/tramite/${this.tramiteId}`);
            const data = await response.json();
            
            if (data.success) {
                this.actualizarEstadosArchivos(data.data);
            }
        } catch (error) {
            console.error('Error al cargar estados de archivos:', error);
        }
    }

    actualizarEstadosArchivos(archivos) {
        archivos.forEach(archivo => {
            this.actualizarArchivo(archivo.id, {
                estado: archivo.status,
                comentario: archivo.comentario_revision,
                fecha_revision: archivo.fecha_revision,
                revisor: archivo.revisor ? archivo.revisor.name : null
            });
        });
    }

    actualizarArchivo(archivoId, datos) {
        const textareaEl = document.getElementById(`textarea_archivo_${archivoId}`);
        const decisionEl = document.getElementById(`decision_archivo_${archivoId}`);
        const comentarioEl = document.getElementById(`comentario_archivo_${archivoId}`);
        const estadoEl = document.getElementById(`estado_archivo_${archivoId}`);
        
        if (textareaEl && datos.comentario) {
            textareaEl.value = datos.comentario;
            textareaEl.placeholder = `Comentario anterior: ${datos.comentario}`;
        }
        
        if (decisionEl) decisionEl.value = datos.estado;
        if (comentarioEl) comentarioEl.value = datos.comentario || '';
        
        if (estadoEl) {
            estadoEl.textContent = datos.estado;
            estadoEl.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
            
            if (datos.estado === 'Aprobado') {
                estadoEl.classList.add('bg-green-100', 'text-green-800');
            } else if (datos.estado === 'Rechazado') {
                estadoEl.classList.add('bg-red-100', 'text-red-800');
            } else {
                estadoEl.classList.add('bg-gray-100', 'text-gray-600');
            }
            
            if (datos.ya_evaluada) {
                estadoEl.innerHTML += ' <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
            }
        }
    }

    recargarDatos() {
        this.cargarEstadosArchivos();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    if (tramiteId) {
        window.cargarEstadosArchivos = new CargarEstadosArchivos(tramiteId);
    }
}); 