/**
 * Módulo para la búsqueda y gestión de actividades económicas
 * en el formulario de trámites
 */
class ActividadesManager {
    constructor() {
        this.actividadesSeleccionadas = [];
        this.timeout = null;
        this.init();
    }

    init() {
        this.buscarInput = document.getElementById('buscar-actividad');
        this.container = document.getElementById('actividades-container');
        
        if (this.buscarInput) {
            this.setupEventListeners();
        }
    }

    setupEventListeners() {
        this.buscarInput.addEventListener('input', (e) => {
            this.handleSearch(e.target.value.trim());
        });
    }

    handleSearch(query) {
        clearTimeout(this.timeout);
        
        if (query.length < 2) {
            this.showEmptyState();
            return;
        }

        this.timeout = setTimeout(() => {
            this.buscarActividades(query);
        }, 300);
    }

    async buscarActividades(query) {
        try {
            this.showLoading();
            
            const response = await fetch(`/api/actividades/buscar?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                this.mostrarResultados(data.data);
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            console.error('Error en búsqueda:', error);
            this.showError('Error al buscar actividades. Intente nuevamente.');
        }
    }

    mostrarResultados(actividades) {
        if (actividades.length === 0) {
            this.container.innerHTML = '<p class="text-center text-gray-500 py-4">No se encontraron actividades</p>';
            return;
        }

        const resultados = actividades.map(act => `
            <div class="p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer actividad-item" 
                 data-id="${act.id}" data-nombre="${act.nombre}" data-codigo="${act.codigo_scian || 'N/A'}">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-medium text-sm">${act.nombre}</p>
                        <p class="text-xs text-gray-500">SCIAN: ${act.codigo_scian || 'N/A'} | ${act.sector || 'Sin sector'}</p>
                    </div>
                    <button type="button" class="text-blue-600 text-xs hover:text-blue-800 px-2 py-1 rounded">
                        Agregar
                    </button>
                </div>
            </div>
        `).join('');
        
        this.container.innerHTML = resultados;
        this.attachItemEvents();
    }

    attachItemEvents() {
        this.container.querySelectorAll('.actividad-item').forEach(item => {
            item.addEventListener('click', () => {
                const actividad = {
                    id: item.dataset.id,
                    nombre: item.dataset.nombre,
                    codigo: item.dataset.codigo
                };
                
                this.agregarActividad(actividad);
            });
        });
    }

    agregarActividad(actividad) {
        // Verificar si ya está seleccionada
        if (this.actividadesSeleccionadas.find(a => a.id === actividad.id)) {
            this.showMessage('Esta actividad ya está seleccionada', 'warning');
            return;
        }

        this.actividadesSeleccionadas.push(actividad);
        this.actualizarActividadesSeleccionadas();
        this.limpiarBusqueda();
        this.showMessage('Actividad agregada correctamente', 'success');
    }

    eliminarActividad(id) {
        this.actividadesSeleccionadas = this.actividadesSeleccionadas.filter(a => a.id !== id);
        this.actualizarActividadesSeleccionadas();
        this.showMessage('Actividad eliminada', 'info');
    }

    actualizarActividadesSeleccionadas() {
        if (this.actividadesSeleccionadas.length === 0) {
            this.showEmptyState();
            return;
        }

        const html = this.actividadesSeleccionadas.map(act => `
            <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex-1">
                    <p class="font-medium text-sm">${act.nombre}</p>
                    <p class="text-xs text-gray-600">SCIAN: ${act.codigo}</p>
                </div>
                <button type="button" 
                        class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 transition-colors" 
                        onclick="actividadesManager.eliminarActividad('${act.id}')"
                        title="Eliminar actividad">
                    <i class="fas fa-times"></i>
                </button>
                <input type="hidden" name="actividades[]" value="${act.id}">
            </div>
        `).join('');
        
        this.container.innerHTML = html;
    }

    limpiarBusqueda() {
        this.buscarInput.value = '';
        this.showEmptyState();
    }

    showEmptyState() {
        this.container.innerHTML = `
            <div class="text-sm text-slate-500 text-center py-8 border-2 border-dashed border-slate-200 rounded-xl">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <p>No hay actividades económicas agregadas</p>
                <p class="text-xs">Use el buscador para agregar actividades</p>
            </div>
        `;
    }

    showLoading() {
        this.container.innerHTML = `
            <div class="text-center py-4">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">Buscando actividades...</span>
                </div>
            </div>
        `;
    }

    showError(message) {
        this.container.innerHTML = `
            <div class="text-center py-4">
                <div class="text-red-600 text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    ${message}
                </div>
            </div>
        `;
    }

    showMessage(message, type = 'info') {
        // Crear notificación temporal
        const colors = {
            success: 'bg-green-100 text-green-800 border-green-200',
            warning: 'bg-yellow-100 text-yellow-800 border-yellow-200',
            error: 'bg-red-100 text-red-800 border-red-200',
            info: 'bg-blue-100 text-blue-800 border-blue-200'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg border text-sm z-50 ${colors[type]}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-current opacity-70 hover:opacity-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove después de 3 segundos
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }

    // Método público para obtener actividades seleccionadas
    getActividadesSeleccionadas() {
        return this.actividadesSeleccionadas;
    }

    // Método para cargar actividades existentes (útil para edición)
    cargarActividades(actividades) {
        this.actividadesSeleccionadas = actividades;
        this.actualizarActividadesSeleccionadas();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.actividadesManager = new ActividadesManager();
});