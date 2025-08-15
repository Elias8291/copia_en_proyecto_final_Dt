@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary to-primary-dark px-6 py-4">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mi Estado de Proveedor
            </h2>
            <p class="text-primary-100 mt-1">Información actualizada de tu registro en el padrón</p>
        </div>
    
        <div class="p-8">
            @if($proveedor)
                <!-- Información del Proveedor -->
                <div class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Razón Social</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $proveedor->razon_social ?? 'N/A' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">RFC</div>
                            <div class="text-lg font-mono text-gray-900">{{ $proveedor->rfc ?? 'N/A' }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Estado</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($proveedor->estado_padron === 'vigente') bg-green-100 text-green-800 
                                @elseif($proveedor->estado_padron === 'vencido') bg-red-100 text-red-800 
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($proveedor->estado_padron ?? 'Desconocido') }}
                            </span>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Vencimiento</div>
                            <div class="text-lg text-gray-900">{{ $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                @if(isset($datosCompletos) && isset($ultimoTramite) && $datosCompletos && $ultimoTramite)
                <!-- Último Trámite -->
                <div class="border-t pt-8 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Último Trámite</h3>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($ultimoTramite->status === 'Aprobado') bg-green-100 text-green-800
                            @elseif($ultimoTramite->status === 'Rechazado') bg-red-100 text-red-800
                            @elseif($ultimoTramite->status === 'Para_Correccion') bg-yellow-100 text-yellow-800
                            @elseif($ultimoTramite->status === 'En_Revision') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ str_replace('_', ' ', $ultimoTramite->status) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Tipo</div>
                            <div class="text-lg font-medium text-gray-900">{{ $ultimoTramite->tipo_tramite }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Creado</div>
                            <div class="text-lg text-gray-900">{{ $ultimoTramite->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-1">Actualizado</div>
                            <div class="text-lg text-gray-900">{{ $ultimoTramite->updated_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Formularios Desplegables -->
                <div class="border-t pt-8 space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Información Detallada</h3>
                    
                    @if(!empty($datosCompletos['datos_generales']))
                    <!-- Datos Generales -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('datos-generales')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Datos Generales</span>
                            </div>
                            <svg id="datos-generales-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="datos-generales-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.datos-generales :datos="$datosCompletos['datos_generales']" :editable="false" />
                        </div>
                    </div>
                    @endif

                    @if(!empty($datosCompletos['direccion']))
                    <!-- Domicilio -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('domicilio')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Domicilio</span>
                            </div>
                            <svg id="domicilio-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="domicilio-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.domicilio :datos="$datosCompletos['direccion']" :editable="false" />
                        </div>
                    </div>
                    @endif

                    @if(!empty($datosCompletos['actividades_economicas']))
                    <!-- Actividades Económicas -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('actividades')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Actividades Económicas</span>
                            </div>
                            <svg id="actividades-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="actividades-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.actividades-economicas :datos="$datosCompletos['actividades_economicas']" :editable="false" />
                        </div>
                    </div>
                    @endif

                    @if(($proveedor->tipo_persona ?? null) === 'Moral' && !empty($datosCompletos['constitucion']))
                    <!-- Constitución -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('constitucion')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Constitución</span>
                            </div>
                            <svg id="constitucion-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="constitucion-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.constitucion :datos="$datosCompletos['constitucion']" :editable="false" />
                        </div>
                    </div>
                    @endif

                    @if(($proveedor->tipo_persona ?? null) === 'Moral' && !empty($datosCompletos['apoderado_legal']))
                    <!-- Apoderado Legal -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('apoderado')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Apoderado Legal</span>
                            </div>
                            <svg id="apoderado-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="apoderado-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.apoderado :datos="$datosCompletos['apoderado_legal']" :editable="false" />
                        </div>
                    </div>
                    @endif

                    @if(($proveedor->tipo_persona ?? null) === 'Moral' && !empty($datosCompletos['accionistas']))
                    <!-- Accionistas -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('accionistas')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Accionistas</span>
                            </div>
                            <svg id="accionistas-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="accionistas-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.accionistas :datos="$datosCompletos['accionistas']" :editable="false" />
                        </div>
                    </div>
                    @endif

                    <!-- Documentos -->
                    <div class="border border-gray-200 rounded-lg">
                        <button type="button" onclick="toggleSection('archivos')" 
                                class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-900">Documentos</span>
                            </div>
                            <svg id="archivos-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="archivos-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                            <x-forms.archivos-dinamicos :editable="false" :archivosCargados="$archivosCargados" soloLectura="true" />
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Historial de Trámites -->
                @if($proveedor->tramites && $proveedor->tramites->count() > 0)
                <div class="border-t pt-8 mt-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Historial de Trámites</h3>
                    <div class="space-y-4">
                        @foreach($proveedor->tramites as $tramite)
                        @php
                            $status = $tramite->status ?? 'Pendiente';
                            $statusColor = [
                                'Aprobado' => 'bg-green-100 text-green-800',
                                'Rechazado' => 'bg-red-100 text-red-800',
                                'Para_Correccion' => 'bg-yellow-100 text-yellow-800',
                                'En_Revision' => 'bg-blue-100 text-blue-800',
                                'Pendiente' => 'bg-gray-100 text-gray-800',
                            ][$status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <div class="font-medium text-gray-900">{{ $tramite->tipo_tramite ?? 'Trámite' }}</div>
                                <div class="text-sm text-gray-500">{{ $tramite->created_at ? $tramite->created_at->format('d/m/Y H:i') : 'N/D' }}</div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos de proveedor</h3>
                    <p class="text-gray-500">No se encontraron datos de proveedor asociados a tu usuario.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleSection(sectionName) {
    const content = document.getElementById(sectionName + '-content');
    const icon = document.getElementById(sectionName + '-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Auto-expandir la primera sección
document.addEventListener('DOMContentLoaded', function() {
    const firstSection = document.getElementById('datos-generales-content');
    if (firstSection) {
        toggleSection('datos-generales');
    }
});
</script>

@endsection