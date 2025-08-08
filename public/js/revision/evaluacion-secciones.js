// Función global para evaluar secciones
async function evaluarSeccion(seccion, estado) {
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