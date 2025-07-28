/**
 * Funcionalidades del componente comparador de documentos
 */

// Función para mostrar/ocultar el comparador de documentos
function toggleDocumentoComparador(seccion) {
    const comparador = document.getElementById(`documento-comparador-${seccion}`);
    const grid = document.getElementById(`${seccion}Grid`);
    const mainContent = document.getElementById(`${seccion}Main`);
    
    if (!comparador || !grid || !mainContent) {
        console.error(`Elementos no encontrados para la sección: ${seccion}`);
        return;
    }
    
    if (comparador.classList.contains('oculto')) {
        // Mostrar comparador
        comparador.classList.remove('oculto');
        comparador.classList.add('mostrar');
        grid.classList.remove('grid-cols-1');
        grid.classList.add('grid-cols-2');
        
        // Ajustar el contenido principal
        mainContent.classList.add('col-span-1');
        
        // Actualizar el botón
        const button = document.querySelector(`[onclick="toggleDocumentoComparador('${seccion}')"]`);
        if (button) {
            button.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Ocultar Comparador
            `;
        }
        
        // Cargar el documento si existe
        cargarDocumentoComparador(seccion);
        
    } else {
        // Ocultar comparador
        comparador.classList.remove('mostrar');
        comparador.classList.add('oculto');
        grid.classList.remove('grid-cols-2');
        grid.classList.add('grid-cols-1');
        
        // Restaurar el contenido principal
        mainContent.classList.remove('col-span-1');
        
        // Actualizar el botón
        const button = document.querySelector(`[onclick="toggleDocumentoComparador('${seccion}')"]`);
        if (button) {
            button.innerHTML = `
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Comparar Documento
            `;
        }
    }
}

// Función para cargar el documento en el comparador
function cargarDocumentoComparador(seccion) {
    const comparador = document.getElementById(`documento-comparador-${seccion}`);
    if (!comparador) return;
    
    // Verificar si hay un documento disponible
    const documentoPreview = comparador.querySelector('.documento-preview-container');
    if (!documentoPreview) return;
    
    // Si no hay documento, mostrar mensaje
    const iframe = documentoPreview.querySelector('iframe');
    const img = documentoPreview.querySelector('img');
    
    if (!iframe && !img) {
        // No hay documento cargado
        console.log(`No hay documento disponible para la sección: ${seccion}`);
    }
}

// Función para hacer zoom en el documento
function zoomDocumento(seccion, accion) {
    const preview = document.getElementById(`documento-preview-${seccion}`);
    if (!preview) return;
    
    let currentZoom = parseFloat(preview.style.transform.replace('scale(', '').replace(')', '')) || 1;
    
    if (accion === 'in') {
        currentZoom = Math.min(currentZoom * 1.2, 3);
    } else {
        currentZoom = Math.max(currentZoom / 1.2, 0.5);
    }
    
    preview.style.transform = `scale(${currentZoom})`;
    preview.style.transformOrigin = 'center center';
}

// Función para resaltar diferencias
function resaltarDiferencias(seccion) {
    console.log(`Resaltando diferencias para sección: ${seccion}`);
    
    // Obtener datos del formulario
    const formData = obtenerDatosFormulario(seccion);
    
    // Aquí se implementaría la lógica para comparar con el documento
    // Por ahora solo mostramos un mensaje
    mostrarNotificacion('info', 'Función de resaltado en desarrollo');
}

// Función para exportar comparación
function exportarComparacion(seccion) {
    console.log(`Exportando comparación para sección: ${seccion}`);
    
    // Obtener datos del formulario
    const formData = obtenerDatosFormulario(seccion);
    
    // Crear reporte de comparación
    const reporte = generarReporteComparacion(seccion, formData);
    
    // Descargar reporte
    descargarReporte(reporte, `comparacion_${seccion}_${new Date().toISOString().split('T')[0]}.pdf`);
    
    mostrarNotificacion('success', 'Reporte de comparación generado');
}

// Función para anotar documento
function anotarDocumento(seccion) {
    console.log(`Anotando documento para sección: ${seccion}`);
    
    // Abrir modal de anotaciones
    abrirModalAnotaciones(seccion);
}

// Función para obtener datos del formulario
function obtenerDatosFormulario(seccion) {
    const mainContent = document.getElementById(`${seccion}Main`);
    if (!mainContent) return {};
    
    const formData = {};
    const inputs = mainContent.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        if (input.name) {
            formData[input.name] = input.value;
        }
    });
    
    return formData;
}

// Función para generar reporte de comparación
function generarReporteComparacion(seccion, formData) {
    const reporte = {
        seccion: seccion,
        fecha: new Date().toISOString(),
        datosFormulario: formData,
        observaciones: []
    };
    
    // Aquí se implementaría la lógica de comparación
    // Por ahora retornamos un objeto básico
    return reporte;
}

// Función para descargar reporte
function descargarReporte(reporte, nombreArchivo) {
    const contenido = JSON.stringify(reporte, null, 2);
    const blob = new Blob([contenido], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = nombreArchivo;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Función para mostrar notificaciones
function mostrarNotificacion(tipo, mensaje) {
    const notificacion = document.createElement('div');
    notificacion.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        tipo === 'success' ? 'bg-green-500 text-white' :
        tipo === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notificacion.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${tipo === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' :
                  tipo === 'error' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' :
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
            </svg>
            <span>${mensaje}</span>
        </div>
    `;
    
    document.body.appendChild(notificacion);
    
    setTimeout(() => {
        notificacion.remove();
    }, 3000);
}

// Función para abrir modal de anotaciones
function abrirModalAnotaciones(seccion) {
    // Crear modal de anotaciones
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Agregar Anotación</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <textarea class="w-full h-32 p-3 border border-gray-300 rounded-lg resize-none" 
                      placeholder="Escriba su anotación aquí..."></textarea>
            <div class="flex justify-end space-x-3 mt-4">
                <button onclick="this.closest('.fixed').remove()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancelar
                </button>
                <button onclick="guardarAnotacion('${seccion}', this)" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// Función para guardar anotación
function guardarAnotacion(seccion, button) {
    const modal = button.closest('.fixed');
    const textarea = modal.querySelector('textarea');
    const anotacion = textarea.value.trim();
    
    if (anotacion) {
        console.log(`Anotación guardada para ${seccion}: ${anotacion}`);
        mostrarNotificacion('success', 'Anotación guardada');
        modal.remove();
    } else {
        mostrarNotificacion('error', 'Por favor escriba una anotación');
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Configurar eventos adicionales si es necesario
    const comparadores = document.querySelectorAll('.documento-comparador');
    comparadores.forEach(comparador => {
        // Agregar eventos específicos si es necesario
    });
});

// Exportar funciones para uso global
window.toggleDocumentoComparador = toggleDocumentoComparador;
window.zoomDocumento = zoomDocumento;
window.resaltarDiferencias = resaltarDiferencias;
window.exportarComparacion = exportarComparacion;
window.anotarDocumento = anotarDocumento; 