// Script de prueba para el buscador de actividades
console.log('Test Actividades: Script de prueba cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('Test Actividades: DOM cargado');
    
    // Verificar que los elementos existan
    const buscador = document.getElementById('buscador-actividad');
    const resultados = document.getElementById('resultados-actividades');
    const seleccionadas = document.getElementById('actividades-seleccionadas');
    
    console.log('Test Actividades: Elementos encontrados:', {
        buscador: !!buscador,
        resultados: !!resultados,
        seleccionadas: !!seleccionadas
    });
    
    // Verificar que la clase ActividadesBuscar esté disponible
    if (typeof ActividadesBuscar !== 'undefined') {
        console.log('Test Actividades: Clase ActividadesBuscar disponible');
        
        // Crear instancia de prueba
        try {
            const instancia = new ActividadesBuscar();
            console.log('Test Actividades: Instancia creada exitosamente');
        } catch (error) {
            console.error('Test Actividades: Error al crear instancia:', error);
        }
    } else {
        console.error('Test Actividades: Clase ActividadesBuscar no disponible');
    }
    
    // Verificar que la función removeActividad esté disponible
    if (typeof window.removeActividad === 'function') {
        console.log('Test Actividades: Función removeActividad disponible');
    } else {
        console.error('Test Actividades: Función removeActividad no disponible');
    }
}); 