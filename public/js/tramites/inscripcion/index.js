// Inicializa todos los módulos del formulario de inscripción

document.addEventListener('DOMContentLoaded', () => {
    // Inicializar domicilio flexible (reemplaza el código postal anterior)
    if (typeof initDomicilioFlexible === 'function') {
        initDomicilioFlexible();
    }
    
    // Inicializar módulo de actividades
    if (typeof ActividadesBuscar !== 'undefined' && document.getElementById('buscar-actividades')) {
        window.actividadesManager = new ActividadesBuscar();
    }
    
    // Inicializar formulario principal
    if (typeof FormularioInscripcion !== 'undefined') {
        window.formularioInscripcion = new FormularioInscripcion();
    }
}); 