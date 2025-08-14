@props(['comentario' => ''])

@if(!empty($comentario))
    <div class="mt-8 mb-6 bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-300 rounded-lg p-5 shadow-lg">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-2 mb-3">
                    <h3 class="text-lg font-bold text-red-800">
                        💬 Comentario del Revisor
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-200 text-red-800 animate-pulse">
                        ⚠️ Requiere Corrección
                    </span>
                </div>
                <div class="bg-white border-l-4 border-red-500 p-4 rounded-r-lg shadow-inner">
                    <p class="text-base font-semibold text-red-900 leading-relaxed">
                        "{{ $comentario }}"
                    </p>
                </div>
                <div class="mt-3 text-xs text-red-600 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Por favor, corrija esta sección antes de continuar
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Comentario vacío - mostrar para debug -->
    <div class="mt-4 mb-4 bg-gray-100 border border-gray-300 rounded-lg p-3 opacity-60">
        <div class="text-sm text-gray-600 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <em>Sin comentario específico del revisor para esta sección</em>
        </div>
    </div>
@endif
