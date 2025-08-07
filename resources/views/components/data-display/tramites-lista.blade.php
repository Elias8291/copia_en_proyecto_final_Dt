@props([
    'tramites' => [],
    'titulo' => 'Lista de Trámites',
    'descripcion' => 'Administra y revisa el estado de los trámites',
    'mostrarFiltros' => true,
    'mostrarAcciones' => true,
    'tipoVista' => 'completa', // completa, simple, tarjetas
    'accionPrincipal' => null // ['texto' => 'Crear', 'url' => '#', 'icono' => '...']
])

<div class="w-full">
    <!-- Header de la Lista -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200/70 mb-8">
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 rounded-xl p-3 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-800">{{ $titulo }}</h1>
                        <p class="text-sm md:text-base text-gray-500">{{ $descripcion }}</p>
                    </div>
                </div>
                
                @if($accionPrincipal && $mostrarAcciones)
                <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                    <a href="{{ $accionPrincipal['url'] }}" 
                       class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                        @if(isset($accionPrincipal['icono']))
                            {!! $accionPrincipal['icono'] !!}
                        @endif
                        {{ $accionPrincipal['texto'] }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($mostrarFiltros && $tipoVista === 'completa')
    <!-- Filtros -->
    <x-tramites.filtros />
    @endif

    @if($tipoVista === 'tarjetas')
        <!-- Vista de Tarjetas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            @forelse($tramites as $tramite)
                <x-tramites.tarjeta :tramite="$tramite" />
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Sin trámites</h3>
                    <p class="mt-1 text-gray-500">No hay trámites disponibles en este momento.</p>
                </div>
            @endforelse
        </div>
    @else
        <!-- Vista de Tabla -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <x-tramites.tabla :tramites="$tramites" :tipo="$tipoVista" />
        </div>
    @endif
</div> 