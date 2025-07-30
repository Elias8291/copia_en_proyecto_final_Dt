// Script de prueba para el envío de documentos (Simplificado)
console.log('Test Documentos: Script de prueba cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('Test Documentos: DOM cargado');
    
    // Verificar que los elementos de documentos existan
    const documentoInputs = document.querySelectorAll('input[type="file"]');
    const documentoLabels = document.querySelectorAll('label[for^="file_"]');
    
    console.log('Test Documentos: Elementos encontrados:', {
        documentoInputs: documentoInputs.length,
        documentoLabels: documentoLabels.length
    });
    
    // Verificar que el formulario tenga el enctype correcto
    const form = document.getElementById('tramite-form');
    if (form) {
        console.log('Test Documentos: Formulario encontrado');
        console.log('Test Documentos: Enctype del formulario:', form.enctype);
        
        if (form.enctype !== 'multipart/form-data') {
            console.warn('Test Documentos: El formulario no tiene enctype multipart/form-data');
        }
    } else {
        console.error('Test Documentos: Formulario no encontrado');
    }
    
    // Verificar que el handler simplificado esté cargado
    if (typeof handleFileUploadSimple === 'function') {
        console.log('Test Documentos: Función handleFileUploadSimple disponible');
    } else {
        console.error('Test Documentos: Función handleFileUploadSimple no disponible');
    }
    
    // Verificar que no haya conflictos con el handler anterior
    if (typeof handleFileUpload === 'function') {
        console.warn('Test Documentos: Función handleFileUpload aún disponible (posible conflicto)');
    } else {
        console.log('Test Documentos: No hay conflictos con handler anterior');
    }
}); 