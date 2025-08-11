<!-- Mobile sidebar -->
<div x-show="sidebarOpen" x-cloak class="fixed inset-0 flex z-40 md:hidden">
    <div class="fixed inset-0 bg-gray-600/75 backdrop-blur-sm" @click="sidebarOpen = false"></div>
    <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gradient-to-b from-white to-gray-50">
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button class="flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="sidebarOpen = false">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <nav class="px-3 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z" />
                    </svg>
                    <span class="font-semibold tracking-wide">Dashboard</span>
                </a>

                <!-- Usuarios -->
                @can('usuarios.ver')
                <a href="{{ route('users.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('users.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="font-medium tracking-wide">Usuarios</span>
                </a>
                @endcan

                <!-- Roles -->
                @can('roles.ver')
                <a href="{{ route('roles.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('roles.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('roles.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                    </svg>
                    <span class="font-medium tracking-wide">Roles</span>
                </a>
                @endcan

                <!-- Trámites -->
                <a href="{{ route('tramites.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('tramites.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('tramites.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium tracking-wide">Trámites</span>
                </a>

                <!-- Revisiones -->
                @can('revisiones.ver')
                <a href="{{ route('revisiones.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('revisiones.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('revisiones.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="font-medium tracking-wide">Revisiones</span>
                </a>
                @endcan

                <!-- Citas -->
                @can('citas.ver')
                <a href="{{ route('citas.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('citas.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('citas.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                    <span class="font-medium tracking-wide">Citas</span>
                </a>
                @endcan

                <!-- Archivos -->
                @can('archivos.ver')
                <a href="{{ route('archivos.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('archivos.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('archivos.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10,9 9,9 8,9"/>
                    </svg>
                    <span class="font-medium tracking-wide">Archivos</span>
                </a>
                @endcan

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
                    async markAsReadAndOpen() {
                        if (!this.open) {
                            await this.loadNotifications();
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
                    this.count = result.conteo_restante || 0;
                    (this.notificaciones || []).forEach(notif => {
                        if (!notif.leida) notif.leida = true;
                    });
                                } catch (error) {
                                    console.error('Error marking notifications as read:', error);
                                }
                            }
                        }
                        this.open = !this.open;
                    }
                }">
                    <!-- Botón principal de notificaciones (va a la página) -->
                    <a href="{{ route('notificaciones.index') }}" @click="sidebarOpen = false"
                       class="group flex items-center w-full px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                       {{ request()->routeIs('notificaciones.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <div class="relative">
                            <svg class="{{ request()->routeIs('notificaciones.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3 transition-all duration-200"
                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                            </svg>
                            <!-- Badge de notificaciones no leídas -->
                            <span x-show="count > 0" x-text="count > 99 ? '99+' : count" 
                                  class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                            </span>
                        </div>
                        <span class="font-medium tracking-wide">Notificaciones</span>
                    </a>

                    <!-- Botón para abrir dropdown (pequeño, solo visible al hacer hover) -->
                    <button @click="markAsReadAndOpen()" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-primary opacity-0 group-hover:opacity-100 transition-all duration-200"
                            title="Ver notificaciones recientes">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown de notificaciones -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         @click.away="open = false"
                         class="absolute left-full top-0 ml-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-hidden">
                        
                        <!-- Header del dropdown -->
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900">Notificaciones Recientes</h3>
                                <a href="{{ route('notificaciones.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    Ver todas
                                </a>
                            </div>
                        </div>

                        <!-- Contenido del dropdown -->
                        <div class="max-h-80 overflow-y-auto">
                            <!-- Loading state -->
                            <div x-show="loading" class="p-4 text-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto"></div>
                                <p class="text-sm text-gray-500 mt-2">Cargando notificaciones...</p>
                            </div>

                            <!-- Lista de notificaciones -->
                            <template x-if="!loading && notificaciones && notificaciones.length > 0">
                                <div>
                                    <template x-for="notificacion in notificaciones" :key="notificacion.id">
                                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                                            <div class="flex items-start space-x-3">
                                                <!-- Icono según tipo -->
                                                <div class="flex-shrink-0 mt-1">
                                                    <template x-if="notificacion.tipo === 'Tramite'">
                                                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="notificacion.tipo === 'Cita'">
                                                        <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                                        </svg>
                                                    </template>
                                                    <template x-if="notificacion.tipo === 'exito'">
                                                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="notificacion.tipo === 'error'">
                                                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="notificacion.tipo === 'advertencia'">
                                                        <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="notificacion.tipo === 'informativo'">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </template>
                                                </div>

                                                <!-- Contenido de la notificación -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between">
                                                        <p class="text-sm font-medium text-gray-900" x-text="notificacion.titulo || 'Sin título'"></p>
                                                        <div class="flex items-center space-x-2">
                                                            <!-- Indicador de no leída -->
                                                            <div x-show="!notificacion.leida" class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                            <!-- Fecha -->
                                                            <span class="text-xs text-gray-500" x-text="notificacion.fecha_formateada || 'Reciente'"></span>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2" x-text="notificacion.mensaje || 'Sin mensaje'"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- Estado vacío -->
                            <template x-if="!loading && (!notificaciones || notificaciones.length === 0)">
                                <div class="p-4 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">No hay notificaciones recientes</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Mi Perfil -->
                @can('perfil.ver')
                <a href="{{ route('profile.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('profile.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('profile.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium tracking-wide">Mi Perfil</span>
                </a>
                @endcan

                <!-- Separador -->
                <div class="px-3 py-2">
                    <div class="h-px bg-gray-200"></div>
                </div>

                <!-- Cerrar Sesión -->
                <form method="POST" action="/cerrar-sesion" class="inline">
                    @csrf
                    <button type="submit" class="group flex items-center w-full px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 text-red-700 hover:bg-red-50 hover:shadow-md hover:text-red-800">
                        <svg class="text-red-400 group-hover:text-red-500 flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium tracking-wide">Cerrar Sesión</span>
                    </button>
                </form>
            </nav>
        </div>
    </div>
</div> 