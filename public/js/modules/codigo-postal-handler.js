/**
 * Módulo para búsqueda automática de datos geográficos por código postal
 * Busca estados, municipios y asentamientos basado en el código postal ingresado
 */

class CodigoPostalHandler {
    constructor() {
        // Inicialización diferida - solo configurar variables
        this.initialized = false;
        this.asentamientosData = [];
        this.timeoutId = null;
        
        // Intentar inicializar ahora o configurar para inicialización posterior
        this.init();
    }
    
    /**
     * Inicializa los elementos y event listeners
     * Puede ser llamado múltiples veces de forma segura
     */
    init() {
        // Si ya está inicializado, no hacer nada
        if (this.initialized) return;
        
        // Buscar elementos del DOM
        this.elementos = {
            codigoPostalInput: document.getElementById('codigo_postal_input'),
            estadoSelect: document.getElementById('estado_select'),
            municipioSelect: document.getElementById('municipio_select'),
            asentamientoInput: document.getElementById('asentamiento_input'),
            asentamientoId: document.getElementById('asentamiento_id'),
            asentamientosSugerencias: document.getElementById('asentamientos_sugerencias'),
            asentamientoInfo: document.getElementById('asentamiento_info'),
            asentamientoInfoText: document.getElementById('asentamiento_info_text')
        };
        
        // Verificar si los elementos necesarios están disponibles
        if (!this.elementos.codigoPostalInput) {
            console.log('CodigoPostalHandler: Esperando elementos del DOM...');
            return; // Salir sin marcar como inicializado
        }
        
        // Vincular eventos
        this.bindEvents();
        
        // Marcar como inicializado
        this.initialized = true;
        console.log('CodigoPostalHandler: Inicializado correctamente');
    }
    
    /**
     * Vincula los event listeners
     */
    bindEvents() {
        // Event listener para código postal
        this.elementos.codigoPostalInput.addEventListener('input', (e) => {
            this.handleCodigoPostalInput(e.target.value);
        });
        
        // Event listener para asentamiento
        if (this.elementos.asentamientoInput) {
            this.elementos.asentamientoInput.addEventListener('input', (e) => {
                this.filtrarAsentamientos(e.target.value);
            });
            
            this.elementos.asentamientoInput.addEventListener('focus', () => {
                if (this.asentamientosData.length > 0) {
                    this.mostrarSugerencias();
                }
            });
            
            // Cerrar sugerencias al hacer clic fuera
            document.addEventListener('click', (e) => {
                if (!this.elementos.asentamientoInput.contains(e.target) && 
                    !this.elementos.asentamientosSugerencias.contains(e.target)) {
                    this.ocultarSugerencias();
                }
            });
        }
    }
    
    /**
     * Maneja la entrada del código postal
     */
    handleCodigoPostalInput(codigoPostal) {
        // Limpiar timeout anterior
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
        }
        
        // Limpiar campos dependientes
        this.limpiarCamposDependientes();
        
        // Validar formato del código postal
        if (!/^\d{5}$/.test(codigoPostal)) {
            return;
        }
        
