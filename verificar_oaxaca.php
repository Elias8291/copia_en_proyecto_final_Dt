<?php

require_once 'bootstrap/app.php';

use App\Models\Direccion;

echo "🗺️  Verificando proveedores recién creados y sus coordenadas:\n\n";

$direcciones = Direccion::with(['coordenada', 'estado', 'proveedor'])
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($direcciones as $index => $direccion) {
    echo "Proveedor " . ($index + 1) . ":\n";
    echo "  Empresa: " . $direccion->proveedor->razon_social . "\n";
    echo "  Estado: " . $direccion->estado->nombre . "\n";
    echo "  Municipio: " . $direccion->municipio . "\n";
    echo "  Coordenadas: " . $direccion->coordenada->latitud . ", " . $direccion->coordenada->longitud . "\n";
    
    // Determinar si está en Oaxaca basándose en las coordenadas
    $lat = $direccion->coordenada->latitud;
    $lng = $direccion->coordenada->longitud;
    
    if ($lat >= 15.65 && $lat <= 18.66 && $lng >= -98.75 && $lng <= -93.95) {
        echo "  ✅ Coordenadas en territorio de Oaxaca\n";
        
        // Verificar si está cerca de alguna ciudad principal
        $ciudadesOaxaca = [
            ['nombre' => 'Oaxaca de Juárez', 'lat' => 17.0732, 'lng' => -96.7266],
            ['nombre' => 'Salina Cruz', 'lat' => 16.1647, 'lng' => -95.1903],
            ['nombre' => 'Tuxtepec', 'lat' => 18.0951, 'lng' => -96.1267],
            ['nombre' => 'Juchitán', 'lat' => 16.4347, 'lng' => -95.0122],
            ['nombre' => 'Puerto Escondido', 'lat' => 15.8539, 'lng' => -97.0700],
        ];
        
        foreach ($ciudadesOaxaca as $ciudad) {
            $distancia = sqrt(pow($lat - $ciudad['lat'], 2) + pow($lng - $ciudad['lng'], 2));
            if ($distancia <= 0.15) { // ~15km aproximadamente
                echo "  📍 Cerca de " . $ciudad['nombre'] . "\n";
                break;
            }
        }
    } else {
        echo "  ⚠️  Coordenadas fuera de Oaxaca\n";
    }
    echo "\n";
}

// Contar total de proveedores por estado
echo "📊 Estadísticas por estado:\n";
$estadisticas = Direccion::with('estado')
    ->selectRaw('estado_id, count(*) as total')
    ->groupBy('estado_id')
    ->orderBy('total', 'desc')
    ->get();

foreach ($estadisticas as $stat) {
    echo "  " . $stat->estado->nombre . ": " . $stat->total . " proveedores\n";
}
