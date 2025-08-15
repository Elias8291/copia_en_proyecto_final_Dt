@extends('layouts.app')

@section('title', 'Crear Cita')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Crear Nueva Cita</h1>
                        <p class="text-base text-gray-500 mt-1">Programa una nueva cita para revisión</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('citas.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('citas.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tramite_id" class="block text-sm font-medium text-gray-700 mb-2">Trámite *</label>
                        <select id="tramite_id" name="tramite_id" required 
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 @error('tramite_id') border-red-300 @enderror">
                            <option value="">Seleccionar trámite</option>
                            @foreach($tramites as $tramite)
                                <option value="{{ $tramite->id }}" {{ old('tramite_id') == $tramite->id ? 'selected' : '' }}>
                                    #{{ $tramite->id }} - {{ $tramite->proveedor->razon_social ?? 'Sin proveedor' }} ({{ $tramite->status }})
                                </option>
                            @endforeach
                        </select>
                        @error('tramite_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tipo_cita" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cita *</label>
                        <select id="tipo_cita" name="tipo_cita" required 
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 @error('tipo_cita') border-red-300 @enderror">
                            <option value="">Seleccionar tipo</option>
                            @foreach($tiposCita as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_cita') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                            @endforeach
                        </select>
                        @error('tipo_cita')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="fecha_cita" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora *</label>
                        <input type="datetime-local" id="fecha_cita" name="fecha_cita" required 
                               value="{{ old('fecha_cita') }}"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 @error('fecha_cita') border-red-300 @enderror">
                        @error('fecha_cita')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">La fecha debe ser futura</p>
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                        <select id="estado" name="estado" required 
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 @error('estado') border-red-300 @enderror">
                            @foreach($estados as $estado)
                                <option value="{{ $estado }}" {{ (old('estado') ?? 'Asignada') == $estado ? 'selected' : '' }}>
                                    {{ $estado == 'No_Asistio' ? 'No Asistió' : $estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="asignado_a" class="block text-sm font-medium text-gray-700 mb-2">Asignado a</label>
                    <select id="asignado_a" name="asignado_a" 
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] transition-all duration-200 @error('asignado_a') border-red-300 @enderror">
                        <option value="">Sin asignar</option>
                        @foreach($revisores as $revisor)
                            <option value="{{ $revisor->id }}" {{ old('asignado_a') == $revisor->id ? 'selected' : '' }}>
                                {{ $revisor->nombre }} ({{ $revisor->roles->first()->name ?? 'Sin rol' }})
                            </option>
                        @endforeach
                    </select>
                    @error('asignado_a')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('citas.index') }}" 
                       class="px-6 py-2.5 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200 text-center font-medium">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-[#9d2449] text-white rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 font-medium shadow-sm">
                        Crear Cita
                    </button>
                </div>
            </form>
        </div>
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
    message="La cita se creó correctamente."
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