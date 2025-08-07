class RevisionDigitalEstados {
    constructor(tramiteId) {
        this.tramiteId = tramiteId;
        this.seccionesEvaluadas = {};
        this.revisionesAnteriores = {};
        this.init();
    }

    async init() {
        try {
            await this.cargarDatos();
            this.actualizarUI();
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al cargar datos');
        }
    }

    async cargarDatos() {
        const response = await fetch(`/api/revisiones/${this.tramiteId}/estados`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const data = await response.json();
        if (!data.success) throw new Error(data.message);
        
        this.seccionesEvaluadas = data.seccionesEvaluadas || {};
        this.revisionesAnteriores = data.revisionesAnteriores || {};
    }

    actualizarUI() {
        Object.keys(this.seccionesEvaluadas).forEach(seccion => {
            this.actualizarSeccion(seccion, this.seccionesEvaluadas[seccion]);
        });
        
        this.actualizarArchivos();
        this.mostrarMensajes();
    }

    actualizarArchivos() {
        if (this.seccionesEvaluadas.archivos && this.seccionesEvaluadas.archivos.archivos_individuales) {
            Object.keys(this.seccionesEvaluadas.archivos.archivos_individuales).forEach(archivoId => {
                this.actualizarArchivo(archivoId, this.seccionesEvaluadas.archivos.archivos_individuales[archivoId]);
            });
        }
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

    actualizarSeccion(seccion, datos) {
        const estadoEl = document.getElementById(`estado_${seccion}`);
        const textareaEl = document.getElementById(`textarea_${seccion}`);
        const decisionEl = document.getElementById(`decision_${seccion}`);
        const comentarioEl = document.getElementById(`comentario_${seccion}`);
        
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
        
        if (textareaEl && datos.comentario) {
            textareaEl.value = datos.comentario;
            textareaEl.placeholder = `Comentario anterior: ${datos.comentario}`;
        }
        
        if (decisionEl) decisionEl.value = datos.estado;
        if (comentarioEl) comentarioEl.value = datos.comentario || '';
        
        if (datos.ya_evaluada && datos.revisor) {
            this.mostrarRevisor(seccion, datos);
        }
    }

    mostrarRevisor(seccion, datos) {
        const infoEl = document.createElement('div');
        infoEl.className = 'text-xs text-gray-500 mt-1';
        infoEl.innerHTML = `Evaluado por: ${datos.revisor} - ${new Date(datos.fecha_revision).toLocaleDateString()}`;
        
        const container = document.querySelector(`[data-seccion="${seccion}"]`);
        if (container) {
            const existing = container.querySelector('.text-xs.text-gray-500');
            if (existing) existing.remove();
            container.appendChild(infoEl);
        }
    }

    mostrarMensajes() {
        const evaluadas = Object.values(this.seccionesEvaluadas).filter(s => s.ya_evaluada);
        
        // Solo mostrar un mensaje general si hay revisiones anteriores
        if (this.revisionesAnteriores.total_revisiones > 0) {
            const ultima = this.revisionesAnteriores.ultima_revision;
            this.insertarMensaje(`
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800">Revisiones Anteriores</h4>
                            <p class="text-sm text-yellow-700">Este trámite ha sido revisado ${this.revisionesAnteriores.total_revisiones} vez(es) anteriormente.</p>
                            ${ultima ? `<div class="text-xs text-yellow-600 mt-1"><strong>Última:</strong> ${ultima.revisor || 'N/A'} - ${new Date(ultima.fecha_inicio).toLocaleDateString()}</div>` : ''}
                            ${evaluadas.length > 0 ? `<div class="text-xs text-yellow-600 mt-1">Se han cargado ${evaluadas.length} secciones evaluadas anteriormente.</div>` : ''}
                        </div>
                    </div>
                </div>
            `);
        }
    }

    insertarMensaje(html) {
        const container = document.querySelector('.max-w-7xl');
        if (container) {
            const div = document.createElement('div');
            div.innerHTML = html;
            container.insertBefore(div.firstElementChild, container.firstChild);
        }
    }

    mostrarError(mensaje) {
        this.insertarMensaje(`
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-red-800">Error</h4>
                        <p class="text-sm text-red-700">${mensaje}</p>
                    </div>
                </div>
            </div>
        `);
    }

    async recargar() {
        try {
            await this.cargarDatos();
            this.actualizarUI();
        } catch (error) {
            console.error('Error al recargar:', error);
            this.mostrarError('Error al recargar datos');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    if (tramiteId) {
        window.revisionDigitalEstados = new RevisionDigitalEstados(tramiteId);
    }
});

window.RevisionDigitalEstados = RevisionDigitalEstados; 
window.RevisionDigitalEstados = RevisionDigitalEstados; 