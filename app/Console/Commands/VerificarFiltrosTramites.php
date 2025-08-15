<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class VerificarFiltrosTramites extends Command
{
    protected $signature = 'verificar:filtros-tramites';
    protected $description = 'Verificar que los filtros de trámites funcionan correctamente';

    public function handle()
    {
        $this->info("=== VERIFICACIÓN DE FILTROS DE TRÁMITES ===\n");

        // Estadísticas generales por tipo de trámite
        $tiposTramite = DB::select("
            SELECT 
                tipo_tramite,
                COUNT(*) as total_tramites,
                COUNT(DISTINCT proveedor_id) as proveedores_unicos
            FROM tramites 
            WHERE tipo_tramite IS NOT NULL
            GROUP BY tipo_tramite
            ORDER BY total_tramites DESC
        ");

        if (!empty($tiposTramite)) {
            $this->info("📊 ESTADÍSTICAS POR TIPO DE TRÁMITE:");
            $datosTipos = [];
            foreach ($tiposTramite as $tipo) {
                $datosTipos[] = [
                    'Tipo' => $tipo->tipo_tramite,
                    'Total Trámites' => $tipo->total_tramites,
                    'Proveedores' => $tipo->proveedores_unicos
                ];
            }
            $this->table(['Tipo', 'Total Trámites', 'Proveedores'], $datosTipos);
        }

        // Estadísticas por año
        $tramitesPorAño = DB::select("
            SELECT 
                YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) as año,
                COUNT(*) as total_tramites,
                COUNT(DISTINCT proveedor_id) as proveedores_unicos,
                COUNT(CASE WHEN tipo_tramite = 'Inscripcion' THEN 1 END) as inscripciones,
                COUNT(CASE WHEN tipo_tramite = 'Renovacion' THEN 1 END) as renovaciones,
                COUNT(CASE WHEN tipo_tramite = 'Actualizacion' THEN 1 END) as actualizaciones
            FROM tramites 
            WHERE COALESCE(fecha_finalizacion, fecha_inicio, created_at) IS NOT NULL
            GROUP BY YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at))
            ORDER BY año DESC
        ");

        if (!empty($tramitesPorAño)) {
            $this->info("\n📅 ESTADÍSTICAS POR AÑO:");
            $datosAños = [];
            foreach ($tramitesPorAño as $año) {
                $datosAños[] = [
                    'Año' => $año->año,
                    'Total' => $año->total_tramites,
                    'Proveedores' => $año->proveedores_unicos,
                    'Inscripciones' => $año->inscripciones,
                    'Renovaciones' => $año->renovaciones,
                    'Actualizaciones' => $año->actualizaciones
                ];
            }
            $this->table(['Año', 'Total', 'Proveedores', 'Inscr.', 'Renov.', 'Actual.'], $datosAños);
        }

        // Probar filtros específicos
        $this->info("\n🎯 PRUEBAS DE FILTROS ESPECÍFICOS:");

        // Filtro: Renovaciones en 2023
        $renovaciones2023 = Proveedor::whereHas('tramites', function($q) {
            $q->where('tipo_tramite', 'Renovacion')
              ->whereRaw('YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) = 2023');
        })->count();

        // Filtro: Inscripciones en 2022
        $inscripciones2022 = Proveedor::whereHas('tramites', function($q) {
            $q->where('tipo_tramite', 'Inscripcion')
              ->whereRaw('YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) = 2022');
        })->count();

        // Filtro: Actualizaciones en 2024
        $actualizaciones2024 = Proveedor::whereHas('tramites', function($q) {
            $q->where('tipo_tramite', 'Actualizacion')
              ->whereRaw('YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) = 2024');
        })->count();

        // Filtro: Solo tipo Renovacion (cualquier año)
        $soloRenovaciones = Proveedor::whereHas('tramites', function($q) {
            $q->where('tipo_tramite', 'Renovacion');
        })->count();

        // Filtro: Solo año 2023 (cualquier tipo)
        $solo2023 = Proveedor::whereHas('tramites', function($q) {
            $q->whereRaw('YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) = 2023');
        })->count();

        $this->table([
            'Filtro', 'Proveedores Encontrados'
        ], [
            ['🔄 Renovaciones en 2023', $renovaciones2023],
            ['📝 Inscripciones en 2022', $inscripciones2022],
            ['🔧 Actualizaciones en 2024', $actualizaciones2024],
            ['🔄 Solo Renovaciones (cualquier año)', $soloRenovaciones],
            ['📅 Solo 2023 (cualquier tipo)', $solo2023]
        ]);

        // Ejemplos específicos de cada filtro
        $this->info("\n💡 EJEMPLOS DE PROVEEDORES POR FILTRO:");

        // Ejemplos de renovaciones en 2023
        $ejemplosRenovaciones2023 = DB::select("
            SELECT DISTINCT
                p.id,
                p.razon_social,
                t.tipo_tramite,
                YEAR(COALESCE(t.fecha_finalizacion, t.fecha_inicio, t.created_at)) as año
            FROM proveedores p
            INNER JOIN tramites t ON p.id = t.proveedor_id
            WHERE t.tipo_tramite = 'Renovacion'
            AND YEAR(COALESCE(t.fecha_finalizacion, t.fecha_inicio, t.created_at)) = 2023
            LIMIT 5
        ");

        if (!empty($ejemplosRenovaciones2023)) {
            $this->info("🔄 Ejemplos de Renovaciones en 2023:");
            $datosEjemplos = [];
            foreach ($ejemplosRenovaciones2023 as $ejemplo) {
                $datosEjemplos[] = [
                    'ID' => $ejemplo->id,
                    'Razón Social' => substr($ejemplo->razon_social, 0, 40) . '...',
                    'Tipo' => $ejemplo->tipo_tramite,
                    'Año' => $ejemplo->año
                ];
            }
            $this->table(['ID', 'Razón Social', 'Tipo', 'Año'], $datosEjemplos);
        }

        // URLs de prueba
        $this->info("\n🌐 URLS DE PRUEBA PARA LOS FILTROS:");
        $baseUrl = 'http://127.0.0.1:8000/proveedores';
        
        $urls = [
            "🔄 Renovaciones en 2023: {$baseUrl}?tipo_tramite_año=Renovacion&año_especifico=2023",
            "📝 Inscripciones en 2022: {$baseUrl}?tipo_tramite_año=Inscripcion&año_especifico=2022",
            "🔧 Actualizaciones en 2024: {$baseUrl}?tipo_tramite_año=Actualizacion&año_especifico=2024",
            "🔄 Solo Renovaciones: {$baseUrl}?tipo_tramite_filtro=Renovacion",
            "📅 Solo 2023: {$baseUrl}?año_tramite=2023",
            "🔄 Renovadores constantes: {$baseUrl}?con_historial=renovadores"
        ];

        foreach ($urls as $url) {
            $this->line($url);
        }

        $this->info("\n✅ Verificación completada. Los filtros están listos para usar!");
    }
}