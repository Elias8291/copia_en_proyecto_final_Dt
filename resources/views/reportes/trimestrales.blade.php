@extends('layouts.app')

@section('title', 'Reportes Trimestrales')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 sm:py-6 lg:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold leading-7 text-gray-900 sm:leading-9 sm:truncate">
                        📅 Reportes Trimestrales
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Genera reportes de proveedores que estuvieron activos durante trimestres específicos
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('reportes.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        ← Volver a Reportes
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas del Año Actual -->
        <div class="mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">📊 Resumen {{ $añoActual }}</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($estadisticasTrimestrales as $trimestre => $stats)
                    @php
                        $trimestres = [
                            1 => ['nombre' => 'Q1 - Ene/Mar', 'color' => 'blue'],
                            2 => ['nombre' => 'Q2 - Abr/Jun', 'color' => 'green'],
                            3 => ['nombre' => 'Q3 - Jul/Sep', 'color' => 'yellow'],
                            4 => ['nombre' => 'Q4 - Oct/Dic', 'color' => 'red']
                        ];
                        $info = $trimestres[$trimestre];
                        $colorClasses = [
                            'blue' => 'bg-blue-50 border-blue-200',
                            'green' => 'bg-green-50 border-green-200',
                            'yellow' => 'bg-yellow-50 border-yellow-200',
                            'red' => 'bg-red-50 border-red-200'
                        ];
                    @endphp
                    <div class="relative rounded-lg border-2 {{ $colorClasses[$info['color']] }} p-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 truncate">{{ $info['nombre'] }}</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</dd>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div class="text-center p-2 bg-white rounded">
                                <div class="font-semibold text-green-600">{{ $stats['nuevos'] }}</div>
                                <div class="text-gray-500">Nuevos</div>
                            </div>
                            <div class="text-center p-2 bg-white rounded">
                                <div class="font-semibold text-red-600">{{ $stats['vencidos'] }}</div>
                                <div class="text-gray-500">Vencidos</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Selector de Reporte Trimestral -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    📥 Generar Reporte Trimestral
                </h3>
                
                <form method="GET" action="{{ route('reportes.exportar-trimestral') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Selector de Año -->
                        <div>
                            <label for="año" class="block text-sm font-medium text-gray-700">
                                Año
                            </label>
                            <select name="año" id="año" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach($añosDisponibles as $año)
                                    <option value="{{ $año }}" {{ $año == $añoActual ? 'selected' : '' }}>
                                        {{ $año }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selector de Trimestre -->
                        <div>
                            <label for="trimestre" class="block text-sm font-medium text-gray-700">
                                Trimestre
                            </label>
                            <select name="trimestre" id="trimestre" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="1">Q1 - Primer Trimestre (Enero - Marzo)</option>
                                <option value="2">Q2 - Segundo Trimestre (Abril - Junio)</option>
                                <option value="3">Q3 - Tercer Trimestre (Julio - Septiembre)</option>
                                <option value="4">Q4 - Cuarto Trimestre (Octubre - Diciembre)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Descargar Reporte Excel
                        </button>

                        <a href="{{ route('reportes.comparativo-trimestral') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Ver Comparativo
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información del Reporte -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        ¿Qué incluye el reporte trimestral?
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Proveedores activos durante el trimestre:</strong> Incluye todos los que estuvieron dados de alta y vigentes en cualquier momento del período</li>
                            <li><strong>Estado durante el trimestre:</strong> Si estuvo activo todo el período, venció en el trimestre, o fue nuevo registro</li>
                            <li><strong>Días activos:</strong> Cantidad exacta de días que estuvo activo dentro del trimestre</li>
                            <li><strong>Información completa:</strong> Datos generales, ubicación, actividades económicas y contactos</li>
                            <li><strong>Observaciones:</strong> Notas especiales como "Nuevo en Q1" o fecha específica de vencimiento</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">🚀 Accesos Rápidos</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $trimestres = [
                        1 => ['nombre' => 'Q1 ' . $añoActual, 'descripcion' => 'Enero - Marzo', 'color' => 'blue'],
                        2 => ['nombre' => 'Q2 ' . $añoActual, 'descripcion' => 'Abril - Junio', 'color' => 'green'],
                        3 => ['nombre' => 'Q3 ' . $añoActual, 'descripcion' => 'Julio - Septiembre', 'color' => 'yellow'],
                        4 => ['nombre' => 'Q4 ' . $añoActual, 'descripcion' => 'Octubre - Diciembre', 'color' => 'red']
                    ];
                @endphp
                
                @foreach($trimestres as $trimestre => $info)
                    @php
                        $buttonColors = [
                            'blue' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
                            'green' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
                            'yellow' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
                            'red' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                        ];
                    @endphp
                    <a href="{{ route('reportes.exportar-trimestral', ['año' => $añoActual, 'trimestre' => $trimestre]) }}" 
                       class="inline-flex flex-col items-center justify-center px-4 py-6 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $buttonColors[$info['color']] }} focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200">
                        <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-bold">{{ $info['nombre'] }}</span>
                        <span class="text-xs opacity-75">{{ $info['descripcion'] }}</span>
                        <span class="text-xs mt-1 bg-white bg-opacity-20 px-2 py-1 rounded">
                            {{ number_format($estadisticasTrimestrales[$trimestre]['total']) }} proveedores
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
