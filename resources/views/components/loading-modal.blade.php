<!-- Modal de Carga Global -->
<div id="loading-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-md flex items-center justify-center z-50">
    <div class="relative max-w-sm w-full mx-6">
        <!-- Modal principal elegante -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 p-8 relative overflow-hidden">
            <!-- Efecto de fondo sutil -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary-dark/5"></div>
            
            <!-- Header elegante -->
            <div class="text-center mb-8 relative z-10">
                <!-- Icono de carga elegante -->
                <div class="relative w-24 h-24 mx-auto mb-6">
                    <!-- Círculo exterior con efecto de pulso -->
                    <div class="absolute inset-0 bg-primary/20 rounded-full animate-pulse"></div>
                    
                    <!-- Anillos concéntricos animados -->
                    <div class="absolute inset-2 border-2 border-primary/40 rounded-full animate-spin" style="animation-duration: 2.5s;"></div>
                    <div class="absolute inset-4 border-2 border-primary/60 rounded-full animate-spin" style="animation-duration: 2s; animation-direction: reverse;"></div>
                    
                    <!-- Icono central elegante -->
                    <div class="absolute inset-6 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <!-- Icono de carga con puntos -->
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-primary rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                            <div class="w-2 h-2 bg-primary rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                        </div>
                    </div>
                    
                    <!-- Punto central brillante -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-1.5 h-1.5 bg-primary rounded-full animate-ping"></div>
                    </div>
                </div>
                
                <!-- Título elegante -->
                <h3 id="loading-title" class="text-xl font-semibold text-gray-800 mb-3">
                    Procesando...
                </h3>
                
                <!-- Línea decorativa con efecto de onda -->
                <div class="relative w-16 h-1 mx-auto mb-4">
                    <div class="absolute inset-0 bg-primary/30 rounded-full"></div>
                    <div class="absolute inset-0 bg-primary rounded-full animate-pulse" style="width: 60%;"></div>
                </div>
            </div>
            
            <!-- Contenido elegante -->
            <div class="text-center space-y-6 relative z-10">
                <p id="loading-message" class="text-gray-600 text-sm leading-relaxed">
                    Por favor espere mientras procesamos su solicitud.
                </p>
                
                <!-- Barra de progreso elegante con icono -->
                <div class="relative">
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
                        <div id="loading-progress" 
                             class="bg-gradient-to-r from-primary to-primary-dark h-3 rounded-full transition-all duration-500 ease-out relative overflow-hidden" 
                             style="width: 0%">
                            <!-- Efecto de brillo -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                        </div>
                    </div>
                    
                    <!-- Icono flotante en la barra -->
                    <div class="absolute -top-8 right-0 transform -translate-x-1/2">
                        <div class="bg-white rounded-full p-2 shadow-lg border border-primary/20">
                            <svg class="w-4 h-4 text-primary animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Indicador de progreso elegante -->
                    <div class="absolute -top-12 right-0 text-xs font-medium text-primary bg-white px-3 py-1 rounded-full shadow-sm border border-primary/20">
                        <span id="progress-text">0%</span>
                    </div>
                </div>
                
                <!-- Status con icono elegante -->
                <div class="flex items-center justify-center space-x-2">
                    <div class="relative">
                        <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                        <div class="absolute inset-0 w-2 h-2 bg-primary/30 rounded-full animate-ping"></div>
                    </div>
                    <p id="loading-status" class="text-xs text-gray-500"></p>
                </div>
            </div>
            
            <!-- Decoraciones sutiles -->
            <div class="absolute top-4 right-4">
                <div class="w-2 h-2 bg-primary/30 rounded-full animate-pulse"></div>
            </div>
            <div class="absolute bottom-4 left-4">
                <div class="w-1.5 h-1.5 bg-primary/20 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
            </div>
            
            <!-- Efecto de brillo en las esquinas -->
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-primary/10 to-transparent rounded-full blur-xl"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-primary-dark/10 to-transparent rounded-full blur-xl"></div>
        </div>
    </div>
</div>

<script>
// Funciones globales para el modal de loading
window.showLoading = function(title = 'Procesando...', message = 'Por favor espere mientras procesamos su solicitud.', progress = 0) {
    // Manejar si se pasa un objeto como parámetro (para compatibilidad con el login)
    if (typeof title === 'object' && title !== null) {
        const options = title;
        title = options.text || options.title || 'Procesando...';
        message = options.message || 'Por favor espere mientras procesamos su solicitud.';
        progress = options.progress || 0;
    }
    
    const modal = document.getElementById('loading-modal');
    const titleEl = document.getElementById('loading-title');
    const messageEl = document.getElementById('loading-message');
    const progressEl = document.getElementById('loading-progress');
    const progressTextEl = document.getElementById('progress-text');
    const statusEl = document.getElementById('loading-status');
    
    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (progressEl) progressEl.style.width = progress + '%';
    if (progressTextEl) progressTextEl.textContent = Math.round(progress) + '%';
    if (statusEl) statusEl.textContent = '';
    
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.updateLoading = function(progress, status = '') {
    const progressEl = document.getElementById('loading-progress');
    const progressTextEl = document.getElementById('progress-text');
    const statusEl = document.getElementById('loading-status');
    
    if (progressEl) progressEl.style.width = progress + '%';
    if (progressTextEl) progressTextEl.textContent = Math.round(progress) + '%';
    if (statusEl) statusEl.textContent = status;
};

window.hideLoading = function() {
    const modal = document.getElementById('loading-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};

// Sobrescribir las funciones globales después de que se cargue global-loading.js
document.addEventListener('DOMContentLoaded', function() {
    // Guardar las funciones originales
    const originalShowLoading = window.showLoading;
    const originalHideLoading = window.hideLoading;
    
    // Sobrescribir con nuestras funciones
    window.showLoading = function(title = 'Procesando...', message = 'Por favor espere mientras procesamos su solicitud.', progress = 0) {
        // Manejar si se pasa un objeto como parámetro (para compatibilidad con el login)
        if (typeof title === 'object' && title !== null) {
            const options = title;
            title = options.text || options.title || 'Procesando...';
            message = options.message || 'Por favor espere mientras procesamos su solicitud.';
            progress = options.progress || 0;
        }
        
        const modal = document.getElementById('loading-modal');
        const titleEl = document.getElementById('loading-title');
        const messageEl = document.getElementById('loading-message');
        const progressEl = document.getElementById('loading-progress');
        const progressTextEl = document.getElementById('progress-text');
        const statusEl = document.getElementById('loading-status');
        
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        if (progressEl) progressEl.style.width = progress + '%';
        if (progressTextEl) progressTextEl.textContent = Math.round(progress) + '%';
        if (statusEl) statusEl.textContent = '';
        
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.updateLoading = function(progress, status = '') {
        const progressEl = document.getElementById('loading-progress');
        const progressTextEl = document.getElementById('progress-text');
        const statusEl = document.getElementById('loading-status');
        
        if (progressEl) progressEl.style.width = progress + '%';
        if (progressTextEl) progressTextEl.textContent = Math.round(progress) + '%';
        if (statusEl) statusEl.textContent = status;
    };

    window.hideLoading = function() {
        const modal = document.getElementById('loading-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };
});
</script> 