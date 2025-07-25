@extends('layouts.app')

@section('title', 'Crear Cita - Trámite #' . $tramite->id)

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Crear Nueva Cita</h1>
                        <p class="text-sm text-gray-500">Agendar cita para el trámite #{{ $tramite->id }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('citas.tramite', $tramite) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Ver Citas del Trámite
                    </a>
                    <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Información del Trámite -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70 mb-8">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Información del Trámite</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600"><strong>Trámite #:</strong> {{ $tramite->id }}</p>
                        <p class="text-sm text-gray-600"><strong>Proveedor:</strong> {{ $tramite->proveedor->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600"><strong>RFC:</strong> {{ $tramite->proveedor->rfc ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600"><strong>Estado:</strong> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($tramite->estado === 'Por_Cotejar') bg-blue-100 text-blue-800
                                @elseif($tramite->estado === 'En_Revision') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ str_replace('_', ' ', $tramite->estado) }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600"><strong>Fecha de Creación:</strong> {{ $tramite->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Cita -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-6">Detalles de la Cita</h2>
                
                <form action="{{ route('citas.store', $tramite) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Tipo de Cita -->
                    <div>
                        <label for="tipo_cita" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Cita <span class="text-red-500">*</span>
                        </label>
                        <select id="tipo_cita" name="tipo_cita" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-colors duration-200">
                            <option value="">Selecciona el tipo de cita</option>
                            <option value="Cotejo" {{ old('tipo_cita') == 'Cotejo' ? 'selected' : '' }}>Cotejo de Documentos</option>
                            <option value="Consulta" {{ old('tipo_cita') == 'Consulta' ? 'selected' : '' }}>Consulta</option>
                            <option value="Entrega" {{ old('tipo_cita') == 'Entrega' ? 'selected' : '' }}>Entrega de Documentos</option>
                        </select>
                        @error('tipo_cita')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha y Hora -->
                    <div>
                        <label for="fecha_cita" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha y Hora <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="fecha_cita" name="fecha_cita" required
                               value="{{ old('fecha_cita') }}"
                               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-colors duration-200">
                        @error('fecha_cita')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Observaciones -->
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                            Observaciones
                        </label>
                        <textarea id="observaciones" name="observaciones" rows="4"
                                  placeholder="Observaciones adicionales sobre la cita..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#B4325E]/50 focus:border-[#B4325E] transition-colors duration-200 resize-none">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('citas.index') }}" 
                           class="inline-flex items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E] transition-colors duration-200">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-xl hover:shadow-md transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Agendar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validación de fecha mínima
    document.getElementById('fecha_cita').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const now = new Date();
        
        if (selectedDate <= now) {
            alert('La fecha y hora de la cita debe ser posterior a la fecha y hora actual.');
            this.value = '';
        }
    });
</script>
@endpush
@endsection 