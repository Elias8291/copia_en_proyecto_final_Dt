@extends('layouts.app')

@section('content')
<div class="min-h-screen py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 transform hover:scale-[1.01] transition-all duration-300 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#B4325E] to-[#93264B] rounded-xl p-3 shadow-md">
                        <svg class="w-7 h-7 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#B4325E] to-[#93264B] bg-clip-text text-transparent">
                            Editar Usuario
                        </h2>
                        <p class="text-sm text-gray-500">Modifica la información del usuario: {{ $user->nombre }}</p>
                    </div>
                </div>
                <a href="{{ route('users.index') }}" 
                   class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 rounded-xl shadow-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transform hover:scale-105 transition-all duration-300 hover:shadow-lg w-full md:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Usuarios
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white/95 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Información del Usuario</h3>
                
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre Completo
                            </label>
                            <input type="text" name="nombre" id="nombre" value="{{ $user->nombre }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:border-transparent">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico
                            </label>
                            <input type="email" name="correo" id="correo" value="{{ $user->correo }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:border-transparent">
                        </div>

                        <!-- RFC -->
                        <div>
                            <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">
                                RFC
                            </label>
                            <input type="text" name="rfc" id="rfc" value="{{ $user->rfc }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:border-transparent">
                        </div>

                        <!-- Contraseña (opcional) -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Contraseña (opcional)
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#B4325E] focus:border-transparent"
                                   placeholder="Dejar vacío para mantener la actual">
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}" 
                           class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-[#B4325E] to-[#93264B] text-white rounded-lg hover:from-[#93264B] hover:to-[#7a1d37] focus:outline-none focus:ring-2 focus:ring-[#B4325E] transition-all duration-200">
                            Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection