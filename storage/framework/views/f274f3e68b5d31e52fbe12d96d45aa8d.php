<?php $__env->startSection('content'); ?>
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
            <form method="GET" action="<?php echo e(route('revisiones.index')); ?>" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" id="searchForm">
                <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 15)); ?>">
                
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
                                   value="<?php echo e(request('search')); ?>"
                                   placeholder="Buscar por RFC, razón social o CURP..." 
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
                        <a href="<?php echo e(route('revisiones.index')); ?>" 
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
                                        <option value="alta" <?php echo e(request('prioridad') == 'alta' ? 'selected' : ''); ?>>Alta prioridad</option>
                                        <option value="media" <?php echo e(request('prioridad') == 'media' ? 'selected' : ''); ?>>Prioridad media</option>
                                        <option value="baja" <?php echo e(request('prioridad') == 'baja' ? 'selected' : ''); ?>>Prioridad baja</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="ordenar_por" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Ordenar por</label>
                                    <select name="ordenar_por" 
                                            id="ordenar_por" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="fecha_desc" <?php echo e(request('ordenar_por', 'fecha_desc') == 'fecha_desc' ? 'selected' : ''); ?>>Más recientes</option>
                                        <option value="fecha_asc" <?php echo e(request('ordenar_por') == 'fecha_asc' ? 'selected' : ''); ?>>Más antiguos</option>
                                        <option value="prioridad" <?php echo e(request('ordenar_por') == 'prioridad' ? 'selected' : ''); ?>>Por prioridad</option>
                                        <option value="estado" <?php echo e(request('ordenar_por') == 'estado' ? 'selected' : ''); ?>>Por estado</option>
                                        <option value="tipo" <?php echo e(request('ordenar_por') == 'tipo' ? 'selected' : ''); ?>>Por tipo</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="antiguedad" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Antigüedad</label>
                                    <select name="antiguedad" 
                                            id="antiguedad" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="">Cualquier fecha</option>
                                        <option value="hoy" <?php echo e(request('antiguedad') == 'hoy' ? 'selected' : ''); ?>>Hoy</option>
                                        <option value="semana" <?php echo e(request('antiguedad') == 'semana' ? 'selected' : ''); ?>>Esta semana</option>
                                        <option value="mes" <?php echo e(request('antiguedad') == 'mes' ? 'selected' : ''); ?>>Este mes</option>
                                        <option value="urgente" <?php echo e(request('antiguedad') == 'urgente' ? 'selected' : ''); ?>>Más de 7 días</option>
                                        <option value="muy_urgente" <?php echo e(request('antiguedad') == 'muy_urgente' ? 'selected' : ''); ?>>Más de 15 días</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="tipo_prioridad" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1">Tipo de Prioridad</label>
                                    <select name="tipo_prioridad" 
                                            id="tipo_prioridad" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 bg-white">
                                        <option value="">Todos los tipos</option>
                                        <option value="renovacion" <?php echo e(request('tipo_prioridad') == 'renovacion' ? 'selected' : ''); ?>>Renovaciones</option>
                                        <option value="nuevo" <?php echo e(request('tipo_prioridad') == 'nuevo' ? 'selected' : ''); ?>>Nuevos registros</option>
                                        <option value="correccion" <?php echo e(request('tipo_prioridad') == 'correccion' ? 'selected' : ''); ?>>Para corrección</option>
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
                                               value="<?php echo e(request('fecha_desde')); ?>"
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
                                               value="<?php echo e(request('fecha_hasta')); ?>"
                                               class="w-full pl-10 pr-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                    </div>
                                </div>

                                <div>
                                    <label for="rango_fecha" class="block text-xs sm:text-sm font-medium text-green-700 mb-1">Rango Predefinido</label>
                                    <select name="rango_fecha" 
                                            id="rango_fecha" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                        <option value="">Seleccionar rango</option>
                                        <option value="hoy" <?php echo e(request('rango_fecha') == 'hoy' ? 'selected' : ''); ?>>Hoy</option>
                                        <option value="ayer" <?php echo e(request('rango_fecha') == 'ayer' ? 'selected' : ''); ?>>Ayer</option>
                                        <option value="semana" <?php echo e(request('rango_fecha') == 'semana' ? 'selected' : ''); ?>>Última semana</option>
                                        <option value="mes" <?php echo e(request('rango_fecha') == 'mes' ? 'selected' : ''); ?>>Último mes</option>
                                        <option value="trimestre" <?php echo e(request('rango_fecha') == 'trimestre' ? 'selected' : ''); ?>>Último trimestre</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="periodo_especifico" class="block text-xs sm:text-sm font-medium text-green-700 mb-1">Período Específico</label>
                                    <select name="periodo_especifico" 
                                            id="periodo_especifico" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 bg-white">
                                        <option value="">Seleccionar período</option>
                                        <option value="lunes_viernes" <?php echo e(request('periodo_especifico') == 'lunes_viernes' ? 'selected' : ''); ?>>Lunes a Viernes</option>
                                        <option value="fin_semana" <?php echo e(request('periodo_especifico') == 'fin_semana' ? 'selected' : ''); ?>>Fin de semana</option>
                                        <option value="primer_semana" <?php echo e(request('periodo_especifico') == 'primer_semana' ? 'selected' : ''); ?>>Primera semana del mes</option>
                                        <option value="ultima_semana" <?php echo e(request('periodo_especifico') == 'ultima_semana' ? 'selected' : ''); ?>>Última semana del mes</option>
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
                                        <option value="Pendiente" <?php echo e(request('estado') == 'Pendiente' ? 'selected' : ''); ?>>Pendiente</option>
                                        <option value="Revision_Digital" <?php echo e(request('estado') == 'Revision_Digital' ? 'selected' : ''); ?>>Revisión Digital</option>
                                        <option value="Revision_Presencial" <?php echo e(request('estado') == 'Revision_Presencial' ? 'selected' : ''); ?>>Revisión Presencial</option>
                                        <option value="Revision_Domiciliaria" <?php echo e(request('estado') == 'Revision_Domiciliaria' ? 'selected' : ''); ?>>Revisión Domiciliaria</option>
                                        <option value="Para_Correccion" <?php echo e(request('estado') == 'Para_Correccion' ? 'selected' : ''); ?>>Para Corrección</option>
                                        <option value="Aprobado" <?php echo e(request('estado') == 'Aprobado' ? 'selected' : ''); ?>>Aprobado</option>
                                        <option value="Rechazado" <?php echo e(request('estado') == 'Rechazado' ? 'selected' : ''); ?>>Rechazado</option>
                                        <option value="Cancelado" <?php echo e(request('estado') == 'Cancelado' ? 'selected' : ''); ?>>Cancelado</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="tipo_tramite" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Tipo de Trámite</label>
                                    <select name="tipo_tramite" 
                                            id="tipo_tramite" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                                        <option value="">Todos los tipos</option>
                                        <option value="Inscripcion" <?php echo e(request('tipo_tramite') == 'Inscripcion' ? 'selected' : ''); ?>>Inscripción</option>
                                        <option value="Renovacion" <?php echo e(request('tipo_tramite') == 'Renovacion' ? 'selected' : ''); ?>>Renovación</option>
                                        <option value="Actualizacion" <?php echo e(request('tipo_tramite') == 'Actualizacion' ? 'selected' : ''); ?>>Actualización</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="asignado_a" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Asignado a</label>
                                    <select name="asignado_a" 
                                            id="asignado_a" 
                                            class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-white">
                                        <option value="">Todos los revisores</option>
                                        <option value="mi_usuario" <?php echo e(request('asignado_a') == 'mi_usuario' ? 'selected' : ''); ?>>Asignados a mí</option>
                                        <option value="sin_asignar" <?php echo e(request('asignado_a') == 'sin_asignar' ? 'selected' : ''); ?>>Sin asignar</option>
                                        <option value="otros" <?php echo e(request('asignado_a') == 'otros' ? 'selected' : ''); ?>>Asignados a otros</option>
                                    </select>
                                </div>

                                <div class="flex items-center justify-center">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" 
                                               name="mis_tramites" 
                                               id="mis_tramites" 
                                               value="1" 
                                               <?php echo e(request('mis_tramites') ? 'checked' : ''); ?>

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
                            <a href="<?php echo e(route('revisiones.index')); ?>" 
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
            <?php
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
            ?>
            
            <!-- Estadísticas compactas -->
            <div class="flex flex-wrap items-center gap-4 mb-4 p-3 bg-slate-50 rounded-lg border border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-slate-600">Total:</span>
                    <span class="text-sm font-bold text-slate-900 px-2 py-0.5 bg-slate-100 rounded"><?php echo e($totalTramites); ?></span>
                        </div>
                <div class="w-px h-4 bg-slate-300"></div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-blue-600">Renovaciones:</span>
                    <span class="text-sm font-bold text-blue-700 px-2 py-0.5 bg-blue-50 rounded border border-blue-200"><?php echo e($renovaciones); ?></span>
                        </div>
                <div class="w-px h-4 bg-slate-300"></div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-orange-600">Urgentes:</span>
                    <span class="text-sm font-bold text-orange-700 px-2 py-0.5 bg-orange-50 rounded border border-orange-200"><?php echo e($urgentes); ?></span>
                    </div>
                <div class="w-px h-4 bg-slate-300"></div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-red-600">Muy Urgentes:</span>
                    <span class="text-sm font-bold text-red-700 px-2 py-0.5 bg-red-50 rounded border border-red-200"><?php echo e($muyUrgentes); ?></span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]"><?php echo e($tramites->total()); ?></span> 
                        <?php echo e($tramites->total() == 1 ? 'trámite pendiente' : 'trámites pendientes'); ?>

                        <?php if($tramites->hasPages()): ?>
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                (<?php echo e($tramites->firstItem()); ?>-<?php echo e($tramites->lastItem()); ?>)
                            </span>
                        <?php endif; ?>
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
                            <option value="10" <?php echo e(request('per_page', 15) == 10 ? 'selected' : ''); ?>>10</option>
                            <option value="15" <?php echo e(request('per_page', 15) == 15 ? 'selected' : ''); ?>>15</option>
                            <option value="25" <?php echo e(request('per_page', 15) == 25 ? 'selected' : ''); ?>>25</option>
                            <option value="50" <?php echo e(request('per_page', 15) == 50 ? 'selected' : ''); ?>>50</option>
                            <option value="100" <?php echo e(request('per_page', 15) == 100 ? 'selected' : ''); ?>>100</option>
                        </select>
                        <span class="text-xs sm:text-sm md:text-base text-gray-600 whitespace-nowrap">por página</span>
                    </div>
                </div>
            </div>

            <!-- Filtros activos -->
            <?php if(request()->hasAny(['search', 'estado', 'tipo_tramite', 'fecha_desde', 'fecha_hasta', 'mis_tramites', 'prioridad', 'ordenar_por', 'antiguedad', 'tipo_prioridad', 'rango_fecha', 'periodo_especifico', 'asignado_a'])): ?>
            <div class="mt-3 sm:mt-4 md:mt-5 pt-3 sm:pt-4 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 md:gap-3">
                    <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros activos:</span>
                    
                    <?php if(request('search')): ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449] text-white">
                        Búsqueda: "<?php echo e(request('search')); ?>"
                        <a href="<?php echo e(request()->fullUrlWithQuery(['search' => null])); ?>" class="ml-1 sm:ml-1.5 text-white hover:text-gray-200">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('prioridad')): ?>
                    <?php
                        $prioridadLabels = [
                            'alta' => 'Alta prioridad',
                            'media' => 'Prioridad media',
                            'baja' => 'Prioridad baja'
                        ];
                        $prioridadLabel = $prioridadLabels[request('prioridad')] ?? request('prioridad');
                    ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        <?php echo e($prioridadLabel); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['prioridad' => null])); ?>" class="ml-1 sm:ml-1.5 text-blue-800 hover:text-blue-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('ordenar_por')): ?>
                    <?php
                        $ordenarLabels = [
                            'fecha_desc' => 'Más recientes',
                            'fecha_asc' => 'Más antiguos',
                            'prioridad' => 'Por prioridad',
                            'estado' => 'Por estado',
                            'tipo' => 'Por tipo'
                        ];
                        $ordenarLabel = $ordenarLabels[request('ordenar_por')] ?? request('ordenar_por');
                    ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        Orden: <?php echo e($ordenarLabel); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['ordenar_por' => null])); ?>" class="ml-1 sm:ml-1.5 text-blue-800 hover:text-blue-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('antiguedad')): ?>
                    <?php
                        $antiguedadLabels = [
                            'hoy' => 'Hoy',
                            'semana' => 'Esta semana',
                            'mes' => 'Este mes',
                            'urgente' => 'Más de 7 días',
                            'muy_urgente' => 'Más de 15 días'
                        ];
                        $antiguedadLabel = $antiguedadLabels[request('antiguedad')] ?? request('antiguedad');
                    ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-orange-100 text-orange-800 border border-orange-200">
                        <?php echo e($antiguedadLabel); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['antiguedad' => null])); ?>" class="ml-1 sm:ml-1.5 text-orange-800 hover:text-orange-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('rango_fecha')): ?>
                    <?php
                        $rangoLabels = [
                            'hoy' => 'Hoy',
                            'ayer' => 'Ayer',
                            'semana' => 'Última semana',
                            'mes' => 'Último mes',
                            'trimestre' => 'Último trimestre'
                        ];
                        $rangoLabel = $rangoLabels[request('rango_fecha')] ?? request('rango_fecha');
                    ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        Rango: <?php echo e($rangoLabel); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['rango_fecha' => null])); ?>" class="ml-1 sm:ml-1.5 text-green-800 hover:text-green-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('estado')): ?>
                    <?php
                        $estadoLabels = [
                            'Pendiente' => 'Pendiente',
                            'Revision_Digital' => 'Revisión Digital',
                            'Revision_Presencial' => 'Revisión Presencial',
                            'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                            'Para_Correccion' => 'Para Corrección',
                            'Aprobado' => 'Aprobado',
                            'Rechazado' => 'Rechazado',
                            'Cancelado' => 'Cancelado'
                        ];
                        $estadoLabel = $estadoLabels[request('estado')] ?? request('estado');
                    ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                        Estado: <?php echo e($estadoLabel); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['estado' => null])); ?>" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('tipo_tramite')): ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                        Tipo: <?php echo e(ucfirst(request('tipo_tramite'))); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['tipo_tramite' => null])); ?>" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('fecha_desde') || request('fecha_hasta')): ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                        Fecha: <?php echo e(request('fecha_desde', 'Inicio')); ?> - <?php echo e(request('fecha_hasta', 'Fin')); ?>

                        <a href="<?php echo e(request()->fullUrlWithQuery(['fecha_desde' => null, 'fecha_hasta' => null])); ?>" class="ml-1 sm:ml-1.5 text-green-800 hover:text-green-600">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>

                    <?php if(request('mis_tramites')): ?>
                    <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449]/10 text-[#9d2449] border border-[#9d2449]/20">
                        Mis trámites asignados
                        <a href="<?php echo e(request()->fullUrlWithQuery(['mis_tramites' => null])); ?>" class="ml-1 sm:ml-1.5 text-[#9d2449] hover:text-[#8a1f40]">
                            <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
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
                        <?php $__empty_1 = true; $__currentLoopData = $tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tramite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-medium text-gray-900 text-sm">
                                                Trámite #<?php echo e($tramite->id); ?>

                                            </div>
                                        <div class="text-xs text-gray-500"><?php echo e($tramite->created_at->format('d/m/Y H:i')); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="min-w-0">
                                    <div class="text-gray-900 text-xs sm:text-sm md:text-base font-medium">
                                        <?php echo e($tramite->getRazonSocial() ?? 'N/A'); ?>

                                    </div>
                                    <div class="text-gray-500 text-xs sm:text-sm">
                                        RFC: <?php echo e($tramite->proveedor->rfc ?? 'N/A'); ?>

                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <?php
                                    $tipoConfig = [
                                        'Inscripcion' => ['class' => 'bg-green-50 text-green-700 border-green-200'],
                                        'Renovacion' => ['class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                        'Actualizacion' => ['class' => 'bg-purple-50 text-purple-700 border-purple-200']
                                    ];
                                    $tipoClass = $tipoConfig[$tramite->tipo_tramite]['class'] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                ?>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded border <?php echo e($tipoClass); ?>">
                                    <?php echo e($tramite->tipo_tramite); ?>

                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <?php
                                    $estadoConfig = [
                                        'Pendiente' => ['label' => 'Pendiente', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                        'Revision_Digital' => ['label' => 'Revisión Digital', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                        'Revision_Presencial' => ['label' => 'Revisión Presencial', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                        'Revision_Domiciliaria' => ['label' => 'Revisión Domiciliaria', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                                        'Para_Correccion' => ['label' => 'Para Corrección', 'class' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                        'Aprobado' => ['label' => 'Aprobado', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                        'Rechazado' => ['label' => 'Rechazado', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                                        'Cancelado' => ['label' => 'Cancelado', 'class' => 'bg-gray-50 text-gray-700 border-gray-200']
                                    ];
                                    $estado = $estadoConfig[$tramite->status] ?? ['label' => $tramite->status, 'class' => 'bg-gray-50 text-gray-700 border-gray-200'];
                                ?>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-medium rounded border <?php echo e($estado['class']); ?>">
                                    <?php echo e($estado['label']); ?>

                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="text-sm text-gray-900">
                                    <?php echo e($tramite->created_at->format('d/m/Y')); ?>

                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                    <a href="<?php echo e(route('revisiones.seleccionar-tipo', $tramite->id)); ?>" 
                                   class="inline-flex items-center px-3 py-1 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded hover:bg-gray-100 transition-colors duration-200"
                                       title="Iniciar revisión">
                                    Revisar
                                    </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista móvil de trámites -->
        <div class="border-t border-gray-100 pt-4 sm:pt-5 md:pt-6 lg:pt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:hidden gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tramite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 md:p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3 min-w-0 flex-1">
                        <div class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="font-medium text-gray-900 text-sm">
                                Trámite #<?php echo e($tramite->id); ?>

                            </div>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($tramite->tipo_tramite); ?></p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <?php
                            $estadoConfig = [
                                'Pendiente' => ['label' => 'Pendiente', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                'Revision_Digital' => ['label' => 'Revisión Digital', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                'Revision_Presencial' => ['label' => 'Revisión Presencial', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                'Revision_Domiciliaria' => ['label' => 'Revisión Domiciliaria', 'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200'],
                                'Para_Correccion' => ['label' => 'Para Corrección', 'class' => 'bg-orange-50 text-orange-700 border-orange-200'],
                                'Aprobado' => ['label' => 'Aprobado', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                'Rechazado' => ['label' => 'Rechazado', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                                'Cancelado' => ['label' => 'Cancelado', 'class' => 'bg-gray-50 text-gray-700 border-gray-200']
                            ];
                            $estado = $estadoConfig[$tramite->status] ?? ['label' => $tramite->status, 'class' => 'bg-gray-50 text-gray-700 border-gray-200'];
                        ?>
                        <span class="px-2 py-1 text-xs font-medium rounded border whitespace-nowrap <?php echo e($estado['class']); ?>">
                            <?php echo e($estado['label']); ?>

                        </span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="text-sm font-medium text-gray-800 truncate">
                        <?php echo e($tramite->getRazonSocial() ?? 'N/A'); ?>

                    </div>
                    <div class="text-xs text-gray-600 truncate">
                        RFC: <?php echo e($tramite->proveedor->rfc ?? 'N/A'); ?>

                    </div>
                    <div class="text-xs text-gray-600">
                        Fecha: <?php echo e($tramite->created_at->format('d/m/Y')); ?>

                    </div>
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100">
                    <a href="<?php echo e(route('revisiones.seleccionar-tipo', $tramite->id)); ?>" 
                       class="w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded hover:bg-gray-100 transition-colors duration-200">
                        Iniciar revisión
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 md:p-8 lg:p-10 text-center">
                <div class="text-gray-500">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 lg:w-24 lg:h-24 mx-auto mb-4 sm:mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl">No hay trámites pendientes</p>
                </div>
            </div>
            <?php endif; ?>
            </div>
        </div>

        <!-- Paginación -->
        <?php if($tramites->hasPages()): ?>
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm md:text-base">
                    <?php echo e($tramites->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
        const autoSubmitFilters = ['prioridad', 'ordenar_por', 'antiguedad', 'tipo_prioridad', 'asignado_a'];
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

    } catch (error) {
        console.warn('Error initializing revisiones page JavaScript:', error);
    }
});
</script>
<?php $__env->stopPush(); ?>

<!-- Modal de éxito -->
<?php if (isset($component)) { $__componentOriginal765bfa0583680d927bcd8d764b0d499a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal765bfa0583680d927bcd8d764b0d499a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.modals.modal-exito','data' => ['id' => 'modal-revision-exito','title' => session('success_title', '¡Operación Exitosa!'),'message' => session('success_message', 'La operación se realizó correctamente.'),'redirectUrl' => session('success_redirect', route('revisiones.index'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.modals.modal-exito'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'modal-revision-exito','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('success_title', '¡Operación Exitosa!')),'message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('success_message', 'La operación se realizó correctamente.')),'redirectUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('success_redirect', route('revisiones.index')))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal765bfa0583680d927bcd8d764b0d499a)): ?>
<?php $attributes = $__attributesOriginal765bfa0583680d927bcd8d764b0d499a; ?>
<?php unset($__attributesOriginal765bfa0583680d927bcd8d764b0d499a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal765bfa0583680d927bcd8d764b0d499a)): ?>
<?php $component = $__componentOriginal765bfa0583680d927bcd8d764b0d499a; ?>
<?php unset($__componentOriginal765bfa0583680d927bcd8d764b0d499a); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/revisiones/index.blade.php ENDPATH**/ ?>