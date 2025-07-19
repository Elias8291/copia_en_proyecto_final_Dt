@extends('layouts.app')

@section('title', 'Inscripción de Proveedor')

@section('content')
    <div class="min-h-screen py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 bg-white">
            <!-- Alertas de sesión -->
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700 text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-yellow-700 text-sm">{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-red-700 text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Header -->
            <div class="bg-gradient-to-r from-[#9d2449] to-[#be1558] px-8 py-6 text-center rounded-2xl shadow-xl mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white/20 rounded-xl mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Inscripción al Padrón</h1>
                <p class="text-pink-100 text-sm">Complete la información requerida para su registro</p>
            </div>

            <!-- Secciones del formulario -->
            <div class="px-4 sm:px-0 mb-8">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Complete todas las secciones del formulario en orden</p>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 mb-6">
                <form id="inscripcionForm" method="POST" action="#" class="min-h-[600px]">
                    @csrf
                    
                    {{-- Incluir componentes de formulario --}}
                    <x-formularios.datos-generales />
                    <x-formularios.domicilio />
                    <x-formularios.constitucion />
                    <x-formularios.accionistas />
                    <x-formularios.apoderado />
                    <x-formularios.documentos />
                </form>
            </div>

            <!-- Botón de Enviar -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 px-4 sm:px-8 py-4 sm:py-6 flex justify-center">
                <button type="submit" id="btn-enviar"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#be1558] text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 text-base">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Enviar Solicitud
                </button>
            </div>
        </div>
    </div>

    {{-- Cargar archivos JavaScript en el orden correcto --}}
    <script src="{{ asset('js/tramites/inscripcion/validacion-formulario.js') }}"></script>
    <script src="{{ asset('js/tramites/inscripcion/navegacion-formulario.js') }}"></script>
    <script src="{{ asset('js/tramites/inscripcion/actividades.js') }}"></script>
    <script src="{{ asset('js/tramites/inscripcion/accionistas-manager.js') }}"></script>
    <script src="{{ asset('js/tramites/inscripcion/codigo-postal.js') }}"></script>
    <script src="{{ asset('js/tramites/inscripcion/index.js') }}"></script>
    
    {{-- Agregar animaciones CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endsection
