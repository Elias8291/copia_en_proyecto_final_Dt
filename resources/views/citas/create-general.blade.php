@extends('layouts.app')

@section('title', 'Crear Nueva Cita')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                        <p class="text-sm text-gray-500">Selecciona un trámite para agendar una cita</p>
                    </div>
                </div>
                <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Crear Nueva Cita</h2>
                    <p class="text-sm text-gray-600 mb-6">Selecciona una opción para continuar con el agendamiento de la cita.</p>
                </div>

                <!-- Opción para Cita General -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200 mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-100 rounded-xl p-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Cita General</h3>
                                <p class="text-sm text-gray-600">Crear una cita para consultas, reuniones administrativas u otros propósitos</p>
                            </div>
                        </div>
                        <a href="{{ route('citas.create.general') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:shadow-md transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Crear Cita General
                        </a>
                    </div>
                </div>

                @if($tramites->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Trámites Disponibles para Cita</h3>
                        <p class="text-sm text-gray-600 mb-6">Selecciona un trámite para continuar con el agendamiento de la cita.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tramites as $tramite)
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 hover:border-[#B4325E]/30 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                            Trámite #{{ $tramite->id }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3">
                                            <strong>Proveedor:</strong> {{ $tramite->proveedor->user->name ?? 'N/A' }}
                                        </p>
                                        <div class="flex items-center space-x-2 mb-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($tramite->estado === 'Por_Cotejar') bg-blue-100 text-blue-800
                                                @elseif($tramite->estado === 'En_Revision') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ str_replace('_', ' ', $tramite->estado) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            Creado: {{ $tramite->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-3">
                                    <a href="{{ route('citas.create.tramite', $tramite) }}" 
                                       class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg hover:shadow-md transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Agendar Cita
                                    </a>
                                    <a href="{{ route('revision.show', $tramite) }}" 
                                       class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#B4325E]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay trámites disponibles</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            No hay trámites en estado "Por Cotejar" o "En Revisión" para agendar citas.
                        </p>
                        <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#B4325E] to-[#7a1d37] rounded-lg hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#B4325E]/50 focus:ring-offset-2">
                            Volver a Citas
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 