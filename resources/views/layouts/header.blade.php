<nav class="bg-white border-b border-gray-200">
    <div class="w-full">
        <div class="flex justify-between h-16">
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

            <div class="md:hidden flex items-center space-x-2 pr-2">
                <!-- Botón de notificaciones móvil -->
                <div class="relative" x-data="{ 
                    count: 0,
                    
                    async loadCount() {
                        try {
                            const response = await fetch('{{ route('notificaciones.conteo-no-leidas') }}');
                            const data = await response.json();
                            this.count = data.count || 0;
                        } catch (error) {
                            console.error('Error loading notification count:', error);
                            this.count = 0;
                        }
                    }
                }" x-init="loadCount()">
                    
                    <a href="{{ route('notificaciones.index') }}" 
                       class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-0 active:outline-none active:ring-0 no-underline select-none touch-manipulation"
                       style="-webkit-tap-highlight-color: transparent; -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                        </svg>
                        
                        <span x-show="count > 0" 
                              x-text="count > 99 ? '99+' : count"
                              class="absolute -top-0.5 -right-0.5 min-w-[16px] h-[16px] bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium text-[10px]">
                        </span>
                    </a>


                </div>

                <!-- Menú de usuario móvil -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="group flex items-center p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <span class="sr-only">Abrir menú de usuario</span>
                        <div class="relative">
                            <span
                                class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gradient-to-br from-primary to-primary-dark text-white shadow-md group-hover:shadow-lg transition-all duration-200 group-hover:scale-105">
                                <span class="text-sm font-semibold leading-none">
                                    {{ auth()->check() ? strtoupper(substr(auth()->user()->nombre, 0, 1)) : 'I' }}</span>
                            </span>
                        </div>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         class="origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-gray-200 divide-y divide-gray-100 focus:outline-none z-50 overflow-hidden"
                         style="display: none;">

                        <div class="px-4 py-3 bg-gradient-to-r from-primary/10 to-primary-dark/10">
                            <div class="flex items-center space-x-3">
                                <span
                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-primary to-primary-dark text-white shadow-md">
                                    <span class="text-base font-semibold leading-none">
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

                        <div class="py-1">
                            <a href="{{ route('profile.index') }}"
                                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition-all duration-200">
                                <div
                                    class="flex-shrink-0 w-6 h-6 bg-primary/10 rounded flex items-center justify-center mr-3 group-hover:bg-primary/20 transition-colors duration-200">
                                    <svg class="w-3 h-3 text-primary" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium text-sm">Mi Perfil</div>
                                </div>
                            </a>
                        </div>

                        <div class="py-1">
                            <form method="POST" action="/cerrar-sesion" class="inline">
                                @csrf
                                <button type="submit"
                                    class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-all duration-200">
                                    <div
                                        class="flex-shrink-0 w-6 h-6 bg-red-50 rounded flex items-center justify-center mr-3 group-hover:bg-red-100 transition-colors duration-200">
                                        <svg class="w-3 h-3 text-red-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-medium text-sm">Cerrar Sesión</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-4 pr-4">
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
                            class="relative p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                        </svg>
                        
                        <span x-show="count > 0" 
                              x-text="count > 99 ? '99+' : count"
                              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium">
                        </span>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 top-full mt-2 bg-white rounded-lg shadow-lg border border-gray-200 z-50 overflow-hidden"
                         style="display: none; width: 32rem !important; min-width: 28rem !important;">
                        
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                                <span class="text-xs text-gray-500">Se marcan como leídas</span>
                            </div>
                        </div>

                        <div class="max-h-[28rem] overflow-y-auto">
                            <template x-if="loading">
                                <div class="flex flex-col items-center justify-center py-12">
                                    <div class="w-8 h-8 border-2 border-gray-300 border-t-gray-600 rounded-full animate-spin"></div>
                                    <p class="text-sm text-gray-500 mt-3">Cargando...</p>
                                </div>
                            </template>

                            <template x-if="!loading && (!notificaciones || notificaciones.length === 0)">
                                <div class="flex flex-col items-center justify-center py-10 px-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-semibold text-gray-900 mb-1">Todo al día</h4>
                                    <p class="text-xs text-gray-500 text-center">No tienes notificaciones pendientes</p>
                                </div>
                            </template>

                            <div class="divide-y divide-gray-100">
                                <template x-for="notificacion in (notificaciones || [])" :key="notificacion.id">
                                    <div class="p-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                         :class="!notificacion.leida ? 'bg-blue-50 border-l-3 border-blue-400' : ''">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between mb-1">
                                                    <h4 class="text-sm font-semibold text-gray-900" x-text="notificacion.titulo || 'Notificación'"></h4>
                                                    <div x-show="!notificacion.leida" class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                </div>
                                                <p class="text-xs text-gray-600 mb-2 leading-relaxed" x-text="notificacion.mensaje || 'Sin mensaje'"></p>
                                                <div class="flex items-center justify-between">
                                                    <span x-text="formatTimeAgo(notificacion.created_at)" class="text-xs text-gray-500"></span>
                                                    <div x-show="!notificacion.leida" class="px-2 py-0.5 bg-blue-500 text-white text-xs rounded font-medium">
                                                        Nueva
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <template x-if="notificaciones && notificaciones.length > 0">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                                <a href="{{ route('notificaciones.index') }}" 
                                   class="block w-full text-center py-2 px-4 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded transition-colors duration-200">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    setInterval(async function() {
        try {
            const response = await fetch('{{ route("notificaciones.conteo-no-leidas") }}');
            const data = await response.json();
            const unreadCount = data.count;
            
            document.querySelectorAll('[x-data]').forEach(element => {
                if (element._x_dataStack && element._x_dataStack[0] && typeof element._x_dataStack[0].count !== 'undefined') {
                    element._x_dataStack[0].count = unreadCount;
                }
            });
            
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
    }, 30000);
});
</script>

