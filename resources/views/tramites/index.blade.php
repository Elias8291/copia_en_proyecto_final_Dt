@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
<div class="min-h-screen py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @php
            // Verificar si hay trámite pendiente
            $tieneTramitePendiente = false;
            $tramitePendiente = null;
            $tipoTramitePendiente = null;
            $rfc = auth()->user()->rfc ?? null;
            
            if ($rfc) {
                $rfcService = app(\App\Services\RfcProveedorService::class);
                $tramitePendiente = $rfcService->obtenerTramitePendiente($rfc);
                $tieneTramitePendiente = $tramitePendiente !== null;
                if ($tramitePendiente) {
                    $tipoTramitePendiente = strtolower($tramitePendiente->tipo_tramite);
                }
            }
        @endphp

        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            @if(session('success'))
                <div class="bg-green-50 border-b border-green-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-b border-red-200 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-r from-white to-gray-50 border-b border-gray-200">
                <div class="px-8 py-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Trámites Disponibles</h1>
                            <p class="text-gray-600 text-base mt-1">
                                @if($tieneTramitePendiente)
                                    Tiene un trámite de {{ ucfirst($tipoTramitePendiente) }} en proceso
                                @else
                                    Seleccione el tipo de trámite que desea realizar
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <button onclick="openHistorialModal()" class="inline-flex items-center px-6 py-3 text-sm font-medium text-[#9d2449] bg-white border-2 border-[#9d2449] rounded-xl hover:bg-[#9d2449] hover:text-white transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Historial
                    </button>
                </div>
            </div>

            <div class="p-8">
                <div class="text-center mb-12">
                    <h2 class="text-lg font-medium text-gray-700 mb-3">Seleccione un Tipo de Trámite</h2>
                    <p class="text-gray-500 text-base max-w-3xl mx-auto">Elija una de las siguientes opciones para proceder con su solicitud.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    
                    <div class="bg-white rounded-2xl shadow-lg border-2 border-orange-200 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                        @if($tieneTramitePendiente)
                            <div class="h-2 bg-gradient-to-r from-orange-500 to-yellow-500"></div>
                        @else
                            <div class="h-2 bg-gradient-to-r from-[#9d2449] to-[#8a1f40]"></div>
                        @endif
                        
                        <div class="p-6">
                            @if($tieneTramitePendiente)
                                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-orange-800">
                                            Tiene un trámite de {{ ucfirst($tipoTramitePendiente) }} en curso
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-start space-x-4">
                                <div class="bg-gradient-to-br from-[#9d2449] to-[#8a1f40] rounded-xl p-3 flex-shrink-0 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Inscripción al Padrón</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Registro inicial para nuevos proveedores. Complete todos los requisitos para formar parte del padrón oficial.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                @if($tieneTramitePendiente)
                                    <a href="{{ route('tramites.estado') }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-600 to-amber-600 text-white text-sm font-semibold rounded-xl hover:from-orange-700 hover:to-amber-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver detalles del trámite
                                    </a>
                                @else
                                    <a href="{{ route('tramites.cargar-constancia', 'inscripcion') }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-[#9d2449] to-[#8a1f40] text-white text-sm font-semibold rounded-xl hover:from-[#8a1f40] hover:to-[#7a1a37] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                        Iniciar Trámite
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-300 overflow-hidden opacity-75 scale-95">
                        <div class="h-2 bg-gradient-to-r from-gray-300 to-gray-400"></div>
                        
                        <div class="p-6">
                            <div class="flex justify-end mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                    No Disponible
                                </span>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="bg-gray-200 rounded-xl p-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-400 mb-2">Renovación de Registro</h3>
                                    <p class="text-gray-400 text-sm leading-relaxed">
                                        Renueve su registro anual para mantener activo su estado en el padrón de proveedores.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button disabled class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed border-2 border-gray-200">
                                    No Disponible
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg border-2 border-gray-300 overflow-hidden opacity-75 scale-95">
                        <div class="h-2 bg-gradient-to-r from-gray-300 to-gray-400"></div>
                        
                        <div class="p-6">
                            <div class="flex justify-end mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                    No Disponible
                                </span>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="bg-gray-200 rounded-xl p-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-400 mb-2">Actualización de Datos</h3>
                                    <p class="text-gray-400 text-sm leading-relaxed">
                                        Modifique su información registrada. Mantenga sus datos siempre actualizados.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button disabled class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-400 text-sm font-semibold rounded-xl cursor-not-allowed border-2 border-gray-200">
                                    No Disponible
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div id="historialModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="bg-[#9d2449] text-white px-6 py-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h2 class="text-xl font-bold">Historial de Trámites</h2>
                </div>
                <button onclick="closeHistorialModal()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium">Total de trámites</span>
                        <div class="bg-[#9d2449] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                            {{ $historialTramites->count() }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                        </svg>
                        <span class="text-sm">Ordenado por fecha</span>
                    </div>
                </div>

                <div class="space-y-4 max-h-[60vh] overflow-y-auto">
                    @if($historialTramites->isNotEmpty())
                        @foreach($historialTramites as $tramite)
                            <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="font-bold text-gray-900 text-lg">{{ strtoupper($tramite['razon_social'] ?? 'Sin datos') }}</h3>
                                            <span class="bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ ucfirst($tramite['tipo_tramite'] ?? 'N/A') }}
                                            </span>
                                            
                                            @php
                                                $estadoColors = [
                                                    'Pendiente' => 'bg-yellow-100 text-yellow-800',
                                                    'Revision_Digital' => 'bg-blue-100 text-blue-800',
                                                    'Revision_Presencial' => 'bg-purple-100 text-purple-800',
                                                    'Revision_Domiciliaria' => 'bg-indigo-100 text-indigo-800',
                                                    'Para_Correccion' => 'bg-orange-100 text-orange-800',
                                                    'Aprobado' => 'bg-green-100 text-green-800',
                                                    'Rechazado' => 'bg-red-100 text-red-800',
                                                    'Cancelado' => 'bg-gray-100 text-gray-800'
                                                ];
                                                $status = $tramite['status'] ?? 'N/A';
                                                $estadoColor = $estadoColors[$status] ?? 'bg-gray-100 text-gray-800';
                                                $estadoLabels = [
                                                    'Pendiente' => 'Pendiente',
                                                    'Revision_Digital' => 'Revisión Digital',
                                                    'Revision_Presencial' => 'Revisión Presencial',
                                                    'Revision_Domiciliaria' => 'Revisión Domiciliaria',
                                                    'Para_Correccion' => 'Para Corrección',
                                                    'Aprobado' => 'Aprobado',
                                                    'Rechazado' => 'Rechazado',
                                                    'Cancelado' => 'Cancelado'
                                                ];
                                                $estadoLabel = $estadoLabels[$status] ?? $status;
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $estadoColor }}">
                                                {{ $estadoLabel }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-gray-600 text-sm mb-2">
                                            @if(isset($tramite['created_at']))
                                                @if($tramite['created_at'] instanceof \Carbon\Carbon)
                                                    {{ $tramite['created_at']->format('d/m/Y H:i') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($tramite['created_at'])->format('d/m/Y H:i') }}
                                                @endif
                                            @else
                                                Fecha no disponible
                                            @endif
                                        </div>

                                        @if(isset($tramite['oficio']) && $tramite['oficio'])
                                            <div class="text-gray-600 text-sm mb-2">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <span class="font-medium text-[#9d2449]">Oficio:</span>
                                                    <span class="ml-1">{{ $tramite['oficio']['numero_oficio'] ?? 'Sin número' }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        

                                        
                                        <div class="flex items-center space-x-3">
                                            @if(isset($tramite['id']))
                                                <a href="{{ route('tramites.estado', $tramite['id']) }}" 
                                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Ver estado
                                                </a>
                                            @endif

                                            @if(isset($tramite['oficio']) && $tramite['oficio'] && isset($tramite['oficio']['url']))
                                                <a href="{{ $tramite['oficio']['url'] }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#9d2449] border border-[#9d2449] rounded-lg hover:bg-[#8a1f40] transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Ver oficio
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="flex items-center justify-center mb-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                Sin historial de trámites
                            </h3>
                            <p class="text-gray-600">
                                Aún no ha realizado ningún trámite. Comience seleccionando uno de los tipos disponibles.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                <div class="text-gray-600 text-sm">
                    Haga clic en 'Ver detalles' para más información
                </div>
                <button onclick="closeHistorialModal()" class="bg-[#9d2449] text-white px-6 py-2 rounded-lg hover:bg-[#8a1f40] transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openHistorialModal() {
    document.getElementById('historialModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeHistorialModal() {
    document.getElementById('historialModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.getElementById('historialModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeHistorialModal();
    }
});

                
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeHistorialModal();
    }
});
</script>

@endsection