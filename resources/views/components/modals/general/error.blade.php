<div id="modalError"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 relative"
        id="contenidoError">
        
        <button onclick="cerrarModalError()" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors duration-200 z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="text-center pt-6 pb-4">
            <div class="mx-auto flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-3">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 id="tituloError" class="text-lg font-semibold text-gray-900 mb-2">Error</h3>
            <p id="descripcionError" class="text-gray-600 text-sm px-4 leading-relaxed">
                Ha ocurrido un error inesperado.
            </p>
        </div>

        <div class="px-4 pb-4">
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                <div class="flex items-start space-x-2">
                    <div class="flex-shrink-0">
                        <svg class="w-4 h-4 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-medium text-red-900 mb-1">Sugerencias:</h4>
                        <p class="text-xs text-red-700 leading-relaxed">
                            Verifica el archivo e intenta nuevamente.
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-2 mb-4">
                <div class="flex items-center space-x-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Verifica el formato del archivo</span>
                </div>
                <div class="flex items-center space-x-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Intenta con otro archivo</span>
                </div>
            </div>

            <div class="space-y-2">
                <button onclick="cerrarModalError()"
                    class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span>Intentar Nuevamente</span>
                    </div>
                </button>
                
                <button onclick="cerrarModalError()" 
                    class="w-full bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 font-medium py-2 px-4 rounded-lg transition-all duration-300 border border-gray-300 hover:border-gray-400">
                    <span>Cerrar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarModalError(titulo, descripcion) {
        const modal = document.getElementById('modalError');
        const content = document.getElementById('contenidoError');
        const tituloElement = document.getElementById('tituloError');
        const descripcionElement = document.getElementById('descripcionError');

        if (titulo) tituloElement.textContent = titulo;
        if (descripcion) descripcionElement.textContent = descripcion;

        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');

        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 50);
    }

    function cerrarModalError() {
        const modal = document.getElementById('modalError');
        const content = document.getElementById('contenidoError');

        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.remove('opacity-100', 'visible');
            modal.classList.add('opacity-0', 'invisible');
        }, 300);
    }

    document.getElementById('modalError')?.addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalError();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('modalError');
            if (modal && modal.classList.contains('visible')) {
                cerrarModalError();
            }
        }
    });
</script>