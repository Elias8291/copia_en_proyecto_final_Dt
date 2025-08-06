/**
 * Funciones para Revisión Digital
 */

let currentSection = 0;
const sections = document.querySelectorAll('[data-section]');

// Navegación entre secciones
function navigateSection(direction) {
    if (direction === 'prev' && currentSection > 0) {
        currentSection--;
    } else if (direction === 'next' && currentSection < sections.length - 1) {
        currentSection++;
    }
    
    sections[currentSection].scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Toggle cotejo
function toggleCotejo(seccion) {
    const content = document.getElementById(`content_${seccion}`);
    const cotejo = document.getElementById(`cotejo_${seccion}`);
    const toggleText = document.getElementById(`toggle_text_${seccion}`);
    
    if (cotejo.classList.contains('hidden')) {
        cotejo.classList.remove('hidden');
        content.classList.remove('grid-cols-1');
        content.classList.add('lg:grid-cols-2');
        toggleText.textContent = 'Ocultar Cotejo';
    } else {
        cotejo.classList.add('hidden');
        content.classList.remove('lg:grid-cols-2');
        content.classList.add('grid-cols-1');
        toggleText.textContent = 'Mostrar Cotejo';
    }
}

// Toggle historial
function toggleHistorial() {
    const contenido = document.getElementById('contenido_historial');
    const toggleText = document.getElementById('toggle_text_historial');
    
    if (contenido.classList.contains('hidden')) {
        contenido.classList.remove('hidden');
        toggleText.textContent = 'Ocultar Historial';
    } else {
        contenido.classList.add('hidden');
        toggleText.textContent = 'Mostrar Historial';
    }
}

// Evaluación por secciones
function evaluarSeccion(seccion, decision) {
    const textarea = document.getElementById(`textarea_${seccion}`);
    const comentario = textarea ? textarea.value.trim() : '';
    
    // Actualizar campos ocultos del formulario
    const decisionInput = document.getElementById(`decision_${seccion}`);
    const comentarioInput = document.getElementById(`comentario_${seccion}`);
    
    if (decisionInput) {
        decisionInput.value = decision;
    }
    
    if (comentarioInput) {
        comentarioInput.value = comentario;
    }
    
    // Actualizar resumen visual
    actualizarResumenSeccion(seccion, decision);
    
    // Actualizar indicador de estado individual de la sección
    actualizarEstadoIndividual(seccion, decision);
    
    // Feedback visual mejorado
    const seccionLabel = obtenerNombreSeccion(seccion);
    const comentarioTexto = comentario ? ` (con comentarios)` : '';
    mostrarNotificacion(`${seccionLabel} evaluada como: ${decision}${comentarioTexto}`, decision === 'Aprobado' ? 'success' : 'warning');
    
    // Debug: mostrar en consola los valores capturados
    console.log(`Sección evaluada:`, {
        seccion: seccion,
        decision: decision,
        comentario: comentario,
        decisionInputValue: decisionInput?.value,
        comentarioInputValue: comentarioInput?.value
    });
}

function obtenerNombreSeccion(seccion) {
    const nombres = {
        'datos_generales': 'Datos Generales',
        'actividades': 'Actividades Económicas', 
        'domicilio': 'Domicilio',
        'constitucion': 'Constitución',
        'accionistas': 'Accionistas',
        'apoderado': 'Apoderado Legal',
        'archivos': 'Documentos'
    };
    return nombres[seccion] || seccion;
}

function actualizarResumenSeccion(seccion, decision) {
    const resumenElement = document.getElementById(`resumen_${seccion}`);
    if (!resumenElement) return;
    
    const estadoElement = resumenElement.querySelector('.seccion-estado');
    if (!estadoElement) return;
    
    // Limpiar clases anteriores
    estadoElement.className = 'seccion-estado px-2 py-1 rounded text-xs';
    
    // Aplicar nuevas clases según la decisión
    if (decision === 'Aprobado') {
        estadoElement.classList.add('bg-green-100', 'text-green-800');
        estadoElement.textContent = 'Aprobado';
    } else if (decision === 'Rechazado') {
        estadoElement.classList.add('bg-red-100', 'text-red-800');
        estadoElement.textContent = 'Rechazado';
    } else {
        estadoElement.classList.add('bg-gray-100', 'text-gray-600');
        estadoElement.textContent = 'Pendiente';
    }
}

function actualizarEstadoIndividual(seccion, decision) {
    // Actualizar el indicador de estado en la sección individual
    let estadoElement = document.getElementById(`estado_${seccion}`);
    
    // Si no encuentra el estado normal, buscar el de persona física (para archivos)
    if (!estadoElement && seccion === 'archivos') {
        estadoElement = document.getElementById(`estado_archivos_fisica`);
    }
    
    if (!estadoElement) return;
    
    // Limpiar clases anteriores
    estadoElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
    
    // Aplicar nuevas clases según la decisión
    if (decision === 'Aprobado') {
        estadoElement.classList.add('bg-green-100', 'text-green-800');
        estadoElement.textContent = '✓ Aprobado';
    } else if (decision === 'Rechazado') {
        estadoElement.classList.add('bg-red-100', 'text-red-800');
        estadoElement.textContent = '✗ Rechazado';
    } else {
        estadoElement.classList.add('bg-gray-100', 'text-gray-600');
        estadoElement.textContent = 'Pendiente';
    }
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 text-white transition-opacity duration-300 ${
        tipo === 'success' ? 'bg-green-500' :
        tipo === 'warning' ? 'bg-yellow-500' :
        tipo === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    div.textContent = mensaje;
    
    document.body.appendChild(div);
    
    // Mostrar notificación
    setTimeout(() => div.classList.add('opacity-100'), 100);
    
    // Ocultar después de 3 segundos
    setTimeout(() => {
        div.classList.add('opacity-0');
        setTimeout(() => document.body.removeChild(div), 300);
    }, 3000);
}

// Sincronizar comentarios en tiempo real
function sincronizarComentario(seccion) {
    const textarea = document.getElementById(`textarea_${seccion}`);
    const comentarioInput = document.getElementById(`comentario_${seccion}`);
    
    if (textarea && comentarioInput) {
        comentarioInput.value = textarea.value.trim();
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Agregar event listeners a todos los textareas de comentarios
    const textareas = document.querySelectorAll('[id^="textarea_"]');
    textareas.forEach(textarea => {
        const seccion = textarea.id.replace('textarea_', '');
        
        // Sincronizar en tiempo real mientras el usuario escribe
        textarea.addEventListener('input', function() {
            sincronizarComentario(seccion);
        });
        
        // Sincronizar cuando pierde el foco
        textarea.addEventListener('blur', function() {
            sincronizarComentario(seccion);
        });
    });
    
    // Validar que al menos una sección haya sido evaluada antes de enviar
    const form = document.getElementById('formRevisionCompleta');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sincronizar todos los comentarios antes de enviar
            textareas.forEach(textarea => {
                const seccion = textarea.id.replace('textarea_', '');
                sincronizarComentario(seccion);
            });
            
            const decisiones = Array.from(document.querySelectorAll('[id^="decision_"]'));
            const hayEvaluaciones = decisiones.some(input => input.value !== 'Pendiente');
            
            if (!hayEvaluaciones) {
                e.preventDefault();
                mostrarNotificacion('Debe evaluar al menos una sección antes de finalizar la revisión', 'warning');
                return false;
            }
            
            // Debug: mostrar todos los datos que se van a enviar
            console.log('Datos del formulario a enviar:');
            decisiones.forEach(input => {
                const seccion = input.id.replace('decision_', '');
                const comentarioInput = document.getElementById(`comentario_${seccion}`);
                console.log(`- ${seccion}:`, {
                    decision: input.value,
                    comentario: comentarioInput?.value || ''
                });
            });
        });
    }
});

// Exportar funciones globalmente
window.navigateSection = navigateSection;
window.toggleCotejo = toggleCotejo;
window.toggleHistorial = toggleHistorial;
window.evaluarSeccion = evaluarSeccion;
window.sincronizarComentario = sincronizarComentario; 