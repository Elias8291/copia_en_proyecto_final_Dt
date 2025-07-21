/**
 * Filtros dinámicos para el módulo de Catálogo de Archivos
 * Similar al sistema de users pero adaptado para archivos
 */

class ArchivosFilter {
    constructor() {
        this.searchInput = document.getElementById('search-filter');
        this.tipoPersonaSelect = document.getElementById('tipo-persona-filter');
        this.tipoArchivoSelect = document.getElementById('tipo-archivo-filter');
        this.estadoSelect = document.getElementById('estado-filter');
        this.clearBtn = document.getElementById('clear-filters');
        this.tableContainer = document.getElementById('table-container');
        this.resultsCount = document.getElementById('results-count');
        
        this.debounceTimeout = null;
        this.isLoading = false;
        
        this.init();
    }

    init() {
        this.attachEventListeners();
        this.updateResultsCount();
    }

    attachEventListeners() {
        // Búsqueda con debounce
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.debounceFilter();
            });
        }

        // Filtros de selección
        [this.tipoPersonaSelect, this.tipoArchivoSelect, this.estadoSelect].forEach(select => {
            if (select) {
                select.addEventListener('change', () => {
                    this.applyFilters();
                });
            }
        });

        // Botón limpiar filtros
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => {
                this.clearAllFilters();
            });
        }

        // Manejar paginación dinámica
        document.addEventListener('click', (e) => {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('.pagination a').href;
                this.loadPage(url);
            }
        });
    }

    debounceFilter() {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => {
            this.applyFilters();
        }, 300);
    }

    async applyFilters() {
        if (this.isLoading) return;

        this.isLoading = true;
        this.showLoading();

        try {
            const params = new URLSearchParams();
            
            // Obtener valores de los filtros
            const search = this.searchInput?.value.trim();
            const tipoPersona = this.tipoPersonaSelect?.value;
            const tipoArchivo = this.tipoArchivoSelect?.value;
            const estado = this.estadoSelect?.value;

            // Agregar parámetros no vacíos
            if (search) params.append('search', search);
            if (tipoPersona) params.append('tipo_persona', tipoPersona);
            if (tipoArchivo) params.append('tipo_archivo', tipoArchivo);
            if (estado) params.append('estado', estado);

            // Hacer petición AJAX
            const url = `/archivos?${params.toString()}`;
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            // Actualizar contenido
            this.updateTable(data.html);
            this.updatePagination(data.pagination);
            this.updateResultsCount(data.total);

        } catch (error) {
            console.error('Error al aplicar filtros:', error);
            this.showError('Error al cargar los datos. Intenta de nuevo.');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    async loadPage(url) {
        if (this.isLoading) return;

        this.isLoading = true;
        this.showLoading();

        try {
            // Agregar filtros actuales a la URL de paginación
            const urlObj = new URL(url);
            
            const search = this.searchInput?.value.trim();
            const tipoPersona = this.tipoPersonaSelect?.value;
            const tipoArchivo = this.tipoArchivoSelect?.value;
            const estado = this.estadoSelect?.value;

            if (search) urlObj.searchParams.set('search', search);
            if (tipoPersona) urlObj.searchParams.set('tipo_persona', tipoPersona);
            if (tipoArchivo) urlObj.searchParams.set('tipo_archivo', tipoArchivo);
            if (estado) urlObj.searchParams.set('estado', estado);

            const response = await fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            this.updateTable(data.html);
            this.updatePagination(data.pagination);
            this.updateResultsCount(data.total);

            // Scroll suave al inicio de la tabla
            this.tableContainer.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });

        } catch (error) {
            console.error('Error al cargar página:', error);
            this.showError('Error al cargar la página. Intenta de nuevo.');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    updateTable(html) {
        if (this.tableContainer) {
            this.tableContainer.innerHTML = html;
        }
    }

    updatePagination(paginationHtml) {
        // La paginación ya está incluida en el HTML de la tabla
        // No necesitamos hacer nada adicional aquí
    }

    updateResultsCount(total = null) {
        if (!this.resultsCount) return;

        if (total !== null) {
            const texto = total === 1 ? 'archivo encontrado' : 'archivos encontrados';
            this.resultsCount.textContent = `${total} ${texto}`;
        } else {
            // Contar desde el DOM actual
            const rows = document.querySelectorAll('tbody tr');
            const emptyRow = document.querySelector('tbody tr td[colspan]');
            const count = emptyRow ? 0 : rows.length;
            const texto = count === 1 ? 'archivo' : 'archivos';
            this.resultsCount.textContent = `${count} ${texto}`;
        }
    }

    clearAllFilters() {
        // Limpiar todos los campos
        if (this.searchInput) this.searchInput.value = '';
        if (this.tipoPersonaSelect) this.tipoPersonaSelect.selectedIndex = 0;
        if (this.tipoArchivoSelect) this.tipoArchivoSelect.selectedIndex = 0;
        if (this.estadoSelect) this.estadoSelect.selectedIndex = 0;

        // Aplicar filtros limpios (mostrar todos)
        this.applyFilters();
    }

    showLoading() {
        if (!this.tableContainer) return;

        // Agregar overlay de carga
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loading-overlay';
        loadingOverlay.className = 'absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10';
        loadingOverlay.innerHTML = `
            <div class="flex flex-col items-center space-y-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#B4325E]"></div>
                <p class="text-sm text-gray-600">Cargando archivos...</p>
            </div>
        `;

        this.tableContainer.style.position = 'relative';
        this.tableContainer.appendChild(loadingOverlay);

        // Deshabilitar controles
        this.toggleControls(false);
    }

    hideLoading() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }

        // Habilitar controles
        this.toggleControls(true);
    }

    toggleControls(enabled) {
        [this.searchInput, this.tipoPersonaSelect, this.tipoArchivoSelect, this.estadoSelect, this.clearBtn]
            .forEach(control => {
                if (control) {
                    control.disabled = !enabled;
                    control.style.opacity = enabled ? '1' : '0.6';
                }
            });
    }

    showError(message) {
        // Crear notificación de error temporal
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-sm';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm">${message}</span>
            </div>
        `;

        document.body.appendChild(errorDiv);

        // Remover después de 5 segundos
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.parentNode.removeChild(errorDiv);
            }
        }, 5000);
    }

    // Método público para obtener filtros actuales
    getCurrentFilters() {
        return {
            search: this.searchInput?.value.trim() || '',
            tipo_persona: this.tipoPersonaSelect?.value || '',
            tipo_archivo: this.tipoArchivoSelect?.value || '',
            estado: this.estadoSelect?.value || ''
        };
    }

    // Método público para aplicar filtros desde código
    setFilters(filters) {
        if (filters.search !== undefined && this.searchInput) {
            this.searchInput.value = filters.search;
        }
        if (filters.tipo_persona !== undefined && this.tipoPersonaSelect) {
            this.tipoPersonaSelect.value = filters.tipo_persona;
        }
        if (filters.tipo_archivo !== undefined && this.tipoArchivoSelect) {
            this.tipoArchivoSelect.value = filters.tipo_archivo;
        }
        if (filters.estado !== undefined && this.estadoSelect) {
            this.estadoSelect.value = filters.estado;
        }

        this.applyFilters();
    }

    // Método para búsqueda de archivos (autocomplete)
    async buscarArchivos(termino, limite = 10) {
        try {
            const response = await fetch(`/archivos/buscar?termino=${encodeURIComponent(termino)}&limite=${limite}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error en búsqueda de archivos:', error);
            return [];
        }
    }

    // Método para obtener estadísticas
    async getEstadisticas() {
        try {
            const response = await fetch('/archivos/estadisticas', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error al obtener estadísticas:', error);
            return null;
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Solo inicializar si estamos en la página de archivos
    if (document.getElementById('table-container')) {
        window.archivosFilter = new ArchivosFilter();
        console.log('Sistema de filtros de archivos inicializado');
    }
});

// Exportar para uso en otros scripts si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ArchivosFilter;
} 