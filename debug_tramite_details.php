<?php

require_once 'vendor/autoload.php';

use App\Models\Proveedor;
use App\Models\Tramite;
use Illuminate\Support\Facades\Log;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG TRAMITE DETAILS ===\n";

try {
    // Buscar el proveedor específico
    $proveedor = Proveedor::where('rfc', 'APA210607A8A')->first();
    
    if (!$proveedor) {
        echo "❌ No se encontró el proveedor con RFC: APA210607A8A\n";
        exit;
    }
    
    echo "✅ Proveedor encontrado:\n";
    echo "   ID: {$proveedor->id}\n";
    echo "   RFC: {$proveedor->rfc}\n";
    echo "   Nombre: {$proveedor->user->nombre}\n";
    echo "   Email: {$proveedor->user->correo}\n";
    echo "   PV: {$proveedor->pv_numero}\n";
    echo "   Estado: {$proveedor->estado_padron}\n";
    
    // Buscar el último trámite aprobado
    $ultimoTramite = $proveedor->tramites()
        ->where('estado', 'Aprobado')
        ->with(['datosGenerales'])
        ->latest('created_at')
        ->first();
    
    if (!$ultimoTramite) {
        echo "\n❌ No se encontró trámite aprobado\n";
        exit;
    }
    
    echo "\n✅ Trámite encontrado:\n";
    echo "   ID: {$ultimoTramite->id}\n";
    echo "   Tipo: {$ultimoTramite->tipo_tramite}\n";
    echo "   Estado: {$ultimoTramite->estado}\n";
    echo "   Fecha: {$ultimoTramite->created_at}\n";
    
    // Verificar datos generales
    if ($ultimoTramite->datosGenerales) {
        echo "\n✅ Datos generales encontrados:\n";
        echo "   ID: {$ultimoTramite->datosGenerales->id}\n";
        echo "   Razón social: {$ultimoTramite->datosGenerales->razon_social}\n";
        echo "   RFC: {$ultimoTramite->datosGenerales->rfc}\n";
        echo "   CURP: {$ultimoTramite->datosGenerales->curp}\n";
    } else {
        echo "\n❌ No hay datos generales\n";
    }
    
    // Intentar cargar todas las relaciones correctas
    echo "\n=== CARGANDO RELACIONES CORRECTAS ===\n";
    
    $tramiteCompleto = $ultimoTramite->load([
        'datosGenerales',
        'apoderadoLegal',
        'direccion',
        'accionistas',
        'actividades',
        'datosConstitutivos',
        'archivos',
        'revisionSecciones'
    ]);
    
    echo "✅ Relaciones cargadas exitosamente\n";
    
    // Verificar cada relación
    $datosCompletos = [
        'tramite' => $tramiteCompleto,
        'datosGenerales' => $tramiteCompleto->datosGenerales,
        'apoderadoLegal' => $tramiteCompleto->apoderadoLegal,
        'direccion' => $tramiteCompleto->direccion,
        'accionistas' => $tramiteCompleto->accionistas,
        'actividadesEconomicas' => $tramiteCompleto->actividades,
        'constitucion' => $tramiteCompleto->datosConstitutivos,
        'documentos' => $tramiteCompleto->archivos,
        'estadoSeccion' => $tramiteCompleto->revisionSecciones
    ];
    
    echo "\n=== VERIFICANDO DATOS ===\n";
    foreach ($datosCompletos as $key => $value) {
        if ($key === 'tramite') {
            echo "✅ {$key}: " . ($value ? 'Presente' : 'Ausente') . "\n";
        } elseif (is_array($value) || $value instanceof \Illuminate\Database\Eloquent\Collection) {
            echo "✅ {$key}: " . ($value && count($value) > 0 ? count($value) . ' elementos' : 'Vacío') . "\n";
        } else {
            echo "✅ {$key}: " . ($value ? 'Presente' : 'Ausente') . "\n";
        }
    }
    
    echo "\n✅ Datos completos generados exitosamente\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} 