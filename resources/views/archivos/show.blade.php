@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 -mt-4">
    <!-- Header mejorado -->
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 mb-4">
        <div class="p-4 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-primary via-primary-dark to-primary-light rounded-xl p-2 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-800">Detalles del Catálogo</h1>
                        <p class="text-base text-gray-500 mt-1">Información completa del catálogo de archivo</p>
                    </div>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center space-y-2 lg:space-y-0 lg:space-x-3">
                    <a href="{{ route('archivos.edit', $archivo) }}" 
                       class="px-4 py-2 text-sm font-semibold text-blue-600 bg-white border border-blue-300 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('archivos.index') }}" 
                       class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Catálogo -->
    <div class="bg-gray-50/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-200/50 overflow-hidden">
        <!-- Header del Card -->
        <div class="bg-gradient-to-r from-slate-100 to-slate-50 px-4 py-3 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center">
                    <span class="text-lg font-bold text-white">{{ strtoupper(substr($archivo->nombre, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $archivo->nombre }}</h2>
                    <p class="text-gray-600 text-sm">{{ $archivo->descripcion }}</p>
                </div>
                <div class="ml-auto">
                    @if($archivo->es_visible)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Visible
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293-4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Oculto
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-4 space-y-4">
            <!-- Información del Catálogo -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Información del Catálogo</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Nombre del Catálogo</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ $archivo->nombre }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Tipo de Persona</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ $archivo->tipo_persona }}</p>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Descripción</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ $archivo->descripcion }}</p>
                    </div>
                </div>
            </div>

            <!-- Configuración -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Configuración</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Formato de Archivo</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">{{ strtoupper($archivo->tipo_archivo) }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 border border-gray-200/60">
                        <div class="flex items-center mb-2">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Estado</span>
                        </div>
                        <p class="text-lg font-semibold {{ $archivo->es_visible ? 'text-green-600' : 'text-red-600' }}">
                            {{ $archivo->es_visible ? 'Visible en el sistema' : 'Oculto del sistema' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="border-b border-gray-100 pb-4">
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Información del Sistema</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">ID</span>
                        </div>
                        <p class="text-sm font-bold text-gray-900">#{{ $archivo->id }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Creado</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $archivo->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $archivo->created_at->format('H:i') }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Actualizado</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $archivo->updated_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $archivo->updated_at->format('H:i') }}</p>
                    </div>

                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2.5 border border-gray-200/60">
                        <div class="flex items-center mb-1.5">
                            <span class="text-base font-semibold text-gray-600 uppercase tracking-wide">Visibilidad</span>
                        </div>
                        <p class="text-sm font-semibold {{ $archivo->es_visible ? 'text-green-600' : 'text-red-600' }}">
                            {{ $archivo->es_visible ? 'Visible' : 'Oculto' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Archivos Asociados -->
            <div>
                <div class="flex items-center mb-3">
                    <div class="w-5 h-5 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Archivos Asociados</h3>
                </div>
                
                @if($archivo->archivos->count() > 0)
                    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-3 border border-yellow-200/60">
                        <div class="flex items-center">
                            <div>
                                <p class="text-base font-semibold text-yellow-800">{{ $archivo->archivos->count() }} {{ $archivo->archivos->count() == 1 ? 'archivo asociado' : 'archivos asociados' }}</p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    <strong>Nota:</strong> No se puede eliminar un catálogo que tenga archivos asociados.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200/60">
                        <div class="flex items-center">
                            <div>
                                <p class="text-base font-semibold text-blue-800">Sin archivos asociados</p>
                                <p class="text-xs text-blue-600 mt-1">Este catálogo puede ser eliminado de forma segura.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 