        // Buscar con delay para evitar muchas peticiones
        this.timeoutId = setTimeout(() => {
            this.buscarDatosPorCodigoPostal(codigoPostal);
        }, 500);
    }
    
    /**
     * Busca los datos geográficos por código postal
     */
    async buscarDatosPorCodigoPostal(codigoPostal) {
        try {
            this.mostrarCargando();
            
            const response = await fetch(`/api/codigo-postal/${codigoPostal}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                this.procesarDatosEncontrados(data.data);
            } else {
                this.mostrarError('No se encontraron datos para este código postal');
            }
            
        } catch (error) {
            console.error('Error al buscar código postal:', error);
            this.mostrarError('Error al buscar el código postal. Intente nuevamente.');
        } finally {
            this.ocultarCargando();
        }
    }
    
    /**
     * Procesa los datos encontrados y llena los campos
     */
    procesarDatosEncontrados(datos) {
        if (datos.length === 0) return;
        
        // Tomar el primer resultado para estado y municipio
        const primerDato = datos[0];
        
        // Llenar estado
        this.llenarEstado(primerDato.estado);
        
        // Llenar municipio
        this.llenarMunicipio(primerDato.municipio);
        
        // Guardar asentamientos para autocompletado
        this.asentamientosData = datos.map(item => ({
            id: item.asentamiento_id,
            nombre: item.asentamiento,
            tipo: item.tipo_asentamiento || 'Colonia'
        }));
        
        // Habilitar campo de asentamiento
        this.habilitarAsentamiento();
        
        // Mostrar información
        this.mostrarInfoAsentamientos(datos.length);
    }
    
    /**
     * Llena el campo de estado
     */
    llenarEstado(estado) {
        if (this.elementos.estadoSelect) {
            // Crear opción si no existe
            const option = new Option(estado, estado, true, true);
            this.elementos.estadoSelect.innerHTML = '';
            this.elementos.estadoSelect.appendChild(option);
            this.elementos.estadoSelect.disabled = false;
        }
    }
    
    /**
     * Llena el campo de municipio
     */
    llenarMunicipio(municipio) {
        if (this.elementos.municipioSelect) {
            // Crear opción si no existe
            const option = new Option(municipio, municipio, true, true);
            this.elementos.municipioSelect.innerHTML = '';
            this.elementos.municipioSelect.appendChild(option);
            this.elementos.municipioSelect.disabled = false;
        }
    }
    
    /**
     * Habilita el campo de asentamiento
     */
    habilitarAsentamiento() {
        if (this.elementos.asentamientoInput) {
            this.elementos.asentamientoInput.disabled = false;
            this.elementos.asentamientoInput.placeholder = 'Escriba para buscar asentamientos...';
        }
    }
    
    /**
     * Filtra los asentamientos según el texto ingresado
     */
    filtrarAsentamientos(texto) {
        if (!texto || texto.length < 2) {
            this.ocultarSugerencias();
            return;
        }
        
        const textoLower = texto.toLowerCase();
        const asentamientosFiltrados = this.asentamientosData.filter(asentamiento =>
            asentamiento.nombre.toLowerCase().includes(textoLower)
        );
        
        this.mostrarSugerenciasAsentamientos(asentamientosFiltrados);
    }
    
    /**
     * Muestra las sugerencias de asentamientos
     */
    mostrarSugerenciasAsentamientos(asentamientos) {
        if (!this.elementos.asentamientosSugerencias) return;
        
        if (asentamientos.length === 0) {
            this.ocultarSugerencias();
            return;
        }
        
        const html = asentamientos.map(asentamiento => `
            <div class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0" 
                 data-id="${asentamiento.id}" 
                 data-nombre="${asentamiento.nombre}">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-900">${asentamiento.nombre}</span>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">${asentamiento.tipo}</span>
                </div>
            </div>
        `).join('');
        
        this.elementos.asentamientosSugerencias.innerHTML = html;
        this.elementos.asentamientosSugerencias.classList.remove('hidden');
        
        // Agregar event listeners a las sugerencias
        this.elementos.asentamientosSugerencias.querySelectorAll('[data-id]').forEach(item => {
            item.addEventListener('click', () => {
                this.seleccionarAsentamiento(item.dataset.id, item.dataset.nombre);
            });
        });
    }
    
    /**
     * Selecciona un asentamiento
     */
    seleccionarAsentamiento(id, nombre) {
        if (this.elementos.asentamientoInput) {
            this.elementos.asentamientoInput.value = nombre;
        }
        
        if (this.elementos.asentamientoId) {
            this.elementos.asentamientoId.value = id;
        }
        
        this.ocultarSugerencias();
    }
    
    /**
     * Muestra las sugerencias
     */
    mostrarSugerencias() {
        if (this.elementos.asentamientosSugerencias && this.asentamientosData.length > 0) {
            this.mostrarSugerenciasAsentamientos(this.asentamientosData);
        }
    }
    
    /**
     * Oculta las sugerencias
     */
    ocultarSugerencias() {
        if (this.elementos.asentamientosSugerencias) {
            this.elementos.asentamientosSugerencias.classList.add('hidden');
        }
    }
    
    /**
     * Muestra información sobre los asentamientos encontrados
     */
    mostrarInfoAsentamientos(cantidad) {
        if (this.elementos.asentamientoInfo && this.elementos.asentamientoInfoText) {
            this.elementos.asentamientoInfoText.textContent = 
                `Se encontraron ${cantidad} asentamiento${cantidad > 1 ? 's' : ''} para este código postal.`;
            this.elementos.asentamientoInfo.classList.remove('hidden');
        }
    }
    
    /**
     * Limpia los campos dependientes
     */
    limpiarCamposDependientes() {
        // Limpiar estado
        if (this.elementos.estadoSelect) {
            this.elementos.estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';
            this.elementos.estadoSelect.disabled = true;
        }
        
        // Limpiar municipio
        if (this.elementos.municipioSelect) {
            this.elementos.municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            this.elementos.municipioSelect.disabled = true;
        }
        
        // Limpiar asentamiento
        if (this.elementos.asentamientoInput) {
            this.elementos.asentamientoInput.value = '';
            this.elementos.asentamientoInput.disabled = true;
            this.elementos.asentamientoInput.placeholder = 'Ingrese código postal primero';
        }
        
        if (this.elementos.asentamientoId) {
            this.elementos.asentamientoId.value = '';
        }
        
        // Ocultar sugerencias e info
        this.ocultarSugerencias();
        if (this.elementos.asentamientoInfo) {
            this.elementos.asentamientoInfo.classList.add('hidden');
        }
        
        // Limpiar datos
        this.asentamientosData = [];
    }
    
    /**
     * Muestra indicador de carga
     */
    mostrarCargando() {
        if (this.elementos.codigoPostalInput) {
            this.elementos.codigoPostalInput.classList.add('animate-pulse');
        }
    }
    
    /**
     * Oculta indicador de carga
     */
    ocultarCargando() {
        if (this.elementos.codigoPostalInput) {
            this.elementos.codigoPostalInput.classList.remove('animate-pulse');
        }
    }
    
    /**
     * Muestra mensaje de error
     */
    mostrarError(mensaje) {
        if (this.elementos.asentamientoInfo && this.elementos.asentamientoInfoText) {
            this.elementos.asentamientoInfoText.textContent = mensaje;
            this.elementos.asentamientoInfo.classList.remove('hidden');
            this.elementos.asentamientoInfo.classList.add('bg-red-50', 'border-red-200');
            this.elementos.asentamientoInfo.classList.remove('bg-blue-50', 'border-blue-200');
            
            // Cambiar color del icono
            const icon = this.elementos.asentamientoInfo.querySelector('svg');
            if (icon) {
                icon.classList.add('text-red-600');
                icon.classList.remove('text-blue-600');
            }
            
            // Ocultar después de 5 segundos
            setTimeout(() => {
                if (this.elementos.asentamientoInfo) {
                    this.elementos.asentamientoInfo.classList.add('hidden');
                    this.elementos.asentamientoInfo.classList.remove('bg-red-50', 'border-red-200');
                    this.elementos.asentamientoInfo.classList.add('bg-blue-50', 'border-blue-200');
                    
                    if (icon) {
                        icon.classList.remove('text-red-600');
                        icon.classList.add('text-blue-600');
                    }
                }
            }, 5000);
        }
    }
}

// Exportar para uso global
window.CodigoPostalHandler = CodigoPostalHandler;

// Crear una instancia global
let codigoPostalHandler;

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Crear instancia
    codigoPostalHandler = new CodigoPostalHandler();
    
    // Configurar un observador de mutaciones para detectar cuando la sección de domicilio se hace visible
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const seccionDomicilio = document.getElementById('seccion-2');
                if (seccionDomicilio && !seccionDomicilio.classList.contains('hidden')) {
                    // La sección de domicilio se ha hecho visible, intentar inicializar
                    if (codigoPostalHandler && !codigoPostalHandler.initialized) {
                        codigoPostalHandler.init();
                    }
                }
            }
        });
    });
    
    // Observar cambios en la sección de domicilio
    const seccionDomicilio = document.getElementById('seccion-2');
    if (seccionDomicilio) {
        observer.observe(seccionDomicilio, { attributes: true });
    }
});