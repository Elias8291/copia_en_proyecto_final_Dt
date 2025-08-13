<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['id' => 'modal-terminos-servicio']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['id' => 'modal-terminos-servicio']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div
    id="<?php echo e($id); ?>"
    tabindex="-1"
    aria-hidden="true"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 p-4"
>
    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <!-- Modal content -->
        <div class="relative rounded-lg bg-white shadow-xl">
            <!-- Modal header -->
            <div
                class="flex items-start justify-between rounded-t border-b p-5 bg-[#9d2449] text-white"
            >
                <h3
                    class="text-xl font-semibold text-white lg:text-2xl"
                >
                    Términos de Servicio
                </h3>
                <button
                    type="button"
                    onclick="closeTerminosModal()"
                    class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-white hover:bg-white hover:text-[#9d2449] transition-colors duration-200"
                >
                    <svg
                        class="h-3 w-3"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 14 14"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                        />
                    </svg>
                    <span class="sr-only">Cerrar modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="space-y-4 p-6">
                <p class="text-base leading-relaxed text-gray-600">
                    Al utilizar nuestros servicios, usted acepta cumplir con los siguientes términos y condiciones que rigen el uso de nuestra plataforma de trámites.
                </p>
                
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 bg-[#9d2449] text-white text-sm font-bold rounded-full flex items-center justify-center">1</span>
                        <div>
                            <strong class="text-gray-900">Información Veraz:</strong>
                            <p class="text-gray-600 mt-1">Usted se compromete a proporcionar información veraz, completa y actualizada en todos los formularios y documentos que presente a través de nuestra plataforma.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 bg-[#9d2449] text-white text-sm font-bold rounded-full flex items-center justify-center">2</span>
                        <div>
                            <strong class="text-gray-900">Documentación:</strong>
                            <p class="text-gray-600 mt-1">Todos los documentos cargados deben ser legítimos, actuales y corresponder a la información proporcionada en el formulario.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 bg-[#9d2449] text-white text-sm font-bold rounded-full flex items-center justify-center">3</span>
                        <div>
                            <strong class="text-gray-900">Responsabilidad:</strong>
                            <p class="text-gray-600 mt-1">Usted es responsable de la veracidad y legalidad de toda la información y documentación proporcionada.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 bg-[#9d2449] text-white text-sm font-bold rounded-full flex items-center justify-center">4</span>
                        <div>
                            <strong class="text-gray-900">Confidencialidad:</strong>
                            <p class="text-gray-600 mt-1">Nos comprometemos a mantener la confidencialidad de su información personal y empresarial de acuerdo con las leyes de protección de datos vigentes.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 bg-[#9d2449] text-white text-sm font-bold rounded-full flex items-center justify-center">5</span>
                        <div>
                            <strong class="text-gray-900">Uso Adecuado:</strong>
                            <p class="text-gray-600 mt-1">La plataforma debe utilizarse únicamente para los fines legítimos de gestión de trámites y cumplimiento de obligaciones legales.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div
                class="flex items-center justify-end space-x-3 rounded-b border-t border-gray-200 p-6"
            >
                <button
                    type="button"
                    onclick="acceptTerminos()"
                    class="rounded-lg bg-[#9d2449] px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-[#7a1c3a] focus:outline-none focus:ring-4 focus:ring-[#9d2449] focus:ring-opacity-50 transition-colors duration-200"
                >
                    Acepto los términos
                </button>
                <button
                    type="button"
                    onclick="closeTerminosModal()"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-colors duration-200"
                >
                    Rechazar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openTerminosModal() {
    const modal = document.getElementById('<?php echo e($id); ?>');
    if (modal) {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
    }
}

function closeTerminosModal() {
    const modal = document.getElementById('<?php echo e($id); ?>');
    if (modal) {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    }
}

function acceptTerminos() {
    // Marcar que los términos fueron aceptados
    const terminosCheckbox = document.getElementById('aceptar_terminos');
    if (terminosCheckbox) {
        terminosCheckbox.checked = true;
        terminosCheckbox.dispatchEvent(new Event('change'));
    }
    
    closeTerminosModal();
    
    // Mostrar mensaje de confirmación
    const successMessage = document.createElement('div');
    successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successMessage.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Términos de servicio aceptados</span>
        </div>
    `;
    
    document.body.appendChild(successMessage);
    
    setTimeout(() => {
        if (successMessage.parentNode) {
            successMessage.remove();
        }
    }, 3000);
}

// Cerrar modal al hacer clic fuera de él
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('<?php echo e($id); ?>');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeTerminosModal();
            }
        });
    }
});
</script> <?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/modals/terminos-servicio.blade.php ENDPATH**/ ?>