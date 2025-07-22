@extends('errors.layout')

@section('code', '404')
@section('title', 'Página No Encontrada')

@section('header-message')
    Página no encontrada <span class="sparkle">🔍</span>
@endsection

@section('content')
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        La página que buscas no está disponible actualmente. 
        <br><span class="highlight-text">Esto puede suceder</span> por varios motivos que detallamos a continuación.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">Sugerencias:</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>Verifica que la URL esté escrita correctamente</li>
            <li>La página pudo haber sido movida o eliminada</li>
            <li>El enlace que seguiste podría estar desactualizado</li>
        </ul>
    </div>
@endsection

@section('buttons')
    <button onclick="window.history.back()" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#9d2449] to-[#7a1d37] hover:from-[#7a1d37] hover:to-[#9d2449] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="relative">Regresar</span>
    </button>
    
    <a href="{{ route('dashboard') }}" 
       class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-[#9d2449] bg-white border border-[#9d2449] hover:bg-[#fdf2f5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="relative">Ir al inicio</span>
    </a>
@endsection