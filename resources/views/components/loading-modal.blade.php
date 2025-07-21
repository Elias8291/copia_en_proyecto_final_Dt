<!-- Modal de Carga Global -->
<div id="loading-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4">
        <!-- Header -->
        <div class="text-center mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full flex items-center justify-center mx-auto mb-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>
            <h3 id="loading-title" class="text-lg font-semibold text-gray-800">Procesando...</h3>
        </div>
        
        <!-- Contenido -->
        <div class="text-center">
            <p id="loading-message" class="text-gray-600 text-sm mb-4">Por favor espere mientras procesamos su solicitud.</p>
            
            <!-- Barra de progreso -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div id="loading-progress" class="bg-gradient-to-r from-primary to-primary-dark h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            
            <!-- Status adicional -->
            <p id="loading-status" class="text-xs text-gray-500"></p>
        </div>
    </div>
</div>

<script>
// Funciones globales para el modal de loading
window.showLoading = function(title = 'Procesando...', message = 'Por favor espere mientras procesamos su solicitud.', progress = 0) {
    const modal = document.getElementById('loading-modal');
    const titleEl = document.getElementById('loading-title');
    const messageEl = document.getElementById('loading-message');
    const progressEl = document.getElementById('loading-progress');
    const statusEl = document.getElementById('loading-status');
    
    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;
    if (progressEl) progressEl.style.width = progress + '%';
    if (statusEl) statusEl.textContent = '';
    
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.updateLoading = function(progress, status = '') {
    const progressEl = document.getElementById('loading-progress');
    const statusEl = document.getElementById('loading-status');
    
    if (progressEl) progressEl.style.width = progress + '%';
    if (statusEl) statusEl.textContent = status;
};

window.hideLoading = function() {
    const modal = document.getElementById('loading-modal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};
</script> 