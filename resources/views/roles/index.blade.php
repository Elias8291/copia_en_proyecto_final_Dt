@extends('layouts.app')

@section('content')
    <div class="min-h-screen">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Notificaciones -->
            @if (session('success') || session('error'))
                <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-sm">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                            class="bg-white rounded-lg shadow-lg border-l-4 border-emerald-500 p-4">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">{{ session('success') }}</p>
                                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                            class="bg-white rounded-xl shadow-lg border-l-4 border-red-500 p-4">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Contenedor Principal -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                <!-- Encabezado -->
                <div class="p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div
                                class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Roles del Sistema</h1>
                                <p class="text-sm text-gray-500">Gestiona los roles y permisos de los usuarios.</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('roles.create') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nuevo Rol
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda y Filtros -->
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/70">
                    <!-- Barra de búsqueda principal -->
                    <div class="mb-6">
                        <div class="relative max-w-2xl mx-auto group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <div class="relative">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#B4325E] transition-colors duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <div
                                        class="absolute -inset-1 bg-gradient-to-r from-[#B4325E]/20 to-[#7a1d37]/20 rounded-full opacity-0 group-focus-within:opacity-100 transition-opacity duration-200 blur-sm">
                                    </div>
                                </div>
                            </div>
                            <input type="text" id="search-filter"
                                class="block w-full pl-12 pr-4 py-3.5 text-sm border border-gray-300 rounded-xl bg-white/80 backdrop-blur-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-300 shadow-sm hover:shadow-md hover:bg-white group-focus-within:shadow-lg"
                                placeholder="Buscar roles por nombre...">
                            <div
                                class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#B4325E]/5 to-[#7a1d37]/5 opacity-0 group-focus-within:opacity-100 transition-opacity duration-200 pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- Filtros compactos -->
                    <div class="flex flex-wrap items-center justify-center gap-3 mb-4">
                        <!-- Estado -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <select id="estado-filter"
                                class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 hover:shadow-sm min-w-[100px]">
                                <option value="">Estado</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Elementos por página -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 font-medium whitespace-nowrap">Mostrar:</span>
                            <div class="relative">
                                <select id="per-page-selector" onchange="changePerPage(this.value)"
                                    class="appearance-none bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 hover:shadow-sm min-w-[70px]">
                                    <option value="5" {{ (request('per_page', 10) == 5) ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ (request('per_page', 10) == 10) ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ (request('per_page', 10) == 25) ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ (request('per_page', 10) == 50) ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ (request('per_page', 10) == 100) ? 'selected' : '' }}>100</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Botón limpiar -->
                        <button id="clear-filters"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200 hover:shadow-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Limpiar
                        </button>
                    </div>

                    <!-- Contador de resultados -->
                    <div class="text-center">
                        <div id="results-count"
                            class="text-sm text-gray-600 font-medium bg-white/60 backdrop-blur-sm rounded-full px-4 py-1 inline-block border border-gray-200/50">
                        </div>
                    </div>
                </div>

                <!-- Contenido de la tabla -->
                <div id="table-container" class="overflow-hidden relative">
                    @include('roles.partials.table')
                </div>

                <!-- Paginación -->
                @if ($roles->hasPages())
                    @include('roles.partials.pagination')
                @endif
            </div>
        </div>
    </div>
@endsection

<script src="{{ asset('js/tablas/filtros.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TablaFiltros({
            entityName: 'roles',
            messages: {
                noResults: 'No se encontraron roles',
                results: 'rol',
                results_plural: 'roles'
            },
            filtros: [{
                    id: 'search-filter',
                    columna: 1,
                    tipo: 'contains',
                    label: 'texto'
                },
                {
                    id: 'estado-filter',
                    columna: 2,
                    selector: 'span',
                    tipo: 'contains',
                    label: 'estado'
                }
            ]
        });

        // Función para cambiar elementos por página
        window.changePerPage = function(perPage) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        };
    });
</script>
