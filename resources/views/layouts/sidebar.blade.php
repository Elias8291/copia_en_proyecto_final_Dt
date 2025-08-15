<!-- Desktop Sidebar -->
<div class="hidden md:block">
    <div class="group fixed top-16 left-0 h-[calc(100vh-4rem)] transition-all duration-300 ease-in-out w-[65px] hover:w-72 bg-gradient-to-b from-white to-gray-50 border-r border-gray-200 shadow-lg overflow-hidden hover:overflow-y-auto z-30"
         x-data="{ hovered: false }"
         @mouseenter="hovered = true; $dispatch('sidebar-hover', true)"
         @mouseleave="hovered = false; $dispatch('sidebar-hover', false)">
        <div class="flex flex-col flex-grow">
            <div class="flex-grow flex flex-col pt-4">
                <nav class="flex-1 px-2 space-y-2">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6a2 2 0 01-2 2H10a2 2 0 01-2-2V5z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Dashboard</span>
                    </a>

                                    <!-- Usuarios -->
                @can('usuarios.ver')
                <a href="{{ route('users.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                <div class="relative">
                    <svg class="{{ request()->routeIs('users.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-all duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Usuarios</span>
            </a>
            @endcan

                    <!-- Roles -->
                    @can('roles.ver')
                    <a href="{{ route('roles.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('roles.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('roles.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Roles</span>
                    </a>
                    @endcan

                    <!-- Trámites -->
                    <a href="{{ route('tramites.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('tramites.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('tramites.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Trámites</span>
                    </a>

                    <!-- Revisiones -->
                    @can('revisiones.ver')
                    <a href="{{ route('revisiones.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('revisiones.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('revisiones.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Revisiones</span>
                    </a>
                    @endcan

                    <!-- Citas -->
                    @can('citas.ver')
                    <a href="{{ route('citas.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('citas.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('citas.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Citas</span>
                    </a>
                    @endcan

                    <!-- Archivos -->
                    @can('archivos.ver')
                    <a href="{{ route('archivos.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('archivos.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('archivos.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/>
                            <polyline points="14,2 14,8 20,8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10,9 9,9 8,9"/>
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Archivos</span>
                    </a>
                    @endcan

                    <!-- Proveedores -->
                    @can('proveedores.ver')
                    <a href="{{ route('proveedores.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('proveedores.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('proveedores.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Proveedores</span>
                    </a>
                    @endcan


                    <!-- Logs -->
                    @can('logs.ver')
                    <a href="{{ route('logs.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('logs.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('logs.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Logs</span>
                    </a>
                    @endcan


                  

                    <!-- Notificaciones -->
                    <a href="{{ route('notificaciones.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('notificaciones.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('notificaciones.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1h6z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Notificaciones</span>
                    </a>


                    <!-- Mi Perfil -->
                    @can('perfil.ver')
                    <a href="{{ route('profile.index') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('profile.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('profile.*') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-all duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Mi Perfil</span>
                    </a>
                    @endcan

                    <!-- Mi Estado -->
                    @can('mi-estado.ver')
                    <a href="{{ route('mi-estado') }}" class="group/item flex items-center min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                        {{ request()->routeIs('mi-estado') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                        <svg class="{{ request()->routeIs('mi-estado') ? 'text-primary' : 'text-gray-400 group-hover/item:text-primary' }} flex-shrink-0 w-6 h-6 transition-transform duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Mi Estado</span>
                    </a>
                    @endcan

                    <!-- Separador final -->
                    <div class="px-3 py-2">
                        <div class="h-px bg-gray-200"></div>
                    </div>

                    <!-- Cerrar Sesión -->
                    <form method="POST" action="/cerrar-sesion" class="inline">
                        @csrf
                        <button type="submit" class="group/item flex items-center w-full min-w-[250px] px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 text-red-700 hover:bg-red-50 hover:shadow-md hover:text-red-800">
                            <svg class="text-red-400 group-hover/item:text-red-500 flex-shrink-0 w-6 h-6 transition-all duration-200 group-hover/item:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ml-3 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">Cerrar Sesión</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </div>
</div> 

 