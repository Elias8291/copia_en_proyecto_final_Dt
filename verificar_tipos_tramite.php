<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tramite;

$tipos = Tramite::select('tipo_tramite')->distinct()->pluck('tipo_tramite')->toArray();

echo "=== TIPOS DE TRÁMITE EXISTENTES ===\n";
foreach ($tipos as $tipo) {
    echo "- " . $tipo . "\n";
} 