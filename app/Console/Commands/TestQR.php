<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;

class TestQR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:test {tramite_id : ID del trámite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar generación de QR code para un trámite';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $tramite = Tramite::with(['proveedor'])->find($tramiteId);
        
        if (!$tramite || !$tramite->proveedor) {
            $this->error("Trámite {$tramiteId} no encontrado o sin proveedor.");
            return 1;
        }
        
        $this->info("Probando generación de QR para trámite {$tramiteId}...");
        
        try {
            // Simular la generación del nombre de archivo
            $proveedor = $tramite->proveedor;
            $datosGenerales = $tramite->datosGenerales()->latest()->first();
            
            $nombreProveedor = '';
            if ($datosGenerales && $datosGenerales->razon_social) {
                $nombreProveedor = str_replace(' ', '_', strtoupper($datosGenerales->razon_social));
            } else {
                $nombreProveedor = 'PROVEEDOR_' . $proveedor->id;
            }
            
            $rfc = $proveedor->rfc;
            $fecha = now()->format('Y-m-d');
            
            $nombreArchivo = "Oficio_{$nombreProveedor}_{$rfc}_{$fecha}.pdf";
            
            // Generar URL de descarga
            $urlDescarga = route('oficios.descargar', [
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'archivo' => $nombreArchivo
            ]);
            
            $this->info("✅ QR Code generaría la siguiente URL:");
            $this->line($urlDescarga);
            
            // Probar generación real del QR
            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $qrCode = \Endroid\QrCode\QrCode::create($urlDescarga)
                ->setSize(150)
                ->setMargin(10);

            $result = $writer->write($qrCode);
            $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($result->getString());
            
            $this->info("✅ QR Code generado exitosamente!");
            $this->line("Tamaño: " . strlen($qrCodeBase64) . " caracteres");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar QR:");
            $this->error($e->getMessage());
            return 1;
        }
    }
}