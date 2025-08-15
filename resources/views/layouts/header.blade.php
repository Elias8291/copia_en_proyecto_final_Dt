<nav class="bg-white border-b border-gray-200">
    <div class="w-full">
        <div class="flex justify-between h-16">
            <!-- Logo y menú móvil -->
            <div class="flex items-center">
                <button type="button"
                    class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary"
                    @click="sidebarOpen = !sidebarOpen">
                    <span class="sr-only">Toggle sidebar</span>
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-shrink-0 flex items-center ml-3">
                    <a href="#" class="flex items-center hover:opacity-80 transition-opacity duration-200">
                        <img class="h-11 w-auto" src="/images/logoColor.png" alt="Logo">
                    </a>
                </div>
            </div>

            <!-- Notificaciones móvil -->
            <div class="md:hidden" x-data="{ 
                open: false, 
                notificaciones: [], 
                count: 0,
                loading: false,
                
                async loadNotifications() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('notificaciones.recientes-dropdown') }}');
                        const data = await response.json();
                        this.notificaciones = data.notificaciones || [];
                        this.count = data.conteo_no_leidas || 0;
                    } catch (error) {
                        console.error('Error loading notifications:', error);
                        this.notificaciones = [];
                        this.count = 0;
                    } finally {
                        this.loading = false;
                    }
                },
                
                async toggleNotifications() {
                    if (!this.open) {
                        // Al abrir, cargar notificaciones y marcar como leídas
                        await this.loadNotifications();
                        await this.markNotificationsAsRead();
                    }
                    this.open = !this.open;
                },
                
                async markNotificationsAsRead() {
                    const unread = (this.notificaciones || []).filter(n => !n.leida);
                    if (unread.length > 0) {
                        try {
                            const response = await fetch('{{ route('notificaciones.marcar-vistas-leidas') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify({ notificaciones: unread.map(n => n.id) })
                            });
                            const result = await response.json();
                            // Actualizar contador desde el backend
                            this.count = result.conteo_restante ?? 0;
                            // Marcar como leídas localmente solo las afectadas
                            const idsMarcados = new Set((unread || []).map(n => n.id));
                            (this.notificaciones || []).forEach(n => {
                                if (idsMarcados.has(n.id)) n.leida = true;
                            });
                        } catch (error) {
                            console.error('Error marking notifications as read:', error);
                        }
                    }
                },
                
                formatTimeAgo(dateString) {
                    if (!dateString) return 'Reciente';
                    
                    const now = new Date();
                    const date = new Date(dateString);
                    const diffInSeconds = Math.floor((now - date) / 1000);
                    
                    if (diffInSeconds < 60) {
                        return 'Hace un momento';
                    } else if (diffInSeconds < 3600) {
                        const minutes = Math.floor(diffInSeconds / 60);
                        return `Hace ${minutes} min`;
                    } else if (diffInSeconds < 86400) {
                        const hours = Math.floor(diffInSeconds / 3600);
                        return `Hace ${hours} h`;
                    } else if (diffInSeconds < 604800) {
                        const days = Math.floor(diffInSeconds / 86400);
                        return `Hace ${days} día${days > 1 ? 's' : ''}`;
                    } else if (diffInSeconds < 2419200) {
                        const weeks = Math.floor(diffInSeconds / 604800);
                        return `Hace ${weeks} semana${weeks > 1 ? 's' : ''}`;
                    } else {
                        const months = Math.floor(diffInSeconds / 2419200);
                        return `Hace ${months} mes${months > 1 ? 'es' : ''}`;
                    }
                }
            }" x-init="loadNotifications()">
                
                <button @click="toggleNotifications()" 
                        class="relative p-2 text-gray-600 hover:text-[#9d2449] hover:bg-gray-50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 group">
                    <span class="sr-only">Ver notificaciones</span>
                    <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                    </svg>
                    <!-- Badge contador móvil -->
                    <span x-show="count > 0" 
                          x-text="count > 99 ? '99+' : count"
                          class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-gradient-to-r from-red-500 to-red-600 text-white text-xs rounded-full flex items-center justify-center font-semibold shadow-lg animate-pulse">
                    </span>
                </button>

                <!-- Panel móvil elegante -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                     class="absolute right-4 mt-3 w-80 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 z-50 overflow-hidden"
                     style="display: none;">
                    
                    <!-- Header elegante móvil -->
                    <div class="bg-gradient-to-r from-[#9d2449] to-[#8a1f40] px-4 py-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="p-1.5 bg-white/20 rounded-lg">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-white">Notificaciones</h3>
                            </div>
                            <div class="text-xs text-white/80 font-medium">
                                Al abrir se marcan como leídas
                            </div>
                        </div>
                    </div>

                    <!-- Contenido móvil -->
                    <div class="max-h-80 overflow-y-auto">
                        <!-- Estado de carga -->
                        <template x-if="loading">
                            <div class="flex flex-col items-center justify-center py-8">
                                <div class="w-6 h-6 border-2 border-[#9d2449]/20 border-t-[#9d2449] rounded-full animate-spin"></div>
                                <p class="text-xs text-gray-500 mt-2">Cargando...</p>
                            </div>
                        </template>

                        <!-- Sin notificaciones -->
                        <template x-if="!loading && (!notificaciones || notificaciones.length === 0)">
                            <div class="flex flex-col items-center justify-center py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xs font-medium text-gray-900 mb-1">Todo al día</h4>
                                <p class="text-xs text-gray-500">Sin notificaciones pendientes</p>
                            </div>
                        </template>

                        <!-- Lista de notificaciones -->
                        <div class="divide-y divide-gray-100">
                            <template x-for="notificacion in (notificaciones || []).slice(0, 5)" :key="notificacion.id">
                                <div class="p-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer"
                                     :class="!notificacion.leida ? 'bg-blue-50/50' : ''">
                                    <div class="flex items-start space-x-2.5">
                                        <!-- Icono de tipo -->
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm"
                                                 :class="{
                                                     'bg-emerald-100 text-emerald-600': notificacion.tipo === 'exito',
                                                     'bg-amber-100 text-amber-600': notificacion.tipo === 'advertencia',
                                                     'bg-red-100 text-red-600': notificacion.tipo === 'error',
                                                     'bg-blue-100 text-blue-600': notificacion.tipo === 'Tramite',
                                                     'bg-purple-100 text-purple-600': notificacion.tipo === 'Cita',
                                                     'bg-gray-100 text-gray-600': notificacion.tipo === 'informativo'
                                                 }">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <circle cx="10" cy="10" r="6"/>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Contenido de la notificación -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <h4 class="text-xs font-semibold text-gray-900 truncate" x-text="notificacion.titulo || 'Notificación'"></h4>
                                                <div x-show="!notificacion.leida" class="flex-shrink-0 ml-1">
                                                    <div class="w-2 h-2 bg-[#9d2449] rounded-full"></div>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-600 line-clamp-2 mb-1" x-text="notificacion.mensaje || 'Sin mensaje'"></p>
                                            <div class="flex items-center justify-between mt-1">
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span x-text="formatTimeAgo(notificacion.created_at)" class="font-medium"></span>
                                                </div>
                                                <div x-show="!notificacion.leida" class="px-1.5 py-0.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-xs font-semibold rounded-full">
                                                    Nueva
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer móvil -->
                    <template x-if="notificaciones && notificaciones.length > 0">
                        <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                            <a href="{{ route('notificaciones.index') }}" 
                               class="block w-full text-center py-2 px-3 bg-[#9d2449] hover:bg-[#8a1f40] text-white text-xs font-medium rounded-lg transition-colors duration-200">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Notificaciones y menú de usuario -->
            <div class="hidden md:flex items-center space-x-4 pr-4">
                <!-- Notificaciones -->
                <div class="relative" x-data="{ 
                    open: false, 
                    notificaciones: [], 
                    count: 0,
                    loading: false,
                    
                    async loadNotifications() {
                        this.loading = true;
                        try {
                            const response = await fetch('{{ route('notificaciones.recientes-dropdown') }}');
                            const data = await response.json();
                            this.notificaciones = data.notificaciones || [];
                            this.count = data.conteo_no_leidas || 0;
                        } catch (error) {
                            console.error('Error loading notifications:', error);
                            this.notificaciones = [];
                            this.count = 0;
                        } finally {
                            this.loading = false;
                        }
                    },
                    
                                    async toggleNotifications() {
                    if (!this.open) {
                        // Al abrir, cargar notificaciones y marcar como leídas
                        await this.loadNotifications();
                        await this.markNotificationsAsRead();
                    }
                    this.open = !this.open;
                },
                    
                    async markNotificationsAsRead() {
                        const unreadNotifications = (this.notificaciones || []).filter(n => !n.leida);
                        if (unreadNotifications.length > 0) {
                            try {
                                const response = await fetch('{{ route('notificaciones.marcar-vistas-leidas') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    }
                                });
                                const result = await response.json();
                                this.count = 0;
                                // Actualizar estado local
                                (this.notificaciones || []).forEach(notif => {
                                    if (!notif.leida) notif.leida = true;
                                });
                            } catch (error) {
                                console.error('Error marking notifications as read:', error);
                            }
                        }
                    },
                    
                    formatTimeAgo(dateString) {
                        if (!dateString) return 'Reciente';
                        
                        const now = new Date();
                        const date = new Date(dateString);
                        const diffInSeconds = Math.floor((now - date) / 1000);
                        
                        if (diffInSeconds < 60) {
                            return 'Hace un momento';
                        } else if (diffInSeconds < 3600) {
                            const minutes = Math.floor(diffInSeconds / 60);
                            return `Hace ${minutes} min`;
                        } else if (diffInSeconds < 86400) {
                            const hours = Math.floor(diffInSeconds / 3600);
                            return `Hace ${hours} h`;
                        } else if (diffInSeconds < 604800) {
                            const days = Math.floor(diffInSeconds / 86400);
                            return `Hace ${days} día${days > 1 ? 's' : ''}`;
                        } else if (diffInSeconds < 2419200) {
                            const weeks = Math.floor(diffInSeconds / 604800);
                            return `Hace ${weeks} semana${weeks > 1 ? 's' : ''}`;
                        } else {
                            const months = Math.floor(diffInSeconds / 2419200);
                            return `Hace ${months} mes${months > 1 ? 'es' : ''}`;
                        }
                    }
                }" x-init="loadNotifications()">
                    
                    <button @click="toggleNotifications()" 
                            class="relative p-3 text-gray-600 hover:text-[#9d2449] hover:bg-gray-50 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 group">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                        </svg>
                        <!-- Badge contador elegante -->
                        <span x-show="count > 0" 
                              x-text="count > 99 ? '99+' : count"
                              class="absolute -top-1 -right-1 min-w-[20px] h-[20px] bg-gradient-to-r from-red-500 to-red-600 text-white text-xs rounded-full flex items-center justify-center font-semibold shadow-lg animate-pulse">
                        </span>
                    </button>

                    <!-- Panel de notificaciones elegante -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 transform scale-95 translate-y-2"
                         class="absolute right-0 mt-4 w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 z-50 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Header elegante -->
                        <div class="bg-gradient-to-r from-[#9d2449] to-[#8a1f40] px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 bg-white/20 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white">Notificaciones</h3>
                                </div>
                                <div class="text-xs text-white/80 font-medium">
                                    Al abrir se marcan como leídas
                                </div>
                            </div>
                        </div>

                        <!-- Contenido -->
                        <div class="max-h-96 overflow-y-auto">
                            <!-- Estado de carga -->
                            <template x-if="loading">
                                <div class="flex flex-col items-center justify-center py-12">
                                    <div class="w-8 h-8 border-3 border-[#9d2449]/20 border-t-[#9d2449] rounded-full animate-spin"></div>
                                    <p class="text-sm text-gray-500 mt-3">Cargando notificaciones...</p>
                                </div>
                            </template>

                            <!-- Sin notificaciones -->
                            <template x-if="!loading && (!notificaciones || notificaciones.length === 0)">
                                <div class="flex flex-col items-center justify-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">Todo al día</h4>
                                    <p class="text-sm text-gray-500">No tienes notificaciones pendientes</p>
                                </div>
                            </template>

                            <!-- Lista de notificaciones -->
                            <div class="divide-y divide-gray-100">
                                <template x-for="notificacion in (notificaciones || []).slice(0, 6)" :key="notificacion.id">
                                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200 cursor-pointer"
                                         :class="!notificacion.leida ? 'bg-blue-50/50' : ''">
                                        <div class="flex items-start space-x-3">
                                            <!-- Icono de tipo -->
                                            <div class="flex-shrink-0 mt-1">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm"
                                                     :class="{
                                                         'bg-emerald-100 text-emerald-600': notificacion.tipo === 'exito',
                                                         'bg-amber-100 text-amber-600': notificacion.tipo === 'advertencia',
                                                         'bg-red-100 text-red-600': notificacion.tipo === 'error',
                                                         'bg-blue-100 text-blue-600': notificacion.tipo === 'Tramite',
                                                         'bg-purple-100 text-purple-600': notificacion.tipo === 'Cita',
                                                         'bg-gray-100 text-gray-600': notificacion.tipo === 'informativo'
                                                     }">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <circle cx="10" cy="10" r="6"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Contenido de la notificación -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-1">
                                                    <h4 class="text-sm font-semibold text-gray-900 truncate" x-text="notificacion.titulo || 'Notificación'"></h4>
                                                    <div x-show="!notificacion.leida" class="flex-shrink-0 ml-2">
                                                        <div class="w-2.5 h-2.5 bg-[#9d2449] rounded-full"></div>
                                                    </div>
                                                </div>
                                                <p class="text-sm text-gray-600 line-clamp-2 mb-2" x-text="notificacion.mensaje || 'Sin mensaje'"></p>
                                                <div class="flex items-center justify-between mt-2">
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span x-text="formatTimeAgo(notificacion.created_at)" class="font-medium"></span>
                                                    </div>
                                                    <div x-show="!notificacion.leida" class="px-2 py-0.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs font-semibold rounded-full">
                                                        Nueva
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Footer -->
                        <template x-if="notificaciones && notificaciones.length > 0">
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                                <a href="{{ route('notificaciones.index') }}" 
                                   class="block w-full text-center py-2 px-4 bg-[#9d2449] hover:bg-[#8a1f40] text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open"
                            class="group flex items-center max-w-xs text-sm rounded-full hover:ring-2 hover:ring-primary/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                            id="user-menu-button">
                            <span class="sr-only">Abrir menú de usuario</span>
                            <div class="relative">
                                <span
                                    class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-gradient-to-br from-primary to-primary-dark text-white shadow-md group-hover:shadow-lg transition-all duration-200 group-hover:scale-105">
                                    <span class="text-sm font-semibold leading-none">
                                        {{ auth()->check() ? strtoupper(substr(auth()->user()->nombre, 0, 1)) : 'I' }}</span>
                                </span>
                            </div>
                        </button>
                    </div>
                    <div x-show="open" 
                         @click.away="open = false"
                         class="origin-top-right absolute right-0 mt-3 w-64 rounded-xl shadow-xl bg-white ring-1 ring-gray-200 divide-y divide-gray-100 focus:outline-none z-50 overflow-hidden"
                         style="display: none;">

                        <!-- Header del usuario -->
                        <div class="px-4 py-4 bg-gradient-to-r from-primary/10 to-primary-dark/10">
                            <div class="flex items-center space-x-3">
                                <span
                                    class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gradient-to-br from-primary to-primary-dark text-white shadow-md">
                                    <span class="text-lg font-semibold leading-none">
                                        {{ auth()->check() ? strtoupper(substr(auth()->user()->nombre, 0, 1)) : 'I' }}
                                    </span>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ auth()->check() ? auth()->user()->nombre : 'Invitado' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Opciones principales -->
                        <div class="py-2">
                            <a href="{{ route('profile.index') }}"
                                class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition-all duration-200">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary/20 transition-colors duration-200">
                                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium">Mi Perfil</div>
                                    <div class="text-xs text-gray-500">Configurar cuenta</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary transition-colors duration-200"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <!-- Cerrar sesión -->
                        <div class="py-2">
                            <form method="POST" action="/cerrar-sesion" class="inline">
                                @csrf
                                <button type="submit"
                                    class="group flex w-full items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium">Cerrar Sesión</div>
                                        <div class="text-xs text-red-400">Salir del sistema</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Script para actualizar notificaciones automáticamente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar contador de notificaciones cada 30 segundos
    setInterval(async function() {
        try {
            // Obtener conteo actualizado de notificaciones no leídas
            const response = await fetch('{{ route('notificaciones.conteo-no-leidas') }}');
            const data = await response.json();
            const unreadCount = data.count;
            
            // Actualizar badges tanto en desktop como móvil usando Alpine.js
            // Buscar componentes Alpine.js que tengan la propiedad count
            document.querySelectorAll('[x-data]').forEach(element => {
                if (element._x_dataStack && element._x_dataStack[0] && typeof element._x_dataStack[0].count !== 'undefined') {
                    element._x_dataStack[0].count = unreadCount;
                }
            });
            
            // También actualizar badges visibles directamente
            const badges = document.querySelectorAll('[x-text="count > 99 ? \'99+\' : count"]');
            badges.forEach(badge => {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            });
        } catch (error) {
            console.error('Error updating notification count:', error);
        }
    }, 30000); // 30 segundos
});
</script>
