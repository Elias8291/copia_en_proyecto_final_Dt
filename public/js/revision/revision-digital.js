/**
 * Revision Digital - Gestión de revisiones de trámites
 */

class RevisionDigital {
    constructor() {
        this.tramiteId = window.tramiteId;
        this.csrfToken = window.csrfToken;
        this.route = window.revisionSeccionComentarioRoute;
        this.esPersonaMoral = window.esPersonaMoral || false;
        
        this.secciones = ['datos_generales', 'domicilio', 'actividades', 'documentos'];
        if (this.esPersonaMoral) {
            this.secciones.push('constitucion', 'apoderado', 'accionistas');
        }
        
        this.init();
    }
    
    init() {
        this.cargarEstadosExistentes();
        this.setupEventListeners();
    }
    
    setEstadoVisual(seccion, aprobado) {
        const estadoSpan = document.getElementById(`estado_visual_${seccion}`);
        if (!estadoSpan) return;
        
        const estados = {
            true: {
                className: 'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200',
                html: '<div class="w-2 h-2 bg-emerald-500 rounded-full mr-1.5"></div>Aprobado'
            },
            false: {
                className: 'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 border border-red-200',
                html: '<div class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></div>Rechazado'
            },
            null: {
                className: 'inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200',
                html: '<div class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></div>Pendiente'
            }
        };
        
        const estado = estados[aprobado];
        estadoSpan.className = estado.className;
        estadoSpan.innerHTML = estado.html;
    }
    
