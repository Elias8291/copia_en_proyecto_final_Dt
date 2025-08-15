@extends('layouts.app')

@push('styles')
<style>
.page-loading {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
.page-loaded {
    opacity: 1;
}

#filtersContainer {
    transform: translateZ(0);
    will-change: transform, opacity;
}

.table-container {
    min-height: 400px;
}
</style>
@endpush

@section('content')
<script>
// Script inline para prevenir FOUC - se ejecuta inmediatamente
document.documentElement.style.visibility = 'hidden';
window.addEventListener('load', function() {
    document.documentElement.style.visibility = 'visible';
});
</script>
<div class="p-2 sm:p-3 md:p-4 lg:p-5 page-loading" id="mainContainer">
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
                        <button type="submit" 
                                class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-blue-600 text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600/50 transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span class="hidden sm:inline">Filtrar</span>
                        </button>
                        <a href="{{ route('proveedores.index') }}" 
                           class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-100 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2 border border-gray-300">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                                <h3 class="text-lg font-semibold text-gray-900">Filtros Avanzados</h3>
                                <p class="text-sm text-gray-500">Personaliza tu búsqueda con filtros específicos</p>
                            </div>
                        </div>
                        <div>
                            <button type="button" id="toggleFilters" class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-[#9d2449] text-[#9d2449] hover:bg-[#9d2449] hover:text-white font-medium rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                </svg>
                            <span id="filterText">Mostrar filtros</span>
                            <span id="filterIcon" class="transform transition-transform duration-300">▼</span>
                        </button>
                        </div>
                    </div>
                        
                    <div id="filtersContainer" class="hidden max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">         
                            <div class="space-y-5">
                                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="p-2 bg-gray-600 rounded-lg">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Información Básica</h4>
                                </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado del Padrón</label>
                                            <select name="estado" id="estado" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                    <option value="">Todos los estados</option>
                                                <option value="Activo" {{ request('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                                <option value="Inactivo" {{ request('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                                <option value="Vencido" {{ request('estado') == 'Vencido' ? 'selected' : '' }}>Vencido</option>
                                                <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>

                            <div>
                                            <label for="tipo_persona" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Persona</label>
                                            <select name="tipo_persona" id="tipo_persona" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                <option value="">Todos los tipos</option>
                                                <option value="Física" {{ request('tipo_persona') == 'Física' ? 'selected' : '' }}>Persona Física</option>
                                                <option value="Moral" {{ request('tipo_persona') == 'Moral' ? 'selected' : '' }}>Persona Moral</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-5 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="p-2 bg-[#9d2449] rounded-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Fechas y Ubicación</h4>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                                <div>
                                            <label for="año" class="block text-sm font-medium text-gray-700 mb-2">Año de Alta en Padrón</label>
                                            <select name="año" id="año" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                    <option value="">Todos los años</option>
                                                @foreach($añosDisponibles as $año)
                                                    <option value="{{ $año }}" {{ request('año') == $año ? 'selected' : '' }}>{{ $año }}</option>
                                                @endforeach
                                </select>
                            </div>

                            <div>
                                        <label for="estado_geografico" class="block text-sm font-medium text-gray-700 mb-2">Estado Geográfico</label>
                                            <select name="estado_geografico" id="estado_geografico" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                    <option value="">Todos los estados</option>
                                            @foreach($estados ?? [] as $estado)
                                        <option value="{{ $estado->id }}" {{ request('estado_geografico') == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->nombre }}
                                        </option>
                                        @endforeach
                                </select>
                                    </div>
                                </div>

                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h5 class="text-sm font-semibold text-gray-900 mb-3">Filtro de Vencimiento</h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="proximidad_vencimiento" class="block text-sm font-medium text-gray-700 mb-2">Próximos a Vencer</label>
                                                <select name="proximidad_vencimiento" id="proximidad_vencimiento" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                    <option value="">Sin filtro de vencimiento</option>
                                                    <option value="7" {{ request('proximidad_vencimiento') == '7' ? 'selected' : '' }}>Próximos 7 días</option>
                                                    <option value="15" {{ request('proximidad_vencimiento') == '15' ? 'selected' : '' }}>Próximos 15 días</option>
                                                    <option value="30" {{ request('proximidad_vencimiento') == '30' ? 'selected' : '' }}>Próximos 30 días</option>
                                                    <option value="60" {{ request('proximidad_vencimiento') == '60' ? 'selected' : '' }}>Próximos 2 meses</option>
                                                    <option value="90" {{ request('proximidad_vencimiento') == '90' ? 'selected' : '' }}>Próximos 3 meses</option>
                                                    <option value="180" {{ request('proximidad_vencimiento') == '180' ? 'selected' : '' }}>Próximos 6 meses</option>
                                                    <option value="custom" {{ request('proximidad_vencimiento') == 'custom' ? 'selected' : '' }}>Personalizado</option>
                                                </select>
                            </div>

                                            <div id="custom-days-container" class="hidden">
                                                <label for="dias_personalizados" class="block text-sm font-medium text-gray-700 mb-2">Días Personalizados</label>
                                                <input type="number" 
                                                       name="dias_personalizados" 
                                                       id="dias_personalizados"
                                                       value="{{ request('dias_personalizados') }}"
                                                       min="1" 
                                                       max="365"
                                                       placeholder="Ej: 45"
                                                       class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                <p class="text-xs text-gray-500 mt-1">Ingresa el número de días (1-365)</p>
                                            </div>
                                        </div>
                                        <div class="bg-gray-100 p-3 rounded-lg mt-3">
                                            <p class="text-sm text-gray-600">Filtra proveedores cuyo padrón vence en el período especificado</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3. Sectores y Actividades -->
                                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="p-2 bg-gray-600 rounded-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Sectores y Actividades</h4>
                                    </div>
                                    <div>
                                        <button type="button" onclick="openSectorActividadModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 border border-gray-300 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                                            </svg>
                                            Seleccionar Sectores/Actividades
                                        </button>
                                        <p class="text-sm text-gray-600 mt-2">Filtra por sectores económicos y actividades específicas</p>
                                    </div>
                                </div>

                            </div>

                            <!-- COLUMNA DERECHA -->
                            <div class="space-y-5">

                                <!-- 1. Historial de Trámites -->
                                <div class="bg-white rounded-lg p-5 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="p-2 bg-[#9d2449] rounded-lg">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v0z"/>
                                        </svg>
                                    </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Historial de Trámites</h4>
                                </div>
                                    <div>
                                        <label for="con_historial" class="block text-sm font-medium text-gray-700 mb-2">Experiencia del Proveedor</label>
                                        <select name="con_historial" id="con_historial" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                            <option value="">Todos los proveedores</option>
                                            <option value="si" {{ request('con_historial') == 'si' ? 'selected' : '' }}>Con historial (2+ trámites)</option>
                                            <option value="renovadores" {{ request('con_historial') == 'renovadores' ? 'selected' : '' }}>Renovadores constantes</option>
                                            <option value="no" {{ request('con_historial') == 'no' ? 'selected' : '' }}>Nuevos (1 trámite o menos)</option>
                                            <option value="sin_tramites" {{ request('con_historial') == 'sin_tramites' ? 'selected' : '' }}>Sin trámites</option>
                                        </select>
                                    </div>
                                    </div>

                                <!-- 2. Filtros Específicos -->
                                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="p-2 bg-gray-600 rounded-lg">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v0z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Filtros Específicos</h4>
                                    </div>
                                    <div class="space-y-4">
                                            <div>
                                            <label for="tipo_tramite_año" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Trámite</label>
                                            <select name="tipo_tramite_año" id="tipo_tramite_año" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                    <option value="">Cualquier tipo</option>
                                                <option value="Inscripcion" {{ request('tipo_tramite_año') == 'Inscripcion' ? 'selected' : '' }}>Inscripciones</option>
                                                <option value="Renovacion" {{ request('tipo_tramite_año') == 'Renovacion' ? 'selected' : '' }}>Renovaciones</option>
                                                <option value="Actualizacion" {{ request('tipo_tramite_año') == 'Actualizacion' ? 'selected' : '' }}>Actualizaciones</option>
                                                </select>
                                            </div>
                                            <div>
                                            <label for="año_especifico" class="block text-sm font-medium text-gray-700 mb-2">Año del Trámite</label>
                                            <select name="año_especifico" id="año_especifico" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                    <option value="">Cualquier año</option>
                                                <option value="2025" {{ request('año_especifico') == '2025' ? 'selected' : '' }}>2025</option>
                                                <option value="2024" {{ request('año_especifico') == '2024' ? 'selected' : '' }}>2024</option>
                                                <option value="2023" {{ request('año_especifico') == '2023' ? 'selected' : '' }}>2023</option>
                                                <option value="2022" {{ request('año_especifico') == '2022' ? 'selected' : '' }}>2022</option>
                                                <option value="2021" {{ request('año_especifico') == '2021' ? 'selected' : '' }}>2021</option>
                                                </select>
                                            </div>
                                        <div class="bg-gray-100 p-3 rounded-lg">
                                            <p class="text-sm text-gray-600">Busca proveedores que realizaron un tipo específico de trámite en un año determinado</p>
                                    </div>
                                </div>
                            </div>

                                <!-- 3. Filtros Trimestrales -->
                                <div class="bg-white rounded-lg p-5 border border-gray-200">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="p-2 bg-[#9d2449] rounded-lg">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Filtros Trimestrales</h4>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                            <label for="año_trimestre" class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                                            <select name="año_trimestre" id="año_trimestre" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                <option value="">Todos los años</option>
                                                @foreach($añosDisponibles as $año)
                                                    <option value="{{ $año }}" {{ request('año_trimestre') == $año ? 'selected' : '' }}>{{ $año }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                        
                                        <div>
                                            <label for="trimestre" class="block text-sm font-medium text-gray-700 mb-2">Trimestre</label>
                                            <select name="trimestre" id="trimestre" class="w-full px-4 py-3 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449] focus:border-[#9d2449] transition-all duration-200 bg-white">
                                                <option value="">Todos los trimestres</option>
                                                <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>Q1 - Enero a Marzo</option>
                                                <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>Q2 - Abril a Junio</option>
                                                <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>Q3 - Julio a Septiembre</option>
                                                <option value="4" {{ request('trimestre') == '4' ? 'selected' : '' }}>Q4 - Octubre a Diciembre</option>
                                            </select>
                                </div>
                            </div>
                                    <div class="bg-gray-100 p-3 rounded-lg mt-4">
                                        <p class="text-sm text-gray-600">Filtra proveedores cuyo último trámite fue realizado en el período seleccionado</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de Filtros - Fuera del contenedor pero dentro del formulario -->
                    <div class="pt-4 mt-4">
                        <div class="flex justify-center gap-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500/30 transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm">Filtrar</span>
                            </button>
                            <a href="{{ route('proveedores.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 border border-gray-300 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span class="text-sm">Limpiar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sección de Resultados -->
        <div class="p-3 sm:p-4 md:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-[#9d2449] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
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
                <div class="flex items-center gap-1 sm:gap-2 md:gap-3">
                    <!-- Botón Exportar Excel -->
                    <button type="button" onclick="openExportModal()" 
                            class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 bg-green-600 text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md"
                            title="Configurar y Exportar a Excel">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="hidden sm:inline">Excel</span>
                    </button>
                    
                    @if(request()->hasAny(['search', 'estado', 'año', 'estado_geografico', 'sector', 'actividad_economica', 'tipo_persona', 'con_historial', 'tipo_tramite_año', 'año_especifico', 'proximidad_vencimiento', 'dias_personalizados', 'año_trimestre', 'trimestre']))
                        <a href="{{ route('proveedores.index') }}" 
                           class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 bg-gray-100 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-200 transition-all duration-200 border border-gray-300">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                            <span class="hidden sm:inline">Limpiar</span>
                        </a>
                    @endif
                    <div class="flex items-center gap-1 sm:gap-2">
                        <label for="per_page" class="text-xs sm:text-sm md:text-base text-gray-600 whitespace-nowrap">Por página:</label>
                        <select id="per_page" 
                                class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] bg-white">
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Proveedores -->
        <div class="border-t border-gray-100 overflow-hidden hidden xl:block">
            <div class="overflow-x-auto table-container">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['orden_por' => 'razon_social', 'direccion' => request('orden_por') == 'razon_social' && request('direccion') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider hover:text-[#9d2449] transition-colors">
                                    <span>Razón Social</span>
                                    @if(request('orden_por') == 'razon_social')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            @if(request('direccion') == 'asc')
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            @else
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['orden_por' => 'rfc', 'direccion' => request('orden_por') == 'rfc' && request('direccion') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider hover:text-[#9d2449] transition-colors">
                                    <span>RFC</span>
                                    @if(request('orden_por') == 'rfc')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            @if(request('direccion') == 'asc')
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            @else
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['orden_por' => 'estado_padron', 'direccion' => request('orden_por') == 'estado_padron' && request('direccion') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider hover:text-[#9d2449] transition-colors">
                                    <span>Estado</span>
                                    @if(request('orden_por') == 'estado_padron')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            @if(request('direccion') == 'asc')
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            @else
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['orden_por' => 'fecha_alta_padron', 'direccion' => request('orden_por') == 'fecha_alta_padron' && request('direccion') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider hover:text-[#9d2449] transition-colors">
                                    <span>Fecha Alta Padrón</span>
                                    @if(request('orden_por') == 'fecha_alta_padron')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            @if(request('direccion') == 'asc')
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            @else
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['orden_por' => 'fecha_vencimiento_padron', 'direccion' => request('orden_por') == 'fecha_vencimiento_padron' && request('direccion') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider hover:text-[#9d2449] transition-colors">
                                    <span>Vencimiento</span>
                                    @if(request('orden_por') == 'fecha_vencimiento_padron')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            @if(request('direccion') == 'asc')
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            @else
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <span class="text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Fecha Inicio</span>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-left">
                                <a href="{{ request()->fullUrlWithQuery(['orden_por' => 'tipo_persona', 'direccion' => request('orden_por') == 'tipo_persona' && request('direccion') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="flex items-center space-x-1 text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider hover:text-[#9d2449] transition-colors">
                                    <span>Tipo Persona</span>
                                    @if(request('orden_por') == 'tipo_persona')
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            @if(request('direccion') == 'asc')
                                                <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                            @else
                                                <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                            @endif
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th class="px-2 sm:px-3 md:px-4 py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($todosProveedores as $proveedor)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <!-- Razón Social -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <div class="flex flex-col space-y-1">
                                    <div class="flex items-start space-x-2">
                                        <div class="flex-shrink-0 mt-0.5">
                                            @if($proveedor->tipo_persona == 'Moral')
                                                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                    </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm md:text-base font-medium text-gray-900 truncate">
                                                {{ $proveedor->razon_social }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                ID: {{ $proveedor->id }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($proveedor->tramites_count > 0)
                                        <div class="flex items-center space-x-2 ml-7 sm:ml-8">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-[#9d2449]/10 text-[#9d2449] font-medium">
                                                📋 {{ $proveedor->tramites_count }} {{ $proveedor->tramites_count == 1 ? 'trámite' : 'trámites' }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- RFC -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-[#9d2449] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-gray-700 font-mono text-xs sm:text-sm md:text-base">{{ $proveedor->rfc }}</span>
                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @switch($proveedor->estado_padron)
                                    @case('Activo')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Activo
                                        </span>
                                        @break
                                    @case('Inactivo')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Inactivo
                                        </span>
                                        @break
                                    @case('Vencido')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Vencido
                                </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $proveedor->estado_padron }}
                                </span>
                                @endswitch
                            </td>

                                                        <!-- Fecha Alta Padrón -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @if($proveedor->fecha_alta_padron)
                                    <div class="flex items-center space-x-1.5">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-[#9d2449] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base">{{ \Carbon\Carbon::parse($proveedor->fecha_alta_padron)->format('d/m/Y') }}</span>
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
                            
                            <!-- Vencimiento -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @if($proveedor->fecha_vencimiento_padron)
                                    @php
                                        $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                                        $esVencido = $fechaVencimiento->isPast();
                                    @endphp
                                        <div class="flex items-center space-x-1.5">
                                        @if($esVencido)
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                        <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base">{{ $fechaVencimiento->format('d/m/Y') }}</span>
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

                            <!-- Fecha Inicio (calculada) -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @if($proveedor->fecha_vencimiento_padron)
                                    @php
                                        // Calcular fecha de inicio (1 año antes de vencimiento)
                                        $fechaVencimiento = \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron);
                                        $fechaInicio = $fechaVencimiento->copy()->subYear();
                                    @endphp
                                    <div class="flex items-center space-x-1.5">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-700 text-xs sm:text-sm md:text-base font-medium">
                                            {{ $fechaInicio->format('d/m/Y') }}
                                        </span>
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

                            <!-- Tipo de Persona -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                @if($proveedor->tipo_persona)
                                    <div class="flex items-center space-x-2">
                                        @if($proveedor->tipo_persona == 'Moral')
                                            <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 text-xs sm:text-sm md:text-base font-medium">Moral</span>
                                        @else
                                            <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700 text-xs sm:text-sm md:text-base font-medium">Física</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </div>
                                        <span class="text-gray-500 text-xs sm:text-sm md:text-base">No definido</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="px-2 sm:px-3 md:px-4 py-2 sm:py-3">
                                <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                    <a href="{{ route('proveedores.show', $proveedor->id) }}" 
                                       class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" 
                                       title="Ver detalles">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <form id="form-delete-proveedor-{{ $proveedor->id }}" action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="showDeleteModal('Eliminar proveedor', '¿Está seguro que desea eliminar este proveedor? Esta acción no se puede deshacer.', 'form-delete-proveedor-{{ $proveedor->id }}')" class="group inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" title="Eliminar">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <div class="text-center">
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron proveedores</h3>
                                        <p class="text-gray-500 text-sm">
                                            @if(request()->hasAny(['search', 'estado', 'año', 'estado_geografico', 'sector', 'actividad_economica', 'tipo_persona', 'con_historial', 'tipo_tramite_año', 'año_especifico']))
                                                Intenta ajustar los filtros de búsqueda o 
                                                <a href="{{ route('proveedores.index') }}" class="text-[#9d2449] hover:text-[#8a1f40] font-medium">limpiar todos los filtros</a>
                                            @else
                                                No hay proveedores registrados en el sistema
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($todosProveedores->hasPages())
        <div class="border-t border-gray-100 px-3 sm:px-4 md:px-5 py-3 sm:py-4">
            {{ $todosProveedores->withQueryString()->links('vendor.pagination.tailwind') }}
                </div>
        @endif
                        </div>
                    </div>

@include('components.ui.modals.delete-confirmation-modal')

<!-- Modal para Sectores y Actividades -->
<div id="sectorActividadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Filtrar por Sectores y Actividades</h3>
            <button type="button" onclick="closeSectorActividadModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Sectores -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                    Sectores Económicos
                </h4>
                <div class="text-xs text-gray-500 mb-2">Formato: ID - Nombre del Sector</div>
                <div id="sectoresContainer" class="space-y-1 max-h-64 overflow-y-auto border rounded-lg p-2 bg-gray-50">
                    <div class="text-center py-4 text-gray-500">Cargando sectores...</div>
                </div>
            </div>
            
            <!-- Actividades -->
            <div>
                <h4 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v0z"/>
                    </svg>
                    Actividades Económicas
                </h4>
                <div class="text-xs text-gray-500 mb-2">Formato: ID - Nombre de la Actividad</div>
                <div id="actividadesContainer" class="space-y-1 max-h-64 overflow-y-auto border rounded-lg p-2 bg-gray-50">
                    <div class="text-center py-4 text-gray-500">Cargando actividades...</div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" onclick="closeSectorActividadModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                Cancelar
            </button>
            <button type="button" onclick="applySectorActividadFilters()" class="px-4 py-2 bg-[#9d2449] text-white rounded-lg hover:bg-[#8a1f40]">
                Aplicar Filtros
            </button>
        </div>
    </div>
</div>

<!-- Modal para Configuración de Exportación Excel -->
<div id="exportModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Seleccionar Columnas para Exportar</h3>
            <button type="button" onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="exportForm" method="GET" action="{{ route('proveedores.export') }}">
            <!-- Incluir filtros actuales -->
            @foreach(request()->except(['page']) as $key => $value)
                @if(is_array($value))
                    @foreach($value as $item)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach

            <!-- Selección de Columnas -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z"/>
                        </svg>
                        Columnas Disponibles
                    </h4>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" id="selectAllColumns" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="text-sm text-gray-600">Seleccionar todas</span>
                    </label>
                </div>
                <div class="text-xs text-gray-500 mb-2">Selecciona las columnas que deseas incluir en la exportación</div>
                
                <div class="space-y-1 max-h-64 overflow-y-auto border rounded-lg p-2 bg-gray-50">
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="id" checked class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">ID</span>
                            <span class="text-gray-700">Identificador único</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="rfc" checked class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">RFC</span>
                            <span class="text-gray-700">Registro Federal de Contribuyentes</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="razon_social" checked class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">RAZÓN</span>
                            <span class="text-gray-700">Razón Social</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="tipo_persona" checked class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">TIPO</span>
                            <span class="text-gray-700">Tipo de Persona</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="estado_padron" checked class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">ESTADO</span>
                            <span class="text-gray-700">Estado del Padrón</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="telefono" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">TEL</span>
                            <span class="text-gray-700">Teléfono</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="domicilio" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">DOM</span>
                            <span class="text-gray-700">Domicilio</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="fecha_alta_padron" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">F.ALTA</span>
                            <span class="text-gray-700">Fecha de Alta</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="fecha_vencimiento_padron" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">F.VENC</span>
                            <span class="text-gray-700">Fecha de Vencimiento</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="dias_restantes" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">DÍAS</span>
                            <span class="text-gray-700">Días Restantes</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="fecha_inicio" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">F.INICIO</span>
                            <span class="text-gray-700">Fecha Inicio</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="tramites_count" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">TRAM</span>
                            <span class="text-gray-700">Número de Trámites</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="actividades" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">ACT</span>
                            <span class="text-gray-700">Actividades Económicas</span>
                        </span>
                    </label>
                    
                    <label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="columns[]" value="estado_geografico" class="rounded border-gray-300 text-green-600 focus:ring-green-600 export-column">
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">ESTADO</span>
                            <span class="text-gray-700">Estado Geográfico</span>
                        </span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeExportModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar Excel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remover el efecto de loading una vez que el DOM esté cargado
    const mainContainer = document.getElementById('mainContainer');
    if (mainContainer) {
        mainContainer.classList.remove('page-loading');
        mainContainer.classList.add('page-loaded');
    }
    
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
        console.log('🔍 Abriendo modal de sectores/actividades...');
        const modal = document.getElementById('sectorActividadModal');
        if (modal) {
            modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
            console.log('✅ Modal abierto, cargando datos...');
        lazyLoadSectorActividad();
        } else {
            console.error('❌ No se encontró el modal sectorActividadModal');
        }
    }

    window.closeSectorActividadModal = function() {
        document.getElementById('sectorActividadModal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    let sectoresData = null;
    let actividadesData = null;

    window.lazyLoadSectorActividad = function() {
        console.log('📡 Cargando datos de sectores y actividades...');
        
        if (!sectoresData) {
            console.log('🔍 Cargando sectores desde /api/sectores...');
            fetch('/api/sectores')
                .then(response => {
                    console.log('📡 Respuesta sectores:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Sectores cargados:', data.length, 'elementos');
                    sectoresData = data;
                    renderList(document.getElementById('sectoresContainer'), data, 'sector');
                })
                .catch(error => {
                    console.error('❌ Error al cargar sectores:', error);
                    document.getElementById('sectoresContainer').innerHTML = '<div class="text-center py-4 text-red-500">Error al cargar sectores</div>';
                });
        }

        if (!actividadesData) {
            console.log('🔍 Cargando actividades desde /api/actividades...');
            fetch('/api/actividades')
                .then(response => {
                    console.log('📡 Respuesta actividades:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Actividades cargadas:', data.length, 'elementos');
                    actividadesData = data;
                    renderList(document.getElementById('actividadesContainer'), data, 'actividad_economica');
                })
                .catch(error => {
                    console.error('❌ Error al cargar actividades:', error);
                    document.getElementById('actividadesContainer').innerHTML = '<div class="text-center py-4 text-red-500">Error al cargar actividades</div>';
                });
        }
    }

    function renderList(container, items, name) {
        const selected = new Set((new URLSearchParams(window.location.search)).getAll(name + '[]'));
        container.innerHTML = items.map(item => {
            const id = item.id ?? item.value ?? item;
            const label = item.nombre ?? item.label ?? item;
            const checked = selected.has(String(id)) ? 'checked' : '';
            return `<label class="flex items-center gap-2 py-1 hover:bg-gray-50 px-2 rounded cursor-pointer">
                        <input type="checkbox" name="${name}[]" form="searchForm" value="${id}" ${checked} class="rounded border-gray-300 text-[#9d2449] focus:ring-[#9d2449]"> 
                        <span class="flex-1 text-sm">
                            <span class="font-mono text-xs text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded mr-2">${id}</span>
                            <span class="text-gray-700">${label}</span>
                        </span>
                    </label>`;
        }).join('') || '<div class="py-6 text-center text-gray-400">Sin datos</div>';
    }

    window.applySectorActividadFilters = function() {
        closeSectorActividadModal();
        searchForm.submit();
    }

    // Manejar filtro de vencimiento personalizado
    const proximidadSelect = document.getElementById('proximidad_vencimiento');
    const customContainer = document.getElementById('custom-days-container');

    if (proximidadSelect && customContainer) {
        function toggleCustomDays() {
            if (proximidadSelect.value === 'custom') {
                customContainer.classList.remove('hidden');
            } else {
                customContainer.classList.add('hidden');
            }
        }

        // Ejecutar al cargar la página
        toggleCustomDays();

        // Ejecutar cuando cambie la selección
        proximidadSelect.addEventListener('change', toggleCustomDays);
    }

    // Funciones para el modal de exportación
    window.openExportModal = function() {
        document.getElementById('exportModal')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    window.closeExportModal = function() {
        document.getElementById('exportModal')?.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Manejar selección de todas las columnas
    const selectAllColumns = document.getElementById('selectAllColumns');
    const exportColumns = document.querySelectorAll('.export-column');

    if (selectAllColumns && exportColumns.length > 0) {
        selectAllColumns.addEventListener('change', function() {
            exportColumns.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Actualizar el estado del "Seleccionar todas" cuando se cambien las columnas individuales
        exportColumns.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(exportColumns).every(cb => cb.checked);
                const someChecked = Array.from(exportColumns).some(cb => cb.checked);
                
                selectAllColumns.checked = allChecked;
                selectAllColumns.indeterminate = someChecked && !allChecked;
            });
        });
    }


});
</script>
@endpush