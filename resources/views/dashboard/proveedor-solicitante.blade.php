@extends('layouts.app')

@section('content')
<div class="min-h-screen font-sans py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Dashboard Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            
            <!-- Dashboard Header -->
            <div class="bg-gradient-to-r from-[#9d2449] to-[#be185d] h-20 relative">
                <div class="absolute inset-0 bg-black/5"></div>
            </div>
            
            <div class="px-6 py-6 flex flex-col sm:flex-row items-center sm:justify-between -mt-10">
                <div class="flex flex-col sm:flex-row items-center text-center sm:text-left sm:space-x-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#9d2449] to-[#be185d] rounded-full flex items-center justify-center ring-4 ring-white shadow-lg flex-shrink-0">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}</span>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">Bienvenido, {{ auth()->user()->nombre }}</h1>
                        <p class="text-base text-gray-600">
                            @if (auth()->user()->hasRole('Proveedor'))
                                Sistema de Gestión del Padrón de Proveedores del Estado de Oaxaca
                            @else
                                Portal de Solicitudes para Proveedores del Estado de Oaxaca
                            @endif
                        </p>
                        <div class="flex items-center justify-center sm:justify-start mt-2 space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Miembro desde {{ auth()->user()->created_at->format('M Y') }}
                            </span>
                            <span class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                Activo
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                    @if (auth()->user()->hasRole('Proveedor'))
                        <a href="{{ route('mi-estado') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#9d2449] rounded-lg hover:bg-[#be185d] focus:ring-4 focus:outline-none focus:ring-[#9d2449]/30 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Consultar Estado
                        </a>
                    @else
                        <a href="{{ route('tramites.index') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#9d2449] rounded-lg hover:bg-[#be185d] focus:ring-4 focus:outline-none focus:ring-[#9d2449]/30 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Iniciar Trámite
                        </a>
                    @endif
                    <a href="{{ route('citas.index') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-[#9d2449] bg-white border border-[#9d2449] rounded-lg hover:bg-[#9d2449] hover:text-white focus:ring-4 focus:outline-none focus:ring-[#9d2449]/30 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Gestionar Citas
                    </a>
                </div>
            </div>

            <!-- Dashboard Actions Section -->
            <div class="px-6 pb-6">
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Acciones Principales</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if (auth()->user()->hasRole('Proveedor'))
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-md transition-all duration-200 cursor-pointer" onclick="window.location.href='{{ route('mi-estado') }}'">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-green-500 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900">Estado del Proveedor</h3>
                                        <p class="text-xs text-gray-600">Consulta tu estatus actual</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-md transition-all duration-200 cursor-pointer" onclick="window.location.href='{{ route('tramites.index') }}'">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-[#9d2449] p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900">Nuevo Trámite</h3>
                                        <p class="text-xs text-gray-600">Registra tu solicitud</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-md transition-all duration-200 cursor-pointer" onclick="window.location.href='{{ route('tramites.index') }}'">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900">Mis Trámites</h3>
                                    <p class="text-xs text-gray-600">Gestiona tus solicitudes</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 hover:shadow-md transition-all duration-200 cursor-pointer" onclick="window.location.href='{{ route('citas.index') }}'">
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-500 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900">Mis Citas</h3>
                                    <p class="text-xs text-gray-600">Programa tus citas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Information Section -->
            <div class="px-6 pb-6">
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de Soporte</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div class="flex items-start space-x-3">
                                <div class="bg-[#9d2449] p-2 rounded-lg">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">¿Necesitas Ayuda?</h3>
                                    <p class="text-xs text-gray-600 mb-3">Consulta nuestra guía de trámites para obtener información detallada sobre el proceso.</p>
                                    <a href="#" class="text-[#9d2449] font-medium hover:text-[#be185d] transition-colors duration-200 text-xs">Ver Guía de Trámites →</a>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div class="flex items-start space-x-3">
                                <div class="bg-[#9d2449] p-2 rounded-lg">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h10v-2H4v2zM4 11h14v-2H4v2zM4 7h18v-2H4v2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Notificaciones</h3>
                                    <p class="text-xs text-gray-600 mb-3">Mantente informado sobre el progreso de tus trámites a través de notificaciones.</p>
                                    <a href="{{ route('notificaciones.index') }}" class="text-[#9d2449] font-medium hover:text-[#be185d] transition-colors duration-200 text-xs">Ver Notificaciones →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
