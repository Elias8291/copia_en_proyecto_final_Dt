@props(['archivosSubidos', 'seccion' => 'archivos'])

<div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-emerald-800">Resumen de Evaluación</h4>
                <p class="text-sm text-emerald-600">Estado actual de la revisión de documentos</p>
            </div>
        </div>
        <div class="text-right">
            <div class="text-2xl font-bold text-emerald-800" id="progreso-evaluacion">0%</div>
            <div class="text-xs text-emerald-600">Completado</div>
        </div>
    </div>

    <!-- Estadísticas detalladas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-white/80 backdrop-blur-sm rounded-lg p-3 text-center border border-emerald-200">
            <div class="text-xl font-bold text-gray-800" id="total-docs">{{ count($archivosSubidos) }}</div>
            <div class="text-xs text-gray-600 font-medium">Total</div>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-lg p-3 text-center border border-green-200">
            <div class="text-xl font-bold text-green-600" id="aprobados-docs">0</div>
            <div class="text-xs text-green-600 font-medium">Aprobados</div>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-lg p-3 text-center border border-red-200">
            <div class="text-xl font-bold text-red-600" id="rechazados-docs">0</div>
            <div class="text-xs text-red-600 font-medium">Rechazados</div>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-lg p-3 text-center border border-gray-200">
            <div class="text-xl font-bold text-gray-600" id="pendientes-docs">0</div>
            <div class="text-xs text-gray-600 font-medium">Pendientes</div>
        </div>
    </div>

    <!-- Barra de progreso -->
    <div class="mb-4">
        <div class="flex items-center justify-between text-xs text-emerald-700 mb-1">
            <span>Progreso de evaluación</span>
            <span id="progreso-texto">0 de {{ count($archivosSubidos) }} documentos evaluados</span>
        </div>
        <div class="w-full bg-emerald-200 rounded-full h-3">
            <div id="progreso-barra-detallada" class="bg-emerald-600 h-3 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
        </div>
    </div>

    <!-- Alertas y recomendaciones -->
    <div id="alertas-evaluacion" class="space-y-2">
        <!-- Las alertas se generarán dinámicamente -->
    </div>

    <!-- Acciones rápidas -->
    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-emerald-200">
        <div class="flex items-center space-x-2">
            <button type="button" onclick="expandirPendientes()" class="text-sm text-emerald-700 hover:text-emerald-900 font-medium">
                Expandir pendientes
            </button>
            <span class="text-emerald-300">|</span>
            <button type="button" onclick="expandirRechazados()" class="text-sm text-red-700 hover:text-red-900 font-medium">
                Expandir rechazados
            </button>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-emerald-600">Última actualización:</span>
            <span id="ultima-actualizacion" class="text-xs text-emerald-700">{{ now()->format('H:i:s') }}</span>
        </div>
    </div>
</div> 