// Función global para evaluar secciones
async function evaluarSeccion(seccion, estado) {
    // Validación especial para la sección de archivos
    if (seccion === 'archivos' && estado === 'Aprobado') {
        const archivos = document.querySelectorAll('[id^="estado_archivo_"]');
        const estados = Array.from(archivos).map(el => el.textContent.trim());
        
        if (estados.includes('Rechazado')) {
            mostrarError('No se puede aprobar la sección de documentos. Hay documentos rechazados que requieren corrección.');
            return;
        }
        
        if (estados.includes('Pendiente')) {
            mostrarError('No se puede aprobar la sección de documentos. Hay documentos pendientes de revisión.');
            return;
        }
    }
    
    const comentario = document.getElementById(`comentario_${seccion}`)?.value || '';
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    
    if (!tramiteId) return;
    
    try {
        const response = await fetch(`/revisiones/${tramiteId}/seccion/evaluar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ seccion, estado, comentario })
        });

        if (response.ok) {
            // Actualizar estado visual
            const sectionElement = document.querySelector(`[data-section="${seccion}"]`);
            if (sectionElement) {
                sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
                sectionElement.classList.add(estado === 'Aprobado' ? 'seccion-aprobada' : 'seccion-rechazada');
            }
            
            // Actualizar indicador de estado
            const estadoEl = document.getElementById(`estado_${seccion}`);
            if (estadoEl) {
                estadoEl.textContent = estado;
                estadoEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                    estado === 'Rechazado' ? 'bg-red-100 text-red-800' : 
                    'bg-gray-100 text-gray-600'
                }`;
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Función para mostrar/ocultar área de cotejo
function toggleCotejo(seccion) {
    const contentElement = document.getElementById(`content_${seccion}`);
    const cotejoElement = document.getElementById(`cotejo_${seccion}`);
    const toggleText = document.getElementById(`toggle_text_${seccion}`);
    
    if (contentElement && cotejoElement && toggleText) {
        const isHidden = cotejoElement.classList.contains('hidden');
        
        if (isHidden) {
            // Mostrar cotejo al lado
            contentElement.classList.remove('grid-cols-1');
            contentElement.classList.add('grid-cols-2');
            cotejoElement.classList.remove('hidden');
            toggleText.textContent = 'Ocultar Cotejo';
        } else {
            // Ocultar cotejo
            contentElement.classList.remove('grid-cols-2');
            contentElement.classList.add('grid-cols-1');
            cotejoElement.classList.add('hidden');
            toggleText.textContent = 'Mostrar Cotejo';
        }
    }
}

// Función para mostrar/ocultar panel de historial
function toggleHistorial() {
    const contenidoHistorial = document.getElementById('contenido_historial');
    const toggleIcon = document.getElementById('toggle_icon_historial');
    const toggleText = document.getElementById('toggle_text_historial');
    
    if (contenidoHistorial && toggleIcon && toggleText) {
        const isHidden = contenidoHistorial.classList.contains('hidden');
        
        if (isHidden) {
            // Mostrar historial
            contenidoHistorial.classList.remove('hidden');
            toggleIcon.style.transform = 'rotate(180deg)';
            toggleText.textContent = 'Ocultar Historial';
        } else {
            // Ocultar historial
            contenidoHistorial.classList.add('hidden');
            toggleIcon.style.transform = 'rotate(0deg)';
            toggleText.textContent = 'Ver Historial';
        }
    }
}

// Función para mostrar mensajes de error
function mostrarError(mensaje) {
    // Crear o actualizar elemento de error
    let errorElement = document.getElementById('error-mensaje');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.id = 'error-mensaje';
        errorElement.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50 shadow-lg';
        document.body.appendChild(errorElement);
    }
    
    errorElement.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">${mensaje}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-red-700 hover:text-red-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (errorElement && errorElement.parentElement) {
            errorElement.remove();
        }
    }, 5000);
}

// Cargar estados iniciales al cargar la página
document.addEventListener('DOMContentLoaded', async function() {
    const tramiteId = document.querySelector('meta[name="tramite-id"]')?.getAttribute('content');
    if (!tramiteId) return;

    const secciones = ['datos_generales', 'actividades', 'domicilio'];
    if (window.esPersonaMoral) {
        secciones.push('constitucion', 'accionistas', 'apoderado');
    }
    // NO incluir 'archivos' aquí - se maneja en archivos-tiempo-real.js
    
    // Cargar estado de cada sección
    for (const seccion of secciones) {
        try {
            const response = await fetch(`/revisiones/${tramiteId}/seccion/estado?seccion=${seccion}`);
            const data = await response.json();
            
            if (data.evaluada) {
                const comentarioField = document.getElementById(`comentario_${seccion}`);
                if (comentarioField && data.comentario) {
                    comentarioField.value = data.comentario;
                }
                
                const sectionElement = document.querySelector(`[data-section="${seccion}"]`);
                if (sectionElement) {
                    sectionElement.classList.remove('seccion-aprobada', 'seccion-rechazada');
                    sectionElement.classList.add(data.estado === 'Aprobado' ? 'seccion-aprobada' : 'seccion-rechazada');
                }
                
                const estadoEl = document.getElementById(`estado_${seccion}`);
                if (estadoEl) {
                    estadoEl.textContent = data.estado;
                    estadoEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        data.estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 
                        data.estado === 'Rechazado' ? 'bg-red-100 text-red-800' : 
                        'bg-gray-100 text-gray-600'
                    }`;
                }
            }
        } catch (error) {
            console.error(`Error al cargar estado de ${seccion}:`, error);
        }
    }
});

// Las funciones de decisiones finales se han movido a decisiones-finales.js 