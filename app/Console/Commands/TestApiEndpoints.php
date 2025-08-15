<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Api\ProveedorController;

class TestApiEndpoints extends Command
{
    protected $signature = 'test:api-endpoints';
    protected $description = 'Test API endpoints for sectors and activities';

    public function handle()
    {
        $this->info("=== PROBANDO ENDPOINTS API ===\n");

        try {
            $controller = new ProveedorController();
            
            // Probar sectores
            $this->info("🔍 Probando endpoint de sectores...");
            $sectoresResponse = $controller->getSectores();
            $sectoresData = json_decode($sectoresResponse->getContent(), true);
            
            if (is_array($sectoresData)) {
                $this->info("✅ Sectores: " . count($sectoresData) . " elementos encontrados");
                if (count($sectoresData) > 0) {
                    $this->info("   Primer sector: ID=" . $sectoresData[0]['id'] . ", Nombre=" . $sectoresData[0]['nombre']);
                }
            } else {
                $this->error("❌ Error en respuesta de sectores");
            }
            
            // Probar actividades
            $this->info("\n🔍 Probando endpoint de actividades...");
            $actividadesResponse = $controller->getActividades();
            $actividadesData = json_decode($actividadesResponse->getContent(), true);
            
            if (is_array($actividadesData)) {
                $this->info("✅ Actividades: " . count($actividadesData) . " elementos encontrados");
                if (count($actividadesData) > 0) {
                    $this->info("   Primera actividad: ID=" . $actividadesData[0]['id'] . ", Nombre=" . $actividadesData[0]['nombre']);
                }
            } else {
                $this->error("❌ Error en respuesta de actividades");
            }
            
            $this->info("\n🌐 URLs de prueba:");
            $this->line("Sectores: http://127.0.0.1:8000/api/sectores");
            $this->line("Actividades: http://127.0.0.1:8000/api/actividades");
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
}