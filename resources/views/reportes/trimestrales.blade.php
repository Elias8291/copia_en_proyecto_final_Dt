@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 mb-6 text-white shadow-lg">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white/20 rounded-xl">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold">Reportes Trimestrales</h1>
                <p class="text-blue-100">Genera reportes de proveedores activos por trimestre considerando fechas de finalización</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="max-w-4xl mx-auto">
            <form id="formReporteTrimestral" action="{{ route('proveedores.reporte.trimestral.generar') }}" method="POST">
                @csrf
                
                <!-- Filtros Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Año -->
                    <div class="space-y-2">
                        <label for="año" class="block text-sm font-semibold text-gray-700">
                            📅 Año de Reporte
                        </label>
                        <select name="año" id="año" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                            @foreach($años as $año)
                                <option value="{{ $año }}" {{ $año == date('Y') ? 'selected' : '' }}>
                                    {{ $año }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trimestre -->
                    <div class="space-y-2">
                        <label for="trimestre" class="block text-sm font-semibold text-gray-700">
                            📊 Trimestre
                        </label>
                        <select name="trimestre" id="trimestre" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                            <option value="1">Q1 - Enero a Marzo</option>
                            <option value="2">Q2 - Abril a Junio</option>
                            <option value="3">Q3 - Julio a Septiembre</option>
                            <option value="4">Q4 - Octubre a Diciembre</option>
                        </select>
                    </div>

                    <!-- Formato -->
                    <div class="space-y-2">
                        <label for="formato" class="block text-sm font-semibold text-gray-700">
                            📄 Formato de Exportación
                        </label>
                        <select name="formato" id="formato" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white shadow-sm hover:shadow-md">
                            <option value="excel">📊 Excel (.xlsx)</option>
                            <option value="pdf" disabled>📑 PDF (Próximamente)</option>
                        </select>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Información del Reporte</h3>
                            <div class="space-y-2 text-sm text-blue-800">
                                <p><strong>Criterios de inclusión:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Proveedores creados antes o durante el trimestre seleccionado</li>
                                    <li>Que no hayan finalizado antes del inicio del trimestre</li>
                                    <li>O que no tengan fecha de finalización (considerados activos)</li>
                                </ul>
                                <p class="mt-3"><strong>El reporte incluye:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Datos básicos del proveedor (Razón Social, RFC, Tipo)</li>
                                    <li>Estado del padrón y fechas relevantes</li>
                                    <li>Ubicación geográfica y actividades económicas</li>
                                    <li>Estatus específico durante el trimestre</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div id="previewSection" class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8" style="display: none;">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        📋 Vista Previa del Reporte
                    </h3>
                    <div id="previewContent" class="space-y-2 text-sm text-gray-700">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" id="btnGenerar"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-lg font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span id="btnText">Generar Reporte Trimestral</span>
                        <div id="loadingSpinner" class="hidden">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="messages" class="mt-6"></div>
</div>

<!-- JavaScript -->
<script src="{{ asset('js/reportes/trimestrales.js') }}"></script>
@endsection