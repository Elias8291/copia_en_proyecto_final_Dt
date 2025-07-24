if (typeof ActividadesBuscar === 'undefined') {
    class ActividadesBuscar {
        constructor() {
            this.buscarInput = document.getElementById('buscador-actividad');
            this.resultadosContainer = document.getElementById('resultados-actividades');
            this.seleccionadasContainer = document.getElementById('actividades-seleccionadas');
            this.actividadesSeleccionadas = [];
            this.timeout = null;
            this.isUpdating = false;
            
            this.init();
        }
        
        init() {
            if (!this.buscarInput || !this.resultadosContainer || !this.seleccionadasContainer) return;
            
            this.buscarInput.addEventListener('input', (e) => this.handleSearch(e));
            this.setupGlobalFunctions();
            this.setupClickOutside();
        }
        
        handleSearch(event) {
            const query = event.target.value.trim();
            clearTimeout(this.timeout);
            
            if (query.length < 2) {
                this.hideResults();
                return;
            }
            
            this.timeout = setTimeout(() => {
                this.searchActividades(query);
            }, 500);
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
            
            const html = actividades.map(act => `
                <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-150 actividad-item" 
                     data-id="${act.id}" data-nombre="${act.nombre}">
                    <div class="text-sm font-medium text-slate-800">${act.nombre}</div>
                </div>
            `).join('');
            
            this.resultadosContainer.innerHTML = html;
            this.resultadosContainer.classList.remove('hidden');
            this.attachItemEvents();
        }
        
        attachItemEvents() {
            this.resultadosContainer.querySelectorAll('.actividad-item').forEach(item => {
                item.addEventListener('click', () => {
                    const id = item.dataset.id;
                    const nombre = item.dataset.nombre;
                    
                    this.addActividad({ id, nombre });
                });
            });
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
            this.actividadesSeleccionadas = this.actividadesSeleccionadas.filter(a => a.id !== id);
            this.updateSelectedActividades();
        }
        
        updateSelectedActividades() {
            if (this.isUpdating) return;
            this.isUpdating = true;
            
            try {
                const inputsAnteriores = document.querySelectorAll('input[name="actividades[]"]');
                inputsAnteriores.forEach(input => input.remove());
                
                if (!this.actividadesSeleccionadas.length) {
                    this.seleccionadasContainer.innerHTML = '<p class="text-sm text-slate-500">No se han seleccionado actividades económicas</p>';
                    return;
                }
                
                const html = this.actividadesSeleccionadas.map(act => `
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-lg px-4 py-3">
                        <span class="text-sm font-medium text-slate-700">${act.nombre}</span>
                        <button type="button" onclick="window.actividadesBuscar.removeActividad('${act.id}')" 
                                class="text-red-500 hover:text-red-700 transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `).join('');
                
                this.seleccionadasContainer.innerHTML = html;
                
                const contenedorActividades = document.getElementById('actividades-hidden-inputs');
                if (contenedorActividades) {
                    const fragment = document.createDocumentFragment();
                    
                    this.actividadesSeleccionadas.forEach(act => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'actividades[]';
                        input.value = act.id;
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
        }
    }

    window.ActividadesBuscar = ActividadesBuscar;
}