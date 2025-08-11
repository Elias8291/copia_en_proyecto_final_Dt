<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Estado;

class VerificarEstadosGeograficos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proveedores:verificar-estados-geograficos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los estados geográficos asociados a proveedores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando estados geográficos...');
        
        // Verificar estados disponibles
        $estados = Estado::orderBy('nombre')->get();
        $this->info("Estados disponibles en la base de datos: {$estados->count()}");
        
        if ($estados->count() > 0) {
            $this->table(['ID', 'Nombre'], $estados->take(10)->map(function($estado) {
                return [$estado->id, $estado->nombre];
            })->toArray());
            
            if ($estados->count() > 10) {
                $this->info("... y " . ($estados->count() - 10) . " más");
            }
        }
        
        // Verificar proveedores con direcciones y estados
        $proveedoresConDirecciones = Proveedor::whereHas('tramites.direcciones.estado')
            ->with(['tramites.direcciones.estado'])
            ->get();
        
        $this->info("\nProveedores con direcciones y estados: {$proveedoresConDirecciones->count()}");
        
        if ($proveedoresConDirecciones->count() > 0) {
            $this->info("\nEjemplos de proveedores por estado:");
            
            $estadosConteo = [];
            foreach ($proveedoresConDirecciones as $proveedor) {
                foreach ($proveedor->tramites as $tramite) {
                    foreach ($tramite->direcciones as $direccion) {
                        if ($direccion->estado) {
                            $estadoNombre = $direccion->estado->nombre;
                            if (!isset($estadosConteo[$estadoNombre])) {
                                $estadosConteo[$estadoNombre] = 0;
                            }
                            $estadosConteo[$estadoNombre]++;
                        }
                    }
                }
            }
            
            arsort($estadosConteo);
            $tableData = [];
            foreach (array_slice($estadosConteo, 0, 10, true) as $estado => $count) {
                $tableData[] = [$estado, $count];
            }
            
            $this->table(['Estado', 'Proveedores'], $tableData);
        } else {
            $this->warn('No se encontraron proveedores con direcciones y estados asociados.');
            $this->info('Esto podría indicar que:');
            $this->info('1. No hay trámites con direcciones');
            $this->info('2. Las direcciones no tienen estados asignados');
            $this->info('3. Faltan datos de prueba');
        }
        
        return Command::SUCCESS;
    }
}
