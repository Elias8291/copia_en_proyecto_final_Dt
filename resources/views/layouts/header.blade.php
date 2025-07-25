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

            <!-- Notificaciones y usuario -->
            <div class="hidden md:flex items-center space-x-4 pr-4">
                <!-- Notificaciones -->
                <div class="relative" id="notifications-container">
                    <button id="notifications-btn"
                        class="group p-1.5 rounded-lg hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary relative">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="w-6 h-6 text-gray-500 group-hover:text-primary transition-colors duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span id="notifications-badge" class="absolute -top-0.5 -right-0.5 flex h-5 w-5" style="display: none;">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-5 w-5 bg-primary items-center justify-center">
                                <span class="text-white text-xs font-bold" id="notifications-count">0</span>
                            </span>
                        </span>
                    </button>

                    <!-- Panel de notificaciones -->
                    <div id="notifications-panel" 
                         class="origin-top-right absolute right-0 mt-3 w-96 rounded-xl shadow-xl bg-white ring-1 ring-gray-200 focus:outline-none z-50 overflow-hidden"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-primary to-primary-dark flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <h3 class="text-white text-sm font-semibold">Notificaciones</h3>
                            </div>
                            <span id="notifications-header-count" class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-primary bg-white rounded-full" style="display: none;">
                                <span id="notifications-header-number">0</span> nueva<span id="notifications-header-text">s</span>
                            </span>
                        </div>

                        <!-- Contenido -->
                        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100" id="notifications-content">
                            <!-- Loading -->
                            <div id="notifications-loading" class="p-4 flex items-center justify-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
                                <span class="ml-2 text-sm text-gray-500">Cargando notificaciones...</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                            <a href="{{ route('notificaciones.index') }}"
                                class="text-xs text-primary hover:text-primary-dark font-medium">
                                Ver todas las notificaciones
                            </a>
                            <button id="mark-all-read-btn"
                                    class="text-xs text-gray-600 hover:text-gray-900 font-medium"
                                    style="display: none;">
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

<script>
// Notificaciones básicas
let notificationsOpen = false;

// Toggle panel
document.getElementById('notifications-btn').addEventListener('click', function() {
    const panel = document.getElementById('notifications-panel');
    notificationsOpen = !notificationsOpen;
    
    if (notificationsOpen) {
        panel.style.display = 'block';
        loadNotifications();
    } else {
        panel.style.display = 'none';
    }
});

// Cerrar al hacer clic fuera
document.addEventListener('click', function(e) {
    const container = document.getElementById('notifications-container');
    if (!container.contains(e.target) && notificationsOpen) {
        document.getElementById('notifications-panel').style.display = 'none';
        notificationsOpen = false;
    }
});

// Cargar notificaciones
async function loadNotifications() {
    try {
        const response = await fetch('/notificaciones/header');
        const data = await response.json();
        
        if (data.success) {
            updateNotifications(data.notificaciones, data.contador_no_leidas);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Actualizar UI
function updateNotifications(notifications, unreadCount) {
    // Contador
    const badge = document.getElementById('notifications-badge');
    const count = document.getElementById('notifications-count');
    
    if (unreadCount > 0) {
        badge.style.display = 'flex';
        count.textContent = unreadCount;
    } else {
        badge.style.display = 'none';
    }
    
    // Contenido
    const content = document.getElementById('notifications-content');
    
    if (notifications.length === 0) {
        content.innerHTML = '<div class="p-4 text-center text-gray-500">No hay notificaciones</div>';
        return;
    }
    
    const html = notifications.map(notif => `
        <div class="p-4 ${notif.leida ? 'hover:bg-gray-50' : 'bg-blue-50'}">
            <div class="flex">
                <div class="flex-1">
                    <p class="text-sm font-medium">${notif.titulo}</p>
                    <p class="text-sm text-gray-600 mt-1">${notif.mensaje}</p>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-xs text-gray-500">${formatDate(notif.created_at)}</span>
                        ${!notif.leida ? `<button onclick="markAsRead(${notif.id})" class="text-xs text-blue-600">Marcar leído</button>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    content.innerHTML = html;
}

// Marcar como leído
async function markAsRead(id) {
    try {
        await fetch('/notificaciones/marcar-leida', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ notificacion_id: id })
        });
        loadNotifications();
    } catch (error) {
        console.error('Error:', error);
    }
}

// Formato fecha simple
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / (1000 * 60));
    
    if (diff < 60) return 'Hace ' + diff + ' min';
    if (diff < 1440) return 'Hace ' + Math.floor(diff / 60) + ' h';
    return date.toLocaleDateString();
}

// Cargar al inicio
loadNotifications();
</script>
