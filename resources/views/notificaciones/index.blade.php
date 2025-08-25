@extends('layouts.app')

@section('content')
<div class="p-3 sm:p-4 md:p-5 lg:p-6 xl:p-8">
    <div class="max-w-7xl mx-auto bg-white shadow-sm rounded-lg border border-gray-200">        
        <div class="p-6 border-b border-gray-200/70">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#9d2449] via-[#8a1f40] to-[#7a1a37] rounded-xl p-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Logs del Sistema</h1>
                        <p class="text-base text-gray-500 mt-1">Registros automáticos de errores y eventos del sistema</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logs.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Ver Logs Completos
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 p-2 sm:p-3 md:p-4 lg:p-5 mb-4 sm:mb-5 md:mb-6 lg:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 md:gap-5">
                <div class="flex items-center gap-2 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#9d2449]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-xs sm:text-sm md:text-base lg:text-lg text-gray-700">
                        <span class="font-medium text-[#9d2449]">{{ $logs->total() ?? 0 }}</span> 
                        {{ ($logs->total() ?? 0) == 1 ? 'registro encontrado' : 'registros encontrados' }}
                        @if(isset($logs) && $logs->hasPages())
                            <span class="text-gray-500 ml-1 sm:ml-2 md:ml-3">
                                ({{ $logs->firstItem() }}-{{ $logs->lastItem() }})
                            </span>
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-sm text-gray-500">
                        Última actualización: {{ now()->format('d/m/Y H:i:s') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100">
            <div class="p-4 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-lg">Sistema de Logs Automático</p>
                    <p class="text-sm text-gray-400 mt-1">
                        Todos los errores del sistema se registran automáticamente en los logs.<br>
                        No se muestran notificaciones al usuario, solo se registran para auditoría.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('logs.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#9d2449] text-white text-sm font-medium rounded-lg hover:bg-[#8a1f40] focus:outline-none focus:ring-2 focus:ring-[#9d2449]/50 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Ver Logs del Sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-ui.modals.error-modal 
    id="error-modal"
    title="Error"
    message="Ha ocurrido un error. Por favor, inténtalo de nuevo."
    buttonText="OK"
/>

<x-ui.modals.modal-exito 
    id="success-modal"
    title="¡Éxito!"
    message="La operación se realizó correctamente."
    acceptText="Aceptar"
    :redirectUrl="route('notificaciones.index')"
/>

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showErrorModal('error-modal', 'Error', '{{ session("error") }}');
});
</script>
@endif
                            
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showSuccessModal('success-modal', '¡Éxito!', '{{ session("success") }}');
});
</script>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Auto-refresh cada 30 segundos para mostrar logs actualizados
        setInterval(function() {
            location.reload();
        }, 30000);
    } catch (error) {
        console.warn('Error initializing logs page JavaScript:', error);
    }
});
</script>
@endpush
@endsection
