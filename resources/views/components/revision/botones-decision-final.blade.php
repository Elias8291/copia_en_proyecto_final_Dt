@props([
    'showAprobar' => true,
    'showCorrecciones' => true,
    'showRechazar' => true,
    'textoAprobar' => 'Aprobar y Agendar Cita',
    'textoCorrecciones' => 'Rechazar y Para Corrección',
    'textoRechazar' => 'Rechazar Trámite',
    'layout' => 'grid' // grid, flex
])

@php
    $containerClasses = match($layout) {
        'flex' => 'flex flex-col sm:flex-row gap-2 justify-center',
        default => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 justify-items-center'
    };
@endphp

<div class="{{ $containerClasses }}">
    @if($showAprobar)
        <button 
            type="button" 
            onclick="confirmarDecision('agendar_cita', '{{ $textoAprobar }}', '¿Está seguro que desea aprobar este trámite y agendar una cita presencial? Esta acción no se puede deshacer.')"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ $textoAprobar }}</span>
        </button>
    @endif
    

    
    @if($showCorrecciones)
        <button 
            type="button" 
            onclick="confirmarDecision('correcciones', '{{ $textoCorrecciones }}', '¿Está seguro que desea rechazar este trámite y enviarlo para corrección? El solicitante deberá realizar los cambios solicitados.')"
            class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.084 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <span>{{ $textoCorrecciones }}</span>
        </button>
    @endif
    
    @if($showRechazar)
        <button 
            type="button" 
            onclick="confirmarDecision('rechazado', '{{ $textoRechazar }}', '¿Está seguro que desea rechazar este trámite? Esta acción no se puede deshacer y el trámite será cancelado.')"
            class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>{{ $textoRechazar }}</span>
        </button>
    @endif
</div>

<script>
function confirmarDecision(decision, titulo, mensaje) {
    // Verificar que showConfirmModal existe
    if (typeof showConfirmModal !== 'function') {
        console.error('showConfirmModal no está definida');
        alert('Error: Función de confirmación no disponible');
        return;
    }
    
    // Si es aprobar, verificar el estado de las secciones primero
    if (decision === 'agendar_cita') {
        verificarEstadoSecciones(function() {
            // Si todas están aprobadas, continuar con la confirmación
            showConfirmModal(
                'Confirmar: ' + titulo,
                mensaje,
                null,
                function() {
                    ejecutarDecisionFinal(decision);
                }
            );
        });
    } else {
        // Para otras decisiones, mostrar confirmación directamente
        showConfirmModal(
            'Confirmar: ' + titulo,
            mensaje,
            null,
            function() {
                ejecutarDecisionFinal(decision);
            }
        );
    }
}

function verificarEstadoSecciones(callback) {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.content;
    if (!tramiteId) {
        console.error('No se encontró el ID del trámite');
        callback();
        return;
    }
    
    // Obtener el estado general de las secciones
    fetch(`/revisiones/${tramiteId}/estado-general`)
        .then(response => {
            return response.json();
        })
        .then(data => {
            try {
                // Verificar que la respuesta tenga la estructura esperada
                if (data && data.success && typeof data.todas_aprobadas !== 'undefined') {
                    if (!data.todas_aprobadas && data.secciones && Array.isArray(data.secciones)) {
                        const seccionesPendientes = data.secciones.filter(s => s.estado !== 'Aprobado');
                        
                        if (seccionesPendientes.length > 0) {
                            const seccionesNombres = seccionesPendientes.map(s => s.nombre).join(', ');
                            const mensaje = `Secciones pendientes: ${seccionesNombres}`;
                            
                            mostrarNotificacion(mensaje, 'error');
                            return;
                        }
                    }
                } else {
                    console.warn('Respuesta del servidor no tiene la estructura esperada:', data);
                    // Si no podemos verificar, continuar de todas formas
                }
            } catch (error) {
                console.error('Error al procesar la respuesta:', error);
                // Si hay error al procesar, continuar de todas formas
            }
            
            callback();
        })
        .catch(error => {
            console.error('Error al verificar estado de secciones:', error);
            callback(); // Continuar de todas formas
        });
}

function ejecutarDecisionFinal(decision) {
    // Obtener el ID del trámite desde el meta tag
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.content;
    if (!tramiteId) {
        console.error('No se encontró el ID del trámite');
        alert('Error: No se encontró el ID del trámite');
        return;
    }
    
    // Obtener el token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) {
        console.error('No se encontró el token CSRF');
        alert('Error: No se encontró el token CSRF');
        return;
    }
    
    // Obtener comentario general
    const comentarioGeneral = document.getElementById('comentario_general')?.value || '';
    
    // Mapear la decisión a la ruta correcta
    let url;
    switch(decision) {
        case 'agendar_cita':
            url = `/revisiones/${tramiteId}/aprobar-y-agendar`;
            break;
        case 'correcciones':
            url = `/revisiones/${tramiteId}/rechazar-correccion`;
            break;
        case 'rechazado':
            url = `/revisiones/${tramiteId}/rechazar-completo`;
            break;
        default:
            console.error('Decisión no válida:', decision);
            alert('Error: Decisión no válida');
            return;
    }
    
    // Enviar solicitud AJAX
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            comentario_general: comentarioGeneral
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar modal de éxito
            mostrarModalExito(data.message);
        } else {
            // Mostrar error específico
            const mensajeError = data.message || 'Error al procesar la decisión';
            console.error('Error del servidor:', mensajeError);
            mostrarNotificacion(mensajeError, 'error');
        }
    })
    .catch(error => {
        console.error('Error al enviar solicitud:', error);
        mostrarNotificacion('Error de conexión al procesar la decisión', 'error');
    });
}

// Función para mostrar modal de éxito
function mostrarModalExito(mensaje) {
    // Crear el modal de éxito dinámicamente
    const modalHtml = `
        <div id="modal-exito-dinamico" class="fixed z-50 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
                    role="dialog" aria-modal="true" aria-labelledby="modal-headline-exito">
                    
                    <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                        <button type="button" data-behavior="cancel" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline-exito">
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
                                onclick="cerrarModalExito()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Agregar el modal al body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Configurar event listeners
    const modal = document.getElementById('modal-exito-dinamico');
    
            // Botones de cancelar
        const cancelBtns = modal.querySelectorAll('[data-behavior="cancel"]');
        cancelBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                cerrarModalExito();
            });
        });
        
        // Cerrar al hacer clic fuera del modal
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                cerrarModalExito();
            }
        });
        
        // Cerrar con la tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                cerrarModalExito();
            }
        });
}

// Función para cerrar el modal de éxito y redirigir
function cerrarModalExito() {
    const modal = document.getElementById('modal-exito-dinamico');
    if (modal) {
        modal.remove();
    }
    // Redirigir al inicio de revisiones
    window.location.href = '/revisiones';
}
</script> 