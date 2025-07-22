<div id="modalCorreoVerificado"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-all duration-300 relative"
        id="contenidoCorreoVerificado">
        
        <button onclick="cerrarModalCorreoVerificado()" 
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center pt-8 pb-4">
            <div class="mx-auto flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">¡Correo Verificado!</h3>
            <p class="text-gray-600 text-sm px-6 leading-relaxed">
                Tu cuenta ha sido verificada exitosamente. Ya puedes iniciar sesión con tus credenciales.
            </p>
        </div>

        <div class="px-6 pb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 0121 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-green-900 mb-1">Cuenta activada</h4>
                        <p class="text-xs text-green-700 leading-relaxed">
                            Tu cuenta está ahora completamente activa y puedes acceder al Padron de Proveedores.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>Tu cuenta está protegida y verificada</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Acceso completo a la plataforma</span>
                </div>
            </div>

            <div class="space-y-3">
                <button onclick="cerrarModalCorreoVerificado()"
                    class="w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>Iniciar Sesión</span>
                    </div>
                </button>
                
                <button onclick="cerrarModalCorreoVerificado()" 
                    class="w-full bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 font-medium py-2.5 px-4 rounded-xl transition-all duration-300 border border-gray-300 hover:border-gray-400">
                    <span>Cerrar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarModalCorreoVerificado() {
        const modal = document.getElementById('modalCorreoVerificado');
        const content = document.getElementById('contenidoCorreoVerificado');

        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');

        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 50);
    }

    function cerrarModalCorreoVerificado() {
        const modal = document.getElementById('modalCorreoVerificado');
        const content = document.getElementById('contenidoCorreoVerificado');

        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.remove('opacity-100', 'visible');
            modal.classList.add('opacity-0', 'invisible');

            window.location.href = "{{ route('login') }}";
        }, 300);
    }

    document.getElementById('modalCorreoVerificado')?.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalCorreoVerificado();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalCorreoVerificado');
            if (modal && modal.classList.contains('visible')) {
                cerrarModalCorreoVerificado();
            }
        }
    });
</script>