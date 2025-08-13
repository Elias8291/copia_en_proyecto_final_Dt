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
                        // Cargar todas las notificaciones recientes (leídas y no leídas)
                        const response = await fetch('<?php echo e(route('notificaciones.recientes-dropdown')); ?>');
                        const data = await response.json();
                        this.notificaciones = data.notificaciones || [];
                        this.count = data.conteo_no_leidas || 0; // Solo contar las no leídas
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
                        // Al abrir, cargar notificaciones
                        await this.loadNotifications();
                        // Marcar las no leídas como leídas
                        const unreadNotifications = (this.notificaciones || []).filter(n => !n.leida);
                        if (unreadNotifications.length > 0) {
                            try {
                                const response = await fetch('<?php echo e(route('notificaciones.marcar-vistas-leidas')); ?>', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    }
                                });
                                const result = await response.json();
                                // Actualizar contador
                                this.count = result.conteo_restante || 0;
                                // Actualizar el estado de las notificaciones localmente
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
            }" x-init="loadNotifications()">
                <button @click="markAsReadAndOpen()" 
                        class="relative p-2 text-primary hover:text-primary-dark hover:bg-primary/10 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <span class="sr-only">Ver notificaciones</span>
                    <!-- Ícono de campana (Heroicons Bell Outline) -->
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                    </svg>
                    <!-- Badge de contador móvil -->
                    <span x-show="count > 0" 
                          x-text="count > 99 ? '99+' : count"
                          class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold px-1 shadow-sm">
                    </span>
                </button>

                <!-- Dropdown móvil -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="origin-top-right absolute right-4 mt-3 w-72 rounded-xl shadow-xl bg-white ring-1 ring-gray-200 focus:outline-none z-50 overflow-hidden"
                     style="display: none;">
                    
                    <!-- Header móvil -->
                    <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                            <a href="/notificaciones" 
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                Ver todas
                            </a>
                        </div>
                    </div>

                    <!-- Lista móvil -->
                    <div class="max-h-80 overflow-y-auto">
                        <template x-if="loading">
                            <div class="px-4 py-6 text-center">
                                <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                <p class="text-xs text-gray-500 mt-2">Cargando...</p>
                            </div>
                        </template>

                        <template x-if="!loading && (!notificaciones || notificaciones.length === 0)">
                            <div class="px-4 py-6 text-center">
                                <!-- Ícono de campana (Heroicons Bell Outline) -->
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                                </svg>
                                <p class="text-xs text-gray-500">Sin notificaciones</p>
                            </div>
                        </template>

                        <div class="divide-y divide-gray-100">
                                                         <template x-for="notificacion in (notificaciones || []).slice(0, 6)" :key="notificacion.id">
                                 <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-150"
                                      :class="notificacion.leida ? 'bg-gray-50/30 opacity-75' : 'bg-blue-50/30'">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 mt-0.5">
                                            <div class="w-6 h-6 rounded-lg flex items-center justify-center"
                                                 :class="{
                                                     'bg-emerald-100 text-emerald-600': notificacion.tipo === 'exito',
                                                     'bg-amber-100 text-amber-600': notificacion.tipo === 'advertencia',
                                                     'bg-red-100 text-red-600': notificacion.tipo === 'error',
                                                     'bg-blue-100 text-blue-600': notificacion.tipo === 'Tramite',
                                                     'bg-purple-100 text-purple-600': notificacion.tipo === 'Cita',
                                                     'bg-gray-100 text-gray-600': notificacion.tipo === 'informativo'
                                                 }">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <circle cx="10" cy="10" r="8"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <p class="text-xs font-medium text-gray-900 truncate" x-text="notificacion.titulo || 'Sin título'"></p>
                                                <!-- Indicador de no leída -->
                                                <div x-show="!notificacion.leida" class="flex-shrink-0 ml-2">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-600 line-clamp-1 mt-0.5" x-text="notificacion.mensaje || 'Sin mensaje'"></p>
                                            <div class="flex items-center mt-1 space-x-2">
                                                <span class="text-xs text-gray-500" x-text="notificacion.fecha_formateada || 'Reciente'"></span>
                                                <span x-show="notificacion.leida" class="text-xs text-gray-400">• Leída</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Footer móvil -->
                    <template x-if="notificaciones && notificaciones.length > 0">
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                            <a href="<?php echo e(route('notificaciones.index')); ?>" 
                               class="block text-center text-xs text-blue-600 hover:text-blue-800 font-medium">
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
                            // Cargar todas las notificaciones recientes (leídas y no leídas)
                            const response = await fetch('<?php echo e(route('notificaciones.recientes-dropdown')); ?>');
                            const data = await response.json();
                            this.notificaciones = data.notificaciones || [];
                            this.count = data.conteo_no_leidas || 0; // Solo contar las no leídas
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
                            // Al abrir, cargar notificaciones
                            await this.loadNotifications();
                            // Marcar las no leídas como leídas
                            const unreadNotifications = (this.notificaciones || []).filter(n => !n.leida);
                            if (unreadNotifications.length > 0) {
                                try {
                                    const response = await fetch('<?php echo e(route('notificaciones.marcar-vistas-leidas')); ?>', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                        }
                                    });
                                    const result = await response.json();
                                    // Actualizar contador
                                    this.count = result.conteo_restante || 0;
                                    // Actualizar el estado de las notificaciones localmente
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
                }" x-init="loadNotifications()">
                    <button @click="markAsReadAndOpen()" 
                            class="relative p-2 text-primary hover:text-primary-dark hover:bg-primary/10 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <span class="sr-only">Ver notificaciones</span>
                        <!-- Ícono de campana (Heroicons Bell Outline) -->
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                        </svg>
                        <!-- Badge de contador -->
                        <span x-show="count > 0" 
                              x-text="count > 99 ? '99+' : count"
                              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold px-1 shadow-sm">
                        </span>
                    </button>

                    <!-- Dropdown de notificaciones -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-3 w-80 rounded-xl shadow-xl bg-white ring-1 ring-gray-200 focus:outline-none z-50 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900">Notificaciones</h3>
                                <a href="<?php echo e(route('notificaciones.index')); ?>" 
                                   class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                    Ver todas
                                </a>
                            </div>
                        </div>

                        <!-- Lista de notificaciones -->
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="loading">
                                <div class="px-4 py-8 text-center">
                                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                    <p class="text-sm text-gray-500 mt-2">Cargando notificaciones...</p>
                                </div>
                            </template>

                            <template x-if="!loading && (!notificaciones || notificaciones.length === 0)">
                                <div class="px-4 py-8 text-center">
                                    <!-- Ícono de campana (Heroicons Bell Outline) -->
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">No tienes notificaciones</p>
                                </div>
                            </template>

                            <div class="divide-y divide-gray-100">
                                                                 <template x-for="notificacion in (notificaciones || []).slice(0, 7)" :key="notificacion.id">
                                     <div class="px-4 py-3 hover:bg-gray-50 transition-colors duration-150"
                                          :class="notificacion.leida ? 'bg-gray-50/30 opacity-75' : 'bg-blue-50/30'">
                                        <div class="flex items-start space-x-3">
                                            <!-- Icono según tipo -->
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                                                     :class="{
                                                         'bg-emerald-100 text-emerald-600': notificacion.tipo === 'exito',
                                                         'bg-amber-100 text-amber-600': notificacion.tipo === 'advertencia',
                                                         'bg-red-100 text-red-600': notificacion.tipo === 'error',
                                                         'bg-blue-100 text-blue-600': notificacion.tipo === 'Tramite',
                                                         'bg-purple-100 text-purple-600': notificacion.tipo === 'Cita',
                                                         'bg-gray-100 text-gray-600': notificacion.tipo === 'informativo'
                                                     }">
                                                    <!-- Icono de éxito -->
                                                    <svg x-show="notificacion.tipo === 'exito'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <!-- Icono de error -->
                                                    <svg x-show="notificacion.tipo === 'error'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <!-- Icono de advertencia -->
                                                    <svg x-show="notificacion.tipo === 'advertencia'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-.993.883L9 6v3a1 1 0 001.993.117L11 9V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <!-- Icono de trámite -->
                                                    <svg x-show="notificacion.tipo === 'Tramite'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <!-- Icono de cita -->
                                                    <svg x-show="notificacion.tipo === 'Cita'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <!-- Icono informativo -->
                                                    <svg x-show="notificacion.tipo === 'informativo'" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Contenido -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-start justify-between">
                                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="notificacion.titulo || 'Sin título'"></p>
                                                            <!-- Indicador de no leída -->
                                                            <div x-show="!notificacion.leida" class="flex-shrink-0 ml-2">
                                                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                            </div>
                                                        </div>
                                                                                                                 <p class="text-xs text-gray-600 line-clamp-2 mt-1" x-text="notificacion.mensaje || 'Sin mensaje'"></p>
                                                        <div class="flex items-center mt-2 space-x-2">
                                                            <span class="text-xs text-gray-500" x-text="notificacion.fecha_formateada || 'Reciente'"></span>
                                                            <span x-show="notificacion.leida" class="text-xs text-gray-400">• Leída</span>
                                                        </div>
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
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                                <a href="<?php echo e(route('notificaciones.index')); ?>" 
                                   class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
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
                                        <?php echo e(auth()->check() ? strtoupper(substr(auth()->user()->nombre, 0, 1)) : 'I'); ?></span>
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
                                        <?php echo e(auth()->check() ? strtoupper(substr(auth()->user()->nombre, 0, 1)) : 'I'); ?>

                                    </span>
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        <?php echo e(auth()->check() ? auth()->user()->nombre : 'Invitado'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Opciones principales -->
                        <div class="py-2">
                            <a href="<?php echo e(route('profile.index')); ?>"
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
                                <?php echo csrf_field(); ?>
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
            const response = await fetch('<?php echo e(route('notificaciones.conteo-no-leidas')); ?>');
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
<?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/layouts/header.blade.php ENDPATH**/ ?>