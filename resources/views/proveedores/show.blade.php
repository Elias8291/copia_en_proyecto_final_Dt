@extends('layouts.app')

@section('title', 'Detalles del Proveedor - ' . $proveedor->razon_social)

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        
        <!-- Header del Proveedor -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg p-3">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                {{ $proveedor->razon_social }}
                            </h1>
                            <p class="text-sm text-gray-500">RFC: {{ $proveedor->rfc }} | Tipo: {{ $proveedor->tipo_persona }}</p>
                            <p class="text-sm text-gray-500">Estado: 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $proveedor->estado_padron === 'Activo' ? 'bg-green-100 text-green-800' : 
                                       ($proveedor->estado_padron === 'Inactivo' ? 'bg-red-100 text-red-800' : 
                                       ($proveedor->estado_padron === 'Vencido' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ $proveedor->estado_padron }}
                                </span>
                            </p>
                            @if($ultimoTramite)
                                <p class="text-sm text-gray-500 mt-1">
                                    Último Trámite: {{ $ultimoTramite->tipo_tramite }} ({{ $ultimoTramite->status }}) 
                                    - {{ $ultimoTramite->created_at->format('d/m/Y H:i') }}
                                </p>
                            @else
                                <p class="text-sm text-yellow-600 mt-1">Sin trámites registrados</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                        <a href="{{ route('proveedores.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9d2449] to-[#7a1a37] text-white rounded-lg font-medium hover:shadow-lg transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Información Básica del Proveedor -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">PV Número</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $proveedor->pv_numero ?? 'No asignado' }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">Fecha de Alta</h3>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('d/m/Y') : 'No definida' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">Fecha de Vencimiento</h3>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'No definida' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500">Total de Trámites</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $proveedor->tramites->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($datosCompletos && $ultimoTramite)
            <div class="space-y-6">
                <!-- Datos Generales -->
                @if(!empty($datosCompletos['datos_generales']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.datos-generales :datos="$datosCompletos['datos_generales']" :editable="false" />
                </div>
                @endif

                <!-- Domicilio -->
                @if(!empty($datosCompletos['direccion']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.domicilio :datos="$datosCompletos['direccion']" :editable="false" />
                </div>
                @endif



                <!-- Actividades Económicas -->
                @if(!empty($datosCompletos['actividades_economicas']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.actividades-economicas :datos="$datosCompletos['actividades_economicas']" :editable="false" />
                </div>
                @endif

                <!-- Constitución (solo para personas morales) -->
                @if($proveedor->tipo_persona === 'Moral' && !empty($datosCompletos['constitucion']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.constitucion :datos="$datosCompletos['constitucion']" :editable="false" />
                </div>
                @endif

                <!-- Apoderado Legal (solo para personas morales) -->
                @if($proveedor->tipo_persona === 'Moral' && !empty($datosCompletos['apoderado_legal']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.apoderado :datos="$datosCompletos['apoderado_legal']" :editable="false" />
                </div>
                @endif

                <!-- Accionistas (solo para personas morales) -->
                @if($proveedor->tipo_persona === 'Moral' && !empty($datosCompletos['accionistas']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.accionistas :datos="$datosCompletos['accionistas']" :editable="false" />
                </div>
                @endif

                <!-- Archivos/Documentos -->
                @if(!empty($datosCompletos['documentos']))
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.archivos :datos="$datosCompletos['documentos']" :editable="false" />
                </div>
                @endif
            </div>
        @else
            <!-- Estado sin trámites -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Sin trámites registrados</h3>
                <p class="text-gray-500">Este proveedor no tiene trámites asociados aún.</p>
            </div>
        @endif
    </div>
</div>
@endsection
