/**
 * Sistema de Registro con Constancia Fiscal SAT
 * Maneja la carga, procesamiento y validación de constancias fiscales
 */
class ConstanciaFiscal {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Event listener para carga de archivo
        const documentInput = document.getElementById('document');
        if (documentInput) {
            documentInput.addEventListener('change', (e) => this.handleFileUpload(e));
        }

        // Global functions para compatibilidad
        window.uploadFile = (input) => this.handleFileUpload({target: input});
        window.handleActionButton = () => this.handleActionButton();
        window.togglePassword = (fieldId) => this.togglePassword(fieldId);
        window.mostrarModalSAT = (satData) => this.showModal(satData);
        window.cerrarModalSAT = () => this.closeModal();
        window.continuarRegistro = () => this.continueRegistration();
    }

    async handleFileUpload(event) {
        const files = event.target.files;
        if (!files || files.length === 0) return;

        const file = files[0];
        
        // Actualizar UI
        this.updateFileName(file.name);
        
        // Procesar archivo
        await this.processFile(file);
    }

    updateFileName(fileName) {
        const fileNameElement = document.getElementById('fileName');
        if (fileNameElement) {
            fileNameElement.textContent = fileName;
        }
    }

    async processFile(file) {
        this.showProcessingIndicator(true);

        try {
            const formData = new FormData();
            formData.append('pdf', file);
            
            const response = await this.makeAPIRequest(formData);
            const data = await response.json();

            this.showProcessingIndicator(false);

            if (data.success && data.sat_data) {
                this.handleSuccess(data.sat_data);
            } else {
                this.handleError(data.error || 'No se pudieron extraer los datos fiscales');
            }

        } catch (error) {
            this.showProcessingIndicator(false);
            this.handleError('Error de conexión: ' + error.message);
        }
    }

    async makeAPIRequest(formData) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Intentar ruta API principal
        let response = await fetch('/api/extract-qr-url', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });

        // Si falla con CSRF, usar ruta alternativa
        if (response.status === 419) {
            response = await fetch('/extract-qr-url-web', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
        }

        return response;
    }

    handleSuccess(satData) {
        console.log('Datos SAT recibidos:', satData);
        
        // Llenar campos ocultos
        this.fillHiddenInputs(satData);
        
        // Mostrar modal con datos
        this.showModal(satData);
    }

    handleError(message) {
        alert('Error: ' + message);
    }

    fillHiddenInputs(satData) {
        const fieldMapping = {
            'satRfc': satData.rfc,
            'satNombre': satData.nombre,
            'satCurp': satData.curp,
            'satRegimenFiscal': satData.regimen_fiscal,
            'satEstatus': satData.estatus,
            'satEntidadFederativa': satData.entidad_federativa,
            'satMunicipio': satData.municipio,
            'satEmail': satData.email
        };

        Object.entries(fieldMapping).forEach(([fieldId, value]) => {
            const element = document.getElementById(fieldId);
            if (element && value) {
                element.value = value;
            }
        });
    }

    showModal(satData) {
        const modal = document.getElementById('modalSATDatos');
        const contenido = document.getElementById('contenidoSAT');

        if (!modal || !contenido) {
            console.error('Modal elements not found');
            return;
        }

        // Generar HTML con los datos
        contenido.innerHTML = this.generateModalContent(satData);
        
        // Mostrar modal
        modal.classList.remove('hidden');
        
        // Bind close events
        this.bindModalEvents();
    }

    generateModalContent(satData) {
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

    bindModalEvents() {
        // Close button
        const closeBtn = document.querySelector('#modalSATDatos button[onclick*="cerrarModalSAT"]');
        if (closeBtn) {
            closeBtn.onclick = () => this.closeModal();
        }

        // Continue button  
        const continueBtn = document.querySelector('#modalSATDatos button[onclick*="continuarRegistro"]');
        if (continueBtn) {
            continueBtn.onclick = () => this.continueRegistration();
        }
    }

    closeModal() {
        const modal = document.getElementById('modalSATDatos');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    continueRegistration() {
        this.closeModal();
        
        // Mostrar formulario de registro
        const registrationForm = document.getElementById('registrationForm');
        if (registrationForm) {
            registrationForm.classList.remove('hidden');
        }
    }

    showProcessingIndicator(show) {
        const indicator = document.getElementById('processingStatus');
        if (indicator) {
            if (show) {
                indicator.classList.remove('hidden');
            } else {
                indicator.classList.add('hidden');
            }
        }
    }

    handleActionButton() {
        const input = document.getElementById('document');
        if (input) {
            if (input.files && input.files.length > 0) {
                this.processFile(input.files[0]);
            } else {
                input.click();
            }
        }
    }

    togglePassword(fieldId) {
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
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new ConstanciaFiscal();
}); 