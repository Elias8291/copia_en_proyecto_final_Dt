<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tramite;
use App\Models\Coordenada;
use App\Models\Direccion;

// Buscar un trámite existente
$tramite = Tramite::first();

if (!$tramite) {
    echo "No se encontró ningún trámite\n";
    exit;
}

echo "Trámite encontrado: ID " . $tramite->id . "\n";

// Crear coordenadas de ejemplo (Centro de CDMX)
$coordenadas = Coordenada::create([
    'latitud' => 19.4326,
    'longitud' => -99.1332
]);

echo "Coordenadas creadas: " . $coordenadas->latitud . ", " . $coordenadas->longitud . "\n";

// Crear dirección de ejemplo
$direccion = Direccion::create([
    'id_tramite' => $tramite->id,
    'calle' => 'Av. Juárez',
    'numero_exterior' => '123',
    'codigo_postal' => '06000',
    'asentamiento' => 'Centro',
    'municipio' => 'Cuauhtémoc',
    'estado_id' => 9, // CDMX
    'coordenadas_id' => $coordenadas->id
]);

echo "Dirección creada: " . $direccion->calle . " " . $direccion->numero_exterior . "\n";
echo "Coordenadas asociadas: " . $direccion->coordenadas->latitud . ", " . $direccion->coordenadas->longitud . "\n";

echo "\n¡Coordenadas agregadas exitosamente!\n"; 