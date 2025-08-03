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
            <h3 class="text-lg font-semibold text-gray-900">Documentos</h3>
            <p class="text-sm text-gray-500">Documentación requerida</p>
        </div>
    </div>

    @if(empty($datos))
        @if($editable)
        <div class="space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            No hay documentos cargados
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Cargue los documentos requeridos para continuar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-800 mb-3">Cargar Documentos</h4>
                
                <div class="space-y-4">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Acta Constitutiva <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file-pdf text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[acta_constitutiva]"
                                accept=".pdf,.doc,.docx"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, DOC, DOCX (máx. 10MB)</p>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Poder Notarial
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file-contract text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[poder_notarial]"
                                accept=".pdf,.doc,.docx"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, DOC, DOCX (máx. 10MB)</p>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Identificación Oficial <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[identificacion]"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, JPG, JPEG, PNG (máx. 5MB)</p>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Comprobante de Domicilio
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-home text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[comprobante_domicilio]"
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Formatos permitidos: PDF, JPG, JPEG, PNG (máx. 5MB)</p>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Otros Documentos
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-folder text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[otros][]"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Puede seleccionar múltiples archivos (máx. 10MB cada uno)</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        No hay documentos cargados
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>No se han cargado documentos para este trámite.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @else
        <div class="space-y-4">
            @foreach($datos as $index => $documento)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-800">
                        {{ $documento['nombre'] ?? 'Documento #' . ($index + 1) }}
                    </h4>
                    @if($editable)
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="eliminarDocumento({{ $index }})">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Documento
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file text-gray-500"></i>
                            </div>
                            <input type="text"
                                name="documentos[{{ $index }}][nombre]"
                                value="{{ $documento['nombre'] ?? '' }}"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                {{ !$editable ? 'disabled' : '' }}
                                placeholder="Ingrese nombre del documento">
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Documento
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-500"></i>
                            </div>
                            <select name="documentos[{{ $index }}][tipo]"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                {{ !$editable ? 'disabled' : '' }}>
                                <option value="">Seleccione tipo</option>
                                <option value="acta_constitutiva" {{ ($documento['tipo'] ?? '') == 'acta_constitutiva' ? 'selected' : '' }}>Acta Constitutiva</option>
                                <option value="poder_notarial" {{ ($documento['tipo'] ?? '') == 'poder_notarial' ? 'selected' : '' }}>Poder Notarial</option>
                                <option value="identificacion" {{ ($documento['tipo'] ?? '') == 'identificacion' ? 'selected' : '' }}>Identificación Oficial</option>
                                <option value="comprobante_domicilio" {{ ($documento['tipo'] ?? '') == 'comprobante_domicilio' ? 'selected' : '' }}>Comprobante de Domicilio</option>
                                <option value="otros" {{ ($documento['tipo'] ?? '') == 'otros' ? 'selected' : '' }}>Otros</option>
                            </select>
                        </div>
                    </div>

                    @if($editable)
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Archivo
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-upload text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[{{ $index }}][archivo]"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                    </div>
                    @endif
                </div>

                @if(!empty($documento['comentarios']))
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Comentarios
                    </label>
                    <textarea name="documentos[{{ $index }}][comentarios]"
                        rows="2"
                        class="block w-full px-3 py-2 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        {{ !$editable ? 'disabled' : '' }}
                        placeholder="Comentarios adicionales">{{ $documento['comentarios'] ?? '' }}</textarea>
                </div>
                @endif
            </div>
            @endforeach

            @if($editable)
            <div class="mt-4">
                <button type="button" id="agregarDocumento" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Agregar otro documento
                </button>
            </div>
            @endif
        </div>
    @endif
</div>

@if($editable)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let documentoCount = {{ empty($datos) ? 0 : count($datos) }};
    
    const agregarDocumentoBtn = document.getElementById('agregarDocumento');
    if (agregarDocumentoBtn) {
        agregarDocumentoBtn.addEventListener('click', function() {
            const container = document.querySelector('.space-y-4');
            const newDocumento = document.createElement('div');
            newDocumento.className = 'border border-gray-200 rounded-lg p-4';
            newDocumento.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-800">
                        Documento #${documentoCount + 1}
                    </h4>
                    <button type="button" class="text-red-600 hover:text-red-800" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:gap-6">
                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Documento
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-file text-gray-500"></i>
                            </div>
                            <input type="text"
                                name="documentos[${documentoCount}][nombre]"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                placeholder="Ingrese nombre del documento">
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Documento
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-gray-500"></i>
                            </div>
                            <select name="documentos[${documentoCount}][tipo]"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="">Seleccione tipo</option>
                                <option value="acta_constitutiva">Acta Constitutiva</option>
                                <option value="poder_notarial">Poder Notarial</option>
                                <option value="identificacion">Identificación Oficial</option>
                                <option value="comprobante_domicilio">Comprobante de Domicilio</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group field-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Archivo
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-upload text-gray-500"></i>
                            </div>
                            <input type="file"
                                name="documentos[${documentoCount}][archivo]"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="block w-full pl-10 pr-4 py-2.5 text-gray-900 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-primary/30 focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertBefore(newDocumento, agregarDocumentoBtn.parentElement);
            documentoCount++;
        });
    }
});

function eliminarDocumento(index) {
    if (confirm('¿Está seguro de que desea eliminar este documento?')) {
        const documentoElement = document.querySelector(`[name="documentos[${index}][nombre]"]`).closest('.border');
        documentoElement.remove();
    }
}
</script>
@endif 