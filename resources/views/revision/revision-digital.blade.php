@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/revision-panels.css') }}">
@endpush

@section('content')
    @php
        $proveedorService = app(\App\Services\ProveedorService::class);
        $tipoPersona = $proveedorService->getTipoPersona($tramite->proveedor);
        
        if (!$tipoPersona && $tramite->proveedor && $tramite->proveedor->rfc) {
            $tipoPersona = $proveedorService->calcularTipoPersonaPorRfc($tramite->proveedor);
        }
        
        $tipoPersona = $tipoPersona ?: 'Física';
        $esPersonaMoral = $tipoPersona === 'Moral';
    @endphp
    
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
            
            <!-- Header -->
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
                                <h1 class="text-2xl font-bold text-gray-800">Revisión de Datos del Trámite</h1>
                                <p class="text-sm text-gray-500">Tipo: <span class="font-medium text-[#9D2449]">{{ $tipoPersona }}</span></p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $tramite->estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($tramite->estado === 'En_Revision' ? 'bg-blue-100 text-blue-800' : 
                                   ($tramite->estado === 'Aprobado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                {{ str_replace('_', ' ', $tramite->estado) }}
                            </span>
                            <a href="{{ route('revision.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#B4325E] to-[#7a1d37] text-white rounded-lg font-medium hover:shadow-lg transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instrucciones de Revisión -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-700">
                        <strong>Instrucciones:</strong> Revise cada sección, use "Aprobar" o "Rechazar", agregue observaciones si es necesario. 
                        Verifique RFC válido, dirección completa, actividades correctas y documentos requeridos.
                        @if($esPersonaMoral) Para personas morales: verificar constitución y apoderado. @endif
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                
                <!-- Sección Datos Generales -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 min-h-[600px]">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Datos Generales</h3>
                                <p class="text-sm text-gray-500">Información personal y de contacto</p>
                            </div>
                        </div>
                        
                        <!-- Botón para mostrar/ocultar comparador -->
                        <button type="button" 
                                onclick="toggleComparador('datosGenerales')"
                                class="hidden md:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Comparar
                        </button>
                    </div>
                    
                    <div id="datosGeneralesGrid" class="grid grid-cols-1 gap-4">
                        <div id="datosGeneralesMain" class="transition-all duration-300">
                            @include('revision.partials.datos-generales', [
                                'tramite' => $tramite,
                                'proveedor' => $tramite->proveedor,
                                'editable' => true,
                            ])
                        </div>
                        
                        <!-- Componente comparador de documentos -->
                        <x-documento-comparador 
                            :documentos="$tramite->archivos"
                            seccion="datosGenerales"
                            :mostrar="false"
                            :tramiteId="$tramite->id" />
                    </div>
                    
                    @include('revision.partials.revision-panel', ['seccion' => 'datos_generales'])
                </div>

                <!-- Sección Domicilio -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Domicilio</h3>
                                <p class="text-sm text-gray-500">Dirección completa del solicitante</p>
                            </div>
                        </div>
                        
                        <!-- Botón para mostrar/ocultar comparador -->
                        <button type="button" 
                                onclick="toggleComparador('domicilio')"
                                class="hidden md:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Comparar
                        </button>
                    </div>
                    
                    <div id="domicilioGrid" class="grid grid-cols-1 gap-4">
                        <div id="domicilioMain" class="transition-all duration-300">
                            @include('revision.partials.domicilio', [
                                'tramite' => $tramite,
                                'direccion' => $tramite->direcciones->first(),
                                'editable' => true,
                            ])
                        </div>
                        
                        <!-- Componente comparador de documentos -->
                        <x-documento-comparador 
                            :documentos="$tramite->archivos"
                            seccion="domicilio"
                            :mostrar="false"
                            :tramiteId="$tramite->id" />
                    </div>
                    
                    @include('revision.partials.revision-panel', ['seccion' => 'domicilio'])
                </div>

                <!-- Sección Actividades -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Actividades</h3>
                                <p class="text-sm text-gray-500">Actividades económicas y clasificación</p>
                            </div>
                        </div>
                        
                        <!-- Botón para mostrar/ocultar comparador -->
                        <button type="button" 
                                onclick="toggleComparador('actividades')"
                                class="hidden md:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Comparar
                        </button>
                    </div>
                    
                    <div id="actividadesGrid" class="grid grid-cols-1 gap-4">
                        <div id="actividadesMain" class="transition-all duration-300">
                            @include('revision.partials.actividades', [
                                'tramite' => $tramite,
                                'actividades' => $tramite->actividades ?? [],
                                'editable' => true,
                            ])
                        </div>
                        
                        <!-- Componente comparador de documentos -->
                        <x-documento-comparador 
                            :documentos="$tramite->archivos"
                            seccion="actividades"
                            :mostrar="false"
                            :tramiteId="$tramite->id" />
                    </div>
                    
                    @include('revision.partials.revision-panel', ['seccion' => 'actividades'])
                </div>

                @if($esPersonaMoral)
                    <!-- Sección Constitución -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Constitución</h3>
                                    <p class="text-sm text-gray-500">Datos de constitución</p>
                                </div>
                            </div>
                            
                            <!-- Botón para mostrar/ocultar comparador -->
                            <button type="button" 
                                    onclick="toggleComparador('constitucion')"
                                    class="hidden md:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Comparar
                            </button>
                        </div>
                        
                        <div id="constitucionGrid" class="grid grid-cols-1 gap-4">
                            <div id="constitucionMain" class="transition-all duration-300">
                                @include('revision.partials.constitucion', [
                                    'tramite' => $tramite,
                                    'editable' => true,
                                ])
                            </div>
                            
                            <!-- Componente comparador de documentos -->
                            <x-documento-comparador 
                                :documentos="$tramite->archivos"
                                seccion="constitucion"
                                :mostrar="false"
                                :tramiteId="$tramite->id" />
                        </div>
                        
                        @include('revision.partials.revision-panel', ['seccion' => 'constitucion'])
                    </div>

                    <!-- Sección Apoderado Legal -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Apoderado Legal</h3>
                                    <p class="text-sm text-gray-500">Información del representante legal</p>
                                </div>
                            </div>
                            
                            <!-- Botón para mostrar/ocultar comparador -->
                            <button type="button" 
                                    onclick="toggleComparador('apoderado')"
                                    class="hidden md:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Comparar
                            </button>
                        </div>
                        
                        <div id="apoderadoGrid" class="grid grid-cols-1 gap-4">
                            <div id="apoderadoMain" class="transition-all duration-300">
                                @include('revision.partials.apoderado', [
                                    'tramite' => $tramite,
                                    'editable' => true,
                                ])
                            </div>
                            
                            <!-- Componente comparador de documentos -->
                            <x-documento-comparador 
                                :documentos="$tramite->archivos"
                                seccion="apoderado"
                                :mostrar="false"
                                :tramiteId="$tramite->id" />
                        </div>
                        
                        @include('revision.partials.revision-panel', ['seccion' => 'apoderado'])
                    </div>

                    <!-- Sección Accionistas -->
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Accionistas</h3>
                                    <p class="text-sm text-gray-500">Información de socios y accionistas</p>
                                </div>
                            </div>
                            
                            <!-- Botón para mostrar/ocultar comparador -->
                            <button type="button" 
                                    onclick="toggleComparador('accionistas')"
                                    class="hidden md:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Comparar
                            </button>
                        </div>
                        
                        <div id="accionistasGrid" class="grid grid-cols-1 gap-4">
                            <div id="accionistasMain" class="transition-all duration-300">
                                @include('revision.partials.accionistas', [
                                    'tramite' => $tramite,
                                    'editable' => true,
                                ])
                            </div>
                            
                            <!-- Componente comparador de documentos -->
                            <x-documento-comparador 
                                :documentos="$tramite->archivos"
                                seccion="accionistas"
                                :mostrar="false"
                                :tramiteId="$tramite->id" />
                        </div>
                        
                        @include('revision.partials.revision-panel', ['seccion' => 'accionistas'])
                    </div>
                @endif

                <!-- Sección Documentos -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Documentos</h3>
                            <p class="text-sm text-gray-500">Documentos requeridos</p>
                        </div>
                    </div>
                    
                    <div id="documentosGrid">
                        <div id="documentosMain" class="transition-all duration-300">
                            @include('revision.partials.documentos', [
                                'tramite' => $tramite,
                                'documentos' => $tramite->archivos ?? [],
                                'editable' => true,
                            ])
                        </div>
                    </div>
                    
                    @include('revision.partials.revision-panel', ['seccion' => 'documentos'])
                </div>

                <!-- Comentario General -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Comentario General</h3>
                            <p class="text-sm text-gray-500">Observaciones generales</p>
                        </div>
                    </div>

                    @if (isset($tramite->comentarios_revision) && $tramite->comentarios_revision)
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">{{ $tramite->comentarios_revision }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('revision.comentario-general') }}" method="POST" class="bg-white rounded-lg border border-gray-200">
                        @csrf
                        <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">
                        <div class="p-4">
                            <div class="space-y-2">
                                <label for="comentario_general" class="block text-sm font-medium text-gray-700">
                                    Comentario General del Trámite
                                </label>
                                <div class="relative">
                                    <textarea id="comentario_general" name="comentario" rows="4"
                                        class="block w-full px-3 py-2 pr-20 border border-gray-300 rounded-md text-sm placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"
                                        placeholder="Escriba un comentario general sobre el trámite...">{{ old('comentario', $tramite->comentarios_revision ?? '') }}</textarea>
                                    <button type="submit"
                                        class="absolute bottom-2 right-2 inline-flex items-center px-3 py-1 bg-[#9D2449] text-white rounded text-sm font-medium hover:bg-[#7A1D3A] focus:outline-none transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Guardar
                                    </button>
                                </div>
                            </div>
                            @if(session('comentario_success'))
                                <div class="text-sm text-center font-medium text-emerald-600 mt-2">
                                    <div class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ session('comentario_success') }}
                                    </div>
                                </div>
                            @endif
                            @if(session('comentario_error'))
                                <div class="text-sm text-center font-medium text-red-600 mt-2">
                                    <div class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ session('comentario_error') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>

                    <!-- Acciones de Revisión -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h4 class="text-base font-semibold text-gray-900 mb-2">Acciones de Revisión</h4>
                        <p class="text-sm text-gray-500 mb-4">Seleccione la acción a realizar con este trámite</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            
                            <form id="form_por_cotejar" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST">
                                @csrf
                                <input type="hidden" name="nuevo_estado" value="Por_Cotejar">
                                <input type="hidden" name="observaciones" id="observaciones_por_cotejar">
                                <button type="button" onclick="showConfirmModal('Aceptar y Enviar a Cotejo Presencial', '¿Está seguro que desea aceptar y enviar a cotejo presencial este trámite?', 'form_por_cotejar')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold shadow-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="hidden sm:inline">Aceptar y Enviar a Cotejo Presencial</span>
                                    <span class="sm:hidden">Cotejar</span>
                                </button>
                            </form>
                            
                            <form id="form_rechazar" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST">
                                @csrf
                                <input type="hidden" name="nuevo_estado" value="Rechazado">
                                <input type="hidden" name="observaciones" id="observaciones_rechazar">
                                <button type="button" onclick="showConfirmModal('Rechazar Trámite', '¿Está seguro que desea rechazar este trámite?', 'form_rechazar')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-700 text-white rounded-lg font-semibold shadow-md text-sm hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-700 focus:ring-offset-2 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span class="hidden sm:inline">Rechazar Trámite</span>
                                    <span class="sm:hidden">Rechazar</span>
                                </button>
                            </form>
                            
                            <form id="form_para_correccion" action="{{ route('revision.cambiar-estado', $tramite) }}" method="POST">
                                @csrf
                                <input type="hidden" name="nuevo_estado" value="Para_Correccion">
                                <input type="hidden" name="observaciones" id="observaciones_para_correccion">
                                <button type="button" onclick="showConfirmModal('Para Corrección', '¿Está seguro que desea solicitar correcciones para este trámite?', 'form_para_correccion')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-500 text-gray-900 rounded-lg font-semibold shadow-md text-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="hidden lg:inline">Para Corrección</span>
                                    <span class="lg:hidden">Corregir</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales -->
    <x-modal-confirmacion id="modal-confirmacion" title="Confirmar acción" message="¿Está seguro que desea realizar esta acción?" confirm-text="Confirmar" cancel-text="Cancelar" />
    <x-modal-exito id="modal-exito" title="¡Trámite procesado!" message="El trámite ha sido procesado exitosamente." accept-text="Ir al listado" :redirect-url="route('revision.index')" />
    <x-modal-exito id="modal-cita-agendada" title="¡Cita Agendada!" message="Se ha agendado automáticamente una cita para cotejo presencial." accept-text="Ver Cita" :redirect-url="route('citas.index')" />
    <x-modal-error id="modal-error" title="Error" message="Ha ocurrido un error al procesar su solicitud." button-text="OK" />
    <x-modal-eliminar id="modal-eliminar" title="Confirmar eliminación" message="¿Está seguro que desea eliminar este elemento? Esta acción no se puede deshacer." confirm-text="Eliminar" cancel-text="Cancelar" />
@endsection

@push('scripts')
    <script src="{{ asset('js/revision/revision-digital.js') }}"></script>
    <script src="{{ asset('js/revision/documento-comparador.js') }}"></script>
    <script>
        window.tramiteId = {{ $tramite->id }};
        window.csrfToken = "{{ csrf_token() }}";
        window.revisionSeccionComentarioRoute = "{{ route('revision.seccion.comentario') }}";
    </script>
@endpush
