<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\ProveedoresController;

class ProbarFiltroAño extends Command
{
    protected $signature = 'probar:filtro-año {año}';
    protected $description = 'Probar el filtro de año simulando una request';

    public function handle()
    {
        $año = $this->argument('año');
        
        $this->info("=== PROBANDO FILTRO AÑO: {$año} ===\n");

        // Simular request
        $request = new Request(['año' => $año]);
        
        // Crear una instancia del controlador
        $controller = app(ProveedoresController::class);
        
        // Llamar al método index
        try {
            $response = $controller->index($request);
            
            if ($response instanceof \Illuminate\View\View) {
                $proveedores = $response->getData()['todosProveedores'];
                
                $this->info("✅ Filtro aplicado correctamente");
                $this->info("📊 Resultados encontrados: " . $proveedores->total());
                
                if ($proveedores->count() > 0) {
                    $this->info("\n💡 Primeros 5 resultados:");
                    $datos = [];
                    foreach ($proveedores->take(5) as $proveedor) {
                        $datos[] = [
                            'ID' => $proveedor->id,
                            'Razón Social' => substr($proveedor->razon_social, 0, 40) . '...',
                            'Fecha Alta' => $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('d/m/Y') : 'N/A'
                        ];
                    }
                    $this->table(['ID', 'Razón Social', 'Fecha Alta'], $datos);
                } else {
                    $this->warn("No se encontraron proveedores para el año {$año}");
                }
                
            } else {
                $this->error("Error: La respuesta no es una vista");
            }
            
        } catch (\Exception $e) {
            $this->error("Error al probar el filtro: " . $e->getMessage());
        }
        
        $this->info("\n🌐 URL de prueba: http://127.0.0.1:8000/proveedores?año={$año}");
    }
}