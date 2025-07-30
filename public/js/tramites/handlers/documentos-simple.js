// Handler simplificado para documentos - Sin interferencias en la subida
console.log('Documentos Simple Handler: Cargado');

// Función básica para manejar la subida de archivos
function handleFileUploadSimple(input, documentoId) {
    const file = input.files[0];
    const statusElement = document.getElementById(`status_${documentoId}`);
    const filenameElement = document.getElementById(`filename_${documentoId}`);
    
    if (!statusElement || !filenameElement) {
        console.warn('Elementos de estado no encontrados para documento:', documentoId);
        return;
    }
    
    if (file) {
        // Validar tamaño del archivo (máximo 50MB)
        const maxSize = 50 * 1024 * 1024; // 50MB
        if (file.size > maxSize) {
            statusElement.className = "inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full";
            statusElement.innerHTML = `
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Archivo muy grande
            `;
            filenameElement.textContent = `Error: máximo 50MB`;
            filenameElement.classList.remove('hidden');
            input.value = '';
            return;
        }
        
        // Archivo válido
        statusElement.className = "inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full";
        statusElement.innerHTML = `
            <i class="fas fa-check mr-1"></i>
            Subido
        `;
        filenameElement.textContent = file.name;
        filenameElement.classList.remove('hidden');
        
    } else {
        statusElement.className = "inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full";
        statusElement.innerHTML = `
            <i class="fas fa-clock mr-1"></i>
            Pendiente
        `;
        filenameElement.classList.add('hidden');
    }
}

// Función para validar documentos antes del envío
function validateDocumentosBeforeSubmit() {
    const documentosInputs = document.querySelectorAll('input[type="file"]');
    let documentosEnviados = 0;
    let totalDocumentos = documentosInputs.length;
    
    documentosInputs.forEach(input => {
        if (input.files && input.files.length > 0) {
            documentosEnviados++;
        }
    });
    
    console.log(`Documentos enviados: ${documentosEnviados}/${totalDocumentos}`);
    
    // Permitir envío incluso si no todos los documentos están subidos
    return true;
}

// Función para mostrar información de documentos
function mostrarInfoDocumentos() {
    const documentosInputs = document.querySelectorAll('input[type="file"]');
    let documentosSubidos = 0;
    let totalDocumentos = documentosInputs.length;
    
    documentosInputs.forEach(input => {
        if (input.files && input.files.length > 0) {
            documentosSubidos++;
        }
    });
    
    console.log(`Documentos subidos: ${documentosSubidos}/${totalDocumentos}`);
    
    // Actualizar progreso si existe
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    if (progressBar && progressText) {
        const percentage = totalDocumentos > 0 ? Math.round((documentosSubidos / totalDocumentos) * 100) : 0;
        progressBar.style.width = `${percentage}%`;
        progressText.textContent = `Documentos: ${documentosSubidos}/${totalDocumentos}`;
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Documentos Simple Handler: Inicializando...');
    
    // Agregar listeners para cambios en inputs de archivo
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const documentoId = this.id.replace('file_', '');
            handleFileUploadSimple(this, documentoId);
            mostrarInfoDocumentos();
        });
    });
    
    // Validar documentos al cargar
    setTimeout(() => {
        mostrarInfoDocumentos();
    }, 500);
});

// Hacer funciones disponibles globalmente
window.handleFileUploadSimple = handleFileUploadSimple;
window.validateDocumentosBeforeSubmit = validateDocumentosBeforeSubmit;
window.mostrarInfoDocumentos = mostrarInfoDocumentos; 