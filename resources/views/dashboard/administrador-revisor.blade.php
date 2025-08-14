@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
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
                                <h1 class="text-lg md:text-xl lg:text-2xl font-bold text-gray-800">Dashboard</h1>
                                <p class="text-xs md:text-sm text-gray-500 mt-1">Sistema de Gestión de Proveedores</p>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row items-center space-y-3 lg:space-y-0 lg:space-x-3">
                            <div class="bg-gradient-to-r from-[#9d2449] to-[#be185d] px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="currentTime" class="font-mono"></span>
                            </div>
                            <div class="text-sm text-gray-600 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span id="currentDate"></span>
                            </div>
                            @if (auth()->user()->hasRole(['Administrador', 'Super Administrador']))
                                <div class="bg-gradient-to-r from-red-400 to-red-500 px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    <span>Administrador</span>
                                </div>
                            @elseif (auth()->user()->hasRole(['Revisor Digital', 'Revisor Presencial', 'Revisor Domiciliario']))
                                <div class="bg-gradient-to-r from-green-400 to-green-500 px-4 py-2 rounded-full text-sm font-semibold text-white flex items-center shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <span>Revisor</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/70">
                    <div class="mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                Buenos días, {{ auth()->check() ? auth()->user()->nombre : 'Invitado' }}
                            </h2>
                            @if (auth()->user()->hasRole(['Administrador', 'Super Administrador']))
                                <p class="text-gray-600">
                                    <span class="text-red-600 font-semibold">¡Administrador!</span> -
                                    Bienvenido al <span class="text-[#9d2449] font-semibold">Sistema de Gestión de Proveedores</span>
                                </p>
                            @elseif (auth()->user()->hasRole(['Revisor Digital', 'Revisor Presencial', 'Revisor Domiciliario']))
                                <p class="text-gray-600">
                                    <span class="text-green-600 font-semibold">¡Revisor!</span> -
                                    Bienvenido al <span class="text-[#9d2449] font-semibold">Sistema de Revisión de Trámites</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @if (auth()->user()->hasRole(['Administrador', 'Super Administrador']))
                            <!-- Card para gestionar usuarios -->
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('users.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Gestionar Usuarios</h3>
                                        <p class="text-sm text-gray-600">Administra usuarios del sistema</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('roles.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Gestionar Roles</h3>
                                        <p class="text-sm text-gray-600">Administra roles y permisos</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('tramites.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-amber-500 to-amber-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Ver Trámites</h3>
                                        <p class="text-sm text-gray-600">Revisa todos los trámites</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('citas.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Gestionar Citas</h3>
                                        <p class="text-sm text-gray-600">Administra las citas del sistema</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('archivos.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Archivos</h3>
                                        <p class="text-sm text-gray-600">Gestiona documentos</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('revisiones.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Revisiones</h3>
                                        <p class="text-sm text-gray-600">Gestiona revisiones de trámites</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('notificaciones.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM11 17H7l4 4v-4zM13 3h5l-5-5v5zM11 3H7l4-4v4z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Notificaciones</h3>
                                        <p class="text-sm text-gray-600">Gestiona notificaciones</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        @if (auth()->user()->hasRole(['Revisor Digital', 'Revisor Presencial', 'Revisor Domiciliario']))
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('revisiones.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Revisar Trámites</h3>
                                        <p class="text-sm text-gray-600">Evalúa solicitudes pendientes</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="window.location.href='{{ route('archivos.index') }}'">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800">Ver Archivos</h3>
                                        <p class="text-sm text-gray-600">Accede a documentos del sistema</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        @endif
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

            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = `${formattedHours}:${formattedMinutes} ${ampm}`;
            }
                    
            const dateElement = document.getElementById('currentDate');
            if (dateElement) {
                const options = {
                    weekday: 'long',
                    year: 'numeric',  
                    month: 'long',
                    day: 'numeric'
                };
                dateElement.textContent = now.toLocaleDateString('es-ES', options);
            }
        }

        updateDateTime();
        setInterval(updateDateTime, 60000);
    });
</script>
@endsection
