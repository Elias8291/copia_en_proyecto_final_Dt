@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 -mt-4">

    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-6">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Mi Perfil</h1>
                        <p class="text-base text-gray-500 mt-1">Gestiona tu información personal</p>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                    <a href="{{ route('profile.edit') }}" 
                       class="px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-500 to-blue-600 border border-blue-500 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Información Personal</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Nombre Completo</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->nombre }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Correo Electrónico</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estado de la Cuenta -->
            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Estado de la Cuenta</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Estado</h3>
                                    <p class="text-lg font-semibold text-green-600">Activo</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Último Acceso</h3>
                                    <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">ID de Usuario</h3>
                                    <p class="text-lg font-semibold text-gray-900">#{{ $user->id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historial de Trámites -->
            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Historial de Trámites</h2>
                        </div>
                        <a href="{{ route('tramites.historial') }}" class="text-sm text-[#9d2449] hover:text-[#8a203f] font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Ver todos
                        </a>
                    </div>
                    
                    @php
                        $proveedor = $user->proveedor;
                        $tramites = $proveedor ? $proveedor->tramites()->orderBy('created_at', 'desc')->take(5)->get() : collect();
                    @endphp
                    
                    @if($tramites->count() > 0)
                        <div class="space-y-4">
                            @foreach($tramites as $tramite)
                                @php
                                    $estadoColor = match ($tramite->estado) {
                                        'Pendiente' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'Por_Cotejar' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'En_Revision' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'Aprobado' => 'bg-green-50 text-green-700 border-green-200',
                                        'Rechazado' => 'bg-red-50 text-red-700 border-red-200',
                                        'Cancelado' => 'bg-gray-50 text-gray-700 border-gray-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                    
                                    $estadoIcon = match ($tramite->estado) {
                                        'Pendiente' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                        'Por_Cotejar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
                                        'En_Revision' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>',
                                        'Aprobado' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                                        'Rechazado' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                                        'Cancelado' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                                        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                    };
                                @endphp
                                
                                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $estadoIcon !!}
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold text-gray-900">
                                                    Trámite #{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}
                                                </h3>
                                                <p class="text-xs text-gray-500">
                                                    {{ $tramite->created_at->format('d/m/Y H:i') }}
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    {{ ucfirst(str_replace('_', ' ', $tramite->tipo_tramite)) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $estadoColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $tramite->estado)) }}
                                            </span>
                                            <a href="{{ route('tramites.estado', $tramite->id) }}" 
                                               class="text-[#9d2449] hover:text-[#8a203f] transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    @if($tramite->observaciones)
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <p class="text-xs text-gray-600">
                                                <span class="font-medium">Observaciones:</span> 
                                                {{ Str::limit($tramite->observaciones, 100) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay trámites</h3>
                            <p class="text-sm text-gray-500 mb-4">Aún no has realizado ningún trámite</p>
                            <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a203f] transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Iniciar Primer Trámite
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Avatar y Roles -->
            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Mi Cuenta</h2>
                    </div>
                    
                    <!-- Avatar -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-primary to-primary-dark rounded-full shadow-lg mb-4">
                            <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->nombre, 0, 1)) }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $user->nombre }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    
                    <!-- Roles -->
                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Roles Asignados</h4>
                        @foreach($user->roles as $role)
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ ucfirst($role->name) }}</p>
                                    @if($role->description)
                                        <p class="text-xs text-gray-500">{{ $role->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Información del Registro -->
            <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Información del Registro</h2>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-medium text-gray-500">Creado</h3>
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-medium text-gray-500">Actualizado</h3>
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de error -->
<x-error-modal 
    id="error-modal"
    title="Error"
    message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
    buttonText="OK"
/>

<!-- Modal de éxito -->
<x-modal-exito 
    id="success-modal"
    title="¡Éxito!"
    message="La operación se realizó correctamente."
    acceptText="Aceptar"
    :redirectUrl="route('profile.index')"
/>

<!-- Mostrar modal de error si hay error de sesión -->
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session('error') }}');
});
</script>
@endif
@endsection 