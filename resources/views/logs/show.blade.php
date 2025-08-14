@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Detalles del Log</h1>
                        <p class="text-base text-gray-500 mt-1">Información completa del registro</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logs.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información principal -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">ID:</span>
                                <span class="text-sm text-gray-900 font-mono">{{ $log->id }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Nivel:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->level_color }}">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $log->level_icon }}"/>
                                    </svg>
                                    {{ ucfirst($log->level) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Canal:</span>
                                <span class="text-sm text-gray-900">{{ $log->channel ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Fecha:</span>
                                <span class="text-sm text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Usuario:</span>
                                <div class="text-right">
                                    @if($log->user)
                                        <div class="text-sm text-gray-900">{{ $log->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                    @else
                                        <span class="text-sm text-gray-500">Sistema</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Red</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Dirección IP:</span>
                                <span class="text-sm text-gray-900 font-mono">{{ $log->ip_address ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Método HTTP:</span>
                                <span class="text-sm text-gray-900 font-mono">{{ $log->method ?? 'N/A' }}</span>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-700 block mb-2">URL:</span>
                                <div class="text-sm text-gray-900 font-mono break-all bg-white p-2 rounded border">
                                    {{ $log->url ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-gray-700 block mb-2">User Agent:</span>
                                <div class="text-sm text-gray-900 font-mono break-all bg-white p-2 rounded border max-h-20 overflow-y-auto">
                                    {{ $log->user_agent ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje y contexto -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mensaje</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $log->message }}</div>
                        </div>
                    </div>

                    @if($log->context)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contexto</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <pre class="text-sm text-gray-900 whitespace-pre-wrap overflow-x-auto">{{ $log->context_formatted }}</pre>
                        </div>
                    </div>
                    @endif

                    <!-- Información adicional -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Adicional</h3>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Creado:</span>
                                <span class="text-sm text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Actualizado:</span>
                                <span class="text-sm text-gray-900">{{ $log->updated_at->format('d/m/Y H:i:s') }}</span>
                            </div>
                            
                            @if($log->user)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">ID Usuario:</span>
                                <span class="text-sm text-gray-900 font-mono">{{ $log->user_id }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('logs.index') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500/50 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a la lista
                    </a>
                    
                    @can('logs.exportar')
                    <a href="{{ route('logs.exportar') }}?search={{ urlencode($log->message) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Exportar similares
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
