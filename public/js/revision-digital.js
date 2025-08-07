// UI Functions - Toggle visibility
function toggleCotejo(seccion) {
    const content = document.getElementById(`content_${seccion}`);
    const cotejo = document.getElementById(`cotejo_${seccion}`);
    const toggleText = document.getElementById(`toggle_text_${seccion}`);
    
    if (!content || !cotejo || !toggleText) return;
    
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

function toggleHistorial() {
    const contenido = document.getElementById('contenido_historial');
    const toggleText = document.getElementById('toggle_text_historial');
    
    if (!contenido || !toggleText) return;
    
    if (contenido.classList.contains('hidden')) {
        contenido.classList.remove('hidden');
        toggleText.textContent = 'Ocultar Historial';
    } else {
        contenido.classList.add('hidden');
        toggleText.textContent = 'Mostrar Historial';
    }
}

window.toggleCotejo = toggleCotejo;
window.toggleHistorial = toggleHistorial; 