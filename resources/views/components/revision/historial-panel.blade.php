@props([
    'tramite' => null,
    'estadisticasHistorial' => [],
    'historialTramites' => []
])

<!-- Panel de Historial -->
<div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Historial de Trámites</h3>
                <p class="text-sm text-gray-500">RFC: {{ $tramite->proveedor->rfc }}</p>
            </div>
        </div>
        <button type="button" onclick="toggleHistorial()" 
                class="flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
            <span id="toggle_text_historial">Mostrar Historial</span>
        </button>
    </div>

    <!-- Estadísticas rápidas -->
    <x-revision.estadisticas-card 
        :estadisticas="[
            'total' => ['valor' => $estadisticasHistorial['total'], 'label' => 'Total', 'color' => 'gray'],
            'aprobados' => ['valor' => $estadisticasHistorial['aprobados'], 'label' => 'Aprobados', 'color' => 'green'],
            'rechazados' => ['valor' => $estadisticasHistorial['rechazados'], 'label' => 'Rechazados', 'color' => 'red'],
            'pendientes' => ['valor' => $estadisticasHistorial['pendientes'], 'label' => 'Pendientes', 'color' => 'yellow']
        ]"
    />

    <!-- Lista de trámites históricos -->
    <div id="contenido_historial" class="hidden mt-4">
        @if($historialTramites->count() > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($historialTramites as $tramiteHistorico)
                    <x-revision.historial-item 
                        :tramite-historico="$tramiteHistorico"
                        :tramite-actual="$tramite"
                    />
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500">No hay trámites anteriores para este RFC</p>
            </div>
        @endif
    </div>
</div> 