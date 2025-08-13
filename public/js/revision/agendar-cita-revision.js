/**
 * Módulo para gestionar el agendamiento de citas de revisión
 */
class AgendarCitaRevision {
    constructor() {
        this.modal = null;
        this.revisoresData = [];
        this.revisorSeleccionado = null;
        this.tramiteId = null;
        
        this.init();
    }

    init() {
        this.modal = document.getElementById('modal-agendar-cita');
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Cambio de tipo de revisión
        document.querySelectorAll('input[name="tipo_revision"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.cargarRevisores(e.target.value);
            });
        });

        // Submit del formulario
        const form = document.getElementById('form-agendar-cita');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.procesarAgendamiento();
            });
        }

        // Cambio de fecha para verificar disponibilidad
        const fechaInput = document.getElementById('fecha-cita');
        if (fechaInput) {
            fechaInput.addEventListener('change', () => {
                this.actualizarHorariosDisponibles();
            });
        }
    }

    /**
     * Abrir modal para agendar cita
     */
    abrirModal(tramiteId) {
        this.tramiteId = tramiteId;
        document.getElementById('tramite-id-input').value = tramiteId;
        
        // Mostrar modal
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Cargar revisores por defecto (Digital)
        this.cargarRevisores('Digital');
    }

    /**
     * Cerrar modal
     */
    cerrarModal() {
        this.modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        this.limpiarFormulario();
    }

    /**
     * Cargar revisores según el tipo de revisión
     */
    async cargarRevisores(tipoRevision) {
        const container = document.getElementById('lista-revisores');
        
        // Mostrar loading
        container.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#9d2449]"></div>
                <span class="ml-2 text-gray-600">Cargando revisores...</span>
            </div>
        `;

        try {
            const response = await fetch('/api/revisores/disponibles', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    tipo_revision: tipoRevision,
                    tramite_id: this.tramiteId
                })
            });

            const data = await response.json();
            
            if (data.success) {
                this.revisoresData = data.revisores;
                this.renderizarRevisores();
            } else {
                this.mostrarErrorRevisores(data.message);
            }
        } catch (error) {
            console.error('Error al cargar revisores:', error);
            this.mostrarErrorRevisores('Error al cargar los revisores disponibles');
        }
    }

    /**
     * Renderizar lista de revisores
     */
    renderizarRevisores() {
        const container = document.getElementById('lista-revisores');
        
        if (this.revisoresData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <p class="font-medium">No hay revisores disponibles</p>
                    <p class="text-sm">Intente con otro tipo de revisión</p>
                </div>
            `;
            return;
        }

        const revisoresHTML = this.revisoresData.map(revisor => {
            const distanciaClass = this.obtenerClaseDistancia(revisor.categoria_distancia);
            const disponibilidadClass = this.obtenerClaseDisponibilidad(revisor.disponibilidad);
            
            return `
                <div class="revisor-card border border-gray-200 rounded-lg p-4 cursor-pointer hover:shadow-md transition-all duration-200"
                     onclick="agendarCitaRevision.seleccionarRevisor(${revisor.id})"
                     data-revisor-id="${revisor.id}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-sm font-semibold text-gray-600">${revisor.nombre.charAt(0)}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">${revisor.nombre}</h4>
                                <p class="text-sm text-gray-600">${revisor.correo}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="distancia-badge ${distanciaClass}">
                                        📍 ${revisor.distancia_texto}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        ${revisor.citas_pendientes} citas pendientes
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-1 mb-1">
                                <div class="w-2 h-2 rounded-full ${disponibilidadClass}"></div>
                                <span class="text-xs text-gray-600 capitalize">${revisor.disponibilidad}</span>
                            </div>
                            <p class="text-xs text-gray-500">
                                ${revisor.proximas_citas.length} horarios disponibles
                            </p>
                        </div>
                    </div>
                    
                    <!-- Próximas citas disponibles -->
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-700 mb-2">Próximos horarios:</p>
                        <div class="flex flex-wrap gap-1">
                            ${revisor.proximas_citas.slice(0, 4).map(cita => 
                                `<span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                    ${cita.fecha_formateada} ${cita.hora_formateada}
                                </span>`
                            ).join('')}
                            ${revisor.proximas_citas.length > 4 ? 
                                `<span class="text-xs text-gray-500">+${revisor.proximas_citas.length - 4} más</span>` 
                                : ''
                            }
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = revisoresHTML;
    }

    /**
     * Seleccionar revisor
     */
    seleccionarRevisor(revisorId) {
        // Remover selección anterior
        document.querySelectorAll('.revisor-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Agregar selección actual
        const card = document.querySelector(`[data-revisor-id="${revisorId}"]`);
        if (card) {
            card.classList.add('selected');
        }

        this.revisorSeleccionado = this.revisoresData.find(r => r.id === revisorId);
        
        // Actualizar horarios disponibles si hay fecha seleccionada
        this.actualizarHorariosDisponibles();
    }

    /**
     * Actualizar horarios disponibles según fecha y revisor seleccionado
     */
    actualizarHorariosDisponibles() {
        const fechaInput = document.getElementById('fecha-cita');
        const horaSelect = document.getElementById('hora-cita');
        
        if (!this.revisorSeleccionado || !fechaInput.value) {
            return;
        }

        const fechaSeleccionada = fechaInput.value;
        const horariosDisponibles = this.revisorSeleccionado.proximas_citas
            .filter(cita => cita.fecha === fechaSeleccionada);

        // Limpiar opciones actuales (excepto la primera)
        horaSelect.innerHTML = '<option value="">Seleccionar hora</option>';

        if (horariosDisponibles.length === 0) {
            horaSelect.innerHTML += '<option value="" disabled>No hay horarios disponibles</option>';
            return;
        }

        // Agregar horarios disponibles
        horariosDisponibles.forEach(horario => {
            const option = document.createElement('option');
            option.value = horario.hora;
            option.textContent = horario.hora_formateada;
            horaSelect.appendChild(option);
        });
    }

    /**
     * Procesar agendamiento de cita
     */
    async procesarAgendamiento() {
        if (!this.validarFormulario()) {
            return;
        }

        const btnAgendar = document.getElementById('btn-agendar-cita');
        const spinner = document.getElementById('spinner-agendar');
        const textoBtn = document.getElementById('texto-btn-agendar');

        // Mostrar loading
        btnAgendar.disabled = true;
        spinner.classList.remove('hidden');
        textoBtn.textContent = 'Agendando...';

        try {
            const formData = new FormData(document.getElementById('form-agendar-cita'));
            formData.append('revisor_id', this.revisorSeleccionado.id);

            const response = await fetch('/api/citas/agendar-revision', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                this.mostrarExito(data.message);
                this.cerrarModal();
                
                // Recargar la página o actualizar la interfaz
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                this.mostrarError(data.message);
            }
        } catch (error) {
            console.error('Error al agendar cita:', error);
            this.mostrarError('Error al agendar la cita. Intente nuevamente.');
        } finally {
            // Restaurar botón
            btnAgendar.disabled = false;
            spinner.classList.add('hidden');
            textoBtn.textContent = 'Agendar Cita';
        }
    }

    /**
     * Validar formulario
     */
    validarFormulario() {
        if (!this.revisorSeleccionado) {
            this.mostrarError('Debe seleccionar un revisor');
            return false;
        }

        const fecha = document.getElementById('fecha-cita').value;
        const hora = document.getElementById('hora-cita').value;

        if (!fecha) {
            this.mostrarError('Debe seleccionar una fecha');
            return false;
        }

        if (!hora) {
            this.mostrarError('Debe seleccionar una hora');
            return false;
        }

        return true;
    }

    /**
     * Limpiar formulario
     */
    limpiarFormulario() {
        document.getElementById('form-agendar-cita').reset();
        document.querySelectorAll('.revisor-card').forEach(card => {
            card.classList.remove('selected');
        });
        this.revisorSeleccionado = null;
        this.revisoresData = [];
    }

    /**
     * Obtener clase CSS para distancia
     */
    obtenerClaseDistancia(categoria) {
        return {
            'cerca': 'distancia-cerca',
            'media': 'distancia-media',
            'lejos': 'distancia-lejos'
        }[categoria] || 'distancia-media';
    }

    /**
     * Obtener clase CSS para disponibilidad
     */
    obtenerClaseDisponibilidad(disponibilidad) {
        return {
            'alta': 'bg-green-400',
            'media': 'bg-yellow-400',
            'baja': 'bg-red-400'
        }[disponibilidad] || 'bg-gray-400';
    }

    /**
     * Mostrar error en revisores
     */
    mostrarErrorRevisores(mensaje) {
        const container = document.getElementById('lista-revisores');
        container.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-medium">Error al cargar revisores</p>
                <p class="text-sm">${mensaje}</p>
            </div>
        `;
    }

    /**
     * Mostrar mensaje de éxito
     */
    mostrarExito(mensaje) {
        if (window.mostrarNotificacion) {
            window.mostrarNotificacion(mensaje, 'success');
        } else {
            alert(mensaje);
        }
    }

    /**
     * Mostrar mensaje de error
     */
    mostrarError(mensaje) {
        if (window.mostrarNotificacion) {
            window.mostrarNotificacion(mensaje, 'error');
        } else {
            alert(mensaje);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.agendarCitaRevision = new AgendarCitaRevision();
});

// Funciones globales para compatibilidad
function abrirModalAgendarCita(tramiteId) {
    if (window.agendarCitaRevision) {
        window.agendarCitaRevision.abrirModal(tramiteId);
    }
}

function cerrarModalCita() {
    if (window.agendarCitaRevision) {
        window.agendarCitaRevision.cerrarModal();
    }
}
