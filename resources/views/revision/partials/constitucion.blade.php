@php
    $datosConstitutivos = $tramite->datosConstitutivos;
    $instrumentoNotarial = $datosConstitutivos?->instrumentoNotarial;
@endphp

<div class="space-y-4">
    @if($datosConstitutivos && $instrumentoNotarial)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Fecha de Constitución -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Fecha de Constitución</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->fecha_constitucion ? \Carbon\Carbon::parse($instrumentoNotarial->fecha_constitucion)->format('d/m/Y') : 'No especificada' }}</p>
            </div>

            <!-- Número de Escritura -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Número de Escritura</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->numero_escritura ?? 'No especificado' }}</p>
            </div>

            <!-- Nombre del Notario -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Nombre del Notario</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->nombre_notario ?? 'No especificado' }}</p>
            </div>

            <!-- Número de Notario -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Número de Notario</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->numero_notario ?? 'No especificado' }}</p>
            </div>

            <!-- Entidad Federativa -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Entidad Federativa</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->entidad_federativa ?? 'No especificada' }}</p>
            </div>

            <!-- Número de Registro Público -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Número de Registro Público</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->numero_registro_publico ?? 'No especificado' }}</p>
            </div>

            <!-- Fecha de Inscripción -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Fecha de Inscripción</span>
                </div>
                <p class="text-sm text-gray-900">{{ $instrumentoNotarial->fecha_inscripcion ? \Carbon\Carbon::parse($instrumentoNotarial->fecha_inscripcion)->format('d/m/Y') : 'No especificada' }}</p>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span class="text-sm font-medium text-yellow-800">Sin datos constitutivos</span>
            </div>
            <p class="text-sm text-yellow-700 mt-1">No se encontraron datos constitutivos para este trámite.</p>
        </div>
    @endif
</div> 