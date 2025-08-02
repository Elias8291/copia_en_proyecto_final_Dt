@props([
    'actions' => []
])

<tr class="lg:table-row">
    <td colspan="100%" class="px-10 py-20 text-center">
        <div class="flex flex-col items-center">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mb-8">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">No hay datos</h3>
            <p class="text-gray-500 text-lg mb-8 max-w-lg">No se encontraron registros para mostrar.</p>
            @if(isset($actions['create']))
                <a href="{{ $actions['create']['url'] ?? '#' }}" 
                   class="px-8 py-4 bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-6 h-6 mr-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ $actions['create']['label'] ?? 'Crear nuevo' }}
                </a>
            @endif
        </div>
    </td>
</tr> 