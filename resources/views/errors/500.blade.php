@extends('errors.layout')

@section('code', '500')
@section('title', 'Error Interno del Servidor')

@section('header-message')
    Error interno del servidor <span class="sparkle">⚠️</span>
@endsection

@section('content')
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        Se ha producido un error interno en el servidor. 
        <br><span class="highlight-text">Nuestro equipo técnico</span> está trabajando para resolver esta incidencia.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">¿Qué puedes hacer?</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>Intenta recargar la página en unos minutos</li>
            <li>Si el problema persiste, contacta a soporte</li>
            <li>Regresa al dashboard para continuar</li>
        </ul>
    </div>
@endsection

@section('buttons')
    <button onclick="window.location.reload()" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#9d2449] to-[#7a1d37] hover:from-[#7a1d37] hover:to-[#9d2449] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <span class="relative">Reintentar</span>
    </button>
    
    <a href="{{ route('dashboard') }}" 
       class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-[#9d2449] bg-white border border-[#9d2449] hover:bg-[#fdf2f5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="relative">Ir al dashboard</span>
    </a>
@endsection