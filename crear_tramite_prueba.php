<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tramite;
use App\Models\Cita;
use Carbon\Carbon;

// Crear un trámite de prueba
$tramite = Tramite::create([
    'proveedor_id' => 5, // Usar el mismo proveedor
    'tipo_tramite' => 'Actualizacion',
    'estado' => 'Por_Cotejar',
    'fecha_inicio' => now(),
    'paso_actual' => 1
]);

// Crear una cita vencida para este trámite
$cita = Cita::create([
    'tramite_id' => $tramite->id,
    'fecha_cita' => Carbon::now()->subDays(2), // Cita de hace 2 días
    'estado' => 'Programada',
    'contador_reagendamientos' => 0,
    'max_reagendamientos' => 2,
    'observaciones' => 'Cita de prueba vencida'
]);

echo "=== TRÁMITE DE PRUEBA CREADO ===\n";
echo "Trámite ID: " . $tramite->id . "\n";
echo "Estado: " . $tramite->estado . "\n";
echo "Cita ID: " . $cita->id . "\n";
echo "Fecha cita: " . $cita->fecha_cita->format('d/m/Y H:i') . "\n";
echo "Estado cita: " . $cita->estado . "\n";
echo "Reagendamientos usados: " . $cita->contador_reagendamientos . "\n";
echo "Max reagendamientos: " . $cita->max_reagendamientos . "\n"; 