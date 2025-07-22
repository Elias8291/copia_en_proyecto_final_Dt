@extends('errors.layout')

@section('code', '405')
@section('title', 'Método No Permitido')

@section('header-message')
    Método no permitido <span class="sparkle">🚫</span>
@endsection

@section('content')
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        El método HTTP utilizado no está permitido para esta ruta. 
        <br><span class="highlight-text">Verifica el método</span> de la solicitud que estás realizando.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">Métodos comunes:</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li><strong>GET:</strong> Para obtener información</li>
            <li><strong>POST:</strong> Para enviar datos</li>
            <li><strong>PUT/PATCH:</strong> Para actualizar recursos</li>
            <li><strong>DELETE:</strong> Para eliminar recursos</li>
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
@endsection