@extends('layouts.app')

@section('title', 'Crear Cita')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg mr-4">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Crear Nueva Cita</h2>
                    <p class="text-gray-600">Programa una nueva cita para revisión</p>
                </div>
            </div>

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

            <form action="{{ route('citas.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="tramite_id" class="block text-sm font-medium text-gray-700 mb-2">Trámite *</label>
                    <select id="tramite_id" name="tramite_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] @error('tramite_id') border-red-300 @enderror">
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

                <div class="mb-6">
                    <label for="tipo_cita" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cita *</label>
                    <select id="tipo_cita" name="tipo_cita" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] @error('tipo_cita') border-red-300 @enderror">
                        <option value="">Seleccionar tipo</option>
                        @foreach($tiposCita as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_cita') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_cita')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="fecha_cita" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora *</label>
                    <input type="datetime-local" id="fecha_cita" name="fecha_cita" required 
                           value="{{ old('fecha_cita') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] @error('fecha_cita') border-red-300 @enderror">
                    @error('fecha_cita')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">La fecha debe ser futura</p>
                </div>

                <div class="mb-6">
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select id="estado" name="estado" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] @error('estado') border-red-300 @enderror">
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

                <div class="mb-6">
                    <label for="asignado_a" class="block text-sm font-medium text-gray-700 mb-2">Asignado a</label>
                    <select id="asignado_a" name="asignado_a" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#9d2449]/20 focus:border-[#9d2449] @error('asignado_a') border-red-300 @enderror">
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

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('citas.index') }}" 
                       class="px-6 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#9d2449] text-white rounded-md hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-colors">
                        Crear Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 