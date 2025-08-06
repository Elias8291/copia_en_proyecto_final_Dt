@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Mis Notificaciones</h1>
                        <p class="text-base text-gray-500 mt-1">Gestiona todas tus notificaciones del sistema</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(isset($estadisticas) && $estadisticas['no_leidas'] > 0)
                    <form method="POST" action="{{ route('notificaciones.marcar-todas-leidas') }}" class="inline">
                        @csrf
                        <button type="submit" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Marcar todas como leídas
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        @if(isset($estadisticas))
        <div class="p-6 border-b border-gray-200/70">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total</p>
                            <p class="text-2xl font-bold text-blue-900">{{ $estadisticas['total'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L3 7v11h14V7l-7-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600">No leídas</p>
                            <p class="text-2xl font-bold text-red-900">{{ $estadisticas['no_leidas'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600">Leídas</p>
                            <p class="text-2xl font-bold text-green-900">{{ $estadisticas['leidas'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Recientes</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $estadisticas['recientes'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filtros -->
        <div class="border-t border-gray-100 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <form method="GET" action="{{ route('notificaciones.index') }}" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8" id="searchForm">
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
                                   placeholder="Buscar en notificaciones..." 
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
                        <a href="{{ route('notificaciones.index') }}" 
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
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                            <div>
                                <label for="status" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Estado</label>
                                <select name="status" 
                                        id="status" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todas</option>
                                    <option value="no_leidas" {{ request('status') == 'no_leidas' ? 'selected' : '' }}>No leídas</option>
                                    <option value="leidas" {{ request('status') == 'leidas' ? 'selected' : '' }}>Leídas</option>
                                </select>
                            </div>

                            <div>
                                <label for="type" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Tipo</label>
                                <select name="type" 
                                        id="type" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los tipos</option>
                                    <option value="informativo" {{ request('type') == 'informativo' ? 'selected' : '' }}>Informativo</option>
                                    <option value="advertencia" {{ request('type') == 'advertencia' ? 'selected' : '' }}>Advertencia</option>
                                    <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Error</option>
                                    <option value="exito" {{ request('type') == 'exito' ? 'selected' : '' }}>Éxito</option>
                                    <option value="Tramite" {{ request('type') == 'Tramite' ? 'selected' : '' }}>Trámite</option>
                                    <option value="Cita" {{ request('type') == 'Cita' ? 'selected' : '' }}>Cita</option>
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
                            <a href="{{ route('notificaciones.index') }}" 
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

        <!-- Información de resultados -->
        <div class="border-t border-gray-100 p-2 sm:p-3 md:p-4 lg:p-5 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5c0 0-5-5-5-5h5v-5z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]">{{ $notificaciones->total() }}</span> 
                        {{ $notificaciones->total() == 1 ? 'notificación encontrada' : 'notificaciones encontradas' }}
                        @if($notificaciones->hasPages())
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                ({{ $notificaciones->firstItem() }}-{{ $notificaciones->lastItem() }})
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Lista de notificaciones -->
        <div class="border-t border-gray-100">
            @forelse($notificaciones as $notificacion)
            <div class="border-b border-gray-100 p-4 hover:bg-gray-50 transition-colors duration-200 {{ !$notificacion->leida ? 'bg-blue-50/30' : '' }}">
                <div class="flex items-start space-x-4">
                    <!-- Icono de tipo -->
                    <div class="flex-shrink-0">
                        @php
                            $iconColors = [
                                'exito' => 'bg-emerald-100 text-emerald-600',
                                'advertencia' => 'bg-amber-100 text-amber-600',
                                'error' => 'bg-red-100 text-red-600',
                                'Tramite' => 'bg-blue-100 text-blue-600',
                                'Cita' => 'bg-purple-100 text-purple-600',
                                'informativo' => 'bg-gray-100 text-gray-600'
                            ];
                            $iconColor = $iconColors[$notificacion->tipo] ?? $iconColors['informativo'];
                        @endphp
                        <div class="w-10 h-10 rounded-lg {{ $iconColor }} flex items-center justify-center">
                            @if($notificacion->tipo === 'exito')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notificacion->tipo === 'error')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notificacion->tipo === 'advertencia')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-.993.883L9 6v3a1 1 0 001.993.117L11 9V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notificacion->tipo === 'Tramite')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notificacion->tipo === 'Cita')
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Contenido de la notificación -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $notificacion->titulo }}</h4>
                                    @if(!$notificacion->leida)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Nueva
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $notificacion->mensaje }}</p>
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span>{{ $notificacion->created_at->diffForHumans() }}</span>
                                    @php
                                        $badgeColors = [
                                            'exito' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'advertencia' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'error' => 'bg-red-100 text-red-700 border-red-200',
                                            'Tramite' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'Cita' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'informativo' => 'bg-gray-100 text-gray-700 border-gray-200'
                                        ];
                                        $badgeColor = $badgeColors[$notificacion->tipo] ?? $badgeColors['informativo'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border {{ $badgeColor }}">
                                        {{ ucfirst($notificacion->tipo) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="flex items-center gap-2 ml-4">
                                @if(!$notificacion->leida)
                                <form method="POST" action="{{ route('notificaciones.marcar-leida', $notificacion) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200"
                                            title="Marcar como leída">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                                
                                @if($notificacion->accion_url)
                                <a href="{{ $notificacion->accion_url }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-[#9d2449] hover:text-white hover:bg-[#9d2449] rounded-lg transition-all duration-200"
                                   title="Ver detalles">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                @endif
                                
                                <form method="POST" action="{{ route('notificaciones.eliminar', $notificacion) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta notificación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                            title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-5 5c0 0-5-5-5-5h5v-5z"/>
                    </svg>
                    <p class="text-lg">No tienes notificaciones</p>
                    <p class="text-sm text-gray-400 mt-1">Cuando recibas notificaciones aparecerán aquí</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($notificaciones->hasPages())
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10 px-6 pb-6">
            <div class="flex justify-center">
                <div class="text-xs sm:text-sm md:text-base">
                    {{ $notificaciones->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal de error -->
<x-error-modal 
    id="error-modal"
    title="Error"
    message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
    buttonText="OK"
/>

<!-- Modal de éxito -->
<x-modal-exito 
    id="success-modal"
    title="¡Éxito!"
    message="La operación se realizó correctamente."
    acceptText="Aceptar"
    :redirectUrl="route('notificaciones.index')"
/>

<!-- Mostrar modal de error si hay error de sesión -->
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session('error') }}');
});
</script>
@endif
                            
<!-- Mostrar modal de éxito si hay éxito de sesión -->
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showSuccessModal('success-modal', '¡Éxito!', '{{ session('success') }}');
});
</script>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const toggle = document.getElementById('toggleFilters');
        const container = document.getElementById('filtersContainer');
        const text = document.getElementById('filterText');
        const icon = document.getElementById('filterIcon');
        
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
    } catch (error) {
        console.warn('Error initializing notifications page JavaScript:', error);
    }
});
</script>
@endpush
@endsection
