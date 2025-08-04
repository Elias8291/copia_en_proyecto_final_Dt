@extends('layouts.app')

@section('title', 'Estado de Trámites')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#9D2449]/5 via-white to-[#B91C1C]/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden border border-gray-200/70">
                <div class="p-4 sm:p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div class="bg-gradient-to-br from-[#9D2449] via-[#B91C1C] to-[#7a1d37] rounded-lg sm:rounded-xl p-2 sm:p-3 shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Estado de Trámites</h1>
                                <p class="text-xs sm:text-sm text-gray-500">RFC: {{ $rfc }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('tramites.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    @if($tramitesPendientes->count() > 0)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Trámites Pendientes</h2>
                            <div class="space-y-4">
                                @foreach($tramitesPendientes as $tramite)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="font-semibold text-yellow-800">
                                                    Trámite #{{ $tramite->id }} - {{ $tramite->tipo_tramite }}
                                                </h3>
                                                <p class="text-sm text-yellow-700 mt-1">
                                                    Proveedor: {{ $tramite->proveedor->rfc ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-yellow-700">
                                                    Fecha de inicio: {{ $tramite->fecha_inicio ? $tramite->fecha_inicio->format('d/m/Y H:i') : 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                    Pendiente
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Proveedores Registrados</h2>
                        <div class="space-y-4">
                            @forelse($proveedores as $proveedor)
                                <div class="bg-white border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">
                                                Proveedor #{{ $proveedor->id }} - {{ $proveedor->rfc }}
                                            </h3>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Tipo: {{ $proveedor->tipo_persona }} | 
                                                Estado: {{ $proveedor->estado_padron }} |
                                                Número: {{ $proveedor->pv_numero }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Vencimiento: {{ $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if($proveedor->estado_padron === 'Activo')
                                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    Activo
                                                </span>
                                            @elseif($proveedor->estado_padron === 'Vencido')
                                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                                    Vencido
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">
                                                    {{ $proveedor->estado_padron }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                                    <p class="text-gray-600">No hay proveedores registrados para este RFC.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 