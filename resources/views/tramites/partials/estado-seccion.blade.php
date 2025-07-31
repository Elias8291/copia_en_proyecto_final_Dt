@props(['seccion' => '', 'tramite' => null, 'editable' => true])

@php
    $revisionSeccion = null;
    if ($tramite) {
        $revisionSeccion = \App\Models\RevisionSeccion::where('tramite_id', $tramite->id)
            ->where('seccion', $seccion)
            ->with('user')
            ->first();
    }
    
    $estado = $revisionSeccion ? $revisionSeccion->estado_texto : 'Pendiente';
    $comentario = $revisionSeccion ? $revisionSeccion->comentario : null;
    $aprobado = $revisionSeccion ? $revisionSeccion->aprobado : null;
    $revisadoPor = $revisionSeccion && $revisionSeccion->user ? $revisionSeccion->user->nombre : null;
    $fechaRevision = $revisionSeccion ? $revisionSeccion->updated_at->format('d/m/Y H:i') : null;
    
    $estadoColors = match($estado) {
        'Aprobado' => 'bg-green-100 text-green-800 border-green-200',
        'Rechazado' => 'bg-red-100 text-red-800 border-red-200',
        default => 'bg-amber-100 text-amber-800 border-amber-200'
    };
    
    $estadoIcon = match($estado) {
        'Aprobado' => 'fas fa-check-circle',
        'Rechazado' => 'fas fa-times-circle',
        default => 'fas fa-clock'
    };
    
    // Solo permitir edición si la sección NO está aprobada
    $permitirEdicion = $aprobado !== true;
@endphp

@if($editable)
<div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-lg">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center space-x-2">
            <i class="{{ $estadoIcon }} text-sm"></i>
            <span class="text-sm font-medium text-gray-700">Estado de la Sección</span>
        </div>
        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border {{ $estadoColors }}">
            <div class="w-2 h-2 rounded-full mr-1.5 
                {{ $estado === 'Aprobado' ? 'bg-green-400' : 
                   ($estado === 'Rechazado' ? 'bg-red-400' : 'bg-amber-400') }}">
            </div>
            {{ $estado }}
        </span>
    </div>
    
    @if($comentario)
        <div class="bg-blue-50 border-l-3 border-blue-400 p-3 rounded-r-lg">
            <div class="flex items-start space-x-2">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-medium text-blue-800 mb-1">Observación del Revisor</p>
                    <p class="text-xs text-blue-700">{{ $comentario }}</p>
                    @if($revisadoPor && $fechaRevision)
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-user mr-1"></i>
                            {{ $revisadoPor }} - {{ $fechaRevision }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    @if($aprobado === true)
        <div class="mt-3 p-2 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-lock text-green-600 text-sm"></i>
                <span class="text-xs text-green-700 font-medium">
                    Esta sección está aprobada y no puede ser modificada
                </span>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const seccionContainer = document.querySelector('[data-seccion="{{ $seccion }}"]');
                
                if (seccionContainer) {
                    const inputs = seccionContainer.querySelectorAll('input, textarea, select');
                    const buttons = seccionContainer.querySelectorAll('button[type="button"]');
                    
                    inputs.forEach(input => {
                        if (input.type !== 'hidden') {
                            input.disabled = true;
                            input.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    });
                    
                    buttons.forEach(button => {
                        button.disabled = true;
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                    
                    const mensaje = document.createElement('div');
                    mensaje.className = 'mt-3 p-2 bg-yellow-50 border border-yellow-200 rounded-lg';
                    mensaje.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-lock text-yellow-600 text-sm"></i>
                            <span class="text-xs text-yellow-700 font-medium">
                                Los campos están deshabilitados porque esta sección está aprobada
                            </span>
                        </div>
                    `;
                    seccionContainer.appendChild(mensaje);
                }
            });
        </script>
    @else
        <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center space-x-2">
                <i class="fas fa-edit text-blue-600 text-sm"></i>
                <span class="text-xs text-blue-700 font-medium">
                    Esta sección puede ser editada para realizar correcciones
                </span>
            </div>
        </div>
    @endif
</div>
@endif 