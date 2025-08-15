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
                        <button type="button" id="toggleFilters" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-[#9d2449] text-[#9d2449] hover:bg-[#9d2449] hover:text-white font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                            <span id="filterText">Mostrar filtros</span>
                            <span id="filterIcon" class="transform transition-transform duration-300">▼</span>
                        </button>
                        <button type="button" onclick="openSectorActividadModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#9d2449] text-white font-medium rounded-lg hover:bg-[#8a1f40] transition-all duration-300 shadow-sm">Filtrar sectores/actividades</button>
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
                    <!-- Exportar -->
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" 
                       class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar Excel
                    </a>
                    
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
                                    <form id="form-delete-proveedor-{{ $proveedor->id }}" action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="showDeleteModal('Eliminar proveedor', '¿Está seguro que desea eliminar este proveedor? Esta acción no se puede deshacer.', 'form-delete-proveedor-{{ $proveedor->id }}')" class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" title="Eliminar">
                                            <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m1-3H8a1 1 0 00-1 1v2h10V5a1 1 0 00-1-1z"/>
                                            </svg>
                                        </button>
                                    </form>
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

        <div id="sectorActividadModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/50" onclick="closeSectorActividadModal()"></div>
            <div class="relative max-w-3xl mx-auto mt-16 bg-white rounded-xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-4 sm:px-6 py-3 bg-[#9d2449] text-white">
                    <h3 class="font-semibold">Filtrar por sectores y actividades</h3>
                    <button onclick="closeSectorActividadModal()" class="hover:text-gray-200">✕</button>
                </div>
                <div class="p-4 sm:p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sectores</label>
                            <div id="sectoresContainer" class="max-h-64 overflow-y-auto border rounded-md p-2 text-sm"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Actividades</label>
                            <div id="actividadesContainer" class="max-h-64 overflow-y-auto border rounded-md p-2 text-sm"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 px-4 sm:px-6 py-3 bg-gray-50">
                    <button onclick="closeSectorActividadModal()" class="px-4 py-2 rounded-md border">Cancelar</button>
                    <button onclick="applySectorActividadFilters()" class="px-4 py-2 rounded-md bg-[#9d2449] text-white">Aplicar</button>
                </div>
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


@endsection

<x-ui.modals.modal-eliminar id="modal-eliminar-proveedor"
    title="Eliminar proveedor"
    message="¿Está seguro que desea eliminar este proveedor? Esta acción no se puede deshacer."
    confirmText="Eliminar"
    cancelText="Cancelar" />

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('toggleFilters');
    const container = document.getElementById('filtersContainer');
    const text = document.getElementById('filterText');
    const icon = document.getElementById('filterIcon');
    const perPageSelect = document.getElementById('per_page');
    const searchForm = document.getElementById('searchForm');
    
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
    window.openSectorActividadModal = function() {
        document.getElementById('sectorActividadModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        lazyLoadSectorActividad();
    }
    window.closeSectorActividadModal = function() {
        document.getElementById('sectorActividadModal')?.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function lazyLoadSectorActividad() {
        const sectoresEl = document.getElementById('sectoresContainer');
        const actividadesEl = document.getElementById('actividadesContainer');
        if (!sectoresEl || !actividadesEl) return;
        if (!sectoresEl.dataset.loaded) {
            sectoresEl.innerHTML = '<div class="py-6 text-center text-gray-500">Cargando sectores...</div>';
            actividadesEl.innerHTML = '<div class="py-6 text-center text-gray-500">Cargando actividades...</div>';
            fetch('{{ route('proveedores.index') }}?format=json&catalogs=sectores,actividades', { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                .then(r => r.ok ? r.json() : Promise.reject())
                .then(data => {
                    renderList(sectoresEl, data.sectores || [], 'sector');
                    renderList(actividadesEl, data.actividades || [], 'actividad_economica');
                    sectoresEl.dataset.loaded = '1';
                })
                .catch(() => {
                    sectoresEl.innerHTML = '<div class="py-6 text-center text-red-500">Error al cargar</div>';
                    actividadesEl.innerHTML = '<div class="py-6 text-center text-red-500">Error al cargar</div>';
                });
        }
    }

    function renderList(container, items, name) {
        const selected = new Set((new URLSearchParams(window.location.search)).getAll(name + '[]'));
        container.innerHTML = items.map(item => {
            const id = item.id ?? item.value ?? item;
            const label = item.nombre ?? item.label ?? item;
            const checked = selected.has(String(id)) ? 'checked' : '';
            return `<label class="flex items-center gap-2 py-1"><input type="checkbox" name="${name}[]" form="searchForm" value="${id}" ${checked} class="rounded"> <span>${label}</span></label>`;
        }).join('') || '<div class="py-6 text-center text-gray-400">Sin datos</div>';
    }

    window.applySectorActividadFilters = function() {
        closeSectorActividadModal();
        searchForm.submit();
    }
});
</script>
@endpush
