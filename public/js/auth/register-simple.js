console.log('🚀 SIMPLE: Script cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 SIMPLE: DOM cargado');
    
    // Buscar elementos básicos
    const documentInput = document.getElementById('document');
    const uploadArea = document.getElementById('uploadArea');
    const actionButton = document.getElementById('actionButton');
    
    console.log('🔍 SIMPLE: Elementos encontrados:', {
        documentInput: documentInput ? 'SÍ' : 'NO',
        uploadArea: uploadArea ? 'SÍ' : 'NO', 
        actionButton: actionButton ? 'SÍ' : 'NO'
    });
    
    // Event listener básico para el input
    if (documentInput) {
        documentInput.addEventListener('change', function(e) {
            console.log('📁 SIMPLE: ¡¡¡ARCHIVO SELECCIONADO!!!', e.target.files);
            
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                console.log('📁 SIMPLE: Detalles del archivo:', {
                    name: file.name,
                    size: file.size,
                    type: file.type
                });
                
                // Mostrar nombre de archivo en la UI
                const fileNameElement = document.getElementById('fileName');
                if (fileNameElement) {
                    fileNameElement.textContent = file.name;
                    console.log('✅ SIMPLE: Nombre de archivo actualizado en UI');
                }
                
                // PROCESAR AUTOMÁTICAMENTE
                console.log('🚀 SIMPLE: ¡¡¡INICIANDO PROCESAMIENTO AUTOMÁTICO!!!');
                processFileSimple(file);
            } else {
                console.log('⚠️ SIMPLE: No hay archivos seleccionados');
            }
        });
        console.log('✅ SIMPLE: Event listener agregado al input');
        
        // Limpiar el input para permitir re-subir el mismo archivo
        documentInput.addEventListener('click', function() {
            console.log('🔄 SIMPLE: Limpiando input para permitir re-subida');
            this.value = '';
        });
        
    } else {
        console.error('❌ SIMPLE: No se encontró el input de documento');
    }
    
    // Event listener para el área de carga
    if (uploadArea) {
        uploadArea.addEventListener('click', function(e) {
            console.log('🔍 SIMPLE: Click en área de carga');
            if (documentInput) {
                console.log('📁 SIMPLE: Abriendo selector de archivos...');
                documentInput.click();
            }
        });
        console.log('✅ SIMPLE: Event listener agregado al área de carga');
    }
    
    // Event listener para el botón de acción
    if (actionButton) {
        actionButton.addEventListener('click', function(e) {
            console.log('🔍 SIMPLE: Click en botón de acción');
            e.preventDefault();
            if (documentInput) {
                console.log('📁 SIMPLE: Abriendo selector desde botón...');
                documentInput.click();
            }
        });
        console.log('✅ SIMPLE: Event listener agregado al botón');
    }
});

async function processFileSimple(file) {
    console.log('🔄 SIMPLE: ¡¡¡INICIANDO PROCESAMIENTO DE ARCHIVO!!!');
    console.log('📁 SIMPLE: Archivo a procesar:', {
        nombre: file.name,
        tamaño: file.size + ' bytes',
        tipo: file.type
    });
    
    // Mostrar indicador visual inmediatamente
    const processingStatus = document.getElementById('processingStatus');
    if (processingStatus) {
        processingStatus.classList.remove('hidden');
        console.log('👁️ SIMPLE: Indicador de procesamiento mostrado');
    }
    
    try {
        // Crear FormData
        const formData = new FormData();
        formData.append('pdf', file);
        console.log('📦 SIMPLE: FormData creado con archivo');
        
        // Obtener CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('🔐 SIMPLE: CSRF Token:', csrfToken ? '✅ ENCONTRADO' : '❌ NO ENCONTRADO');
        
        if (!csrfToken) {
            throw new Error('No se encontró el token CSRF');
        }
        
        // Mostrar alert de inicio
        console.log('🚨 SIMPLE: Mostrando alert de procesamiento...');
        alert('🔄 Procesando constancia fiscal: ' + file.name + '\n\n¡Por favor espere!');
        
        // Hacer petición a la API
        console.log('🌐 SIMPLE: ¡¡¡HACIENDO PETICIÓN A /api/extract-qr-url!!!');
        
        const response = await fetch('/api/extract-qr-url', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        console.log('🌐 SIMPLE: ¡¡¡RESPUESTA RECIBIDA!!!', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok,
            headers: [...response.headers.entries()]
        });
        
        // Intentar parsear la respuesta
        let data;
        try {
            const responseText = await response.text();
            console.log('📄 SIMPLE: Texto de respuesta crudo:', responseText);
            data = JSON.parse(responseText);
            console.log('📊 SIMPLE: Datos parseados:', data);
        } catch (parseError) {
            console.error('❌ SIMPLE: Error parseando JSON:', parseError);
            throw new Error('Respuesta no válida del servidor');
        }
        
        // Ocultar indicador de procesamiento
        if (processingStatus) {
            processingStatus.classList.add('hidden');
        }
        
        // Manejar respuesta
        if (data.success && data.url) {
            console.log('✅ SIMPLE: ¡¡¡ÉXITO!!! QR URL extraída:', data.url);
            alert('✅ ¡Éxito!\n\nQR extraído correctamente:\n' + data.url);
            
            // Aquí podrías continuar con el procesamiento SAT
            console.log('🔄 SIMPLE: Continuando con análisis SAT...');
            
        } else {
            console.error('❌ SIMPLE: Error en respuesta:', data);
            alert('❌ Error al procesar la constancia:\n\n' + (data.error || data.message || 'Error desconocido'));
        }
        
    } catch (error) {
        console.error('❌ SIMPLE: ¡¡¡ERROR EN PROCESAMIENTO!!!', {
            message: error.message,
            stack: error.stack,
            error: error
        });
        
        // Ocultar indicador
        if (processingStatus) {
            processingStatus.classList.add('hidden');
        }
        
        alert('❌ Error de conexión:\n\n' + error.message + '\n\nRevisa la consola para más detalles.');
    }
}

console.log('✅ SIMPLE: Funciones definidas'); 