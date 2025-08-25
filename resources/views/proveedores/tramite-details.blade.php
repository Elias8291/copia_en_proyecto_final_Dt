@extends('layouts.app')

@section('title', 'Detalles del Trámite - ' . $proveedor->user->nombre)

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-[#B4325E] to-[#7a1d37] rounded-lg p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                @if($tramite)
                                    Detalles del Último Trámite Aprobado
                                @else
                                    Información del Proveedor
                                @endif
                            </h1>
                            <p class="text-sm text-gray-500">Proveedor: {{ $proveedor->user->nombre }} ({{ $proveedor->rfc }})</p>
                            @if($tramite)
                                <p class="text-sm text-gray-500">Trámite: {{ $tramite->tipo_tramite }} - Aprobado el {{ $tramite->updated_at->format('d/m/Y H:i') }}</p>
                            @else
                                <p class="text-sm text-yellow-600">Sin trámites aprobados</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('proveedores.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] text-white rounded-lg font-medium hover:shadow-lg transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @if($datosCompletos)
                
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                   
                    @if($datosCompletos && isset($datosCompletos['datos_generales']) && !empty($datosCompletos['datos_generales']))
                        <x-forms.datos-generales :datos="$datosCompletos['datos_generales']" :editable="false" />
                    @else
                        <x-forms.datos-generales :datos="['rfc' => $proveedor->rfc]" :editable="false" />
                    @endif
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.domicilio :datos="($datosCompletos && isset($datosCompletos['direccion'])) ? $datosCompletos['direccion'] : []" :editable="false" />
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Actividades Económicas</h3>
                            <p class="text-sm text-gray-500">Actividades económicas y clasificación</p>
                        </div>
                    </div>
                    
                    <x-forms.actividades-economicas :datos="($datosCompletos && isset($datosCompletos['actividades_economicas'])) ? $datosCompletos['actividades_economicas'] : []" :editable="false" />
                </div> 
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">                       
                    <x-forms.constitucion :datos="($datosCompletos && isset($datosCompletos['constitucion'])) ? $datosCompletos['constitucion'] : []" :editable="false" />
                </div>

                
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    
                    <x-forms.apoderado :datos="($datosCompletos && isset($datosCompletos['apoderado_legal'])) ? $datosCompletos['apoderado_legal'] : []" :editable="false" />
                </div>                
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    
                    <x-forms.accionistas :datos="($datosCompletos && isset($datosCompletos['accionistas'])) ? $datosCompletos['accionistas'] : []" :editable="false" />
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <x-forms.documentos :datos="($datosCompletos && isset($datosCompletos['documentos'])) ? $datosCompletos['documentos'] : []" :editable="false" />
                </div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Estado de Secciones</h3>
                            <p class="text-sm text-gray-500">Estado de cada sección del trámite</p>
                        </div>
                    </div>
                    <x-forms.estado-seccion :datos="($datosCompletos && isset($datosCompletos['estado_seccion'])) ? $datosCompletos['estado_seccion'] : []" :editable="false" />
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="p-8 text-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            No se encontraron datos del trámite
                        </h3>
                        <p class="text-gray-600">
                            No se pudieron cargar los datos completos del último trámite aprobado.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 