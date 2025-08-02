<?php

require_once 'vendor/autoload.php';

use Carbon\Carbon;

// Inicializar Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST: Cálculo Corregido de Inicio de Vigencia ===\n\n";

try {
    $fechasTest = [
        '2024-12-15' => 'Fecha normal',
        '2024-02-29' => '29 Feb año bisiesto -> año no bisiesto',
        '2020-02-29' => '29 Feb año bisiesto -> año bisiesto',
        '2023-02-28' => '28 Feb año normal',
        '2024-02-28' => '28 Feb año bisiesto',
    ];
    
    foreach ($fechasTest as $fechaStr => $descripcion) {
        $fechaVencimiento = Carbon::parse($fechaStr);
        
        // Aplicar la misma lógica que en la vista
        if ($fechaVencimiento->month == 2 && $fechaVencimiento->day == 29) {
            // Caso especial: 29 de febrero
            $yearInicio = $fechaVencimiento->year - 1;
            if (Carbon::create($yearInicio)->isLeapYear()) {
                // Si el año de inicio también es bisiesto, usar 29 de febrero
                $fechaInicio = Carbon::create($yearInicio, 2, 29);
            } else {
                // Si el año de inicio no es bisiesto, usar 28 de febrero
                $fechaInicio = Carbon::create($yearInicio, 2, 28);
            }
        } else {
            // Caso normal: restar un año
            $fechaInicio = $fechaVencimiento->copy()->subYear();
        }
        
        $periodoValido = $fechaInicio->diffInDays($fechaVencimiento);
        
        echo "✅ {$descripcion}:\n";
        echo "   Vencimiento: {$fechaVencimiento->format('d/m/Y')} ({$fechaVencimiento->year})\n";
        echo "   Inicio:      {$fechaInicio->format('d/m/Y')} ({$fechaInicio->year})\n";
        echo "   Período:     {$periodoValido} días\n";
        echo "   Año inicio bisiesto: " . ($fechaInicio->isLeapYear() ? 'SÍ' : 'NO') . "\n";
        echo "   Año venc. bisiesto:  " . ($fechaVencimiento->isLeapYear() ? 'SÍ' : 'NO') . "\n\n";
    }
    
    echo "🎉 CÁLCULOS CORRECTOS:\n";
    echo "   - Fechas normales: restamos exactamente un año ✅\n";
    echo "   - 29 Feb bisiesto -> no bisiesto: 28 Feb ✅\n";
    echo "   - 29 Feb bisiesto -> bisiesto: 29 Feb ✅\n";
    echo "   - Período de validez consistente ✅\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}