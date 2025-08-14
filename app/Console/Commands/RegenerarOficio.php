<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\OficioService;

class RegenerarOficio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oficio:regenerar {tramite_id : ID del trámite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerar oficio con QR code para un trámite específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $this->info("Buscando trámite {$tramiteId}...");
        
        $tramite = Tramite::with(['proveedor'])->find($tramiteId);
        
        if (!$tramite) {
            $this->error("Trámite {$tramiteId} no encontrado.");
            return 1;
        }
        
        if (!$tramite->proveedor) {
            $this->error("El trámite {$tramiteId} no tiene proveedor asignado.");
            return 1;
        }
        
        $this->info("Trámite encontrado:");
        $this->line("- ID: {$tramite->id}");
        $this->line("- RFC: {$tramite->proveedor->rfc}");
        $this->line("- PV: " . ($tramite->proveedor->pv_numero ?? 'Sin PV'));
        $this->line("- Estado: {$tramite->status}");
        
        try {
            $this->info("Generando oficio con QR code...");
            
            $oficioService = app(OficioService::class);
            $oficio = $oficioService->generarOficioParaTramite($tramite);
            
            // Generar URL pública del proveedor con token seguro (donde apunta el QR)
            $tokenSeguro = $tramite->proveedor->obtenerTokenPublico();
            $urlPublicaQR = route('proveedores.publico.token', $tokenSeguro);
            $urlPublicaQR = str_replace('http://localhost', 'http://127.0.0.1:8000', $urlPublicaQR);
            
            $this->info("✅ Oficio regenerado exitosamente:");
            $this->line("- Número de oficio: {$oficio->numero_oficio}");
            $this->line("- ID del oficio: {$oficio->id}");
            $this->line("- URL de descarga: {$oficio->url}");
            
            // Mostrar URL del QR Code
            $this->info("🔗 QR Code apunta a: {$urlPublicaQR}");
            
            // Verificar archivos generados
            $oficiosDir = storage_path('app/public/oficios');
            if (is_dir($oficiosDir)) {
                $archivos = glob($oficiosDir . '/*.pdf');
                $this->info("📁 Archivos PDF en directorio oficios: " . count($archivos));
                foreach ($archivos as $archivo) {
                    $this->line("  - " . basename($archivo));
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar oficio:");
            $this->error($e->getMessage());
            
            if ($this->option('verbose')) {
                $this->error($e->getTraceAsString());
            }
            
            return 1;
        }
    }
}