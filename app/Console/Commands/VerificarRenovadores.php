<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Tramite;
use Illuminate\Support\Facades\DB;

class VerificarRenovadores extends Command
{
    protected $signature = 'verificar:renovadores';
    protected $description = 'Verificar proveedores con patrón de renovación constante';

    public function handle()
    {
        $this->info("=== ANÁLISIS DE PROVEEDORES RENOVADORES CONSTANTES ===\n");

        // Proveedores que califican como renovadores constantes
        $renovadores = DB::select("
            SELECT 
                p.id,
                p.razon_social,
                p.rfc,
                COUNT(CASE WHEN t.tipo_tramite = 'Renovacion' THEN 1 END) as renovaciones,
                COUNT(CASE WHEN t.tipo_tramite = 'Inscripcion' THEN 1 END) as inscripciones,
                COUNT(CASE WHEN t.tipo_tramite = 'Actualizacion' THEN 1 END) as actualizaciones,
                COUNT(t.id) as total_tramites,
                MIN(t.fecha_inicio) as primer_tramite,
                MAX(t.fecha_inicio) as ultimo_tramite
            FROM proveedores p 
            INNER JOIN tramites t ON p.id = t.proveedor_id
            GROUP BY p.id, p.razon_social, p.rfc
            HAVING COUNT(CASE WHEN t.tipo_tramite = 'Renovacion' THEN 1 END) >= 2
            AND COUNT(CASE WHEN t.tipo_tramite = 'Inscripcion' THEN 1 END) >= 1
            ORDER BY renovaciones DESC, total_tramites DESC
        ");

        if (!empty($renovadores)) {
            $this->info("=== PROVEEDORES RENOVADORES CONSTANTES ===");
            $this->info("Criterio: Al menos 1 inscripción + 2 o más renovaciones\n");

            $datos = [];
            foreach ($renovadores as $renovador) {
                $datos[] = [
                    'ID' => $renovador->id,
                    'Razón Social' => substr($renovador->razon_social, 0, 35) . '...',
                    'Inscripciones' => $renovador->inscripciones,
                    'Renovaciones' => $renovador->renovaciones,
                    'Actualizaciones' => $renovador->actualizaciones,
                    'Total Trámites' => $renovador->total_tramites,
                    'Primer Trámite' => date('Y-m-d', strtotime($renovador->primer_tramite)),
                    'Último Trámite' => date('Y-m-d', strtotime($renovador->ultimo_tramite))
                ];
            }

            $this->table([
                'ID', 'Razón Social', 'Inscr.', 'Renov.', 'Actual.', 'Total', 'Primer Trámite', 'Último Trámite'
            ], $datos);

            $this->info("\nTotal de proveedores renovadores constantes: " . count($renovadores));
        } else {
            $this->warn("No se encontraron proveedores renovadores constantes.");
        }

        // Estadísticas por año de renovaciones
        $renovacionesPorAño = DB::select("
            SELECT 
                YEAR(fecha_inicio) as año,
                COUNT(*) as renovaciones
            FROM tramites 
            WHERE tipo_tramite = 'Renovacion'
            AND fecha_inicio IS NOT NULL
            GROUP BY YEAR(fecha_inicio)
            ORDER BY año DESC
        ");

        if (!empty($renovacionesPorAño)) {
            $this->info("\n=== RENOVACIONES POR AÑO ===");
            $datosAño = [];
            foreach ($renovacionesPorAño as $item) {
                $datosAño[] = [
                    'Año' => $item->año,
                    'Renovaciones' => $item->renovaciones
                ];
            }
            $this->table(['Año', 'Renovaciones'], $datosAño);
        }

        // Patrones de renovación más comunes
        $patrones = DB::select("
            SELECT 
                CONCAT(
                    COUNT(CASE WHEN t.tipo_tramite = 'Inscripcion' THEN 1 END), ' Inscr. + ',
                    COUNT(CASE WHEN t.tipo_tramite = 'Renovacion' THEN 1 END), ' Renov. + ',
                    COUNT(CASE WHEN t.tipo_tramite = 'Actualizacion' THEN 1 END), ' Actual.'
                ) as patron,
                COUNT(*) as proveedores
            FROM proveedores p 
            INNER JOIN tramites t ON p.id = t.proveedor_id
            GROUP BY p.id
            HAVING COUNT(CASE WHEN t.tipo_tramite = 'Renovacion' THEN 1 END) >= 1
        ");

        if (!empty($patrones)) {
            $patronesConteo = [];
            foreach ($patrones as $patron) {
                $key = $patron->patron;
                if (!isset($patronesConteo[$key])) {
                    $patronesConteo[$key] = 0;
                }
                $patronesConteo[$key] += $patron->proveedores;
            }

            arsort($patronesConteo);
            $patronesConteo = array_slice($patronesConteo, 0, 5, true);

            $this->info("\n=== PATRONES DE TRÁMITES MÁS COMUNES ===");
            $datosPatrones = [];
            foreach ($patronesConteo as $patron => $cantidad) {
                $datosPatrones[] = [
                    'Patrón' => $patron,
                    'Proveedores' => $cantidad
                ];
            }
            $this->table(['Patrón', 'Proveedores'], $datosPatrones);
        }

        $this->info("\n✅ Análisis completado. Usa el filtro '🔄 Renovadores constantes' para ver solo estos proveedores.");
    }
}
