@extends('errors.layout')

@section('code', '429')
@section('title', 'Demasiadas Solicitudes')

@section('custom-styles')
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .pulse-icon {
        animation: pulse 2s ease-in-out infinite;
    }
@endsection

@section('header-message')
    Demasiadas solicitudes <span class="sparkle pulse-icon">⏳</span>
@endsection

@section('content')
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        Has realizado demasiadas solicitudes en poco tiempo. 
        <br><span class="highlight-text">Por favor espera</span> unos momentos antes de intentar nuevamente.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">¿Por qué sucede esto?</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>Es una medida de protección contra spam</li>
            <li>Ayuda a mantener el rendimiento del servidor</li>
            <li>Protege contra ataques automatizados</li>
        </ul>
        
        <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-blue-800 font-medium text-xs flex items-center">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                Espera 1 minuto y podrás continuar normalmente
            </p>
        </div>
    </div>
@endsection

@section('buttons')
    <button onclick="setTimeout(() => window.location.reload(), 60000)" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#9d2449] to-[#7a1d37] hover:from-[#7a1d37] hover:to-[#9d2449] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="relative">Esperar 1 min</span>
    </button>
    
    <button onclick="window.history.back()" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-[#9d2449] bg-white border border-[#9d2449] hover:bg-[#fdf2f5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="relative">Regresar</span>
    </button>
@endsection