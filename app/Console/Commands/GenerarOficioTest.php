<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Support\Facades\Log;

class GenerarOficioTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oficio:generar-test {tramite_id=13}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar oficio de prueba para un trámite específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("Generando oficio de prueba para el trámite ID: {$tramiteId}");
        
        try {
            // Buscar el trámite
            $tramite = Tramite::with([
                'proveedor',
                'datosGenerales',
                'datosConstitutivos',
                'direcciones.estado',
                'apoderadosLegales',
                'actividades',
                'accionistas',
                'contactos'
            ])->find($tramiteId);
            
            if (!$tramite) {
                $this->error("❌ Trámite con ID {$tramiteId} no encontrado");
                return 1;
            }
            
            $this->info("✅ Trámite encontrado:");
            $this->line("   - ID: {$tramite->id}");
            $this->line("   - Tipo: {$tramite->tipo_tramite}");
            $this->line("   - Status: {$tramite->status}");
            
            if ($tramite->proveedor) {
                $this->line("   - Proveedor: {$tramite->proveedor->rfc} ({$tramite->proveedor->razon_social})");
                $this->line("   - PV: {$tramite->proveedor->pv_numero}");
            } else {
                $this->error("❌ El trámite no tiene proveedor asociado");
                return 1;
            }
            
            // Verificar si ya existe un oficio
            $oficioExistente = $tramite->oficios()->first();
            if ($oficioExistente) {
                $this->warn("⚠️  Ya existe un oficio para este trámite:");
                $this->line("   - Número: {$oficioExistente->numero_oficio}");
                $this->line("   - Fecha: {$oficioExistente->fecha_oficio}");
                $this->line("   - Estado: {$oficioExistente->estado}");
                
                if (!$this->confirm('¿Desea generar un nuevo oficio?', false)) {
                    $this->info("Operación cancelada");
                    return 0;
                }
            }
            
            // Generar el oficio
            $this->info("🔄 Generando oficio...");
            
            $oficioService = app(OficioService::class);
            $oficio = $oficioService->generarOficioParaTramite($tramite);
            
            $this->info("✅ Oficio generado exitosamente:");
            $this->line("   - ID: {$oficio->id}");
            $this->line("   - Número: {$oficio->numero_oficio}");
            $this->line("   - Fecha: {$oficio->fecha_oficio}");
            $this->line("   - Estado: {$oficio->estado}");
            $this->line("   - URL: {$oficio->url}");
            
            // Mostrar información del QR
            $this->info("📱 Información del QR generado:");
            $this->line("   - Contiene ID del proveedor: {$tramite->proveedor->id}");
            $this->line("   - URL de validación: " . route('oficios.validar', $tramite->id));
            $this->line("   - URL pública del proveedor: " . route('proveedores.publico', $tramite->proveedor->id));
            
            // Verificar datos del trámite
            $datosVerificados = $oficioService->verificarDatosTramite($tramite);
            $this->info("📊 Datos verificados del trámite:");
            $this->line("   - Datos generales: " . ($datosVerificados['datos_generales'] ? '✅' : '❌'));
            $this->line("   - Datos constitutivos: " . ($datosVerificados['datos_constitutivos'] ? '✅' : '❌'));
            $this->line("   - Direcciones: " . count($datosVerificados['direcciones']) . " registros");
            $this->line("   - Actividades: " . count($datosVerificados['actividades']) . " registros");
            $this->line("   - Apoderados: " . count($datosVerificados['apoderados_legales']) . " registros");
            
            $this->info("🎉 Oficio de prueba generado correctamente!");
            $this->line("Puede acceder a la vista pública en: " . route('proveedores.publico', $tramite->proveedor->id));
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar el oficio: " . $e->getMessage());
            Log::error('Error en comando GenerarOficioTest', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
