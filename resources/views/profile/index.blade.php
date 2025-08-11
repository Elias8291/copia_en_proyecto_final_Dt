@extends('layouts.app')

@section('content')
<div class="min-h-screen font-sans py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Profile Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-primary to-primary-dark h-20 relative">
                <div class="absolute inset-0 bg-black/5"></div>
            </div>
            
            <div class="px-6 py-6 flex flex-col sm:flex-row items-center sm:justify-between -mt-10">
                <div class="flex flex-col sm:flex-row items-center text-center sm:text-left sm:space-x-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-dark rounded-full flex items-center justify-center ring-4 ring-white shadow-lg flex-shrink-0">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->nombre, 0, 1)) }}</span>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-4">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->nombre }}</h1>
                        <p class="text-base text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center justify-center sm:justify-start mt-2 space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Miembro desde {{ $user->created_at->format('M Y') }}
                            </span>
                            <span class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                Activo
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark focus:ring-4 focus:outline-none focus:ring-primary/30 transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Perfil
                    </a>
                </div>
            </div>

            <!-- Account Details Section -->
            <div class="px-6 pb-6">
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de la Cuenta</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Información del usuario -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nombre Completo</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->nombre }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Correo Electrónico</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->correo }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">ID de Usuario</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1">#{{ $user->id }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Estado de la Cuenta</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></div>
                                    Activo
                                </span>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Miembro desde</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">Última actualización</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <x-ui.modals.error-modal
            id="error-modal"
            title="Error"
            message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
            buttonText="OK"
        />

        <x-ui.modals.modal-exito
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
