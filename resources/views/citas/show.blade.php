@extends('layouts.app')

@section('title', 'Detalles de Cita')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Cita #{{ $cita->id }}</h2>
                <a href="{{ route('citas.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300">
                    Volver
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Información de la Cita</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="font-medium text-gray-700">Usuario:</span>
                            <span class="ml-2">{{ $cita->user->nombre ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Tipo:</span>
                            <span class="ml-2">{{ $cita->tipo_cita }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Estado:</span>
                            <span class="ml-2">{{ $cita->estado }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Fecha:</span>
                            <span class="ml-2">{{ $cita->fecha_cita->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Motivo:</span>
                            <p class="ml-2 mt-1">{{ $cita->motivo }}</p>
                        </div>
                        @if($cita->observaciones)
                        <div>
                            <span class="font-medium text-gray-700">Observaciones:</span>
                            <p class="ml-2 mt-1">{{ $cita->observaciones }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex space-x-4">
                    <a href="{{ route('citas.edit', $cita) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Editar
                    </a>
                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" onclick="return confirm('¿Está seguro?')">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 