class EvaluacionSecciones {
    constructor(tramiteId) {
        this.tramiteId = tramiteId;
        this.init();
    }

    init() {
        this.cargarEstadosIniciales();
    }

    async evaluarSeccion(seccion, estado) {
        const comentario = document.getElementById(`comentario_${seccion}`).value;
        
        try {
            const response = await fetch(`/revisiones/${this.tramiteId}/seccion/evaluar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    seccion: seccion,
                    estado: estado,
                    comentario: comentario
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.mostrarNotificacion(`Sección ${seccion} ${estado.toLowerCase()} correctamente`, 'success');
                this.actualizarEstadoSeccion(seccion, data.data);
                this.verificarEstadoTramite();
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarNotificacion('Error al evaluar la sección', 'error');
        }
    }

    async cargarEstadoSeccion(seccion) {
        try {
            const response = await fetch(`/revisiones/${this.tramiteId}/seccion/estado?seccion=${seccion}`);
            const data = await response.json();
            
            if (data.evaluada) {
                this.actualizarEstadoSeccion(seccion, data);
            }
        } catch (error) {
            console.error('Error al cargar estado:', error);
        }
    }

    cargarEstadosIniciales() {
        const secciones = ['datos_generales', 'actividades', 'domicilio'];
        
        if (window.esPersonaMoral) {
            secciones.push('constitucion', 'accionistas', 'apoderado');
        }
        secciones.push('archivos');
        
        secciones.forEach(seccion => {
            this.cargarEstadoSeccion(seccion);
        });
    }

    actualizarEstadoSeccion(seccion, data) {
        const comentarioField = document.getElementById(`comentario_${seccion}`);
        if (comentarioField && data.comentario) {
            comentarioField.value = data.comentario;
        }

        this.mostrarEstadoVisual(seccion, data.estado);
    }

    mostrarEstadoVisual(seccion, estado) {
        const sectionElement = document.querySelector(`[data-section="${seccion}"]`);
        if (!sectionElement) return;

        sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
        
        if (estado === 'Aprobado') {
            sectionElement.classList.add('seccion-aprobada');
        } else if (estado === 'Rechazado') {
            sectionElement.classList.add('seccion-rechazada');
        }
    }

    async verificarEstadoTramite() {
        try {
            const response = await fetch(`/revisiones/${this.tramiteId}/estado-general`);
            const data = await response.json();
            
            this.actualizarEstadoTramite(data);
        } catch (error) {
            console.error('Error al verificar estado del trámite:', error);
        }
    }

    actualizarEstadoTramite(data) {
        const indicador = document.getElementById('estado-tramite-indicador');
        if (indicador) {
            indicador.textContent = `Estado: ${data.estado} (${data.seccionesEvaluadas}/${data.totalSecciones})`;
            indicador.className = `px-3 py-1 rounded-full text-sm font-medium ${this.getEstadoClass(data.estado)}`;
        }
    }

    getEstadoClass(estado) {
        switch (estado) {
            case 'Aprobado': return 'bg-green-100 text-green-800';
            case 'Rechazado': return 'bg-red-100 text-red-800';
            case 'Para_Correccion': return 'bg-yellow-100 text-yellow-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    mostrarNotificacion(mensaje, tipo) {
        const notif = document.createElement('div');
        notif.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${tipo === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        notif.textContent = mensaje;
        document.body.appendChild(notif);
        
        setTimeout(() => {
            notif.remove();
        }, 3000);
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }
}

// Función global para los botones
function evaluarSeccion(seccion, estado) {
    if (window.evaluadorSecciones) {
        window.evaluadorSecciones.evaluarSeccion(seccion, estado);
    }
} 