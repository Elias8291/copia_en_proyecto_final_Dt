@extends('layouts.app')

@section('title', $titulo ?? 'Formulario de Trámite')

@section('content')
<div class="min-h-screen">
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

            <!-- Errores Generales del Formulario -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-red-800 mb-2">
                                Se encontraron {{ $errors->count() }} error(es) en el formulario:
                            </h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start">
                                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif



            <div class="space-y-8 sm:space-y-12">
                    <!-- Sección 1: Datos Generales -->
                <div id="datos-generales" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Datos Generales</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Información personal y de contacto</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="transition-all duration-300">
                            @include('tramites.partials.datos-generales', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'datosSat' => $datosSat,
                                'editable' => true,
                                'tramite' => $tramite ?? null,
                            ])
                        </div>
                    </div>

                <div class="border-t-2 border-gray-200 my-8"></div>

                <div id="actividades" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                                    </svg>
                                </div>
                                <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Actividades</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Actividades económicas y clasificación</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="transition-all duration-300">
                            @include('tramites.partials.actividades-economicas', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'editable' => true,
                                'tramite' => $tramite ?? null,
                            ])
                        </div>
                    </div>
                    <div class="border-t-2 border-gray-200 my-8"></div>
                    <div id="domicilio" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Domicilio</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Dirección completa del solicitante</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="transition-all duration-300">
                            @include('tramites.partials.domicilio', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'datosSat' => $datosSat,
                                'editable' => true,
                                'tramite' => $tramite ?? null,
                            ])
                        </div>

                    </div>

                    <div class="border-t-2 border-gray-200 my-8"></div>

                    @if($esPersonaMoral)
                        <div id="constitucion" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Constitución</h3>
                                        <p class="text-xs sm:text-sm text-gray-500">Datos de constitución</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="transition-all duration-300">
                                @include('tramites.partials.constitucion', [
                                    'tipo' => $tipo_tramite,
                                    'proveedor' => $proveedor,
                                    'editable' => true,
                                    'tramite' => $tramite ?? null,
                                ])
                            </div>
                        </div>
                        <div class="border-t-2 border-gray-200 my-8"></div>
                        <div id="apoderado" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Apoderado Legal</h3>
                                        <p class="text-xs sm:text-sm text-gray-500">Información del representante legal</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="transition-all duration-300">
                                @include('tramites.partials.apoderado', [
                                    'tipo' => $tipo_tramite,
                                    'proveedor' => $proveedor,
                                    'editable' => true,
                                    'tramite' => $tramite ?? null,
                                ])
                            </div>
                        </div>
                        <div class="border-t-2 border-gray-200 my-8"></div>
                        <div id="accionistas" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Accionistas</h3>
                                        <p class="text-xs sm:text-sm text-gray-500">Información de socios y accionistas</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="transition-all duration-300">
                                @include('tramites.partials.accionistas', [
                                    'tipo' => $tipo_tramite,
                                    'proveedor' => $proveedor,
                                    'editable' => true,
                                    'tramite' => $tramite ?? null,
                                ])
                            </div>
                        </div>
                    @endif
                    <div class="border-t-2 border-gray-200 my-8"></div>
                <div id="documentos" class="form-section bg-white rounded-lg shadow-md border border-gray-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-black rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Documentos</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Documentos requeridos - Todos deben estar aprobados</p>
                            </div>
                            </div>
                        </div>
                        
                        <div class="transition-all duration-300">
                            @include('tramites.partials.documentos', [
                                'tipo' => $tipo_tramite,
                                'proveedor' => $proveedor,
                                'editable' => true,
                                'tipoPersona' => $tipoPersona,
                                'tramite' => $tramite ?? null,
                            ])
                        </div>
                        

                    </div>
                    <div class="mt-8 flex justify-center">
                            <button type="submit" 
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#9d2449] to-[#8a203f] text-white text-lg font-semibold rounded-lg shadow-lg hover:from-[#8a203f] hover:to-[#7a1d37] focus:outline-none focus:ring-4 focus:ring-[#9d2449]/30 transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            {{ $es_correccion ?? false ? 'Reenviar Trámite' : 'Enviar Trámite' }}
                            </button>
                    </div>
        </form>
        
        
    </div>
</div>

@php
    // Helper function para mostrar errores de campos
    function showFieldError($errors, $field) {
        if ($errors->has($field)) {
            return '<div class="field-error-message mt-1">
                        <svg class="w-3 h-3 text-red-500 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-xs text-red-600">' . $errors->first($field) . '</span>
                    </div>';
        }
        return '';
    }
    
    // Helper function para agregar clases de error a inputs
    function getInputErrorClass($errors, $field) {
        return $errors->has($field) ? 'field-error border-red-500 bg-red-50' : '';
    }
    
    // Helper function para obtener el valor old o el valor por defecto
    function getOldValue($errors, $field, $default = '') {
        return old($field, $default);
    }
@endphp

@push('styles')
<style>
    /* Estilos personalizados para mejorar la experiencia */
    .form-section {
        scroll-margin-top: 2rem;
    }
    
    /* Animación suave al hacer scroll */
    html {
        scroll-behavior: smooth;
    }
    
    /* Estilo para campos requeridos */
    .required-field::after {
        content: " *";
        color: #ef4444;
    }
    
    /* Hover effects mejorados */
    .form-section:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }

    /* Separación mejorada entre secciones */
    .form-section {
        margin-bottom: 2rem;
    }
    
    /* Separador visual entre secciones */
    .border-t-2 {
        border-color: #e5e7eb;
    }
    
    /* Estilos para campos con errores */
    .error-field {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    /* Estilos para campos con errores */
    .field-error {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    /* Mensaje de error de campo */
    .field-error-message {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
    }
    
    .field-error-message::before {
        content: "⚠ ";
        margin-right: 0.25rem;
    }
    
    /* Animación de shake para errores */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .error-field {
        animation: shake 0.5s ease-in-out;
            }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/tramites/handlers/actividades-buscar.js') }}"></script>
<script src="{{ asset('js/tramites/handlers/codigo-postal-handler.js') }}"></script>
<script src="{{ asset('js/tramites/handlers/documentos-handler.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el buscador de actividades
    if (typeof ActividadesBuscar !== 'undefined') {
        window.actividadesBuscar = new ActividadesBuscar();
    }
    
    // Verificar que el handler de documentos esté cargado
    if (typeof handleFileUpload !== 'undefined') {
        console.log('Documentos handler cargado correctamente');
    } else {
        console.error('Documentos handler no encontrado');
    }
    
    // Manejo de errores de Laravel - Solo efectos visuales
    @if ($errors->any())
        // Si hay errores, hacer scroll a la primera sección con errores
        setTimeout(() => {
            const firstErrorField = document.querySelector('.field-error');
            if (firstErrorField) {
                const section = firstErrorField.closest('.form-section');
                if (section) {
                    section.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    
                    // Agregar efecto visual al campo con error
                    firstErrorField.classList.add('animate-pulse');
                    setTimeout(() => {
                        firstErrorField.classList.remove('animate-pulse');
                    }, 2000);
                }
            }
        }, 500);
        
        // Agregar tooltips a campos con errores
        const errorFields = document.querySelectorAll('.field-error');
        errorFields.forEach(field => {
            const errorMessage = field.querySelector('.field-error-message span');
            if (errorMessage) {
                field.title = errorMessage.textContent;
            }
        });
    @endif
    

});
</script>
@endpush


@endsection 