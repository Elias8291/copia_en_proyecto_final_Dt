<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use Carbon\Carbon;
use App\Models\Notificacion;

class ActualizarProveedoresVencidos extends Command
{
    protected $signature = 'proveedores:actualizar-vencidos {--dry-run : Solo mostrar cuántos se actualizarían}';

    public function handle()
    {
        $hoy = Carbon::today();

        $query = Proveedor::query()
            ->whereNotNull('fecha_vencimiento_padron')
            ->whereDate('fecha_vencimiento_padron', '<', $hoy)
            ->where('estado_padron', '!=', 'Vencido');

        if ($this->option('dry-run')) {
            $conteo = $query->count();
            $this->info((string) $conteo);
            return 0;
        }

        $proveedores = $query->get(['id', 'usuario_id']);

        if ($proveedores->isEmpty()) {
            $this->info('0');
            return 0;
        }

        Proveedor::whereIn('id', $proveedores->pluck('id'))
            ->update(['estado_padron' => 'Vencido']);

        foreach ($proveedores as $proveedor) {
            if ($proveedor->usuario_id) {
                Notificacion::crear(
                    $proveedor->usuario_id,
                    'informativo',
                    'Estado de padrón: Vencido',
                    'Tu registro en el padrón ha pasado a estado Vencido por fecha de vencimiento.'
                );
            }
        }

        $this->info((string) $proveedores->count());
        return 0;
    }
}


