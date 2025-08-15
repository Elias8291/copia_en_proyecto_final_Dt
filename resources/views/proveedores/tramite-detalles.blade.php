@extends('layouts.app')

@section('title', 'Detalles del Trámite - ' . $tramite->proveedor->razon_social)

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        
        <!-- Header del Trámite -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg p-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">
                                Detalles del Trámite
                            </h1>
                            <p class="text-sm text-gray-500">Proveedor: {{ $tramite->proveedor->razon_social }} | RFC: {{ $tramite->proveedor->rfc }}</p>
                            <p class="text-sm text-gray-500">Tipo: {{ $tramite->tipo_tramite }} | Estado: 
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @if($tramite->status === 'Aprobado') bg-green-100 text-green-800
                                    @elseif($tramite->status === 'Rechazado') bg-red-100 text-red-800
                                    @elseif($tramite->status === 'Para_Correccion') bg-yellow-100 text-yellow-800
                                    @elseif($tramite->status === 'En_Revision') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ str_replace('_', ' ', $tramite->status) }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                @php
                                    $fechaTramite = $tramite->fecha_finalizacion ?? $tramite->fecha_inicio;
                                @endphp
                                Fecha del Trámite: 
                                @if($fechaTramite)
                                    {{ \Carbon\Carbon::parse($fechaTramite)->format('d/m/Y') }}
                                @else
                                    {{ $tramite->created_at->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('proveedores.show', $tramite->proveedor->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Ver Proveedor
                        </a>
                        <a href="{{ route('proveedores.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#9d2449] to-[#7a1a37] text-white rounded-lg font-medium hover:shadow-lg transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                            </svg>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Datos Generales -->
            @if(!empty($datosCompletos['datos_generales']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('datos-generales')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
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
                <div id="datos-generales-content" class="border-t border-gray-200 p-6 bg-gray-50">
                    <x-forms.datos-generales :datos="$datosCompletos['datos_generales']" :editable="false" />
                </div>
            </div>
            @endif

            <!-- Domicilio -->
                            @if(!empty($datosCompletos['domicilio']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('domicilio')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
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
                                            <x-forms.domicilio :datos="$datosCompletos['domicilio']" :editable="false" />
                </div>
            </div>
            @endif

            <!-- Actividades Económicas -->
                            @if(!empty($datosCompletos['actividades']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('actividades')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
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
                                            <x-forms.actividades-economicas :datos="$datosCompletos['actividades']" :editable="false" />
                </div>
            </div>
            @endif

            <!-- Constitución (solo para personas morales) -->
            @if($tramite->proveedor->tipo_persona === 'Moral' && !empty($datosCompletos['constitucion']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('constitucion')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
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

            <!-- Apoderado Legal (solo para personas morales) -->
                            @if($tramite->proveedor->tipo_persona === 'Moral' && !empty($datosCompletos['apoderado']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('apoderado')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
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
                                            <x-forms.apoderado :datos="$datosCompletos['apoderado']" :editable="false" />
                </div>
            </div>
            @endif

            <!-- Accionistas (solo para personas morales) -->
            @if($tramite->proveedor->tipo_persona === 'Moral' && !empty($datosCompletos['accionistas']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('accionistas')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
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
                            @if(!empty($datosCompletos['archivos']))
            <div class="bg-white rounded-lg shadow-md border border-gray-200">
                <button type="button" onclick="toggleSection('archivos')" 
                        class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Documentos ({{ count($datosCompletos['documentos']) }})</span>
                    </div>
                    <svg id="archivos-icon" class="w-5 h-5 text-gray-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="archivos-content" class="hidden border-t border-gray-200 p-6 bg-gray-50">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                                    @foreach($datosCompletos['archivos'] as $archivo)
                            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col h-full">
                                <div class="text-center flex-grow">
                                    <!-- Icono según tipo de archivo -->
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        @php
                                            $extension = $archivo['extension'] ?? pathinfo($archivo['ruta'] ?? '', PATHINFO_EXTENSION);
                                        @endphp
                                        @switch($extension)
                                            @case('pdf')
                                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                </svg>
                                                @break
                                            @case('jpg')
                                            @case('jpeg')
                                            @case('png')
                                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z"/>
                                                </svg>
                                                @break
                                            @case('mp4')
                                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4,2H20A2,2 0 0,1 22,4V16A2,2 0 0,1 20,18H13.9L10.2,21.71C10,21.9 9.75,22 9.5,22V22H9A1,1 0 0,1 8,21V18H4A2,2 0 0,1 2,16V4A2,2 0 0,1 4,2M5,5V11H19V5H5Z"/>
                                                </svg>
                                                @break
                                            @default
                                                <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                </svg>
                                        @endswitch
                                    </div>
                                    
                                    <!-- Nombre del archivo -->
                                    <h5 class="font-medium text-gray-900 mb-2 text-sm">
                                                                                    {{ $archivo['nombre_original'] ?? $archivo['nombre_catalogo'] ?? 'Archivo' }}
                                    </h5>
                                    
                                    <!-- Nombre original -->
                                    @if(!empty($archivo['nombre_original']))
                                    <p class="text-xs text-gray-500 mb-3">{{ $archivo['nombre_original'] }}</p>
                                    @endif
                                    
                                    <!-- Tipo de archivo -->
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ strtoupper($extension) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Estado del archivo -->
                                    @if(isset($archivo['status']))
                                    <div class="mb-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($archivo['status'] === 'Aprobado') bg-green-100 text-green-800
                                            @elseif($archivo['status'] === 'Rechazado') bg-red-100 text-red-800
                                            @elseif($archivo['status'] === 'Para_Correccion') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ str_replace('_', ' ', $archivo['status']) }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <!-- Botón para ver/descargar -->
                                    <a href="{{ route('revisiones.mostrar-archivo', $archivo['id']) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#9d2449] to-[#7a1a37] rounded-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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
