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
                <a href="{{ route('users.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('users.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="font-medium tracking-wide">Usuarios</span>
                </a>

                <!-- Roles -->
                <a href="{{ route('roles.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('roles.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('roles.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                    </svg>
                    <span class="font-medium tracking-wide">Roles</span>
                </a>

                <!-- Trámites -->
                <a href="{{ route('tramites.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('tramites.*') && !request()->routeIs('tramites-prueba.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('tramites.*') && !request()->routeIs('tramites-prueba.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium tracking-wide">Trámites</span>
                </a>

                <!-- Revisión -->
                <a href="{{ route('revision.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('revision.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('revision.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium tracking-wide">Revisión</span>
                </a>

                <!-- Notificaciones -->
                <a href="{{ route('notificaciones.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('notificaciones.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('notificaciones.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="font-medium tracking-wide">Notificaciones</span>
                </a>

                <!-- Archivos -->
                <a href="{{ route('archivos.index') }}" @click="sidebarOpen = false" class="group flex items-center px-3 py-3 text-base font-medium rounded-xl transition-all duration-200 
                    {{ request()->routeIs('archivos.*') ? 'bg-primary-50 text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:shadow-md hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('archivos.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} flex-shrink-0 w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium tracking-wide">Archivos</span>
                </a>

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
                <form method="POST" action="{{ route('logout') }}">
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