@props(['datos' => [], 'editable' => false])

<div class="space-y-6" {{ $attributes }}>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Archivos</h3>
            <p class="text-sm text-gray-500">Documentación requerida</p>
        </div>
    </div>

    @if(empty($datos))
        @if($editable)
        <div>
            <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
                Cargar Archivos
            </h4>
            
            <!-- Alerta mejorada -->
            <div class="bg-primary/10 border border-primary/20 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-primary"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-primary">
                            Archivos Requeridos
                        </h3>
                        <div class="mt-2 text-sm text-primary/80">
                            <p>Complete la carga de todos los archivos obligatorios para continuar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Acta Constitutiva -->
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-primary transition-colors">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-file-pdf text-primary text-lg"></i>
                        </div>
                        <h5 class="font-medium text-gray-900 mb-2">Acta Constitutiva</h5>
                        <p class="text-xs text-gray-500 mb-3">Archivo obligatorio</p>
                        <label class="cursor-pointer">
                            <input type="file"
                                name="documentos[acta_constitutiva]"
                                accept=".pdf,.doc,.docx"
                                class="hidden"
                                onchange="updateFileName(this, 'acta-constitutiva-name')">
                            <span class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Seleccionar
                            </span>
                        </label>
                        <p id="acta-constitutiva-name" class="text-xs text-gray-600 mt-2 hidden"></p>
                    </div>
                </div>

                <!-- Poder Notarial -->
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-primary transition-colors">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-file-contract text-primary text-lg"></i>
                        </div>
                        <h5 class="font-medium text-gray-900 mb-2">Poder Notarial</h5>
                        <p class="text-xs text-gray-500 mb-3">Archivo opcional</p>
                        <label class="cursor-pointer">
                            <input type="file"
                                name="documentos[poder_notarial]"
                                accept=".pdf,.doc,.docx"
                                class="hidden"
                                onchange="updateFileName(this, 'poder-notarial-name')">
                            <span class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Seleccionar
                            </span>
                        </label>
                        <p id="poder-notarial-name" class="text-xs text-gray-600 mt-2 hidden"></p>
                    </div>
                </div>

                <!-- Identificación Oficial -->
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-primary transition-colors">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-id-card text-primary text-lg"></i>
                        </div>
                        <h5 class="font-medium text-gray-900 mb-2">Identificación Oficial</h5>
                        <p class="text-xs text-gray-500 mb-3">Archivo obligatorio</p>
                        <label class="cursor-pointer">
                            <input type="file"
                                name="documentos[identificacion]"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="hidden"
                                onchange="updateFileName(this, 'identificacion-name')">
                            <span class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Seleccionar
                            </span>
                        </label>
                        <p id="identificacion-name" class="text-xs text-gray-600 mt-2 hidden"></p>
                    </div>
                </div>

                <!-- Comprobante de Domicilio -->
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-primary transition-colors">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-home text-primary text-lg"></i>
                        </div>
                        <h5 class="font-medium text-gray-900 mb-2">Comprobante de Domicilio</h5>
                        <p class="text-xs text-gray-500 mb-3">Archivo opcional</p>
                        <label class="cursor-pointer">
                            <input type="file"
                                name="documentos[comprobante_domicilio]"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="hidden"
                                onchange="updateFileName(this, 'comprobante-domicilio-name')">
                            <span class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Seleccionar
                            </span>
                        </label>
                        <p id="comprobante-domicilio-name" class="text-xs text-gray-600 mt-2 hidden"></p>
                    </div>
                </div>

                <!-- Otros Archivos -->
                <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-primary transition-colors">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-folder text-primary text-lg"></i>
                        </div>
                        <h5 class="font-medium text-gray-900 mb-2">Otros Archivos</h5>
                        <p class="text-xs text-gray-500 mb-3">Múltiples archivos</p>
                        <label class="cursor-pointer">
                            <input type="file"
                                name="documentos[otros][]"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="hidden"
                                onchange="updateFileName(this, 'otros-archivos-name')">
                            <span class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Seleccionar
                            </span>
                        </label>
                        <p id="otros-archivos-name" class="text-xs text-gray-600 mt-2 hidden"></p>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <h5 class="font-medium text-gray-900 mb-2">Formatos permitidos:</h5>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• <strong>PDF, DOC, DOCX:</strong> Para archivos legales (máx. 10MB)</li>
                    <li>• <strong>JPG, JPEG, PNG:</strong> Para imágenes (máx. 5MB)</li>
                    <li>• <strong>Múltiples archivos:</strong> Para "Otros Archivos"</li>
                </ul>
            </div>
        </div>
        @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-alt text-gray-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">
                        Sin Archivos
                    </h3>
                    <p class="text-gray-600">
                        No se han cargado archivos para este trámite.
                    </p>
                </div>
            </div>
        </div>
        @endif
    @else
        <div>
            <h4 class="text-sm font-semibold text-gray-800 mb-3 pb-2 border-b-2 border-gray-200 sm:text-base sm:mb-4 sm:pb-3">
                Archivos Cargados
            </h4>
            
            <div class="space-y-4">
                @foreach($datos as $index => $documento)
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-primary"></i>
                            </div>
                            <h5 class="font-medium text-gray-900">
                                {{ $documento['nombre'] ?? 'Archivo #' . ($index + 1) }}
                            </h5>
                        </div>
                        @if($editable)
                        <button type="button" 
                                class="w-6 h-6 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded flex items-center justify-center transition-colors" 
                                onclick="eliminarArchivo({{ $index }})"
                                title="Eliminar archivo">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre del Archivo
                            </label>
                            <input type="text"
                                name="documentos[{{ $index }}][nombre]"
                                value="{{ $documento['nombre'] ?? '' }}"
                                class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                {{ !$editable ? 'disabled' : '' }}
                                placeholder="Ingrese nombre del archivo">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tipo de Archivo
                            </label>
                            <select name="documentos[{{ $index }}][tipo]"
                                class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                {{ !$editable ? 'disabled' : '' }}>
                                <option value="">Seleccione tipo</option>
                                <option value="acta_constitutiva" {{ ($documento['tipo'] ?? '') == 'acta_constitutiva' ? 'selected' : '' }}>Acta Constitutiva</option>
                                <option value="poder_notarial" {{ ($documento['tipo'] ?? '') == 'poder_notarial' ? 'selected' : '' }}>Poder Notarial</option>
                                <option value="identificacion" {{ ($documento['tipo'] ?? '') == 'identificacion' ? 'selected' : '' }}>Identificación Oficial</option>
                                <option value="comprobante_domicilio" {{ ($documento['tipo'] ?? '') == 'comprobante_domicilio' ? 'selected' : '' }}>Comprobante de Domicilio</option>
                                <option value="otros" {{ ($documento['tipo'] ?? '') == 'otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                        </div>

                        @if($editable)
                        <div class="form-group sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Archivo
                            </label>
                            <input type="file"
                                name="documentos[{{ $index }}][archivo]"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </div>
                        @endif
                    </div>

                    @if(!empty($documento['comentarios']))
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Comentarios
                        </label>
                        <textarea name="documentos[{{ $index }}][comentarios]"
                            rows="2"
                            class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            {{ !$editable ? 'disabled' : '' }}
                            placeholder="Comentarios adicionales">{{ $documento['comentarios'] ?? '' }}</textarea>
                    </div>
                    @endif
                </div>
                @endforeach

                @if($editable)
                <div class="flex justify-center">
                    <button type="button" id="agregarArchivo" 
                            class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-md transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Agregar Archivo
                    </button>
                </div>
                @endif
            </div>
        </div>
    @endif
</div>

@if($editable)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let archivoCount = {{ empty($datos) ? 0 : count($datos) }};
    
    const agregarArchivoBtn = document.getElementById('agregarArchivo');
    if (agregarArchivoBtn) {
        agregarArchivoBtn.addEventListener('click', function() {
            const container = document.querySelector('.space-y-4');
            const newArchivo = document.createElement('div');
            newArchivo.className = 'bg-white border border-gray-200 rounded-lg p-4';
            newArchivo.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <h5 class="font-medium text-gray-900">
                            Archivo #${archivoCount + 1}
                        </h5>
                    </div>
                    <button type="button" 
                            class="w-6 h-6 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded flex items-center justify-center transition-colors" 
                            onclick="this.closest('.bg-white').remove()"
                            title="Eliminar archivo">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre del Archivo
                        </label>
                        <input type="text"
                            name="documentos[${archivoCount}][nombre]"
                            class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            placeholder="Ingrese nombre del archivo">
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de Archivo
                        </label>
                        <select name="documentos[${archivoCount}][tipo]"
                            class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="">Seleccione tipo</option>
                            <option value="acta_constitutiva">Acta Constitutiva</option>
                            <option value="poder_notarial">Poder Notarial</option>
                            <option value="identificacion">Identificación Oficial</option>
                            <option value="comprobante_domicilio">Comprobante de Domicilio</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Archivo
                        </label>
                        <input type="file"
                            name="documentos[${archivoCount}][archivo]"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-md focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </div>
            `;
            
            container.insertBefore(newArchivo, agregarArchivoBtn.parentElement);
            archivoCount++;
        });
    }
});

function updateFileName(input, elementId) {
    const fileNameElement = document.getElementById(elementId);
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        fileNameElement.textContent = fileName.length > 20 ? fileName.substring(0, 20) + '...' : fileName;
        fileNameElement.classList.remove('hidden');
    }
}

function eliminarArchivo(index) {
    if (confirm('¿Está seguro de que desea eliminar este archivo?')) {
        const archivoElement = document.querySelector(`[name="documentos[${index}][nombre]"]`).closest('.bg-white');
        archivoElement.remove();
    }
}
</script>
@endif 