@extends('layouts.app')

@section('title', 'Trámite Enviado Exitosamente')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-green-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-6 sm:p-8">
                    <div class="flex items-center justify-center">
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">¡Trámite Enviado Exitosamente!</h1>
                        <p class="text-emerald-100 text-sm sm:text-base">Su solicitud ha sido recibida y procesada correctamente</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <!-- Success Message -->
                    <div class="text-center mb-8">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 mb-6">
                            <p class="text-emerald-800 text-lg font-medium">{{ $mensaje }}</p>
                        </div>
                        
                        @if($tramite_id)
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">Información de su Trámite</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-center space-x-3">
                                    <span class="text-blue-700 font-medium">Número de Folio:</span>
                                    <span class="bg-blue-100 px-4 py-2 rounded-lg font-mono text-lg text-blue-900">#{{ $tramite_id }}</span>
                                </div>
                                <p class="text-blue-700 text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Conserve este número para dar seguimiento a su trámite
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Próximos Pasos
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-0.5">1</div>
                                <p class="text-gray-700 text-sm">
                                    <strong>Procesamiento:</strong> Su trámite será revisado por nuestro equipo técnico en un plazo de 5 a 10 días hábiles.
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-0.5">2</div>
                                <p class="text-gray-700 text-sm">
                                    <strong>Notificaciones:</strong> Recibirá actualizaciones por correo electrónico y SMS sobre el estado de su trámite.
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 mt-0.5">3</div>
                                <p class="text-gray-700 text-sm">
                                    <strong>Seguimiento:</strong> Puede consultar el estado de su trámite en cualquier momento usando su número de folio.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('tramites.estado') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Consultar Estado del Trámite
                        </a>
                        
                        <a href="{{ route('tramites.index') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            Volver a Trámites
                        </a>
                        
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Ir al Dashboard
                        </a>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-8 text-center">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-amber-800 mb-3">¿Necesita Ayuda?</h4>
                            <p class="text-amber-700 text-sm mb-3">
                                Si tiene alguna duda o necesita asistencia, nuestro equipo de soporte está disponible para ayudarle.
                            </p>
                            <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-6 text-sm">
                                <div class="flex items-center justify-center text-amber-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    soporte@proveedores.gob.mx
                                </div>
                                <div class="flex items-center justify-center text-amber-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    (55) 1234-5678
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 