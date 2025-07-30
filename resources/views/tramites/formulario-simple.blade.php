@extends('layouts.app')

@section('title', $titulo ?? 'Formulario de Trámite')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        
        <!-- Header del Formulario -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-[#B4325E] to-[#7a1d37] rounded-lg p-3">
                            @if ($tipo_tramite === 'inscripcion')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @elseif($tipo_tramite === 'renovacion')
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $titulo }}</h1>
                            <p class="text-sm text-gray-500">{{ $descripcion }}</p>
                            @if($es_correccion ?? false)
                                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Modo Corrección: Realice los cambios solicitados.
                                    @if(($tramite->correcciones_count ?? 0) > 0)
                                        <span class="ml-2 px-2 py-0.5 bg-amber-200 text-amber-800 rounded-full text-xs font-medium">
                                            Corrección #{{ ($tramite->correcciones_count ?? 0) + 1 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($tipo_tramite) }}
                        </span>
                        @if ($proveedor)
                            <span class="text-sm text-gray-500">{{ $proveedor->razon_social }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicador de Progreso -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-700">Progreso del formulario</span>
                <span class="text-sm text-gray-500" id="progress-text">Paso 1 de 5</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-[#B4325E] to-[#7a1d37] h-2 rounded-full transition-all duration-300" id="progress-bar" style="width: 20%"></div>
            </div>
        </div>

        @php
            $proveedorService = app(\App\Services\ProveedorService::class);
            $rfcUsuario = Auth::user()->rfc ?? ($datosSat['rfc'] ?? '');
            $tipoPersona = $proveedorService->getTipoPersona($proveedor);
            
            if (!$tipoPersona && $proveedor && $proveedor->rfc) {
                $tipoPersona = $proveedorService->calcularTipoPersonaPorRfc($proveedor);
            }
            
            $tipoPersona = $tipoPersona ?: 'Física';
            $esPersonaMoral = $tipoPersona === 'Moral';
        @endphp

        <!-- Formulario Principal -->
        <form id="tramite-form" method="POST" action="{{ $es_correccion ?? false ? route('tramites.actualizar.correccion', $tramite->id) : route('tramites.store', $tipo_tramite) }}" enctype="multipart/form-data">
            @csrf
            @if($es_correccion ?? false)
                @method('POST')
            @endif
            <input type="hidden" name="tipo_persona" value="{{ $tipoPersona }}">
            <input type="hidden" name="confirma_datos" value="on">
            <input type="hidden" name="formulario_simple" value="true">

            <!-- Aviso General de Errores -->
            @if ($errors->any())
                <div class="mb-6 flex items-center justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-full shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <span class="text-sm font-semibold text-amber-800">
                                    Se encontraron errores en el formulario
                                </span>
                                <span class="text-xs text-amber-600 ml-2">
                                    • Revise cada sección para corregir los errores
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-8">
                
                <!-- Sección 1: Datos Generales -->
                <div id="datos-generales" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6" data-step="1">
                    <div class="flex items-center space-x-3 mb-6">
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
                    
                    @include('tramites.partials.datos-generales', [
                        'tipo' => $tipo_tramite,
                        'proveedor' => $proveedor,
                        'datosSat' => $datosSat,
                        'editable' => true,
                        'tramite' => $tramite ?? null,
                    ])
                </div>

                <!-- Sección 2: Actividades -->
                <div id="actividades" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden" data-step="2">
                    <div class="flex items-center space-x-3 mb-6">
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
                    
                    @include('tramites.partials.actividades-economicas', [
                        'tipo' => $tipo_tramite,
                        'proveedor' => $proveedor,
                        'editable' => true,
                        'tramite' => $tramite ?? null,
                    ])
                </div>

                <!-- Sección 3: Domicilio -->
                <div id="domicilio" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden" data-step="3">
                    <div class="flex items-center space-x-3 mb-6">
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
                    
                    @include('tramites.partials.domicilio', [
                        'tipo' => $tipo_tramite,
                        'proveedor' => $proveedor,
                        'datosSat' => $datosSat,
                        'editable' => true,
                        'tramite' => $tramite ?? null,
                    ])
                </div>

                @if($esPersonaMoral)
                    <!-- Sección 4: Constitución -->
                    <div id="constitucion" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden" data-step="4">
                        <div class="flex items-center space-x-3 mb-6">
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
                        
                        @include('tramites.partials.constitucion', [
                            'tipo' => $tipo_tramite,
                            'proveedor' => $proveedor,
                            'editable' => true,
                            'tramite' => $tramite ?? null,
                        ])
                    </div>

                    <!-- Sección 5: Apoderado Legal -->
                    <div id="apoderado" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden" data-step="5">
                        <div class="flex items-center space-x-3 mb-6">
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
                        
                        @include('tramites.partials.apoderado', [
                            'tipo' => $tipo_tramite,
                            'proveedor' => $proveedor,
                            'editable' => true,
                            'tramite' => $tramite ?? null,
                        ])
                    </div>

                    <!-- Sección 6: Accionistas -->
                    <div id="accionistas" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden" data-step="6">
                        <div class="flex items-center space-x-3 mb-6">
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
                        
                        @include('tramites.partials.accionistas', [
                            'tipo' => $tipo_tramite,
                            'proveedor' => $proveedor,
                            'editable' => true,
                            'tramite' => $tramite ?? null,
                        ])
                    </div>
                @endif

                <!-- Sección Final: Documentos -->
                <div id="documentos" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-6 hidden" data-step="{{ $esPersonaMoral ? '7' : '4' }}">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Documentos</h3>
                            <p class="text-sm text-gray-500">Documentos requeridos - Todos deben estar aprobados</p>
                        </div>
                    </div>
                    
                    @include('tramites.partials.documentos', [
                        'tipo' => $tipo_tramite,
                        'proveedor' => $proveedor,
                        'editable' => true,
                        'tipoPersona' => $tipoPersona,
                        'tramite' => $tramite ?? null,
                    ])
                </div>

                <!-- Botones de Navegación -->
                <div class="flex justify-between items-center mt-8">
                    <button type="button" id="btn-anterior" class="hidden inline-flex items-center px-6 py-3 bg-gray-600 text-white text-lg font-semibold rounded-lg shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-600/30 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Anterior
                    </button>
                    
                    <div class="flex space-x-4">
                        <button type="button" id="btn-siguiente" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#8a203f] text-white text-lg font-semibold rounded-lg shadow-lg hover:from-[#8a203f] hover:to-[#7a1d37] focus:outline-none focus:ring-4 focus:ring-[#9d2449]/30 transition-all duration-300 transform hover:scale-105">
                            Siguiente
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <button type="submit" id="btn-enviar" class="hidden inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-lg font-semibold rounded-lg shadow-lg hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-4 focus:ring-green-600/30 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            {{ $es_correccion ?? false ? 'Reenviar Trámite' : 'Enviar Trámite' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/tramites/form-navigator.js') }}"></script>
<script src="{{ asset('js/tramites/handlers/actividades-buscar.js') }}"></script>
<script src="{{ asset('js/test-actividades.js') }}"></script>
<script src="{{ asset('js/test-codigo-postal.js') }}"></script>
<script src="{{ asset('js/test-documentos.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el buscador de actividades
    if (typeof ActividadesBuscar !== 'undefined') {
        window.actividadesBuscarInstance = new ActividadesBuscar();
    }
    
    // Asegurar que el buscador se inicialice cuando se muestre la sección de actividades
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const actividadesSection = document.getElementById('actividades');
                if (actividadesSection && !actividadesSection.classList.contains('hidden')) {
                    // Si la sección de actividades se muestra y no hay instancia del buscador
                    if (!window.actividadesBuscarInstance && typeof ActividadesBuscar !== 'undefined') {
                        window.actividadesBuscarInstance = new ActividadesBuscar();
                    }
                }
            }
        });
    });
    
    // Observar cambios en la sección de actividades
    const actividadesSection = document.getElementById('actividades');
    if (actividadesSection) {
        observer.observe(actividadesSection, { attributes: true });
    }
});
</script>
@endpush

@endsection 