@props([
    'showAprobar' => true,
    'showCorrecciones' => true,
    'showRechazar' => true,
    'textoAprobar' => 'Aprobar y Agendar Cita',
    'textoCorrecciones' => 'Rechazar y Para Corrección',
    'textoRechazar' => 'Rechazar Trámite',
    'layout' => 'grid' // grid, flex
])

@php
    $containerClasses = match($layout) {
        'flex' => 'flex flex-col sm:flex-row gap-2 justify-center',
        default => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 justify-items-center'
    };
    $tramiteId = request()->route('tramite');
@endphp

<div class="{{ $containerClasses }}">
    @if($showAprobar)
        <form action="{{ route('revisiones.aprobar-y-agendar', $tramiteId) }}" method="POST" class="inline-block">
            @csrf
            <input type="hidden" name="comentario_general" id="form_comentario_general_aprobar">
        <button 
            type="button" 
                onclick="confirmarDecisionFinal(this, '{{ $textoAprobar }}', '¿Está seguro que desea aprobar este trámite y agendar una cita presencial? Esta acción no se puede deshacer.')"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ $textoAprobar }}</span>
        </button>
        </form>
    @endif
    
    @if($showCorrecciones)
        <form action="{{ route('revisiones.rechazar-correccion', $tramiteId) }}" method="POST" class="inline-block">
            @csrf
            <input type="hidden" name="comentario_general" id="form_comentario_general_correccion">
        <button 
            type="button" 
                onclick="confirmarDecisionFinal(this, '{{ $textoCorrecciones }}', '¿Está seguro que desea rechazar este trámite y enviarlo para corrección? El solicitante deberá realizar los cambios solicitados.')"
            class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.084 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <span>{{ $textoCorrecciones }}</span>
        </button>
        </form>
    @endif
    
    @if($showRechazar)
        <form action="{{ route('revisiones.rechazar-completo', $tramiteId) }}" method="POST" class="inline-block">
            @csrf
            <input type="hidden" name="comentario_general" id="form_comentario_general_rechazar">
        <button 
            type="button" 
                onclick="confirmarDecisionFinal(this, '{{ $textoRechazar }}', '¿Está seguro que desea rechazar este trámite? Esta acción no se puede deshacer y el trámite será cancelado.')"
            class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>{{ $textoRechazar }}</span>
        </button>
        </form>
    @endif
</div>

<script>
function confirmarDecisionFinal(button, titulo, mensaje) {
   
    const form = button.closest('form');
    if (!form) return;
    
   
    if (typeof showConfirmModal === 'function') {
        showConfirmModal(titulo, mensaje, null, function() {
            enviarFormulario(form);
        });
    } else {
       
        if (confirm(`${titulo}\n\n${mensaje}`)) {
            enviarFormulario(form);
        }
    }
}

function enviarFormulario(form) {

    const comentarioGeneral = document.getElementById('comentario_general')?.value || '';
    const hiddenInput = form.querySelector('input[name="comentario_general"]');
    if (hiddenInput) {
        hiddenInput.value = comentarioGeneral;
    }

    form.submit();
}
</script> 