<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DiaInhabil;
use Carbon\Carbon;

class DiasInhabilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diasInhabiles = [
            // Días festivos oficiales de México 2025
            [
                'descripcion' => 'Año Nuevo',
                'fecha_inicio' => '2025-01-01',
                'fecha_fin' => '2025-01-01',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
            [
                'descripcion' => 'Día de la Constitución',
                'fecha_inicio' => '2025-02-03',
                'fecha_fin' => '2025-02-03',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial (primer lunes de febrero)'
            ],
            [
                'descripcion' => 'Natalicio de Benito Juárez',
                'fecha_inicio' => '2025-03-17',
                'fecha_fin' => '2025-03-17',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial (tercer lunes de marzo)'
            ],
            [
                'descripcion' => 'Día del Trabajo',
                'fecha_inicio' => '2025-05-01',
                'fecha_fin' => '2025-05-01',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
            [
                'descripcion' => 'Día de la Independencia',
                'fecha_inicio' => '2025-09-16',
                'fecha_fin' => '2025-09-16',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
            [
                'descripcion' => 'Día de la Revolución',
                'fecha_inicio' => '2025-11-17',
                'fecha_fin' => '2025-11-17',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial (tercer lunes de noviembre)'
            ],
            [
                'descripcion' => 'Navidad',
                'fecha_inicio' => '2025-12-25',
                'fecha_fin' => '2025-12-25',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],

            // Días festivos adicionales comunes
            [
                'descripcion' => 'Viernes Santo',
                'fecha_inicio' => '2025-04-18',
                'fecha_fin' => '2025-04-18',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo religioso'
            ],
            [
                'descripcion' => 'Día de los Muertos',
                'fecha_inicio' => '2025-11-02',
                'fecha_fin' => '2025-11-02',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo cultural'
            ],

            // Vacaciones de verano (ejemplo)
            [
                'descripcion' => 'Vacaciones de Verano',
                'fecha_inicio' => '2025-07-14',
                'fecha_fin' => '2025-07-25',
                'tipo' => 'Vacaciones',
                'observaciones' => 'Vacaciones de verano del personal'
            ],

            // Vacaciones de invierno (ejemplo)
            [
                'descripcion' => 'Vacaciones de Invierno',
                'fecha_inicio' => '2025-12-22',
                'fecha_fin' => '2025-12-31',
                'tipo' => 'Vacaciones',
                'observaciones' => 'Vacaciones de fin de año del personal'
            ],

            // Días festivos 2026 (para continuidad)
            [
                'descripcion' => 'Año Nuevo 2026',
                'fecha_inicio' => '2026-01-01',
                'fecha_fin' => '2026-01-01',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
            [
                'descripcion' => 'Día de la Constitución 2026',
                'fecha_inicio' => '2026-02-02',
                'fecha_fin' => '2026-02-02',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial (primer lunes de febrero)'
            ],
            [
                'descripcion' => 'Natalicio de Benito Juárez 2026',
                'fecha_inicio' => '2026-03-16',
                'fecha_fin' => '2026-03-16',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial (tercer lunes de marzo)'
            ],
            [
                'descripcion' => 'Día del Trabajo 2026',
                'fecha_inicio' => '2026-05-01',
                'fecha_fin' => '2026-05-01',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
            [
                'descripcion' => 'Día de la Independencia 2026',
                'fecha_inicio' => '2026-09-16',
                'fecha_fin' => '2026-09-16',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
            [
                'descripcion' => 'Día de la Revolución 2026',
                'fecha_inicio' => '2026-11-16',
                'fecha_fin' => '2026-11-16',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial (tercer lunes de noviembre)'
            ],
            [
                'descripcion' => 'Navidad 2026',
                'fecha_inicio' => '2026-12-25',
                'fecha_fin' => '2026-12-25',
                'tipo' => 'Feriado',
                'observaciones' => 'Día festivo oficial'
            ],
        ];

        foreach ($diasInhabiles as $dia) {
            DiaInhabil::create($dia);
        }

        $this->command->info('Se han creado los días inhábiles de México para 2025-2026 exitosamente.');
    }
} 