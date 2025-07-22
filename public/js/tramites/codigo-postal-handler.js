/**
 * Manejador de código postal para formularios de trámites
 * Carga automáticamente los datos de ubicación al ingresar un código postal
 * También permite selección manual de ubicaciones
 */

// Evitar redeclaración
if (typeof CodigoPostalHandler === 'undefined') {
class CodigoPostalHandler {
    constructor() {
        this.codigoPostalInput = null;
        this.estadoSelect = null;
        this.municipioSelect = null;
        this.localidadSelect = null;
        this.ubicacionesData = [];
        this.estadosData = [];
        this.isLoading = false;
        this.modoAutomatico = true;
        
        this.init();
    }

    init() {
        this.setupElements();
        this.attachEvents();
        this.cargarEstados();
    }

    setupElements() {
        this.codigoPostalInput = document.getElementById('codigo_postal');
        this.estadoSelect = document.getElementById('estado');
        this.municipioSelect = document.getElementById('municipio');
        this.localidadSelect = document.getElementById('localidad');
        
        if (!this.codigoPostalInput) {
            console.warn('Campo código postal no encontrado');
            return;
        }
    }

    attachEvents() {
        if (!this.codigoPostalInput) return;

        // Eventos para cambio de modo
        const modoCodigoPostal = document.getElementById('modo_codigo_postal');
        const modoManual = document.getElementById('modo_manual');
        
        if (modoCodigoPostal) {
            modoCodigoPostal.addEventListener('change', () => {
                this.cambiarModo(true);
            });
        }
        
        if (modoManual) {
            modoManual.addEventListener('change', () => {
                this.cambiarModo(false);
            });
        }

        // Evento para cuando se ingresa el código postal
        this.codigoPostalInput.addEventListener('input', (e) => {
            if (this.modoAutomatico) {
                this.handleCodigoPostalInput(e);
            }
        });

        // Evento para cuando se pierde el foco
        this.codigoPostalInput.addEventListener('blur', (e) => {
            if (this.modoAutomatico) {
                this.handleCodigoPostalBlur(e);
            }
        });

        // Eventos para selección manual
        if (this.estadoSelect) {
            this.estadoSelect.addEventListener('change', (e) => {
                if (!this.modoAutomatico) {
                    this.handleEstadoChange(e);
                }
            });
        }

        if (this.municipioSelect) {
            this.municipioSelect.addEventListener('change', (e) => {
                if (!this.modoAutomatico) {
                    this.handleMunicipioChange(e);
                }
            });
        }

        if (this.localidadSelect) {
            this.localidadSelect.addEventListener('change', (e) => {
                if (!this.modoAutomatico) {
                    this.handleLocalidadChange(e);
                }
            });
        }
    }

    cambiarModo(automatico) {
        this.modoAutomatico = automatico;
        
        if (automatico) {
            // Modo automático por código postal
            this.habilitarModoAutomatico();
        } else {
            // Modo manual
            this.habilitarModoManual();
        }
    }

    habilitarModoAutomatico() {
        // Habilitar código postal
        if (this.codigoPostalInput) {
            this.codigoPostalInput.disabled = false;
            this.codigoPostalInput.classList.remove('bg-gray-100');
            this.codigoPostalInput.classList.add('bg-white');
        }

        // Deshabilitar selects para modo automático
        if (this.estadoSelect) {
            this.estadoSelect.disabled = true;
            this.estadoSelect.classList.add('bg-gray-100');
        }
        
        if (this.municipioSelect) {
            this.municipioSelect.disabled = true;
            this.municipioSelect.classList.add('bg-gray-100');
        }

        if (this.localidadSelect) {
            this.localidadSelect.disabled = true;
            this.localidadSelect.classList.add('bg-gray-100');
        }

        // Actualizar textos de ayuda
        this.actualizarTextosAyuda(true);
    }

    habilitarModoManual() {
        // Mantener código postal habilitado pero sin búsqueda automática
        if (this.codigoPostalInput) {
            this.codigoPostalInput.classList.remove('bg-gray-100');
            this.codigoPostalInput.classList.add('bg-white');
        }

        // Habilitar selects para modo manual
        if (this.estadoSelect) {
            this.estadoSelect.disabled = false;
            this.estadoSelect.classList.remove('bg-gray-100');
            this.estadoSelect.classList.add('bg-white');
        }

        // Actualizar textos de ayuda
        this.actualizarTextosAyuda(false);
        
        // Limpiar campos automáticos pero mantener valores manuales
        this.limpiarCamposAutomaticos();
    }

    actualizarTextosAyuda(automatico) {
        const cpHelpText = document.getElementById('cp-help-text');
        const estadoHelpText = document.getElementById('estado-help-text');
        const municipioHelpText = document.getElementById('municipio-help-text');

        if (automatico) {
            if (cpHelpText) cpHelpText.textContent = '5 dígitos - Se cargarán los datos automáticamente';
            if (estadoHelpText) estadoHelpText.textContent = 'Se carga automáticamente con código postal';
            if (municipioHelpText) municipioHelpText.textContent = 'Se carga automáticamente con código postal';
        } else {
            if (cpHelpText) cpHelpText.textContent = '5 dígitos (requerido)';
            if (estadoHelpText) estadoHelpText.textContent = 'Seleccione un estado de la lista';
            if (municipioHelpText) municipioHelpText.textContent = 'Seleccione primero un estado';
        }
    }

    async cargarEstados() {
        try {
            const response = await fetch('/api/ubicacion/estados', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.estadosData = data.data;
                this.poblarSelectEstados();
            }

        } catch (error) {
            console.error('Error al cargar estados:', error);
        }
    }

    poblarSelectEstados() {
        if (!this.estadoSelect) return;

        // Limpiar opciones existentes excepto la primera
        this.estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';

        // Agregar estados
        this.estadosData.forEach(estado => {
            const option = document.createElement('option');
            option.value = estado.nombre;
            option.textContent = estado.nombre;
            option.dataset.estadoId = estado.id;
            this.estadoSelect.appendChild(option);
        });
    }

    async handleEstadoChange(event) {
        const selectedOption = event.target.selectedOptions[0];
        const estadoId = selectedOption?.dataset.estadoId;

        if (!estadoId) {
            this.limpiarMunicipios();
            this.limpiarLocalidades();
            return;
        }

        // Guardar ID del estado
        this.setFieldValue('estado_id', estadoId);

        // Cargar municipios
        await this.cargarMunicipios(estadoId);
    }

    async cargarMunicipios(estadoId) {
        try {
            this.showLoadingMunicipios();

            const response = await fetch('/api/ubicacion/municipios', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    estado_id: parseInt(estadoId)
                })
            });

            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.poblarSelectMunicipios(data.data);
                this.municipioSelect.disabled = false;
            } else {
                this.limpiarMunicipios();
            }

        } catch (error) {
            console.error('Error al cargar municipios:', error);
            this.limpiarMunicipios();
        } finally {
            this.hideLoadingMunicipios();
        }
    }

    poblarSelectMunicipios(municipios) {
        if (!this.municipioSelect) return;

        this.municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';

        municipios.forEach(municipio => {
            const option = document.createElement('option');
            option.value = municipio.nombre;
            option.textContent = municipio.nombre;
            option.dataset.municipioId = municipio.id;
            this.municipioSelect.appendChild(option);
        });
    }

    async handleMunicipioChange(event) {
        const selectedOption = event.target.selectedOptions[0];
        const municipioId = selectedOption?.dataset.municipioId;

        if (!municipioId) {
            this.limpiarLocalidades();
            return;
        }

        // Guardar ID del municipio
        this.setFieldValue('municipio_id', municipioId);

        // Cargar localidades
        await this.cargarLocalidades(municipioId);
    }

    async cargarLocalidades(municipioId) {
        try {
            this.showLoadingLocalidades();

            const response = await fetch('/api/ubicacion/localidades', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    municipio_id: parseInt(municipioId)
                })
            });

            const data = await response.json();

            if (data.success && data.data.length > 0) {
                this.poblarSelectLocalidades(data.data);
                this.localidadSelect.disabled = false;
            } else {
                this.limpiarLocalidades();
            }

        } catch (error) {
            console.error('Error al cargar localidades:', error);
            this.limpiarLocalidades();
        } finally {
            this.hideLoadingLocalidades();
        }
    }

    poblarSelectLocalidades(localidades) {
        if (!this.localidadSelect) return;

        this.localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>';

        localidades.forEach(localidad => {
            const option = document.createElement('option');
            option.value = localidad.nombre;
            option.textContent = localidad.nombre;
            option.dataset.localidadId = localidad.id;
            this.localidadSelect.appendChild(option);
        });
    }

    handleLocalidadChange(event) {
        const selectedOption = event.target.selectedOptions[0];
        const localidadId = selectedOption?.dataset.localidadId;

        if (localidadId) {
            this.setFieldValue('localidad_id', localidadId);
        }
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
                this.showSuccess('Datos cargados correctamente');
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

        // Tomar el primer resultado
        const ubicacion = ubicaciones[0];

        // Cargar datos en los selects
        this.setSelectValue('estado', ubicacion.estado, ubicacion.estado_id);
        this.setSelectValue('municipio', ubicacion.municipio, ubicacion.municipio_id);
        this.setSelectValue('localidad', ubicacion.localidad, ubicacion.localidad_id);
        
        // Si hay múltiples asentamientos, crear selector
        if (ubicaciones.length > 1) {
            this.crearSelectorAsentamientos(ubicaciones);
        } else {
            this.setFieldValue('asentamiento', ubicacion.asentamiento);
            this.setFieldValue('tipo_asentamiento', ubicacion.tipo_asentamiento);
            this.setFieldValue('asentamiento_id', ubicacion.asentamiento_id);
        }

        // Guardar IDs para uso posterior
        this.setFieldValue('pais_id', ubicacion.pais_id);
        this.setFieldValue('estado_id', ubicacion.estado_id);
        this.setFieldValue('municipio_id', ubicacion.municipio_id);
        this.setFieldValue('localidad_id', ubicacion.localidad_id);
    }

    setSelectValue(selectId, value, dataId) {
        const select = document.getElementById(selectId);
        if (!select) return;

        // Buscar la opción que coincida
        for (let option of select.options) {
            if (option.value === value) {
                option.selected = true;
                break;
            }
        }

        // Si no existe la opción, crearla
        if (select.value !== value) {
            const newOption = document.createElement('option');
            newOption.value = value;
            newOption.textContent = value;
            newOption.dataset[selectId + 'Id'] = dataId;
            newOption.selected = true;
            select.appendChild(newOption);
        }
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
        this.setFieldValue('asentamiento_id', ubicaciones[0].asentamiento_id);

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

        // Limpiar selects
        if (this.estadoSelect) this.estadoSelect.selectedIndex = 0;
        if (this.municipioSelect) {
            this.municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            this.municipioSelect.disabled = true;
        }
        if (this.localidadSelect) {
            this.localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>';
            this.localidadSelect.disabled = true;
        }

        // Restaurar input de asentamiento si se convirtió en select
        const asentamientoSelect = document.getElementById('asentamiento_select');
        if (asentamientoSelect) {
            const input = document.createElement('input');
            input.type = 'text';
            input.id = 'asentamiento';
            input.name = 'asentamiento';
            input.className = asentamientoSelect.className.replace('bg-gray-100', 'bg-white');
            input.placeholder = 'Ingrese el asentamiento/colonia';
            input.required = true;
            
            asentamientoSelect.parentNode.replaceChild(input, asentamientoSelect);
        }
    }

    limpiarCamposAutomaticos() {
        // Solo limpiar campos que se llenan automáticamente, no los manuales
        this.setFieldValue('asentamiento_id', '');
        this.setFieldValue('localidad_id', '');
        this.setFieldValue('municipio_id', '');
        this.setFieldValue('estado_id', '');
    }

    limpiarMunicipios() {
        if (this.municipioSelect) {
            this.municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            this.municipioSelect.disabled = true;
        }
        this.setFieldValue('municipio_id', '');
        this.limpiarLocalidades();
    }

    limpiarLocalidades() {
        if (this.localidadSelect) {
            this.localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>';
            this.localidadSelect.disabled = true;
        }
        this.setFieldValue('localidad_id', '');
    }

    showLoadingMunicipios() {
        if (this.municipioSelect) {
            this.municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';
            this.municipioSelect.disabled = true;
        }
    }

    hideLoadingMunicipios() {
        // El contenido se actualiza en poblarSelectMunicipios
    }

    showLoadingLocalidades() {
        if (this.localidadSelect) {
            this.localidadSelect.innerHTML = '<option value="">Cargando localidades...</option>';
            this.localidadSelect.disabled = true;
        }
    }

    hideLoadingLocalidades() {
        // El contenido se actualiza en poblarSelectLocalidades
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
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.codigoPostalHandler = new CodigoPostalHandler();
});

// Exportar para uso en otros módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CodigoPostalHandler;
}