<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use App\Http\Controllers\OficioMpdfController;
use Illuminate\Console\Command;

class ProbarMpdf extends Command
{
    protected $signature = 'probar:mpdf {oficio_id}';
    protected $description = 'Probar la generación del PDF con mPDF';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== PRUEBA DE GENERACIÓN CON MPDF ===");
        $this->info("Oficio ID: {$oficioId}");
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("No se encontró oficio con ID: {$oficioId}");
            return;
        }

        try {
            $oficioService = app(\App\Services\OficioService::class);
            $controller = new OficioMpdfController($oficioService);
            $response = $controller->generarPdf($oficio);
            
            if ($response) {
                $this->info("✅ PDF generado exitosamente con mPDF");
                $this->info("URL: " . $response->getTargetUrl());
                $this->info("✅ Generación completada sin verificar tamaño");
            } else {
                $this->error("❌ Error al generar PDF");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
} 