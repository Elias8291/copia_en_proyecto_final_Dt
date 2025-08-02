<div id="modalPasswordResetSent"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-all duration-300 relative"
        id="contenidoPasswordResetSent">
        
        <button onclick="cerrarModalPasswordResetSent()" 
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center pt-8 pb-4">
            <div class="mx-auto flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">¡Correo Enviado!</h3>
            <p class="text-gray-600 text-sm px-6 leading-relaxed">
                Te hemos enviado las instrucciones para restablecer tu contraseña a tu correo electrónico.
            </p>
            @if (session('reset_email'))
                <div class="mt-3 mx-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        <p class="text-sm font-medium text-gray-800 text-center">{{ session('reset_email') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="px-6 pb-6">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-amber-900 mb-1">Revisa tu correo electrónico</h4>
                        <p class="text-xs text-amber-700 leading-relaxed">
                            Haz clic en el enlace que te enviamos para crear una nueva contraseña. Recuerda revisar tu carpeta de spam.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>El enlace expira en 60 minutos</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Si no recibes el correo, verifica la dirección ingresada</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Revisa también tu carpeta de correo no deseado</span>
                </div>
            </div>

            <div class="space-y-3">
                <button onclick="cerrarModalPasswordResetSent()"
                    class="w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>Ir a Iniciar Sesión</span>
                    </div>
                </button>
                
                <button onclick="enviarNuevamente()" 
                    class="w-full bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 font-medium py-2.5 px-4 rounded-xl transition-all duration-300 border border-gray-300 hover:border-gray-400">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Enviar Nuevamente</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarModalPasswordResetSent() {
        const modal = document.getElementById('modalPasswordResetSent');
        const content = document.getElementById('contenidoPasswordResetSent');

        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');

        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 50);
    }

    function cerrarModalPasswordResetSent() {
        const modal = document.getElementById('modalPasswordResetSent');
        const content = document.getElementById('contenidoPasswordResetSent');

        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.remove('opacity-100', 'visible');
            modal.classList.add('opacity-0', 'invisible');

            window.location.href = "{{ route('login') }}";
        }, 300);
    }

    function enviarNuevamente() {
        const modal = document.getElementById('modalPasswordResetSent');
        const content = document.getElementById('contenidoPasswordResetSent');

        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.remove('opacity-100', 'visible');
            modal.classList.add('opacity-0', 'invisible');

            // Recargar la página para volver al formulario
            window.location.reload();
        }, 300);
    }

    document.getElementById('modalPasswordResetSent')?.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalPasswordResetSent();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalPasswordResetSent');
            if (modal && modal.classList.contains('visible')) {
                cerrarModalPasswordResetSent();
            }
        }
    });

    // Auto-mostrar el modal si hay un status de éxito
    @if (session('status'))
        document.addEventListener('DOMContentLoaded', function() {
            mostrarModalPasswordResetSent();
        });
    @endif
</script>