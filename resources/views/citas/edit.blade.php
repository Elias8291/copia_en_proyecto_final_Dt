@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Editar Cita #{{ $cita->id }}</h2>

            <form action="{{ route('citas.update', $cita) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Usuario</label>
                    <select id="user_id" name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="">Seleccionar usuario</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ $cita->user_id == $user->id ? 'selected' : '' }}>{{ $user->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="fecha_cita" class="block text-sm font-medium text-gray-700 mb-2">Fecha y Hora</label>
                    <input type="datetime-local" id="fecha_cita" name="fecha_cita" value="{{ $cita->fecha_cita->format('Y-m-d\TH:i') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="tipo_cita" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cita</label>
                    <input type="text" id="tipo_cita" name="tipo_cita" value="{{ $cita->tipo_cita }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <input type="text" id="estado" name="estado" value="{{ $cita->estado }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">Motivo</label>
                    <textarea id="motivo" name="motivo" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ $cita->motivo }}</textarea>
                </div>

                <div class="mb-6">
                    <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ $cita->observaciones }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('citas.index') }}" class="px-4 py-2 text-gray-600 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Actualizar Cita</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 