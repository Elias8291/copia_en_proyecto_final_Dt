<div class="group relative bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200 transition-all duration-300 @if($disponible) hover:shadow-lg hover:-translate-y-1 @endif">
    @if(!$disponible)
        <div class="absolute inset-0 bg-white/20 z-10 rounded-xl"></div>
    @endif

    <div class="p-6">
        @if($disponible)
            <div class="text-center mb-3">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Disponible
                </span>
            </div>
        @else
            <div class="text-center mb-3">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Bloqueado
                </span>
            </div>
        @endif

        <svg class="w-10 h-10 mx-auto text-gray-400 sm:mx-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>

        <h3 class="mt-4 text-lg font-bold text-gray-900 sm:mt-6">{{ $titulo }}</h3>
        <p class="mt-3 text-sm text-gray-600">{{ $descripcion }}</p>

        <div class="mt-4 space-y-2">
            @if($tipo === 'inscripcion')
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Registro inicial de proveedor</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Documentación completa</span>
                </div>
                <div class="mt-3 p-2 bg-blue-50 rounded-lg">
                    <div class="flex items-center gap-2 text-xs text-blue-700">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Disponible para nuevos proveedores</span>
                    </div>
                </div>
            @elseif($tipo === 'renovacion')
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Renovación anual obligatoria</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Mantener estado activo</span>
                </div>
                <div class="mt-3 p-2 bg-amber-50 rounded-lg">
                    <div class="flex items-center gap-2 text-xs text-amber-700">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Disponible 7 días antes del vencimiento</span>
                    </div>
                </div>
            @elseif($tipo === 'actualizacion')
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Modificar datos registrados</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <svg class="w-3 h-3 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Actualizar información</span>
                </div>
                <div class="mt-3 p-2 bg-green-50 rounded-lg">
                    <div class="flex items-center gap-2 text-xs text-green-700">
                        <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Disponible para proveedores activos</span>
                    </div>
                </div>
            @endif
        </div>

        @if($disponible)
            <button onclick="mostrarModalDocumentos('{{ $tipo }}')" 
               class="mt-5 w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                Iniciar {{ ucfirst($tipo) }}
            </button>
        @else
            <button disabled class="mt-5 w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                No Disponible
            </button>
        @endif
    </div>
</div>

<!-- Modal de Documentos Requeridos -->
<div id="modalDocumentos" class="fixed inset-0 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-2 sm:p-4">
        <div class="w-full max-w-4xl bg-white rounded-xl sm:rounded-2xl shadow-2xl transform transition-all mx-2 sm:mx-0 max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 sm:p-6 lg:p-8 border-b border-gray-100 bg-white">
                <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800">Documentos Requeridos</h2>
                        <p class="text-xs text-gray-500">Para continuar con el trámite</p>
                    </div>
                </div>
                <button onclick="cerrarModalDocumentos()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Contenido -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 hover:scrollbar-thumb-gray-400">
                <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6">Asegúrese de tener estos documentos listos:</p>
                
                <!-- Lista de Documentos -->
                <div id="listaDocumentos" class="space-y-2 sm:space-y-3 mb-6">
                    <!-- Los documentos se cargarán dinámicamente aquí -->
                </div>
            </div>
            
            <!-- Footer con botones -->
            <div class="border-t border-gray-100 p-4 sm:p-6 lg:p-8 bg-gray-50/50">
                <div class="space-y-3 sm:space-y-0 sm:flex sm:gap-3">
                    <button onclick="iniciarTramite()" class="w-full sm:flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm">
                        Continuar
                    </button>
                    <button onclick="cerrarModalDocumentos()" class="w-full sm:flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales para el modal
window.tramiteModal = {
    tramiteActual: '',
    
    mostrar: function(tipo) {
        this.tramiteActual = tipo;
        document.getElementById('modalDocumentos').classList.remove('hidden');
        this.cargarDocumentos('Física');
    },
    
    cerrar: function() {
        document.getElementById('modalDocumentos').classList.add('hidden');
        this.tramiteActual = '';
    },
    
    cargarDocumentos: function(tipoPersona) {
        const listaDocumentos = document.getElementById('listaDocumentos');
        listaDocumentos.className = 'space-y-3 mb-6';
        listaDocumentos.innerHTML = `
            <div class="text-center py-4 sm:py-6">
                <div class="animate-spin rounded-full h-5 w-5 sm:h-6 sm:w-6 border-2 border-blue-500 border-t-transparent mx-auto"></div>
                <p class="mt-2 text-xs sm:text-sm text-gray-600">Cargando documentos...</p>
            </div>
        `;
        
        // Hacer petición AJAX para obtener documentos desde la API
        fetch(`/api/documentos/${tipoPersona}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                listaDocumentos.innerHTML = '';
                
                if (data.documentos && data.documentos.length > 0) {
                    // Crear contenedor con grid responsive
                    listaDocumentos.className = 'grid grid-cols-1 lg:grid-cols-2 gap-2 sm:gap-3 mb-6';
                    
                    data.documentos.forEach((documento, index) => {
                        const div = document.createElement('div');
                        div.className = 'flex items-start space-x-2 sm:space-x-3 p-2.5 sm:p-3 bg-gray-50 rounded-lg border border-gray-100/50';
                        div.innerHTML = `
                            <div class="flex-shrink-0 w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-500 rounded-full mt-1.5"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-gray-900 leading-tight">${documento.nombre}</p>
                                ${documento.descripcion ? `<p class="text-xs text-gray-500 mt-1 leading-relaxed">${documento.descripcion}</p>` : ''}
                            </div>
                        `;
                        listaDocumentos.appendChild(div);
                    });
                } else {
                    listaDocumentos.className = 'space-y-3 mb-6';
                    listaDocumentos.innerHTML = `
                        <div class="text-center py-4 sm:py-6 text-gray-500">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs sm:text-sm font-medium">No hay documentos específicos</p>
                            <p class="text-xs text-gray-400 mt-1">Para este trámite</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error cargando documentos:', error);
                listaDocumentos.className = 'space-y-3 mb-6';
                listaDocumentos.innerHTML = `
                    <div class="text-center py-4 sm:py-6 text-red-500">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-xs sm:text-sm font-medium">Error al cargar documentos</p>
                        <p class="text-xs text-red-400 mt-1">Intente nuevamente más tarde</p>
                    </div>
                `;
            });
    },
    
    iniciar: function() {
        if (this.tramiteActual) {
            window.location.href = `/tramites/constancia/${this.tramiteActual}`;
        }
    }
};

// Funciones globales para usar en onclick
function mostrarModalDocumentos(tipo) {
    window.tramiteModal.mostrar(tipo);
}

function cerrarModalDocumentos() {
    window.tramiteModal.cerrar();
}

function iniciarTramite() {
    window.tramiteModal.iniciar();
}
</script>

<style>
/* Scrollbar personalizado para el modal */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
    transition: background 0.2s ease;
}

.scrollbar-thin:hover::-webkit-scrollbar-thumb {
    background: #9ca3af;
}

/* Para Firefox */
.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db #f3f4f6;
}

.scrollbar-thin:hover {
    scrollbar-color: #9ca3af #f3f4f6;
}
</style>
