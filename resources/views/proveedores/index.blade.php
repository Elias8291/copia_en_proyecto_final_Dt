@extends('layouts.app')

@section('content')
<div class="p-2 sm:p-3 md:p-4 lg:p-5">
    <div class="max-w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-4 sm:p-5 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Proveedores</h1>
                        <p class="text-base text-gray-500 mt-1">Catálogo de proveedores registrados</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 mb-3 sm:mb-4">
            <form method="GET" action="{{ route('proveedores.index') }}" class="p-3 sm:p-4 md:p-5" id="searchForm">
                <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                <div class="flex flex-col lg:flex-row gap-2 sm:gap-3 md:gap-4 mb-3 sm:mb-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 md:pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Buscar por razón social, RFC o ID..." 
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
                        <a href="{{ route('proveedores.index') }}" 
                           class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden sm:inline">Limpiar</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-3 sm:pt-4">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-r from-[#9d2449] to-[#8a1f40] rounded-xl shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Filtros de Búsqueda</h3>
                                <p class="text-sm text-gray-500">Refina tus resultados con criterios específicos</p>
                            </div>
                        </div>
                        <button type="button" 
                                id="toggleFilters" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#9d2449] text-[#9d2449] hover:bg-[#9d2449] hover:text-white font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                            <span id="filterText">Mostrar filtros</span>
                            <span id="filterIcon" class="transform transition-transform duration-300">▼</span>
                        </button>
                    </div>
                        
                    <div id="filtersContainer" class="hidden max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="space-y-6">
                            <!-- Filtros Básicos -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100 shadow-sm">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="p-1.5 bg-blue-500 rounded-lg">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-md font-semibold text-gray-900">Filtros Básicos</h4>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado del Padrón</label>
                                <select name="estado" 
                                        id="estado" 
                                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md"
                                                onchange="console.log('Estado cambiado a:', this.value)">
                                    <option value="">Todos los estados</option>
                                            <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>✅ Activo</option>
                                            <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>⏸️ Inactivo</option>
                                            <option value="Vencido" {{ request('estado') == 'Vencido' ? 'selected' : '' }}>❌ Vencido</option>
                                            <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>⏳ Pendiente</option>
                                            <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>🚫 Cancelado</option>
                                </select>
                            </div>

                            <div>
                                        <label for="tipo_persona" class="block text-sm font-medium text-gray-700 mb-2">Tipo de persona</label>
                                <select name="tipo_persona" 
                                        id="tipo_persona" 
                                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                                    <option value="">Todos los tipos</option>
                                            <option value="Física" {{ request('tipo_persona') == 'Física' ? 'selected' : '' }}>👤 Persona Física</option>
                                            <option value="Moral" {{ request('tipo_persona') == 'Moral' ? 'selected' : '' }}>🏢 Persona Moral</option>
                                </select>
                            </div>

                            <div>
                                        <label for="vencimiento" class="block text-sm font-medium text-gray-700 mb-2">Estado de vencimiento</label>
                                <select name="vencimiento" 
                                        id="vencimiento" 
                                                class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                                    <option value="">Todos</option>
                                            <option value="vencido" {{ request('vencimiento') == 'vencido' ? 'selected' : '' }}>🔴 Ya vencido</option>
                                            <option value="por_vencer" {{ request('vencimiento') == 'por_vencer' ? 'selected' : '' }}>🟡 Por vencer</option>
                                            <option value="sin_fecha" {{ request('vencimiento') == 'sin_fecha' ? 'selected' : '' }}>⚪ Sin fecha</option>
                                </select>
                            </div>

                            <div>
                                <label for="año" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Año de registro</label>
                                <select name="año" 
                                        id="año" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los años</option>
                                    @for($year = 2020; $year <= date('Y') + 5; $year++)
                                        <option value="{{ $year }}" {{ request('año') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="estado_geografico" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Estado geográfico</label>
                                <select name="estado_geografico" 
                                        id="estado_geografico" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los estados</option>
                                    @foreach($estados as $estado)
                                        <option value="{{ $estado->id }}" {{ request('estado_geografico') == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                        @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="sector" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Sector económico</label>
                                <div class="relative">
                                    <button type="button" 
                                            id="btnAbrirModalSectores"
                                            class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white text-left flex items-center justify-between hover:bg-gray-50">
                                        <span id="sectoresSeleccionadosTexto" class="truncate">
                                            @if(request('sector') && !empty(array_filter((array)request('sector'))))
                                                {{ count(array_filter((array)request('sector'))) }} sector(es) seleccionado(s)
                                            @else
                                                Seleccionar sectores...
                                            @endif
                                        </span>
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <!-- Input oculto para mantener los sectores seleccionados -->
                                    @if(request('sector') && !empty(array_filter((array)request('sector'))))
                                        @foreach((array)request('sector') as $sectorId)
                                            @if(!empty($sectorId))
                                                <input type="hidden" name="sector[]" value="{{ $sectorId }}">
                                            @endif
                                        @endforeach
                                    @endif
                                    <input type="hidden" id="sectoresSeleccionados" value="{{ implode(',', array_filter((array)request('sector', []))) }}">
                                </div>
                            </div>

                            <div>
                                <label for="actividad_economica" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Actividad económica</label>
                                <div class="relative">
                                    <button type="button" 
                                            id="btnAbrirModalActividades"
                                            class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white text-left flex items-center justify-between hover:bg-gray-50">
                                        <span id="actividadesSeleccionadasTexto" class="truncate">
                                            @if(request('actividad_economica') && !empty(array_filter((array)request('actividad_economica'))))
                                                {{ count(array_filter((array)request('actividad_economica'))) }} actividad(es) seleccionada(s)
                                            @else
                                                Seleccionar actividades...
                                            @endif
                                        </span>
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <!-- Input oculto para mantener las actividades seleccionadas -->
                                    @if(request('actividad_economica') && !empty(array_filter((array)request('actividad_economica'))))
                                        @foreach((array)request('actividad_economica') as $actividadId)
                                            @if(!empty($actividadId))
                                                <input type="hidden" name="actividad_economica[]" value="{{ $actividadId }}">
                                            @endif
                                        @endforeach
                                    @endif
                                    <input type="hidden" id="actividadesSeleccionadas" value="{{ implode(',', array_filter((array)request('actividad_economica', []))) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
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

        <div class="border-t border-gray-100 p-2 sm:p-3 md:p-4 mb-3 sm:mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]">{{ $todosProveedores->total() }}</span> 
                        {{ $todosProveedores->total() == 1 ? 'proveedor encontrado' : 'proveedores encontrados' }}
                        @if($todosProveedores->hasPages())
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                ({{ $todosProveedores->firstItem() }}-{{ $todosProveedores->lastItem() }})
                            </span>
                        @endif
                    </p>
                </div>

                                    <!-- Controles de visualización -->
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3">
                                    <!-- Sección de Reportes -->
                <div class="flex items-center gap-2">
                    <!-- Reporte Trimestral -->
                    <button type="button" 
                            id="btnReporteTrimestral"
                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Trimestral
                    </button>
                    
                    <!-- Exportar Tabla Filtrada -->
                    <button type="button" 
                            id="btnAbrirModalExport"
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar
                    </button>
                </div>
                    
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
                    @if(request()->hasAny(['search', 'estado', 'tipo_persona', 'vencimiento', 'año', 'estado_geografico', 'sector', 'actividad_economica']))
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 md:gap-3">
                        <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros activos:</span>
                        
                        @if(request('search'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449] text-white">
                            Búsqueda: "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 sm:ml-1.5 text-white hover:text-gray-200">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('estado'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                            Estado: {{ request('estado') }}
                            <a href="{{ request()->fullUrlWithQuery(['estado' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('tipo_persona'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            Tipo: {{ request('tipo_persona') }}
                            <a href="{{ request()->fullUrlWithQuery(['tipo_persona' => null]) }}" class="ml-1 sm:ml-1.5 text-gray-700 hover:text-gray-900">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('vencimiento'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                            Vencimiento: {{ ucfirst(str_replace('_', ' ', request('vencimiento'))) }}
                            <a href="{{ request()->fullUrlWithQuery(['vencimiento' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('año'))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            Año: {{ request('año') }}
                            <a href="{{ request()->fullUrlWithQuery(['año' => null]) }}" class="ml-1 sm:ml-1.5 text-gray-700 hover:text-gray-900">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('estado_geografico') && !empty(array_filter((array)request('estado_geografico'))))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-700 border border-green-200">
                            Estados: {{ count(array_filter((array)request('estado_geografico'))) }} seleccionado(s)
                            <a href="{{ request()->fullUrlWithQuery(['estado_geografico' => null]) }}" class="ml-1 sm:ml-1.5 text-green-700 hover:text-green-900">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('sector') && !empty(array_filter((array)request('sector'))))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-700 border border-blue-200">
                            Sectores: {{ count(array_filter((array)request('sector'))) }} seleccionado(s)
                            <a href="{{ request()->fullUrlWithQuery(['sector' => null]) }}" class="ml-1 sm:ml-1.5 text-blue-700 hover:text-blue-900">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('actividad_economica') && !empty(array_filter((array)request('actividad_economica'))))
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                            Actividades: {{ count(array_filter((array)request('actividad_economica'))) }} seleccionada(s)
                            <a href="{{ request()->fullUrlWithQuery(['actividad_economica' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
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
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Razón Social</th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">RFC</th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Tipo</th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Inicio Vigencia</th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Vencimiento</th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($todosProveedores as $proveedor)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-xs sm:text-sm md:text-base">{{ substr($proveedor->razon_social_ultimo_tramite ?? $proveedor->razon_social ?? 'P', 0, 1) }}</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl text-xs sm:text-sm md:text-base" title="{{ $proveedor->razon_social_ultimo_tramite ?? $proveedor->razon_social ?? 'N/A' }}">
                                            {{ $proveedor->razon_social_ultimo_tramite ?? $proveedor->razon_social ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs sm:text-sm text-gray-500">ID: {{ $proveedor->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <span class="text-gray-900 font-mono text-xs sm:text-sm md:text-base">{{ $proveedor->rfc ?? 'N/A' }}</span>
                            </td>
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @php
                                    $statusClass = match($proveedor->estado_padron ?? 'Pendiente') {
                                        'Activo' => 'bg-green-100 text-green-800',
                                        'Inactivo' => 'bg-red-100 text-red-800',
                                        'Vencido' => 'bg-yellow-100 text-yellow-800',
                                        'Pendiente' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium {{ $statusClass }}">
                                    {{ $proveedor->estado_padron ?? 'Pendiente' }}
                                </span>
                            </td>
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 bg-[#9d2449]/10 text-[#9d2449] rounded-full text-xs sm:text-sm font-medium">
                                    {{ $proveedor->tipo_persona ?? 'N/A' }}
                                </span>
                            </td>
                            <!-- Columna Inicio Vigencia -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @if($proveedor->fecha_vencimiento_padron)
                                    @php
                                        $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                                        $fechaInicio = $fechaVencimiento->copy()->subYear();
                                    @endphp
                                    <div class="flex items-center space-x-1.5">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-[#9d2449] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base">{{ $fechaInicio->format('d/m/Y') }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-1.5">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="text-gray-500 text-xs sm:text-sm md:text-base">No definido</span>
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Columna Vencimiento -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @if($proveedor->fecha_vencimiento_padron)
                                    @php
                                        $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                                        $hoy = \Carbon\Carbon::now();
                                        $diasRestantes = $hoy->diffInDays($fechaVencimiento, false);
                                        
                                        if ($diasRestantes < 0) {
                                            $estadoVigencia = 'vencido';
                                            $colorIcono = 'text-red-500';
                                            $colorTexto = 'text-red-600';
                                            $bgColor = 'bg-red-50';
                                            $borderColor = 'border-red-200';
                                            $estadoLabel = 'Vencido';
                                        } elseif ($diasRestantes <= 30) {
                                            $estadoVigencia = 'por_vencer';
                                            $colorIcono = 'text-amber-500';
                                            $colorTexto = 'text-amber-600';
                                            $bgColor = 'bg-amber-50';
                                            $borderColor = 'border-amber-200';
                                            $estadoLabel = 'Por vencer';
                                        } else {
                                            $estadoVigencia = 'vigente';
                                            $colorIcono = 'text-emerald-500';
                                            $colorTexto = 'text-emerald-600';
                                            $bgColor = 'bg-emerald-50';
                                            $borderColor = 'border-emerald-200';
                                            $estadoLabel = 'Vigente';
                                        }
                                    @endphp
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-1.5">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $colorIcono }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="{{ $colorTexto }} font-medium text-xs sm:text-sm md:text-base">{{ $fechaVencimiento->format('d/m/Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $bgColor }} {{ $colorTexto }} {{ $borderColor }}">
                                                {{ $estadoLabel }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-1.5">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="text-gray-500 text-xs sm:text-sm md:text-base">No definido</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <a href="{{ route('proveedores.show', $proveedor->id) }}" 
                                       class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-[#9d2449] hover:text-white hover:bg-[#9d2449] rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="Ver detalles">
                                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
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
                            <td colspan="7" class="px-2 sm:px-3 md:px-4 py-6 sm:py-8 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-sm">No hay proveedores registrados</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-3 sm:pt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:hidden gap-3 sm:gap-4">
            @forelse($todosProveedores as $proveedor)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4">
                <div class="flex items-start justify-between mb-2 sm:mb-3">
                    <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-3 lg:space-x-4 min-w-0 flex-1">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold text-xs sm:text-sm md:text-base lg:text-lg">{{ substr($proveedor->razon_social_ultimo_tramite ?? $proveedor->razon_social ?? 'P', 0, 1) }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base lg:text-lg block">ID: {{ $proveedor->id }}</span>
                            <p class="text-xs sm:text-sm md:text-base text-gray-500 truncate">{{ $proveedor->tipo_persona ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-1 sm:ml-2 md:ml-3">
                        @php
                            $statusClass = match($proveedor->estado_padron ?? 'Pendiente') {
                                'Activo' => 'text-green-800 bg-green-100',
                                'Inactivo' => 'text-red-800 bg-red-100',
                                'Vencido' => 'text-yellow-800 bg-yellow-100',
                                'Pendiente' => 'text-blue-800 bg-blue-100',
                                default => 'text-gray-800 bg-gray-100'
                            };
                        @endphp
                        <span class="px-1 sm:px-1.5 md:px-2 lg:px-2.5 py-0.5 sm:py-1 md:py-1.5 text-xs sm:text-sm md:text-base font-medium rounded-full {{ $statusClass }} whitespace-nowrap">
                            {{ $proveedor->estado_padron ?? 'Pendiente' }}
                        </span>
                    </div>
                </div>
                <div class="space-y-1 sm:space-y-1.5 md:space-y-2 lg:space-y-3">
                    <div class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-800 truncate" title="{{ $proveedor->razon_social_ultimo_tramite ?? $proveedor->razon_social ?? 'N/A' }}">
                        {{ $proveedor->razon_social_ultimo_tramite ?? $proveedor->razon_social ?? 'N/A' }}
                    </div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-mono truncate">
                        RFC: {{ $proveedor->rfc ?? 'N/A' }}
                    </div>
                    @if($proveedor->fecha_vencimiento_padron)
                        @php
                            $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                            $fechaInicio = $fechaVencimiento->copy()->subYear();
                            $hoy = \Carbon\Carbon::now();
                            $diasRestantes = $hoy->diffInDays($fechaVencimiento, false);
                            
                            if ($diasRestantes < 0) {
                                $estadoVigencia = 'vencido';
                                $colorIcono = 'text-red-500';
                                $colorTexto = 'text-red-600';
                            } elseif ($diasRestantes <= 30) {
                                $estadoVigencia = 'por_vencer';
                                $colorIcono = 'text-amber-500';
                                $colorTexto = 'text-amber-600';
                            } else {
                                $estadoVigencia = 'vigente';
                                $colorIcono = 'text-emerald-500';
                                $colorTexto = 'text-emerald-600';
                            }
                        @endphp
                        <div class="text-xs sm:text-sm md:text-base space-y-1 sm:space-y-1.5 md:space-y-2">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-[#9d2449] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="truncate text-gray-600 font-medium">Inicio: {{ $fechaInicio->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $colorIcono }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="truncate {{ $colorTexto }} font-medium">Vence: {{ $fechaVencimiento->format('d/m/Y') }}</span>
                            </div>
                            @if($estadoVigencia === 'vencido')
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                        Vencido
                                    </span>
                                </div>
                            @elseif($estadoVigencia === 'por_vencer')
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        Por vencer
                                    </span>
                                </div>
                            @else
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Vigente
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-xs sm:text-sm md:text-base text-gray-500">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="truncate">Sin fecha de vigencia</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="flex space-x-2 sm:space-x-3 pt-3 sm:pt-4 mt-3 sm:mt-4 border-t border-gray-100">
                    <a href="{{ route('proveedores.show', $proveedor->id) }}" 
                       class="flex-1 text-center px-2 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-[#9d2449] bg-[#9d2449]/5 border border-[#9d2449]/20 rounded-lg hover:bg-[#9d2449] hover:text-white transition-all duration-200 truncate shadow-sm">
                        <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.639 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.639 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Ver
                        </span>
                    </a>
                    <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                       class="flex-1 text-center px-2 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-all duration-200 truncate shadow-sm">
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
            <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 text-center">
                <div class="text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm">No hay proveedores</p>
                </div>
            </div>
            @endforelse
            </div>
        </div>
                @if($todosProveedores->hasPages())
        <div class="mt-4 sm:mt-5">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm">
                    {{ $todosProveedores->links() }}
                </div>
            </div>
        </div>
        @endif

        </div>
    </div>
</div>

<!-- Modal de Estados del País -->
<div id="modalEstados" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Estados del País</h3>
                        <p class="text-sm text-gray-600">Selecciona los estados geográficos para filtrar proveedores</p>
                    </div>
                </div>
                <button data-modal-close class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Controles Superiores -->
            <div class="py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">
                            Estados seleccionados: <span data-counter class="text-green-600 font-bold">0</span>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button data-select-all class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-100 transition-colors border border-blue-200">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Seleccionar Todos
                        </button>
                        <button data-clear-all class="px-3 py-1.5 bg-gray-50 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-100 transition-colors border border-gray-200">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpiar Todo
                        </button>
                    </div>
                </div>

                <!-- Buscador -->
                <div class="mt-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               id="buscarEstado"
                               data-search 
                               placeholder="Buscar estado..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Lista de Estados con Scroll -->
            <div class="py-4">
                <div class="max-h-96 overflow-y-auto pr-2" id="listaEstados">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($estados as $estado)
                        <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors" data-name="{{ strtolower($estado->nombre) }}">
                            <input type="checkbox" 
                                   value="{{ $estado->id }}" 
                                   class="filter-checkbox mr-3 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm text-gray-700 font-medium">{{ $estado->nombre }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div class="text-xs text-gray-500">
                    💡 Tip: Selecciona los estados y presiona "Guardar Selección". Luego usa el botón "Buscar" para aplicar los filtros
                </div>
                <div class="flex gap-3">
                    <button data-modal-cancel class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors border border-gray-300">
                        Cancelar
                    </button>
                    <button data-apply class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Selección
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Selección de Columnas para Export -->
<div id="modalExportColumnas" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Personalizar Exportación</h3>
                        <p class="text-sm text-gray-600">Selecciona las columnas que deseas incluir en el archivo Excel</p>
                    </div>
                </div>
                <button data-modal-close class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Controles Superiores -->
            <div class="py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">
                            Columnas seleccionadas: <span data-column-counter class="text-green-600 font-bold">8</span>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button data-select-all-columns class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-medium rounded-md hover:bg-blue-100 transition-colors border border-blue-200">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Seleccionar Todas
                        </button>
                        <button data-clear-all-columns class="px-3 py-1.5 bg-gray-50 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-100 transition-colors border border-gray-200">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Limpiar Todo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lista de Columnas con Scroll -->
            <div class="py-4">
                <div class="max-h-96 overflow-y-auto pr-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Información Básica -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-800 text-sm border-b border-gray-200 pb-2">📋 Información Básica</h4>
                            <div class="space-y-2">
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="id" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">ID</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="pv_numero" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">PV Número</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="razon_social" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">Razón Social</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="rfc" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">RFC</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="tipo_persona" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">Tipo Persona</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="estado_padron" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">Estado Padrón</span>
                                </label>
                            </div>
                        </div>

                        <!-- Fechas y Estados -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-800 text-sm border-b border-gray-200 pb-2">📅 Fechas y Estados</h4>
                            <div class="space-y-2">
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="fecha_alta" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">Fecha Alta</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="fecha_vencimiento" class="columna-checkbox mr-3 text-green-600 rounded" checked>
                                    <span class="text-sm text-gray-700">Fecha Vencimiento</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="estado_geografico" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Estado Geográfico</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="municipio" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Municipio</span>
                                </label>
                            </div>
                        </div>

                        <!-- Actividades y Contacto -->
                        <div class="space-y-3">
                            <h4 class="font-semibold text-gray-800 text-sm border-b border-gray-200 pb-2">🏢 Actividades y Contacto</h4>
                            <div class="space-y-2">
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="actividades" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Actividades Económicas</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="sectores" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Sectores Económicos</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="contacto" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Contacto</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="telefono" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Teléfono</span>
                                </label>
                                <label class="flex items-center p-2 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" value="correo" class="columna-checkbox mr-3 text-green-600 rounded">
                                    <span class="text-sm text-gray-700">Correo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div class="text-xs text-gray-500">
                    💡 Tip: Selecciona solo las columnas que necesites para un archivo más limpio
                </div>
                <div class="flex gap-3">
                    <button data-modal-cancel class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors border border-gray-300">
                        Cancelar
                    </button>
                    <button data-confirm-export class="px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Actividades Económicas -->
<div id="modalActividades" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Seleccionar Actividades Económicas</h3>
                <button type="button" id="cerrarModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Barra de búsqueda -->
            <div class="py-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="buscarActividad" 
                           placeholder="Buscar actividades económicas..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg">
                @if(isset($sectores) && $sectores->count() > 0)
                    @foreach($sectores as $sector)
                        @if($sector->actividades->count() > 0)
                        <div class="sector-container" data-sector-id="{{ $sector->id }}">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-medium text-gray-900 text-sm">{{ $sector->nombre }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <button type="button" 
                                                class="select-all-sector text-xs text-[#9d2449] hover:text-[#8a1f40] font-medium"
                                                data-sector-id="{{ $sector->id }}">
                                            Seleccionar todas
                                        </button>
                                        <button type="button" 
                                                class="deselect-all-sector text-xs text-gray-500 hover:text-gray-700 font-medium"
                                                data-sector-id="{{ $sector->id }}">
                                            Deseleccionar todas
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($sector->actividades as $actividad)
                                <div class="actividad-item px-4 py-2 hover:bg-gray-50" data-actividad-nombre="{{ strtolower($actividad->nombre) }}">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               class="actividad-checkbox form-checkbox h-4 w-4 text-[#9d2449] rounded border-gray-300 focus:ring-[#9d2449]/20"
                                               value="{{ $actividad->id }}"
                                               data-nombre="{{ $actividad->nombre }}"
                                               data-sector-id="{{ $sector->id }}"
                                               {{ in_array($actividad->id, (array)request('actividad_economica', [])) ? 'checked' : '' }}>
                                        <span class="ml-3 text-sm text-gray-700">{{ $actividad->nombre }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="p-8 text-center text-gray-500">
                        <p>No hay actividades económicas disponibles.</p>
                    </div>
                @endif
            </div>

            <!-- Footer del Modal -->
            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span id="contadorSeleccionadas">0</span> actividades seleccionadas
                </div>
                <div class="flex space-x-3">
                    <button type="button" 
                            id="limpiarSeleccion" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                        Limpiar selección
                    </button>
                    <button type="button" 
                            id="aplicarSeleccion" 
                            class="px-4 py-2 text-sm font-medium text-white bg-[#9d2449] border border-transparent rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200">
                        Aplicar selección
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sectores Económicos -->
<div id="modalSectores" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Seleccionar Sectores Económicos</h3>
                <button type="button" id="cerrarModalSectores" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Barra de búsqueda -->
            <div class="py-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           id="buscarSector" 
                           placeholder="Buscar sectores económicos..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-lg">
                @if(isset($sectores) && $sectores->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($sectores as $sector)
                        <div class="sector-item px-4 py-3 hover:bg-gray-50" data-sector-nombre="{{ strtolower($sector->nombre) }}">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       class="sector-checkbox form-checkbox h-4 w-4 text-[#9d2449] rounded border-gray-300 focus:ring-[#9d2449]/20"
                                       value="{{ $sector->id }}"
                                       data-nombre="{{ $sector->nombre }}"
                                       {{ in_array($sector->id, (array)request('sector', [])) ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $sector->nombre }}</div>
                                    @if($sector->actividades && $sector->actividades->count() > 0)
                                        <div class="text-xs text-gray-500">{{ $sector->actividades->count() }} actividades</div>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        <p>No hay sectores económicos disponibles.</p>
                    </div>
                @endif
            </div>

            <!-- Footer del Modal -->
            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <span id="contadorSectoresSeleccionados">0</span> sectores seleccionados
                </div>
                <div class="flex space-x-3">
                    <button type="button" 
                            id="limpiarSeleccionSectores" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200">
                        Limpiar selección
                    </button>
                    <button type="button" 
                            id="aplicarSeleccionSectores" 
                            class="px-4 py-2 text-sm font-medium text-white bg-[#9d2449] border border-transparent rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200">
                        Aplicar selección
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Reporte Trimestral -->
<div id="modalReporteTrimestral" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modal-title">
                        Reporte Trimestral de Proveedores
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-4">
                            Selecciona el período para generar el reporte de proveedores que estuvieron activos durante ese trimestre.
                        </p>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="reporteAno" class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                                <select id="reporteAno" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Seleccionar año...</option>
                                    @for($year = 2020; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div>
                                <label for="reporteTrimestre" class="block text-sm font-medium text-gray-700 mb-2">Trimestre</label>
                                <select id="reporteTrimestre" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Seleccionar trimestre...</option>
                                    <option value="1">Q1 - Enero a Marzo</option>
                                    <option value="2">Q2 - Abril a Junio</option>
                                    <option value="3">Q3 - Julio a Septiembre</option>
                                    <option value="4">Q4 - Octubre a Diciembre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        id="btnGenerarReporte"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Generar Reporte
                </button>
                <button type="button" 
                        id="btnCerrarModalReporte"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('toggleFilters');
    const container = document.getElementById('filtersContainer');
    const text = document.getElementById('filterText');
    const icon = document.getElementById('filterIcon');
    const perPageSelect = document.getElementById('per_page');
    const searchForm = document.getElementById('searchForm');
    
    // Debug del formulario
    searchForm?.addEventListener('submit', function(e) {
        const estadoSelect = document.getElementById('estado');
        const estadoGeografico = document.getElementById('estado_geografico');
        
        console.log('=== FORMULARIO ENVIADO ===');
        console.log('- Estado del padrón:', estadoSelect ? estadoSelect.value : '');
        console.log('- Estado geográfico:', estadoGeografico ? estadoGeografico.value : '');
        console.log('- Búsqueda:', this.querySelector('[name="search"]')?.value || '');
        console.log('=== FIN DEBUG FORMULARIO ===');
    });
    
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

    // ========== MODAL DE ACTIVIDADES ECONÓMICAS ==========
    const modal = document.getElementById('modalActividades');
    const btnAbrirModal = document.getElementById('btnAbrirModalActividades');
    const btnCerrarModal = document.getElementById('cerrarModal');
    const btnAplicarSeleccion = document.getElementById('aplicarSeleccion');
    const btnLimpiarSeleccion = document.getElementById('limpiarSeleccion');
    const buscarActividad = document.getElementById('buscarActividad');
    const contadorSeleccionadas = document.getElementById('contadorSeleccionadas');
    const actividadesSeleccionadasTexto = document.getElementById('actividadesSeleccionadasTexto');
    const inputActividadesSeleccionadas = document.getElementById('actividadesSeleccionadas');

    // Abrir modal
    btnAbrirModal?.addEventListener('click', function() {
        modal?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        actualizarContador();
    });

    // Cerrar modal
    function cerrarModal() {
        modal?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    btnCerrarModal?.addEventListener('click', cerrarModal);

    // Cerrar modal al hacer clic fuera
    modal?.addEventListener('click', function(e) {
        if (e.target === modal) {
            cerrarModal();
        }
    });

    // Función para actualizar el contador
    function actualizarContador() {
        const checkboxes = document.querySelectorAll('.actividad-checkbox:checked');
        const count = checkboxes.length;
        if (contadorSeleccionadas) {
            contadorSeleccionadas.textContent = count;
        }
    }

    // Función para actualizar el texto del botón principal
    function actualizarTextoBoton() {
        const checkboxes = document.querySelectorAll('.actividad-checkbox:checked');
        const count = checkboxes.length;
        
        if (actividadesSeleccionadasTexto) {
            if (count === 0) {
                actividadesSeleccionadasTexto.textContent = 'Seleccionar actividades...';
            } else {
                actividadesSeleccionadasTexto.textContent = count + ' actividad(es) seleccionada(s)';
            }
        }
    }

    // Función para aplicar la selección
    function aplicarSeleccion() {
        const checkboxes = document.querySelectorAll('.actividad-checkbox:checked');
        const valores = Array.from(checkboxes).map(cb => cb.value);
        
        // Actualizar el input oculto
        if (inputActividadesSeleccionadas) {
            inputActividadesSeleccionadas.value = valores.join(',');
        }

        // Crear inputs dinámicos para el formulario
        const form = document.getElementById('searchForm');
        
        // Remover inputs anteriores de actividades
        const oldInputs = form.querySelectorAll('input[name="actividad_economica[]"]');
        oldInputs.forEach(input => {
            if (input.id !== 'actividadesSeleccionadas') {
                input.remove();
            }
        });

        // Agregar nuevos inputs
        valores.forEach(valor => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'actividad_economica[]';
            input.value = valor;
            form.appendChild(input);
        });

        actualizarTextoBoton();
        cerrarModal();
    }

    // Event listeners para los checkboxes
    document.querySelectorAll('.actividad-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', actualizarContador);
    });

    // Aplicar selección
    btnAplicarSeleccion?.addEventListener('click', aplicarSeleccion);

    // Limpiar selección
    btnLimpiarSeleccion?.addEventListener('click', function() {
        document.querySelectorAll('.actividad-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        actualizarContador();
    });

    // Seleccionar todas las actividades de un sector
    document.querySelectorAll('.select-all-sector').forEach(btn => {
        btn.addEventListener('click', function() {
            const sectorId = this.dataset.sectorId;
            const checkboxes = document.querySelectorAll(`.actividad-checkbox[data-sector-id="${sectorId}"]`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            actualizarContador();
        });
    });

    // Deseleccionar todas las actividades de un sector
    document.querySelectorAll('.deselect-all-sector').forEach(btn => {
        btn.addEventListener('click', function() {
            const sectorId = this.dataset.sectorId;
            const checkboxes = document.querySelectorAll(`.actividad-checkbox[data-sector-id="${sectorId}"]`);
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            actualizarContador();
        });
    });

    // Función de búsqueda
    buscarActividad?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const actividadItems = document.querySelectorAll('.actividad-item');
        const sectorContainers = document.querySelectorAll('.sector-container');

        if (searchTerm === '') {
            // Mostrar todos los elementos
            actividadItems.forEach(item => {
                item.style.display = 'block';
            });
            sectorContainers.forEach(container => {
                container.style.display = 'block';
            });
        } else {
            // Filtrar elementos
            actividadItems.forEach(item => {
                const nombre = item.dataset.actividadNombre;
                if (nombre.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });

            // Mostrar/ocultar sectores según si tienen actividades visibles
            sectorContainers.forEach(container => {
                const actividadesVisibles = container.querySelectorAll('.actividad-item[style*="display: block"], .actividad-item:not([style*="display: none"])');
                const tieneActividadesVisibles = Array.from(actividadesVisibles).some(item => 
                    item.style.display !== 'none' && item.dataset.actividadNombre.includes(searchTerm)
                );
                
                if (tieneActividadesVisibles) {
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                }
            });
        }
    });

    // Inicializar estado
    actualizarTextoBoton();

    // Función para mostrar todas las actividades
    function mostrarTodasLasActividades() {
        const sectoresContainer = document.querySelectorAll('.sector-container');
        
        sectoresContainer.forEach(container => {
            container.style.display = 'block';
        });
    }

    // ========== MODAL DE SECTORES ECONÓMICOS ==========
    const modalSectores = document.getElementById('modalSectores');
    const btnAbrirModalSectores = document.getElementById('btnAbrirModalSectores');
    const btnCerrarModalSectores = document.getElementById('cerrarModalSectores');
    const btnAplicarSeleccionSectores = document.getElementById('aplicarSeleccionSectores');
    const btnLimpiarSeleccionSectores = document.getElementById('limpiarSeleccionSectores');
    const buscarSector = document.getElementById('buscarSector');
    const contadorSectoresSeleccionados = document.getElementById('contadorSectoresSeleccionados');
    const sectoresSeleccionadosTexto = document.getElementById('sectoresSeleccionadosTexto');
    const inputSectoresSeleccionados = document.getElementById('sectoresSeleccionados');

    // Abrir modal de sectores
    btnAbrirModalSectores?.addEventListener('click', function() {
        modalSectores?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        actualizarContadorSectores();
    });

    // Cerrar modal de sectores
    function cerrarModalSectores() {
        modalSectores?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    btnCerrarModalSectores?.addEventListener('click', cerrarModalSectores);

    // Cerrar modal al hacer clic fuera
    modalSectores?.addEventListener('click', function(e) {
        if (e.target === modalSectores) {
            cerrarModalSectores();
        }
    });

    // Función para actualizar el contador de sectores
    function actualizarContadorSectores() {
        const checkboxes = document.querySelectorAll('.sector-checkbox:checked');
        const count = checkboxes.length;
        if (contadorSectoresSeleccionados) {
            contadorSectoresSeleccionados.textContent = count;
        }
    }

    // Función para actualizar el texto del botón principal de sectores
    function actualizarTextoBotonSectores() {
        const checkboxes = document.querySelectorAll('.sector-checkbox:checked');
        const count = checkboxes.length;
        
        if (sectoresSeleccionadosTexto) {
            if (count === 0) {
                sectoresSeleccionadosTexto.textContent = 'Seleccionar sectores...';
            } else {
                sectoresSeleccionadosTexto.textContent = count + ' sector(es) seleccionado(s)';
            }
        }
    }

    // Función para aplicar la selección de sectores
    function aplicarSeleccionSectores() {
        const checkboxes = document.querySelectorAll('.sector-checkbox:checked');
        const valores = Array.from(checkboxes).map(cb => cb.value);
        
        // Actualizar el input oculto
        if (inputSectoresSeleccionados) {
            inputSectoresSeleccionados.value = valores.join(',');
        }

        // Crear inputs dinámicos para el formulario
        const form = document.getElementById('searchForm');
        
        // Remover inputs anteriores de sectores
        const oldInputs = form.querySelectorAll('input[name="sector[]"]');
        oldInputs.forEach(input => {
            if (input.id !== 'sectoresSeleccionados') {
                input.remove();
            }
        });

        // Agregar nuevos inputs
        valores.forEach(valor => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'sector[]';
            input.value = valor;
            form.appendChild(input);
        });

        actualizarTextoBotonSectores();
        cerrarModalSectores();
    }

    // Event listeners para los checkboxes de sectores
    document.querySelectorAll('.sector-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', actualizarContadorSectores);
    });

    // Aplicar selección de sectores
    btnAplicarSeleccionSectores?.addEventListener('click', aplicarSeleccionSectores);

    // Limpiar selección de sectores
    btnLimpiarSeleccionSectores?.addEventListener('click', function() {
        document.querySelectorAll('.sector-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        actualizarContadorSectores();
    });

    // Función de búsqueda para sectores
    buscarSector?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const sectorItems = document.querySelectorAll('.sector-item');

        sectorItems.forEach(item => {
            const nombre = item.dataset.sectorNombre;
            if (searchTerm === '' || nombre.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });



    // Inicializar estado de sectores
    actualizarTextoBotonSectores();

    // Estados geográficos simplificados - ya no se usa modal

    // Estados geográficos ahora usan select simple
    const buscarEstado = document.getElementById('buscarEstado');
    const btnAplicarSeleccionEstados = document.querySelector('#modalEstados [data-apply]');

    // Función para cerrar modal de estados
    function cerrarModalEstados() {
        const modalEstados = document.getElementById('modalEstados');
        modalEstados?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Función para actualizar el texto del botón principal de estados
    function actualizarTextoBotonEstados() {
        const checkboxes = document.querySelectorAll('.estado-checkbox:checked');
        const count = checkboxes.length;
        const estadosSeleccionadosTexto = document.getElementById('estadosSeleccionadosTexto');
        
        if (estadosSeleccionadosTexto) {
            if (count === 0) {
                estadosSeleccionadosTexto.textContent = 'Seleccionar estados...';
            } else {
                estadosSeleccionadosTexto.textContent = count + ' estado(s) seleccionado(s)';
            }
        }
    }

    // Buscador de estados
    buscarEstado?.addEventListener('input', function() {
        const termino = this.value.toLowerCase();
        const estados = document.querySelectorAll('.estado-item');
        
        estados.forEach(estado => {
            const nombre = estado.getAttribute('data-nombre');
            if (nombre.includes(termino)) {
                estado.style.display = 'flex';
            } else {
                estado.style.display = 'none';
            }
        });
    });

    // Aplicar selección de estados
    btnAplicarSeleccionEstados?.addEventListener('click', function() {
        console.log('Aplicando filtro de estados geográficos...');
        const estadosSeleccionados = [];
        const checkboxes = document.querySelectorAll('.estado-checkbox:checked');
        
        console.log('Checkboxes encontrados:', checkboxes.length);
        
        checkboxes.forEach(checkbox => {
            estadosSeleccionados.push(checkbox.value);
            console.log('Estado seleccionado:', checkbox.value, checkbox.textContent?.trim());
        });
        
        console.log('Estados a enviar:', estadosSeleccionados);

        // Limpiar inputs existentes
        const inputsExistentes = document.querySelectorAll('input[name="estado_geografico[]"]');
        inputsExistentes.forEach(input => input.remove());

        // Agregar nuevos inputs ocultos
        const form = document.getElementById('searchForm');
        estadosSeleccionados.forEach(estadoId => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'estado_geografico[]';
            input.value = estadoId;
            form.appendChild(input);
        });

        // Actualizar el campo oculto
        document.getElementById('estadosSeleccionados').value = estadosSeleccionados.join(',');

        // Actualizar texto del botón
        actualizarTextoBotonEstados();

        // Mostrar indicador de cambios pendientes si hay diferencias con la URL actual
        const estadosEnURL = new URLSearchParams(window.location.search).getAll('estado_geografico[]');
        const hayDiferencias = JSON.stringify(estadosSeleccionados.sort()) !== JSON.stringify(estadosEnURL.sort());
        const indicador = document.getElementById('estadosIndicadorCambios');
        if (indicador) {
            if (hayDiferencias) {
                indicador.classList.remove('hidden');
        } else {
                indicador.classList.add('hidden');
            }
        }
        
        // Solo cerrar modal sin enviar formulario
        console.log('Cerrando modal. Estados seleccionados guardados:', estadosSeleccionados);
        console.log('Inputs ocultos mantenidos:', form.querySelectorAll('input[name="estado_geografico[]"]').length);
        
        cerrarModalEstados();
        
        console.log('Modal cerrado. Use el botón Buscar para aplicar filtros.');
    });

    // ========== MODAL DE SELECCIÓN DE COLUMNAS PARA EXPORT ==========
    console.log('🔍 Inicializando modal de exportación...');
    const modalExport = document.getElementById('modalExportColumnas');
    const btnAbrirModalExport = document.getElementById('btnAbrirModalExport');
    const btnCerrarModalExport = document.querySelector('[data-modal-close]');
    const btnCancelarExport = document.querySelector('#modalExportColumnas [data-modal-cancel]');
    const btnConfirmarExport = document.querySelector('[data-confirm-export]');
    const btnSeleccionarTodasColumnas = document.querySelector('[data-select-all-columns]');
    const btnLimpiarTodasColumnas = document.querySelector('[data-clear-all-columns]');
    const contadorColumnasSeleccionadas = document.querySelector('[data-column-counter]');
    
    // Debug de elementos encontrados
    console.log('📋 Estado de elementos del modal de exportación:');
    console.log('  - Modal:', modalExport ? '✅ Encontrado' : '❌ No encontrado');
    console.log('  - Botón Abrir:', btnAbrirModalExport ? '✅ Encontrado' : '❌ No encontrado');
    console.log('  - Botón Cerrar:', btnCerrarModalExport ? '✅ Encontrado' : '❌ No encontrado');
    console.log('  - Botón Cancelar:', btnCancelarExport ? '✅ Encontrado' : '❌ No encontrado');
    console.log('  - Botón Confirmar:', btnConfirmarExport ? '✅ Encontrado' : '❌ No encontrado');
    console.log('  - Contador Columnas:', contadorColumnasSeleccionadas ? '✅ Encontrado' : '❌ No encontrado');



    // Cerrar modal de export
    function cerrarModalExport() {
        modalExport?.classList.add('hidden');
    }

    btnCerrarModalExport?.addEventListener('click', cerrarModalExport);
    btnCancelarExport?.addEventListener('click', cerrarModalExport);

    // Cerrar modal al hacer clic fuera
    modalExport?.addEventListener('click', function(e) {
        if (e.target === modalExport) {
            cerrarModalExport();
        }
    });

    // Función para actualizar contador de columnas seleccionadas
    function actualizarContadorColumnas() {
        const checkboxes = document.querySelectorAll('.columna-checkbox:checked');
        if (contadorColumnasSeleccionadas) {
        contadorColumnasSeleccionadas.textContent = checkboxes.length;
        }
    }

    // Seleccionar todas las columnas
    btnSeleccionarTodasColumnas?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.columna-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = true);
        actualizarContadorColumnas();
    });

    // Limpiar todas las columnas
    btnLimpiarTodasColumnas?.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.columna-checkbox');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        actualizarContadorColumnas();
    });

    // Actualizar contador cuando se cambie una checkbox
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('columna-checkbox')) {
            actualizarContadorColumnas();
        }
    });

    // Abrir modal de export
    if (btnAbrirModalExport) {
        console.log('✅ Botón de exportar encontrado');
        btnAbrirModalExport.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🔄 Clic en botón de exportar detectado...');
            
            if (modalExport) {
                console.log('✅ Modal encontrado, abriendo...');
                modalExport.classList.remove('hidden');
        actualizarContadorColumnas(); // Actualizar contador al abrir
                console.log('✅ Modal de exportación abierto exitosamente');
            } else {
                console.error('❌ Modal de exportación no encontrado');
                alert('Error: No se puede encontrar el modal de exportación. Por favor, recarga la página.');
            }
    });
    } else {
        console.error('❌ Botón de exportar no encontrado en el DOM');
    }

    // Confirmar exportación
    btnConfirmarExport?.addEventListener('click', function() {
        console.log('Iniciando exportación...');
        const columnasSeleccionadas = [];
        const checkboxes = document.querySelectorAll('.columna-checkbox:checked');
        
        console.log('Checkboxes encontrados:', checkboxes.length);
        
        if (checkboxes.length === 0) {
            alert('Debes seleccionar al menos una columna para exportar.');
            return;
        }

        checkboxes.forEach(checkbox => {
            columnasSeleccionadas.push(checkbox.value);
        });
        
        console.log('Columnas seleccionadas:', columnasSeleccionadas);

        // Obtener todos los parámetros de la URL actual
        const urlParams = new URLSearchParams(window.location.search);
        
        // Construir la URL de exportación con los mismos parámetros
        const exportUrl = new URL('{{ route("proveedores.reporte.filtrado") }}', window.location.origin);
        
        // Copiar todos los parámetros de búsqueda excepto los que vamos a reemplazar
        urlParams.forEach((value, key) => {
            if (!['actividad_economica[]', 'sector[]', 'estado_geografico[]', 'columnas'].includes(key)) {
            exportUrl.searchParams.append(key, value);
            }
        });

        // Agregar las actividades económicas seleccionadas si las hay
        const actividadesInputs = document.querySelectorAll('input[name="actividad_economica[]"]');
        actividadesInputs.forEach(input => {
            if (input.value && input.value.trim() !== '') {
                exportUrl.searchParams.append('actividad_economica[]', input.value);
            }
        });
        
        // También revisar las actividades seleccionadas del modal
        const actividadesSeleccionadas = document.getElementById('actividadesSeleccionadas');
        if (actividadesSeleccionadas && actividadesSeleccionadas.value) {
            const actividadesIds = actividadesSeleccionadas.value.split(',').filter(id => id.trim() !== '');
            actividadesIds.forEach(id => {
                exportUrl.searchParams.append('actividad_economica[]', id);
            });
        }

        // Agregar los sectores seleccionados si los hay
        const sectoresInputs = document.querySelectorAll('input[name="sector[]"]');
        sectoresInputs.forEach(input => {
            if (input.value && input.value.trim() !== '') {
                exportUrl.searchParams.append('sector[]', input.value);
            }
        });
        
        // También revisar los sectores seleccionados del modal
        const sectoresSeleccionados = document.getElementById('sectoresSeleccionados');
        if (sectoresSeleccionados && sectoresSeleccionados.value) {
            const sectoresIds = sectoresSeleccionados.value.split(',').filter(id => id.trim() !== '');
            sectoresIds.forEach(id => {
                exportUrl.searchParams.append('sector[]', id);
            });
        }

        // Agregar los estados geográficos seleccionados si los hay
        const estadosCheckboxes = document.querySelectorAll('.estado-checkbox:checked');
        estadosCheckboxes.forEach(checkbox => {
            if (checkbox.value && checkbox.value.trim() !== '') {
                exportUrl.searchParams.append('estado_geografico[]', checkbox.value);
            }
        });
        
        // También revisar el input oculto de estadosSeleccionados
        const estadosSeleccionadosInput = document.getElementById('estadosSeleccionados');
        if (estadosSeleccionadosInput && estadosSeleccionadosInput.value) {
            const estadosIds = estadosSeleccionadosInput.value.split(',').filter(id => id.trim() !== '');
            estadosIds.forEach(id => {
                exportUrl.searchParams.append('estado_geografico[]', id);
            });
        }
        
        // Agregar las columnas seleccionadas
        exportUrl.searchParams.set('columnas', columnasSeleccionadas.join(','));
        
        console.log('URL de exportación completa:', exportUrl.toString());
        console.log('Parametros de la URL:', Object.fromEntries(exportUrl.searchParams));
        
        // Cerrar modal y realizar la exportación
        cerrarModalExport();
        
        // Mostrar indicador de carga en el botón
        const originalText = btnConfirmarExport.innerHTML;
        btnConfirmarExport.innerHTML = `
            <svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Generando...
        `;
        btnConfirmarExport.disabled = true;

        try {
        // Abrir el enlace de descarga
            console.log('Abriendo ventana de descarga...');
            const ventanaDescarga = window.open(exportUrl.toString(), '_blank');
            
            if (!ventanaDescarga) {
                alert('No se pudo abrir la ventana de descarga. Por favor, permite las ventanas emergentes.');
                console.error('Ventana de descarga bloqueada');
            }
        } catch (error) {
            console.error('Error al abrir la descarga:', error);
            alert('Error al generar el archivo. Por favor, inténtalo de nuevo.');
        }

        // Restaurar el botón después de un breve delay
        setTimeout(() => {
            btnConfirmarExport.innerHTML = originalText;
            btnConfirmarExport.disabled = false;
            console.log('Botón restaurado');
        }, 3000);
    });

    // ========== REPORTE TRIMESTRAL ==========
    const modalReporteTrimestral = document.getElementById('modalReporteTrimestral');
    const btnReporteTrimestral = document.getElementById('btnReporteTrimestral');
    const btnCerrarModalReporte = document.getElementById('btnCerrarModalReporte');
    const btnGenerarReporte = document.getElementById('btnGenerarReporte');
    const reporteAno = document.getElementById('reporteAno');
    const reporteTrimestre = document.getElementById('reporteTrimestre');

    // Abrir modal de reporte trimestral
    btnReporteTrimestral?.addEventListener('click', function() {
        modalReporteTrimestral?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Cerrar modal de reporte trimestral
    function cerrarModalReporteTrimestral() {
        modalReporteTrimestral?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    btnCerrarModalReporte?.addEventListener('click', cerrarModalReporteTrimestral);

    // Cerrar modal al hacer clic fuera
    modalReporteTrimestral?.addEventListener('click', function(e) {
        if (e.target === modalReporteTrimestral) {
            cerrarModalReporteTrimestral();
        }
    });

    // Generar reporte trimestral
    btnGenerarReporte?.addEventListener('click', function() {
        const ano = reporteAno?.value;
        const trimestre = reporteTrimestre?.value;

        if (!ano || !trimestre) {
            alert('Por favor selecciona tanto el año como el trimestre.');
            return;
        }

        // Calcular fechas del trimestre
        const fechasTrimestrales = {
            1: { inicio: `${ano}-01-01`, fin: `${ano}-03-31` },
            2: { inicio: `${ano}-04-01`, fin: `${ano}-06-30` },
            3: { inicio: `${ano}-07-01`, fin: `${ano}-09-30` },
            4: { inicio: `${ano}-10-01`, fin: `${ano}-12-31` }
        };

        const periodo = fechasTrimestrales[trimestre];
        const fechaInicio = periodo.inicio;
        const fechaFin = periodo.fin;

        console.log(`Generando reporte trimestral para Q${trimestre} ${ano}`);
        console.log(`Período: ${fechaInicio} al ${fechaFin}`);

        // Mostrar indicador de carga
        const originalText = this.innerHTML;
        this.innerHTML = `
            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Generando...
        `;
        this.disabled = true;

        // Construir URL del reporte con filtros específicos para el trimestre
        const baseUrl = '{{ route("proveedores.index") }}';
        const params = new URLSearchParams();
        params.set('reporte_trimestral', '1');
        params.set('ano', ano);
        params.set('trimestre', trimestre);
        params.set('fecha_inicio', fechaInicio);
        params.set('fecha_fin', fechaFin);
        params.set('estado', 'Activo'); // Solo proveedores activos
        params.set('formato', 'excel');
        
        const reporteUrl = baseUrl + '?' + params.toString();

        try {
            // Abrir el reporte en una nueva ventana
            console.log('URL del reporte:', reporteUrl);
            const ventanaReporte = window.open(reporteUrl, '_blank');
            
            if (!ventanaReporte) {
                alert('No se pudo abrir la ventana del reporte. Por favor, permite las ventanas emergentes.');
                console.error('Ventana del reporte bloqueada');
            } else {
                // Cerrar modal después de un breve delay
                setTimeout(() => {
                    cerrarModalReporteTrimestral();
                }, 1000);
            }
        } catch (error) {
            console.error('Error al generar el reporte:', error);
            alert('Error al generar el reporte. Por favor, inténtalo de nuevo.');
        }

        // Restaurar el botón después de 2 segundos
        setTimeout(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        }, 2000);
    });

    // Mensaje final de depuración
    console.log('🎉 Script de proveedores/index.blade.php ejecutado completamente');
    console.log('📊 Resumen de elementos críticos:');
    console.log('   - Botón Exportar (btnAbrirModalExport):', btnAbrirModalExport ? 'DISPONIBLE' : 'NO DISPONIBLE');
    console.log('   - Modal Exportar (modalExport):', modalExport ? 'DISPONIBLE' : 'NO DISPONIBLE');
    console.log('   - Botón Reporte Trimestral (btnReporteTrimestral):', btnReporteTrimestral ? 'DISPONIBLE' : 'NO DISPONIBLE');
});
</script>
@endpush

