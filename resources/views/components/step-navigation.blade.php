@props([
    'currentStep' => 0,
    'totalSteps' => 0
])

<div class="bg-white border-t border-gray-200 mt-8 pt-6" data-step-navigation>
    <div class="flex justify-between items-center">
        <!-- Botón Anterior -->
        <button type="button" 
                onclick="navigateStep('prev')" 
                class="flex items-center px-6 py-3 text-gray-600 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:border-gray-200 disabled:hover:text-gray-600 font-medium shadow-sm hover:shadow-md"
                {{ $currentStep === 0 ? 'disabled' : '' }}>
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Anterior
        </button>

        <!-- Indicador de progreso -->
        <div class="text-center">
            <div class="text-sm font-medium text-gray-500" data-step-indicator>
                Paso {{ $currentStep + 1 }} de {{ $totalSteps }}
            </div>
            <div class="text-xs text-gray-400 mt-1">
                {{ round((($currentStep + 1) / $totalSteps) * 100) }}% completado
            </div>
        </div>

        <!-- Botón Siguiente -->
        <button type="button" 
                onclick="navigateStep('next')" 
                class="flex items-center px-6 py-3 text-white bg-gradient-to-r from-[#9d2449] to-[#8a1f40] border-2 border-[#9d2449] rounded-xl hover:from-[#8a1f40] hover:to-[#7a1a37] hover:border-[#8a1f40] transition-all duration-300 disabled:opacity-40 disabled:cursor-not-allowed font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Siguiente
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div> 