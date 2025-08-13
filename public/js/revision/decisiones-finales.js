class DecisionesFinales {
    constructor() {
        this.tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    async validarSeccionesAprobadas() {
        if (!this.tramiteId) return false;

        try {
            const response = await fetch(`/revisiones/${this.tramiteId}/estado-general`);
            const data = await response.json();
            return data.todas_aprobadas === true;
        } catch (error) {
            console.error('Error al validar secciones:', error);
            return false;
        }
    }

    async aprobarYAgendarCita() {
        const todasAprobadas = await this.validarSeccionesAprobadas();
        
        if (!todasAprobadas) {
            this.mostrarError('No se puede aprobar. Todas las secciones deben estar aprobadas.');
            return;
        }

        this.mostrarConfirmacion(
            'Aprobar y Agendar Cita',
            '¿Aprobar el trámite y agendar cita presencial?',
            () => this.ejecutarDecision('aprobar-y-agendar')
        );
    }

    rechazarParaCorreccion() {
        this.mostrarConfirmacion(
            'Rechazar para Corrección',
            '¿Enviar el trámite para corrección?',
            () => this.ejecutarDecision('rechazar-correccion')
        );
    }

    rechazarTramite() {
        this.mostrarConfirmacion(
            'Rechazar Trámite',
            '¿Rechazar completamente el trámite? Esta acción es irreversible.',
            () => this.ejecutarDecision('rechazar-completo')
        );
    }

    async ejecutarDecision(accion) {
        let comentarioGeneral = document.getElementById('comentario_general')?.value || '';
        
        if (comentarioGeneral === '') {
            comentarioGeneral = null;
        }
        
        try {
            const response = await fetch(`/revisiones/${this.tramiteId}/${accion}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ comentario_general: comentarioGeneral })
            });

            const result = await response.json();

            if (result.success) {
                this.mostrarModalExito(result.message);
            } else {
                this.mostrarError(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al procesar la solicitud');
        }
    }

    mostrarConfirmacion(titulo, mensaje, callback) {
        if (typeof showConfirmModal === 'function') {
            showConfirmModal(titulo, mensaje, null, callback);
        } else {
            if (confirm(`${titulo}: ${mensaje}`)) {
                callback();
            }
        }
    }

    mostrarError(mensaje) {
        if (typeof mostrarError === 'function') {
            mostrarError(mensaje);
        } else {
            alert('Error: ' + mensaje);
        }
    }

    mostrarExito(mensaje) {
        if (typeof mostrarExito === 'function') {
            mostrarExito(mensaje);
        } else {
            alert('Éxito: ' + mensaje);
        }
    }

    mostrarModalExito(mensaje) {
        const modalHtml = `
            <div id="modal-exito-dinamico" class="fixed z-50 inset-0 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                        
                        <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                            <button type="button" onclick="cerrarModalExito()" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    ¡Éxito!
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        ${mensaje}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="button" 
                                    onclick="aceptarExito()"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Ver Trámites
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.decisionesFinales = new DecisionesFinales();
});

function cerrarModalExito() {
    const modal = document.getElementById('modal-exito-dinamico');
    if (modal) {
        modal.remove();
    }
}

function aceptarExito() {
    cerrarModalExito();
    window.location.href = '/revisiones';
}

window.aprobarYAgendarCita = () => window.decisionesFinales?.aprobarYAgendarCita();
window.rechazarParaCorreccion = () => window.decisionesFinales?.rechazarParaCorreccion();
window.rechazarTramite = () => window.decisionesFinales?.rechazarTramite(); 