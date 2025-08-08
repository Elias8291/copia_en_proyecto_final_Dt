@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Revisión de Trámites</h1>
                        <p class="text-base text-gray-500 mt-1">Revisa y aprueba trámites pendientes</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <form method="GET" action="{{ route('revisiones.index') }}" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" id="searchForm">
                <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                
                <!-- Filtros principales -->
                <div class="flex flex-col lg:flex-row gap-2 sm:gap-3 md:gap-4 lg:gap-6 mb-3 sm:mb-4 md:mb-5 lg:mb-6">
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
                                   placeholder="Buscar por RFC, razón social, CURP o ID de trámite..." 
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
                        <a href="{{ route('revisiones.index') }}" 
                           class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span class="hidden sm:inline">Limpiar</span>
                        </a>
                    </div>
                </div>

                <!-- Filtros avanzados -->
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
                        <!-- Filtros de prioridad y ordenamiento -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-blue-800">Priorización y Ordenamiento</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                <div>
                                    <label for="prioridad" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Prioridad</label>
                                    <select name="prioridad" 
                                            id="prioridad" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="">Todas las prioridades</option>
                                        <option value="muy_alta" {{ request('prioridad') == 'muy_alta' ? 'selected' : '' }}>Muy alta (15+ días)</option>
                                        <option value="alta" {{ request('prioridad') == 'alta' ? 'selected' : '' }}>Alta (7-14 días)</option>
                                        <option value="media" {{ request('prioridad') == 'media' ? 'selected' : '' }}>Media (3-6 días)</option>
                                        <option value="baja" {{ request('prioridad') == 'baja' ? 'selected' : '' }}>Baja (0-2 días)</option>
                                        <option value="renovacion" {{ request('prioridad') == 'renovacion' ? 'selected' : '' }}>Renovaciones (prioridad especial)</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="ordenar_por" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Ordenar por</label>
                                    <select name="ordenar_por" 
                                            id="ordenar_por" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="fecha_desc" {{ request('ordenar_por', 'fecha_desc') == 'fecha_desc' ? 'selected' : '' }}>Más recientes</option>
                                        <option value="fecha_asc" {{ request('ordenar_por') == 'fecha_asc' ? 'selected' : '' }}>Más antiguos</option>
                                        <option value="prioridad" {{ request('ordenar_por') == 'prioridad' ? 'selected' : '' }}>Por prioridad</option>
                                        <option value="estado" {{ request('ordenar_por') == 'estado' ? 'selected' : '' }}>Por estado</option>
                                        <option value="tipo" {{ request('ordenar_por') == 'tipo' ? 'selected' : '' }}>Por tipo</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="antiguedad" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Antigüedad</label>
                                    <select name="antiguedad" 
                                            id="antiguedad" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="">Cualquier fecha</option>
                                        <option value="hoy" {{ request('antiguedad') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                                        <option value="semana" {{ request('antiguedad') == 'semana' ? 'selected' : '' }}>Esta semana</option>
                                        <option value="mes" {{ request('antiguedad') == 'mes' ? 'selected' : '' }}>Este mes</option>
                                        <option value="urgente" {{ request('antiguedad') == 'urgente' ? 'selected' : '' }}>Más de 7 días</option>
                                        <option value="muy_urgente" {{ request('antiguedad') == 'muy_urgente' ? 'selected' : '' }}>Más de 15 días</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="tipo_prioridad" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Tipo de Prioridad</label>
                                    <select name="tipo_prioridad" 
                                            id="tipo_prioridad" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="">Todos los tipos</option>
                                        <option value="renovacion" {{ request('tipo_prioridad') == 'renovacion' ? 'selected' : '' }}>Renovaciones</option>
                                        <option value="nuevo" {{ request('tipo_prioridad') == 'nuevo' ? 'selected' : '' }}>Nuevos registros</option>
                                        <option value="correccion" {{ request('tipo_prioridad') == 'correccion' ? 'selected' : '' }}>Para corrección</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros de fechas mejorados -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-green-800">Filtros de Fecha</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                <div>
                                    <label for="fecha_desde" class="block text-xs sm:text-sm font-medium text-green-700 mb-1">Fecha Desde</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="date" 
                                               name="fecha_desde" 
                                               id="fecha_desde" 
                                               value="{{ request('fecha_desde') }}"
                                               class="w-full pl-10 pr-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                    </div>
                                </div>

                                <div>
                                    <label for="fecha_hasta" class="block text-xs sm:text-sm font-medium text-green-700 mb-1">Fecha Hasta</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <input type="date" 
                                               name="fecha_hasta" 
                                               id="fecha_hasta" 
                                               value="{{ request('fecha_hasta') }}"
                                               class="w-full pl-10 pr-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                    </div>
                                </div>

                                <div>
                                    <label for="rango_fecha" class="block text-xs sm:text-sm font-medium text-green-700 mb-1">Rango Predefinido</label>
                                    <select name="rango_fecha" 
                                            id="rango_fecha" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                        <option value="">Seleccionar rango</option>
                                        <option value="hoy" {{ request('rango_fecha') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                                        <option value="ayer" {{ request('rango_fecha') == 'ayer' ? 'selected' : '' }}>Ayer</option>
                                        <option value="semana" {{ request('rango_fecha') == 'semana' ? 'selected' : '' }}>Última semana</option>
                                        <option value="mes" {{ request('rango_fecha') == 'mes' ? 'selected' : '' }}>Último mes</option>
                                        <option value="trimestre" {{ request('rango_fecha') == 'trimestre' ? 'selected' : '' }}>Último trimestre</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="periodo_especifico" class="block text-xs sm:text-sm font-medium text-green-700 mb-1">Período Específico</label>
                                    <select name="periodo_especifico" 
                                            id="periodo_especifico" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                        <option value="">Seleccionar período</option>
                                        <option value="lunes_viernes" {{ request('periodo_especifico') == 'lunes_viernes' ? 'selected' : '' }}>Lunes a Viernes</option>
                                        <option value="fin_semana" {{ request('periodo_especifico') == 'fin_semana' ? 'selected' : '' }}>Fin de semana</option>
                                        <option value="primer_semana" {{ request('periodo_especifico') == 'primer_semana' ? 'selected' : '' }}>Primera semana del mes</option>
                                        <option value="ultima_semana" {{ request('periodo_especifico') == 'ultima_semana' ? 'selected' : '' }}>Última semana del mes</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros tradicionales -->
                        <div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-gray-800">Filtros Generales</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                <div>
                                    <label for="estado" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Estado del Trámite</label>
                                    <select name="estado" 
                                            id="estado" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                                        <option value="">Todos los estados</option>
                                        @foreach(\App\Enums\TramiteStatus::toArray() as $value => $label)
                                            <option value="{{ $value }}" {{ request('estado') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="tipo_tramite" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tipo de Trámite</label>
                                    <select name="tipo_tramite" 
                                            id="tipo_tramite" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                                        <option value="">Todos los tipos</option>
                                        <option value="Inscripcion" {{ request('tipo_tramite') == 'Inscripcion' ? 'selected' : '' }}>Inscripción</option>
                                        <option value="Renovacion" {{ request('tipo_tramite') == 'Renovacion' ? 'selected' : '' }}>Renovación</option>
                                        <option value="Actualizacion" {{ request('tipo_tramite') == 'Actualizacion' ? 'selected' : '' }}>Actualización</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="asignado_a" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Asignado a</label>
                                    <select name="asignado_a" 
                                            id="asignado_a" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                                        <option value="">Todos los revisores</option>
                                        <option value="mi_usuario" {{ request('asignado_a') == 'mi_usuario' ? 'selected' : '' }}>Asignados a mí</option>
                                        <option value="sin_asignar" {{ request('asignado_a') == 'sin_asignar' ? 'selected' : '' }}>Sin asignar</option>
                                        <option value="otros" {{ request('asignado_a') == 'otros' ? 'selected' : '' }}>Asignados a otros</option>
                                        <option value="todos_asignados" {{ request('asignado_a') == 'todos_asignados' ? 'selected' : '' }}>Todos los asignados</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="estado_revision" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Estado de Revisión</label>
                                    <select name="estado_revision" 
                                            id="estado_revision" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                                        <option value="">Todos los estados</option>
                                        <option value="pendiente" {{ request('estado_revision') == 'pendiente' ? 'selected' : '' }}>Pendientes de revisión</option>
                                        <option value="en_proceso" {{ request('estado_revision') == 'en_proceso' ? 'selected' : '' }}>En proceso de revisión</option>
                                        <option value="finalizada" {{ request('estado_revision') == 'finalizada' ? 'selected' : '' }}>Revisión finalizada</option>
                                        <option value="sin_revision" {{ request('estado_revision') == 'sin_revision' ? 'selected' : '' }}>Sin revisión iniciada</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" 
                                               name="mis_tramites" 
                                               id="mis_tramites" 
                                               value="1" 
                                               {{ request('mis_tramites') ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-[#9d2449] focus:ring-[#9d2449]">
                                        <span class="text-xs sm:text-sm font-medium text-gray-700">Solo mis trámites asignados</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-100">
                            <button type="submit" 
                                    class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-gradient-to-r from-[#9d2449] to-[#8a1f40] text-white text-xs sm:text-sm font-medium rounded-md hover:from-[#8a1f40] hover:to-[#7a1a37] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Aplicar filtros
                            </button>
                            <a href="{{ route('revisiones.index') }}" 
                               class="w-full sm:w-auto px-4 md:px-6 py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 text-center flex items-center justify-center gap-2">
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
            <!-- Estadísticas de prioridad -->
            @php
                $totalTramites = $tramites->total();
                $urgentes = 0;
                $muyUrgentes = 0;
                $renovaciones = 0;
                
                foreach($tramites as $tramite) {
                    $diasTranscurridos = $tramite->created_at->diffInDays(now());
                    if ($diasTranscurridos >= 15) {
                        $muyUrgentes++;
                    } elseif ($diasTranscurridos >= 7) {
                        $urgentes++;
                    }
                    if ($tramite->tipo_tramite === 'Renovacion') {
                        $renovaciones++;
                    }
                }
            @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-red-800">Muy Urgentes</p>
                            <p class="text-lg font-bold text-red-900">{{ $muyUrgentes }}</p>
                        </div>
                        <div class="w-8 h-8 bg-red-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-orange-800">Urgentes</p>
                            <p class="text-lg font-bold text-orange-900">{{ $urgentes }}</p>
                        </div>
                        <div class="w-8 h-8 bg-orange-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-purple-800">Renovaciones</p>
                            <p class="text-lg font-bold text-purple-900">{{ $renovaciones }}</p>
                        </div>
                        <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-blue-800">Total</p>
                            <p class="text-lg font-bold text-blue-900">{{ $totalTramites }}</p>
                        </div>
                        <div class="w-8 h-8 bg-blue-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]">{{ $tramites->total() }}</span> 
                        {{ $tramites->total() == 1 ? 'trámite pendiente' : 'trámites pendientes' }}
                        @if($tramites->hasPages())
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                ({{ $tramites->firstItem() }}-{{ $tramites->lastItem() }})
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
                </div>
            </div>

            <!-- Filtros activos -->
            @if(request()->hasAny(['search', 'estado', 'tipo_tramite', 'fecha_desde', 'fecha_hasta', 'mis_tramites', 'prioridad', 'ordenar_por', 'antiguedad', 'tipo_prioridad', 'rango_fecha', 'periodo_especifico', 'asignado_a']))
            <div class="mt-3 sm:mt-4 md:mt-5 pt-3 sm:pt-4 border-t border-gray-100">
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

                    @if(request('prioridad'))
                    @php
                        $prioridadLabels = [
                            'muy_alta' => 'Muy alta (15+ días)',
                            'alta' => 'Alta (7-14 días)',
                            'media' => 'Media (3-6 días)',
                            'baja' => 'Baja (0-2 días)',
                            'renovacion' => 'Renovaciones (prioridad especial)'
                        ];
                        $prioridadLabel = $prioridadLabels[request('prioridad')] ?? request('prioridad');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        {{ $prioridadLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['prioridad' => null]) }}" class="ml-1 sm:ml-1.5 text-blue-800 hover:text-blue-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('ordenar_por'))
                    @php
                        $ordenarLabels = [
                            'fecha_desc' => 'Más recientes',
                            'fecha_asc' => 'Más antiguos',
                            'prioridad' => 'Por prioridad',
                            'estado' => 'Por estado',
                            'tipo' => 'Por tipo'
                        ];
                        $ordenarLabel = $ordenarLabels[request('ordenar_por')] ?? request('ordenar_por');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        Orden: {{ $ordenarLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['ordenar_por' => null]) }}" class="ml-1 sm:ml-1.5 text-blue-800 hover:text-blue-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('antiguedad'))
                    @php
                        $antiguedadLabels = [
                            'hoy' => 'Hoy',
                            'semana' => 'Esta semana',
                            'mes' => 'Este mes',
                            'urgente' => 'Más de 7 días',
                            'muy_urgente' => 'Más de 15 días'
                        ];
                        $antiguedadLabel = $antiguedadLabels[request('antiguedad')] ?? request('antiguedad');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-orange-100 text-orange-800 border border-orange-200">
                        {{ $antiguedadLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['antiguedad' => null]) }}" class="ml-1 sm:ml-1.5 text-orange-800 hover:text-orange-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('rango_fecha'))
                    @php
                        $rangoLabels = [
                            'hoy' => 'Hoy',
                            'ayer' => 'Ayer',
                            'semana' => 'Última semana',
                            'mes' => 'Último mes',
                            'trimestre' => 'Último trimestre'
                        ];
                        $rangoLabel = $rangoLabels[request('rango_fecha')] ?? request('rango_fecha');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        Rango: {{ $rangoLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['rango_fecha' => null]) }}" class="ml-1 sm:ml-1.5 text-green-800 hover:text-green-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('estado'))
                    @php
                        $estadoLabel = \App\Enums\TramiteStatus::toArray()[request('estado')] ?? request('estado');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                        Estado: {{ $estadoLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['estado' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('tipo_tramite'))
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                        Tipo: {{ ucfirst(request('tipo_tramite')) }}
                        <a href="{{ request()->fullUrlWithQuery(['tipo_tramite' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('fecha_desde') || request('fecha_hasta'))
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        Fecha: {{ request('fecha_desde', 'Inicio') }} - {{ request('fecha_hasta', 'Fin') }}
                        <a href="{{ request()->fullUrlWithQuery(['fecha_desde' => null, 'fecha_hasta' => null]) }}" class="ml-1 sm:ml-1.5 text-green-800 hover:text-green-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('mis_tramites'))
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                        Mis trámites asignados
                        <a href="{{ request()->fullUrlWithQuery(['mis_tramites' => null]) }}" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('estado_revision'))
                    @php
                        $estadoRevisionLabels = [
                            'pendiente' => 'Pendientes de revisión',
                            'en_proceso' => 'En proceso de revisión',
                            'finalizada' => 'Revisión finalizada',
                            'sin_revision' => 'Sin revisión iniciada'
                        ];
                        $estadoRevisionLabel = $estadoRevisionLabels[request('estado_revision')] ?? request('estado_revision');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                        Estado revisión: {{ $estadoRevisionLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['estado_revision' => null]) }}" class="ml-1 sm:ml-1.5 text-indigo-800 hover:text-indigo-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif

                    @if(request('asignado_a'))
                    @php
                        $asignadoLabels = [
                            'mi_usuario' => 'Asignados a mí',
                            'sin_asignar' => 'Sin asignar',
                            'otros' => 'Asignados a otros',
                            'todos_asignados' => 'Todos los asignados'
                        ];
                        $asignadoLabel = $asignadoLabels[request('asignado_a')] ?? request('asignado_a');
                    @endphp
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                        Asignación: {{ $asignadoLabel }}
                        <a href="{{ request()->fullUrlWithQuery(['asignado_a' => null]) }}" class="ml-1 sm:ml-1.5 text-purple-800 hover:text-purple-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Tabla de trámites para desktop -->
        <div class="border-t border-gray-100 overflow-hidden hidden xl:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Trámite</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Proveedor</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Tipo</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tramites as $tramite)
                        @php
                            // Calcular prioridad basada en antigüedad y tipo
                            $diasTranscurridos = $tramite->created_at->diffInDays(now());
                            $prioridad = 'baja';
                            $prioridadColor = 'bg-gray-100 text-gray-600';
                            $prioridadIcon = 'clock';
                            
                            if ($diasTranscurridos >= 15) {
                                $prioridad = 'muy_alta';
                                $prioridadColor = 'bg-red-100 text-red-800';
                                $prioridadIcon = 'exclamation-triangle';
                            } elseif ($diasTranscurridos >= 7) {
                                $prioridad = 'alta';
                                $prioridadColor = 'bg-orange-100 text-orange-800';
                                $prioridadIcon = 'clock';
                            } elseif ($diasTranscurridos >= 3) {
                                $prioridad = 'media';
                                $prioridadColor = 'bg-yellow-100 text-yellow-800';
                                $prioridadIcon = 'clock';
                            }
                            
                            // Prioridad especial para renovaciones
                            if ($tramite->tipo_tramite === 'Renovacion') {
                                $prioridad = 'alta';
                                $prioridadColor = 'bg-purple-100 text-purple-800';
                                $prioridadIcon = 'refresh-cw';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $prioridad === 'muy_alta' ? 'bg-red-50/50' : ($prioridad === 'alta' ? 'bg-orange-50/50' : '') }}">
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <div class="relative">
                                        <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-semibold text-xs sm:text-sm md:text-base">#{{ $tramite->id }}</span>
                                        </div>
                                        @if($prioridad !== 'baja')
                                        <div class="absolute -top-1 -right-1 w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 {{ $prioridadColor }} rounded-full flex items-center justify-center">
                                            <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 md:w-3 md:h-3" fill="currentColor" viewBox="0 0 20 20">
                                                @if($prioridadIcon === 'exclamation-triangle')
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                @elseif($prioridadIcon === 'refresh-cw')
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                @else
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                @endif
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2">
                                            <div class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl text-xs sm:text-sm md:text-base">
                                                Trámite #{{ $tramite->id }}
                                            </div>
                                            @if($prioridad !== 'baja')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadColor }}">
                                                @if($prioridad === 'muy_alta')
                                                    Muy urgente
                                                @elseif($prioridad === 'alta')
                                                    Urgente
                                                @else
                                                    Media
                                                @endif
                                            </span>
                                            @endif
                                        </div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $tramite->created_at->format('d/m/Y H:i') }}</div>
                                        @if($diasTranscurridos > 0)
                                        <div class="text-xs text-gray-400">
                                            {{ $diasTranscurridos }} {{ $diasTranscurridos == 1 ? 'día' : 'días' }} de antigüedad
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="min-w-0">
                                    <div class="text-gray-900 text-xs sm:text-sm md:text-base font-medium">
                                        {{ $tramite->getRazonSocial() ?? 'N/A' }}
                                    </div>
                                    <div class="text-gray-500 text-xs sm:text-sm">
                                        RFC: {{ $tramite->proveedor->rfc ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 bg-[#9d2449]/10 text-[#9d2449] rounded-full text-xs sm:text-sm font-medium">
                                    {{ $tramite->tipo_tramite }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                @php
                                    // Usar el enum para obtener el color y label del estado
                                    $estadoEnum = \App\Enums\TramiteStatus::tryFrom($tramite->status);
                                    $estadoColor = $estadoEnum ? $estadoEnum->color() : 'bg-gray-100 text-gray-800';
                                    $estadoLabel = $estadoEnum ? $estadoEnum->label() : $tramite->status;
                                @endphp
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium {{ $estadoColor }}">
                                    {{ $estadoLabel }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="text-sm text-gray-900">
                                    {{ $tramite->created_at->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <a href="{{ route('revisiones.seleccionar-tipo', $tramite->id) }}" 
                                       class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-[#9d2449] hover:text-white hover:bg-[#9d2449] rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="Iniciar revisión">
                                        <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-8 sm:py-10 md:py-12 lg:py-16 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-xs sm:text-sm md:text-base lg:text-lg">No hay trámites pendientes de revisión</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista móvil de trámites -->
        <div class="border-t border-gray-100 pt-4 sm:pt-5 md:pt-6 lg:pt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:hidden gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            @forelse($tramites as $tramite)
            @php
                // Calcular prioridad basada en antigüedad y tipo
                $diasTranscurridos = $tramite->created_at->diffInDays(now());
                $prioridad = 'baja';
                $prioridadColor = 'bg-gray-100 text-gray-600';
                $prioridadIcon = 'clock';
                
                if ($diasTranscurridos >= 15) {
                    $prioridad = 'muy_alta';
                    $prioridadColor = 'bg-red-100 text-red-800';
                    $prioridadIcon = 'exclamation-triangle';
                } elseif ($diasTranscurridos >= 7) {
                    $prioridad = 'alta';
                    $prioridadColor = 'bg-orange-100 text-orange-800';
                    $prioridadIcon = 'clock';
                } elseif ($diasTranscurridos >= 3) {
                    $prioridad = 'media';
                    $prioridadColor = 'bg-yellow-100 text-yellow-800';
                    $prioridadIcon = 'clock';
                }
                
                // Prioridad especial para renovaciones
                if ($tramite->tipo_tramite === 'Renovacion') {
                    $prioridad = 'alta';
                    $prioridadColor = 'bg-purple-100 text-purple-800';
                    $prioridadIcon = 'refresh-cw';
                }
            @endphp
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 sm:p-3 md:p-4 lg:p-5 {{ $prioridad === 'muy_alta' ? 'border-l-4 border-l-red-500' : ($prioridad === 'alta' ? 'border-l-4 border-l-orange-500' : '') }}">
                <div class="flex items-start justify-between mb-2 sm:mb-3 md:mb-4">
                    <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-3 lg:space-x-4 min-w-0 flex-1">
                        <div class="relative">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-semibold text-xs sm:text-sm md:text-base lg:text-lg">#{{ $tramite->id }}</span>
                            </div>
                            @if($prioridad !== 'baja')
                            <div class="absolute -top-1 -right-1 w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-4 md:h-4 lg:w-5 lg:h-5 {{ $prioridadColor }} rounded-full flex items-center justify-center">
                                <svg class="w-1.5 h-1.5 sm:w-2 sm:h-2 md:w-2.5 md:h-2.5 lg:w-3 lg:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    @if($prioridadIcon === 'exclamation-triangle')
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    @elseif($prioridadIcon === 'refresh-cw')
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                    @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    @endif
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1 sm:gap-2">
                                <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base lg:text-lg block">Trámite #{{ $tramite->id }}</span>
                                @if($prioridad !== 'baja')
                                <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium {{ $prioridadColor }}">
                                    @if($prioridad === 'muy_alta')
                                        Muy urgente
                                    @elseif($prioridad === 'alta')
                                        Urgente
                                    @else
                                        Media
                                    @endif
                                </span>
                                @endif
                            </div>
                            <p class="text-xs sm:text-sm md:text-base text-gray-500 truncate">{{ $tramite->tipo_tramite }}</p>
                            @if($diasTranscurridos > 0)
                            <p class="text-xs text-gray-400">{{ $diasTranscurridos }} {{ $diasTranscurridos == 1 ? 'día' : 'días' }} de antigüedad</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-1 sm:ml-2 md:ml-3">
                        @php
                            // Usar el enum para obtener el color y label del estado
                            $estadoEnum = \App\Enums\TramiteStatus::tryFrom($tramite->status);
                            $estadoColor = $estadoEnum ? $estadoEnum->color() : 'bg-gray-100 text-gray-800';
                            $estadoLabel = $estadoEnum ? $estadoEnum->label() : $tramite->status;
                        @endphp
                        <span class="px-1 sm:px-1.5 md:px-2 lg:px-2.5 py-0.5 sm:py-1 md:py-1.5 text-xs sm:text-sm md:text-base font-medium rounded-full {{ $estadoColor }} whitespace-nowrap">
                            {{ $estadoLabel }}
                        </span>
                    </div>
                </div>
                <div class="space-y-1 sm:space-y-1.5 md:space-y-2 lg:space-y-3">
                    <div class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-800 truncate">
                        {{ $tramite->getRazonSocial() ?? 'N/A' }}
                    </div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 truncate">
                        RFC: {{ $tramite->proveedor->rfc ?? 'N/A' }}
                    </div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600">
                        Fecha: {{ $tramite->created_at->format('d/m/Y') }}
                    </div>
                </div>
                <div class="flex space-x-2 sm:space-x-3 md:space-x-4 pt-3 sm:pt-4 md:pt-5 mt-3 sm:mt-4 md:mt-5 border-t border-gray-100">
                    <a href="{{ route('revisiones.seleccionar-tipo', $tramite->id) }}" 
                       class="flex-1 text-center px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base font-medium text-[#9d2449] bg-[#9d2449]/5 border border-[#9d2449]/20 rounded-lg hover:bg-[#9d2449] hover:text-white transition-all duration-200 truncate shadow-sm">
                        <span class="flex items-center justify-center gap-1.5 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Iniciar
                        </span>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 md:p-8 lg:p-10 text-center">
                <div class="text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl">No hay trámites pendientes</p>
                </div>
            </div>
            @endforelse
            </div>
        </div>

        <!-- Paginación -->
        @if($tramites->hasPages())
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm md:text-base">
                    {{ $tramites->links() }}
                </div>
            </div>
        </div>
        @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const toggle = document.getElementById('toggleFilters');
        const container = document.getElementById('filtersContainer');
        const text = document.getElementById('filterText');
        const icon = document.getElementById('filterIcon');
        const perPageSelect = document.getElementById('per_page');
        const searchForm = document.getElementById('searchForm');
        
        // Elementos de filtros de fecha
        const rangoFechaSelect = document.getElementById('rango_fecha');
        const fechaDesdeInput = document.getElementById('fecha_desde');
        const fechaHastaInput = document.getElementById('fecha_hasta');
        const periodoEspecificoSelect = document.getElementById('periodo_especifico');
        
        if (toggle && container) {
            toggle.addEventListener('click', function() {
                const hidden = container.classList.contains('hidden');
                if (hidden) {
                    container.classList.remove('hidden');
                    container.classList.remove('max-h-0');
                    container.classList.add('max-h-screen');
                    if (text) text.textContent = 'Ocultar filtros';
                    if (icon) icon.classList.add('rotate-180');
                } else {
                    container.classList.add('max-h-0');
                    setTimeout(() => {
                        container.classList.add('hidden');
                    }, 300);
                    if (text) text.textContent = 'Mostrar filtros';
                    if (icon) icon.classList.remove('rotate-180');
                }
            });
        }

        if (perPageSelect && searchForm) {
            perPageSelect.addEventListener('change', function() {
                const hiddenPerPage = searchForm.querySelector('input[name="per_page"]');
                if (hiddenPerPage) {
                    hiddenPerPage.value = this.value;
                }
                searchForm.submit();
            });
        }

        // Función para formatear fecha como YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Función para obtener fecha de hace X días
        function getDateDaysAgo(days) {
            const date = new Date();
            date.setDate(date.getDate() - days);
            return formatDate(date);
        }

        // Función para obtener fecha de hace X semanas
        function getDateWeeksAgo(weeks) {
            const date = new Date();
            date.setDate(date.getDate() - (weeks * 7));
            return formatDate(date);
        }

        // Función para obtener fecha de hace X meses
        function getDateMonthsAgo(months) {
            const date = new Date();
            date.setMonth(date.getMonth() - months);
            return formatDate(date);
        }

        // Manejar cambios en el selector de rango de fecha
        if (rangoFechaSelect) {
            rangoFechaSelect.addEventListener('change', function() {
                const today = new Date();
                const todayStr = formatDate(today);
                
                switch(this.value) {
                    case 'hoy':
                        fechaDesdeInput.value = todayStr;
                        fechaHastaInput.value = todayStr;
                        break;
                    case 'ayer':
                        const yesterday = getDateDaysAgo(1);
                        fechaDesdeInput.value = yesterday;
                        fechaHastaInput.value = yesterday;
                        break;
                    case 'semana':
                        fechaDesdeInput.value = getDateWeeksAgo(1);
                        fechaHastaInput.value = todayStr;
                        break;
                    case 'mes':
                        fechaDesdeInput.value = getDateMonthsAgo(1);
                        fechaHastaInput.value = todayStr;
                        break;
                    case 'trimestre':
                        fechaDesdeInput.value = getDateMonthsAgo(3);
                        fechaHastaInput.value = todayStr;
                        break;
                    default:
                        // Limpiar fechas si no hay selección
                        fechaDesdeInput.value = '';
                        fechaHastaInput.value = '';
                }
            });
        }

        // Manejar cambios en el selector de período específico
        if (periodoEspecificoSelect) {
            periodoEspecificoSelect.addEventListener('change', function() {
                const today = new Date();
                const todayStr = formatDate(today);
                
                switch(this.value) {
                    case 'lunes_viernes':
                        // Obtener el lunes de esta semana
                        const monday = new Date(today);
                        const dayOfWeek = today.getDay();
                        const daysToMonday = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
                        monday.setDate(today.getDate() - daysToMonday);
                        
                        // Obtener el viernes de esta semana
                        const friday = new Date(monday);
                        friday.setDate(monday.getDate() + 4);
                        
                        fechaDesdeInput.value = formatDate(monday);
                        fechaHastaInput.value = formatDate(friday);
                        break;
                    case 'fin_semana':
                        // Obtener el sábado de esta semana
                        const saturday = new Date(today);
                        const daysToSaturday = dayOfWeek === 0 ? 0 : 7 - dayOfWeek;
                        saturday.setDate(today.getDate() + daysToSaturday);
                        
                        // Obtener el domingo de esta semana
                        const sunday = new Date(saturday);
                        sunday.setDate(saturday.getDate() + 1);
                        
                        fechaDesdeInput.value = formatDate(saturday);
                        fechaHastaInput.value = formatDate(sunday);
                        break;
                    case 'primer_semana':
                        // Primera semana del mes actual
                        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                        const firstWeekEnd = new Date(firstDay);
                        firstWeekEnd.setDate(firstDay.getDate() + 6);
                        
                        fechaDesdeInput.value = formatDate(firstDay);
                        fechaHastaInput.value = formatDate(firstWeekEnd);
                        break;
                    case 'ultima_semana':
                        // Última semana del mes actual
                        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        const lastWeekStart = new Date(lastDay);
                        lastWeekStart.setDate(lastDay.getDate() - 6);
                        
                        fechaDesdeInput.value = formatDate(lastWeekStart);
                        fechaHastaInput.value = formatDate(lastDay);
                        break;
                    default:
                        // Limpiar fechas si no hay selección
                        fechaDesdeInput.value = '';
                        fechaHastaInput.value = '';
                }
            });
        }

        // Validar que fecha_hasta no sea menor que fecha_desde
        if (fechaDesdeInput && fechaHastaInput) {
            fechaDesdeInput.addEventListener('change', function() {
                if (fechaHastaInput.value && this.value > fechaHastaInput.value) {
                    fechaHastaInput.value = this.value;
                }
            });

            fechaHastaInput.addEventListener('change', function() {
                if (fechaDesdeInput.value && this.value < fechaDesdeInput.value) {
                    fechaDesdeInput.value = this.value;
                }
            });
        }

        // Auto-submit cuando se cambian ciertos filtros
        const autoSubmitFilters = ['prioridad', 'ordenar_por', 'antiguedad', 'tipo_prioridad', 'asignado_a', 'estado_revision', 'estado', 'tipo_tramite'];
        autoSubmitFilters.forEach(filterName => {
            const filterElement = document.getElementById(filterName);
            if (filterElement) {
                filterElement.addEventListener('change', function() {
                    setTimeout(() => {
                        searchForm.submit();
                    }, 100);
                });
            }
        });

        // Manejar checkbox de mis trámites
        const misTramitesCheckbox = document.getElementById('mis_tramites');
        if (misTramitesCheckbox) {
            misTramitesCheckbox.addEventListener('change', function() {
                setTimeout(() => {
                    searchForm.submit();
                }, 100);
            });
        }

    } catch (error) {
        console.warn('Error initializing revisiones page JavaScript:', error);
    }
});
</script>
@endpush

<!-- Modal de éxito -->
<x-ui.modals.modal-exito 
    id="modal-revision-exito"
    :title="session('success_title', '¡Operación Exitosa!')"
    :message="session('success_message', 'La operación se realizó correctamente.')"
    :redirectUrl="session('success_redirect', route('revisiones.index'))"
/>

@endsection 