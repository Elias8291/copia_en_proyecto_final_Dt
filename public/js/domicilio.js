
/**
 * Módulo de mapa para domicilio
 * Evita conflictos de nombres usando namespace
 */

// Evitar redeclaración y usar namespace
if (typeof DomicilioMap === 'undefined') {
    window.DomicilioMap = (function() {
        let map;
        let marker;

        function initMap() {
            // Verificar si el elemento del mapa existe
            const mapElement = document.getElementById("map");
            if (!mapElement) {
                return; // No hay elemento de mapa, salir silenciosamente
            }

            const initialPosition = { lat: -34.397, lng: 150.644 };
            map = new google.maps.Map(mapElement, {
                center: initialPosition,
                zoom: 8,
            });

            marker = new google.maps.Marker({
                position: initialPosition,
                map: map,
                draggable: true,
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                const latInput = document.getElementById("latitud");
                const lngInput = document.getElementById("longitud");
                
                if (latInput) latInput.value = this.getPosition().lat();
                if (lngInput) lngInput.value = this.getPosition().lng();
            });
        }

        // Exponer función pública
        return {
            init: initMap
        };
    })();

    // Hacer la función disponible globalmente para compatibilidad
    window.initMap = function() {
        DomicilioMap.init();
    };
}
