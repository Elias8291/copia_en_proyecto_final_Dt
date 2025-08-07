@extends('layouts.app')

@section('title', 'Revisión Digital')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <!-- Header de Revisión -->
        <x-ui.section-header 
            :titulo="$tipoRevisionLabel . ' - Trámite #' . $tramite->id"
            :descripcion="strtolower($tipoRevisionLabel) . ' de documentos y datos del trámite'"
            color-icono="blue"
            :icono="'<svg class=\"w-6 h-6\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\"/>
            </svg>'"
        >
            <x-slot name="actions">
                <x-ui.buttons.action-button 
                    tipo="secondary" 
                    :url="route('revisiones.index')"
                    :icono="'<svg class=\"w-4 h-4\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M10 19l-7-7m0 0l7-7m-7 7h18\"/>
                    </svg>'"
                >
                    Volver
                </x-ui.buttons.action-button>
            </x-slot>
        </x-ui.section-header>

        <div class="p-6">
            <!-- Información del trámite -->
            <x-revision.info-tramite 
                :tramite="$tramite"
                :tipo-revision="$tipoRevision"
                :revision="$revision"
            />

            <!-- Panel de Historial -->
            <x-revision.historial-panel 
                :tramite="$tramite"
                :estadisticas-historial="$estadisticasHistorial"
                :historial-tramites="$historialTramites"
            />

            <!-- Datos Generales -->
            <x-revision.seccion-wrapper 
                seccion="datos_generales"
                titulo="Datos Generales"
            >
                @include('components.forms.datos-generales', [
                    'editable' => false, 
                    'datosConstancia' => $viewModel
                ])
                
                <x-slot name="cotejoContent">
                    <x-cotejo-selector 
                        seccion="datos_generales"
                        :datos-constancia="$viewModel->getDatosConstancia()"
                        :datos-sat="$viewModel->getDatosSAT()"
                    />
                </x-slot>
            </x-revision.seccion-wrapper>

            <x-revision.area-decision 
                seccion="datos_generales" 
                titulo="Decisión - Datos Generales"
                numero-seccion="Sección 1/6"
                placeholder="Agregar observaciones específicas para datos generales..." 
            />

            <x-revision.separador />

            <!-- Actividades Económicas -->
            <x-revision.seccion-wrapper 
                seccion="actividades"
                titulo="Actividades Económicas"
            >
                @include('components.forms.actividades-economicas', [
                    'editable' => false,
                    'datosConstancia' => $viewModel
                ])
                
                <x-slot name="cotejoContent">
                    <x-cotejo-selector 
                        seccion="actividades"
                        :datos-constancia="$viewModel->getDatosConstancia()"
                        :datos-sat="$viewModel->getDatosSAT()"
                    />
                </x-slot>
            </x-revision.seccion-wrapper>

            <x-revision.area-decision 
                seccion="actividades" 
                titulo="Decisión - Actividades Económicas"
                numero-seccion="Sección 2/6"
                placeholder="Agregar observaciones específicas para actividades económicas..." 
            />

            <x-revision.separador />

            <!-- Más secciones... -->
            <!-- [Repetir patrón para domicilio, constitución, accionistas, apoderado, archivos] -->

            <x-revision.separador tipo="final" />

            <!-- Panel de decisión final -->
            <x-revision.panel-decision-final 
                :tramite="$tramite"
                :tipo-revision="$tipoRevision"
                :secciones="$secciones"
                :action-url="route('revisiones.procesar-digital', $tramite->id)"
            />
        </div>
    </div>
</div>

<!-- Navegación flotante -->
<x-revision.navegacion-flotante />

<!-- JavaScript externo -->
<script src="{{ asset('js/revision-digital.js') }}"></script>
@endsection 