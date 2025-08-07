@props([
    'showAprobar' => true,
    'showCorrecciones' => true,
    'showRechazar' => true,
    'textoAprobar' => 'Aprobar y Agendar Cita',
    'textoCorrecciones' => 'Para Corrección',
    'textoRechazar' => 'Rechazar Trámite',
    'layout' => 'grid', // grid, flex
    'formId' => 'formRevisionCompleta'
])

@php
    $containerClasses = match($layout) {
        'flex' => 'flex flex-col sm:flex-row gap-2 justify-center',
        default => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 justify-items-center'
    };
@endphp

<div class="{{ $containerClasses }}">
    @if($showAprobar)
        <button 
            type="button" 
            onclick="confirmarDecision('agendar_cita', '{{ $textoAprobar }}', '¿Está seguro que desea aprobar este trámite y agendar una cita presencial? Esta acción no se puede deshacer.')"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ $textoAprobar }}</span>
        </button>
    @endif
    

    
    @if($showCorrecciones)
        <button 
            type="button" 
            onclick="confirmarDecision('correcciones', '{{ $textoCorrecciones }}', '¿Está seguro que desea enviar este trámite para corrección? El solicitante deberá realizar los cambios solicitados.')"
            class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.084 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <span>{{ $textoCorrecciones }}</span>
        </button>
    @endif
    
    @if($showRechazar)
        <button 
            type="button" 
            onclick="confirmarDecision('rechazado', '{{ $textoRechazar }}', '¿Está seguro que desea rechazar este trámite? Esta acción no se puede deshacer y el trámite será cancelado.')"
            class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-md transition-all duration-200 flex items-center justify-center space-x-1.5 shadow-sm hover:shadow-md min-w-[120px] text-sm"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>{{ $textoRechazar }}</span>
        </button>
    @endif
</div>

<script>
function confirmarDecision(decision, titulo, mensaje) {
    console.log('confirmarDecision llamado:', { decision, titulo, mensaje });
    
    // Verificar que showConfirmModal existe
    if (typeof showConfirmModal !== 'function') {
        console.error('showConfirmModal no está definida');
        alert('Error: Función de confirmación no disponible');
        return;
    }
    
    // Verificar que el formulario existe
    const form = document.getElementById('{{ $formId }}');
    if (!form) {
        console.error('Formulario no encontrado:', '{{ $formId }}');
        alert('Error: Formulario no encontrado');
        return;
    }
    
    console.log('Formulario encontrado:', form);
    
    // Mostrar el modal de confirmación
    showConfirmModal(
        'Confirmar: ' + titulo,
        mensaje,
        '{{ $formId }}',
        function() {
            console.log('Callback de confirmación ejecutado');
            // Callback que se ejecuta cuando se confirma
            const form = document.getElementById('{{ $formId }}');
            if (form) {
                console.log('Procesando formulario...');
                // Crear un campo hidden para la decisión final
                let hiddenField = form.querySelector('input[name="decision_final"]');
                if (!hiddenField) {
                    hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'decision_final';
                    form.appendChild(hiddenField);
                }
                hiddenField.value = decision;
                console.log('Decisión final establecida:', decision);
                
                // Enviar el formulario
                console.log('Enviando formulario...');
                form.submit();
            } else {
                console.error('Formulario no encontrado en callback');
            }
        }
    );
}
</script> 