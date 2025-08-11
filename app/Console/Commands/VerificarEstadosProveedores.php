<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;

class VerificarEstadosProveedores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proveedores:verificar-estados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los estados de los proveedores en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando estados de proveedores...');
        
        // Obtener conteo por estado
        $estadosConteo = Proveedor::select('estado_padron')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('estado_padron')
            ->get();
        
        $this->table(['Estado', 'Cantidad'], $estadosConteo->map(function($item) {
            return [$item->estado_padron, $item->total];
        })->toArray());
        
        $total = Proveedor::count();
        $this->info("Total de proveedores: {$total}");
        
        // Mostrar algunos ejemplos
        $this->info("\nEjemplos de proveedores por estado:");
        
        foreach(['Activo', 'Inactivo', 'Vencido', 'Pendiente', 'Cancelado'] as $estado) {
            $ejemplo = Proveedor::where('estado_padron', $estado)->first();
            if ($ejemplo) {
                $this->line("- {$estado}: ID {$ejemplo->id}, RFC: {$ejemplo->rfc}, Razón Social: " . ($ejemplo->razon_social ?? 'N/A'));
            } else {
                $this->line("- {$estado}: Sin registros");
            }
        }
        
        return Command::SUCCESS;
    }
}
