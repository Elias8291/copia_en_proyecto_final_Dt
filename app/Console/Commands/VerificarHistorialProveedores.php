<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class VerificarHistorialProveedores extends Command
{
    protected $signature = 'verificar:historial-proveedores';
    protected $description = 'Verificar proveedores con historial de trámites';

    public function handle()
    {
        $this->info("=== ANÁLISIS DE HISTORIAL DE PROVEEDORES ===\n");

        // Estadísticas generales
        $totalProveedores = Proveedor::count();
        $conHistorial = Proveedor::has('tramites', '>=', 2)->count();
        $sinHistorial = Proveedor::has('tramites', '<=', 1)->count();
        $sinTramites = Proveedor::doesntHave('tramites')->count();

        $this->table([
            'Categoría', 'Cantidad', 'Porcentaje'
        ], [
            ['Total Proveedores', $totalProveedores, '100%'],
            ['Con Historial (2+ trámites)', $conHistorial, round(($conHistorial / max($totalProveedores, 1)) * 100, 1) . '%'],
            ['Nuevos (1 trámite o menos)', $sinHistorial, round(($sinHistorial / max($totalProveedores, 1)) * 100, 1) . '%'],
            ['Sin Trámites', $sinTramites, round(($sinTramites / max($totalProveedores, 1)) * 100, 1) . '%']
        ]);

        if ($conHistorial > 0) {
            $this->info("\n=== PROVEEDORES CON HISTORIAL ===");
            
            $proveedoresConHistorial = Proveedor::withCount('tramites')
                ->has('tramites', '>=', 2)
                ->orderBy('tramites_count', 'desc')
                ->limit(10)
                ->get();

            $datos = [];
            foreach ($proveedoresConHistorial as $proveedor) {
                $ultimoTramite = $proveedor->tramites()->latest()->first();
                $datos[] = [
                    'ID' => $proveedor->id,
                    'Razón Social' => strlen($proveedor->razon_social) > 40 
                        ? substr($proveedor->razon_social, 0, 37) . '...' 
                        : $proveedor->razon_social,
                    'Trámites' => $proveedor->tramites_count,
                    'Último Estado' => $ultimoTramite->status ?? 'N/A'
                ];
            }

            $this->table([
                'ID', 'Razón Social', 'Trámites', 'Último Estado'
            ], $datos);
        }

        // Mostrar distribución por número de trámites
        $distribucion = DB::select("
            SELECT 
                CASE 
                    WHEN tramites_count = 0 THEN '0 trámites'
                    WHEN tramites_count = 1 THEN '1 trámite'
                    WHEN tramites_count BETWEEN 2 AND 3 THEN '2-3 trámites'
                    WHEN tramites_count BETWEEN 4 AND 5 THEN '4-5 trámites'
                    ELSE '6+ trámites'
                END as rango,
                COUNT(*) as cantidad
            FROM (
                SELECT p.id, COUNT(t.id) as tramites_count
                FROM proveedores p
                LEFT JOIN tramites t ON p.id = t.proveedor_id
                GROUP BY p.id
            ) as subquery
            GROUP BY rango
            ORDER BY 
                CASE rango
                    WHEN '0 trámites' THEN 1
                    WHEN '1 trámite' THEN 2
                    WHEN '2-3 trámites' THEN 3
                    WHEN '4-5 trámites' THEN 4
                    ELSE 5
                END
        ");

        if (!empty($distribucion)) {
            $this->info("\n=== DISTRIBUCIÓN POR NÚMERO DE TRÁMITES ===");
            $datosDistribucion = [];
            foreach ($distribucion as $item) {
                $porcentaje = round(($item->cantidad / max($totalProveedores, 1)) * 100, 1);
                $datosDistribucion[] = [
                    'Rango' => $item->rango,
                    'Cantidad' => $item->cantidad,
                    'Porcentaje' => $porcentaje . '%'
                ];
            }
            $this->table(['Rango', 'Cantidad', 'Porcentaje'], $datosDistribucion);
        }

        $this->info("\n✅ Análisis completado. Usa el filtro 'Con historial' en la interfaz web para ver solo proveedores con múltiples trámites.");
    }
}
