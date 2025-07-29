@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Principal -->
            <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="bg-gradient-to-br from-[#9d2449] via-[#be185d] to-[#9d2449] rounded-xl p-2.5 shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
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
                                <div
                                    class="bg-gradient-to-r from-[#9d2449] to-[#be185d] px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="currentTime" class="font-mono"></span>
                                </div>

                                <!-- Fecha -->
                                <div class="text-sm text-gray-600 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span id="currentDate"></span>
                                </div>

                                <!-- Badge de Proveedor -->
                                @if (auth()->user()->hasRole('Proveedor'))
                                    <div
                                        class="bg-gradient-to-r from-yellow-400 to-yellow-500 px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Proveedor Oficial</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Saludo personalizado -->
                    <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/70">
                        <div class="mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                    Buenos días, {{ auth()->check() ? auth()->user()->nombre : 'Invitado' }}
                                </h2>
                                @if (auth()->user()->hasRole('Proveedor'))
                                    <p class="text-gray-600">
                                        <span class="text-yellow-600 font-semibold">¡Proveedor Oficial!</span> -
                                        Bienvenido al <span class="text-[#9d2449] font-semibold">Padrón de Proveedores del
                                            Estado de Oaxaca</span>
                                    </p>
                                @else
                                    <p class="text-gray-600">
                                        Bienvenido al <span class="text-[#9d2449] font-semibold">Padrón de Proveedores del
                                            Estado de Oaxaca</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Estadísticas integradas -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <!-- Usuarios -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-blue-500 to-blue-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalUsuarios ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Usuarios</h3>
                                </div>
                            </div>

                            <!-- Trámites -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-amber-500 to-amber-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalTramites ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Trámites</h3>
                                </div>
                            </div>

                            <!-- Proveedores -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-emerald-500 to-emerald-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalProveedores ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Proveedores</h3>
                                </div>
                            </div>

                            <!-- Roles -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-purple-500 to-purple-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalRoles ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Roles</h3>
                                </div>
                            </div>

                            <!-- Archivos -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-indigo-500 to-indigo-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalArchivos ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Archivos</h3>
                                </div>
                            </div>

                            <!-- Citas -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-rose-500 to-rose-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalCitas ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Citas</h3>
                                </div>
                            </div>

                            <!-- Actividades -->
                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-teal-500 to-teal-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalActividades ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Actividades</h3>
                                </div>
                            </div>

                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-orange-500 to-orange-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalRevisiones ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Revisiones</h3>
                                </div>
                            </div>

                            <div
                                class="bg-white/70 backdrop-blur-sm rounded-lg p-3 shadow-md border border-white/40 hover:shadow-lg hover:bg-white/90 transition-all duration-300 group h-20 flex flex-col justify-between">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="bg-gradient-to-br from-cyan-500 to-cyan-600 w-6 h-6 rounded-md flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-5 5v-5zM11 17H7l4 4v-4zM13 3h5l-5-5v5zM11 3H7l4-4v4z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ $totalNotificaciones ?? 0 }}</p>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xs font-semibold text-gray-800">Notificaciones</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-white/20">
                                <div class="flex flex-col lg:flex-row items-center gap-8">

                                    <div class="flex-1 text-center lg:text-left">
                                        @if (auth()->user()->hasRole('Proveedor'))
                                            <div class="space-y-6">
                                                <div
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-full text-sm font-semibold text-yellow-700 border border-yellow-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                                                        <a
                                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#be185d] text-white font-semibold rounded-xl hover:from-[#be185d] hover:to-[#9d2449] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                                                </path>
                                                            </svg>
                                                            Mi Estado de Proveedor
                                                        </a>
                                                    </div>
                                                @endcan
                                            </div>
                                        @else
                                            <div class="space-y-6">
                                                <div
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-full text-sm font-semibold text-yellow-700 border border-yellow-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976-2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                        </path>
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
                                                        <a
                                                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#be185d] text-white font-semibold rounded-xl hover:from-[#be185d] hover:to-[#9d2449] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                            </svg>
                                                            Iniciar Mi Trámite
                                                        </a>
                                                    </div>
                                                @endcan
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 flex justify-center lg:justify-end">
                                        <img src="{{ asset('images/mujer_bienvenida.png') }}" alt="Asistente Virtual"
                                            class="w-auto h-64 lg:h-80 object-contain drop-shadow-xl">
                                    </div>
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

                document.getElementById('currentTime').textContent =
                    `${formattedHours}:${formattedMinutes} ${ampm}`;

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
