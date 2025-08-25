<div id="modalRecuperacionExitosa"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-all duration-300 relative"
        id="contenidoRecuperacionExitosa">
        
        <button onclick="cerrarModalRecuperacionExitosa()" 
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center pt-8 pb-4">
            <div class="mx-auto flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">
                ¡Contraseña Restablecida!
            </h3>
            <p class="text-gray-600 text-sm px-6 leading-relaxed">
                Tu contraseña ha sido actualizada exitosamente. Ya puedes iniciar sesión con tu nueva contraseña.
            </p>
            @if (session('userEmail'))
                <div class="mt-3 mx-6">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2">
                        <p class="text-sm font-medium text-gray-800 text-center">{{ session('userEmail') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="px-6 pb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-green-900 mb-1">
                            Cambio completado
                        </h4>
                        <p class="text-xs text-green-700 leading-relaxed">
                            Tu contraseña ha sido cambiada de forma segura. El enlace de recuperación ya no es válido.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Nueva contraseña guardada de forma segura</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"/>
                    </svg>
                    <span>Token de recuperación eliminado</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Listo para iniciar sesión</span>
                </div>
            </div>

            <div class="space-y-3">
                <button onclick="irAIniciarSesion()"
                    class="w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Ir a Iniciar Sesión</span>
                    </div>
                </button>
                
                <button onclick="irAInicio()" 
                    class="w-full bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 font-medium py-2.5 px-4 rounded-xl transition-all duration-300 border border-gray-300 hover:border-gray-400">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Ir al Inicio</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarModalRecuperacionExitosa() {
        const modal = document.getElementById('modalRecuperacionExitosa');
        const content = document.getElementById('contenidoRecuperacionExitosa');

        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');

        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 50);
    }

    function cerrarModalRecuperacionExitosa() {
        const modal = document.getElementById('modalRecuperacionExitosa');
        const content = document.getElementById('contenidoRecuperacionExitosa');

        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.remove('opacity-100', 'visible');
            modal.classList.add('opacity-0', 'invisible');
        }, 300);
    }

    function irAIniciarSesion() {
        cerrarModalRecuperacionExitosa();
        setTimeout(() => {
            window.location.href = "{{ route('login') }}";
        }, 300);
    }

    function irAInicio() {
        cerrarModalRecuperacionExitosa();
        setTimeout(() => {
            window.location.href = "{{ url('/') }}";
        }, 300);
    }

    document.getElementById('modalRecuperacionExitosa')?.addEventListener('click', function(e) {
        if (e.target === this) {
            irAInicio();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalRecuperacionExitosa');
            if (modal && modal.classList.contains('visible')) {
                irAInicio();
            }
        }
    });
</script>
