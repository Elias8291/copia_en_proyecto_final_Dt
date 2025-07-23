<?php
/**
 * Script de verificación para el sistema de actividades económicas
 * Ejecutar desde la raíz del proyecto: php verificar-actividades.php
 */

require_once 'vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICACIÓN DEL SISTEMA DE ACTIVIDADES ===\n\n";

try {
    // 1. Verificar que existan actividades económicas
    $totalActividades = App\Models\ActividadEconomica::count();
    echo "✅ Total actividades económicas: {$totalActividades}\n";
    
    if ($totalActividades === 0) {
        echo "⚠️  No hay actividades económicas. Creando algunas de ejemplo...\n";
        
        // Crear sector de ejemplo
        $sector = App\Models\Sector::firstOrCreate([
            'nombre' => 'Servicios Profesionales'
        ]);
        
        // Crear actividades de ejemplo
        $actividades = [
            'Consultoría en Tecnologías de la Información',
            'Desarrollo de Software',
            'Servicios de Contabilidad',
            'Servicios Legales',
            'Servicios de Marketing Digital'
        ];
        
        foreach ($actividades as $nombre) {
            App\Models\ActividadEconomica::create([
                'sector_id' => $sector->id,
                'nombre' => $nombre,
                'codigo_scian' => '541' . rand(100, 999),
                'descripcion' => "Descripción de {$nombre}",
                'estado_validacion' => 'Validada'
            ]);
        }
        
        echo "✅ Creadas " . count($actividades) . " actividades de ejemplo\n";
    }
    
    // 2. Verificar que la tabla pivot existe
    $pivotExists = Schema::hasTable('actividades');
    echo $pivotExists ? "✅ Tabla pivot 'actividades' existe\n" : "❌ Tabla pivot 'actividades' NO existe\n";
    
    // 3. Verificar relación en el modelo Tramite
    $tramite = new App\Models\Tramite();
    $hasRelation = method_exists($tramite, 'actividades');
    echo $hasRelation ? "✅ Relación actividades() existe en Tramite\n" : "❌ Relación actividades() NO existe en Tramite\n";
    
    // 4. Verificar controlador de búsqueda
    $controller = new App\Http\Controllers\ActividadesController();
    $hasMethod = method_exists($controller, 'buscador');
    echo $hasMethod ? "✅ Método buscador() existe en ActividadesController\n" : "❌ Método buscador() NO existe en ActividadesController\n";
    
    // 5. Mostrar algunas actividades de ejemplo
    echo "\n--- ACTIVIDADES DISPONIBLES ---\n";
    $actividades = App\Models\ActividadEconomica::where('estado_validacion', 'Validada')
                                                ->take(5)
                                                ->get(['id', 'nombre']);
    
    foreach ($actividades as $actividad) {
        echo "ID: {$actividad->id} - {$actividad->nombre}\n";
    }
    
    echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
    echo "✅ El sistema está listo para guardar actividades económicas\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
}