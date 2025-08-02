@props([
    'dataCount' => 0,
    'totalCount' => 0,
    'currentPage' => 1,
    'perPage' => 10
])

@if($dataCount > 0)
<div class="bg-gray-100/80 backdrop-blur-sm px-10 py-6 border-t border-gray-200">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div class="text-base text-gray-700 mb-4 lg:mb-0">
            Mostrando <span class="font-semibold">{{ $dataCount }}</span> de <span class="font-semibold">{{ $totalCount }}</span> resultados
        </div>
        <div class="flex items-center space-x-3">
            <button class="px-6 py-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Anterior
            </button>
            <span class="px-6 py-3 text-sm font-semibold text-primary bg-primary/10 border border-primary/20 rounded-xl">{{ $currentPage }}</span>
            <button class="px-6 py-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm hover:shadow-md">
                Siguiente
                <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
@endif 