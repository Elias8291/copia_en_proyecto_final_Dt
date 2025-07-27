if (typeof ActividadesBuscar === 'undefined') {
    class ActividadesBuscar {
        constructor() {
            this.buscarInput = document.getElementById('buscador-actividad');
            this.resultadosContainer = document.getElementById('resultados-actividades');
            this.seleccionadasContainer = document.getElementById('actividades-seleccionadas');
            this.actividadesSeleccionadas = [];
            this.actividadesTemporales = []; // Para actividades que aún no se han creado
            this.timeout = null;
            this.isUpdating = false;
            this.currentQuery = '';
            
            this.init();
        }
        
        init() {
            if (!this.buscarInput || !this.resultadosContainer || !this.seleccionadasContainer) return;
            
            this.buscarInput.addEventListener('input', (e) => this.handleSearch(e));
            this.buscarInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
            this.setupGlobalFunctions();
            this.setupClickOutside();
        }
        
        handleSearch(event) {
            const query = event.target.value.trim();
            this.currentQuery = query;
            clearTimeout(this.timeout);
            
            if (query.length < 2) {
                this.hideResults();
                return;
            }
            
            this.timeout = setTimeout(() => {
                this.searchActividades(query);
            }, 500);
        }
        
        handleKeyDown(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                
                const query = this.buscarInput.value.trim();
                if (query.length >= 2) {
                    // Si hay resultados visibles, seleccionar el primero
                    const primerResultado = this.resultadosContainer.querySelector('.actividad-item');
                    if (primerResultado) {
                        primerResultado.click();
                    } else {
                        // Si no hay resultados, crear actividad temporal directamente
                        this.agregarActividadTemporal(query);
                    }
                }
            }
        }
        
        async searchActividades(query) {
            try {
                const response = await fetch(`/actividades/buscar?nombre=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (Array.isArray(data)) {
                    this.showResults(data);
                } else {
                    this.showError('Respuesta inválida del servidor');
                }
            } catch (error) {
                this.showError('Error al buscar actividades');
            }
        }
        
        showResults(actividades) {
            if (!actividades.length) {
                this.resultadosContainer.innerHTML = '<div class="p-3 text-slate-500 text-sm">No se encontraron actividades</div>';
                this.resultadosContainer.classList.remove('hidden');
                return;
            }
            
            const html = actividades.map(act => {
                if (act.es_nueva) {
                    return `
                        <div class="p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-150 actividad-item nueva-actividad" 
                             data-id="${act.id}" data-nombre="${act.nombre}" data-query="${this.currentQuery}">
                            <div class="text-sm font-medium text-blue-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                ${act.nombre}
                            </div>
                        </div>
                    `;
                } else {
                    return `
                        <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-150 actividad-item" 
                             data-id="${act.id}" data-nombre="${act.nombre}">
                            <div class="text-sm font-medium text-slate-800">${act.nombre}</div>
                        </div>
                    `;
                }
            }).join('');
            
            this.resultadosContainer.innerHTML = html;
            this.resultadosContainer.classList.remove('hidden');
            this.attachItemEvents();
        }
        
        attachItemEvents() {
            this.resultadosContainer.querySelectorAll('.actividad-item').forEach(item => {
                item.addEventListener('click', () => {
                    const id = item.dataset.id;
                    const nombre = item.dataset.nombre;
                    
                    if (item.classList.contains('nueva-actividad')) {
                        this.agregarActividadTemporal(item.dataset.query);
                    } else {
                        this.addActividad({ id, nombre });
                    }
                });
            });
        }
        
        agregarActividadTemporal(nombre) {
            // Generar un ID temporal negativo para distinguir actividades temporales
            const tempId = -(Date.now() + Math.random());
            
            const actividadTemporal = {
                id: tempId,
                nombre: nombre,
                esTemporal: true
            };
            
            this.actividadesTemporales.push(actividadTemporal);
            this.addActividad(actividadTemporal);
        }
        
        addActividad(actividad) {
            if (this.actividadesSeleccionadas.find(a => a.id === actividad.id)) {
                return;
            }
            
            this.actividadesSeleccionadas.push(actividad);
            this.updateSelectedActividades();
            this.clearSearch();
        }
        
        removeActividad(id) {
            console.log('Intentando remover actividad con ID:', id);
            console.log('Actividades antes:', this.actividadesSeleccionadas);
            
            // Convertir el ID a string para comparación consistente
            const idStr = String(id);
            
            // Remover de actividades seleccionadas
            this.actividadesSeleccionadas = this.actividadesSeleccionadas.filter(a => String(a.id) !== idStr);
            
            // Remover de actividades temporales
            this.actividadesTemporales = this.actividadesTemporales.filter(a => String(a.id) !== idStr);
            
            console.log('Actividades después:', this.actividadesSeleccionadas);
            
            this.updateSelectedActividades();
        }
        
        updateSelectedActividades() {
            if (this.isUpdating) return;
            this.isUpdating = true;
            
            try {
                const inputsAnteriores = document.querySelectorAll('input[name="actividades[]"]');
                inputsAnteriores.forEach(input => input.remove());
                
                // También limpiar inputs de nombres temporales
                const inputsTempAnteriores = document.querySelectorAll('input[name^="actividad_temp_nombre_"]');
                inputsTempAnteriores.forEach(input => input.remove());
                
                if (!this.actividadesSeleccionadas.length) {
                    this.seleccionadasContainer.innerHTML = '<p class="text-sm text-slate-500">No se han seleccionado actividades económicas</p>';
                    return;
                }
                
                const html = this.actividadesSeleccionadas.map(act => {
                    // Verificar si es una actividad temporal (pendiente de crear)
                    const esTemporal = act.esTemporal || (act.id && act.id < 0);
                    
                    return `
                        <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-slate-700">${act.nombre}</span>
                                ${esTemporal ? `
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pendiente
                                    </span>
                                ` : ''}
                            </div>
                            <button type="button" onclick="removeActividad('${act.id}')" 
                                    class="text-red-500 hover:text-red-700 transition-colors duration-150 p-1 rounded-full hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                }).join('');
                
                this.seleccionadasContainer.innerHTML = html;
                
                const contenedorActividades = document.getElementById('actividades-hidden-inputs');
                if (contenedorActividades) {
                    const fragment = document.createDocumentFragment();
                    
                    this.actividadesSeleccionadas.forEach(act => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'actividades[]';
                        input.value = act.id;
                        
                        // Para actividades temporales, agregar información adicional
                        if (act.esTemporal || (act.id && act.id < 0)) {
                            input.setAttribute('data-temp-nombre', act.nombre);
                            input.setAttribute('data-temp-flag', 'true');
                            
                            // Crear input adicional con el nombre de la actividad temporal
                            const inputNombre = document.createElement('input');
                            inputNombre.type = 'hidden';
                            inputNombre.name = `actividad_temp_nombre_${act.id}`;
                            inputNombre.value = act.nombre;
                            fragment.appendChild(inputNombre);
                        }
                        
                        fragment.appendChild(input);
                    });
                    
                    contenedorActividades.innerHTML = '';
                    contenedorActividades.appendChild(fragment);
                    
                    const validationField = document.getElementById('actividades-validation');
                    if (validationField) {
                        validationField.value = this.actividadesSeleccionadas.length > 0 ? 'valid' : '';
                    }
                }
            } finally {
                this.isUpdating = false;
            }
        }
        
        clearSearch() {
            this.buscarInput.value = '';
            this.currentQuery = '';
            this.showEmptyState();
        }
        
        hideResults() {
            this.resultadosContainer.classList.add('hidden');
        }
        
        showEmptyState() {
            this.hideResults();
            
            if (this.actividadesSeleccionadas.length === 0) {
                this.seleccionadasContainer.innerHTML = '<p class="text-sm text-slate-500">No se han seleccionado actividades económicas</p>';
            }
        }
        
        showError(message) {
            this.resultadosContainer.innerHTML = `
                <div class="p-3 text-center">
                    <p class="text-red-500 text-sm">${message}</p>
                    <button type="button" onclick="window.actividadesBuscar.showEmptyState()" 
                            class="text-blue-600 text-xs hover:text-blue-800 mt-2">
                        Intentar de nuevo
                    </button>
                </div>
            `;
            this.resultadosContainer.classList.remove('hidden');
        }
        
        setupClickOutside() {
            document.addEventListener('click', (e) => {
                if (!e.target.closest('#buscador-actividad') && !e.target.closest('#resultados-actividades')) {
                    this.resultadosContainer.classList.add('hidden');
                }
            });
        }
        
        setupGlobalFunctions() {
            window.actividadesBuscar = this;
            
            // Asegurar que la función removeActividad esté disponible globalmente
            window.removeActividad = (id) => {
                if (window.actividadesBuscar) {
                    window.actividadesBuscar.removeActividad(id);
                }
            };
        }
        
        // Método para obtener actividades temporales (para usar en el envío del formulario)
        getActividadesTemporales() {
            return this.actividadesTemporales;
        }
        
        // Método para actualizar IDs de actividades temporales con IDs reales
        actualizarIdsActividades(idsReales) {
            if (!Array.isArray(idsReales) || idsReales.length === 0) return;
            
            // Actualizar actividades seleccionadas
            this.actividadesSeleccionadas = this.actividadesSeleccionadas.map(act => {
                if (act.esTemporal || (act.id && act.id < 0)) {
                    // Buscar el ID real correspondiente
                    const index = this.actividadesTemporales.findIndex(temp => temp.id === act.id);
                    if (index >= 0 && idsReales[index]) {
                        return {
                            ...act,
                            id: idsReales[index],
                            esTemporal: false
                        };
                    }
                }
                return act;
            });
            
            // Limpiar actividades temporales
            this.actividadesTemporales = [];
            
            // Actualizar la interfaz
            this.updateSelectedActividades();
        }
    }

    window.ActividadesBuscar = ActividadesBuscar;
}