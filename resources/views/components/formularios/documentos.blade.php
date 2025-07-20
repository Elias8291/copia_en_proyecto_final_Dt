@props(['tipo' => 'inscripcion', 'proveedor' => null])

<div class="space-y-6">
    <!-- Información sobre documentos requeridos -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="font-semibold text-blue-900 mb-2">Documentos Requeridos</h3>
                <div class="text-blue-700 text-sm space-y-2">
                    @if($tipo === 'inscripcion')
                        <p>Para la inscripción inicial necesitará subir los siguientes documentos:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>RFC actualizado</li>
                            <li>Acta constitutiva (personas morales)</li>
                            <li>Identificación oficial del representante legal</li>
                            <li>Comprobante de domicilio fiscal</li>
                            <li>Estados financieros</li>
                        </ul>
                    @elseif($tipo === 'renovacion')
                        <p>Para la renovación anual necesitará subir:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Estados financieros actualizados</li>
                            <li>Comprobante de domicilio fiscal vigente</li>
                            <li>RFC vigente</li>
                            <li>Documentos adicionales según cambios</li>
                        </ul>
                    @else
                        <p>Para la actualización de datos necesitará subir únicamente los documentos relacionados con los cambios realizados.</p>
                    @endif
    </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de documentos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- RFC -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-file-alt text-red-500 mr-2"></i>
                    RFC
                </h4>
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Requerido</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('rfc-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">Haga clic para cargar RFC</p>
                <p class="text-xs text-gray-400 mt-1">PDF, máximo 5MB</p>
            </div>
            <input type="file" id="rfc-file" name="documentos[rfc]" accept=".pdf" class="hidden">
        </div>

        <!-- Acta Constitutiva (solo para personas morales) -->
        @if($proveedor?->tipo_persona === 'Moral' || !$proveedor)
        <div class="bg-white border border-gray-200 rounded-lg p-4" id="acta-constitutiva">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-building text-blue-500 mr-2"></i>
                    Acta Constitutiva
                </h4>
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Requerido</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('acta-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">Haga clic para cargar Acta Constitutiva</p>
                <p class="text-xs text-gray-400 mt-1">PDF, máximo 10MB</p>
            </div>
            <input type="file" id="acta-file" name="documentos[acta_constitutiva]" accept=".pdf" class="hidden">
        </div>
        @endif

        <!-- Identificación Oficial -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-id-card text-green-500 mr-2"></i>
                    Identificación Oficial
                </h4>
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Requerido</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('identificacion-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">Haga clic para cargar identificación</p>
                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG - máximo 5MB</p>
            </div>
            <input type="file" id="identificacion-file" name="documentos[identificacion]" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
        </div>

        <!-- Comprobante de Domicilio -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-home text-purple-500 mr-2"></i>
                    Comprobante de Domicilio
                </h4>
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Requerido</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('domicilio-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">Haga clic para cargar comprobante</p>
                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG - máximo 5MB</p>
            </div>
            <input type="file" id="domicilio-file" name="documentos[comprobante_domicilio]" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
        </div>

        <!-- Estados Financieros -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-chart-line text-orange-500 mr-2"></i>
                    Estados Financieros
                </h4>
                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Opcional</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('estados-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">Haga clic para cargar estados financieros</p>
                <p class="text-xs text-gray-400 mt-1">PDF, Excel - máximo 10MB</p>
            </div>
            <input type="file" id="estados-file" name="documentos[estados_financieros]" accept=".pdf,.xlsx,.xls" class="hidden">
        </div>

        <!-- Documentos Adicionales -->
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-medium text-gray-900 flex items-center">
                    <i class="fas fa-paperclip text-gray-500 mr-2"></i>
                    Documentos Adicionales
                </h4>
                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Opcional</span>
            </div>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer" onclick="document.getElementById('adicionales-file').click()">
                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                <p class="text-sm text-gray-600">Haga clic para cargar documentos adicionales</p>
                <p class="text-xs text-gray-400 mt-1">Cualquier formato - máximo 10MB</p>
            </div>
            <input type="file" id="adicionales-file" name="documentos[adicionales][]" multiple class="hidden">
        </div>

    </div>

    <!-- Términos y condiciones -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <div class="flex items-start">
            <input type="checkbox" id="acepto-terminos" name="acepto_terminos" class="mt-1 h-4 w-4 text-[#9D2449] border-gray-300 rounded focus:ring-[#9D2449]" required>
            <label for="acepto-terminos" class="ml-3 text-sm text-gray-700">
                Acepto que toda la información proporcionada es veraz y me comprometo a mantenerla actualizada. Entiendo que cualquier información falsa puede resultar en la cancelación de mi registro.
                <a href="#" class="text-[#9D2449] hover:underline">Ver términos y condiciones completos</a>
            </label>
        </div>
    </div>
</div> 

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar preview de archivos
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const uploadArea = e.target.parentNode.querySelector('.border-dashed');
            
            if (file) {
                uploadArea.innerHTML = `
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                    <p class="text-sm text-green-600 font-medium">${file.name}</p>
                    <p class="text-xs text-gray-400 mt-1">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                `;
                uploadArea.classList.remove('border-gray-300');
                uploadArea.classList.add('border-green-300', 'bg-green-50');
            }
        });
    });
});
</script>
@endpush 