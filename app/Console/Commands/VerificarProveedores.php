<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;

class VerificarProveedores extends Command
{
    protected $signature = 'verificar:proveedores';
    protected $description = 'Verificar proveedores generados';

    public function handle()
    {
        $total = Proveedor::count();
        $sinUsuario = Proveedor::whereNull('usuario_id')->count();
        $conUsuario = Proveedor::whereNotNull('usuario_id')->count();
        
        $this->info("=== ESTADÍSTICAS DE PROVEEDORES ===");
        $this->info("Total de proveedores: {$total}");
        $this->info("Sin usuario asociado: {$sinUsuario}");
        $this->info("Con usuario asociado: {$conUsuario}");
        
        $ultimosSinUsuario = Proveedor::whereNull('usuario_id')
            ->latest()
            ->limit(5)
            ->get(['id', 'razon_social', 'created_at']);
            
        if ($ultimosSinUsuario->isNotEmpty()) {
            $this->info("\n=== ÚLTIMOS 5 PROVEEDORES SIN USUARIO ===");
            foreach ($ultimosSinUsuario as $proveedor) {
                $this->info("ID: {$proveedor->id} - {$proveedor->razon_social} - {$proveedor->created_at}");
            }
        }
    }
}
