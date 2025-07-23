/**
 * Funcionalidad de búsqueda de actividades económicas
 * Para formularios de trámites
 */

// Evitar redeclaración
if (typeof ActividadesBuscar === 'undefined') {
    class ActividadesBuscar {
    constructor() {
        this.buscarInput = document.getElementById('buscador-actividad');
        this.resultadosContainer = document.getElementById('resultados-actividades');
        this.seleccionadasContainer = document.getElementById('actividades-seleccionadas');
        this.actividadesSeleccionadas = [];
        this.timeout = null;
        
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
            this.showEmptyState();
            return;
        }
        
        this.timeout = setTimeout(() => {
            this.searchActividades(query);
        }, 300);
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
            console.error('Error en búsqueda:', error);
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
        console.log('🎯 INTENTANDO AGREGAR ACTIVIDAD:', actividad);
        
        // Evitar duplicados
        if (this.actividadesSeleccionadas.find(a => a.id === actividad.id)) {
            console.log('⚠️ Actividad duplicada, no se agrega:', actividad.id);
            return;
        }
        
        this.actividadesSeleccionadas.push(actividad);
        console.log('✅ Actividad agregada al array:', this.actividadesSeleccionadas);
        
        this.updateSelectedActividades();
        this.clearSearch();
        
        console.log('🔧 Proceso de agregar actividad completado');
    }
    
    removeActividad(id) {
        this.actividadesSeleccionadas = this.actividadesSeleccionadas.filter(a => a.id !== id);
        this.updateSelectedActividades();
    }
    
    updateSelectedActividades() {
        console.log('🔄 INICIANDO updateSelectedActividades');
        console.log('📊 Actividades a procesar:', this.actividadesSeleccionadas);
        
        // Limpiar TODOS los inputs de actividades anteriores
        const inputsAnteriores = document.querySelectorAll('input[name="actividades[]"]');
        console.log('🗑️ Limpiando inputs anteriores:', inputsAnteriores.length);
        inputsAnteriores.forEach(input => {
            console.log('   Removiendo input:', input.value, input.id);
            input.remove();
        });
        
        if (!this.actividadesSeleccionadas.length) {
            this.seleccionadasContainer.innerHTML = '<p class="text-sm text-slate-500">No se han seleccionado actividades económicas</p>';
            console.log('ℹ️ No hay actividades, mostrando mensaje vacío');
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
        console.log('🎨 HTML de actividades seleccionadas actualizado');
        
        // Agregar inputs hidden al formulario SIEMPRE que haya actividades
        const contenedorActividades = document.getElementById('actividades-hidden-inputs');
        console.log('🔍 Buscando contenedor actividades-hidden-inputs:', !!contenedorActividades);
        
        if (contenedorActividades) {
            // Limpiar contenedor
            console.log('🧹 Limpiando contenedor de inputs hidden');
            contenedorActividades.innerHTML = '';
            
            // Verificar que el contenedor esté en el formulario
            const formulario = contenedorActividades.closest('form');
            console.log('📋 Contenedor está en formulario:', !!formulario);
            if (formulario) {
                console.log('   ID del formulario:', formulario.id);
                console.log('   Action del formulario:', formulario.action);
            }
            
            // Agregar cada actividad como input hidden
            this.actividadesSeleccionadas.forEach((act, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'actividades[]';
                input.value = act.id;
                input.setAttribute('data-actividad-id', act.id);
                input.id = `actividad-input-${act.id}`; // ID único para debugging
                contenedorActividades.appendChild(input);
                
                console.log(`✅ Input hidden ${index + 1} creado:`, {
                    name: input.name,
                    value: input.value,
                    id: act.id,
                    nombre: act.nombre,
                    inputId: input.id
                });
            });
            
            // Verificar que los inputs se crearon correctamente
            const inputsCreados = contenedorActividades.querySelectorAll('input[name="actividades[]"]');
            console.log('🎯 RESUMEN FINAL:');
            console.log(`   Total inputs creados: ${inputsCreados.length}`);
            console.log(`   Actividades seleccionadas: ${this.actividadesSeleccionadas.length}`);
            console.log('   IDs:', this.actividadesSeleccionadas.map(a => a.id));
            console.log('   Nombres:', this.actividadesSeleccionadas.map(a => a.nombre));
            
        } else {
            console.error('❌ CRÍTICO: No se encontró el contenedor actividades-hidden-inputs');
            console.error('   Verificando elementos del DOM...');
            const todosLosIds = ['actividades-hidden-inputs', 'buscador-actividad', 'resultados-actividades', 'actividades-seleccionadas'];
            todosLosIds.forEach(id => {
                const elemento = document.getElementById(id);
                console.error(`   ${id}:`, !!elemento);
            });
        }
        
        console.log('✅ updateSelectedActividades COMPLETADO');
    }
    
    clearSearch() {
        this.buscarInput.value = '';
        this.showEmptyState();
    }
    
    showEmptyState() {
        this.resultadosContainer.classList.add('hidden');
        
        if (this.actividadesSeleccionadas.length === 0) {
            this.seleccionadasContainer.innerHTML = '<p class="text-sm text-slate-500">No se han seleccionado actividades económicas</p>';
        } else {
            this.updateSelectedActividades();
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
        // Hacer disponible globalmente para onclick handlers
        window.actividadesBuscar = this;
    }
    
    // Método para verificar el estado actual (útil para debugging)
    verificarEstado() {
        console.log('🔍 === VERIFICACIÓN DE ESTADO ACTIVIDADES ===');
        console.log('📊 Actividades seleccionadas:', this.actividadesSeleccionadas.length);
        console.log('📋 Lista:', this.actividadesSeleccionadas);
        
        const inputs = document.querySelectorAll('input[name="actividades[]"]');
        console.log('🎯 Inputs hidden encontrados:', inputs.length);
        
        inputs.forEach((input, index) => {
            console.log(`   Input ${index + 1}: value="${input.value}", id="${input.id}"`);
        });
        
        const contenedor = document.getElementById('actividades-hidden-inputs');
        console.log('📦 Contenedor existe:', !!contenedor);
        if (contenedor) {
            console.log('📦 Contenedor HTML:', contenedor.innerHTML);
        }
        
        console.log('✅ === FIN VERIFICACIÓN ===');
        return {
            seleccionadas: this.actividadesSeleccionadas.length,
            inputs: inputs.length,
            contenedor: !!contenedor
        };
    }
}

    // Hacer disponible globalmente
    window.ActividadesBuscar = ActividadesBuscar;
}