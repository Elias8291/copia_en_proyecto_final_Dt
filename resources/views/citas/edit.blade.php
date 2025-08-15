@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="w-full max-w-7xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 -mt-4">
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-4">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-2 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Editar Cita #{{ $cita->id }}</h1>
                        <p class="text-base text-gray-500 mt-1">Modifique la información de la cita</p>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                    <a href="{{ route('citas.index') }}" 
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

    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50">
        <form action="{{ route('citas.update', $cita) }}" method="POST" class="space-y-4 p-4">
            @csrf
            @method('PUT')
            
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Información de la Cita</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="user_id" class="block text-base font-medium text-gray-700">
                            Usuario <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="user_id" name="user_id" required 
                                    class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('user_id') border-red-300 ring-red-100 @enderror">
                                <option value="">Seleccionar usuario</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ $cita->user_id == $user->id ? 'selected' : '' }}>{{ $user->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('user_id')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="fecha_cita" class="block text-base font-medium text-gray-700">
                            Fecha y Hora <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="datetime-local" 
                                   id="fecha_cita" 
                                   name="fecha_cita" 
                                   value="{{ $cita->fecha_cita->format('Y-m-d\TH:i') }}" 
                                   required 
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('fecha_cita') border-red-300 ring-red-100 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('fecha_cita')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                    <div class="space-y-1">
                        <label for="tipo_cita" class="block text-base font-medium text-gray-700">
                            Tipo de Cita <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="tipo_cita" 
                                   name="tipo_cita" 
                                   value="{{ old('tipo_cita', $cita->tipo_cita) }}" 
                                   required 
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('tipo_cita') border-red-300 ring-red-100 @enderror"
                                   placeholder="Ej: Revisión Domiciliaria">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        @error('tipo_cita')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="estado" class="block text-base font-medium text-gray-700">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="estado" 
                                   name="estado" 
                                   value="{{ old('estado', $cita->estado) }}" 
                                   required 
                                   class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('estado') border-red-300 ring-red-100 @enderror"
                                   placeholder="Ej: Programada, Completada">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('estado')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Detalles Adicionales</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="space-y-1">
                        <label for="motivo" class="block text-base font-medium text-gray-700">
                            Motivo <span class="text-red-500">*</span>
                        </label>
                        <textarea id="motivo" 
                                  name="motivo" 
                                  required 
                                  rows="3" 
                                  class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('motivo') border-red-300 ring-red-100 @enderror resize-none"
                                  placeholder="Describe el motivo de la cita...">{{ old('motivo', $cita->motivo) }}</textarea>
                        @error('motivo')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="observaciones" class="block text-base font-medium text-gray-700">
                            Observaciones
                        </label>
                        <textarea id="observaciones" 
                                  name="observaciones" 
                                  rows="3" 
                                  class="w-full px-3 py-2.5 text-base border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200 @error('observaciones') border-red-300 ring-red-100 @enderror resize-none"
                                  placeholder="Observaciones adicionales (opcional)...">{{ old('observaciones', $cita->observaciones) }}</textarea>
                        @error('observaciones')
                            <p class="text-sm text-red-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                <div class="flex items-center mb-2">
                    <div class="w-4 h-4 bg-gradient-to-br from-primary to-primary-dark rounded-md flex items-center justify-center mr-2">
                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 uppercase tracking-wide">Información de la Cita</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <div class="bg-white/60 rounded-md p-2">
                        <span class="text-gray-500 font-medium">ID:</span>
                        <span class="ml-1 font-semibold text-gray-900">#{{ $cita->id }}</span>
                    </div>
                    <div class="bg-white/60 rounded-md p-2">
                        <span class="text-gray-500 font-medium">Estado Actual:</span>
                        <span class="ml-1 font-semibold text-primary">{{ $cita->estado }}</span>
                    </div>
                    <div class="bg-white/60 rounded-md p-2">
                        <span class="text-gray-500 font-medium">Creada:</span>
                        <span class="ml-1 font-semibold text-gray-900">{{ $cita->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('citas.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-primary to-primary-dark hover:from-primary-dark hover:to-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Cita
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modales -->
<x-ui.modals.error-modal
    id="error-modal"
    title="Error"
    message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
    buttonText="OK"
/>

<x-ui.modals.modal-exito
    id="success-modal"
    title="¡Éxito!"
    message="La cita se actualizó correctamente."
    acceptText="Aceptar"
    :redirectUrl="route('citas.index')"
/>

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session('error') }}');
});
</script>
@endif

@endsection 