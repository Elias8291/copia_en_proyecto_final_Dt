@extends('layouts.app')

@section('content')
<div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200/50 p-4">
    <!-- Header mejorado -->
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-4">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-2 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Detalles del Usuario</h1>
                        <p class="text-sm text-gray-500 mt-1">Información completa del usuario</p>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                    <a href="{{ route('users.edit', $user) }}" 
                       class="px-4 py-2 text-sm font-semibold text-blue-600 bg-white border border-blue-300 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('users.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Usuario -->
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 overflow-hidden">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r from-primary to-primary-dark px-4 py-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-white">{{ strtoupper(substr($user->nombre, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">{{ $user->nombre }}</h2>
                    <p class="text-white/80 text-sm">{{ $user->email }}</p>
                </div>
                <div class="ml-auto">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->deleted_at ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $user->deleted_at ? 'Inactivo' : 'Activo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-4 space-y-4">
            <!-- Información Personal -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 uppercase tracking-wide">Información Personal</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Nombre Completo</span>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $user->nombre }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Correo Electrónico</span>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $user->email }}</p>
                    </div>
                </div>

                @if($user->rfc)
                <div class="mt-3">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60 max-w-xs">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">RFC</span>
                        </div>
                        <p class="text-base font-semibold text-gray-900">{{ $user->rfc }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Roles y Permisos -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 uppercase tracking-wide">Roles y Permisos</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    @forelse($user->roles as $role)
                        <div class="bg-gradient-to-r from-primary/10 to-primary/5 rounded-lg p-2.5 border border-primary/20">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-primary rounded-md flex items-center justify-center mr-2">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-gray-900">{{ ucfirst($role->name) }}</span>
                                    <p class="text-xs text-gray-500">Rol asignado</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60 text-center">
                                <svg class="w-6 h-6 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <p class="text-sm text-gray-600 font-medium">No tiene roles asignados</p>
                                <p class="text-xs text-gray-500 mt-1">Se aplicará el rol por defecto</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Información del Sistema -->
            <div>
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 uppercase tracking-wide">Información del Sistema</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <svg class="w-3 h-3 text-primary mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">ID</span>
                        </div>
                        <p class="text-sm font-bold text-gray-900">#{{ $user->id }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <svg class="w-3 h-3 text-primary mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Creado</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <svg class="w-3 h-3 text-primary mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Actualizado</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $user->updated_at->format('H:i') }}</p>
                    </div>

                    @if($user->ultimo_acceso)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <svg class="w-3 h-3 text-primary mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Último Acceso</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($user->ultimo_acceso)->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($user->ultimo_acceso)->format('H:i') }}</p>
                    </div>
                    @endif
                </div>

                @if($user->deleted_at)
                <div class="mt-3">
                    <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-3 border border-red-200/60">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-red-800">Usuario Eliminado</p>
                                <p class="text-xs text-red-600">Eliminado el {{ $user->deleted_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 