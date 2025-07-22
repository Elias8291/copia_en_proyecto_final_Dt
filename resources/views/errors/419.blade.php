@extends('errors.layout')

@section('code', '419')
@section('title', 'Página Expirada')

@section('custom-styles')
    @keyframes clockTick {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(6deg); }
    }
    .clock-icon {
        animation: clockTick 1s ease-in-out infinite;
    }
@endsection

@section('header-message')
    Sesión expirada <span class="sparkle clock-icon">⏰</span>
@endsection

@section('content')
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        Su sesión ha expirado por motivos de seguridad. 
        <br><span class="highlight-text">Esto es normal</span> cuando se permanece inactivo durante un período prolongado.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">¿Por qué pasó esto?</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>La página estuvo abierta durante mucho tiempo</li>
            <li>El token de seguridad ha expirado</li>
            <li>Es una medida de protección automática del sistema</li>
        </ul>
        
        <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
            <p class="text-amber-800 font-medium text-xs flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Su información no se ha perdido, simplemente recargue la página
            </p>
        </div>
    </div>
@endsection

@section('buttons')
    <button onclick="window.location.reload()" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#9d2449] to-[#7a1d37] hover:from-[#7a1d37] hover:to-[#9d2449] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span class="relative">Recargar página</span>
    </button>
    
    <button onclick="window.history.back()" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-[#9d2449] bg-white border border-[#9d2449] hover:bg-[#fdf2f5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="relative">Página anterior</span>
    </button>
@endsection