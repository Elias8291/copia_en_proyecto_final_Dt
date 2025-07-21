<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActividadEconomica;
use App\Models\Sector;

class ActividadesEconomicasSeeder extends Seeder
{
    public function run()
    {
        // Crear sectores primero si no existen
        $sectores = [
            ['nombre' => 'Construcción'],
            ['nombre' => 'Servicios Profesionales'],
            ['nombre' => 'Comercio'],
            ['nombre' => 'Tecnología'],
            ['nombre' => 'Manufactura'],
            ['nombre' => 'Otros'],
        ];

        foreach ($sectores as $sector) {
            Sector::firstOrCreate(['nombre' => $sector['nombre']]);
        }

        // Crear actividades económicas de ejemplo
        $actividades = [
            [
                'sector_id' => 1,
                'nombre' => 'Construcción de edificaciones residenciales',
                'codigo_scian' => '2361',
                'descripcion' => 'Construcción de casas y edificios residenciales',
                'estado_validacion' => 'Validada'
            ],
            [
                'sector_id' => 1,
                'nombre' => 'Construcción de obras de ingeniería civil',
                'codigo_scian' => '2371',
                'descripcion' => 'Construcción de carreteras, puentes y obras civiles',
                'estado_validacion' => 'Validada'
            ],
            [
                'sector_id' => 2,
                'nombre' => 'Servicios de consultoría en administración',
                'codigo_scian' => '5416',
                'descripcion' => 'Consultoría empresarial y administrativa',
                'estado_validacion' => 'Validada'
            ],
            [
                'sector_id' => 2,
                'nombre' => 'Servicios de contabilidad y auditoría',
                'codigo_scian' => '5412',
                'descripcion' => 'Servicios contables y de auditoría',
                'estado_validacion' => 'Validada'
            ],
            [
                'sector_id' => 3,
                'nombre' => 'Comercio al por menor de productos alimentarios',
                'codigo_scian' => '4621',
                'descripcion' => 'Venta al menudeo de alimentos y bebidas',
                'estado_validacion' => 'Validada'
            ],
            [
                'sector_id' => 4,
                'nombre' => 'Desarrollo de sistemas informáticos',
                'codigo_scian' => '5415',
                'descripcion' => 'Desarrollo de software y aplicaciones',
                'estado_validacion' => 'Validada'
            ],
            [
                'sector_id' => 5,
                'nombre' => 'Fabricación de productos metálicos',
                'codigo_scian' => '3323',
                'descripción' => 'Manufactura de productos de metal',
                'estado_validacion' => 'Validada'
            ],
        ];

        foreach ($actividades as $actividad) {
            ActividadEconomica::firstOrCreate(
                ['codigo_scian' => $actividad['codigo_scian']],
                $actividad
            );
        }
    }
}