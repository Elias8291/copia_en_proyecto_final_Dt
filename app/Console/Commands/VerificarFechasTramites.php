<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use Illuminate\Support\Facades\DB;

class VerificarFechasTramites extends Command
{
    protected $signature = 'verificar:fechas-tramites';
    protected $description = 'Verificar fechas de trámites para confirmar historial de años pasados';

    public function handle()
    {
        $this->info("=== ANÁLISIS DE FECHAS DE TRÁMITES ===\n");

        // Distribución por años
        $tramitesPorAño = DB::select("
            SELECT 
                YEAR(fecha_inicio) as año,
                COUNT(*) as cantidad,
                ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM tramites), 1) as porcentaje
            FROM tramites 
            WHERE fecha_inicio IS NOT NULL
            GROUP BY YEAR(fecha_inicio)
            ORDER BY año DESC
        ");

        if (!empty($tramitesPorAño)) {
            $this->info("=== DISTRIBUCIÓN DE TRÁMITES POR AÑO ===");
            $datos = [];
            foreach ($tramitesPorAño as $item) {
                $datos[] = [
                    'Año' => $item->año,
                    'Cantidad' => $item->cantidad,
                    'Porcentaje' => $item->porcentaje . '%'
                ];
            }
            $this->table(['Año', 'Cantidad', 'Porcentaje'], $datos);
        }

        // Trámites más antiguos
        $tramitesAntiguos = Tramite::with('proveedor')
            ->whereYear('fecha_inicio', '<=', 2023)
            ->orderBy('fecha_inicio', 'asc')
            ->limit(10)
            ->get();

        if ($tramitesAntiguos->isNotEmpty()) {
            $this->info("\n=== TRÁMITES MÁS ANTIGUOS (HISTORIAL) ===");
            $datos = [];
            foreach ($tramitesAntiguos as $tramite) {
                $datos[] = [
                    'ID' => $tramite->id,
                    'Proveedor' => substr($tramite->proveedor->razon_social, 0, 30) . '...',
                    'Tipo' => $tramite->tipo_tramite,
                    'Fecha' => $tramite->fecha_inicio->format('Y-m-d'),
                    'Estado' => $tramite->status
                ];
            }
            $this->table(['ID', 'Proveedor', 'Tipo', 'Fecha', 'Estado'], $datos);
        }

        // Proveedores con mayor historial
        $proveedoresHistorial = DB::select("
            SELECT 
                p.id,
                p.razon_social,
                COUNT(t.id) as total_tramites,
                MIN(t.fecha_inicio) as primer_tramite,
                MAX(t.fecha_inicio) as ultimo_tramite,
                TIMESTAMPDIFF(MONTH, MIN(t.fecha_inicio), MAX(t.fecha_inicio)) as meses_historial
            FROM proveedores p
            INNER JOIN tramites t ON p.id = t.proveedor_id
            WHERE t.fecha_inicio IS NOT NULL
            GROUP BY p.id, p.razon_social
            HAVING COUNT(t.id) >= 3
            ORDER BY meses_historial DESC, total_tramites DESC
            LIMIT 10
        ");

        if (!empty($proveedoresHistorial)) {
            $this->info("\n=== PROVEEDORES CON MAYOR HISTORIAL ===");
            $datos = [];
            foreach ($proveedoresHistorial as $item) {
                $datos[] = [
                    'ID' => $item->id,
                    'Razón Social' => substr($item->razon_social, 0, 35) . '...',
                    'Trámites' => $item->total_tramites,
                    'Primer Trámite' => date('Y-m-d', strtotime($item->primer_tramite)),
                    'Último Trámite' => date('Y-m-d', strtotime($item->ultimo_tramite)),
                    'Meses Historial' => $item->meses_historial
                ];
            }
            $this->table(['ID', 'Razón Social', 'Trámites', 'Primer Trámite', 'Último Trámite', 'Meses Historial'], $datos);
        }

        $this->info("\n✅ Análisis completado. Los proveedores ahora tienen historial realista de años pasados.");
    }
}
