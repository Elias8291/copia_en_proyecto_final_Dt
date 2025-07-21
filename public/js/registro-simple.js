/**
 * Registro Simple con ConstanciaProcessor
 * Uso de la clase unificada para procesar constancias
 */

// Inicializar procesador
const processor = new ConstanciaProcessor({
    debug: false // Cambiar a true para debug
});

// Variables globales
let datosSAT = null;

/**
 * Maneja la subida de archivo
 */
function uploadFile(input) {
    if (input.files && input.files.length > 0) {
        const file = input.files[0];
        
        // Validar archivo
        const validation = processor.validateFile(file);
        if (!validation.valid) {
            alert('Error: ' + validation.error);
            return;
        }
        
        // Actualizar nombre en UI
        updateFileName(file.name);
        
        // Procesar archivo
        processFile(file);
    }
}

/**
 * Procesa el archivo usando el componente unificado
 */
async function processFile(file) {
    const result = await processor.processWithCallbacks(file, {
        onStart: () => showProcessingIndicator(true),
        onSuccess: (satData, qrUrl) => {
            datosSAT = satData;
            fillHiddenInputs(satData);
            showModal(satData);
        },
        onError: (error) => {
            alert('Error: ' + error);
        },
        onFinish: () => showProcessingIndicator(false)
    });
    
    return result;
}

/**
 * Actualiza el nombre del archivo en la UI
 */
function updateFileName(fileName) {
    const fileNameEl = document.getElementById('fileName');
    if (fileNameEl) {
        fileNameEl.textContent = fileName;
    }
}

/**
 * Muestra/oculta el indicador de procesamiento
 */
function showProcessingIndicator(show) {
    const indicator = document.getElementById('processingStatus');
    if (indicator) {
        if (show) {
            indicator.classList.remove('hidden');
        } else {
            indicator.classList.add('hidden');
        }
    }
}

/**
 * Llena los campos ocultos con los datos del SAT
 */
function fillHiddenInputs(satData) {
    const fieldMapping = {
        'satRfc': satData.rfc,
        'satNombre': satData.nombre,
        'satCurp': satData.curp,
        'satRegimenFiscal': satData.regimen_fiscal,
        'satEstatus': satData.estatus,
        'satEntidadFederativa': satData.entidad_federativa,
        'satMunicipio': satData.municipio,
        'satEmail': satData.email,
        'satTipoPersona': satData.tipo_persona,
        'satCp': satData.cp,
        'satColonia': satData.colonia,
        'satNombreVialidad': satData.nombre_vialidad,
        'satNumeroExterior': satData.numero_exterior,
        'satNumeroInterior': satData.numero_interior
    };

    Object.entries(fieldMapping).forEach(([fieldId, value]) => {
        const element = document.getElementById(fieldId);
        if (element && value) {
            element.value = value;
        }
    });
}

/**
 * Muestra el modal con los datos SAT
 */
function showModal(satData) {
    const modal = document.getElementById('modalSATDatos');
    const contenido = document.getElementById('contenidoSAT');

    if (!modal || !contenido) {
        console.error('Modal no encontrado');
        return;
    }

    // Generar contenido del modal
    contenido.innerHTML = generateModalContent(satData);
    
    // Mostrar modal
    modal.classList.remove('hidden');
}

/**
 * Genera el contenido HTML del modal
 */
function generateModalContent(satData) {
    const campos = [
        { clave: 'nombre', etiqueta: 'Nombre/Razón Social', icono: '🏢' },
        { clave: 'rfc', etiqueta: 'RFC', icono: '🆔' },
        { clave: 'curp', etiqueta: 'CURP', icono: '👤' },
        { clave: 'regimen_fiscal', etiqueta: 'Régimen Fiscal', icono: '🏛️' },
        { clave: 'estatus', etiqueta: 'Estatus', icono: '📌' },
        { clave: 'entidad_federativa', etiqueta: 'Estado', icono: '📍' },
        { clave: 'municipio', etiqueta: 'Municipio', icono: '🏘️' },
        { clave: 'email', etiqueta: 'Email Fiscal', icono: '📧' }
    ];

    let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
    
    campos.forEach(campo => {
        const valor = satData[campo.clave] || 'No disponible';
        html += `
            <div class="bg-gray-50 p-3 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    ${campo.icono} ${campo.etiqueta}
                </label>
                <p class="text-sm text-gray-900 font-semibold">${valor}</p>
            </div>
        `;
    });
    
    html += '</div>';
    return html;
}

/**
 * Cierra el modal
 */
function cerrarModalSAT() {
    const modal = document.getElementById('modalSATDatos');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Continúa con el registro mostrando el formulario
 */
function continuarRegistro() {
    cerrarModalSAT();
    
    // Mostrar formulario de registro
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.classList.remove('hidden');
    }
}

/**
 * Maneja el botón de acción principal
 */
function handleActionButton() {
    const input = document.getElementById('document');
    if (input) {
        if (input.files && input.files.length > 0) {
            processFile(input.files[0]);
        } else {
            input.click();
        }
    }
}

/**
 * Toggle para mostrar/ocultar contraseña
 */
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-toggle-icon');

    if (field && icon) {
        const isPassword = field.type === 'password';
        field.type = isPassword ? 'text' : 'password';
        
        icon.innerHTML = isPassword 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 711.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m6.121-6.121A9.97 9.97 0 0721 12c0 .906-.117 1.785-.337 2.625m-3.846 6.321L9.878 9.878"></path>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.723 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
} 