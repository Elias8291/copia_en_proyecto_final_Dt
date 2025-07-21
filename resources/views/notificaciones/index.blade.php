@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Main Container -->
        <div class="rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200/70">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Centro de Notificaciones</h1>
                            <p class="text-sm text-gray-500">Gestiona todas tus notificaciones del sistema.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <button class="px-3 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Marcar todas como leídas
                        </button>
                        <button class="px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                            Eliminar leídas
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="p-6 bg-gray-50/50 border-b border-gray-200/70">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="search-filter" class="text-sm font-medium text-gray-700 mb-1 sr-only">Buscar</label>
                        <div class="relative">
                            <input type="text" id="search-filter" class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-lg bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-300 shadow-sm" placeholder="Buscar en notificaciones...">
                            <svg class="absolute inset-y-0 right-0 w-5 h-5 text-gray-400 mr-3 mt-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                    <div>
                        <label for="status-filter" class="text-sm font-medium text-gray-700 mb-1 sr-only">Estado</label>
                        <select id="status-filter" class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-300">
                            <option value="">Todas</option>
                            <option value="no_leidas">No leídas</option>
                            <option value="leidas">Leídas</option>
                        </select>
                    </div>
                    <div>
                        <label for="type-filter" class="text-sm font-medium text-gray-700 mb-1 sr-only">Tipo</label>
                        <select id="type-filter" class="block w-full pl-4 pr-10 py-2.5 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-all duration-300">
                            <option value="">Todos los tipos</option>
                            <option value="informativo">Informativo</option>
                            <option value="advertencia">Advertencia</option>
                            <option value="error">Error</option>
                            <option value="exito">Éxito</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-200/70">
                @php
                $notificaciones = [
                    ['id' => 1, 'leida' => false, 'tipo' => 'exito', 'mensaje' => 'Tu trámite de inscripción ha sido aprobado.', 'fecha' => 'hace 5 minutos'],
                    ['id' => 2, 'leida' => false, 'tipo' => 'advertencia', 'mensaje' => 'Tu constancia fiscal está a punto de expirar. Por favor, actualízala.', 'fecha' => 'hace 2 horas'],
                    ['id' => 3, 'leida' => true, 'tipo' => 'informativo', 'mensaje' => 'Se ha añadido un nuevo requisito de documentación para el trámite de renovación.', 'fecha' => 'ayer'],
                    ['id' => 4, 'leida' => true, 'tipo' => 'error', 'mensaje' => 'No se pudo procesar el último documento subido. Inténtalo de nuevo.', 'fecha' => 'hace 3 días'],
                ];
                @endphp

                @forelse ($notificaciones as $notificacion)
                <div class="p-6 flex items-start space-x-4 transition-colors duration-200 {{ $notificacion['leida'] ? 'bg-gray-50/50' : 'bg-white hover:bg-gray-50' }}">
                    <!-- Icon -->
                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center 
                        @switch($notificacion['tipo'])
                            @case('exito') bg-emerald-100 @break
                            @case('advertencia') bg-amber-100 @break
                            @case('error') bg-red-100 @break
                            @default bg-blue-100
                        @endswitch">
                        <svg class="w-5 h-5 
                            @switch($notificacion['tipo'])
                                @case('exito') text-emerald-600 @break
                                @case('advertencia') text-amber-600 @break
                                @case('error') text-red-600 @break
                                @default text-blue-600
                            @endswitch"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @switch($notificacion['tipo'])
                                @case('exito') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/> @break
                                @case('advertencia') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/> @break
                                @case('error') <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/> @break
                                @default <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endswitch
                        </svg>
                    </div>

                    <!-- Content -->
                    <div class="flex-grow">
                        <p class="text-sm text-gray-800 {{ !$notificacion['leida'] ? 'font-semibold' : '' }}">{{ $notificacion['mensaje'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notificacion['fecha'] }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        @if(!$notificacion['leida'])
                            <button title="Marcar como leída" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        @endif
                        <button title="Archivar" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @if(!$notificacion['leida'])
                            <div class="w-2.5 h-2.5 bg-blue-500 rounded-full flex-shrink-0" title="No leída"></div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-800">Todo en orden</h3>
                    <p class="mt-1 text-sm text-gray-500">No tienes notificaciones nuevas.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="p-6 bg-white border-t border-gray-200/70">
                {{-- Aquí iría la paginación de Laravel --}}
                {{-- $notificaciones->links() --}}
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <p>Mostrando 1 a 4 de 12 resultados</p>
                    <div class="flex items-center space-x-1">
                        <a href="#" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Anterior</a>
                        <a href="#" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">Siguiente</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lógica para filtros y acciones de notificaciones
});
</script>
@endpush
