@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Notificaciones -->
        @if(session('success') || session('error'))
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-sm">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                 class="bg-white rounded-lg shadow-lg border-l-4 border-emerald-500 p-4">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-900">{{ session('success') }}</p>
                    <button @click="show = false" class="ml-auto text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                 class="bg-white rounded-xl shadow-lg border-l-4 border-red-500 p-4">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-900">{{ session('error') }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Gestión de Usuarios</h1>
                        <p class="text-sm text-slate-600 mt-1">Administra y controla los usuarios del sistema</p>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('users.create') }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#9D2449] to-[#B91C1C] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#9D2449]/50 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Crear Usuario
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-8">
            <!-- Barra de búsqueda principal -->
            <div class="mb-6">
                <div class="relative max-w-2xl mx-auto group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <div class="relative">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-[#9D2449] transition-colors duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <div class="absolute -inset-1 bg-gradient-to-r from-[#9D2449]/20 to-[#B91C1C]/20 rounded-full opacity-0 group-focus-within:opacity-100 transition-opacity duration-200 blur-sm">
                            </div>
                        </div>
                    </div>
                    <input type="text" id="search-filter"
                        class="block w-full pl-12 pr-4 py-3.5 text-sm border border-gray-300 rounded-xl bg-white/80 backdrop-blur-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#9D2449]/30 focus:border-[#9D2449] transition-all duration-300 shadow-sm hover:shadow-md hover:bg-white group-focus-within:shadow-lg"
                        placeholder="Buscar usuarios por nombre, email o RFC...">
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-[#9D2449]/5 to-[#B91C1C]/5 opacity-0 group-focus-within:opacity-100 transition-opacity duration-200 pointer-events-none">
                    </div>
                </div>
            </div>

            <!-- Filtros compactos -->
            <div class="flex flex-wrap items-center justify-center gap-3 mb-4">
                <!-- Estado de Verificación -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <select id="verification-filter"
                        class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#9D2449]/30 focus:border-[#9D2449] transition-all duration-200 hover:shadow-sm min-w-[140px]">
                        <option value="">Estado</option>
                        <option value="verificado">Verificado</option>
                        <option value="pendiente">Pendiente</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Rol -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                        </svg>
                    </div>
                    <select id="role-filter"
                        class="appearance-none bg-white border border-gray-300 rounded-lg pl-10 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#9D2449]/30 focus:border-[#9D2449] transition-all duration-200 hover:shadow-sm min-w-[120px]">
                        <option value="">Rol</option>
                        @if(isset($roles))
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Botón limpiar -->
                <button id="clear-filters"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#9D2449]/30 transition-all duration-200 hover:shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Limpiar
                </button>
            </div>

            <!-- Contador de resultados -->
            <div class="text-center">
                <div id="results-count"
                    class="text-sm text-gray-600 font-medium bg-white/60 backdrop-blur-sm rounded-full px-4 py-1 inline-block border border-gray-200/50">
                </div>
            </div>
        </div>

        <!-- Contenido de la tabla -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 overflow-hidden">
            <div id="table-container" class="overflow-hidden relative">
                @include('users.partials.table')
            </div>
        </div>

        <!-- Información adicional -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Estadísticas -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    Estadísticas del Sistema
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Total de usuarios</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $users->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Usuarios verificados</span>
                        <span class="text-sm font-semibold text-emerald-600">{{ $users->where('verification', 1)->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Pendientes de verificación</span>
                        <span class="text-sm font-semibold text-amber-600">{{ $users->where('verification', 0)->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Último registro</span>
                        <span class="text-sm font-semibold text-slate-800">
                            {{ $users->sortByDesc('created_at')->first()?->created_at?->diffForHumans() ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    Acciones Rápidas
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('users.create') }}" 
                       class="flex items-center p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-[#9D2449] rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-800">Crear nuevo usuario</div>
                            <div class="text-xs text-slate-500">Agregar usuario al sistema</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('roles.index') }}" 
                       class="flex items-center p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-800">Gestionar roles</div>
                            <div class="text-xs text-slate-500">Administrar roles y permisos</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales de Confirmación -->
@if(isset($users))
@foreach($users as $user)
    @if($user->id !== auth()->id())
    <x-modal name="confirm-user-deletion-{{ $user->id }}" focusable maxWidth="md">
        <div class="p-6">
            <div class="flex items-center justify-center space-x-4 mb-6">
                <div class="flex-shrink-0 h-12 w-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
                    <p class="mt-2 text-sm text-gray-500">¿Estás seguro de que deseas eliminar al usuario "{{ $user->nombre }}"? Esta acción no se puede deshacer.</p>
                </div>
            </div>
            <div class="mt-6 flex justify-center space-x-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors duration-200">
                    Cancelar
                </button>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                        Eliminar Usuario
                    </button>
                </form>
            </div>
        </div>
    </x-modal>
    @endif
@endforeach
@endif

<script src="{{ asset('js/tablas/filtros.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TablaFiltros({
            entityName: 'usuarios',
            messages: {
                noResults: 'No se encontraron usuarios',
                results: 'usuario',
                results_plural: 'usuarios'
            },
            filtros: [{
                    id: 'search-filter',
                    columna: 1,
                    tipo: 'contains',
                    label: 'texto'
                },
                {
                    id: 'verification-filter',
                    columna: 2,
                    selector: 'span',
                    tipo: 'contains',
                    label: 'estado'
                },
                {
                    id: 'role-filter',
                    columna: 3,
                    selector: 'span',
                    tipo: 'contains',
                    label: 'rol'
                }
            ]
        });
    });
</script>

@endsection 