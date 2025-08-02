@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4 sm:p-5 md:p-6 lg:p-8 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 lg:gap-6">
                <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-semibold text-gray-900 leading-tight">Documentos</h1>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-600 mt-1">Gestión de documentos del sistema</p>
                </div>
            </div>
        </div>

        <!-- Búsqueda y Filtros -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <form method="GET" action="{{ route('documentos.index') }}" class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
                <!-- Barra de búsqueda principal -->
                <div class="flex flex-col lg:flex-row gap-2 sm:gap-3 md:gap-4 lg:gap-6 mb-3 sm:mb-4 md:mb-5 lg:mb-6">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 md:pl-4 flex items-center pointer-events-none">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 md:h-5 md:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Buscar por nombre, descripción o tipo..." 
                                   class="block w-full pl-7 sm:pl-10 md:pl-12 pr-3 sm:pr-4 py-2 sm:py-2.5 md:py-3 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                        </div>
                    </div>
                    
                    <div class="flex gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                        <button type="submit" 
                                class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-[#9d2449] text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Buscar</span>
                        </button>
                        <a href="{{ route('documentos.index') }}" 
                           class="flex-1 lg:flex-none px-2 sm:px-3 md:px-4 lg:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 flex items-center justify-center gap-1 sm:gap-2">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="hidden sm:inline">Limpiar</span>
                        </a>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="border-t border-gray-100 pt-3 sm:pt-4 md:pt-5 lg:pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3 mb-3 sm:mb-4 md:mb-5">
                        <div class="flex items-center gap-1.5 sm:gap-2 md:gap-3">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm md:text-base font-medium text-gray-700">Filtros avanzados</span>
                        </div>
                        <button type="button" 
                                id="toggleFilters" 
                                class="text-xs sm:text-sm md:text-base text-[#9d2449] hover:text-[#8a1f40] font-medium flex items-center gap-1 transition-colors self-start sm:self-auto">
                            <span id="filterText">Mostrar filtros</span>
                            <svg id="filterIcon" class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div id="filtersContainer" class="hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 md:gap-4 lg:gap-6">
                            <div>
                                <label for="tipo_archivo" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Tipo de archivo</label>
                                <select name="tipo_archivo" 
                                        id="tipo_archivo" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los tipos</option>
                                    <option value="pdf" {{ request('tipo_archivo') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                    <option value="png" {{ request('tipo_archivo') == 'png' ? 'selected' : '' }}>PNG</option>
                                    <option value="jpg" {{ request('tipo_archivo') == 'jpg' ? 'selected' : '' }}>JPG</option>
                                    <option value="jpeg" {{ request('tipo_archivo') == 'jpeg' ? 'selected' : '' }}>JPEG</option>
                                    <option value="doc" {{ request('tipo_archivo') == 'doc' ? 'selected' : '' }}>DOC</option>
                                    <option value="docx" {{ request('tipo_archivo') == 'docx' ? 'selected' : '' }}>DOCX</option>
                                    <option value="xls" {{ request('tipo_archivo') == 'xls' ? 'selected' : '' }}>XLS</option>
                                    <option value="xlsx" {{ request('tipo_archivo') == 'xlsx' ? 'selected' : '' }}>XLSX</option>
                                </select>
                            </div>

                            <div>
                                <label for="tipo_persona" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Tipo de persona</label>
                                <select name="tipo_persona" 
                                        id="tipo_persona" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos los tipos</option>
                                    <option value="Física" {{ request('tipo_persona') == 'Física' ? 'selected' : '' }}>Persona Física</option>
                                    <option value="Moral" {{ request('tipo_persona') == 'Moral' ? 'selected' : '' }}>Persona Moral</option>
                                    <option value="Ambas" {{ request('tipo_persona') == 'Ambas' ? 'selected' : '' }}>Ambas</option>
                                </select>
                            </div>

                            <div>
                                <label for="es_visible" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Estado</label>
                                <select name="es_visible" 
                                        id="es_visible" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('es_visible') == '1' ? 'selected' : '' }}>Visible</option>
                                    <option value="0" {{ request('es_visible') == '0' ? 'selected' : '' }}>Oculto</option>
                                </select>
                            </div>

                            <div>
                                <label for="orden" class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-1.5 md:mb-2">Ordenar por</label>
                                <select name="orden" 
                                        id="orden" 
                                        class="w-full px-2 sm:px-3 md:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200">
                                    <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre</option>
                                    <option value="tipo_archivo" {{ request('orden') == 'tipo_archivo' ? 'selected' : '' }}>Tipo de archivo</option>
                                    <option value="tipo_persona" {{ request('orden') == 'tipo_persona' ? 'selected' : '' }}>Tipo de persona</option>
                                    <option value="created_at" {{ request('orden') == 'created_at' ? 'selected' : '' }}>Fecha de creación</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Botones de filtros -->
                        <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-3 sm:mt-4 md:mt-5 pt-3 sm:pt-4 border-t border-gray-100">
                            <button type="submit" 
                                    class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 bg-[#9d2449] text-white text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200">
                                Aplicar filtros
                            </button>
                            <a href="{{ route('documentos.index') }}" 
                               class="w-full sm:w-auto px-3 sm:px-4 md:px-6 py-2 sm:py-2.5 md:py-3 bg-gray-50 text-gray-700 text-xs sm:text-sm md:text-base font-medium rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 border border-gray-200 text-center">
                                Limpiar filtros
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Filtros Activos -->
        @if(request()->hasAny(['search', 'tipo_archivo', 'tipo_persona', 'es_visible', 'orden']))
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-2 sm:p-3 md:p-4 lg:p-5 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
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

                @if(request('tipo_archivo'))
                <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800">
                    Tipo: {{ strtoupper(request('tipo_archivo')) }}
                    <a href="{{ request()->fullUrlWithQuery(['tipo_archivo' => null]) }}" class="ml-1 sm:ml-1.5 text-blue-800 hover:text-blue-600">
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </span>
                @endif

                @if(request('tipo_persona'))
                <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-green-100 text-green-800">
                    Persona: {{ request('tipo_persona') }}
                    <a href="{{ request()->fullUrlWithQuery(['tipo_persona' => null]) }}" class="ml-1 sm:ml-1.5 text-green-800 hover:text-green-600">
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </span>
                @endif

                @if(request('es_visible') !== null)
                <span class="inline-flex items-center px-1.5 sm:px-2 md:px-2.5 py-0.5 sm:py-1 md:py-1.5 rounded-full text-xs sm:text-sm font-medium bg-yellow-100 text-yellow-800">
                    Estado: {{ request('es_visible') == '1' ? 'Visible' : 'Oculto' }}
                    <a href="{{ request()->fullUrlWithQuery(['es_visible' => null]) }}" class="ml-1 sm:ml-1.5 text-yellow-800 hover:text-yellow-600">
                        <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </span>
                @endif
            </div>
        </div>
        @endif

        <!-- Tabla Desktop -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden hidden xl:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Documento</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Tipo de Archivo</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Tipo de Persona</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-2 sm:py-3 md:py-4 text-left text-xs sm:text-sm font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($documentos as $documento)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-2 sm:space-x-3 md:space-x-4">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                                        @php
                                            $iconClass = match (strtolower($documento->tipo_archivo)) {
                                                'pdf' => 'fas fa-file-pdf text-white',
                                                'png', 'jpg', 'jpeg' => 'fas fa-file-image text-white',
                                                'doc', 'docx' => 'fas fa-file-word text-white',
                                                'xls', 'xlsx' => 'fas fa-file-excel text-white',
                                                default => 'fas fa-file text-white',
                                            };
                                        @endphp
                                        <i class="{{ $iconClass }} text-xs sm:text-sm md:text-base"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 truncate max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl text-xs sm:text-sm md:text-base" title="{{ $documento->nombre }}">
                                            {{ $documento->nombre }}
                                        </div>
                                        <div class="text-xs sm:text-sm text-gray-500">ID: {{ $documento->id }}</div>
                                        @if($documento->descripcion)
                                        <div class="text-xs sm:text-sm text-gray-400 truncate max-w-xs">{{ $documento->descripcion }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 bg-gray-100 text-gray-800 rounded-full text-xs sm:text-sm font-medium font-mono">
                                    {{ strtoupper($documento->tipo_archivo) }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 bg-[#9d2449]/10 text-[#9d2449] rounded-full text-xs sm:text-sm font-medium">
                                    {{ $documento->tipo_persona === 'Física' ? 'Persona Física' : 
                                       ($documento->tipo_persona === 'Moral' ? 'Persona Moral' : 'Ambas') }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                @php
                                    $statusClass = $documento->es_visible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                @endphp
                                <span class="inline-flex items-center px-2 sm:px-2.5 md:px-3 py-1 sm:py-1.5 md:py-2 rounded-full text-xs sm:text-sm font-medium {{ $statusClass }}">
                                    {{ $documento->es_visible ? 'Visible' : 'Oculto' }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-3 sm:py-4 md:py-5">
                                <div class="flex items-center space-x-1 sm:space-x-2 md:space-x-3">
                                    <a href="{{ route('documentos.show', $documento->id) }}" 
                                       class="inline-flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                       title="Ver detalles">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('documentos.edit', $documento->id) }}" 
                                       class="inline-flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                       title="Editar">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 py-8 sm:py-10 md:py-12 lg:py-16 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 mx-auto mb-2 sm:mb-3 md:mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-xs sm:text-sm md:text-base lg:text-lg">No hay documentos registrados</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cards Mobile y Tablet -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:hidden gap-2 sm:gap-3 md:gap-4 lg:gap-6">
            @forelse($documentos as $documento)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 sm:p-3 md:p-4 lg:p-5">
                <div class="flex items-start justify-between mb-2 sm:mb-3 md:mb-4">
                    <div class="flex items-center space-x-1.5 sm:space-x-2 md:space-x-3 lg:space-x-4 min-w-0 flex-1">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 lg:w-8 lg:h-8 bg-[#9d2449] rounded-lg flex items-center justify-center flex-shrink-0">
                            @php
                                $iconClass = match (strtolower($documento->tipo_archivo)) {
                                    'pdf' => 'fas fa-file-pdf text-white',
                                    'png', 'jpg', 'jpeg' => 'fas fa-file-image text-white',
                                    'doc', 'docx' => 'fas fa-file-word text-white',
                                    'xls', 'xlsx' => 'fas fa-file-excel text-white',
                                    default => 'fas fa-file text-white',
                                };
                            @endphp
                            <i class="{{ $iconClass }} text-xs sm:text-sm md:text-base lg:text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-gray-700 font-medium text-xs sm:text-sm md:text-base lg:text-lg block">ID: {{ $documento->id }}</span>
                            <p class="text-xs sm:text-sm md:text-base text-gray-500 truncate">{{ strtoupper($documento->tipo_archivo) }}</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-1 sm:ml-2 md:ml-3">
                        @php
                            $statusClass = $documento->es_visible ? 'text-green-800 bg-green-100' : 'text-red-800 bg-red-100';
                        @endphp
                        <span class="px-1 sm:px-1.5 md:px-2 lg:px-2.5 py-0.5 sm:py-1 md:py-1.5 text-xs sm:text-sm md:text-base font-medium rounded-full {{ $statusClass }} whitespace-nowrap">
                            {{ $documento->es_visible ? 'Visible' : 'Oculto' }}
                        </span>
                    </div>
                </div>
                <div class="space-y-1 sm:space-y-1.5 md:space-y-2 lg:space-y-3">
                    <div class="text-xs sm:text-sm md:text-base lg:text-lg font-semibold text-gray-800 truncate" title="{{ $documento->nombre }}">
                        {{ $documento->nombre }}
                    </div>
                    @if($documento->descripcion)
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 truncate">
                        {{ $documento->descripcion }}
                    </div>
                    @endif
                    <div class="text-xs sm:text-sm md:text-base text-gray-500">
                        <span class="inline-flex items-center px-2 py-1 bg-[#9d2449]/10 text-[#9d2449] rounded-full text-xs font-medium">
                            {{ $documento->tipo_persona === 'Física' ? 'Persona Física' : 
                               ($documento->tipo_persona === 'Moral' ? 'Persona Moral' : 'Ambas') }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-1.5 sm:space-x-2 md:space-x-3 pt-2 sm:pt-3 md:pt-4 mt-2 sm:mt-3 md:mt-4 border-t border-gray-100">
                    <a href="{{ route('documentos.show', $documento->id) }}" 
                       class="flex-1 text-center px-1.5 sm:px-2 md:px-3 lg:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 transition-colors duration-200 truncate">
                        Ver
                    </a>
                    <a href="{{ route('documentos.edit', $documento->id) }}" 
                       class="flex-1 text-center px-1.5 sm:px-2 md:px-3 lg:px-4 py-1.5 sm:py-2 md:py-2.5 text-xs sm:text-sm md:text-base font-medium text-green-600 bg-green-50 rounded-md hover:bg-green-100 transition-colors duration-200 truncate">
                        Editar
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 md:p-8 lg:p-10 text-center">
                <div class="text-gray-500">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 mx-auto mb-2 sm:mb-3 md:mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl">No hay documentos</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($documentos->hasPages() || $documentos->total() > 0)
        <div class="mt-4 sm:mt-5 md:mt-6 lg:mt-8 xl:mt-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5 lg:gap-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm px-2 sm:px-3 md:px-4 lg:px-5 py-2 sm:py-3 md:py-4">
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]">{{ $documentos->total() }}</span> 
                        {{ $documentos->total() == 1 ? 'documento encontrado' : 'documentos encontrados' }}
                        @if($documentos->hasPages())
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                ({{ $documentos->firstItem() }}-{{ $documentos->lastItem() }})
                            </span>
                        @endif
                    </p>
                </div>

                @if($documentos->hasPages())
                <div class="flex justify-center sm:justify-end">
                    <div class="text-xs sm:text-sm md:text-base">
                        {{ $documentos->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

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
    
    if ({{ request()->hasAny(['tipo_archivo', 'tipo_persona', 'es_visible', 'orden']) ? 'true' : 'false' }}) {
        container?.classList.remove('hidden');
        if (text) text.textContent = 'Ocultar filtros';
        icon?.classList.add('rotate-180');
    }
    
    toggle?.addEventListener('click', function() {
        const hidden = container?.classList.contains('hidden');
        container?.classList.toggle('hidden');
        if (text) text.textContent = hidden ? 'Ocultar filtros' : 'Mostrar filtros';
        icon?.classList.toggle('rotate-180');
    });
});
</script>
@endpush 