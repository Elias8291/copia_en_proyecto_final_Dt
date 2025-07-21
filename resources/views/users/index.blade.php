@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
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

        <!-- Contenedor Principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
            <!-- Encabezado -->
            <div class="p-6 border-b border-gray-200/70">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
                            <p class="text-sm text-gray-500">Administra y controla los usuarios del sistema.</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('users.create') }}" 
                           class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Crear Usuario
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filtros Dinámicos -->
            <div class="p-6 bg-gray-50/50 border-b border-gray-200/70">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800">Filtros</h3>
                    <div id="results-count" class="ml-auto text-sm text-gray-500"></div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Búsqueda -->
                    <div class="sm:col-span-2">
                        <label for="search-filter" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <div class="relative">
                            <input type="text" id="search-filter" 
                                   class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-300 shadow-sm hover:shadow-md"
                                   placeholder="Nombre, email o RFC...">
                            <svg class="absolute inset-y-0 right-0 w-5 h-5 text-gray-400 mr-3 mt-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select id="status-filter" class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-300 hover:shadow-md">
                            <option value="">Todos</option>
                            <option value="verified">Verificado</option>
                            <option value="pending">Pendiente</option>
                        </select>
                    </div>

                    <!-- Rol -->
                    <div>
                        <label for="role-filter" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                        <select id="role-filter" class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-300 hover:shadow-md">
                            <option value="">Todos</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botón limpiar filtros -->
                <div class="mt-4 flex justify-end">
                    <button id="clear-filters" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E] transition-all duration-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <!-- Contenido de la tabla -->
            <div id="table-container" class="overflow-hidden relative">
                @include('users.partials.table')
            </div>


        </div>
    </div>
</div>

<!-- Modales de Confirmación -->
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

@push('scripts')
<script src="{{ asset('js/users-dynamic-filter.js') }}"></script>
@endpush

@endsection 