<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\Tramite;
use Illuminate\Support\Facades\DB;

class VerificarFechasAltaTramites extends Command
{
    protected $signature = 'verificar:fechas-alta-tramites';
    protected $description = 'Verificar que el primer trámite coincida con el año de fecha_alta_padron';

    public function handle()
    {
        $this->info("=== VERIFICACIÓN DE FECHAS DE ALTA VS PRIMER TRÁMITE ===\n");

        // Obtener proveedores con trámites y sus fechas
        $proveedores = DB::select("
            SELECT 
                p.id,
                p.razon_social,
                p.fecha_alta_padron,
                YEAR(p.fecha_alta_padron) as año_alta_padron,
                t.fecha_inicio as fecha_primer_tramite,
                COALESCE(t.fecha_finalizacion, t.fecha_inicio) as fecha_tramite_principal,
                YEAR(COALESCE(t.fecha_finalizacion, t.fecha_inicio)) as año_primer_tramite,
                t.tipo_tramite as tipo_primer_tramite,
                COUNT(t2.id) as total_tramites
            FROM proveedores p
            LEFT JOIN tramites t ON p.id = t.proveedor_id 
                AND t.id = (
                    SELECT MIN(t3.id) 
                    FROM tramites t3 
                    WHERE t3.proveedor_id = p.id
                )
            LEFT JOIN tramites t2 ON p.id = t2.proveedor_id
            WHERE p.fecha_alta_padron IS NOT NULL
            GROUP BY p.id, p.razon_social, p.fecha_alta_padron, t.fecha_inicio, t.fecha_finalizacion, t.tipo_tramite
            HAVING COUNT(t2.id) > 0
            ORDER BY p.fecha_alta_padron DESC
        ");

        if (empty($proveedores)) {
            $this->warn("No se encontraron proveedores con fecha_alta_padron y trámites.");
            return;
        }

        // Separar proveedores que coinciden vs los que no
        $coinciden = [];
        $noCoinciden = [];

        foreach ($proveedores as $proveedor) {
            if ($proveedor->año_alta_padron == $proveedor->año_primer_tramite) {
                $coinciden[] = $proveedor;
            } else {
                $noCoinciden[] = $proveedor;
            }
        }

        // Mostrar estadísticas generales
        $total = count($proveedores);
        $totalCoinciden = count($coinciden);
        $totalNoCoinciden = count($noCoinciden);
        $porcentajeCoinciden = $total > 0 ? round(($totalCoinciden / $total) * 100, 1) : 0;

        $this->table([
            'Estadística', 'Valor'
        ], [
            ['Total Proveedores con Trámites', $total],
            ['Fechas que Coinciden', $totalCoinciden . " ({$porcentajeCoinciden}%)"],
            ['Fechas que NO Coinciden', $totalNoCoinciden],
        ]);

        // Mostrar proveedores que SÍ coinciden (algunos ejemplos)
        if (!empty($coinciden)) {
            $this->info("\n✅ PROVEEDORES CON FECHAS COINCIDENTES (Primeros 10):");
            $ejemplosCoinciden = array_slice($coinciden, 0, 10);
            $datosCoinciden = [];
            
            foreach ($ejemplosCoinciden as $p) {
                $datosCoinciden[] = [
                    'ID' => $p->id,
                    'Razón Social' => substr($p->razon_social, 0, 30) . '...',
                    'Año Alta Padrón' => $p->año_alta_padron,
                    'Año Primer Trámite' => $p->año_primer_tramite,
                    'Tipo Trámite' => $p->tipo_primer_tramite,
                    'Total Trámites' => $p->total_tramites
                ];
            }
            
            $this->table([
                'ID', 'Razón Social', 'Año Alta', 'Año Trámite', 'Tipo', 'Total'
            ], $datosCoinciden);
        }

        // Mostrar proveedores que NO coinciden
        if (!empty($noCoinciden)) {
            $this->warn("\n❌ PROVEEDORES CON FECHAS QUE NO COINCIDEN:");
            $datosNoCoinciden = [];
            
            foreach ($noCoinciden as $p) {
                $diferencia = $p->año_primer_tramite - $p->año_alta_padron;
                $datosNoCoinciden[] = [
                    'ID' => $p->id,
                    'Razón Social' => substr($p->razon_social, 0, 25) . '...',
                    'Año Alta' => $p->año_alta_padron,
                    'Año Trámite' => $p->año_primer_tramite,
                    'Diferencia' => $diferencia > 0 ? "+{$diferencia}" : $diferencia,
                    'Total Trámites' => $p->total_tramites
                ];
            }
            
            $this->table([
                'ID', 'Razón Social', 'Año Alta', 'Año Trámite', 'Dif.', 'Total'
            ], $datosNoCoinciden);
        }

        // Distribución por años
        $distribucionAlta = DB::select("
            SELECT 
                YEAR(fecha_alta_padron) as año,
                COUNT(*) as proveedores_alta
            FROM proveedores 
            WHERE fecha_alta_padron IS NOT NULL
            GROUP BY YEAR(fecha_alta_padron)
            ORDER BY año DESC
        ");

        if (!empty($distribucionAlta)) {
            $this->info("\n📊 DISTRIBUCIÓN POR AÑO DE ALTA EN PADRÓN:");
            $datosDistribucion = [];
            foreach ($distribucionAlta as $item) {
                $datosDistribucion[] = [
                    'Año' => $item->año,
                    'Proveedores' => $item->proveedores_alta
                ];
            }
            $this->table(['Año', 'Proveedores'], $datosDistribucion);
        }

        if ($totalCoinciden > 0) {
            $this->info("\n✅ Verificación completada. {$totalCoinciden} de {$total} proveedores tienen su primer trámite en el año correcto.");
        } else {
            $this->error("\n❌ Ningún proveedor tiene su primer trámite en el año de fecha_alta_padron.");
        }
    }
}