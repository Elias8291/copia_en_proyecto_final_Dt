@props([
    'seccion' => '',
    'titulo' => '',
    'showCotejo' => true,
    'cotejoContent' => null,
    'includeForm' => null
])

<div class="mb-6" data-section="{{ $seccion }}">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">{{ $titulo }}</h2>
        @if($showCotejo)
        <button type="button" onclick="toggleCotejo('{{ $seccion }}')" 
                class="flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span id="toggle_text_{{ $seccion }}">Mostrar Cotejo</span>
        </button>
        @endif
    </div>
    
    <div id="content_{{ $seccion }}" class="grid grid-cols-1 gap-6">
        <!-- Contenido principal de la sección -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 min-h-[600px]">
            {{ $slot }}
        </div>
        
        <!-- Panel de cotejo (oculto por defecto) -->
        @if($showCotejo && $cotejoContent)
        <div id="cotejo_{{ $seccion }}" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Información para Cotejo
            </h4>
            {{ $cotejoContent }}
        </div>
        @endif
    </div>
    
    <!-- Área de decisión -->
    @if($includeForm)
        {{ $includeForm }}
    @endif
</div> 