<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use Carbon\Carbon;
use App\Models\Notificacion;

class NotificarVencimientosProximos extends Command
{
    protected $signature = 'proveedores:notificar-vencimientos-proximos {--dias=7 : Días de anticipación para notificar} {--dry-run : Solo mostrar cuántos se notificarían}';

    public function handle()
    {
        $hoy = Carbon::today();
        $diasAnticipacion = (int) $this->option('dias');
        $fechaLimite = $hoy->copy()->addDays($diasAnticipacion);

        $query = Proveedor::query()
            ->whereNotNull('fecha_vencimiento_padron')
            ->whereDate('fecha_vencimiento_padron', '<=', $fechaLimite)
            ->whereDate('fecha_vencimiento_padron', '>', $hoy)
            ->where('estado_padron', 'Activo')
            ->with('usuario');

        if ($this->option('dry-run')) {
            $conteo = $query->count();
            $this->info((string) $conteo);
            return 0;
        }

        $proveedores = $query->get();

        if ($proveedores->isEmpty()) {
            $this->info('0');
            return 0;
        }

        foreach ($proveedores as $proveedor) {
            if ($proveedor->usuario) {
                $diasRestantes = $hoy->diffInDays($proveedor->fecha_vencimiento_padron);
                
                Notificacion::crear(
                    $proveedor->usuario->id,
                    'advertencia',
                    'Vencimiento próximo',
                    "Tu registro en el padrón vence en {$diasRestantes} días. Te recomendamos renovar tu trámite."
                );
                
                $this->info("Notificación enviada al usuario ID: {$proveedor->usuario->id} para proveedor ID: {$proveedor->id} - vence en {$diasRestantes} días");
            }
        }

        $this->info((string) $proveedores->count());
        return 0;
    }
}
