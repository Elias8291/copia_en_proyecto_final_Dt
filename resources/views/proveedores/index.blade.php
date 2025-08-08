@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                <div>
                        <h1 class="text-2xl font-bold text-gray-800">Proveedores</h1>
                        <p class="text-base text-gray-500 mt-1">Gestión y administración de proveedores registrados</p>
                    </div>
                </div>
                @if(config('app.debug'))
                <div class="flex items-center gap-3">
                    <a href="{{ route('proveedores.debug-filtros') }}?{{ http_build_query(request()->all()) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Debug
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="border-t border-gray-100 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <form method="GET" action="{{ route('proveedores.index') }}" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" id="searchForm">
                <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                <div class="flex flex-col lg:flex-row gap-2 sm:gap-3 md:gap-4 lg:gap-6 mb-3 sm:mb-4 md:mb-5 lg:mb-6">
                <div class="flex-1">
                    <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 md:pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="q" 
                               value="{{ $query ?? '' }}"
                               placeholder="Buscar por RFC, razón social o número PV..." 
                                   class="block w-full pl-7 sm:pl-10 md:pl-12 pr-3 sm:pr-4 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                        </div>
                    </div>
                    <div class="flex gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                        <button type="submit" 
                                class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-[#9d2449] text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Buscar</span>
                        </button>
                        <button type="button" 
                                id="debugForm"
                                class="px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-blue-600 text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                            Debug
                        </button>
                        <a href="{{ route('proveedores.index') }}" 
                           class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden sm:inline">Limpiar</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-3 sm:pt-4 md:pt-5 lg:pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 mb-3 sm:mb-4 md:mb-5">
                        <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros avanzados</span>
                        </div>
                        <button type="button" 
                                id="toggleFilters" 
                                class="text-xs sm:text-sm md:text-base text-[#9d2449] hover:text-[#8a1f40] font-medium flex items-center gap-1 transition-colors self-start sm:self-auto">
                            <span id="filterText">Mostrar filtros</span>
                            <span id="filterIcon" class="text-xs sm:text-sm md:text-base transform transition-transform duration-200">▼</span>
                        </button>
                    </div>
                        
                    <div id="filtersContainer" class="hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                            <div>
                                <label for="estado_padron" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Estado del Padrón</label>
                                <select name="estado_padron" 
                                        id="estado_padron" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los estados</option>
                                    <option value="Activo" {{ request('estado_padron') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="Pendiente" {{ request('estado_padron') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Inactivo" {{ request('estado_padron') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            <div>
                                <label for="tipo_persona" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Tipo de Persona</label>
                                <select name="tipo_persona" 
                                        id="tipo_persona" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los tipos</option>
                                    <option value="Física" {{ request('tipo_persona') == 'Física' ? 'selected' : '' }}>Física</option>
                                    <option value="Moral" {{ request('tipo_persona') == 'Moral' ? 'selected' : '' }}>Moral</option>
                                </select>
                            </div>
                            <div>
                                <label for="tramites_count" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Número de Trámites</label>
                                <select name="tramites_count" 
                                        id="tramites_count" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="0" {{ request('tramites_count') == '0' ? 'selected' : '' }}>Sin trámites</option>
                                    <option value="1-5" {{ request('tramites_count') == '1-5' ? 'selected' : '' }}>1-5 trámites</option>
                                    <option value="6-10" {{ request('tramites_count') == '6-10' ? 'selected' : '' }}>6-10 trámites</option>
                                    <option value="11-20" {{ request('tramites_count') == '11-20' ? 'selected' : '' }}>11-20 trámites</option>
                                    <option value="21+" {{ request('tramites_count') == '21+' ? 'selected' : '' }}>Más de 20 trámites</option>
                                </select>
                            </div>
                            <div>
                                <label for="tiene_pv" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Tiene Número PV</label>
                                <select name="tiene_pv" 
                                        id="tiene_pv" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('tiene_pv') == '1' ? 'selected' : '' }}>Con número PV</option>
                                    <option value="0" {{ request('tiene_pv') == '0' ? 'selected' : '' }}>Sin número PV</option>
                                </select>
                            </div>
                            <div>
                                <label for="fecha_vencimiento" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Vencimiento Padrón</label>
                                <select name="fecha_vencimiento" 
                                        id="fecha_vencimiento" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="vencidos" {{ request('fecha_vencimiento') == 'vencidos' ? 'selected' : '' }}>Vencidos</option>
                                    <option value="por_vencer_30" {{ request('fecha_vencimiento') == 'por_vencer_30' ? 'selected' : '' }}>Por vencer (30 días)</option>
                                    <option value="por_vencer_60" {{ request('fecha_vencimiento') == 'por_vencer_60' ? 'selected' : '' }}>Por vencer (60 días)</option>
                                    <option value="por_vencer_90" {{ request('fecha_vencimiento') == 'por_vencer_90' ? 'selected' : '' }}>Por vencer (90 días)</option>
                                    <option value="vigentes" {{ request('fecha_vencimiento') == 'vigentes' ? 'selected' : '' }}>Vigentes</option>
                                </select>
                            </div>
                            <div>
                                <label for="fecha_registro" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Fecha de Registro</label>
                                <select name="fecha_registro" 
                                        id="fecha_registro" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="hoy" {{ request('fecha_registro') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                                    <option value="ultima_semana" {{ request('fecha_registro') == 'ultima_semana' ? 'selected' : '' }}>Última semana</option>
                                    <option value="ultimo_mes" {{ request('fecha_registro') == 'ultimo_mes' ? 'selected' : '' }}>Último mes</option>
                                    <option value="ultimos_3_meses" {{ request('fecha_registro') == 'ultimos_3_meses' ? 'selected' : '' }}>Últimos 3 meses</option>
                                    <option value="ultimo_ano" {{ request('fecha_registro') == 'ultimo_ano' ? 'selected' : '' }}>Último año</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Actividad Económica</label>
                                <div class="flex gap-2">
                                    <input type="text" 
                                           name="actividad_economica"
                                           id="actividad_economica_display" 
                                           value="{{ request('actividad_economica') }}"
                                           placeholder="Seleccionar actividad económica..."
                                           readonly
                                           class="flex-1 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md bg-gray-50 cursor-pointer">
                                    <button type="button" 
                                            id="btnFiltroActividades"
                                            class="px-3 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base bg-[#9d2449] text-white rounded-md hover:bg-[#8a1f40] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="ordenar_por" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Ordenar por</label>
                                <select name="ordenar_por" 
                                        id="ordenar_por" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="created_at_desc" {{ request('ordenar_por', 'created_at_desc') == 'created_at_desc' ? 'selected' : '' }}>Más recientes</option>
                                    <option value="created_at_asc" {{ request('ordenar_por') == 'created_at_asc' ? 'selected' : '' }}>Más antiguos</option>
                                    <option value="razon_social_asc" {{ request('ordenar_por') == 'razon_social_asc' ? 'selected' : '' }}>Razón social A-Z</option>
                                    <option value="razon_social_desc" {{ request('ordenar_por') == 'razon_social_desc' ? 'selected' : '' }}>Razón social Z-A</option>
                                    <option value="rfc_asc" {{ request('ordenar_por') == 'rfc_asc' ? 'selected' : '' }}>RFC A-Z</option>
                                    <option value="tramites_count_desc" {{ request('ordenar_por') == 'tramites_count_desc' ? 'selected' : '' }}>Más trámites</option>
                                    <option value="tramites_count_asc" {{ request('ordenar_por') == 'tramites_count_asc' ? 'selected' : '' }}>Menos trámites</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-3 sm:mt-4 md:mt-5 pt-3 sm:pt-4 border-t border-gray-100">
                <button type="submit" 
                                    class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 bg-[#9d2449] text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                                Aplicar filtros
                </button>
                    <a href="{{ route('proveedores.index') }}" 
                               class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 text-center flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Limpiar filtros
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="border-t border-gray-100 p-2 sm:p-3 md:p-4 lg:p-5 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]">{{ $proveedores->total() }}</span> 
                        {{ $proveedores->total() == 1 ? 'proveedor encontrado' : 'proveedores encontrados' }}
                        @if($proveedores->hasPages())
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                ({{ $proveedores->firstItem() }}-{{ $proveedores->lastItem() }})
                            </span>
                        @endif
                    </p>
                </div>

                <!-- Controles de visualización -->
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 md:gap-4">
                    <!-- Selector de elementos por página -->
                    <div class="flex items-center gap-2">
                        <label for="per_page" class="text-xs sm:text-sm md:text-base font-medium text-gray-700 whitespace-nowrap">
                            Mostrar:
                        </label>
                        <select name="per_page" 
                                id="per_page" 
                                class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-xs sm:text-sm md:text-base text-gray-600 whitespace-nowrap">por página</span>
                    </div>

                    <!-- Filtros activos -->
                    @if(request()->hasAny(['q', 'estado_padron', 'tipo_persona', 'tramites_count', 'tiene_pv', 'fecha_vencimiento', 'fecha_registro', 'actividad_economica', 'ordenar_por']))
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 md:gap-3">
                        <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros activos:</span>
                        
                        @if(request('q'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449] text-white">
                            Búsqueda: "{{ request('q') }}"
                            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="ml-1 sm:ml-1.5 text-white hover:text-gray-200">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('estado_padron'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                            Estado: {{ request('estado_padron') }}
                            <a href="{{ request()->fullUrlWithQuery(['estado_padron' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('tipo_persona'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                            Tipo: {{ request('tipo_persona') }}
                            <a href="{{ request()->fullUrlWithQuery(['tipo_persona' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('tramites_count'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-50 text-blue-600 border border-blue-200">
                            Trámites: {{ request('tramites_count') }}
                            <a href="{{ request()->fullUrlWithQuery(['tramites_count' => null]) }}" class="ml-1 sm:ml-1.5 text-blue-600 hover:text-blue-800">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('tiene_pv'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-50 text-green-600 border border-green-200">
                            PV: {{ request('tiene_pv') == '1' ? 'Con número' : 'Sin número' }}
                            <a href="{{ request()->fullUrlWithQuery(['tiene_pv' => null]) }}" class="ml-1 sm:ml-1.5 text-green-600 hover:text-green-800">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('fecha_vencimiento'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-orange-50 text-orange-600 border border-orange-200">
                            Vencimiento: {{ request('fecha_vencimiento') }}
                            <a href="{{ request()->fullUrlWithQuery(['fecha_vencimiento' => null]) }}" class="ml-1 sm:ml-1.5 text-orange-600 hover:text-orange-800">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('fecha_registro'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-purple-50 text-purple-600 border border-purple-200">
                            Registro: {{ request('fecha_registro') }}
                            <a href="{{ request()->fullUrlWithQuery(['fecha_registro' => null]) }}" class="ml-1 sm:ml-1.5 text-purple-600 hover:text-purple-800">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('actividad_economica'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-indigo-50 text-indigo-600 border border-indigo-200">
                            Actividad: {{ request('actividad_economica') }}
                            <a href="{{ request()->fullUrlWithQuery(['actividad_economica' => null]) }}" class="ml-1 sm:ml-1.5 text-indigo-600 hover:text-indigo-800">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('ordenar_por'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-gray-50 text-gray-600 border border-gray-200">
                            Orden: {{ request('ordenar_por') }}
                            <a href="{{ request()->fullUrlWithQuery(['ordenar_por' => null]) }}" class="ml-1 sm:ml-1.5 text-gray-600 hover:text-gray-800">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            </div>

        <div class="border-t border-gray-100 overflow-hidden hidden xl:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Proveedor</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">RFC</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Trámites</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($proveedores as $proveedor)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl text-xs sm:text-sm md:text-base" title="{{ $proveedor->razon_social ?? 'Sin razón social' }}">
                                            {{ $proveedor->razon_social ?? 'Sin razón social' }}
                                        </div>
                                        <div class="text-xs sm:text-sm text-gray-500 truncate">{{ $proveedor->tipo_persona }}</div>
                                        @if($proveedor->pv_numero)
                                            <div class="text-xs sm:text-sm text-green-600 font-medium">PV: {{ $proveedor->pv_numero }}</div>
                                        @endif
                                    </div>
                                    </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="font-mono text-sm text-gray-900">{{ $proveedor->rfc }}</span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                @php
                                    $estadoColors = [
                                        'Activo' => 'bg-green-50 text-green-600 border-green-200',
                                        'Pendiente' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                                        'Inactivo' => 'bg-red-50 text-red-600 border-red-200'
                                    ];
                                    $estadoColor = $estadoColors[$proveedor->estado_padron] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium border {{ $estadoColor }}">
                                            {{ $proveedor->estado_padron }}
                                        </span>
                                        @if($proveedor->fecha_vencimiento_padron)
                                    <div class="text-xs text-gray-500 mt-1">
                                                Vence: {{ $proveedor->fecha_vencimiento_padron->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium bg-blue-50 text-blue-600 border border-blue-200">
                                    {{ $proveedor->tramites_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    @php
                                        $ultimoTramite = $proveedor->tramites()->orderBy('created_at', 'desc')->first();
                                    @endphp
                                    @if($ultimoTramite)
                                        <a href="{{ route('tramites.historico', $ultimoTramite->id) }}" 
                                           class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-[#9d2449] hover:text-white hover:bg-[#9d2449] rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                           title="Ver último trámite">
                                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed"
                                              title="Sin trámites">
                                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                       class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-gray-600 hover:text-white hover:bg-gray-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="Editar">
                                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-8 sm:py-10 md:py-12 lg:py-16 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p class="text-xs sm:text-sm md:text-base lg:text-lg">
                                        {{ isset($query) ? 'No se encontraron proveedores' : 'No hay proveedores registrados' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4 sm:pt-5 md:pt-6 lg:pt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:hidden gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            @forelse($proveedores as $proveedor)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 sm:p-3 md:p-4 lg:p-5">
                <div class="flex items-start justify-between mb-2 sm:mb-3 md:mb-4">
                    <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-3 lg:space-x-4 min-w-0 flex-1">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                            </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base lg:text-lg block">ID: {{ $proveedor->id }}</span>
                            <p class="text-xs sm:text-sm md:text-base text-gray-500 truncate">{{ $proveedor->tipo_persona }}</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-1 sm:ml-2 md:ml-3">
                        @php
                            $estadoColors = [
                                'Activo' => 'text-green-800 bg-green-100',
                                'Pendiente' => 'text-yellow-800 bg-yellow-100',
                                'Inactivo' => 'text-red-800 bg-red-100'
                            ];
                            $estadoClass = $estadoColors[$proveedor->estado_padron] ?? 'text-gray-800 bg-gray-100';
                        @endphp
                        <span class="px-1 sm:px-1.5 md:px-2 lg:px-2.5 py-0.5 sm:py-1 md:py-1.5 text-xs sm:text-sm md:text-base font-medium rounded-full {{ $estadoClass }} whitespace-nowrap">
                            {{ $proveedor->estado_padron }}
                        </span>
                    </div>
                </div>
                <div class="space-y-1 sm:space-y-1.5 md:space-y-2 lg:space-y-3">
                    <div class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-800 truncate" title="{{ $proveedor->razon_social ?? 'Sin razón social' }}">
                        {{ $proveedor->razon_social ?? 'Sin razón social' }}
                    </div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-mono">
                        RFC: {{ $proveedor->rfc }}
                    </div>
                    @if($proveedor->pv_numero)
                        <div class="text-xs sm:text-sm md:text-base text-green-600 font-medium">
                            PV: {{ $proveedor->pv_numero }}
                        </div>
                    @endif
                    <div class="text-xs sm:text-sm md:text-base text-gray-600">
                        Trámites: {{ $proveedor->tramites_count ?? 0 }}
                    </div>
                    @if($proveedor->fecha_vencimiento_padron)
                        <div class="text-xs sm:text-sm md:text-base text-gray-600">
                            Vence: {{ $proveedor->fecha_vencimiento_padron->format('d/m/Y') }}
                        </div>
                    @endif
                </div>
                <div class="flex space-x-2 sm:space-x-3 md:space-x-4 pt-3 sm:pt-4 md:pt-5 mt-3 sm:mt-4 md:mt-5 border-t border-gray-100">
                    @php
                        $ultimoTramite = $proveedor->tramites()->orderBy('created_at', 'desc')->first();
                    @endphp
                    @if($ultimoTramite)
                        <a href="{{ route('tramites.historico', $ultimoTramite->id) }}" 
                           class="flex-1 text-center px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-medium text-[#9d2449] bg-[#9d2449]/5 border border-[#9d2449]/20 rounded-lg hover:bg-[#9d2449] hover:text-white transition-all duration-200 truncate shadow-sm">
                            <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Ver Trámite
                            </span>
                        </a>
                    @else
                        <span class="flex-1 text-center px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-medium text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed truncate shadow-sm">
                            <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Sin Trámites
                            </span>
                        </span>
                    @endif
                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                       class="flex-1 text-center px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-all duration-200 truncate shadow-sm">
                        <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                            </svg>
                            Editar
                        </span>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 md:p-8 lg:p-10 text-center">
                <div class="text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl">
                        {{ isset($query) ? 'No se encontraron proveedores' : 'No hay proveedores registrados' }}
                    </p>
                </div>
            </div>
            @endforelse
            </div>
        </div>
                @if($proveedores->hasPages())
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm md:text-base">
                    {{ $proveedores->links() }}
        </div>
    </div>
</div>
        @endif
                        </div>

@push('styles')
<style>
    /* Estilos para el modal de actividades */
    .modal-backdrop {
        backdrop-filter: blur(4px);
    }
    
    /* Animación para el modal */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    #modalActividades > div > div {
        animation: modalFadeIn 0.2s ease-out;
    }
</style>
@endpush

<!-- Modal para filtro de actividades económicas -->
<div id="modalActividades" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
            <!-- Header del modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filtrar por Actividad Económica</h3>
                <button type="button" id="cerrarModalActividades" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Contenido del modal -->
            <div class="p-6">
                <!-- Barra de búsqueda -->
                <div class="mb-6">
                    <label for="buscarActividad" class="block text-sm font-medium text-gray-700 mb-2">Buscar actividad:</label>
                    <input type="text" 
                           id="buscarActividad" 
                           placeholder="Escribe para buscar actividades..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449]">
                </div>
                
                <!-- Lista de actividades -->
                <div class="max-h-96 overflow-y-auto">
                    <div id="listaActividades" class="space-y-2">
                        <!-- Las actividades se cargarán aquí dinámicamente -->
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <span id="contadorActividades">0</span> actividades seleccionadas
                    </div>
                    <div class="flex gap-3">
                        <button type="button" 
                                id="limpiarFiltroActividades"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                            Limpiar filtro
                        </button>
                        <button type="button" 
                                id="aplicarFiltroActividades"
                                class="px-4 py-2 text-sm font-medium text-white bg-[#9d2449] border border-[#9d2449] rounded-md hover:bg-[#8a1f40] transition-colors">
                            Aplicar filtro
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('toggleFilters');
    const container = document.getElementById('filtersContainer');
    const text = document.getElementById('filterText');
    const icon = document.getElementById('filterIcon');
    const perPageSelect = document.getElementById('per_page');
    const searchForm = document.getElementById('searchForm');
    
    // Variables para el modal de actividades
    const modalActividades = document.getElementById('modalActividades');
    const btnFiltroActividades = document.getElementById('btnFiltroActividades');
    const cerrarModalActividades = document.getElementById('cerrarModalActividades');
    const buscarActividad = document.getElementById('buscarActividad');
    const listaActividades = document.getElementById('listaActividades');
    const limpiarFiltroActividades = document.getElementById('limpiarFiltroActividades');
    const aplicarFiltroActividades = document.getElementById('aplicarFiltroActividades');
    const actividadEconomicaDisplay = document.getElementById('actividad_economica_display');
    const contadorActividades = document.getElementById('contadorActividades');
    const debugForm = document.getElementById('debugForm');
    
    let actividadesSeleccionadas = [];
    let timeoutBusqueda = null;
    
    // Abrir modal
    btnFiltroActividades?.addEventListener('click', function() {
        modalActividades.classList.remove('hidden');
        buscarActividad.focus();
        cargarActividades();
        cargarActividadesSeleccionadas();
        actualizarContador();
    });
    
    // Abrir modal al hacer clic en el input
    actividadEconomicaDisplay?.addEventListener('click', function() {
        modalActividades.classList.remove('hidden');
        buscarActividad.focus();
        cargarActividades();
        cargarActividadesSeleccionadas();
        actualizarContador();
    });
    
    // Cerrar modal
    cerrarModalActividades?.addEventListener('click', function() {
        modalActividades.classList.add('hidden');
    });
    
    // Cerrar modal al hacer clic fuera
    modalActividades?.addEventListener('click', function(e) {
        if (e.target === modalActividades) {
            modalActividades.classList.add('hidden');
        }
    });
    
    // Búsqueda en tiempo real
    buscarActividad?.addEventListener('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(() => {
            cargarActividades(this.value);
        }, 300);
    });
    
    // Limpiar filtro
    limpiarFiltroActividades?.addEventListener('click', function() {
        actividadesSeleccionadas = [];
        actividadEconomicaDisplay.value = '';
        actualizarContador();
        actualizarListaActividades([]);
    });
    
    // Aplicar filtro
    aplicarFiltroActividades?.addEventListener('click', function() {
        if (actividadesSeleccionadas.length > 0) {
            const nombres = actividadesSeleccionadas.map(a => a.nombre);
            actividadEconomicaDisplay.value = nombres.join(', ');
            console.log('Aplicando filtro con actividades:', nombres);
        } else {
            actividadEconomicaDisplay.value = '';
            console.log('Limpiando filtro de actividades');
        }
        modalActividades.classList.add('hidden');
        
        // Verificar que el valor se está enviando
        console.log('Valor del input:', actividadEconomicaDisplay.value);
        console.log('Enviando formulario...');
        searchForm.submit();
    });
    
    // Botón de debug
    debugForm?.addEventListener('click', function() {
        const formData = new FormData(searchForm);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        console.log('Datos del formulario:', data);
        console.log('Actividades seleccionadas:', actividadesSeleccionadas);
        console.log('Valor del input:', actividadEconomicaDisplay.value);
    });
    
    // Función para actualizar el contador
    function actualizarContador() {
        if (contadorActividades) {
            contadorActividades.textContent = actividadesSeleccionadas.length;
        }
    }
    
    // Función para cargar actividades ya seleccionadas
    function cargarActividadesSeleccionadas() {
        const actividadesActuales = actividadEconomicaDisplay.value;
        if (actividadesActuales) {
            const nombres = actividadesActuales.split(',').map(n => n.trim()).filter(n => n);
            if (nombres.length > 0) {
                fetch(`{{ route('actividades.buscar') }}?q=${encodeURIComponent(nombres.join(' '))}`)
                    .then(response => response.json())
                    .then(data => {
                        // Filtrar solo las actividades que están en la lista actual
                        const actividadesFiltradas = data.filter(actividad => 
                            nombres.includes(actividad.nombre)
                        );
                        actividadesSeleccionadas = actividadesFiltradas;
                        actualizarContador();
                    })
                    .catch(error => {
                        console.error('Error al cargar actividades seleccionadas:', error);
                    });
            }
        }
    }
    
    // Función para cargar actividades
    function cargarActividades(busqueda = '') {
        fetch(`{{ route('actividades.buscar') }}?q=${encodeURIComponent(busqueda)}`)
            .then(response => response.json())
            .then(data => {
                actualizarListaActividades(data);
            })
            .catch(error => {
                console.error('Error al cargar actividades:', error);
                actualizarListaActividades([]);
            });
    }
    
    // Función para actualizar la lista de actividades
    function actualizarListaActividades(actividades) {
        listaActividades.innerHTML = '';
        
        if (actividades.length === 0) {
            listaActividades.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>No se encontraron actividades</p>
                </div>
            `;
            return;
        }
        
        actividades.forEach(actividad => {
            const item = document.createElement('div');
            item.className = 'p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors';
            
            // Verificar si esta actividad ya está seleccionada
            const isSelected = actividadesSeleccionadas.some(a => a.id === actividad.id);
            
            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               id="actividad_${actividad.id}" 
                               class="w-4 h-4 text-[#9d2449] border-gray-300 rounded focus:ring-[#9d2449]/20"
                               ${isSelected ? 'checked' : ''}>
                        <label for="actividad_${actividad.id}" class="text-sm text-gray-900 cursor-pointer">
                            ${actividad.nombre}
                        </label>
                    </div>
                    <svg class="w-4 h-4 ${isSelected ? 'text-[#9d2449]' : 'text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            `;
            
            // Manejar clic en el checkbox
            const checkbox = item.querySelector('input[type="checkbox"]');
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    // Agregar a la selección
                    if (!actividadesSeleccionadas.some(a => a.id === actividad.id)) {
                        actividadesSeleccionadas.push(actividad);
                    }
                    item.classList.add('bg-[#9d2449]/10', 'border-[#9d2449]');
                    item.querySelector('svg').classList.add('text-[#9d2449]');
                } else {
                    // Remover de la selección
                    actividadesSeleccionadas = actividadesSeleccionadas.filter(a => a.id !== actividad.id);
                    item.classList.remove('bg-[#9d2449]/10', 'border-[#9d2449]');
                    item.querySelector('svg').classList.remove('text-[#9d2449]');
                }
                actualizarContador();
            });
            
            // Manejar clic en el item completo
            item.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
            
            // Aplicar estilo inicial si está seleccionada
            if (isSelected) {
                item.classList.add('bg-[#9d2449]/10', 'border-[#9d2449]');
            }
            
            listaActividades.appendChild(item);
        });
    }
    
    // Los filtros siempre empiezan ocultos, sin importar si hay búsqueda
    
    toggle?.addEventListener('click', function() {
        const hidden = container?.classList.contains('hidden');
        if (hidden) {
            container?.classList.remove('hidden');
            container?.classList.remove('max-h-0');
            container?.classList.add('max-h-screen');
            if (text) text.textContent = 'Ocultar filtros';
            icon?.classList.add('rotate-180');
        } else {
            container?.classList.add('max-h-0');
            setTimeout(() => {
                container?.classList.add('hidden');
            }, 300);
            if (text) text.textContent = 'Mostrar filtros';
            icon?.classList.remove('rotate-180');
        }
    });

    perPageSelect?.addEventListener('change', function() {
        const hiddenPerPage = searchForm.querySelector('input[name="per_page"]');
        if (hiddenPerPage) {
            hiddenPerPage.value = this.value;
        }
        searchForm.submit();
    });
});
</script>
@endpush
@endsection