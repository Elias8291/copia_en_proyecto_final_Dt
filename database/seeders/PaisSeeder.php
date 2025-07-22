<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cargando países...');

        $paises = [
            'México',
            'Estados Unidos',
            'Canadá',
            'España',
            'Argentina',
            'Colombia',
            'Chile',
            'Perú',
            'Brasil',
            'Venezuela',
            'Ecuador',
            'Bolivia',
            'Paraguay',
            'Uruguay',
            'Costa Rica',
            'Panamá',
            'Guatemala',
            'Honduras',
            'El Salvador',
            'Nicaragua',
            'República Dominicana',
            'Cuba',
            'Puerto Rico',
        ];

        $paisesData = array_map(function ($nombre) {
            return [
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $paises);

        DB::table('paises')->insert($paisesData);

        $this->command->info('Países cargados exitosamente');
    }
}
