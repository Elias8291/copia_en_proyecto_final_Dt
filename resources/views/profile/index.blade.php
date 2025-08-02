@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">

        <!-- Profile Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden mb-8">
            <div class="h-28 bg-gradient-to-r from-gray-700 via-gray-800 to-black"></div>
            <div class="p-6 flex flex-col sm:flex-row items-center sm:justify-between -mt-16 sm:-mt-20">
                <div class="flex flex-col sm:flex-row items-center text-center sm:text-left sm:space-x-5">
                    <div class="w-28 h-28 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center ring-4 ring-white shadow-lg flex-shrink-0">
                        <span class="text-5xl font-bold text-white tracking-wider">{{ strtoupper(substr($user->nombre, 0, 1)) }}</span>
                    </div>
                    <div class="mt-4 sm:mt-16">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $user->nombre }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-12">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-primary rounded-lg hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-blue-300 transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column -->
            <div class="lg:col-span-8 space-y-8">

                <!-- Account Details -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-6 sm:p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4 text-white flex-shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Detalles de la Cuenta</h2>
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8 text-sm">
                        <div class="sm:col-span-1">
                            <dt class="font-medium text-gray-500">Nombre Completo</dt>
                            <dd class="mt-1 text-gray-900 font-semibold text-base">{{ $user->nombre }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="font-medium text-gray-500">Correo Electrónico</dt>
                            <dd class="mt-1 text-gray-900 font-semibold text-base">{{ $user->correo }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="font-medium text-gray-500">ID de Usuario</dt>
                            <dd class="mt-1 text-gray-900 font-semibold text-base">#{{ $user->id }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="font-medium text-gray-500">Estado de la cuenta</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    Activo
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="font-medium text-gray-500">Miembro desde</dt>
                            <dd class="mt-1 text-gray-900 font-semibold">{{ $user->created_at->format('d/m/Y') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="font-medium text-gray-500">Última actualización</dt>
                            <dd class="mt-1 text-gray-900 font-semibold">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Tramites History -->
                @php
                    $proveedor = $user->proveedor;
                    $tramites = $proveedor ? $proveedor->tramites()->orderBy('created_at', 'desc')->take(5)->get() : collect();
                @endphp

                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50">
                    <div class="p-6 sm:p-8 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                             <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4 text-white flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Historial de Trámites</h2>
                            </div>
                            @if($tramites->count() > 0)
                            <a href="{{ route('tramites.historial') }}" class="text-sm text-primary hover:text-primary-dark font-medium flex items-center flex-shrink-0">
                                Ver todos
                                <svg class="w-4 h-4 ml-1 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            @endif
                        </div>
                    </div>

                    @if($tramites->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($tramites as $tramite)
                                @php
                                    $estadoColor = match ($tramite->estado) {
                                        'Pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'Por_Cotejar' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'En_Revision' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'Aprobado' => 'bg-green-100 text-green-800 border-green-200',
                                        'Rechazado' => 'bg-red-100 text-red-800 border-red-200',
                                        'Cancelado' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200',
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
                                    $estadoNombre = ucfirst(str_replace('_', ' ', $tramite->estado));
                                @endphp
                                <div class="p-4 sm:p-6 hover:bg-gray-50/50 transition-colors duration-200">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $estadoColor }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    {!! $estadoIcon !!}
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">
                                                     Trámite #{{ str_pad($tramite->id, 4, '0', STR_PAD_LEFT) }}
                                                </h3>
                                                <p class="text-xs text-gray-500">
                                                    {{ ucfirst(str_replace('_', ' ', $tramite->tipo_tramite)) }} &middot; {{ $tramite->created_at->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3 mt-3 sm:mt-0 self-end sm:self-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $estadoColor }}">
                                                {{ $estadoNombre }}
                                            </span>
                                            <a href="{{ route('tramites.estado', $tramite->id) }}" class="text-gray-400 hover:text-primary transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                     @if($tramite->observaciones)
                                        <div class="mt-4 sm:pl-14">
                                            <p class="text-xs text-gray-600 bg-gray-100/80 p-3 rounded-lg border border-gray-200/80">
                                                <span class="font-medium text-gray-700">Observaciones:</span>
                                                {{ Str::limit($tramite->observaciones, 150) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 px-6">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay trámites recientes</h3>
                            <p class="text-sm text-gray-500 mb-6">Parece que aún no has iniciado ningún trámite.</p>
                            <a href="{{ route('tramites.index') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark transition-all duration-300 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Iniciar un Trámite
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Roles -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200/50 p-6 sm:p-8">
                     <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center mr-4 text-white flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">Roles Asignados</h2>
                    </div>
                    <div class="space-y-4">
                         @forelse($user->roles as $role)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200/80 hover:border-gray-300 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21a12.083 12.083 0 01-6.16-10.422L12 14z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ ucfirst($role->name) }}</p>
                                         @if($role->description)
                                            <p class="text-xs text-gray-500">{{ $role->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                         @empty
                            <div class="bg-gray-100 text-center rounded-lg p-6 border border-gray-200/80">
                                <p class="text-sm text-gray-600">No tienes roles asignados.</p>
                            </div>
                         @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <x-error-modal
            id="error-modal"
            title="Error"
            message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
            buttonText="OK"
        />

        <x-modal-exito
            id="success-modal"
            title="¡Éxito!"
            message="La operación se realizó correctamente."
            acceptText="Aceptar"
            :redirectUrl="route('profile.index')"
        />

    </div>
</div>

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session('error') }}');
});
</script>
@endif

@endsection
