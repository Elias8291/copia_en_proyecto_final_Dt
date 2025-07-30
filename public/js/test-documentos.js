// Script de prueba para el envío de documentos
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
    
    // Verificar que los inputs de archivo tengan los atributos correctos
    documentoInputs.forEach((input, index) => {
        console.log(`Test Documentos: Input ${index + 1}:`, {
            name: input.name,
            accept: input.accept,
            multiple: input.multiple,
            required: input.required
        });
    });
    
    // Verificar que el handler de documentos esté cargado
    if (typeof handleFileUpload === 'function') {
        console.log('Test Documentos: Función handleFileUpload disponible');
    } else {
        console.error('Test Documentos: Función handleFileUpload no disponible');
    }
}); 