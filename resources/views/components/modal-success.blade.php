<!-- Modal de Éxito Reutilizable -->
<div id="successModal" 
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm p-4"
     style="display: none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-all duration-300"
         id="successModalContent">
        
        <!-- Header con icono de éxito -->
        <div class="text-center pt-8 pb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-gray-900 mb-3" id="successModalTitle">
                ¡Operación Exitosa!
            </h3>
            
            <div class="px-6">
                <p class="text-gray-600 text-sm leading-relaxed" id="successModalMessage">
                    La operación se ha completado exitosamente.
                </p>
            </div>
        </div>
        
        <!-- Footer con botón -->
        <div class="px-6 pb-6">
            <button type="button" 
                    onclick="closeSuccessModal()" 
                    class="w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    id="successModalButton">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="successModalButtonText">Aceptar</span>
                </div>
            </button>
        </div>
    </div>
</div>

<script>
    // Variable global para almacenar la función de callback
    let successModalCallback = null;
    
    /**
     * Mostrar modal de éxito reutilizable
     * @param {Object} options - Opciones del modal
     * @param {string} options.title - Título del modal
     * @param {string} options.message - Mensaje del modal
     * @param {string} options.buttonText - Texto del botón
     * @param {Function} options.onClose - Función callback al cerrar
     * @param {string} options.redirectTo - URL para redireccionar al cerrar
     */
    function showSuccessModal(options = {}) {
        const {
            title = '¡Operación Exitosa!',
            message = 'La operación se ha completado exitosamente.',
            buttonText = 'Aceptar',
            onClose = null,
            redirectTo = null
        } = options;
        
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('successModalContent');
        const titleElement = document.getElementById('successModalTitle');
        const messageElement = document.getElementById('successModalMessage');
        const buttonTextElement = document.getElementById('successModalButtonText');
        
        if (modal && modalContent) {
            // Actualizar contenido
            titleElement.textContent = title;
            messageElement.textContent = message;
            buttonTextElement.textContent = buttonText;
            
            // Guardar callback y redirección
            successModalCallback = onClose;
            if (redirectTo) {
                successModalCallback = () => {
                    if (onClose) onClose();
                    window.location.href = redirectTo;
                };
            }
            
            // Mostrar modal
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
            
            // Animar entrada
            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
            
            // Prevenir scroll del body
            document.body.style.overflow = 'hidden';
        }
    }
    
    /**
     * Cerrar modal de éxito
     */
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('successModalContent');
        
        if (modal && modalContent) {
            // Animar salida
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.style.display = 'none';
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                
                // Ejecutar callback si existe
                if (successModalCallback) {
                    successModalCallback();
                    successModalCallback = null; // Limpiar callback
                }
            }, 300);
        }
    }
    
    /**
     * Mostrar modal con datos de Laravel session
     * @param {Object} sessionData - Datos de la sesión de Laravel
     */
    function showSuccessModalFromSession(sessionData) {
        if (sessionData && sessionData.show_success_modal) {
            showSuccessModal({
                title: sessionData.modal_title || '¡Operación Exitosa!',
                message: sessionData.modal_message || 'La operación se ha completado exitosamente.',
                buttonText: sessionData.modal_button_text || 'Aceptar',
                redirectTo: sessionData.modal_redirect_to || null
            });
        }
    }
    
    // Cerrar modal al hacer click fuera
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('successModalContent');
        
        if (modal && event.target === modal) {
            closeSuccessModal();
        }
    });
    
    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('successModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeSuccessModal();
            }
        }
    });
    
    // Hacer funciones disponibles globalmente
    window.showSuccessModal = showSuccessModal;
    window.closeSuccessModal = closeSuccessModal;
    window.showSuccessModalFromSession = showSuccessModalFromSession;
</script> 