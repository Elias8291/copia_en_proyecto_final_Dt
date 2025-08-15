@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-full mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
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

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-green-800">¡Éxito!</h4>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-red-800">Error</h4>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="border-t border-gray-100 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <form method="GET" action="{{ route('revisiones.index') }}" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" id="searchForm">
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
                                   name="search" 
                                   value="{{ request('search') }}"
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
                        <a href="{{ route('revisiones.index') }}" 
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
                            <!-- Filtro de Asignación - NUEVO -->
                            <div>
                                <label for="filtro_revisor" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mis Revisiones
                                </label>
                                <select name="filtro_revisor" 
                                        id="filtro_revisor" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 bg-gradient-to-r from-[#9d2449]/5 to-transparent">
                                    <option value="todos" {{ request('filtro_revisor', 'todos') == 'todos' ? 'selected' : '' }}>Todos los trámites</option>
                                    <option value="mis_pendientes" {{ request('filtro_revisor') == 'mis_pendientes' ? 'selected' : '' }}>🔄 Mis pendientes</option>
                                    <option value="mis_completadas" {{ request('filtro_revisor') == 'mis_completadas' ? 'selected' : '' }}>✅ Mis completadas</option>
                                    <option value="sin_asignar" {{ request('filtro_revisor') == 'sin_asignar' ? 'selected' : '' }}>❌ Sin asignar</option>
                                </select>
                            </div>

                            <div>
                                <label for="estado" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Estado</label>
                                <select name="estado" 
                                        id="estado" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los estados</option>
                                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Revision_Digital" {{ request('estado') == 'Revision_Digital' ? 'selected' : '' }}>Revisión Digital</option>
                                    <option value="Revision_Presencial" {{ request('estado') == 'Revision_Presencial' ? 'selected' : '' }}>Revisión Presencial</option>
                                    <option value="Revision_Domiciliaria" {{ request('estado') == 'Revision_Domiciliaria' ? 'selected' : '' }}>Revisión Domiciliaria</option>
                                    <option value="Para_Correccion" {{ request('estado') == 'Para_Correccion' ? 'selected' : '' }}>Para Corrección</option>
                                    <option value="Aprobado" {{ request('estado') == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                                    <option value="Rechazado" {{ request('estado') == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                                </select>
                            </div>

                            <div>
                                <label for="tipo_tramite" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Tipo de Trámite</label>
                                <select name="tipo_tramite" 
                                        id="tipo_tramite" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los tipos</option>
                                    <option value="Inscripcion" {{ request('tipo_tramite') == 'Inscripcion' ? 'selected' : '' }}>Inscripción</option>
                                    <option value="Renovacion" {{ request('tipo_tramite') == 'Renovacion' ? 'selected' : '' }}>Renovación</option>
                                    <option value="Actualizacion" {{ request('tipo_tramite') == 'Actualizacion' ? 'selected' : '' }}>Actualización</option>
                                </select>
                            </div>

                            <div>
                                <label for="ordenar_por" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Ordenar por</label>
                                <select name="ordenar_por" 
                                        id="ordenar_por" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="fecha_desc" {{ request('ordenar_por', 'fecha_desc') == 'fecha_desc' ? 'selected' : '' }}>Más recientes</option>
                                    <option value="fecha_asc" {{ request('ordenar_por') == 'fecha_asc' ? 'selected' : '' }}>Más antiguos</option>
                                    <option value="prioridad" {{ request('ordenar_por') == 'prioridad' ? 'selected' : '' }}>Por prioridad</option>
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
                            <a href="{{ route('revisiones.index') }}" 
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
                        <span class="font-medium text-[#9d2449]">{{ $tramites->total() }}</span> 
                        {{ $tramites->total() == 1 ? 'trámite encontrado' : 'trámites encontrados' }}
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

                    <!-- Filtros activos -->
                    @if(request()->hasAny(['search', 'estado', 'tipo_tramite', 'ordenar_por', 'filtro_revisor']))
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

                        @if(request('filtro_revisor') && request('filtro_revisor') != 'todos')
                        @php
                            $filtroRevisorLabels = [
                                'mis_pendientes' => '🔄 Mis pendientes',
                                'mis_completadas' => '✅ Mis completadas',
                                'sin_asignar' => '❌ Sin asignar',
                            ];
                            $filtroRevisorLabel = $filtroRevisorLabels[request('filtro_revisor')] ?? request('filtro_revisor');
                        @endphp
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-[#9d2449] text-white">
                            {{ $filtroRevisorLabel }}
                            <a href="{{ request()->fullUrlWithQuery(['filtro_revisor' => null]) }}" class="ml-1 sm:ml-1.5 text-white hover:text-gray-200">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('estado'))
                        @php
                            $estadoLabels = [
                                'Pendiente' => 'Pendiente',
                                'Revision_Digital' => 'Revisión Digital',
                                'Revision_Presencial' => 'Revisión Presencial',
                                'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                            ];
                            $estadoLabel = $estadoLabels[request('estado')] ?? request('estado');
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
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            Tipo: {{ ucfirst(request('tipo_tramite')) }}
                            <a href="{{ request()->fullUrlWithQuery(['tipo_tramite' => null]) }}" class="ml-1 sm:ml-1.5 text-gray-700 hover:text-gray-900">
                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </span>
                        @endif

                        @if(request('ordenar_por') && request('ordenar_por') != 'fecha_desc')
                        @php
                            $ordenarLabels = [
                                'fecha_asc' => 'Más antiguos',
                                'prioridad' => 'Por prioridad',
                            ];
                            $ordenarLabel = $ordenarLabels[request('ordenar_por')] ?? request('ordenar_por');
                        @endphp
                        <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            Orden: {{ $ordenarLabel }}
                            <a href="{{ request()->fullUrlWithQuery(['ordenar_por' => null]) }}" class="ml-1 sm:ml-1.5 text-gray-700 hover:text-gray-900">
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
                        // Calcular prioridad basada en antigüedad
                        $diasTranscurridos = $tramite->created_at->diffInDays(now());
                        $prioridad = 'normal';
                        $prioridadColor = 'bg-gray-100 text-gray-600';
                        
                        if ($diasTranscurridos >= 15) {
                            $prioridad = 'muy_urgente';
                            $prioridadColor = 'bg-red-100 text-red-800';
                        } elseif ($diasTranscurridos >= 7) {
                            $prioridad = 'urgente';
                            $prioridadColor = 'bg-orange-100 text-orange-800';
                        }
                        
                        // Prioridad especial para renovaciones
                        if ($tramite->tipo_tramite === 'Renovacion') {
                            $prioridad = 'renovacion';
                            $prioridadColor = 'bg-purple-100 text-purple-800';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-200 {{ $prioridad === 'muy_urgente' ? 'bg-red-50/30' : ($prioridad === 'urgente' ? 'bg-orange-50/30' : '') }}">
                        <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                            <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 md:w-3.5 md:h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl text-xs sm:text-sm md:text-base">ID: {{ $tramite->id }}</div>
                                    <div class="text-xs sm:text-sm text-gray-500">{{ $tramite->created_at->format('d/m/Y H:i') }}</div>
                                    @if($prioridad !== 'normal')
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $prioridadColor }}">
                                        @if($prioridad === 'muy_urgente')
                                            Muy urgente
                                        @elseif($prioridad === 'urgente')
                                            Urgente
                                        @elseif($prioridad === 'renovacion')
                                            Renovación
                                        @endif
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                            <div class="min-w-0">
                                <div class="text-gray-900 text-xs sm:text-sm md:text-base font-medium">{{ $tramite->getRazonSocial() ?? 'N/A' }}</div>
                                <div class="text-gray-500 text-xs sm:text-sm">RFC: {{ $tramite->proveedor->rfc ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                            <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 bg-[#9d2449]/10 text-[#9d2449] rounded-full text-xs sm:text-sm font-medium">
                                {{ $tramite->tipo_tramite }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                            @php
                                $estadoColors = [
                                    'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'Revision_Digital' => 'bg-blue-100 text-blue-800',
                                    'Revision_Presencial' => 'bg-purple-100 text-purple-800',
                                    'Revision_Domiciliaria' => 'bg-indigo-100 text-indigo-800',
                                    'Para_Correccion' => 'bg-orange-100 text-orange-800',
                                    'Aprobado' => 'bg-green-100 text-green-800',
                                    'Rechazado' => 'bg-red-100 text-red-800',
                                ];
                                $estadoColor = $estadoColors[$tramite->status] ?? 'bg-gray-100 text-gray-800';
                                
                                $estadoLabels = [
                                    'Pendiente' => 'Pendiente',
                                    'Revision_Digital' => 'Revisión Digital',
                                    'Revision_Presencial' => 'Revisión Presencial',
                                    'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                                    'Para_Correccion' => 'Para Corrección',
                                    'Aprobado' => 'Aprobado',
                                    'Rechazado' => 'Rechazado',
                                ];
                                $estadoLabel = $estadoLabels[$tramite->status] ?? $tramite->status;
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $estadoColor }}">
                                {{ $estadoLabel }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                            <span class="text-sm text-gray-900">{{ $tramite->created_at->format('d/m/Y') }}</span>
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
            // Calcular prioridad basada en antigüedad
            $diasTranscurridos = $tramite->created_at->diffInDays(now());
            $prioridad = 'normal';
            $prioridadColor = 'bg-gray-100 text-gray-600';
            
            if ($diasTranscurridos >= 15) {
                $prioridad = 'muy_urgente';
                $prioridadColor = 'bg-red-100 text-red-800';
            } elseif ($diasTranscurridos >= 7) {
                $prioridad = 'urgente';
                $prioridadColor = 'bg-orange-100 text-orange-800';
            }
            
            // Prioridad especial para renovaciones
            if ($tramite->tipo_tramite === 'Renovacion') {
                $prioridad = 'renovacion';
                $prioridadColor = 'bg-purple-100 text-purple-800';
            }
        @endphp
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 sm:p-3 md:p-4 lg:p-5 {{ $prioridad === 'muy_urgente' ? 'border-l-4 border-l-red-500' : ($prioridad === 'urgente' ? 'border-l-4 border-l-orange-500' : '') }}">
            <div class="flex items-start justify-between mb-2 sm:mb-3 md:mb-4">
                <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-3 lg:space-x-4 min-w-0 flex-1">
                    <div class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-2 h-2 sm:w-2.5 sm:h-2.5 md:w-3 md:h-3 lg:w-3.5 lg:h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1 sm:gap-2">
                            <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base lg:text-lg block">ID: {{ $tramite->id }}</span>
                            @if($prioridad !== 'normal')
                            <span class="inline-flex items-center px-1 py-0.5 rounded-full text-xs font-medium {{ $prioridadColor }}">
                                @if($prioridad === 'muy_urgente')
                                    Muy urgente
                                @elseif($prioridad === 'urgente')
                                    Urgente
                                @elseif($prioridad === 'renovacion')
                                    Renovación
                                @endif
                            </span>
                            @endif
                        </div>
                        <p class="text-xs sm:text-sm md:text-base text-gray-500 truncate">{{ $tramite->tipo_tramite }}</p>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-1 sm:ml-2 md:ml-3">
                    @php
                        $estadoColors = [
                            'Pendiente' => 'bg-yellow-100 text-yellow-800',
                            'Revision_Digital' => 'bg-blue-100 text-blue-800',
                            'Revision_Presencial' => 'bg-purple-100 text-purple-800',
                            'Revision_Domiciliaria' => 'bg-indigo-100 text-indigo-800',
                            'Para_Correccion' => 'bg-orange-100 text-orange-800',
                            'Aprobado' => 'bg-green-100 text-green-800',
                            'Rechazado' => 'bg-red-100 text-red-800',
                        ];
                        $estadoColor = $estadoColors[$tramite->status] ?? 'bg-gray-100 text-gray-800';
                        
                        $estadoLabels = [
                            'Pendiente' => 'Pendiente',
                            'Revision_Digital' => 'Revisión Digital',
                            'Revision_Presencial' => 'Revisión Presencial',
                            'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                            'Para_Correccion' => 'Para Corrección',
                            'Aprobado' => 'Aprobado',
                            'Rechazado' => 'Rechazado',
                        ];
                        $estadoLabel = $estadoLabels[$tramite->status] ?? $tramite->status;
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
        
        // Los filtros siempre empiezan ocultos
        
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
    } catch (error) {
        console.warn('Error initializing revisiones page JavaScript:', error);
    }
});
</script>
@endpush

@endsection