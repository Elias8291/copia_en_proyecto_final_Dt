@props([
    'lat' => null,
    'lng' => null,
    'editable' => false,
    'height' => '300px',
])

<div class="relative space-y-2">
    <div id="mapa" class="rounded border" style="height: {{ $height }};"></div>

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
@endonce

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editable = @json($editable);
    let lat = {{ $lat ?? 19.4326 }};
    let lng = {{ $lng ?? -99.1332 }};
    const hasCoords = {{ $lat ? 'true' : 'false' }} && {{ $lng ? 'true' : 'false' }};

    let map = null;
    let marker = null;
    let mapInitialized = false;
    let initializationAttempts = 0;
    const maxAttempts = 10;

    function isElementVisible(element) {
        if (!element) return false;
        
        const rect = element.getBoundingClientRect();
        const style = window.getComputedStyle(element);
        
        return rect.width > 0 && 
               rect.height > 0 && 
               style.display !== 'none' && 
               style.visibility !== 'hidden' && 
               style.opacity !== '0';
    }

    function initializeMap() {
        if (mapInitialized) return;
        
        const mapContainer = document.getElementById('mapa');
        if (!mapContainer) {
            console.warn('Map container not found');
            return;
        }

        // Verificar si el contenedor es visible
        if (!isElementVisible(mapContainer)) {
            initializationAttempts++;
            if (initializationAttempts < maxAttempts) {
                setTimeout(initializeMap, 200);
            } else {
                console.warn('Map initialization failed after', maxAttempts, 'attempts');
            }
            return;
        }

        try {
            map = L.map('mapa').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            marker = hasCoords ? L.marker([lat, lng], { draggable: editable }).addTo(map) : null;
            mapInitialized = true;
            initializationAttempts = 0;

            // Configurar eventos si es editable
            if (editable) {
                map.on('click', e => updateCoords(e.latlng.lat, e.latlng.lng));
                
                if (marker) marker.on('dragend', e => updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng));
                
                ['latitud-manual', 'longitud-manual'].forEach(id => {
                    const input = document.getElementById(id);
                    if (input) {
                        input.addEventListener('change', () => {
                            const latVal = document.getElementById('latitud-manual')?.value;
                            const lngVal = document.getElementById('longitud-manual')?.value;
                            if (latVal && lngVal) {
                                const newLat = parseFloat(latVal);
                                const newLng = parseFloat(lngVal);
                                if (!isNaN(newLat) && !isNaN(newLng)) {
                                    updateCoords(newLat, newLng);
                                    map.setView([newLat, newLng], 13);
                                }
                            }
                        });
                    }
                });
                
                document.getElementById('ubicacion-btn')?.addEventListener('click', () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(pos => {
                            updateCoords(pos.coords.latitude, pos.coords.longitude);
                            map.setView([pos.coords.latitude, pos.coords.longitude], 13);
                        }, () => alert('No se pudo obtener la ubicación'));
                    } else {
                        alert('Geolocalización no soportada');
                    }
                });
            }

            // Disparar evento personalizado para notificar que el mapa está listo
            window.dispatchEvent(new CustomEvent('mapaInicializado', { detail: { map, marker } }));
            
            console.log('Map initialized successfully');
        } catch (error) {
            console.error('Error initializing map:', error);
            mapInitialized = false;
        }
    }

    function updateCoords(lat, lng) {
        console.log('updateCoords llamado con:', { lat, lng });
        
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else if (map) {
            marker = L.marker([lat, lng], { draggable: editable }).addTo(map);
        }
        
        // Actualizar inputs directamente
        const latInput = document.getElementById('latitud-manual');
        const lngInput = document.getElementById('longitud-manual');
        const display = document.getElementById('coordenadas-display');
        const displayDomicilio = document.getElementById('coordenadas-display-domicilio');
        
        if (latInput) {
            latInput.value = lat.toFixed(6);
            console.log('Latitud actualizada en input:', latInput.value);
        }
        if (lngInput) {
            lngInput.value = lng.toFixed(6);
            console.log('Longitud actualizada en input:', lngInput.value);
        }
        if (display) display.textContent = `Coordenadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        if (displayDomicilio) displayDomicilio.textContent = `Coordenadas seleccionadas: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        // Disparar evento personalizado para notificar cambio de coordenadas
        console.log('Disparando evento coordenadasActualizadas:', { lat, lng });
        window.dispatchEvent(new CustomEvent('coordenadasActualizadas', { 
            detail: { lat, lng } 
        }));
        
        // También disparar un evento de input para activar cualquier listener de input
        if (latInput) {
            latInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (lngInput) {
            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function resizeMap() {
        if (map && mapInitialized) {
            try {
                setTimeout(() => {
                    map.invalidateSize();
                    console.log('Map resized successfully');
                }, 100);
            } catch (error) {
                console.error('Error resizing map:', error);
            }
        }
    }

    // Inicializar mapa inmediatamente si es posible
    initializeMap();

    // Escuchar cambios en los steps para redimensionar el mapa
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                if (target.classList.contains('step-content') && target.classList.contains('active')) {
                    // Si este step contiene el mapa, redimensionarlo
                    if (target.querySelector('#mapa')) {
                        setTimeout(resizeMap, 100);
                    }
                }
            }
        });
    });

    // Observar cambios en los steps
    document.querySelectorAll('.step-content').forEach(step => {
        observer.observe(step, { attributes: true });
    });

    // También escuchar el evento personalizado de navegación de steps
    window.addEventListener('stepChanged', function(event) {
        const currentStep = event.detail?.currentStep;
        const stepElement = document.querySelector(`[data-step="${currentStep}"]`);
        
        if (stepElement && stepElement.querySelector('#mapa')) {
            // Si el mapa no está inicializado, intentar inicializarlo
            if (!mapInitialized) {
                setTimeout(initializeMap, 100);
            } else {
                setTimeout(resizeMap, 100);
            }
        }
    });

    // Escuchar cuando el DOM cambie (para casos donde los steps se cargan dinámicamente)
    const domObserver = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('step-content')) {
                        if (node.querySelector('#mapa')) {
                            setTimeout(initializeMap, 100);
                        }
                    }
                });
            }
        });
    });

    domObserver.observe(document.body, { childList: true, subtree: true });

    // Escuchar cambios en el tamaño de la ventana
    window.addEventListener('resize', function() {
        if (mapInitialized) {
            setTimeout(resizeMap, 100);
        }
    });

    // Escuchar evento para forzar la inicialización del mapa
    window.addEventListener('forceMapInitialization', function() {
        if (!mapInitialized) {
            setTimeout(initializeMap, 100);
        } else {
            setTimeout(resizeMap, 100);
        }
    });
});
</script>
