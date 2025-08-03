<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Sector;
use Illuminate\Database\Seeder;

class ActividadesEconomicasSeeder extends Seeder
{
    public function run()
    {
        // Crear sectores primero si no existen
        $sectores = [
            ['id' => 1, 'nombre' => 'Agricultura, ganadería, aprovechamiento forestal, pesca y caza'],
            ['id' => 2, 'nombre' => 'Minería'],
            ['id' => 3, 'nombre' => 'Generación, transmisión y distribución de energía eléctrica, suministro de agua y de gas por ductos al consumidor final'],
            ['id' => 4, 'nombre' => 'Construcción'],
            ['id' => 5, 'nombre' => 'Industrias manufactureras'],
            ['id' => 6, 'nombre' => 'Comercio al por mayor'],
            ['id' => 7, 'nombre' => 'Comercio al por menor'],
            ['id' => 8, 'nombre' => 'Transportes, correos y almacenamiento'],
            ['id' => 9, 'nombre' => 'Información en medios masivos'],
            ['id' => 10, 'nombre' => 'Servicios financieros y de seguros'],
            ['id' => 11, 'nombre' => 'Servicios inmobiliarios y de alquiler de bienes muebles e intangibles'],
            ['id' => 12, 'nombre' => 'Servicios profesionales, científicos y técnicos'],
            ['id' => 13, 'nombre' => 'Corporativos'],
            ['id' => 14, 'nombre' => 'Servicios de apoyo a los negocios y manejo de desechos y servicios de remediación'],
            ['id' => 15, 'nombre' => 'Servicios educativos'],
            ['id' => 16, 'nombre' => 'Servicios de salud y de asistencia social'],
            ['id' => 17, 'nombre' => 'Servicios de esparcimiento culturales y deportivos, y otros servicios recreativos'],
            ['id' => 18, 'nombre' => 'Servicios de alojamiento temporal y de preparación de alimentos y bebidas'],
            ['id' => 19, 'nombre' => 'Otros servicios excepto actividades gubernamentales'],
            ['id' => 20, 'nombre' => 'Actividades gubernamentales y de organismos internacionales'],
        ];

        foreach ($sectores as $sector) {
            Sector::firstOrCreate(
                ['id' => $sector['id']],
                [
                    'nombre' => $sector['nombre'],
                    'descripcion' => $sector['nombre']
                ]
            );
        }

        // Cargar actividades desde el archivo JSON
        $jsonPath = database_path('json/actividades.json');
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            
            if (isset($jsonData['Hoja1'])) {
                foreach ($jsonData['Hoja1'] as $actividadData) {
                    Actividad::firstOrCreate(
                        ['nombre' => $actividadData['actividad']],
                        [
                            'sector_id' => $actividadData['id_sector'],
                            'nombre' => $actividadData['actividad'],
                            'descripcion' => $actividadData['actividad']
                        ]
                    );
                }
                
                $this->command->info('Se importaron ' . count($jsonData['Hoja1']) . ' actividades económicas desde el archivo JSON.');
            } else {
                $this->command->error('No se encontró la clave "Hoja1" en el archivo JSON.');
            }
        } else {
            $this->command->error('No se encontró el archivo actividades.json en database/json/');
        }
    }
}
