@extends('layouts.app')

@section('content')
<div class="min-h-screen py-4 sm:py-6">
    <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Contenedor principal blanco -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <!-- Notificaciones -->
            @if (session('success') || session('error'))
                <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-sm">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="bg-white rounded-lg shadow-lg border-l-4 border-emerald-500 p-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">{{ session('success') }}</p>
                                <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="bg-white rounded-lg shadow-lg border-l-4 border-red-500 p-4">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <p class="text-sm font-medium text-gray-900">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Header -->
            <div class="p-4 sm:p-6 border-b border-gray-200/70">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-md">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Revisión de Trámites</h1>
                            <p class="text-xs sm:text-sm text-gray-500">Administra y revisa trámites de proveedores</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white rounded-lg px-3 py-2 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $tramites->total() }} Trámites</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="p-4 sm:p-6 border-b border-gray-200/70">
                <!-- Búsqueda -->
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="search-filter"
                            class="block w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E]"
                            placeholder="Buscar trámites...">
                    </div>
                </div>

                <!-- Filtros -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <!-- Tipo de Trámite -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <select id="tipo-tramite-filter"
                            class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] w-full">
                            <option value="">Tipo Trámite</option>
                            <option value="Inscripcion">Inscripción</option>
                            <option value="Renovacion">Renovación</option>
                            <option value="Actualizacion">Actualización</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <select id="estado-filter"
                            class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] w-full">
                            <option value="">Estado</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En_Revision">En Revisión</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Por_Cotejar">Por Cotejar</option>
                            <option value="Para_Correccion">Para Corrección</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Elementos por página -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 font-medium">Mostrar:</span>
                        <div class="relative">
                            <select id="per-page-selector" onchange="changePerPage(this.value)"
                                class="appearance-none bg-white border border-gray-300 rounded-lg pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30 focus:border-[#B4325E] min-w-[70px]">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botón limpiar -->
                <div class="flex justify-center">
                    <button id="clear-filters"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/30">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar Filtros
                    </button>
                </div>

                <div class="text-center mt-3">
                    <div id="results-count" class="text-sm text-gray-600 font-medium bg-white rounded-full px-3 py-1 inline-block border border-gray-200"></div>
                </div>
            </div>

            <!-- Tabla -->
            <div id="table-container">
                @include('revision.partials.table')
            </div>

            <!-- Paginación -->
            @if ($tramites->hasPages())
                <div class="mt-6">
                    @include('revision.partials.pagination')
                </div>
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('js/tablas/filtros.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TablaFiltros({
            entityName: 'tramites',
            messages: {
                noResults: 'No se encontraron trámites',
                results: 'trámite',
                results_plural: 'trámites'
            },
            filtros: [{
                    id: 'search-filter',
                    columna: 1,
                    tipo: 'contains',
                    label: 'texto'
                },
                {
                    id: 'tipo-tramite-filter',
                    columna: 2,
                    selector: 'span',
                    tipo: 'contains',
                    label: 'tipo de trámite'
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