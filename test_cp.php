<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Verificando código postal 71233...\n";

try {
    $result = DB::select('SELECT COUNT(*) as total FROM asentamientos WHERE codigo_postal = ?', ['71233']);
    echo "Total de registros encontrados: " . $result[0]->total . "\n";
    
    if ($result[0]->total > 0) {
        $data = DB::select('SELECT * FROM asentamientos WHERE codigo_postal = ? LIMIT 1', ['71233']);
        echo "Primer registro: " . json_encode($data[0], JSON_PRETTY_PRINT) . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 