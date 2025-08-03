@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Trámite') }}
        </h2>
        <a href="{{ route('tramites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            Volver
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('tramites.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
               

                <!-- Paso 1: Datos Generales -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 1: Datos Generales</h3>
                    @include('components.forms.datos-generales', ['editable' => true])
                </div>

                <!-- Paso 2: Actividades Económicas -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 2: Actividades Económicas</h3>
                    @include('components.forms.actividades-economicas', ['editable' => true])
                </div>

                <!-- Paso 3: Domicilio -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 3: Domicilio</h3>
                    @include('components.forms.domicilio', ['editable' => true])
                </div>

                <!-- Paso 4: Constitución -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 4: Constitución</h3>
                    @include('components.forms.constitucion', ['editable' => true])
                </div>

                <!-- Paso 5: Accionistas -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 5: Accionistas</h3>
                    @include('components.forms.accionistas', ['editable' => true])
                </div>

                <!-- Paso 6: Apoderado Legal -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 6: Apoderado Legal</h3>
                    @include('components.forms.apoderado', ['editable' => true])
                </div>

                <!-- Paso 7: Documentos -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 7: Documentos</h3>
                    @include('components.forms.documentos', ['editable' => true])
                </div>

                <!-- Confirmación -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmación</h3>
                    @include('components.forms.confirmacion', ['editable' => true])
                </div>

                <!-- Botones de navegación -->
                <div class="flex justify-between items-center bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        Anterior
                    </button>
                    <div class="flex space-x-4">
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            Guardar Borrador
                        </button>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            Siguiente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection 