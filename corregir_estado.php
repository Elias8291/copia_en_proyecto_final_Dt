<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tramite;

$tramite = Tramite::find(19);

if ($tramite) {
    echo "Estado anterior: " . $tramite->estado . "\n";
    
    // Corregir el estado del trámite
    $tramite->update([
        'estado' => 'Rechazado',
        'fecha_cancelacion' => now(),
        'observaciones' => 'Rechazado por no asistir a la segunda cita'
    ]);
    
    echo "Estado corregido: " . $tramite->estado . "\n";
    echo "Fecha cancelación: " . $tramite->fecha_cancelacion->format('d/m/Y H:i') . "\n";
} else {
    echo "Trámite no encontrado\n";
} 