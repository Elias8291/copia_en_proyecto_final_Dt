console.log('🚀 ULTRA-SIMPLE: Cargando script...');

// DEFINIR FUNCIÓN GLOBAL INMEDIATAMENTE
window.uploadFile = function(input) {
    console.log('📁 ULTRA-SIMPLE: ¡¡¡FUNCIÓN uploadFile EJECUTADA!!!');
    console.log('📁 ULTRA-SIMPLE: Input recibido:', input);
    console.log('📁 ULTRA-SIMPLE: Files:', input.files);
    
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        console.log('📁 ULTRA-SIMPLE: ¡¡¡ARCHIVO DETECTADO!!!', {
            nombre: file.name,
            tamaño: file.size,
            tipo: file.type
        });
        
        // Mostrar inmediatamente en la UI
        document.getElementById('fileName').textContent = file.name;
        alert('✅ Archivo cargado: ' + file.name + '\n\n¡Procesando automáticamente!');
        
        // Procesar inmediatamente
        processFile(file);
    } else {
        console.log('❌ ULTRA-SIMPLE: No hay archivos');
        alert('❌ No se detectó ningún archivo');
    }
};

// FUNCIÓN DE PROCESAMIENTO SIMPLE
window.processFile = async function(file) {
    console.log('🔄 ULTRA-SIMPLE: ¡¡¡PROCESANDO!!!', file.name);
    
    try {
        // Mostrar indicador
        const indicator = document.getElementById('processingStatus');
        if (indicator) {
            indicator.classList.remove('hidden');
        }
        
        const formData = new FormData();
        formData.append('pdf', file);
        
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('🔐 ULTRA-SIMPLE: Token:', token ? 'OK' : 'FALTA');
        
        console.log('🌐 ULTRA-SIMPLE: Enviando a API...');
        
        const response = await fetch('/api/extract-qr-url', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        
        console.log('📥 ULTRA-SIMPLE: Respuesta:', response.status);
        const data = await response.json();
        console.log('📊 ULTRA-SIMPLE: Data:', data);
        
        // Ocultar indicador
        if (indicator) {
            indicator.classList.add('hidden');
        }
        
        if (data.success) {
            alert('✅ ¡ÉXITO!\n\nQR extraído: ' + data.url);
        } else {
            alert('❌ Error: ' + (data.error || 'Desconocido'));
        }
        
    } catch (error) {
        console.error('❌ ULTRA-SIMPLE: Error:', error);
        alert('❌ Error: ' + error.message);
        
        // Ocultar indicador
        const indicator = document.getElementById('processingStatus');
        if (indicator) {
            indicator.classList.add('hidden');
        }
    }
};

// FUNCIÓN PARA EL BOTÓN
window.handleActionButton = function() {
    console.log('🔘 ULTRA-SIMPLE: Botón presionado');
    const input = document.getElementById('document');
    if (input) {
        if (input.files && input.files.length > 0) {
            console.log('📁 ULTRA-SIMPLE: Procesando archivo existente...');
            processFile(input.files[0]);
        } else {
            console.log('📁 ULTRA-SIMPLE: Abriendo selector...');
            input.click();
        }
    }
};

console.log('✅ ULTRA-SIMPLE: Funciones definidas');

// CUANDO EL DOM ESTÉ LISTO
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 ULTRA-SIMPLE: DOM listo');
    
    const input = document.getElementById('document');
    const uploadArea = document.getElementById('uploadArea');
    
    if (input) {
        console.log('✅ ULTRA-SIMPLE: Input encontrado, agregando listeners...');
        
        // EVENT LISTENER DIRECTO
        input.onchange = function() {
            console.log('📁 ULTRA-SIMPLE: ¡¡¡ONCHANGE DISPARADO!!!');
            uploadFile(this);
        };
        
        // TAMBIÉN CON addEventListener
        input.addEventListener('change', function() {
            console.log('📁 ULTRA-SIMPLE: ¡¡¡ADDEVENTLISTENER CHANGE!!!');
            uploadFile(this);
        });
        
        console.log('✅ ULTRA-SIMPLE: Event listeners agregados');
    } else {
        console.error('❌ ULTRA-SIMPLE: Input NO encontrado');
    }
    
    // Click en área de carga
    if (uploadArea) {
        uploadArea.addEventListener('click', function() {
            console.log('🔘 ULTRA-SIMPLE: Click en área de carga');
            if (input) {
                input.click();
            }
        });
        console.log('✅ ULTRA-SIMPLE: Click listener en área de carga');
    }
}); 