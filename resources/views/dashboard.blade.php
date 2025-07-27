@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Principal -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-br from-[#9d2449] via-[#be185d] to-[#9d2449] rounded-xl p-2.5 shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800">Panel de Control</h1>
                                <p class="text-xs md:text-sm text-gray-500 mt-1">Sistema de Gestión de Proveedores</p>
                            </div>
                        </div>
                        
                        <!-- Información de tiempo y usuario -->
                        <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                            <!-- Reloj -->
                            <div class="bg-gradient-to-r from-[#9d2449] to-[#be185d] px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="currentTime" class="font-mono"></span>
                            </div>
                            
                            <!-- Fecha -->
                            <div class="text-sm text-gray-600 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span id="currentDate"></span>
                            </div>
                            
                            <!-- Badge de Proveedor -->
                            @if(auth()->user()->hasRole('Proveedor'))
                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Proveedor Oficial</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Saludo personalizado -->
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/70">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                            Buenos días, {{ auth()->check() ? auth()->user()->name : 'Invitado' }}
                            </h2>
                        @if(auth()->user()->hasRole('Proveedor'))
                            <p class="text-gray-600">
                                <span class="text-yellow-600 font-semibold">¡Proveedor Oficial!</span> - 
                            Bienvenido al <span class="text-[#9d2449] font-semibold">Padrón de Proveedores del Estado de Oaxaca</span>
                        </p>
                        @else
                            <p class="text-gray-600">
                            Bienvenido al <span class="text-[#9d2449] font-semibold">Padrón de Proveedores del Estado de Oaxaca</span>
                        </p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Estado del Sistema -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-800">Activo</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium text-green-600 bg-green-50 rounded-full">
                            Sistema Online
                        </span>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Estado del Sistema</h3>
                <p class="text-sm text-gray-500">Todos los servicios funcionando correctamente</p>
            </div>

            <!-- Versión del Sistema -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-800">v2.1</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full">
                            Actualizada
                        </span>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Versión del Sistema</h3>
                <p class="text-sm text-gray-500">Última actualización: Diciembre 2024</p>
            </div>

            <!-- Soporte Técnico -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 109.75 9.75A9.75 9.75 0 0012 2.25z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-800">24/7</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium text-purple-600 bg-purple-50 rounded-full">
                            Disponible
                        </span>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Soporte Técnico</h3>
                <p class="text-sm text-gray-500">Asistencia disponible las 24 horas</p>
            </div>

            <!-- Seguridad -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-800">SSL</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-full">
                            Protegido
                        </span>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Seguridad</h3>
                <p class="text-sm text-gray-500">Conexión encriptada y segura</p>
                </div>
            </div>

        <!-- Estadísticas (Solo para administradores) -->
                @can('dashboard.ver-estadisticas')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Usuarios -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-800">{{ $totalUsuarios ?? 0 }}</p>
                        <span class="inline-block px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full">
                                    Total registrados
                                </span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Usuarios del Sistema</h3>
                <p class="text-sm text-gray-500 mb-4">Gestión completa de usuarios</p>
                <a href="{{ route('users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-semibold transition-colors">
                                <span>Administrar usuarios</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                            </a>
                        </div>

            <!-- Trámites -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-gray-800">{{ $tramitesPendientes ?? 0 }}</p>
                        <span class="inline-block px-3 py-1 text-xs font-medium text-amber-600 bg-amber-50 rounded-full">
                            Pendientes
                                </span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Trámites en Proceso</h3>
                <p class="text-sm text-gray-500 mb-4">Solicitudes por aprobar</p>
                <a class="inline-flex items-center text-amber-600 hover:text-amber-800 text-sm font-semibold transition-colors">
                                <span>Revisar trámites</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                            </a>
                        </div>

            <!-- Proveedores -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-200/50 hover:shadow-xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 w-12 h-12 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                            </div>
                            <div class="text-right">
                        <p class="text-3xl font-bold text-gray-800">0</p>
                        <span class="inline-block px-3 py-1 text-xs font-medium text-emerald-600 bg-emerald-50 rounded-full">
                            Activos
                                </span>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">Proveedores Registrados</h3>
                <p class="text-sm text-gray-500 mb-4">Base de datos completa</p>
                <a class="inline-flex items-center text-emerald-600 hover:text-emerald-800 text-sm font-semibold transition-colors">
                                <span>Gestionar proveedores</span>
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                            </a>
                        </div>
                </div>
                @endcan

        <!-- Contenido Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Panel de Acciones Rápidas -->
                    <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#9d2449] to-[#be185d] rounded-xl flex items-center justify-center mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                    </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Acciones Rápidas</h3>
                                <p class="text-sm text-gray-500">Herramientas principales</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                                <!-- Mis Trámites -->
                        <a class="group block p-4 bg-gray-50 rounded-xl hover:bg-blue-50 transition-all duration-300 border border-transparent hover:border-blue-200">
                                    <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                        </div>
                                        <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">Mis Trámites</h4>
                                    <p class="text-sm text-gray-500">Gestiona tus solicitudes</p>
                                            </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                    </div>
                                </a>

                        <!-- Estado del Proveedor -->
                                @if(!auth()->user()->hasRole('Proveedor'))
                                @can('mi-estado-proveedor.ver')
                        <a class="group block p-4 bg-gray-50 rounded-xl hover:bg-emerald-50 transition-all duration-300 border border-transparent hover:border-emerald-200">
                                    <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                        </div>
                                        <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 group-hover:text-emerald-600 transition-colors">Estado del Proveedor</h4>
                                    <p class="text-sm text-gray-500">Consulta tu estatus</p>
                                            </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                    </div>
                                </a>
                                @endcan
                                @endif

                        <!-- Documentación -->
                        <a class="group block p-4 bg-gray-50 rounded-xl hover:bg-purple-50 transition-all duration-300 border border-transparent hover:border-purple-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 group-hover:text-purple-600 transition-colors">Documentación</h4>
                                    <p class="text-sm text-gray-500">Guías y manuales</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>

                        <!-- Soporte -->
                        <a class="group block p-4 bg-gray-50 rounded-xl hover:bg-orange-50 transition-all duration-300 border border-transparent hover:border-orange-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800 group-hover:text-orange-600 transition-colors">Soporte Técnico</h4>
                                    <p class="text-sm text-gray-500">Ayuda y asistencia</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                        </div>
                    </div>

            <!-- Panel Principal con Imagen -->
                    <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
                    <div class="p-8">
                        <div class="flex flex-col lg:flex-row items-center gap-8">
                            <!-- Contenido de texto -->
                            <div class="flex-1 text-center lg:text-left">
                                        @if(auth()->user()->hasRole('Proveedor'))
                                <!-- Contenido para Proveedores -->
                                <div class="space-y-6">
                                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-full text-sm font-semibold text-yellow-700 border border-yellow-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                                    ¡Proveedor Activo!
                                                </div>
                                    
                                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4">
                                                    ¡Bienvenido, 
                                        <span class="text-yellow-600">Proveedor Oficial</span>!
                                    </h2>
                                    
                                    <p class="text-lg text-gray-600 mb-6">
                                                    Gestiona tu estatus en el 
                                        <span class="font-semibold text-[#9d2449]">Padrón de Proveedores</span>
                                                </p>
                                            
                                            @can('mi-estado-proveedor.ver')
                                    <div class="flex justify-center lg:justify-start">
                                        <a class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#be185d] text-white font-semibold rounded-xl hover:from-[#be185d] hover:to-[#9d2449] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Mi Estado de Proveedor
                                                </a>
                                            </div>
                                            @endcan
                                        </div>
                                        
                                        @else
                                <!-- Contenido para Solicitantes -->
                                <div class="space-y-6">
                                    <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-full text-sm font-semibold text-yellow-700 border border-yellow-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976-2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                                        ¡Únete al Padrón Oficial!
                                                </div>
                                                
                                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4">
                                                            Conviértete en
                                        <span class="text-[#9d2449]">Proveedor Oficial</span>
                                    </h2>
                                    
                                    <p class="text-lg text-gray-600 mb-6">
                                                            Forma parte del exclusivo
                                        <span class="font-semibold text-[#9d2449]">Padrón de Proveedores</span>
                                                            del Estado de Oaxaca
                                                        </p>
                                            
                                            @can('tramites-solicitante.inscripcion')
                                            <div class="flex justify-center lg:justify-start">
                                        <a class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#be185d] text-white font-semibold rounded-xl hover:from-[#be185d] hover:to-[#9d2449] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Iniciar Mi Trámite
                                        </a>
                                            </div>
                                            @endcan
                                        </div>
                                        @endif
                                    </div>

                            <!-- Imagen -->
                            <div class="flex-1 flex justify-center lg:justify-end">
                                <img src="{{ asset('images/mujer_bienvenida.png') }}" 
                                     alt="Asistente Virtual" 
                                     class="w-auto h-64 lg:h-80 object-contain drop-shadow-xl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateDateTime() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const formattedHours = hours % 12 || 12;
            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;

            document.getElementById('currentTime').textContent = `${formattedHours}:${formattedMinutes} ${ampm}`;

            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('es-ES', options);

            const greeting = document.getElementById('greeting');
            const userName = '{{ auth()->check() ? auth()->user()->name : 'Invitado' }}';

            if (hours < 12) {
                greeting.textContent = `Buenos días, ${userName}`;
            } else if (hours >= 12 && hours < 19) {
                greeting.textContent = `Buenas tardes, ${userName}`;
            } else {
                greeting.textContent = `Buenas noches, ${userName}`;
            }
        }

        updateDateTime();
        setInterval(updateDateTime, 60000);
    });
</script>
@endsection