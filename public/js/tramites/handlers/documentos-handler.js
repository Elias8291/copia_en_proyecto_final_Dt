// Función para manejar la subida de archivos con feedback visual
function handleFileUpload(input, documentoId) {
    const file = input.files[0];
    const statusElement = document.getElementById(`status_${documentoId}`);
    const filenameElement = document.getElementById(`filename_${documentoId}`);
    const documentContainer = input.closest('.bg-white.border-2');
    
    // Remover mensaje de error existente
    removeDocumentError(documentoId);
    
    if (file) {
        // Validar tipo de archivo
        const allowedTypes = getAcceptedFileTypes(input);
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (allowedTypes && !allowedTypes.includes(fileExtension)) {
            // Error: tipo de archivo no válido
            statusElement.className = "px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full";
            statusElement.innerHTML = `
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Tipo no válido
            `;
            filenameElement.textContent = `Error: formato no permitido`;
            filenameElement.classList.remove('hidden');
            filenameElement.className = "hidden text-xs text-red-600 max-w-32 truncate";
            
            // Marcar contenedor como error
            if (documentContainer) {
                documentContainer.classList.add('border-red-300');
                documentContainer.classList.remove('border-gray-300', 'border-[#9d2449]');
            }
            
            // Limpiar el input
            input.value = '';
            return;
        }
        
        // Validar tamaño del archivo (máximo 10MB)
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            statusElement.className = "px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full";
            statusElement.innerHTML = `
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Archivo muy grande
            `;
            filenameElement.textContent = `Error: máximo 10MB`;
            filenameElement.classList.remove('hidden');
            filenameElement.className = "hidden text-xs text-red-600 max-w-32 truncate";
            
            // Marcar contenedor como error
            if (documentContainer) {
                documentContainer.classList.add('border-red-300');
                documentContainer.classList.remove('border-gray-300', 'border-[#9d2449]');
            }
            
            // Limpiar el input
            input.value = '';
            return;
        }
        
        // Archivo válido
        statusElement.className = "px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full";
        statusElement.innerHTML = `
            <i class="fas fa-check mr-1"></i>
            Archivo Seleccionado
        `;
        filenameElement.textContent = file.name;
        filenameElement.classList.remove('hidden');
        filenameElement.className = "hidden text-xs text-green-600 max-w-32 truncate";
        
        // Marcar contenedor como válido
        if (documentContainer) {
            documentContainer.classList.add('border-green-300');
            documentContainer.classList.remove('border-gray-300', 'border-red-300');
        }
        
        // Validar documentos en tiempo real
        validateDocumentosCompletos();
        
        // Remover error de este documento específico si existe
        removeDocumentError(documentoId);
        
    } else {
        statusElement.className = "px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full";
        statusElement.innerHTML = `
            <i class="fas fa-clock mr-1"></i>
            Pendiente
        `;
        filenameElement.classList.add('hidden');
        
        // Resetear contenedor
        if (documentContainer) {
            documentContainer.classList.remove('border-green-300', 'border-red-300');
            documentContainer.classList.add('border-gray-300');
        }
    }
}

// Función para obtener tipos de archivo aceptados
function getAcceptedFileTypes(input) {
    const accept = input.getAttribute('accept');
    if (!accept) return null;
    
    return accept.split(',').map(type => {
        return type.trim().replace('.', '').toLowerCase();
    });
}

// Función para validar que todos los documentos estén completos
function validateDocumentosCompletos() {
    const documentosInputs = document.querySelectorAll('input[type="file"]');
    let documentosCompletos = 0;
    let totalDocumentos = documentosInputs.length;
    
    documentosInputs.forEach(input => {
        if (input.files && input.files.length > 0) {
            documentosCompletos++;
        }
    });
    
    // Actualizar barra de progreso
    const progressBar = document.getElementById('documentos-progress-bar');
    const progressText = document.getElementById('documentos-progress-text');
    
    if (progressBar && progressText) {
        const percentage = totalDocumentos > 0 ? Math.round((documentosCompletos / totalDocumentos) * 100) : 0;
        progressBar.style.width = `${percentage}%`;
        progressText.textContent = `${documentosCompletos}/${totalDocumentos}`;
        
        // Cambiar color según el progreso
        if (percentage === 100) {
            progressBar.className = 'bg-gradient-to-r from-green-500 to-green-600 h-2.5 rounded-full transition-all duration-500';
        } else if (percentage > 50) {
            progressBar.className = 'bg-gradient-to-r from-yellow-500 to-yellow-600 h-2.5 rounded-full transition-all duration-500';
        } else {
            progressBar.className = 'bg-gradient-to-r from-[#9D2449] to-[#B91C1C] h-2.5 rounded-full transition-all duration-500';
        }
    }
    
    // Mostrar mensaje de completado si todos están listos
    if (documentosCompletos === totalDocumentos && totalDocumentos > 0) {
        showDocumentosCompletosMessage();
    }
    
    return { completos: documentosCompletos, total: totalDocumentos };
}

