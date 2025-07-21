/**
 * Sistema de filtros del lado del cliente para usuarios
 */
class UsersFilter {
    constructor() {
        this.form = document.getElementById('filters-form');
        this.tableContainer = document.getElementById('table-container');
        this.paginationContainer = document.getElementById('pagination-container');
        this.loadingOverlay = document.getElementById('loading-overlay');
        this.allUsers = []; // Almacenar todos los usuarios
        this.filteredUsers = [];
        this.currentPage = 1;
        this.itemsPerPage = 12;
        this.timeout = null;
        this.init();
    }

    init() {
        if (!this.form) return;
        
        this.loadAllUsers();
        this.bindEvents();
    }

    async loadAllUsers() {
        try {
            this.showLoading();
            
            // Hacer una petición para obtener TODOS los usuarios
            const response = await fetch(`${this.form.action}?all=true`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const data = await response.json();
            this.allUsers = data.users || [];
            this.filteredUsers = [...this.allUsers];
            
            this.renderTable();
            this.hideLoading();
            
        } catch (error) {
            console.error('Error al cargar usuarios:', error);
            this.hideLoading();
            this.showError('Error al cargar los datos iniciales.');
        }
    }

    bindEvents() {
        // Auto-filter con debounce
        this.form.addEventListener('input', (e) => {
            if (e.target.type === 'text' || e.target.tagName === 'SELECT') {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => this.filterUsers(), 300);
            }
        });

        // Submit del formulario
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.filterUsers();
        });
    }

    filterUsers() {
        const formData = new FormData(this.form);
        const filters = {
            search: formData.get('search')?.toLowerCase() || '',
            status: formData.get('status') || '',
            role: formData.get('role') || ''
        };

        this.filteredUsers = this.allUsers.filter(user => {
            // Filtro de búsqueda
            if (filters.search) {
                const searchableText = [
                    user.nombre || '',
                    user.correo || '',
                    user.rfc || ''
                ].join(' ').toLowerCase();
                
                if (!searchableText.includes(filters.search)) {
                    return false;
                }
            }

            // Filtro de estado
            if (filters.status) {
                if (filters.status === 'verified' && !user.fecha_verificacion_correo) return false;
                if (filters.status === 'pending' && user.fecha_verificacion_correo) return false;
            }

            // Filtro de rol
            if (filters.role) {
                const userRoles = user.roles || [];
                if (!userRoles.some(role => role.name === filters.role)) return false;
            }

            return true;
        });

        this.currentPage = 1; // Reset a primera página
        this.renderTable();
        this.updateURL(filters);
    }

    renderTable() {
        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
        const endIndex = startIndex + this.itemsPerPage;
        const paginatedUsers = this.filteredUsers.slice(startIndex, endIndex);

        // Renderizar tabla
        this.tableContainer.innerHTML = this.generateTableHTML(paginatedUsers);
        
        // Renderizar paginación
        this.renderPagination();
    }

    generateTableHTML(users) {
        if (users.length === 0) {
            return this.getEmptyStateHTML();
        }

        return `
            <!-- Vista móvil -->
            <div class="block lg:hidden">
                <div class="divide-y divide-gray-200">
                    ${users.map(user => this.generateMobileUserHTML(user)).join('')}
                </div>
            </div>

            <!-- Vista desktop -->
            <div class="hidden md:block">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-[#B4325E] via-[#93264B] to-[#7a1d37]">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Usuario</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider hidden lg:table-cell">RFC</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider hidden xl:table-cell">Roles</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            ${users.map(user => this.generateDesktopUserHTML(user)).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    generateMobileUserHTML(user) {
        const initials = user.nombre ? user.nombre.charAt(0).toUpperCase() : 'U';
        const isVerified = user.fecha_verificacion_correo;
        const roles = user.roles || [];

        return `
            <div class="p-6 hover:bg-gray-50/50 transition-all duration-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="flex-shrink-0 h-14 w-14 bg-gradient-to-br from-[#B4325E] to-[#93264B] text-white rounded-2xl shadow-lg flex items-center justify-center font-bold text-lg">
                                ${initials}
                            </div>
                            ${isVerified ? `
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            ` : ''}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">${user.nombre || 'Sin nombre'}</div>
                            <div class="text-sm text-gray-600">${user.correo || ''}</div>
                            ${user.rfc ? `<div class="text-xs text-gray-500">RFC: ${user.rfc}</div>` : ''}
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    ${roles.map(role => `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                            ${role.name}
                        </span>
                    `).join('')}
                    
                    ${isVerified ? `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                            Verificado
                        </span>
                    ` : `
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                            Pendiente
                        </span>
                    `}
                </div>
            </div>
        `;
    }

    generateDesktopUserHTML(user) {
        const initials = user.nombre ? user.nombre.charAt(0).toUpperCase() : 'U';
        const isVerified = user.fecha_verificacion_correo;
        const roles = user.roles || [];

        return `
            <tr class="hover:bg-gray-50/50 transition-all duration-200 group">
                <td class="px-4 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="relative flex-shrink-0 h-10 w-10 bg-gradient-to-br from-[#B4325E] to-[#93264B] text-white rounded-lg shadow-md flex items-center justify-center font-bold text-sm">
                            ${initials}
                            ${isVerified ? '<div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border border-white"></div>' : ''}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-semibold text-gray-900">${user.nombre || 'Sin nombre'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap hidden md:table-cell">
                    <div class="text-sm text-gray-900">${user.correo || ''}</div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap hidden lg:table-cell">
                    <div class="text-sm text-gray-900 font-mono">${user.rfc || 'N/A'}</div>
                </td>
                <td class="px-4 py-4 hidden xl:table-cell">
                    <div class="flex flex-wrap gap-1">
                        ${roles.length ? roles.map(role => `
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                ${role.name}
                            </span>
                        `).join('') : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Sin roles</span>'}
                    </div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                    ${isVerified ? `
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Verificado
                        </span>
                    ` : `
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pendiente
                        </span>
                    `}
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-2">
                        <a href="/users/${user.id}/edit" class="p-1.5 text-[#B4325E] hover:text-white hover:bg-[#B4325E] rounded-lg transition-all duration-200" title="Editar usuario">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
        `;
    }

    getEmptyStateHTML() {
        return `
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios</h3>
                <p class="text-gray-500">No se encontraron usuarios que coincidan con los filtros aplicados.</p>
            </div>
        `;
    }

    renderPagination() {
        const totalPages = Math.ceil(this.filteredUsers.length / this.itemsPerPage);
        const startItem = (this.currentPage - 1) * this.itemsPerPage + 1;
        const endItem = Math.min(this.currentPage * this.itemsPerPage, this.filteredUsers.length);

        if (totalPages <= 1) {
            this.paginationContainer.innerHTML = '';
            return;
        }

        this.paginationContainer.innerHTML = `
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center text-sm text-gray-700">
                    <span class="font-medium">${startItem}</span>
                    <span class="mx-2">-</span>
                    <span class="font-medium">${endItem}</span>
                    <span class="mx-2">de</span>
                    <span class="font-medium">${this.filteredUsers.length}</span>
                    <span class="ml-2">usuarios</span>
                </div>

                <div class="flex items-center space-x-1">
                    ${this.generatePaginationButtons(totalPages)}
                </div>
            </div>
        `;

        this.attachPaginationEvents();
    }

    generatePaginationButtons(totalPages) {
        let html = '';

        // Botón anterior
        if (this.currentPage > 1) {
            html += `<button class="pagination-btn relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-[#B4325E] transition-all duration-200 rounded-l-lg" data-page="${this.currentPage - 1}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>`;
        } else {
            html += `<span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>`;
        }

        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            if (i === this.currentPage) {
                html += `<span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#93264B] border border-[#B4325E] cursor-default shadow-md">${i}</span>`;
            } else {
                html += `<button class="pagination-btn relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-[#B4325E] transition-all duration-200" data-page="${i}">${i}</button>`;
            }
        }

        // Botón siguiente
        if (this.currentPage < totalPages) {
            html += `<button class="pagination-btn relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 hover:text-[#B4325E] transition-all duration-200 rounded-r-lg" data-page="${this.currentPage + 1}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>`;
        } else {
            html += `<span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>`;
        }

        return html;
    }

    attachPaginationEvents() {
        document.querySelectorAll('.pagination-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.currentPage = parseInt(btn.dataset.page);
                this.renderTable();
            });
        });
    }

    updateURL(filters) {
        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (filters[key]) params.set(key, filters[key]);
        });
        
        const currentUrl = new URL(window.location);
        currentUrl.search = params.toString();
        window.history.pushState({}, '', currentUrl);
    }

    showLoading() {
        this.loadingOverlay?.classList.remove('hidden');
    }

    hideLoading() {
        this.loadingOverlay?.classList.add('hidden');
    }

    showError(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 bg-red-100 border-l-4 border-red-500 p-4 rounded shadow-lg max-w-sm';
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <p class="text-sm text-red-700">${message}</p>
            </div>
        `;
        
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new UsersFilter();
}); 