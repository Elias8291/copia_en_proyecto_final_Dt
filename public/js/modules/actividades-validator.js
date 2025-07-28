/**
 * Módulo para manejar la validación de actividades económicas
 */
class ActividadesValidator {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Los event listeners se configuran automáticamente en los elementos HTML
    }

    /**
     * Buscar actividad en Google
     */
    buscarEnGoogle(termino) {
        const query = encodeURIComponent(termino);
        const url = `https://www.google.com/search?q=${query}`;
        window.open(url, '_blank');
    }

    /**
     * Validar una actividad económica
     */
    async validarActividad(actividadId) {
        if (!actividadId) {
            this.mostrarError('ID de actividad no válido');
            return;
        }

        try {
            const response = await fetch('/api/actividades/validar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    actividad_id: actividadId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.mostrarExito('Actividad validada exitosamente');
                // Recargar la página para mostrar los cambios
                setTimeout(() => location.reload(), 1000);
            } else {
                this.mostrarError('Error al validar la actividad: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al validar la actividad');
        }
    }

    /**
     * Rechazar una actividad económica
     */
    async rechazarActividad(actividadId) {
        if (!actividadId) {
            this.mostrarError('ID de actividad no válido');
            return;
        }

        if (!confirm('¿Está seguro de que desea rechazar esta actividad?')) {
            return;
        }

        try {
            const response = await fetch('/api/actividades/rechazar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    actividad_id: actividadId
                })
            });

            const data = await response.json();

            if (data.success) {
                this.mostrarExito('Actividad rechazada exitosamente');
                // Recargar la página para mostrar los cambios
                setTimeout(() => location.reload(), 1000);
            } else {
                this.mostrarError('Error al rechazar la actividad: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al rechazar la actividad');
        }
    }

    /**
     * Obtener información de una actividad
     */
    async obtenerActividad(actividadId) {
        if (!actividadId) {
            this.mostrarError('ID de actividad no válido');
            return null;
        }

        try {
            const response = await fetch(`/api/actividades/obtener?actividad_id=${actividadId}`, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                return data.actividad;
            } else {
                this.mostrarError('Error al obtener la actividad: ' + data.message);
                return null;
            }
        } catch (error) {
            console.error('Error:', error);
            this.mostrarError('Error al obtener la actividad');
            return null;
        }
    }

    /**
     * Mostrar mensaje de éxito
     */
    mostrarExito(mensaje) {
        // Usar SweetAlert2 si está disponible, sino usar alert
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: mensaje,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert(mensaje);
        }
    }

    /**
     * Mostrar mensaje de error
     */
    mostrarError(mensaje) {
        // Usar SweetAlert2 si está disponible, sino usar alert
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje
            });
        } else {
            alert(mensaje);
        }
    }

    /**
     * Actualizar el estado visual de una actividad
     */
    actualizarEstadoActividad(actividadId, nuevoEstado) {
        const actividadElement = document.querySelector(`[data-actividad-id="${actividadId}"]`);
        if (!actividadElement) return;

        const estadoSpan = actividadElement.querySelector('.inline-flex.items-center');
        if (!estadoSpan) return;

        // Actualizar clases CSS según el estado
        const clases = {
            'Validada': 'bg-green-100 text-green-700',
            'Rechazada': 'bg-red-100 text-red-700',
            'Pendiente': 'bg-yellow-100 text-yellow-700'
        };

        const iconos = {
            'Validada': 'fas fa-check-circle text-green-500',
            'Rechazada': 'fas fa-times-circle text-red-500',
            'Pendiente': 'fas fa-clock text-yellow-500'
        };

        // Remover clases anteriores
        estadoSpan.className = estadoSpan.className.replace(/bg-\w+-100 text-\w+-700/g, '');
        estadoSpan.className = estadoSpan.className.replace(/fas fa-\w+-\w+ text-\w+-500/g, '');

        // Agregar nuevas clases
        estadoSpan.className += ` ${clases[nuevoEstado]}`;
        
        const iconElement = estadoSpan.querySelector('i');
        if (iconElement) {
            iconElement.className = `${iconos[nuevoEstado]} mr-1`;
        }

        // Actualizar texto
        const textElement = estadoSpan.querySelector('span') || estadoSpan;
        textElement.textContent = nuevoEstado;

        // Ocultar botones de acción si la actividad ya no está pendiente
        if (nuevoEstado !== 'Pendiente') {
            const botonesContainer = actividadElement.querySelector('.flex.items-center.space-x-2');
            if (botonesContainer) {
                botonesContainer.style.display = 'none';
            }
        }
    }
}

// Inicializar el módulo cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.actividadesValidator = new ActividadesValidator();
});

// Funciones globales para compatibilidad con el HTML
window.buscarEnGoogle = function(termino) {
    if (window.actividadesValidator) {
        window.actividadesValidator.buscarEnGoogle(termino);
    } else {
        // Fallback si el módulo no está disponible
        const query = encodeURIComponent(termino);
        const url = `https://www.google.com/search?q=${query}`;
        window.open(url, '_blank');
    }
};

window.validarActividad = function(actividadId) {
    if (window.actividadesValidator) {
        window.actividadesValidator.validarActividad(actividadId);
    }
};

window.rechazarActividad = function(actividadId) {
    if (window.actividadesValidator) {
        window.actividadesValidator.rechazarActividad(actividadId);
    }
}; 