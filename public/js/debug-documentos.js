// Script temporal para debuggear la validación de documentos
function debugDocumentos() {
    const form = document.getElementById('tramite-form');
    if (!form) {
        console.error('Formulario no encontrado');
        return;
    }

    const formData = new FormData(form);
    
    // Agregar algunos archivos de prueba
    const testFiles = [
        { id: 1, name: 'test1.pdf', type: 'application/pdf' },
        { id: 2, name: 'test2.pdf', type: 'application/pdf' },
        { id: 3, name: 'test3.pdf', type: 'application/pdf' }
    ];

    testFiles.forEach(file => {
        const blob = new Blob(['test content'], { type: file.type });
        const testFile = new File([blob], file.name, { type: file.type });
        formData.append(`documentos[${file.id}]`, testFile);
    });

    fetch('/tramites/debug-documentos', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Debug de documentos:', data);
    })
    .catch(error => {
        console.error('Error en debug:', error);
    });
}

// Ejecutar debug al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de debug cargado');
    // Descomentar la siguiente línea para ejecutar automáticamente
    // debugDocumentos();
}); 