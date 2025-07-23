@extends('layouts.app')

@section('title', 'Trámite Enviado Exitosamente')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            
            <!-- Icono de éxito -->
            <div class="mb-6">
                <div class="w-24 h-24 mx-auto bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Título -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                ¡Trámite Enviado Exitosamente!
            </h1>

            <!-- Mensaje -->
            <p class="text-lg text-gray-600 mb-6">
                {{ session('success', 'Su trámite ha sido procesado correctamente y se encuentra en revisión.') }}
            </p>

            <!-- Información adicional -->
            @if(session('tramite_id'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800">
                    <strong>ID del Trámite:</strong> {{ session('tramite_id') }}
                </p>
                <p class="text-blue-600 text-sm mt-1">
                    Guarde este número de referencia para futuras consultas.
                </p>
            </div>
            @endif

            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('tramites.index') }}" 
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Ver Mis Trámites
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Ir al Dashboard
                </a>
            </div>

            <!-- Información de seguimiento -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">
                    Recibirá una notificación por correo electrónico cuando su trámite sea revisado.
                </p>
            </div>

        </div>
    </div>
</div>
@endsection 