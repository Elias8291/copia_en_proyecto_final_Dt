@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/70 mb-8">
                <div class="p-6 border-b border-gray-200/70">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div
                                class="bg-gradient-to-br from-[#B4325E] via-[#93264B] to-[#7a1d37] rounded-xl p-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Seleccionar Tipo de Revisión</h1>
                                <p class="text-sm text-gray-500">Trámite #{{ $tramite->id }} •
                                    {{ $tramite->datosGenerales->razon_social ?? ($tramite->proveedor->razon_social ?? 'Proveedor N/A') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $tramite->estado === 'Pendiente'
                                ? 'bg-amber-100 text-amber-700'
                                : ($tramite->estado === 'En_Revision'
                                    ? 'bg-blue-100 text-blue-700'
                                    : ($tramite->estado === 'Aprobado'
                                        ? 'bg-green-100 text-green-700'
                                        : ($tramite->estado === 'Por_Cotejar'
                                            ? 'bg-orange-100 text-orange-700'
                                            : 'bg-gray-100 text-gray-700'))) }}">
                                {{ str_replace('_', ' ', $tramite->estado) }}
                            </span>
                            <a href="{{ route('revision.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m7 7l-7 7z" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Opciones de Revisión -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Revisión Digital -->
                <div
                    class="bg-white shadow-md rounded-xl overflow-hidden {{ in_array($tramite->estado, ['Pendiente', 'En_Revision']) ? 'ring-2 ring-blue-300' : '' }}">
                    <div class="p-9">
                        @if (in_array($tramite->estado, ['Por_Cotejar', 'En_Revision']))
                            <div class="text-center mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Revisión Completada
                                </span>
                            </div>
                        @elseif($tramite->estado === 'Pendiente')
                            <div class="text-center mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Siguiente Paso
                                </span>
                            </div>
                        @endif

                        <svg class="w-12 h-12 mx-auto text-gray-400 sm:mx-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                stroke="#111827" />
                        </svg>

                        <h3 class="mt-6 text-2xl font-bold text-gray-900 sm:mt-10">Revisión Digital</h3>
                        <p class="mt-6 text-base text-gray-600">Revisa documentos y datos en línea de forma completa</p>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Validar datos generales del proveedor</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Revisar actividades económicas</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span>Verificar documentos digitalizados</span>
                            </div>
                        </div>

                        <a href="{{ route('revision.revisar', ['tramite' => $tramite->id, 'tipo' => 'revision-digital']) }}"
                            class="mt-8 w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                            Iniciar Revisión Digital
                        </a>
                    </div>
                </div>

                <!-- Revisión Presencial -->
                <div
                    class="bg-white shadow-md rounded-xl overflow-hidden {{ $tramite->estado === 'Por_Cotejar' ? 'ring-2 ring-orange-300' : '' }}">
                    <div class="p-9">
                        @if ($tramite->estado === 'Por_Cotejar')
                            <div class="text-center mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    Cotejo Pendiente
                                </span>
                            </div>
                        @else
                            <div class="text-center mb-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Bloqueado
                                </span>
                            </div>
                        @endif

                        <svg class="w-12 h-12 mx-auto text-gray-400 sm:mx-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                stroke="#111827" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="#111827" />
                        </svg>

                        <h3 class="mt-6 text-2xl font-bold text-gray-900 sm:mt-10">Cotejo Presencial</h3>
                        <p class="mt-6 text-base text-gray-600">Verificar identidad del representante y cotejar documentos
                            físicos originales</p>

                        <!-- Documentos para cotejar -->
                        @if ($tramite->archivos->count() > 0)
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Documentos para cotejar:</h4>
                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                    @foreach ($tramite->archivos->take(4) as $archivo)
                                        <div
                                            class="flex items-center justify-between gap-2 text-sm text-gray-600 bg-gray-50 rounded p-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span
                                                    class="truncate">{{ $archivo->catalogoArchivo->nombre ?? $archivo->nombre_original ?? 'Documento' }}</span>
                                            </div>
                                            <a href="{{ $archivo->getUrlVisualizacionAttribute() }}" target="_blank"
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-orange-600 bg-orange-50 rounded hover:bg-orange-100 transition-colors flex-shrink-0">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Ver
                                            </a>
                                        </div>
                                    @endforeach
                                    @if ($tramite->archivos->count() > 4)
                                        <div class="text-xs text-gray-500 text-center py-1">
                                            +{{ $tramite->archivos->count() - 4 }} documentos más
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="mt-6">
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-orange-600 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-xs text-orange-700">
                                            <strong>Proceso de cotejo:</strong> Primero verificar identidad oficial del
                                            representante, después cotejar documentos físicos originales.
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <span
                                            class="flex-shrink-0 w-5 h-5 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-medium">1</span>
                                        <span>Verificar identidad oficial del representante</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <span
                                            class="flex-shrink-0 w-5 h-5 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-medium">2</span>
                                        <span>Cotejar documentos físicos originales</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                        <span
                                            class="flex-shrink-0 w-5 h-5 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-xs font-medium">3</span>
                                        <span>Verificar autenticidad de sellos y firmas</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($tramite->estado === 'Por_Cotejar')
                            <a href="/revision/{{ $tramite->id }}/documentos-presencial"
                                class="mt-8 w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                                Iniciar Cotejo Presencial
                            </a>
                        @else
                            <button disabled
                                class="mt-8 w-full inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Cotejo No Disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection
