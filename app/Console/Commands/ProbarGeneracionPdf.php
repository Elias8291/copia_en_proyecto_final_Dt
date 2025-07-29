<?php

namespace App\Console\Commands;

use App\Models\Oficio;
use App\Http\Controllers\OficioPdfController;
use Illuminate\Console\Command;

class ProbarGeneracionPdf extends Command
{
    protected $signature = 'probar:generar-pdf {oficio_id}';
    protected $description = 'Probar la generación del PDF del oficio';

    public function handle()
    {
        $oficioId = $this->argument('oficio_id');
        
        $this->info("=== PRUEBA DE GENERACIÓN DE PDF ===");
        $this->info("Oficio ID: {$oficioId}");
        
        $oficio = Oficio::find($oficioId);
        if (!$oficio) {
            $this->error("No se encontró oficio con ID: {$oficioId}");
            return;
        }

        try {
            $oficioService = app(\App\Services\OficioService::class);
            $controller = new OficioPdfController($oficioService);
            $response = $controller->generarPdf($oficio);
            
            if ($response) {
                $this->info("✅ PDF generado exitosamente");
                $this->info("URL: " . $response->getTargetUrl());
            } else {
                $this->error("❌ Error al generar PDF");
            }
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
} 