
<!-- Modal de Carga Elegante -->
<div id="loading-modal" class="hidden fixed inset-0 bg-white/95 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.15)] border border-gray-100/50 p-8 max-w-md w-full mx-4 transform transition-all duration-500 scale-95 opacity-0" id="loading-content">
        <!-- Header con icono elegante -->
        <div class="text-center mb-8">
            <div class="relative w-20 h-20 mx-auto mb-6">
                <!-- Círculo exterior con animación suave -->
                <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-t-[#9d2449] border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                
                <!-- Círculo interior con logo/icono -->
                <div class="absolute inset-2 bg-gradient-to-br from-gray-50 to-white rounded-full flex items-center justify-center shadow-inner">
                    <svg class="w-8 h-8 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            
            <!-- Título elegante -->
            <h3 id="loading-title" class="text-2xl font-light text-gray-800 mb-2 tracking-wide">Procesando</h3>
            <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-[#9d2449] to-transparent mx-auto"></div>
        </div>
        
        <!-- Contenido del mensaje -->
        <div class="text-center space-y-6">
            <!-- Mensaje principal -->
            <div class="space-y-3">
                <p id="loading-message" class="text-gray-600 text-base leading-relaxed font-light">
                    Por favor espere mientras procesamos su solicitud.
                </p>
                <p id="loading-status" class="text-sm text-gray-500 font-medium min-h-[20px]"></p>
            </div>
            
            <!-- Barra de progreso elegante -->
            <div class="space-y-3">
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden shadow-inner">
                    <div id="loading-progress" 
                         class="bg-gradient-to-r from-[#9d2449] via-[#B4325E] to-[#9d2449] h-full rounded-full transition-all duration-700 ease-out relative overflow-hidden" 
                         style="width: 0%">
                        <!-- Efecto de brillo que se mueve -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-pulse"></div>
                    </div>
                </div>
                
                <!-- Porcentaje -->
                <div class="flex justify-between items-center text-xs text-gray-400 font-medium">
                    <span>Progreso</span>
                    <span id="loading-percentage">0%</span>
                </div>
            </div>
            
            <!-- Indicadores de estado con puntos animados -->
            <div class="flex justify-center items-center space-x-2 pt-2">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-[#9d2449] rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-2 h-2 bg-[#9d2449] rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-2 h-2 bg-[#9d2449] rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>
        </div>
        
        <!-- Footer sutil -->
        <div class="mt-8 pt-6 border-t border-gray-100">
            <p class="text-center text-xs text-gray-400 font-light tracking-wide">
                Sistema de Gestión de Proveedores
            </p>
        </div>
    </div>
</div>

<script>
window.showLoading = function(title = 'Procesando', message = 'Por favor espere mientras procesamos su solicitud.', progress = 0) {
    const modal = document.getElementById('loading-modal');
    const content = document.getElementById('loading-content');
    const titleEl = document.getElementById('loading-title');
    const messageEl = document.getElementById('loading-message');
    const progressEl = document.getElementById('loading-progress');
    const statusEl = document.getElementById('loading-status');
    const percentageEl = document.getElementById('loading-percentage');
    
    // Actualizar contenido
    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (progressEl) progressEl.style.width = progress + '%';
    if (percentageEl) percentageEl.textContent = progress + '%';
    if (statusEl) statusEl.textContent = '';
    
    if (modal && content) {
        // Mostrar modal con animación elegante
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animar entrada
        requestAnimationFrame(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }
};

window.updateLoading = function(progress, status = '') {
    const progressEl = document.getElementById('loading-progress');
    const statusEl = document.getElementById('loading-status');
    const percentageEl = document.getElementById('loading-percentage');
    
    if (progressEl) {
        progressEl.style.width = progress + '%';
        // Añadir una pequeña animación al actualizar
        progressEl.style.transition = 'width 0.7s ease-out';
    }
    if (percentageEl) percentageEl.textContent = Math.round(progress) + '%';
    if (statusEl) statusEl.textContent = status;
};

window.hideLoading = function() {
    const modal = document.getElementById('loading-modal');
    const content = document.getElementById('loading-content');
    
    if (modal && content) {
        // Animar salida
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        // Ocultar modal después de la animación
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
};

// Función adicional para mostrar diferentes estados
window.showLoadingSuccess = function(title = 'Completado', message = 'Proceso finalizado exitosamente.') {
    const titleEl = document.getElementById('loading-title');
    const messageEl = document.getElementById('loading-message');
    const progressEl = document.getElementById('loading-progress');
    const percentageEl = document.getElementById('loading-percentage');
    const statusEl = document.getElementById('loading-status');
    
    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (progressEl) progressEl.style.width = '100%';
    if (percentageEl) percentageEl.textContent = '100%';
    if (statusEl) statusEl.textContent = 'Proceso completado';
    
    // Cambiar el color de la barra a verde para indicar éxito
    if (progressEl) {
        progressEl.classList.remove('from-[#9d2449]', 'via-[#B4325E]', 'to-[#9d2449]');
        progressEl.classList.add('from-green-500', 'via-green-600', 'to-green-500');
    }
    
    // Auto-ocultar después de 2 segundos
    setTimeout(() => {
        window.hideLoading();
    }, 2000);
};

// Función para mostrar error
window.showLoadingError = function(title = 'Error', message = 'Ha ocurrido un error durante el proceso.') {
    const titleEl = document.getElementById('loading-title');
    const messageEl = document.getElementById('loading-message');
    const statusEl = document.getElementById('loading-status');
    const progressEl = document.getElementById('loading-progress');
    
    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (statusEl) statusEl.textContent = 'Error en el proceso';
    
    // Cambiar el color de la barra a rojo para indicar error
    if (progressEl) {
        progressEl.classList.remove('from-[#9d2449]', 'via-[#B4325E]', 'to-[#9d2449]');
        progressEl.classList.add('from-red-500', 'via-red-600', 'to-red-500');
    }
    
    // Auto-ocultar después de 3 segundos
    setTimeout(() => {
        window.hideLoading();
    }, 3000);
};
</script> 