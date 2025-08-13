<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'id' => 'modal-confirmacion',
    'title' => 'Confirmar acción',
    'message' => '¿Está seguro que desea realizar esta acción?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'confirmClass' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
    'cancelClass' => 'bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-blue-500',
]));

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

foreach (array_filter(([
    'id' => 'modal-confirmacion',
    'title' => 'Confirmar acción',
    'message' => '¿Está seguro que desea realizar esta acción?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'confirmClass' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
    'cancelClass' => 'bg-white border-gray-300 text-gray-700 hover:text-gray-500 focus:ring-blue-500',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div id="<?php echo e($id); ?>" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline-<?php echo e($id); ?>">

            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button type="button" data-behavior="cancel"
                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="sm:flex sm:items-start">
                <div
                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline-<?php echo e($id); ?>">
                        <?php echo e($title); ?>

                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" id="modal-message-<?php echo e($id); ?>">
                            <?php echo e($message); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" data-behavior="commit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm <?php echo e($confirmClass); ?>">
                    <?php echo e($confirmText); ?>

                </button>
                <button type="button" data-behavior="cancel"
                    class="mt-3 w-full inline-flex justify-center rounded-md border shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm <?php echo e($cancelClass); ?>">
                    <?php echo e($cancelText); ?>

                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        'use strict';
        let modalInstance = null;
        let formToSubmit = null;
        let onConfirmCallback = null;

        window.showConfirmModal = function(title, message, formId, callback) {
            const modal = document.getElementById('<?php echo e($id); ?>');
            const headline = document.getElementById('modal-headline-<?php echo e($id); ?>');
            const messageEl = document.getElementById('modal-message-<?php echo e($id); ?>');

            if (headline) headline.textContent = title || '<?php echo e($title); ?>';
            if (messageEl) messageEl.textContent = message || '<?php echo e($message); ?>';

            modal.classList.remove('hidden');
            modalInstance = modal;
            formToSubmit = formId ? document.getElementById(formId) : null;
            onConfirmCallback = callback || null;
        };

        window.hideConfirmModal = function() {
            if (modalInstance) {
                modalInstance.classList.add('hidden');
            }
            modalInstance = null;
            formToSubmit = null;
            onConfirmCallback = null;
        };
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('<?php echo e($id); ?>');
            if (!modal) return;

            const confirmBtn = modal.querySelector('[data-behavior="commit"]');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (onConfirmCallback && typeof onConfirmCallback === 'function') {
                        onConfirmCallback();
                    } else if (formToSubmit) {
                        formToSubmit.submit();
                    }
                    hideConfirmModal();
                });
            }
            const cancelBtns = modal.querySelectorAll('[data-behavior="cancel"]');
            cancelBtns.forEach(function(btn) {
                btn.addEventListener('click', hideConfirmModal);
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideConfirmModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideConfirmModal();
                }
            });
        });
    })();
</script>
<?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/ui/modals/modal-confirmacion.blade.php ENDPATH**/ ?>