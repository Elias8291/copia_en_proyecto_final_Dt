// Función para manejar la subida de archivos con feedback visual
function handleFileUpload(input, documentoId) {
    const file = input.files[0];
    const statusElement = document.getElementById(`status_${documentoId}`);
    const filenameElement = document.getElementById(`filename_${documentoId}`);
    
    if (file) {
        statusElement.className = "px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full";
        statusElement.innerHTML = `
            <i class="fas fa-check mr-1"></i>
            Archivo Seleccionado
        `;
        filenameElement.textContent = file.name;
        filenameElement.classList.remove('hidden');
    } else {
        statusElement.className = "px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full";
        statusElement.innerHTML = `
            <i class="fas fa-clock mr-1"></i>
            Pendiente
        `;
        filenameElement.classList.add('hidden');
    }
}

function mostrarArchivosSeleccionados() {
    const inputs = document.querySelectorAll('input[type="file"]');
    inputs.forEach(input => {
        if (input.files[0]) {
        }
    });
}

window.handleFileUpload = handleFileUpload;
window.mostrarArchivosSeleccionados = mostrarArchivosSeleccionados; 