// Función para mostrar error individual en un documento
function showDocumentError(documentoId, mensaje) {
    const documentContainer = document.querySelector(`#file_${documentoId}`).closest('.bg-white.border-2');
    if (!documentContainer) return;
    
    // Remover error existente
    removeDocumentError(documentoId);
    
    // Crear mensaje de error
    const errorDiv = document.createElement('div');
    errorDiv.className = `document-error-${documentoId} bg-red-50 border border-red-200 rounded-lg p-3 mt-3 animate-fade-in`;
    errorDiv.innerHTML = `
        <div class="flex items-start space-x-2">
            <div class="flex-shrink-0 mt-0.5">
                <div class="w-4 h-4 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-2.5 h-2.5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs font-medium text-red-800">${mensaje}</p>
                <p class="text-xs text-red-600 mt-1">Haga clic en "Subir" para cargar este documento</p>
            </div>
        </div>
    `;
    
    // Insertar después del contenido del documento
    const documentContent = documentContainer.querySelector('.flex.items-center.justify-between');
    if (documentContent) {
        documentContent.parentNode.insertBefore(errorDiv, documentContent.nextSibling);
    }
    
    // Marcar contenedor como error
    documentContainer.classList.add('border-red-300');
    documentContainer.classList.remove('border-gray-300', 'border-green-300');
}

// Función para remover error individual de un documento
function removeDocumentError(documentoId) {
    const existingError = document.querySelector(`.document-error-${documentoId}`);
    if (existingError) {
        existingError.remove();
    }
}

// Función para mostrar errores en todos los documentos faltantes
function showDocumentosFaltantesErrors() {
    const documentosInputs = document.querySelectorAll('input[type="file"]');
    
    documentosInputs.forEach(input => {
        const documentoId = input.id.replace('file_', '');
        const documentContainer = input.closest('.bg-white.border-2');
        const documentTitle = documentContainer.querySelector('h4')?.textContent || `Documento ${documentoId}`;
        
        if (!input.files || input.files.length === 0) {
            showDocumentError(documentoId, `Debe subir: ${documentTitle}`);
        }
    });
}

// Función para remover todos los errores de documentos
function removeAllDocumentErrors() {
    const errorElements = document.querySelectorAll('[class*="document-error-"]');
    errorElements.forEach(element => {
        element.remove();
    });
    
    // Resetear bordes de contenedores
    const documentContainers = document.querySelectorAll('.bg-white.border-2');
    documentContainers.forEach(container => {
        container.classList.remove('border-red-300');
        container.classList.add('border-gray-300');
    });
}

// Función para mostrar mensaje de documentos completos
function showDocumentosCompletosMessage() {
    // Remover mensaje existente
    const existingMessage = document.querySelector('.documentos-completos-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    // Crear mensaje de éxito
    const messageDiv = document.createElement('div');
    messageDiv.className = 'documentos-completos-message bg-green-50 border border-green-200 rounded-lg p-4 mt-4';
    messageDiv.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-green-800">¡Todos los documentos han sido cargados!</p>
                <p class="text-xs text-green-600 mt-1">Puede continuar con el siguiente paso</p>
            </div>
        </div>
    `;
    
    // Insertar en la sección de documentos
    const documentosSection = document.querySelector('.step-section[data-step]');
    if (documentosSection) {
        const documentosContainer = documentosSection.querySelector('.bg-white.rounded-2xl');
        if (documentosContainer) {
            documentosContainer.appendChild(messageDiv);
            
            // Auto-remover después de 5 segundos
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    }
}

function mostrarArchivosSeleccionados() {
    const inputs = document.querySelectorAll('input[type="file"]');
    let totalArchivos = 0;
    let archivosSeleccionados = 0;
    
    inputs.forEach(input => {
        totalArchivos++;
        if (input.files[0]) {
            archivosSeleccionados++;
        }
    });
    
    console.log(`Archivos seleccionados: ${archivosSeleccionados}/${totalArchivos}`);
    return { total: totalArchivos, seleccionados: archivosSeleccionados };
}

// Función para inicializar la validación de documentos
function initDocumentosValidation() {
    // Validar documentos al cargar la página
    validateDocumentosCompletos();
    
    // Agregar listeners para cambios en inputs de archivo
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', () => {
            setTimeout(() => {
                validateDocumentosCompletos();
            }, 100);
        });
    });
}

// Función para validar documentos antes de avanzar al siguiente paso
function validateDocumentosBeforeNext() {
    const documentosInputs = document.querySelectorAll('input[type="file"]');
    let documentosFaltantes = [];
    
    documentosInputs.forEach(input => {
        if (!input.files || input.files.length === 0) {
            const documentoId = input.id.replace('file_', '');
            const documentContainer = input.closest('.bg-white.border-2');
            const documentTitle = documentContainer.querySelector('h4')?.textContent || `Documento ${documentoId}`;
            documentosFaltantes.push(documentTitle);
        }
    });
    
    if (documentosFaltantes.length > 0) {
        // Mostrar errores individuales
        showDocumentosFaltantesErrors();
        
        // Scroll a la primera sección de documentos
        const documentosSection = document.querySelector('.step-section[data-step]');
        if (documentosSection) {
            documentosSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        return false;
    }
    
    return true;
}

// Hacer funciones disponibles globalmente
window.handleFileUpload = handleFileUpload;
window.mostrarArchivosSeleccionados = mostrarArchivosSeleccionados;
window.validateDocumentosCompletos = validateDocumentosCompletos;
window.showDocumentosCompletosMessage = showDocumentosCompletosMessage;
window.showDocumentError = showDocumentError;
window.removeDocumentError = removeDocumentError;
window.showDocumentosFaltantesErrors = showDocumentosFaltantesErrors;
window.removeAllDocumentErrors = removeAllDocumentErrors;
window.validateDocumentosBeforeNext = validateDocumentosBeforeNext;
window.initDocumentosValidation = initDocumentosValidation;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    initDocumentosValidation();
}); 