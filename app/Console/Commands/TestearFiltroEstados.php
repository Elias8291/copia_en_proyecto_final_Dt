<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ProveedoresController;
use Illuminate\Http\Request;

class TestearFiltroEstados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proveedores:testear-filtro-estados {estado_ids?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testea el filtro de estados geográficos con IDs específicos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $estadoIds = $this->argument('estado_ids');
        
        if (empty($estadoIds)) {
            $estadoIds = ['1', '2']; // IDs de ejemplo
            $this->info('Usando IDs de ejemplo: ' . implode(', ', $estadoIds));
        }
        
        $this->info('Testeando filtro de estados geográficos...');
        
        // Simular request
        $request = new Request();
        $request->merge([
            'estado_geografico' => $estadoIds,
            'search' => '',
            'estado' => '',
            'per_page' => 15
        ]);
        
        $this->info('Parámetros de prueba:');
        $this->info('- estado_geografico: ' . json_encode($estadoIds));
        
        try {
            $controller = new ProveedoresController();
            
            // Capturar logs
            \Log::info('=== INICIO TEST FILTRO ESTADOS ===');
            
            $response = $controller->index($request);
            
            $this->info('✅ Filtro ejecutado sin errores');
            $this->info('Revisa los logs en storage/logs/laravel.log para ver detalles');
            
        } catch (\Exception $e) {
            $this->error('❌ Error al ejecutar filtro:');
            $this->error($e->getMessage());
        }
        
        return Command::SUCCESS;
    }
}
