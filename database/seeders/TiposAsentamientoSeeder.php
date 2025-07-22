<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposAsentamientoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Cargando tipos de asentamiento...');

        $tiposAsentamiento = [
            'Colonia',
            'Fraccionamiento',
            'Condominio',
            'Unidad habitacional',
            'Barrio',
            'Equipamiento',
            'Zona comercial',
            'Rancho',
            'Ranchería',
            'Zona industrial',
            'Granja',
            'Pueblo',
            'Ejido',
            'Aeropuerto',
            'Paraje',
            'Hacienda',
            'Conjunto habitacional',
            'Zona militar',
            'Puerto',
            'Zona federal',
            'Exhacienda',
            'Finca',
            'Campamento',
            'Zona naval',
        ];

        $tipos = [];
        foreach ($tiposAsentamiento as $index => $nombre) {
            $tipos[] = [
                'id' => $index + 1,
                'nombre' => $nombre,
            ];
        }

        foreach ($tipos as $tipo) {
            DB::table('tipos_asentamiento')->updateOrInsert(
                ['id' => $tipo['id']],
                [
                    'nombre' => $tipo['nombre'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Tipos de asentamiento cargados exitosamente');
    }
}
