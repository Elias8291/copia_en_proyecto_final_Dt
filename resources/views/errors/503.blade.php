@extends('errors.layout')

@section('code', '503')
@section('title', 'Servicio No Disponible')

@section('custom-styles')
    @keyframes maintenance {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
    }
    .maintenance-icon {
        animation: maintenance 2s ease-in-out infinite;
    }
@endsection

@section('header-message')
    Servicio en mantenimiento <span class="sparkle maintenance-icon">🔧</span>
@endsection

@section('content')
    <p class="text-gray-700 text-sm sm:text-base leading-relaxed">
        El sitio web está temporalmente fuera de servicio por mantenimiento programado. 
        <br><span class="highlight-text">El servicio será restablecido</span> una vez completadas las mejoras del sistema.
    </p>
    
    <div class="mt-4 text-xs sm:text-sm text-gray-600">
        <p>
            <span class="font-medium">Durante este tiempo:</span>
        </p>
        <ul class="list-disc list-inside mt-2 space-y-1">
            <li>Estamos mejorando la experiencia del usuario</li>
            <li>Actualizando nuestros sistemas de seguridad</li>
            <li>Optimizando el rendimiento del sitio</li>
        </ul>
        
        <div class="mt-3 p-3 bg-[#fdf2f5] rounded-lg border border-[#fce7ec]">
            <p class="text-[#7a1d37] font-medium text-xs">
                ⏱️ Tiempo estimado: 30-60 minutos
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
        <span class="relative">Verificar estado</span>
    </button>
    
    <button onclick="setTimeout(() => window.location.reload(), 300000)" 
            class="btn-back inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-[#9d2449] bg-white border border-[#9d2449] hover:bg-[#fdf2f5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#9d2449] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="relative">Esperar 5 min</span>
    </button>
@endsection