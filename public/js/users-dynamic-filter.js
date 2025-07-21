/**
 * Sistema de filtrado dinámico para usuarios
 * Filtra en tiempo real sin recargar la página
 */
class UsersDynamicFilter {
    constructor() {
        this.searchInput = document.getElementById('search-filter');
        this.statusSelect = document.getElementById('status-filter');
        this.roleSelect = document.getElementById('role-filter');
        this.clearButton = document.getElementById('clear-filters');
        this.resultsCount = document.getElementById('results-count');
        
        this.userRows = document.querySelectorAll('.user-row');
        this.totalUsers = this.userRows.length;
        
        this.init();
    }
    
    init() {
        // Event listeners para filtros
        this.searchInput?.addEventListener('input', () => this.applyFilters());
        this.statusSelect?.addEventListener('change', () => this.applyFilters());
        this.roleSelect?.addEventListener('change', () => this.applyFilters());
        this.clearButton?.addEventListener('click', () => this.clearFilters());
        
        // Aplicar filtros iniciales y mostrar contador
        this.applyFilters();
    }
    
    applyFilters() {
        const searchTerm = this.searchInput?.value.toLowerCase().trim() || '';
        const statusFilter = this.statusSelect?.value || '';
        const roleFilter = this.roleSelect?.value.toLowerCase() || '';
        
        let visibleCount = 0;
        
        this.userRows.forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const rfc = row.dataset.rfc || '';
            const status = row.dataset.status || '';
            const roles = row.dataset.roles || '';
            
            // Filtro de búsqueda (nombre, email o RFC)
            const matchesSearch = !searchTerm || 
                name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                rfc.includes(searchTerm);
            
            // Filtro de estado
            const matchesStatus = !statusFilter || status === statusFilter;
            
            // Filtro de rol
            const matchesRole = !roleFilter || roles.includes(roleFilter);
            
            // Mostrar/ocultar fila
            const shouldShow = matchesSearch && matchesStatus && matchesRole;
            
            if (shouldShow) {
                row.style.display = '';
                visibleCount++;
                // Agregar efecto de highlight si hay búsqueda
                if (searchTerm) {
                    this.highlightSearchTerm(row, searchTerm);
                } else {
                    this.removeHighlight(row);
                }
            } else {
                row.style.display = 'none';
                this.removeHighlight(row);
            }
        });
        
        this.updateResultsCount(visibleCount);
        this.showNoResultsMessage(visibleCount === 0);
    }
    
    highlightSearchTerm(row, searchTerm) {
        // Remover highlights anteriores
        this.removeHighlight(row);
        
        // Buscar elementos de texto para highlight
        const textElements = row.querySelectorAll('.font-semibold, .text-sm');
        
        textElements.forEach(element => {
            const text = element.textContent;
            const regex = new RegExp(`(${this.escapeRegex(searchTerm)})`, 'gi');
            
            if (regex.test(text)) {
                element.innerHTML = text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
            }
        });
    }
    
    removeHighlight(row) {
        const highlights = row.querySelectorAll('mark');
        highlights.forEach(mark => {
            const parent = mark.parentNode;
            parent.replaceChild(document.createTextNode(mark.textContent), mark);
            parent.normalize();
        });
    }
    
    escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    updateResultsCount(count) {
        if (this.resultsCount) {
            if (count === this.totalUsers) {
                this.resultsCount.textContent = `${count} usuarios`;
            } else {
                this.resultsCount.textContent = `${count} de ${this.totalUsers} usuarios`;
            }
        }
    }
    
    showNoResultsMessage(show) {
        let noResultsMsg = document.getElementById('no-results-message');
        
        if (show && !noResultsMsg) {
            // Crear mensaje de "no hay resultados"
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'no-results-message';
            noResultsMsg.className = 'p-12 text-center border-t border-gray-200';
            noResultsMsg.innerHTML = `
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron usuarios</h3>
                <p class="text-gray-500">Intenta ajustar los filtros para encontrar lo que buscas.</p>
                <button onclick="userFilter.clearFilters()" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#B4325E] hover:bg-[#93264B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E] transition-colors duration-200">
                    Limpiar filtros
                </button>
            `;
            
            // Insertar después de la tabla
            const tableContainer = document.getElementById('table-container');
            if (tableContainer) {
                tableContainer.appendChild(noResultsMsg);
            }
        } else if (!show && noResultsMsg) {
            noResultsMsg.remove();
        }
    }
    
    clearFilters() {
        // Limpiar todos los filtros
        if (this.searchInput) this.searchInput.value = '';
        if (this.statusSelect) this.statusSelect.value = '';
        if (this.roleSelect) this.roleSelect.value = '';
        
        // Aplicar filtros (que ahora estarán vacíos)
        this.applyFilters();
        
        // Focus en el campo de búsqueda
        if (this.searchInput) {
            this.searchInput.focus();
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.userFilter = new UsersDynamicFilter();
});

// También inicializar si el script se carga después del DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        window.userFilter = new UsersDynamicFilter();
    });
} else {
    window.userFilter = new UsersDynamicFilter();
}