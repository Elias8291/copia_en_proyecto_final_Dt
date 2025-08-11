@extends('layouts.app')

@section('title', 'Reportes Dinámicos')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-6 lg:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold leading-7 text-gray-900 sm:leading-9 sm:truncate">
                        📊 Reportes Dinámicos
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Filtra los datos y descarga reportes personalizados según tus necesidades
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex gap-2">
                    <a href="{{ route('reportes.trimestrales') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        📅 Reportes Trimestrales
                    </a>
                    <a href="{{ route('reportes.analisis') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        📊 Análisis
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs sm:text-sm font-bold">T</span>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total</dt>
                                <dd class="text-sm sm:text-lg font-semibold text-gray-900">{{ number_format($estadisticas['total']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs sm:text-sm font-bold">A</span>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Activos</dt>
                                <dd class="text-sm sm:text-lg font-semibold text-green-600">{{ number_format($estadisticas['activos']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs sm:text-sm font-bold">V</span>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Vencidos</dt>
                                <dd class="text-sm sm:text-lg font-semibold text-red-600">{{ number_format($estadisticas['vencidos']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs sm:text-sm font-bold">P</span>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                                <dd class="text-sm sm:text-lg font-semibold text-yellow-600">{{ number_format($estadisticas['pendientes']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-400 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs sm:text-sm font-bold">I</span>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Inactivos</dt>
                                <dd class="text-sm sm:text-lg font-semibold text-gray-600">{{ number_format($estadisticas['inactivos']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-3 sm:p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs sm:text-sm font-bold">F</span>
                            </div>
                        </div>
                        <div class="ml-3 sm:ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Filtrados</dt>
                                <dd class="text-sm sm:text-lg font-semibold text-blue-600">{{ number_format($estadisticas['filtrados']) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    🔍 Filtros de Búsqueda
                </h3>
                
                <form method="GET" action="{{ route('reportes.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        
                        <!-- Búsqueda General -->
                        <div>
                            <label for="buscar" class="block text-sm font-medium text-gray-700">
                                Buscar (Razón Social, RFC, PV)
                            </label>
                            <input type="text" 
                                   name="buscar" 
                                   id="buscar"
                                   value="{{ request('buscar') }}"
                                   placeholder="Escribe para buscar..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Estado del Padrón -->
                        <div>
                            <label for="estado_padron" class="block text-sm font-medium text-gray-700">
                                Estado del Padrón
                            </label>
                            <select name="estado_padron" id="estado_padron" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todos los estados</option>
                                <option value="Activo" {{ request('estado_padron') == 'Activo' ? 'selected' : '' }}>Activo</option>
                                <option value="Vencido" {{ request('estado_padron') == 'Vencido' ? 'selected' : '' }}>Vencido</option>
                                <option value="Pendiente" {{ request('estado_padron') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Inactivo" {{ request('estado_padron') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <!-- Tipo de Persona -->
                        <div>
                            <label for="tipo_persona" class="block text-sm font-medium text-gray-700">
                                Tipo de Persona
                            </label>
                            <select name="tipo_persona" id="tipo_persona" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todos los tipos</option>
                                <option value="Persona Física" {{ request('tipo_persona') == 'Persona Física' ? 'selected' : '' }}>Persona Física</option>
                                <option value="Persona Moral" {{ request('tipo_persona') == 'Persona Moral' ? 'selected' : '' }}>Persona Moral</option>
                            </select>
                        </div>

                        <!-- Estado Geográfico -->
                        <div>
                            <label for="estado_geografico" class="block text-sm font-medium text-gray-700">
                                Estado (Ubicación)
                            </label>
                            <select name="estado_geografico" id="estado_geografico" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todos los estados</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->id }}" {{ request('estado_geografico') == $estado->id ? 'selected' : '' }}>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Municipio -->
                        <div>
                            <label for="municipio" class="block text-sm font-medium text-gray-700">
                                Municipio
                            </label>
                            <input type="text" 
                                   name="municipio" 
                                   id="municipio"
                                   value="{{ request('municipio') }}"
                                   placeholder="Nombre del municipio"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Actividad Económica -->
                        <div>
                            <label for="actividad_economica" class="block text-sm font-medium text-gray-700">
                                Actividad Económica
                            </label>
                            <select name="actividad_economica" id="actividad_economica" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todas las actividades</option>
                                @foreach($actividades as $actividad)
                                    <option value="{{ $actividad->id }}" {{ request('actividad_economica') == $actividad->id ? 'selected' : '' }}>
                                        {{ $actividad->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Días para Vencer -->
                        <div>
                            <label for="dias_vencer" class="block text-sm font-medium text-gray-700">
                                Por Vencer en X días
                            </label>
                            <select name="dias_vencer" id="dias_vencer" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Sin filtro de vencimiento</option>
                                <option value="7" {{ request('dias_vencer') == '7' ? 'selected' : '' }}>7 días</option>
                                <option value="15" {{ request('dias_vencer') == '15' ? 'selected' : '' }}>15 días</option>
                                <option value="30" {{ request('dias_vencer') == '30' ? 'selected' : '' }}>30 días</option>
                                <option value="60" {{ request('dias_vencer') == '60' ? 'selected' : '' }}>60 días</option>
                                <option value="90" {{ request('dias_vencer') == '90' ? 'selected' : '' }}>90 días</option>
                            </select>
                        </div>

                        <!-- Fecha Alta Desde -->
                        <div>
                            <label for="fecha_alta_desde" class="block text-sm font-medium text-gray-700">
                                Fecha Alta Desde
                            </label>
                            <input type="date" 
                                   name="fecha_alta_desde" 
                                   id="fecha_alta_desde"
                                   value="{{ request('fecha_alta_desde') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Fecha Alta Hasta -->
                        <div>
                            <label for="fecha_alta_hasta" class="block text-sm font-medium text-gray-700">
                                Fecha Alta Hasta
                            </label>
                            <input type="date" 
                                   name="fecha_alta_hasta" 
                                   id="fecha_alta_hasta"
                                   value="{{ request('fecha_alta_hasta') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Fecha Vencimiento Desde -->
                        <div>
                            <label for="fecha_vencimiento_desde" class="block text-sm font-medium text-gray-700">
                                Vencimiento Desde
                            </label>
                            <input type="date" 
                                   name="fecha_vencimiento_desde" 
                                   id="fecha_vencimiento_desde"
                                   value="{{ request('fecha_vencimiento_desde') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Fecha Vencimiento Hasta -->
                        <div>
                            <label for="fecha_vencimiento_hasta" class="block text-sm font-medium text-gray-700">
                                Vencimiento Hasta
                            </label>
                            <input type="date" 
                                   name="fecha_vencimiento_hasta" 
                                   id="fecha_vencimiento_hasta"
                                   value="{{ request('fecha_vencimiento_hasta') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Aplicar Filtros
                        </button>

                        <a href="{{ route('reportes.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel de Exportación -->
        @if($proveedores->count() > 0)
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    📥 Descargar Resultados Filtrados
                </h3>
                
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                    <!-- Reporte Completo -->
                    <a href="{{ route('reportes.exportar-filtrado', array_merge(request()->all(), ['tipo_reporte' => 'completo'])) }}" 
                       class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Completo
                    </a>

                    <!-- Reporte Geográfico -->
                    <a href="{{ route('reportes.exportar-filtrado', array_merge(request()->all(), ['tipo_reporte' => 'geografico'])) }}" 
                       class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        Geográfico
                    </a>

                    <!-- Reporte de Contactos -->
                    <a href="{{ route('reportes.exportar-filtrado', array_merge(request()->all(), ['tipo_reporte' => 'contactos'])) }}" 
                       class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Contactos
                    </a>

                    <!-- Reporte de Actividades -->
                    <a href="{{ route('reportes.exportar-filtrado', array_merge(request()->all(), ['tipo_reporte' => 'actividades'])) }}" 
                       class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Actividades
                    </a>

                    <!-- Reporte de Vencimientos -->
                    <a href="{{ route('reportes.exportar-filtrado', array_merge(request()->all(), ['tipo_reporte' => 'vencimientos'])) }}" 
                       class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Vencimientos
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabla de Resultados -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Resultados de la Búsqueda
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Se encontraron {{ number_format($proveedores->total()) }} proveedores con los filtros aplicados
                </p>
            </div>

            @if($proveedores->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Proveedor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ubicación
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vigencia
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proveedores as $proveedor)
                                @php
                                    $ultimoTramite = $proveedor->tramites->first();
                                    $direccion = $ultimoTramite ? $ultimoTramite->direcciones->first() : null;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $proveedor->razon_social }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    RFC: {{ $proveedor->rfc }} | PV: {{ $proveedor->pv_numero }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $proveedor->tipo_persona }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $colors = [
                                                'Activo' => 'bg-green-100 text-green-800',
                                                'Vencido' => 'bg-red-100 text-red-800',
                                                'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                                'Inactivo' => 'bg-gray-100 text-gray-800'
                                            ];
                                            $colorClass = $colors[$proveedor->estado_padron] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $colorClass }}">
                                            {{ $proveedor->estado_padron }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($direccion)
                                            <div>{{ $direccion->estado->nombre ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $direccion->municipio ?? 'N/A' }}</div>
                                        @else
                                            <span class="text-gray-400">Sin dirección</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($proveedor->fecha_vencimiento_padron)
                                            <div>{{ \Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron)->format('d/m/Y') }}</div>
                                            @php
                                                $diasRestantes = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($proveedor->fecha_vencimiento_padron), false);
                                            @endphp
                                            @if($diasRestantes < 0)
                                                <div class="text-xs text-red-500">Vencido hace {{ abs($diasRestantes) }} días</div>
                                            @elseif($diasRestantes <= 30)
                                                <div class="text-xs text-orange-500">Vence en {{ $diasRestantes }} días</div>
                                            @else
                                                <div class="text-xs text-green-500">{{ $diasRestantes }} días restantes</div>
                                            @endif
                                        @else
                                            <span class="text-gray-400">Sin fecha</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('proveedores.show', $proveedor) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $proveedores->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron resultados</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Intenta ajustar los filtros de búsqueda para encontrar proveedores.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
