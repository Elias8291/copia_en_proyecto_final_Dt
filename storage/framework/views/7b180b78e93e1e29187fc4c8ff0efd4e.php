<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'lat' => null,
    'lng' => null,
    'editable' => false,
    'height' => '300px',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'lat' => null,
    'lng' => null,
    'editable' => false,
    'height' => '300px',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="relative space-y-2 mapa-container">
    <div id="mapa" class="rounded border" style="height: <?php echo e($height); ?>;"></div>

    <?php if($editable): ?>
        <button type="button" id="ubicacion-btn" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
            Obtener ubicación actual
        </button>
        <div id="coordenadas-display" class="text-sm text-gray-700"></div>
    <?php endif; ?>
</div>

<?php if (! $__env->hasRenderedOnce('1557b25b-1383-48b7-a3aa-6659c8e6aa6c')): $__env->markAsRenderedOnce('1557b25b-1383-48b7-a3aa-6659c8e6aa6c'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        /* Controlar z-index del mapa para que no aparezca encima de todo */
        .leaflet-container {
            z-index: 1 !important;
        }
        
        .leaflet-control-container {
            z-index: 2 !important;
        }
        
        .leaflet-popup {
            z-index: 3 !important;
        }
        
        .leaflet-tooltip {
            z-index: 4 !important;
        }
        
        /* Asegurar que el contenedor del mapa tenga position relative */
        #mapa {
            position: relative !important;
            z-index: 1 !important;
        }
        
        /* Asegurar que el contenedor padre tenga overflow hidden si es necesario */
        .mapa-container {
            position: relative;
            overflow: hidden;
        }
    </style>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editable = <?php echo json_encode($editable, 15, 512) ?>;
    let lat = <?php echo e($lat ?? 19.4326); ?>;
    let lng = <?php echo e($lng ?? -99.1332); ?>;
    const hasCoords = <?php echo e($lat ? 'true' : 'false'); ?> && <?php echo e($lng ? 'true' : 'false'); ?>;

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
            map = L.map('mapa').setView([lat, lng], 13);
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

            setTimeout(() => {
                if (map) map.invalidateSize();
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
            setTimeout(() => map.invalidateSize(), 100);
        }
    });
});
</script>
<?php /**PATH C:\Users\Elias\Documents\copia_en_proyecto_final_Dt\resources\views/components/ui/forms/openstreet-map.blade.php ENDPATH**/ ?>