    setComentarioBox(seccion, comentario, aprobado = null) {
        const box = document.getElementById(`comentario_box_${seccion}`);
        const icono = document.getElementById(`icono_comentario_${seccion}`);
        const texto = document.getElementById(`comentario_texto_${seccion}`);
        
        if (!box || !icono || !texto) return;
        
        if (comentario && comentario.trim() !== '') {
            texto.textContent = comentario;
            box.style.display = 'block';
            
            const estilos = {
                true: {
                    className: 'px-4 py-3 bg-emerald-50 border-l-3 border-emerald-400',
                    icon: '<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                },
                false: {
                    className: 'px-4 py-3 bg-red-50 border-l-3 border-red-400',
                    icon: '<svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                },
                null: {
                    className: 'px-4 py-3 bg-blue-50 border-l-3 border-blue-400',
                    icon: '<svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01"/></svg>'
                }
            };
            
            const estilo = estilos[aprobado];
            box.className = estilo.className;
            icono.innerHTML = estilo.icon;
        } else {
            texto.textContent = '';
            icono.innerHTML = '';
            box.style.display = 'none';
        }
    }
    
    setAprobado(seccion, valor) {
        const hidden = document.getElementById(`aprobado_${seccion}`);
        const btnAprobar = document.getElementById(`btn_aprobar_${seccion}`);
        const btnRechazar = document.getElementById(`btn_rechazar_${seccion}`);
        
        if (!hidden || !btnAprobar || !btnRechazar) return;
        
        const estados = {
            true: {
                value: '1',
                aprobar: { remove: ['bg-white', 'border-emerald-300', 'text-emerald-700', 'hover:bg-emerald-50'], add: ['bg-emerald-600', 'border-emerald-600', 'text-white', 'hover:bg-emerald-700'] },
                rechazar: { add: ['bg-white', 'border-red-300', 'text-red-700', 'hover:bg-red-50'], remove: ['bg-red-600', 'border-red-600', 'text-white', 'hover:bg-red-700'] }
            },
            false: {
                value: '0',
                aprobar: { add: ['bg-white', 'border-emerald-300', 'text-emerald-700', 'hover:bg-emerald-50'], remove: ['bg-emerald-600', 'border-emerald-600', 'text-white', 'hover:bg-emerald-700'] },
                rechazar: { remove: ['bg-white', 'border-red-300', 'text-red-700', 'hover:bg-red-50'], add: ['bg-red-600', 'border-red-600', 'text-white', 'hover:bg-red-700'] }
            },
            null: {
                value: '',
                aprobar: { add: ['bg-white', 'border-emerald-300', 'text-emerald-700', 'hover:bg-emerald-50'], remove: ['bg-emerald-600', 'border-emerald-600', 'text-white', 'hover:bg-emerald-700'] },
                rechazar: { add: ['bg-white', 'border-red-300', 'text-red-700', 'hover:bg-red-50'], remove: ['bg-red-600', 'border-red-600', 'text-white', 'hover:bg-red-700'] }
            }
        };
        
        const estado = estados[valor];
        hidden.value = estado.value;
        
        estado.aprobar.remove.forEach(cls => btnAprobar.classList.remove(cls));
        estado.aprobar.add.forEach(cls => btnAprobar.classList.add(cls));
        
        estado.rechazar.remove.forEach(cls => btnRechazar.classList.remove(cls));
        estado.rechazar.add.forEach(cls => btnRechazar.classList.add(cls));
    }
    
    async guardarComentarioSeccion(seccion) {
        const textarea = document.querySelector(`textarea[data-seccion="${seccion}"]`);
        const comentario = textarea.value;
        const aprobadoHidden = document.getElementById(`aprobado_${seccion}`);
        const aprobado = aprobadoHidden.value === '' ? null : (aprobadoHidden.value === '1');
        
        try {
            const response = await fetch(this.route, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    tramite_id: this.tramiteId,
                    seccion: seccion,
                    comentario: comentario,
                    aprobado: aprobado
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.mostrarMensajeExito(seccion);
                this.setEstadoVisual(seccion, aprobado);
                this.setComentarioBox(seccion, comentario, aprobado);
            } else {
                this.mostrarMensajeError(seccion, 'Error al guardar el comentario');
            }
        } catch (error) {
            this.mostrarMensajeError(seccion, 'Error de conexión');
        }
    }
    
    async cargarEstadoSeccion(seccion) {
        try {
            const response = await fetch(`/revision/seccion/${this.tramiteId}/${seccion}`);
            const data = await response.json();
            
            if (data.success && data.data) {
                const textarea = document.querySelector(`textarea[data-seccion="${seccion}"]`);
                if (textarea && data.data.comentario) {
                    textarea.value = data.data.comentario;
                }
                
                this.setComentarioBox(seccion, data.data.comentario, data.data.aprobado);
                this.setAprobado(seccion, data.data.aprobado);
                this.setEstadoVisual(seccion, data.data.aprobado);
            }
        } catch (error) {
            console.error('Error cargando sección', seccion, ':', error);
        }
    }
    
    mostrarMensajeExito(seccion) {
        const estadoDiv = document.getElementById(`estado_comentario_${seccion}`);
        if (!estadoDiv) return;
        
        estadoDiv.className = 'text-sm text-center font-medium text-emerald-600';
        estadoDiv.innerHTML = `
            <div class="inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Comentario guardado correctamente
            </div>
        `;
        
        setTimeout(() => {
            estadoDiv.innerHTML = '';
        }, 3000);
    }
    
    mostrarMensajeError(seccion, mensaje) {
        const estadoDiv = document.getElementById(`estado_comentario_${seccion}`);
        if (!estadoDiv) return;
        
        estadoDiv.className = 'text-sm text-center font-medium text-red-600';
        estadoDiv.innerHTML = `
            <div class="inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                ${mensaje}
            </div>
        `;
    }
    
    obtenerEstadoSecciones() {
        const estado = {
            seccionesPendientes: [],
            seccionesAprobadas: [],
            seccionesRechazadas: []
        };
        
        this.secciones.forEach(seccion => {
            const aprobadoHidden = document.getElementById(`aprobado_${seccion}`);
            if (!aprobadoHidden || aprobadoHidden.value === '') {
                estado.seccionesPendientes.push(seccion);
            } else if (aprobadoHidden.value === '1') {
                estado.seccionesAprobadas.push(seccion);
            } else if (aprobadoHidden.value === '0') {
                estado.seccionesRechazadas.push(seccion);
            }
        });
        
        return estado;
    }
    
    validarEnviarACotejo() {
        const estado = this.obtenerEstadoSecciones();
        
        if (estado.seccionesPendientes.length > 0) {
            this.mostrarAlertaSeccionesPendientes(estado.seccionesPendientes);
            return false;
        }
        
        if (estado.seccionesRechazadas.length > 0) {
            this.mostrarAlertaError('No se puede enviar a cotejo', 
                `No se puede enviar a cotejo presencial porque hay secciones rechazadas. Debe aprobar todas las secciones o rechazar el trámite.`);
            return false;
        }
        
        return true;
    }
    
    validarRechazarTramite() {
        const estado = this.obtenerEstadoSecciones();
        
        if (estado.seccionesRechazadas.length === 0) {
            this.mostrarAlertaError('No se puede rechazar el trámite', 
                'No se puede rechazar el trámite porque todas las secciones están aprobadas.');
            return false;
        }
        
        const comentarioGeneral = document.getElementById('comentario_general').value.trim();
        if (!comentarioGeneral) {
            this.mostrarAlertaError('Comentario requerido', 
                'Debe agregar un comentario general explicando por qué rechaza el trámite.');
            return false;
        }
        
        return true;
    }
    
    validarParaCorreccion() {
        const estado = this.obtenerEstadoSecciones();
        
        if (estado.seccionesPendientes.length === 0 && estado.seccionesRechazadas.length === 0) {
            this.mostrarAlertaError('No se puede enviar para corrección', 
                'No se puede enviar para corrección porque todas las secciones están aprobadas.');
            return false;
        }
        
        const comentarioGeneral = document.getElementById('comentario_general').value.trim();
        if (!comentarioGeneral) {
            this.mostrarAlertaError('Comentario requerido', 
                'Debe agregar un comentario general explicando qué correcciones necesita el trámite.');
            return false;
        }
        
        return true;
    }
    
    mostrarAlertaSeccionesPendientes(seccionesPendientes) {
        const nombresSecciones = {
            'datos_generales': 'Datos Generales',
            'domicilio': 'Domicilio',
            'actividades': 'Actividades',
            'documentos': 'Documentos',
            'constitucion': 'Constitución',
            'apoderado': 'Apoderado Legal',
            'accionistas': 'Accionistas'
        };
        
        const seccionesNombres = seccionesPendientes.map(seccion => nombresSecciones[seccion] || seccion);
        this.mostrarAlertaError('Secciones Pendientes de Revisión', 
            `Debe revisar todas las secciones antes de continuar. Secciones pendientes: ${seccionesNombres.join(', ')}`);
    }
    
    mostrarAlertaError(titulo, mensaje) {
        if (window.modalError) {
            window.modalError.show(titulo, mensaje);
        } else {
            const modal = document.getElementById('modal-error-custom');
            const titleElement = modal.querySelector('[data-modal-title]');
            const messageElement = modal.querySelector('[data-modal-message]');
            
            if (titleElement) titleElement.textContent = titulo;
            if (messageElement) messageElement.textContent = mensaje;
            
            modal.classList.remove('hidden');
        }
    }
    
    cargarEstadosExistentes() {
        this.secciones.forEach(seccion => {
            this.cargarEstadoSeccion(seccion);
        });
    }
    
    setupEventListeners() {
        // Event listeners se configuran desde la vista principal
    }
    
    // Métodos estáticos para compatibilidad
    static setEstadoVisual(seccion, aprobado) {
        if (window.revisionDigital) {
            window.revisionDigital.setEstadoVisual(seccion, aprobado);
        }
    }
    
    static setComentarioBox(seccion, comentario, aprobado = null) {
        if (window.revisionDigital) {
            window.revisionDigital.setComentarioBox(seccion, comentario, aprobado);
        }
    }
    
    static setAprobado(seccion, valor) {
        if (window.revisionDigital) {
            window.revisionDigital.setAprobado(seccion, valor);
        }
    }
    
    static guardarComentarioSeccion(seccion) {
        if (window.revisionDigital) {
            window.revisionDigital.guardarComentarioSeccion(seccion);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.revisionDigital = new RevisionDigital();
});

// Funciones globales para compatibilidad
function setEstadoVisual(seccion, aprobado) {
    RevisionDigital.setEstadoVisual(seccion, aprobado);
}

function setComentarioBox(seccion, comentario, aprobado = null) {
    RevisionDigital.setComentarioBox(seccion, comentario, aprobado);
}

function setAprobado(seccion, valor) {
    RevisionDigital.setAprobado(seccion, valor);
}

function guardarComentarioSeccion(seccion) {
    RevisionDigital.guardarComentarioSeccion(seccion);
} 

