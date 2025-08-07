@props(['editable' => true])

<div class="space-y-6" {{ $attributes }}>
    <!-- Título de la sección -->
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Términos y Condiciones</h3>
            <p class="text-sm text-gray-500">Aceptación de términos de servicio</p>
        </div>
    </div>

    @if($editable)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">
                    Términos Obligatorios
                </h3>
                <p class="text-sm text-blue-700 mt-1">
                    Para continuar con el trámite, debe leer y aceptar los términos de servicio.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="space-y-4">
            <!-- Checkbox de aceptación -->
            <div class="flex items-start space-x-3">
                <div class="flex items-center h-5">
                    <input 
                        id="aceptar_terminos" 
                        name="aceptar_terminos" 
                        type="checkbox" 
                        value="1"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        required
                    >
                </div>
                <div class="text-sm">
                    <label for="aceptar_terminos" class="font-medium text-gray-700">
                        He leído y acepto los 
                        <button 
                            type="button" 
                            onclick="openTerminosModal()"
                            class="text-blue-600 hover:text-blue-800 underline font-medium"
                        >
                            términos de servicio
                        </button>
                        <span class="text-red-500">*</span>
                    </label>
                    <p class="text-gray-500 mt-1">
                        Al marcar esta casilla, confirma que ha leído y acepta cumplir con todos los términos y condiciones establecidos.
                    </p>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Información importante:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Los términos de servicio son obligatorios para continuar</li>
                    <li>• Puede leer los términos completos haciendo clic en el enlace</li>
                    <li>• Al aceptar, confirma que proporcionará información veraz</li>
                    <li>• Se compromete a cargar documentos legítimos y actuales</li>
                </ul>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-gray-900">Términos aceptados</h4>
                <p class="text-sm text-gray-600">Los términos de servicio fueron aceptados durante la creación del trámite.</p>
            </div>
        </div>
    </div>
    @endif
</div> 