@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        
        <!-- Tarjeta Principal de Bienvenida -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-[#9d2449] to-[#be185d] px-8 py-10">
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div class="flex-1 text-center lg:text-left mb-6 lg:mb-0">
                        <h1 class="text-2xl lg:text-3xl font-bold text-white mb-3">
                            Bienvenido, {{ auth()->user()->nombre }}
                        </h1>
                        <p class="text-white/90 mb-6">
                            @if (auth()->user()->hasRole('Proveedor'))
                                Sistema de Gestión del Padrón de Proveedores del Estado de Oaxaca
                            @else
                                Portal de Solicitudes para Proveedores del Estado de Oaxaca
                            @endif
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                            @if (auth()->user()->hasRole('Proveedor'))
                                <a href="{{ route('mi-estado') }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-white text-[#9d2449] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Consultar Estado
                                </a>
                            @else
                                <a href="{{ route('tramites.index') }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-white text-[#9d2449] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Iniciar Trámite
                                </a>
                            @endif
                            <a href="{{ route('citas.index') }}" 
                               class="inline-flex items-center px-5 py-2.5 bg-white/10 text-white font-medium rounded-lg hover:bg-white/20 transition-colors duration-200 border border-white/20">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Gestionar Citas
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <img src="/images/mujer_bienvenida.png" alt="Asistente Virtual" 
                             class="w-auto h-32 lg:h-40 object-contain">
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Acciones Principales -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Acciones Principales</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if (auth()->user()->hasRole('Proveedor'))
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-md transition-all duration-200 cursor-pointer border border-green-200" onclick="window.location.href='{{ route('mi-estado') }}'">
                        <div class="flex items-center space-x-4">
                            <div class="bg-green-500 p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-green-900">Estado del Proveedor</h3>
                                <p class="text-sm text-green-700">Consulta tu estatus actual</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-br from-[#9d2449]/10 to-[#be185d]/10 rounded-xl p-6 hover:shadow-md transition-all duration-200 cursor-pointer border border-[#9d2449]/20" onclick="window.location.href='{{ route('tramites.index') }}'">
                        <div class="flex items-center space-x-4">
                            <div class="bg-[#9d2449] p-3 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-[#9d2449]">Nuevo Trámite</h3>
                                <p class="text-sm text-[#9d2449]/80">Registra tu solicitud</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-md transition-all duration-200 cursor-pointer border border-blue-200" onclick="window.location.href='{{ route('tramites.index') }}'">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-500 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900">Mis Trámites</h3>
                            <p class="text-sm text-blue-700">Gestiona tus solicitudes</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-md transition-all duration-200 cursor-pointer border border-purple-200" onclick="window.location.href='{{ route('citas.index') }}'">
                    <div class="flex items-center space-x-4">
                        <div class="bg-purple-500 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-purple-900">Mis Citas</h3>
                            <p class="text-sm text-purple-700">Programa tus citas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Información de Soporte -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Información de Soporte</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-start space-x-4">
                        <div class="bg-[#9d2449] p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">¿Necesitas Ayuda?</h3>
                            <p class="text-gray-600 mb-4 text-sm">Consulta nuestra guía de trámites para obtener información detallada sobre el proceso.</p>
                            <a href="#" class="text-[#9d2449] font-medium hover:text-[#be185d] transition-colors duration-200 text-sm">Ver Guía de Trámites →</a>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-start space-x-4">
                        <div class="bg-[#9d2449] p-3 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h10v-2H4v2zM4 11h14v-2H4v2zM4 7h18v-2H4v2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Notificaciones</h3>
                            <p class="text-gray-600 mb-4 text-sm">Mantente informado sobre el progreso de tus trámites a través de notificaciones.</p>
                            <a href="{{ route('notificaciones.index') }}" class="text-[#9d2449] font-medium hover:text-[#be185d] transition-colors duration-200 text-sm">Ver Notificaciones →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
