@extends('layouts.app')

@section('title', 'Sin Proveedor Asociado')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        
        <h3 class="text-lg font-medium text-gray-900 mb-2">Sin Proveedor Asociado</h3>
        <p class="text-sm text-gray-500 mb-6">
            Tu cuenta de usuario no tiene un proveedor asociado. Contacta al administrador para que te asigne uno.
        </p>
        
        <div class="space-y-3">
            <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary border border-transparent rounded-lg text-sm font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                Volver al Dashboard
            </a>
            
            <a href="mailto:admin@sistema.com" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                Contactar Administrador
            </a>
        </div>
    </div>
</div>
@endsection