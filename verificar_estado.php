<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tramite;

$tramite = Tramite::with(['cita', 'proveedor.user'])->find(19);

if ($tramite) {
    echo "=== ESTADO ACTUAL DEL TRÁMITE ===\n";
    echo "Trámite ID: " . $tramite->id . "\n";
    echo "Estado: " . $tramite->estado . "\n";
    echo "Observaciones: " . $tramite->observaciones . "\n";
    echo "Fecha inicio: " . ($tramite->fecha_inicio ? $tramite->fecha_inicio->format('d/m/Y H:i') : 'N/A') . "\n";
    echo "Fecha cancelación: " . ($tramite->fecha_cancelacion ? $tramite->fecha_cancelacion->format('d/m/Y H:i') : 'N/A') . "\n";
    
    if ($tramite->cita) {
        echo "\n=== ESTADO DE LA CITA ===\n";
        echo "Cita ID: " . $tramite->cita->id . "\n";
        echo "Estado: " . $tramite->cita->estado . "\n";
        echo "Fecha cita: " . $tramite->cita->fecha_cita->format('d/m/Y H:i') . "\n";
        echo "Reagendamientos usados: " . ($tramite->cita->contador_reagendamientos ?? 0) . "\n";
        echo "Max reagendamientos: " . ($tramite->cita->max_reagendamientos ?? 2) . "\n";
        echo "Observaciones: " . ($tramite->cita->observaciones ?? 'N/A') . "\n";
    } else {
        echo "\nNo hay cita asociada\n";
    }
    
    echo "\n=== USUARIO ===\n";
    echo "Usuario: " . $tramite->proveedor->user->nombre . "\n";
    echo "Correo: " . $tramite->proveedor->user->correo . "\n";
} else {
    echo "Trámite no encontrado\n";
} 