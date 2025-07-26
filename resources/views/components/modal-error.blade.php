@props([
    'id' => 'modal-error',
    'title' => 'Error',
    'message' => 'Ha ocurrido un error al procesar su solicitud.',
    'buttonText' => 'OK',
])

@if(session('error'))
<div id="{{ $id }}" class="fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline-{{ $id }}">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline-{{ $id }}">
                        {{ session('error_title', $title) }}
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            {{ session('error_message', $message) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button
                    class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm"
                    onclick="closeErrorModal('{{ $id }}')">
                    {{ session('error_button_text', $buttonText) }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function closeErrorModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
    // Cerrar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('{{ $id }}');
            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        }
    });
    
    // Cerrar al hacer clic fuera del modal
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('{{ $id }}');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    });
</script>
@endif 