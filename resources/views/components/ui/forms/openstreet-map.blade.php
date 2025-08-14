@props([
    'lat' => null,
    'lng' => null,
    'editable' => false,
    'height' => '300px',
])

<div class="relative space-y-2">
    <div id="mapa" class="rounded border overflow-hidden" style="height: {{ $height }}; position: relative; z-index: 1;"></div>

    @if($editable)
        <button type="button" id="ubicacion-btn" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
            Obtener ubicación actual
        </button>
        <div id="coordenadas-display" class="text-sm text-gray-700"></div>
    @endif
</div>

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* Corregir el posicionamiento del mapa */
        #mapa {
            position: relative !important;
            overflow: hidden !important;
            z-index: 1 !important;
        }
        
        #mapa .leaflet-container {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            z-index: 1 !important;
        }
        
        /* Asegurar que los controles del mapa se mantengan dentro del contenedor */
        #mapa .leaflet-control-container {
            position: absolute !important;
            z-index: 2 !important;
        }
        
        #mapa .leaflet-control-zoom {
            position: absolute !important;
            top: 10px !important;
            right: 10px !important;
            z-index: 2 !important;
        }
        
        /* Asegurar que los popups se mantengan dentro del contenedor */
        #mapa .leaflet-popup {
            position: absolute !important;
            z-index: 3 !important;
        }
        
        /* Contenedor padre debe tener overflow hidden */
        .relative.space-y-2 {
            overflow: hidden !important;
        }
    </style>
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editable = @json($editable);
    let lat = {{ $lat ?? 19.4326 }};
    let lng = {{ $lng ?? -99.1332 }};
    const hasCoords = {{ $lat ? 'true' : 'false' }} && {{ $lng ? 'true' : 'false' }};

    let map = null;
    let marker = null;

    function initMap() {
        const mapContainer = document.getElementById('mapa');
        if (!mapContainer) return;

        if (mapContainer.offsetHeight === 0) {
            setTimeout(initMap, 100);
            return;
        }

        try {
            // Asegurar que el contenedor tenga las propiedades correctas
            mapContainer.style.position = 'relative';
            mapContainer.style.overflow = 'hidden';
            mapContainer.style.zIndex = '1';

            map = L.map('mapa', {
                zoomControl: true,
                scrollWheelZoom: editable,
                doubleClickZoom: editable,
                boxZoom: false,
                keyboard: false,
                dragging: editable,
                touchZoom: editable
            }).setView([lat, lng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            if (hasCoords) {
                marker = L.marker([lat, lng], { draggable: editable }).addTo(map);
                if (editable) {
                    marker.on('dragend', e => updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng));
                }
            }

            if (editable) {
                map.on('click', e => updateCoords(e.latlng.lat, e.latlng.lng));
                document.getElementById('ubicacion-btn')?.addEventListener('click', getLocation);
            }

            // Forzar el redimensionamiento del mapa
            setTimeout(() => {
                if (map) {
                    map.invalidateSize();
                    // Asegurar que el mapa se mantenga dentro del contenedor
                    const leafletContainer = mapContainer.querySelector('.leaflet-container');
                    if (leafletContainer) {
                        leafletContainer.style.position = 'absolute';
                        leafletContainer.style.top = '0';
                        leafletContainer.style.left = '0';
                        leafletContainer.style.right = '0';
                        leafletContainer.style.bottom = '0';
                    }
                }
            }, 100);

        } catch (error) {
            console.error('Error inicializando mapa:', error);
            setTimeout(initMap, 200);
        }
    }

    function updateCoords(newLat, newLng) {
        lat = newLat;
        lng = newLng;

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: editable }).addTo(map);
            if (editable) {
                marker.on('dragend', e => updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng));
            }
        }
        
        const latInput = document.getElementById('latitud-manual');
        const lngInput = document.getElementById('longitud-manual');
        const display = document.getElementById('coordenadas-display');
        const displayDomicilio = document.getElementById('coordenadas-display-domicilio');
        
        if (latInput) latInput.value = lat.toFixed(6);
        if (lngInput) lngInput.value = lng.toFixed(6);
        if (display) display.textContent = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        if (displayDomicilio) displayDomicilio.textContent = `Coordenadas seleccionadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        window.dispatchEvent(new CustomEvent('coordenadasActualizadas', { 
            detail: { lat, lng } 
        }));
    }

    function getLocation() {
        const btn = document.getElementById('ubicacion-btn');
        btn.textContent = 'Obteniendo...';
        btn.disabled = true;
        
        if (navigator.geolocation) {
            const options = {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            };
            
            navigator.geolocation.getCurrentPosition(
                pos => {
                    const accuracy = pos.coords.accuracy;
                    updateCoords(pos.coords.latitude, pos.coords.longitude);
                    map.setView([pos.coords.latitude, pos.coords.longitude], 18);
                    
                    if (accuracy <= 10) {
                        btn.textContent = 'Ubicación precisa';
                    } else if (accuracy <= 50) {
                        btn.textContent = 'Ubicación aproximada';
                    } else {
                        btn.textContent = 'Ubicación imprecisa';
                    }
                    
                    setTimeout(() => {
                        btn.textContent = 'Obtener ubicación actual';
                        btn.disabled = false;
                    }, 3000);
                },
                error => {
                    let errorMsg = 'Error al obtener ubicación';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Permiso denegado';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Ubicación no disponible';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Tiempo agotado';
                            break;
                    }
                    
                    btn.textContent = errorMsg;
                    setTimeout(() => {
                        btn.textContent = 'Obtener ubicación actual';
                        btn.disabled = false;
                    }, 3000);
                },
                options
            );
        } else {
            alert('Geolocalización no soportada');
            btn.textContent = 'Obtener ubicación actual';
            btn.disabled = false;
        }
    }

    setTimeout(initMap, 100);

    window.addEventListener('resize', () => {
        if (map) {
            setTimeout(() => {
                map.invalidateSize();
                // Asegurar que el mapa se mantenga dentro del contenedor después del redimensionamiento
                const mapContainer = document.getElementById('mapa');
                const leafletContainer = mapContainer?.querySelector('.leaflet-container');
                if (leafletContainer) {
                    leafletContainer.style.position = 'absolute';
                    leafletContainer.style.top = '0';
                    leafletContainer.style.left = '0';
                    leafletContainer.style.right = '0';
                    leafletContainer.style.bottom = '0';
                }
            }, 100);
        }
    });

    // Evento personalizado para forzar la inicialización del mapa
    window.addEventListener('forceMapInitialization', () => {
        if (!map) {
            setTimeout(initMap, 100);
        } else {
            map.invalidateSize();
        }
    });
});
</script>
