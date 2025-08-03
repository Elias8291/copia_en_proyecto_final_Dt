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
