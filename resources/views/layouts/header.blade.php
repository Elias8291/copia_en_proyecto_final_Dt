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

            <div class="hidden md:flex items-center space-x-4 pr-4">
                <!-- Icono de Notificaciones -->
                <div class="relative" x-data="{ notificationsOpen: false }">
                    <button @click="notificationsOpen = !notificationsOpen"
                        class="group p-1.5 rounded-lg hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary relative">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="w-6 h-6 text-gray-500 group-hover:text-primary transition-colors duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Indicador de notificaciones -->
                        <span class="absolute -top-0.5 -right-0.5 flex h-5 w-5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-5 w-5 bg-primary items-center justify-center">
                                <span class="text-white text-xs font-bold">3</span>
                            </span>
                        </span>
                    </button>

                    <!-- Panel de notificaciones -->
                    <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute right-0 mt-3 w-96 rounded-xl shadow-xl bg-white ring-1 ring-gray-200 focus:outline-none z-50 overflow-hidden"
                        style="display: none;">

                        <div
                            class="px-4 py-3 bg-gradient-to-r from-primary to-primary-dark flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <h3 class="text-white text-sm font-semibold">Notificaciones</h3>
                            </div>
                            <span
                                class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-primary bg-white rounded-full">
                                3 nuevas
                            </span>
                        </div>

                        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                            <div class="p-4 bg-primary-50/30 hover:bg-primary-50/50 transition-colors duration-200">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Nuevo trámite asignado</p>
                                        <p class="text-sm text-gray-600 mt-1">Se ha asignado el trámite #12345 para su
                                            revisión.</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Hace 5 minutos</span>
                                            <button
                                                class="text-xs text-primary hover:text-primary-dark font-medium">Marcar
                                                como leído</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="p-4 bg-primary-50/30 hover:bg-primary-50/50 transition-colors duration-200">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Documento actualizado</p>
                                        <p class="text-sm text-gray-600 mt-1">El proveedor ha actualizado su
                                            documentación.</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Hace 2 horas</span>
                                            <button
                                                class="text-xs text-primary hover:text-primary-dark font-medium">Marcar
                                                como leído</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notificación no leída - Sistema -->
                            <div class="p-4 bg-primary-50/30 hover:bg-primary-50/50 transition-colors duration-200">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Recordatorio</p>
                                        <p class="text-sm text-gray-600 mt-1">Tiene 3 trámites pendientes por revisar.
                                        </p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Ayer</span>
                                            <button
                                                class="text-xs text-primary hover:text-primary-dark font-medium">Marcar
                                                como leído</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notificación leída -->
                            <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Mantenimiento programado</p>
                                        <p class="text-sm text-gray-500 mt-1">El sistema estará en mantenimiento el
                                            próximo domingo.</p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span class="text-xs text-gray-500">Hace 2 días</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie de notificaciones -->
                        <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('notificaciones.index') }}"
                                class="text-xs text-primary hover:text-primary-dark font-medium">
                                Ver todas las notificaciones
                            </a>
                            <button class="text-xs text-gray-600 hover:text-gray-900 font-medium">
                                Marcar todas como leídas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Menú de usuario -->
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
                                <div
                                    class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-green-400 border-2 border-white rounded-full">
                                </div>
                            </div>
                        </button>
                    </div>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute right-0 mt-3 w-64 rounded-xl shadow-xl bg-white ring-1 ring-gray-200 divide-y divide-gray-100 focus:outline-none z-50 overflow-hidden">

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
                            
                                    <div class="flex items-center mt-1">
                                        <div class="h-2 w-2 bg-green-400 rounded-full mr-1"></div>
                                        <span class="text-xs text-green-600 font-medium">En línea</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opciones principales -->
                        <div class="py-2">
                            <a href="#"
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
                            <form method="POST" action="{{ route('logout') }}">
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
