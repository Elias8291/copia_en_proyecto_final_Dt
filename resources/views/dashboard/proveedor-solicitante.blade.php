@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Principal -->
        <div class="mb-12">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-[#9d2449] to-[#be185d] px-8 py-12">
                    <div class="flex flex-col lg:flex-row items-center justify-between">
                        <div class="flex-1 text-center lg:text-left mb-8 lg:mb-0">
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-4">
                                Bienvenido, {{ auth()->user()->nombre }}
                            </h1>
                            <p class="text-lg text-white/90 mb-8">
                                @if (auth()->user()->hasRole('Proveedor'))
                                    Sistema de Gestión del Padrón de Proveedores del Estado de Oaxaca
                                @else
                                    Portal de Solicitudes para Proveedores del Estado de Oaxaca
                                @endif
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                @if (auth()->user()->hasRole('Proveedor'))
                                    <a href="{{ route('mi-estado') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-white text-[#9d2449] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Consultar Estado
                                    </a>
                                @else
                                    <a href="{{ route('tramites.index') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-white text-[#9d2449] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Iniciar Trámite
                                    </a>
                                @endif
                                <a href="{{ route('citas.index') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-white/10 text-white font-medium rounded-lg hover:bg-white/20 transition-colors duration-200 border border-white/20">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Gestionar Citas
                                </a>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <img src="/images/mujer_bienvenida.png" alt="Asistente Virtual" 
                                 class="w-auto h-48 lg:h-64 object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @if (auth()->user()->hasRole('Proveedor'))
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="window.location.href='{{ route('mi-estado') }}'">
                    <div class="flex items-center space-x-4">
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Estado del Proveedor</h3>
                            <p class="text-sm text-gray-600">Consulta tu estatus actual</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="window.location.href='{{ route('tramites.index') }}'">
                    <div class="flex items-center space-x-4">
                        <div class="bg-[#9d2449]/10 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Nuevo Trámite</h3>
                            <p class="text-sm text-gray-600">Registra tu solicitud</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="window.location.href='{{ route('tramites.index') }}'">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Mis Trámites</h3>
                        <p class="text-sm text-gray-600">Gestiona tus solicitudes</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="window.location.href='{{ route('citas.index') }}'">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">Mis Citas</h3>
                        <p class="text-sm text-gray-600">Programa tus citas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de Soporte -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información de Soporte</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">¿Necesitas Ayuda?</h3>
                    <p class="text-gray-600 mb-4">Consulta nuestra guía de trámites para obtener información detallada sobre el proceso.</p>
                    <a href="#" class="text-[#9d2449] font-medium hover:text-[#be185d] transition-colors duration-200">Ver Guía de Trámites →</a>
                </div>
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Notificaciones</h3>
                    <p class="text-gray-600 mb-4">Mantente informado sobre el progreso de tus trámites a través de notificaciones.</p>
                    <a href="{{ route('notificaciones.index') }}" class="text-[#9d2449] font-medium hover:text-[#be185d] transition-colors duration-200">Ver Notificaciones →</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
