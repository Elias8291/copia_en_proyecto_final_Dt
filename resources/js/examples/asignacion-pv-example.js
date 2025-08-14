/**
 * Ejemplo de uso de la funcionalidad de asignación de PV
 * Este archivo muestra cómo integrar la asignación de PV en el frontend
 */

class AsignacionPvManager {
    constructor() {
        this.baseUrl = '/revisiones';
    }

    /**
     * Procesar asignación de PV para un trámite
     * @param {number} tramiteId - ID del trámite
     * @param {number} numeroProveedor - Número identificador del proveedor
     * @param {string|null} ultimoPvSistema - Último PV del sistema (opcional)
     * @param {string} fechaRevision - Fecha de revisión (YYYY-MM-DD)
     * @returns {Promise<Object>} Resultado de la asignación
     */
    async procesarAsignacionPv(tramiteId, numeroProveedor, ultimoPvSistema = null, fechaRevision = null) {
        try {
            // Usar fecha actual si no se proporciona
            const fecha = fechaRevision || new Date().toISOString().split('T')[0];
            
            const response = await fetch(`${this.baseUrl}/${tramiteId}/procesar-asignacion-pv`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    numero_proveedor: numeroProveedor,
                    ultimo_pv_sistema: ultimoPvSistema,
                    fecha_revision: fecha
                })
            });

            const resultado = await response.json();
            
            if (!response.ok) {
                throw new Error(resultado.message || 'Error en la solicitud');
            }

            return resultado;
        } catch (error) {
            console.error('Error al procesar asignación de PV:', error);
            throw error;
        }
    }

    /**
     * Mostrar resultado de asignación en la interfaz
     * @param {Object} resultado - Resultado de la asignación
     * @param {string} containerId - ID del contenedor donde mostrar el resultado
     */
    mostrarResultado(resultado, containerId = 'resultado-asignacion') {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (resultado.success) {
            const datos = resultado.datos;
            container.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Asignación de PV Exitosa
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <strong>PV:</strong> ${datos.pv}
                                    </div>
                                    <div>
                                        <strong>Vigencia Inicio:</strong> ${datos.vigencia_inicio}
                                    </div>
                                    <div>
                                        <strong>Vigencia Fin:</strong> ${datos.vigencia_fin}
                                    </div>
                                    <div>
                                        <strong>Fecha Alta:</strong> ${datos.fecha_alta_padron}
                                    </div>
                                </div>
                                ${datos.notas_validacion ? `<p class="mt-2 text-xs"><strong>Notas:</strong> ${datos.notas_validacion}</p>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Error en Asignación de PV
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>${resultado.message}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    /**
     * Ejemplo de uso completo
     */
    async ejemploUsoCompleto() {
        const tramiteId = 123; // ID del trámite de inscripción
        const numeroProveedor = 901323;
        const ultimoPvSistema = 'PV901323'; // Opcional
        const fechaRevision = '2024-01-15';

        try {
            console.log('Iniciando asignación de PV...');
            
            const resultado = await this.procesarAsignacionPv(
                tramiteId,
                numeroProveedor,
                ultimoPvSistema,
                fechaRevision
            );

            console.log('Resultado:', resultado);
            this.mostrarResultado(resultado);

            if (resultado.success) {
                // Actualizar interfaz con los nuevos datos
                this.actualizarInterfazConPv(resultado.datos);
            }

        } catch (error) {
            console.error('Error en ejemplo:', error);
            this.mostrarResultado({
                success: false,
                message: error.message
            });
        }
    }

    /**
     * Actualizar interfaz con los datos del PV asignado
     * @param {Object} datos - Datos de la asignación
     */
    actualizarInterfazConPv(datos) {
        // Actualizar campos en el formulario o interfaz
        const pvField = document.getElementById('pv_numero');
        if (pvField) pvField.value = datos.pv;

        const vigenciaInicioField = document.getElementById('vigencia_inicio');
        if (vigenciaInicioField) vigenciaInicioField.value = datos.vigencia_inicio;

        const vigenciaFinField = document.getElementById('vigencia_fin');
        if (vigenciaFinField) vigenciaFinField.value = datos.vigencia_fin;

        // Mostrar notificación de éxito
        this.mostrarNotificacion('PV asignado exitosamente', 'success');
    }

    /**
     * Mostrar notificación
     * @param {string} mensaje - Mensaje a mostrar
     * @param {string} tipo - Tipo de notificación (success, error, warning)
     */
    mostrarNotificacion(mensaje, tipo = 'info') {
        // Implementar según el sistema de notificaciones usado
        console.log(`${tipo.toUpperCase()}: ${mensaje}`);
        
        // Ejemplo con SweetAlert2
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: tipo === 'success' ? 'Éxito' : 'Información',
                text: mensaje,
                icon: tipo,
                timer: tipo === 'success' ? 3000 : undefined
            });
        }
    }
}

// Ejemplo de uso
document.addEventListener('DOMContentLoaded', function() {
    const asignacionManager = new AsignacionPvManager();

    // Ejemplo de botón para asignar PV
    const btnAsignarPv = document.getElementById('btn-asignar-pv');
    if (btnAsignarPv) {
        btnAsignarPv.addEventListener('click', async function() {
            const tramiteId = this.dataset.tramiteId;
            const numeroProveedor = document.getElementById('numero_proveedor').value;
            const ultimoPvSistema = document.getElementById('ultimo_pv_sistema').value || null;
            const fechaRevision = document.getElementById('fecha_revision').value || null;

            if (!numeroProveedor) {
                asignacionManager.mostrarNotificacion('Debe ingresar el número de proveedor', 'error');
                return;
            }

            try {
                btnAsignarPv.disabled = true;
                btnAsignarPv.textContent = 'Procesando...';

                const resultado = await asignacionManager.procesarAsignacionPv(
                    tramiteId,
                    parseInt(numeroProveedor),
                    ultimoPvSistema,
                    fechaRevision
                );

                asignacionManager.mostrarResultado(resultado);

            } catch (error) {
                asignacionManager.mostrarNotificacion(error.message, 'error');
            } finally {
                btnAsignarPv.disabled = false;
                btnAsignarPv.textContent = 'Asignar PV';
            }
        });
    }
});

// Exportar para uso en otros módulos
window.AsignacionPvManager = AsignacionPvManager;
