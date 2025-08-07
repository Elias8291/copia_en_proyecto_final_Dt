@props([
    'id' => 'deleteModal',
    'title' => 'Confirmar Eliminación',
    'message' => '¿Está seguro de que desea eliminar este elemento?',
    'confirmText' => 'Eliminar',
    'cancelText' => 'Cancelar',
    'itemName' => '',
    'itemType' => 'elemento'
])

<!-- Modal de Confirmación de Eliminación -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>
    
    <!-- Modal -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-xl shadow-2xl border border-gray-200/50 max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="{{ $id }}Content">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                        <p class="text-sm text-gray-500">Acción irreversible</p>
                    </div>
                </div>
                <button onclick="closeDeleteModal('{{ $id }}')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-700 mb-2">{{ $message }}</p>
                        @if($itemName)
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <p class="text-xs font-medium text-gray-600 uppercase tracking-wide mb-1">{{ $itemType }}:</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $itemName }}</p>
                            </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-3 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Esta acción no se puede deshacer
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 p-6 border-t border-gray-100">
                <button onclick="closeDeleteModal('{{ $id }}')" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ $cancelText }}
                </button>
                <button onclick="confirmDelete('{{ $id }}')" 
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(modalId, itemName = '', itemType = 'elemento') {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + 'Content');
    
    if (modal && content) {
        // Actualizar el nombre del elemento si se proporciona
        const itemNameElement = content.querySelector('.bg-gray-50 p-3 .text-sm.font-semibold');
        if (itemNameElement && itemName) {
            itemNameElement.textContent = itemName;
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + 'Content');
    
    if (modal && content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }
}

function confirmDelete(modalId) {
    const modal = document.getElementById(modalId);
    const itemId = modalId.replace('deleteModal', '');
    
    // Buscar el formulario correspondiente
    const form = document.getElementById('delete-form-' + itemId);
    const mobileForm = document.getElementById('delete-form-mobile-' + itemId);
    
    if (form) {
        // Enviar formulario tradicional de Laravel
        form.submit();
    } else if (mobileForm) {
        // Enviar formulario móvil tradicional de Laravel
        mobileForm.submit();
    } else {
        // Si no hay formulario, cerrar el modal
        closeDeleteModal(modalId);
    }
}

// Cerrar modal al hacer clic en el overlay
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                const modalId = this.id;
                closeDeleteModal(modalId);
            }
        });
    });
});
</script> 