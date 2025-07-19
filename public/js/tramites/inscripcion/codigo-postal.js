// codigo-postal.js - Lógica para autocompletar estado, municipio y asentamiento por código postal
function initCodigoPostal() {
    const inputCP = document.getElementById('codigo_postal_input');
    const estadoSelect = document.getElementById('estado_select');
    const municipioSelect = document.getElementById('municipio_select');
    const asentamientoSelect = document.getElementById('asentamiento_select');
    const asentamientoId = document.getElementById('asentamiento_id');
    const localidadInput = document.getElementById('localidad_input');
    const localidadInfo = document.getElementById('localidad_info');
    const localidadInfoText = document.getElementById('localidad_info_text');
    
    let asentamientos = [];

    if (!inputCP) {
        console.error('❌ Campo de código postal no encontrado');
        return;
    }

    console.log('✅ Inicializando módulo de código postal...');
            console.log('📋 Elementos encontrados:', {
            inputCP: !!inputCP,
            estadoSelect: !!estadoSelect,
            municipioSelect: !!municipioSelect,
            asentamientoSelect: !!asentamientoSelect,
            asentamientoId: !!asentamientoId,
            localidadInput: !!localidadInput,
            localidadInfo: !!localidadInfo,
            localidadInfoText: !!localidadInfoText
        });
    
    // Debugging detallado de elementos
    console.log('🔍 Detalles de elementos:');
    if (estadoSelect) {
        console.log('  - Estado select ID:', estadoSelect.id);
        console.log('  - Estado select actual:', estadoSelect.innerHTML);
    }
    if (municipioSelect) {
        console.log('  - Municipio select ID:', municipioSelect.id);
        console.log('  - Municipio select actual:', municipioSelect.innerHTML);
    }
    if (asentamientoSelect) {
        console.log('  - Asentamiento select ID:', asentamientoSelect.id);
        console.log('  - Asentamiento select actual:', asentamientoSelect.innerHTML);
    }

    // Event listener para el código postal
    inputCP.addEventListener('input', function() {
        const cp = inputCP.value.trim();
        
        // Limpiar campos si está vacío
        if (cp.length === 0) {
            limpiarCampos();
            return;
        }
        
        // Buscar cuando tenga 5 dígitos
        if (cp.length === 5 && /^\d{5}$/.test(cp)) {
            console.log('🔍 Buscando código postal:', cp);
            buscarPorCodigoPostal(cp);
        }
    });

    // Event listener para el select de asentamiento
    if (asentamientoSelect) {
        asentamientoSelect.addEventListener('change', function() {
            const selectedOption = asentamientoSelect.options[asentamientoSelect.selectedIndex];
            const asentamientoId = selectedOption.value;
            
            if (asentamientoId) {
                // Encontrar el asentamiento seleccionado
                const asentamientoSeleccionado = asentamientos.find(a => a.id == asentamientoId);
                
                if (asentamientoSeleccionado) {
                    // Asignar el ID del asentamiento
                    if (document.getElementById('asentamiento_id')) {
                        document.getElementById('asentamiento_id').value = asentamientoSeleccionado.id;
                    }
                    
                    // Asignar la localidad
                    if (localidadInput) {
                        localidadInput.value = asentamientoSeleccionado.localidad;
                        console.log('✅ Localidad asignada automáticamente:', asentamientoSeleccionado.localidad);
                    }
                    
                    // Mostrar información de la localidad
                    if (localidadInfo && localidadInfoText) {
                        localidadInfoText.textContent = `Localidad asignada: ${asentamientoSeleccionado.localidad}`;
                        localidadInfo.classList.remove('hidden');
                    }
                    
                    console.log('✅ Asentamiento seleccionado:', asentamientoSeleccionado.nombre);
                }
            } else {
                // Limpiar campos si no hay selección
                if (document.getElementById('asentamiento_id')) {
                    document.getElementById('asentamiento_id').value = '';
                }
                if (localidadInput) {
                    localidadInput.value = '';
                }
                if (localidadInfo) {
                    localidadInfo.classList.add('hidden');
                }
            }
        });
    }

    /**
     * Busca información por código postal
     */
    function buscarPorCodigoPostal(cp) {
        console.log('🌐 Realizando petición a:', `/api/codigo-postal/buscar?codigo_postal=${cp}`);
        
        // Limpiar campos antes de la búsqueda
        limpiarCampos();

        // Mostrar estado de carga
        inputCP.classList.add('animate-pulse');

        fetch(`/api/codigo-postal/buscar?codigo_postal=${cp}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('📡 Respuesta del servidor:', response.status, response.statusText);
            inputCP.classList.remove('animate-pulse');
            
            if (!response.ok) {
                throw new Error(`Error ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
                .then(data => {
            console.log('📦 Datos recibidos:', data);
            
                    if (data.success) {
                console.log('✅ Procesando datos exitosos');
                procesarDatos(data.data);
            } else {
                console.error('❌ Error en la respuesta:', data.message);
                mostrarError(data.message || 'No se encontraron datos');
            }
        })
        .catch(error => {
            console.error('💥 Error en la petición:', error);
            inputCP.classList.remove('animate-pulse');
            mostrarError('Error al buscar el código postal. Verifique que sea válido.');
        });
    }

    /**
     * Procesa los datos recibidos
     */
    function procesarDatos(data) {
        console.log('🔄 Procesando datos:', data);
        console.log('🔍 Estado recibido:', data.estado);
        console.log('🔍 Municipio recibido:', data.municipio);
        console.log('🔍 Total asentamientos:', data.asentamientos?.length);
        
        // Actualizar estado
        if (estadoSelect) {
            const estadoHTML = `<option value="${data.estado.id}">${data.estado.nombre}</option>`;
            console.log('📝 HTML del estado:', estadoHTML);
            estadoSelect.innerHTML = estadoHTML;
                        estadoSelect.disabled = false;
            console.log('✅ Estado actualizado:', data.estado.nombre);
        } else {
            console.error('❌ No se encontró el select de estado');
        }

        // Actualizar municipio
        if (municipioSelect) {
            const municipioHTML = `<option value="${data.municipio.id}">${data.municipio.nombre}</option>`;
            console.log('📝 HTML del municipio:', municipioHTML);
            municipioSelect.innerHTML = municipioHTML;
                        municipioSelect.disabled = false;
            console.log('✅ Municipio actualizado:', data.municipio.nombre);
        } else {
            console.error('❌ No se encontró el select de municipio');
        }

        // Guardar asentamientos
        asentamientos = data.asentamientos.map(a => ({
                            id: a.id,
                            nombre: a.nombre,
            tipo: a.tipo,
            localidad: a.localidad
        }));

        console.log('✅ Asentamientos cargados:', asentamientos.length);
        console.log('🔍 Primeros 3 asentamientos:', asentamientos.slice(0, 3));
        console.log('🔍 Ejemplo de asentamiento con localidad:', asentamientos[0]);

        // Llenar y habilitar select de asentamiento
        if (asentamientoSelect) {
            // Limpiar opciones existentes
            asentamientoSelect.innerHTML = '<option value="">Seleccione un asentamiento</option>';
            
            // Agregar opciones de asentamientos
            asentamientos.forEach(asentamiento => {
                const option = document.createElement('option');
                option.value = asentamiento.id;
                option.textContent = `${asentamiento.nombre} (${asentamiento.tipo})`;
                asentamientoSelect.appendChild(option);
            });
            
            asentamientoSelect.disabled = false;
            console.log('✅ Select de asentamiento habilitado con', asentamientos.length, 'opciones');
        } else {
            console.error('❌ No se encontró el select de asentamiento');
        }
    }



    /**
     * Limpia todos los campos
     */
    function limpiarCampos() {
        if (estadoSelect) {
            estadoSelect.innerHTML = '<option value="">Seleccione un estado</option>';
            estadoSelect.disabled = true;
        }
        
        if (municipioSelect) {
            municipioSelect.innerHTML = '<option value="">Seleccione un municipio</option>';
            municipioSelect.disabled = true;
        }
        
        if (asentamientoSelect) {
            asentamientoSelect.innerHTML = '<option value="">Seleccione un asentamiento</option>';
            asentamientoSelect.disabled = true;
        }
        
        if (asentamientoId) {
                        asentamientoId.value = '';
                    }
        
        if (localidadInput) {
            localidadInput.value = '';
        }
        
        if (localidadInfo) {
            localidadInfo.classList.add('hidden');
        }
        

        
        asentamientos = [];
        console.log('🧹 Campos limpiados');
    }

    /**
     * Muestra mensaje de error
     */
    function mostrarError(mensaje) {
        console.error('❌ Error:', mensaje);
        if (inputCP) {
            inputCP.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            setTimeout(() => {
                inputCP.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            }, 3000);
        }
    }
}

// Exportar para uso global
window.initCodigoPostal = initCodigoPostal; 