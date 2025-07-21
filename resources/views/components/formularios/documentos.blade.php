@props(['tipo' => 'inscripcion', 'proveedor' => null, 'editable' => true])

<div class="max-w-7xl mx-auto space-y-8" {{ $attributes }}>
    <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
        <!-- Encabezado con icono -->
        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-100">
            <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-[#9d2449] to-[#8a203f] text-white shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <i class="fas fa-file-upload text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Documentos</h2>
                <p class="text-sm text-gray-500 mt-1">
                    @if ($editable)
                        Documentos adjuntos al trámite
                    @else
                        Documentos del trámite registrado
                    @endif
                </p>
            </div>
        </div>
    
        <!-- Lista de Documentos -->
        <div class="space-y-6">
            <!-- Ejemplo de documento -->
            <div class="bg-white border-2 rounded-lg p-6 transition-all duration-300 border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-file-pdf text-2xl mr-3 text-[#9d2449]"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Nombre del Documento</h4>
                            <p class="text-xs text-gray-500">Documento requerido</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="#" target="_blank" class="text-green-600 hover:text-green-800 text-xs underline">
                            <i class="fas fa-eye mr-1"></i>
                            Ver
                        </a>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                            <i class="fas fa-clock mr-1"></i>
                            Pendiente
                        </span>
                    </div>
                </div>
                <!-- Información adicional del archivo -->
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-file-pdf text-[#9d2449] mr-2"></i>
                        <span>Documento adjunto</span>
                        <span class="ml-auto text-xs text-gray-500">Subido: 21/07/2025 12:28</span>
                    </div>
                </div>
            </div>
    
            <!-- Ejemplo de documento aprobado -->
            <div class="bg-white border-2 rounded-lg p-6 transition-all duration-300 border-green-300 bg-green-50">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-file-pdf text-2xl mr-3 text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Nombre del Documento Aprobado</h4>
                            <p class="text-xs text-gray-500">Documento requerido</p>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-check-circle text-green-500 text-xs mr-1"></i>
                                <span class="text-xs font-medium text-green-600">Aprobado</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="#" target="_blank" class="text-green-600 hover:text-green-800 text-xs underline">
                            <i class="fas fa-eye mr-1"></i>
                            Ver
                        </a>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                            <i class="fas fa-check mr-1"></i>
                            Aprobado
                        </span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-file-pdf text-[#9d2449] mr-2"></i>
                        <span>Documento adjunto</span>
                        <span class="ml-auto text-xs text-gray-500">Subido: 21/07/2025 12:28</span>
                    </div>
                </div>
            </div>
    
            <!-- Ejemplo de documento rechazado -->
            <div class="bg-white border-2 rounded-lg p-6 transition-all duration-300 border-red-300 bg-red-50">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="relative">
                            <i class="fas fa-file-pdf text-2xl mr-3 text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Nombre del Documento Rechazado</h4>
                            <p class="text-xs text-gray-500">Documento requerido</p>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-times-circle text-red-500 text-xs mr-1"></i>
                                <span class="text-xs font-medium text-red-600">Rechazado</span>
                            </div>
                            <div class="mt-1">
                                <p class="text-xs text-red-600">Motivo del rechazo: Documento no legible</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="#" target="_blank" class="text-green-600 hover:text-green-800 text-xs underline">
                            <i class="fas fa-eye mr-1"></i>
                            Ver
                        </a>
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                            <i class="fas fa-times mr-1"></i>
                            Rechazado
                        </span>
                    </div>
                </div>
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-file-pdf text-[#9d2449] mr-2"></i>
                        <span>Documento adjunto</span>
                        <span class="ml-auto text-xs text-gray-500">Subido: 21/07/2025 12:28</span>
                    </div>
                </div>
            </div>
    
            <!-- Mensaje cuando no hay documentos -->
            <div class="text-center py-8 hidden">
                <div class="bg-gray-50 rounded-lg p-6">
                    <i class="fas fa-exclamation-circle text-gray-400 text-3xl mb-3"></i>
                    <p class="text-gray-500">No hay documentos adjuntos a este trámite.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.h-12 {
    position: relative;
    overflow: hidden;
}
.h-12::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
    );
    transform: rotate(45deg);
    animation: shine 3s infinite;
}
@keyframes shine {
    0% {
        transform: translateX(-100%) rotate(45deg);
    }
    20%, 100% {
        transform: translateX(100%) rotate(45deg);
    }
}
.form-group:hover input:not([readonly]),
.form-group:hover select,
.form-group:hover textarea {
    @apply border-[#9d2449]/30;
}
input:focus:not([readonly]), 
select:focus,
textarea:focus {
    @apply ring-2 ring-[#9d2449]/20 border-[#9d2449];
    box-shadow: 0 0 0 1px rgba(157, 36, 73, 0.1), 
                0 2px 4px rgba(157, 36, 73, 0.05);
}
input[readonly] {
    @apply bg-gray-50;
}
input, select, textarea {
    @apply transition-all duration-300 bg-white shadow-sm;
}
input:focus:not([readonly]), 
select:focus, 
textarea:focus {
    @apply transform -translate-y-px shadow-md bg-white;
}
.form-group {
    @apply relative;
}
</style>

