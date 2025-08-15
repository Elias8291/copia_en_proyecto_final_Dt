<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;

class VerificarFiltroAñoAlta extends Command
{
    protected $signature = 'verificar:filtro-año-alta';
    protected $description = 'Verificar el filtro de año de alta del padrón';

    public function handle()
    {
        $this->info("=== VERIFICACIÓN FILTRO AÑO DE ALTA DEL PADRÓN ===\n");

        // Verificar cuántos proveedores tienen fecha_alta_padron
        $totalProveedores = Proveedor::count();
        $conFechaAlta = Proveedor::whereNotNull('fecha_alta_padron')->count();
        $sinFechaAlta = Proveedor::whereNull('fecha_alta_padron')->count();

        $this->table([
            'Estadística', 'Cantidad'
        ], [
            ['Total Proveedores', $totalProveedores],
            ['Con fecha_alta_padron', $conFechaAlta],
            ['Sin fecha_alta_padron', $sinFechaAlta],
            ['Porcentaje con fecha', round(($conFechaAlta / $totalProveedores) * 100, 1) . '%']
        ]);

        // Distribución por años
        $distribucionAños = DB::select("
            SELECT 
                YEAR(fecha_alta_padron) as año,
                COUNT(*) as total
            FROM proveedores 
            WHERE fecha_alta_padron IS NOT NULL
            GROUP BY YEAR(fecha_alta_padron)
            ORDER BY año DESC
        ");

        if (!empty($distribucionAños)) {
            $this->info("\n📅 DISTRIBUCIÓN POR AÑO DE ALTA:");
            $datosAños = [];
            foreach ($distribucionAños as $item) {
                $datosAños[] = [
                    'Año' => $item->año,
                    'Proveedores' => $item->total
                ];
            }
            $this->table(['Año', 'Proveedores'], $datosAños);
        }

        // Probar filtros específicos por año
        $añosPrueba = [2022, 2023, 2024];
        $this->info("\n🧪 PRUEBAS DE FILTRO POR AÑO:");
        
        foreach ($añosPrueba as $año) {
            $resultado = Proveedor::whereYear('fecha_alta_padron', $año)->count();
            $this->line("Año {$año}: {$resultado} proveedores");
        }

        // Mostrar ejemplos de proveedores por año
        $this->info("\n💡 EJEMPLOS DE PROVEEDORES POR AÑO:");
        
        foreach ($añosPrueba as $año) {
            $ejemplos = Proveedor::whereYear('fecha_alta_padron', $año)
                ->select('id', 'razon_social', 'fecha_alta_padron')
                ->limit(3)
                ->get();
                
            if ($ejemplos->count() > 0) {
                $this->info("\n📅 Proveedores dados de alta en {$año}:");
                $datosEjemplos = [];
                foreach ($ejemplos as $proveedor) {
                    $datosEjemplos[] = [
                        'ID' => $proveedor->id,
                        'Razón Social' => substr($proveedor->razon_social, 0, 40) . '...',
                        'Fecha Alta' => $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('d/m/Y') : 'N/A'
                    ];
                }
                $this->table(['ID', 'Razón Social', 'Fecha Alta'], $datosEjemplos);
            }
        }

        // URLs de prueba
        $this->info("\n🌐 URLS DE PRUEBA:");
        $baseUrl = 'http://127.0.0.1:8000/proveedores';
        
        foreach ($añosPrueba as $año) {
            $this->line("Proveedores alta {$año}: {$baseUrl}?año={$año}");
        }

        $this->info("\n✅ Verificación completada. Revisa si los números coinciden con lo que ves en la interfaz.");
    }
}