// domicilio-flexible.js - Manejo flexible de domicilio con escritura manual y búsqueda por CP
function initDomicilioFlexible() {
    const inputCP = document.getElementById('codigo_postal_input');
    const btnBuscarCP = document.getElementById('buscar_cp_btn');
    const estadoSelect = document.getElementById('estado_select');
    const municipioInput = document.getElementById('municipio_input');
    const asentamientoInput = document.getElementById('asentamiento_input');
    const localidadInput = document.getElementById('localidad_input');
    const cpSearchInfo = document.getElementById('cp_search_info');
    const cpSearchInfoText = document.getElementById('cp_search_info_text');
    const asentamientoSuggestions = document.getElementById('asentamiento_suggestions');
    const asentamientoList = document.getElementById('asentamiento_list');
    
    let asentamientosEncontrados = [];

    if (!inputCP || !btnBuscarCP) {
        console.error('❌ Elementos de código postal no encontrados');
        return;
    }

    console.log('✅ Inicializando domicilio flexible...');

    // Cargar estados al inicializar
    cargarEstados();

    // Event listener para búsqueda por código postal
    btnBuscarCP.addEventListener('click', function() {
        const cp = inputCP.value.trim();
        if (cp.length === 5 && /^\d{5}$/.test(cp)) {
            buscarPorCodigoPostal(cp);
        } else {
            mostrarInfo('Ingrese un código postal válido de 5 dígitos', 'warning');
        }
    });

    // También buscar al presionar Enter en el campo CP
    inputCP.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            btnBuscarCP.click();
        }
    });

    // Limpiar sugerencias cuando se modifica manualmente el asentamiento
    asentamientoInput.addEventListener('input', function() {
        if (asentamientoSuggestions) {
            asentamientoSuggestions.classList.add('hidden');
        }
    });

    /**
     * Carga la lista de estados
     */
    function cargarEstados() {
        fetch('/api/estados', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && estadoSelect) {
                estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';
                data.data.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.id;
                    option.textContent = estado.nombre;
                    estadoSelect.appendChild(option);
                });
                console.log('✅ Estados cargados:', data.data.length);
            }
        })
        .catch(error => {
            console.error('❌ Error al cargar estados:', error);
        });
    }

    /**
     * Busca información por código postal
     */
    function buscarPorCodigoPostal(cp) {
        console.log('🔍 Buscando código postal:', cp);
        
        // Mostrar estado de carga
        btnBuscarCP.disabled = true;
        btnBuscarCP.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';

        fetch(`/api/codigo-postal/buscar?codigo_postal=${cp}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                procesarDatosBusqueda(data.data);
                mostrarInfo(`Se encontraron ${data.data.asentamientos.length} asentamientos para el CP ${cp}`, 'success');
            } else {
                mostrarInfo(data.message || 'No se encontraron datos para este código postal', 'warning');
            }
        })
        .catch(error => {
            console.error('❌ Error en búsqueda:', error);
            mostrarInfo('Error al buscar el código postal. Puede continuar llenando manualmente.', 'error');
        })
        .finally(() => {
            // Restaurar botón
            btnBuscarCP.disabled = false;
            btnBuscarCP.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>';
        });
    }

    /**
     * Procesa los datos de la búsqueda
     */
    function procesarDatosBusqueda(data) {
        console.log('📦 Procesando datos de búsqueda:', data);

        // Seleccionar estado automáticamente
        if (estadoSelect && data.estado) {
            estadoSelect.value = data.estado.id;
            console.log('✅ Estado seleccionado:', data.estado.nombre);
        }

        // Llenar municipio automáticamente
        if (municipioInput && data.municipio) {
            municipioInput.value = data.municipio.nombre;
            console.log('✅ Municipio llenado:', data.municipio.nombre);
        }

        // Guardar asentamientos encontrados
        asentamientosEncontrados = data.asentamientos || [];

        // Mostrar sugerencias de asentamientos
        if (asentamientosEncontrados.length > 0) {
            mostrarSugerenciasAsentamientos(asentamientosEncontrados);
        }

        // Si solo hay un asentamiento, llenarlo automáticamente
        if (asentamientosEncontrados.length === 1) {
            const asentamiento = asentamientosEncontrados[0];
            asentamientoInput.value = asentamiento.nombre;
            if (localidadInput) {
                localidadInput.value = asentamiento.localidad || '';
            }
            console.log('✅ Asentamiento único seleccionado automáticamente');
        }
    }

    /**
     * Muestra sugerencias de asentamientos
     */
    function mostrarSugerenciasAsentamientos(asentamientos) {
        if (!asentamientoList || !asentamientoSuggestions) return;

        asentamientoList.innerHTML = '';

        asentamientos.forEach(asentamiento => {
            const div = document.createElement('div');
            div.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
            div.innerHTML = `
                <div class="font-medium text-sm">${asentamiento.nombre}</div>
                <div class="text-xs text-gray-500">${asentamiento.tipo}${asentamiento.localidad ? ' - ' + asentamiento.localidad : ''}</div>
            `;
            
            div.addEventListener('click', function() {
                asentamientoInput.value = asentamiento.nombre;
                if (localidadInput && asentamiento.localidad) {
                    localidadInput.value = asentamiento.localidad;
                }
                asentamientoSuggestions.classList.add('hidden');
                console.log('✅ Asentamiento seleccionado:', asentamiento.nombre);
            });

            asentamientoList.appendChild(div);
        });

        asentamientoSuggestions.classList.remove('hidden');
    }

    /**
     * Muestra información al usuario
     */
    function mostrarInfo(mensaje, tipo = 'info') {
        if (!cpSearchInfo || !cpSearchInfoText) return;

        cpSearchInfoText.textContent = mensaje;
        
        // Remover clases anteriores
        cpSearchInfo.classList.remove('bg-blue-50', 'border-blue-200', 'bg-green-50', 'border-green-200', 'bg-yellow-50', 'border-yellow-200', 'bg-red-50', 'border-red-200');
        cpSearchInfoText.classList.remove('text-blue-700', 'text-green-700', 'text-yellow-700', 'text-red-700');

        // Aplicar clases según el tipo
        switch (tipo) {
            case 'success':
                cpSearchInfo.classList.add('bg-green-50', 'border-green-200');
                cpSearchInfoText.classList.add('text-green-700');
                break;
            case 'warning':
                cpSearchInfo.classList.add('bg-yellow-50', 'border-yellow-200');
                cpSearchInfoText.classList.add('text-yellow-700');
                break;
            case 'error':
                cpSearchInfo.classList.add('bg-red-50', 'border-red-200');
                cpSearchInfoText.classList.add('text-red-700');
                break;
            default:
                cpSearchInfo.classList.add('bg-blue-50', 'border-blue-200');
                cpSearchInfoText.classList.add('text-blue-700');
        }

        cpSearchInfo.classList.remove('hidden');

        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            if (cpSearchInfo) {
                cpSearchInfo.classList.add('hidden');
            }
        }, 5000);
    }
}

// Exportar para uso global
window.initDomicilioFlexible = initDomicilioFlexible;