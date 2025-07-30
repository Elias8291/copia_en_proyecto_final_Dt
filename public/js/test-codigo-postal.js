// Script de prueba para el handler de código postal
console.log('Test Código Postal: Script de prueba cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('Test Código Postal: DOM cargado');
    
    // Verificar que los elementos existan
    const cpInput = document.getElementById('codigo_postal');
    const estadoSelect = document.getElementById('estado');
    const municipioSelect = document.getElementById('municipio');
    const asentamientoSelect = document.getElementById('asentamiento');
    
    console.log('Test Código Postal: Elementos encontrados:', {
        cpInput: !!cpInput,
        estadoSelect: !!estadoSelect,
        municipioSelect: !!municipioSelect,
        asentamientoSelect: !!asentamientoSelect
    });
    
    // Verificar que el script de código postal se haya cargado
    if (cpInput) {
        console.log('Test Código Postal: Input de código postal encontrado');
        
        // Simular un evento de input para probar la funcionalidad
        setTimeout(() => {
            if (cpInput.value) {
                console.log('Test Código Postal: Código postal inicial:', cpInput.value);
            }
        }, 1000);
    } else {
        console.error('Test Código Postal: Input de código postal no encontrado');
    }
}); 