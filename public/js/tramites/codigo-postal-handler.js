/**
 * Manejador de código postal para formularios de trámites
 * Carga automáticamente los datos de ubicación al ingresar un código postal
 */

// Evitar redeclaración
if (typeof CodigoPostalHandler === 'undefined') {
    class CodigoPostalHandler {
    constructor() {
        this.codigoPostalInput = null;
        this.ubicacionesData = [];
        this.isLoading = false;
        
        this.init();
    }

    init() {
        this.setupElements();
        this.attachEvents();
    }

    setupElements() {
        this.codigoPostalInput = document.getElementById('codigo_postal');
        
        if (!this.codigoPostalInput) {
            console.warn('Campo código postal no encontrado');
            return;
        }
    }

    attachEvents() {
        if (!this.codigoPostalInput) return;

        // Evento para cuando se ingresa el código postal
        this.codigoPostalInput.addEventListener('input', (e) => {
            this.handleCodigoPostalInput(e);
        });

        // Evento para cuando se pierde el foco
        this.codigoPostalInput.addEventListener('blur', (e) => {
            this.handleCodigoPostalBlur(e);
        });
    }

    handleCodigoPostalInput(event) {
        const codigoPostal = event.target.value.trim();
        
        // Limpiar caracteres no numéricos
        const codigoLimpio = codigoPostal.replace(/\D/g, '');
        
        if (codigoLimpio !== codigoPostal) {
            event.target.value = codigoLimpio;
        }

        // Si tiene 5 dígitos, buscar automáticamente
        if (codigoLimpio.length === 5) {
            this.buscarUbicacion(codigoLimpio);
        } else {
            this.limpiarCamposUbicacion();
        }
    }

    handleCodigoPostalBlur(event) {
        const codigoPostal = event.target.value.trim();
        
        if (codigoPostal.length === 5) {
            this.buscarUbicacion(codigoPostal);
        }
    }

    async buscarUbicacion(codigoPostal) {
        if (this.isLoading) return;

        this.isLoading = true;
        this.showLoading();

        try {
            const response = await fetch('/api/ubicacion/codigo-postal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    codigo_postal: codigoPostal
                })
            });

            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.ubicacionesData = data.data;
                this.cargarDatosUbicacion(data.data);
            } else {
                this.limpiarCamposUbicacion();
                this.showError(data.message || 'No se encontraron datos para este código postal');
            }

        } catch (error) {
            console.error('Error al buscar ubicación:', error);
            this.limpiarCamposUbicacion();
            this.showError('Error al buscar la información del código postal');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    cargarDatosUbicacion(ubicaciones) {
        if (ubicaciones.length === 0) return;

        // Tomar el primer resultado (o mostrar selector si hay múltiples)
        const ubicacion = ubicaciones[0];

        // Cargar datos en los campos
        this.setFieldValue('pais', ubicacion.pais);
        this.setFieldValue('estado', ubicacion.estado);
        this.setFieldValue('municipio', ubicacion.municipio);
        this.setFieldValue('localidad', ubicacion.localidad);
        
        // Si hay múltiples asentamientos, crear selector
        if (ubicaciones.length > 1) {
            this.crearSelectorAsentamientos(ubicaciones);
        } else {
            this.setFieldValue('asentamiento', ubicacion.asentamiento);
            this.setFieldValue('tipo_asentamiento', ubicacion.tipo_asentamiento);
        }

        // Guardar IDs para uso posterior
        this.setFieldValue('pais_id', ubicacion.pais_id);
        this.setFieldValue('estado_id', ubicacion.estado_id);
        this.setFieldValue('municipio_id', ubicacion.municipio_id);
        this.setFieldValue('localidad_id', ubicacion.localidad_id);
        this.setFieldValue('asentamiento_id', ubicacion.asentamiento_id);
    }

    crearSelectorAsentamientos(ubicaciones) {
        const asentamientoField = document.getElementById('asentamiento');
        if (!asentamientoField) return;

        // Crear select si no existe
        let select = document.getElementById('asentamiento_select');
        if (!select) {
            select = document.createElement('select');
            select.id = 'asentamiento_select';
            select.name = 'asentamiento_select';
            select.className = asentamientoField.className;
            
            // Reemplazar input con select
            asentamientoField.parentNode.replaceChild(select, asentamientoField);
        }

        // Limpiar opciones
        select.innerHTML = '<option value="">Seleccione un asentamiento</option>';

        // Agregar opciones
        ubicaciones.forEach(ubicacion => {
            const option = document.createElement('option');
            option.value = ubicacion.asentamiento;
            option.textContent = `${ubicacion.asentamiento} (${ubicacion.tipo_asentamiento})`;
            option.dataset.asentamientoId = ubicacion.asentamiento_id;
            option.dataset.tipoAsentamiento = ubicacion.tipo_asentamiento;
            select.appendChild(option);
        });

        // Seleccionar el primero por defecto
        select.selectedIndex = 1;
        this.setFieldValue('tipo_asentamiento', ubicaciones[0].tipo_asentamiento);

        // Evento para cambio de asentamiento
        select.addEventListener('change', (e) => {
            const selectedOption = e.target.selectedOptions[0];
            if (selectedOption && selectedOption.dataset.asentamientoId) {
                this.setFieldValue('asentamiento_id', selectedOption.dataset.asentamientoId);
                this.setFieldValue('tipo_asentamiento', selectedOption.dataset.tipoAsentamiento);
            }
        });
    }

    setFieldValue(fieldName, value) {
        const field = document.getElementById(fieldName) || 
                     document.querySelector(`[name="${fieldName}"]`);
        
        if (field) {
            field.value = value || '';
            
            // Disparar evento change para otros listeners
            field.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }

    limpiarCamposUbicacion() {
        const campos = [
            'pais', 'estado', 'municipio', 'localidad', 'asentamiento', 'tipo_asentamiento',
            'pais_id', 'estado_id', 'municipio_id', 'localidad_id', 'asentamiento_id'
        ];

        campos.forEach(campo => {
            this.setFieldValue(campo, '');
        });

        // Restaurar input de asentamiento si se convirtió en select
        const asentamientoSelect = document.getElementById('asentamiento_select');
        if (asentamientoSelect) {
            const input = document.createElement('input');
            input.type = 'text';
            input.id = 'asentamiento';
            input.name = 'asentamiento';
            input.className = asentamientoSelect.className;
            input.placeholder = 'Asentamiento';
            
            asentamientoSelect.parentNode.replaceChild(input, asentamientoSelect);
        }
    }

    showLoading() {
        if (this.codigoPostalInput) {
            this.codigoPostalInput.classList.add('animate-pulse');
            
            // Agregar indicador de carga
            const loadingIndicator = document.createElement('div');
            loadingIndicator.id = 'cp-loading';
            loadingIndicator.className = 'absolute right-3 top-1/2 transform -translate-y-1/2';
            loadingIndicator.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            
            const parent = this.codigoPostalInput.parentNode;
            if (parent.style.position !== 'relative') {
                parent.style.position = 'relative';
            }
            parent.appendChild(loadingIndicator);
        }
    }

    hideLoading() {
        if (this.codigoPostalInput) {
            this.codigoPostalInput.classList.remove('animate-pulse');
            
            const loadingIndicator = document.getElementById('cp-loading');
            if (loadingIndicator) {
                loadingIndicator.remove();
            }
        }
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'info') {
        // Crear notificación temporal
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Método público para obtener datos de ubicación actuales
    getUbicacionData() {
        return this.ubicacionesData;
    }

    // Método público para validar código postal
    isValidCodigoPostal(codigo) {
        return /^\d{5}$/.test(codigo);
    }
}

    // Hacer disponible globalmente
    window.CodigoPostalHandler = CodigoPostalHandler;

    // Exportar para uso en otros módulos
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = CodigoPostalHandler;
    }
}