@extends('layouts.app')

@section('title', 'Historial de Trámites')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/30 to-purple-50/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Principal -->
        <div class="w-full max-w-7xl mx-auto bg-white shadow-md rounded-xl overflow-hidden border border-gray-200/70 p-8 -mt-4 mb-8">
            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                <div class="p-6 border-b border-gray-200/70">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Historial de Trámites</h1>
                                <p class="text-base text-gray-500 mt-1">Consulte sus trámites aprobados y completados</p>
                            </div>
                        </div>
                        
                        <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:from-primary-dark hover:to-primary transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nuevo Trámite
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Trámites -->
        <div class="grid gap-6">
            @forelse($tramites as $tramite)
                @php
                    $estadoColor = match ($tramite->estado) {
                        'Pendiente' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'Por_Cotejar' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'En_Revision' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'Aprobado' => 'bg-green-50 text-green-700 border-green-200',
                        'Rechazado' => 'bg-red-50 text-red-700 border-red-200',
                        'Cancelado' => 'bg-gray-50 text-gray-700 border-gray-200',
                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                    };
                    
                    $estadoIcon = match ($tramite->estado) {
                        'Pendiente' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        'Por_Cotejar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                        'En_Revision' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>',
                        'Aprobado' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                        'Rechazado' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                        'Cancelado' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                    };
                    
                    $iconColor = match ($tramite->estado) {
                        'Pendiente' => 'from-yellow-500 to-yellow-600',
                        'Por_Cotejar' => 'from-blue-500 to-blue-600',
                        'En_Revision' => 'from-orange-500 to-orange-600',
                        'Aprobado' => 'from-emerald-500 to-green-600',
                        'Rechazado' => 'from-red-500 to-red-600',
                        'Cancelado' => 'from-gray-500 to-gray-600',
                        default => 'from-gray-500 to-gray-600',
                    };
                @endphp
                
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br {{ $iconColor }} rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $estadoIcon !!}
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $tramite->tipo_tramite)) }} - #{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $tramite->created_at->format('d/m/Y H:i') }}
                                </p>
                                @if($tramite->observaciones)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ Str::limit($tramite->observaciones, 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $estadoColor }}">
                                {{ ucfirst(str_replace('_', ' ', $tramite->estado)) }}
                            </span>
                            
                            <a href="{{ route('tramites.detalles', $tramite->id) }}" 
                               class="inline-flex items-center px-3 py-1 bg-[#9d2449] text-white rounded-lg text-xs font-medium hover:bg-[#8a203f] transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Detalles
                            </a>
                            
                            @if($tramite->estado === 'Aprobado' && $tramite->oficios->count() > 0)
                                <a href="{{ route('tramites.oficio', $tramite->id) }}" class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Ver Oficio
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay trámites</h3>
                    <p class="text-gray-600 mb-4">Aún no ha realizado ningún trámite.</p>
                    <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary to-primary-dark text-white rounded-lg hover:from-primary-dark hover:to-primary transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Iniciar Trámite
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Paginación -->
        @if($tramites->hasPages())
            <div class="mt-8">
                {{ $tramites->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 