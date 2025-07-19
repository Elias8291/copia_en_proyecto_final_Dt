@extends('layouts.app')

@section('title', 'Prueba de Servicios de Proveedor')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white rounded-xl shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Prueba de Servicios de Proveedor</h1>
        
        <div class="space-y-6">
            <!-- Información del Proveedor -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-3">Información del Proveedor</h2>
                
                @if($proveedor)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">RFC:</p>
                            <p class="font-medium">{{ $proveedor->rfc }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tipo de Persona:</p>
                            <p class="font-medium">{{ ucfirst($proveedor->tipo_persona) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Razón Social:</p>
                            <p class="font-medium">{{ $proveedor->razon_social }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Estado en Padrón:</p>
                            <p class="font-medium">{{ $proveedor->estado_padron }}</p>
                        </div>
                        @if($proveedor->tipo_persona === 'fisica')
                        <div>
                            <p class="text-sm text-gray-600">CURP:</p>
                            <p class="font-medium">{{ $proveedor->curp }}</p>
                        </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 italic">No hay información de proveedor disponible.</p>
                @endif
            </div>
            
            <!-- Datos del Servicio -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-3">Datos del Servicio</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">¿Puede realizar inscripción?</p>
                        <p class="font-medium">{{ $puedeInscribirse ? 'Sí' : 'No' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Trámites disponibles:</p>
                        @if(count($tramitesDisponibles) > 0)
                            <ul class="list-disc list-inside">
                                @foreach($tramitesDisponibles as $tramite)
                                    <li class="text-sm">{{ $tramite }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No hay trámites disponibles.</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Enlaces a Formularios -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h2 class="text-lg font-semibold mb-3">Enlaces a Formularios</h2>
                
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('tramites.inscripcion') }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                        Ir al formulario de inscripción
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection