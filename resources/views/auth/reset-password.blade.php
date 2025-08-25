@extends('layouts.auth')

@section('title', 'Restablecer Contraseña - Padrón de Proveedores de Oaxaca')

@section('content')
<div class="text-center mb-3">
    <div class="flex flex-col items-center justify-center mb-2">
        <div class="w-14 h-14 flex items-center justify-center mb-2 bg-gradient-to-br from-primary/10 to-primary-dark/10 rounded-full p-2">
            <img src="/images/logoprin.jpg" alt="Logo Estado de Oaxaca" class="w-full h-full object-contain rounded-full">
        </div>
        <div class="text-center space-y-1">
            <span class="text-primary font-bold text-sm block tracking-wide">ADMINISTRACIÓN</span>
            <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Gobierno del Estado de Oaxaca</span>
        </div>
    </div>

    <div class="space-y-1 mb-2">
        <h1 class="text-lg font-bold text-gray-800 leading-tight">Restablecer Contraseña</h1>
        <p class="text-gray-600 text-xs leading-tight max-w-xs mx-auto">
            Ingresa tu nueva contraseña para acceder a tu cuenta
        </p>
    </div>
</div>

<div class="space-y-2">
    <form method="POST" action="{{ route('password.update') }}" class="space-y-2">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label for="email" class="block text-xs font-medium text-gray-700 mb-0.5">Correo Electrónico</label>
            <div class="relative">
                <input 
                    type="email" 
                    id="email" 
                    name="email_display" 
                    value="{{ $email }}" 
                    readonly
                    class="w-full px-2.5 py-1.5 rounded-lg border border-gray-300 bg-gray-50 text-gray-600 focus:outline-none focus:ring-0 focus:border-gray-300 text-sm"
                >
            </div>
        </div>

        <x-ui.forms.password-input name="password" label="Nueva Contraseña" placeholder="Mínimo 8 caracteres" />

        <x-ui.forms.password-input name="password_confirmation" label="Confirmar Nueva Contraseña" placeholder="Confirma tu nueva contraseña" :required="true" />

        <div class="pt-3">
            <button 
                type="submit" 
                class="group w-full bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 relative overflow-hidden text-sm"
            >
                <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                    </svg>
                    <span>Restablecer Contraseña</span>
                </div>
            </button>
        </div>
    </form>
</div>

<div class="text-center mt-3 relative z-10">
    <a href="{{ route('login') }}" class="text-gray-500 hover:text-primary text-xs font-medium transition-all duration-200 flex items-center justify-center space-x-2 hover:bg-gray-50 py-1.5 px-3 rounded-lg">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Volver al inicio de sesión</span>
    </a>
</div>
@endsection 

<script>
    
</script>