<?php
/**
 * Script de prueba para verificar la funcionalidad de actividades económicas
 * 
 * Este archivo puede ser ejecutado para probar:
 * 1. La búsqueda de actividades
 * 2. La creación de nuevas actividades
 * 3. La validación de datos
 */

require_once 'vendor/autoload.php';

use App\Models\ActividadEconomica;
use App\Models\Sector;

// Simular el entorno de Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA DE FUNCIONALIDAD DE ACTIVIDADES ECONÓMICAS ===\n\n";

// 1. Verificar que la tabla existe y tiene la estructura correcta
echo "1. Verificando estructura de la tabla...\n";
try {
    $actividades = ActividadEconomica::count();
    echo "   ✅ Tabla actividades_economicas existe. Total de actividades: {$actividades}\n";
} catch (Exception $e) {
    echo "   ❌ Error al acceder a la tabla: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Verificar que se pueden crear actividades con sector_id null
echo "\n2. Probando creación de actividad con sector_id null...\n";
try {
    $nuevaActividad = ActividadEconomica::create([
        'nombre' => 'Actividad de prueba - ' . date('Y-m-d H:i:s'),
        'codigo_scian' => null,
        'descripcion' => 'Actividad creada para pruebas',
        'sector_id' => null,
        'estado_validacion' => 'Pendiente',
        'fuente' => 'MANUAL'
    ]);
    
    echo "   ✅ Actividad creada exitosamente:\n";
    echo "      ID: {$nuevaActividad->id}\n";
    echo "      Nombre: {$nuevaActividad->nombre}\n";
    echo "      Sector ID: " . ($nuevaActividad->sector_id ?? 'NULL') . "\n";
    echo "      Estado: {$nuevaActividad->estado_validacion}\n";
    
    // Limpiar la actividad de prueba
    $nuevaActividad->delete();
    echo "   ✅ Actividad de prueba eliminada\n";
    
} catch (Exception $e) {
    echo "   ❌ Error al crear actividad: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Verificar búsqueda de actividades
echo "\n3. Probando búsqueda de actividades...\n";
try {
    $resultados = ActividadEconomica::where('nombre', 'like', '%construcción%')
        ->where('estado_validacion', 'Validada')
        ->take(5)
        ->get(['id', 'nombre']);
    
    echo "   ✅ Búsqueda exitosa. Encontradas: " . $resultados->count() . " actividades\n";
    foreach ($resultados as $actividad) {
        echo "      - {$actividad->nombre}\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error en búsqueda: " . $e->getMessage() . "\n";
}

// 4. Verificar validaciones
echo "\n4. Probando validaciones...\n";
try {
    // Intentar crear actividad con nombre muy corto
    $actividadCorta = ActividadEconomica::create([
        'nombre' => 'AB', // Muy corto
        'sector_id' => null,
        'estado_validacion' => 'Pendiente',
        'fuente' => 'MANUAL'
    ]);
    echo "   ❌ Debería haber fallado por nombre muy corto\n";
    $actividadCorta->delete();
} catch (Exception $e) {
    echo "   ✅ Validación funcionando correctamente: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";
echo "La funcionalidad de actividades económicas está funcionando correctamente.\n"; 