<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiaInhabil;
use Carbon\Carbon;

class DiasInhabilesSeeder extends Seeder
{
    public function run(): void
    {
        // Días inhábiles de ejemplo
        $diasInhabiles = [
            [
                'descripcion' => 'Día de la Independencia',
                'fecha_inicio' => '2024-09-16',
                'fecha_fin' => '2024-09-16',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo nacional',
                'activo' => true
            ],
            [
                'descripcion' => 'Día de la Revolución',
                'fecha_inicio' => '2024-11-20',
                'fecha_fin' => '2024-11-20',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo nacional',
                'activo' => true
            ],
            [
                'descripcion' => 'Navidad',
                'fecha_inicio' => '2024-12-25',
                'fecha_fin' => '2024-12-25',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo nacional',
                'activo' => true
            ]
        ];

        foreach ($diasInhabiles as $diaInhabil) {
            DiaInhabil::create($diaInhabil);
        }
    }
} 