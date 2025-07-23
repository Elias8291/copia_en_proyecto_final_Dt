@extends('layouts.app')

@section('content')
    <div class="min-h-screen">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Notificaciones -->
            @if (session('success') || session('error'))
                <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-sm">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
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
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
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
                                        d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m-6 0h6m-6 0V7a1 1 0 00-1 1v9a2 2 0 002 2h4a2 2 0 002-2V8a1 1 0 00-1-1V7" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Gestión de Citas</h1>
                                <p class="text-sm text-gray-500">Administra las citas programadas para trámites.</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('citas.create') }}"
                                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Nueva Cita
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Búsqueda y Filtros -->
                <div class="p-4 sm:p-6 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/70">
                    <!-- Barra de búsqueda principal -->
                    <div class="mb-4 sm:mb-6">
                        <div class="relative w-full sm:max-w-2xl sm:mx-auto group">
                            <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                <div class="relative">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 group-focus-within:text-[#B4325E] transition-colors duration-200"
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
                                class="block w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 text-sm border border-gray-300 rounded-xl bg-white/80 backdrop-blur-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-300 shadow-sm hover:shadow-md hover:bg-white group-focus-within:shadow-lg"
                                placeholder="Buscar citas...">
                            <div
                                class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#B4325E]/5 to-[#7a1d37]/5 opacity-0 group-focus-within:opacity-100 transition-opacity duration-200 pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- Filtros compactos -->
                    <div
                        class="grid grid-cols-2 sm:flex sm:flex-wrap sm:items-center sm:justify-center gap-2 sm:gap-3 mb-4">
                        <!-- Tipo de Cita -->
                        <div class="relative w-full sm:w-auto">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <select id="tipo-cita-filter"
                                class="appearance-none bg-white border border-gray-300 rounded-lg pl-8 sm:pl-10 pr-6 sm:pr-8 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 hover:shadow-sm w-full sm:min-w-[140px]">
                                <option value="">Tipo Cita</option>
                                <option value="Revision">Revisión</option>
                                <option value="Cotejo">Cotejo</option>
                                <option value="Entrega">Entrega</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-1 sm:pr-2 pointer-events-none">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="relative w-full sm:w-auto">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <select id="estado-filter"
                                class="appearance-none bg-white border border-gray-300 rounded-lg pl-8 sm:pl-10 pr-6 sm:pr-8 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 hover:shadow-sm w-full sm:min-w-[120px]">
                                <option value="">Estado</option>
                                <option value="Programada">Programada</option>
                                <option value="Confirmada">Confirmada</option>
                                <option value="Cancelada">Cancelada</option>
                                <option value="Reagendada">Reagendada</option>
                                <option value="Completada">Completada</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-1 sm:pr-2 pointer-events-none">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Elementos por página -->
                        <div
                            class="flex items-center space-x-1 sm:space-x-2 col-span-2 sm:col-span-1 justify-center sm:justify-start">
                            <span class="text-xs sm:text-sm text-gray-600 font-medium whitespace-nowrap">Mostrar:</span>
                            <div class="relative">
                                <select id="per-page-selector" onchange="changePerPage(this.value)"
                                    class="appearance-none bg-white border border-gray-300 rounded-lg pl-2 sm:pl-3 pr-6 sm:pr-8 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] transition-all duration-200 hover:shadow-sm min-w-[60px] sm:min-w-[70px]">
                                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-1 sm:pr-2 pointer-events-none">
                                    <svg class="h-3 w-3 sm:h-4 sm:w-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón limpiar -->
                    <div class="flex justify-center mt-3">
                        <button id="clear-filters"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-xs sm:text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 transition-all duration-200 hover:shadow-sm">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Limpiar Filtros
                        </button>
                    </div>

                    <div class="text-center">
                        <div id="results-count"
                            class="text-sm text-gray-600 font-medium bg-white/60 backdrop-blur-sm rounded-full px-4 py-1 inline-block border border-gray-200/50">
                        </div>
                    </div>
                </div>

                <!-- Contenido de la tabla -->
                <div id="table-container" class="overflow-hidden relative">
                    @include('citas.partials.table')
                </div>

                <!-- Paginación -->
                @if ($citas->hasPages())
                    @include('citas.partials.pagination')
                @endif
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tablas/filtros.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TablaFiltros({
                entityName: 'citas',
                messages: {
                    noResults: 'No se encontraron citas',
                    results: 'cita',
                    results_plural: 'citas'
                },
                filtros: [{
                        id: 'search-filter',
                        columna: 1,
                        tipo: 'contains',
                        label: 'texto'
                    },
                    {
                        id: 'tipo-cita-filter',
                        columna: 2,
                        selector: 'span',
                        tipo: 'contains',
                        label: 'tipo de cita'
                    },
                    {
                        id: 'estado-filter',
                        columna: 3,
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
@endsection
