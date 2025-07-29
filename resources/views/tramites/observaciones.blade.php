@extends('layouts.app')

@section('title', 'Observaciones del Trámite')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="relative h-16 sm:h-20 lg:h-24 bg-gradient-to-br from-[#9d2449] via-[#8a203f] to-[#7a1d37] overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>
            
            {{-- Avatar --}}
            <div class="relative -mt-16 sm:-mt-20 px-6 sm:px-8">
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center shadow-xl border-4 border-white">
                            <svg class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-black rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                {{-- Título --}}
                <div class="text-center mt-4">
                    <h1 class="text-black text-xl sm:text-2xl lg:text-3xl font-bold tracking-wide">Observaciones del Trámite</h1>
                </div>
            </div>
            
            {{-- Contenido principal --}}
            <div class="px-6 sm:px-8 lg:px-12 py-8 sm:py-12">
                {{-- Información del trámite --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center space-x-4 bg-black/5 rounded-full px-4 py-2 mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-[#9d2449] rounded-full"></div>
                            <span class="text-xs font-medium text-black/70">Folio</span>
                            <span class="text-xs font-bold text-black">{{ $tramite['id'] ?? 'N/A' }}</span>
                        </div>
                        <div class="w-px h-4 bg-black/20"></div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-block w-3 h-3 rounded-full bg-amber-500 shadow-sm"></span>
                            <span class="text-xs font-medium text-black/70">Estado:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold tracking-wide border bg-amber-50 text-amber-700 border-amber-200 shadow-sm">
                                Requiere Correcciones
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Observaciones --}}
                <div class="space-y-6">
                    @if(isset($observaciones) && count($observaciones) > 0)
                        @foreach($observaciones as $seccion => $obs)
                            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-10 h-10 bg-[#9d2449] rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ ucfirst($seccion) }}</h3>
                                </div>
                                
                                <div class="space-y-3">
                                    @if(is_array($obs))
                                        @foreach($obs as $observacion)
                                            <div class="flex items-start space-x-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                                <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                                <p class="text-sm text-amber-800">{{ $observacion }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex items-start space-x-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                            <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            <p class="text-sm text-amber-800">{{ $obs }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-[#9d2449] to-[#8a203f] rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-info-circle text-white text-xl"></i>
                                </div>
                                <p class="text-sm text-gray-600">
                                    No hay observaciones específicas para este trámite.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Botones de acción --}}
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('tramites.corregir', ['id' => $tramite['id']]) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#9d2449] to-[#8a203f] text-white rounded-lg hover:from-[#8a203f] hover:to-[#7a1d37] transition-all duration-300 transform hover:scale-105 font-semibold shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Corregir Trámite
                    </a>
                    <a href="{{ route('tramites.estado.show', ['id' => $tramite['id']]) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 border-2 border-[#9d2449] text-[#9d2449] rounded-lg hover:bg-[#9d2449] hover:text-white transition-all duration-300 font-semibold">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Estado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 