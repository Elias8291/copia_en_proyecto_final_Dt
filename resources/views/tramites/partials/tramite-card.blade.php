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
<div id="modalDocumentos" class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10 z-50 hidden">
    <div class="max-h-full w-full max-w-2xl overflow-y-auto sm:rounded-2xl bg-white">
        <div class="w-full">
            <div class="m-8 my-10 max-w-[600px] mx-auto">
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">Documentos Requeridos</h1>
                        <button onclick="cerrarModalDocumentos()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-600 mb-6">Antes de iniciar el trámite, asegúrese de tener los siguientes documentos listos:</p>
                    
                    <!-- Lista de Documentos -->
                    <div id="listaDocumentos" class="space-y-4 mb-6">
                        <!-- Los documentos se cargarán dinámicamente aquí -->
                    </div>
                </div>
                <div class="space-y-3">
                    <button onclick="iniciarTramite()" class="p-3 bg-gray-900 rounded-lg text-white w-full font-semibold hover:bg-gray-800 transition-colors">
                        Continuar con el trámite
                    </button>
                    <button onclick="cerrarModalDocumentos()" class="p-3 bg-white border border-gray-300 rounded-lg w-full font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let tramiteActual = '';

function mostrarModalDocumentos(tipo) {
    tramiteActual = tipo;
    
    // Mostrar modal
    document.getElementById('modalDocumentos').classList.remove('hidden');
    
    // Cargar documentos según el tipo de persona (por defecto Física)
    cargarDocumentos('Física');
}

function cerrarModalDocumentos() {
    document.getElementById('modalDocumentos').classList.add('hidden');
    tramiteActual = '';
}

function cargarDocumentos(tipoPersona) {
    // Hacer petición AJAX para obtener documentos
    fetch(`/api/documentos/${tipoPersona}`)
        .then(response => response.json())
        .then(data => {
            const listaDocumentos = document.getElementById('listaDocumentos');
            listaDocumentos.innerHTML = '';
            
            if (data.documentos && data.documentos.length > 0) {
                data.documentos.forEach(documento => {
                    const iconClass = getIconClass(documento.tipo_archivo);
                    const div = document.createElement('div');
                    div.className = 'flex items-center p-3 bg-gray-50 rounded-lg';
                    div.innerHTML = `
                        <i class="${iconClass} text-lg mr-3"></i>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">${documento.nombre}</h4>
                            <p class="text-xs text-gray-500">${documento.descripcion || 'Documento requerido'}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-xs text-gray-600">Formato: ${documento.tipo_archivo_label}</span>
                            </div>
                        </div>
                    `;
                    listaDocumentos.appendChild(div);
                });
            } else {
                listaDocumentos.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <p>No hay documentos específicos requeridos para este trámite.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error cargando documentos:', error);
            document.getElementById('listaDocumentos').innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                    <p>Error al cargar los documentos requeridos.</p>
                </div>
            `;
        });
}

function getIconClass(tipoArchivo) {
    switch(tipoArchivo.toLowerCase()) {
        case 'pdf': return 'fas fa-file-pdf text-red-600';
        case 'png':
        case 'jpg':
        case 'jpeg': return 'fas fa-file-image text-blue-600';
        case 'mp3': return 'fas fa-file-audio text-purple-600';
        default: return 'fas fa-file text-gray-600';
    }
}

function iniciarTramite() {
    if (tramiteActual) {
        window.location.href = `/tramites/constancia/${tramiteActual}`;
    }
}
</script>
