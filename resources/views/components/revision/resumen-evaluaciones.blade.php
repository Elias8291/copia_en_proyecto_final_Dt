@props([
    'secciones' => []
])

<!-- Resumen de decisiones por sección -->
<div class="bg-gray-50 rounded-lg p-4">
    <h4 class="text-sm font-medium text-gray-700 mb-3">Resumen de Evaluación por Secciones</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="resumen-secciones">
        @foreach($secciones as $seccion)
        <div id="resumen_{{ $seccion }}" class="flex items-center justify-between p-2 bg-white rounded border">
            <span class="text-sm">{{ ucfirst(str_replace('_', ' ', $seccion)) }}</span>
            <span class="seccion-estado px-2 py-1 rounded text-xs bg-gray-100 text-gray-600">Pendiente</span>
        </div>
        @endforeach
    </div>
</div> 