<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MunicipioSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cargando municipios...');

        $jsonPath = database_path('json/municipios.json');
        if (! File::exists($jsonPath)) {
            $this->command->error('El archivo municipios.json no existe');

            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        if (! $data) {
            $this->command->error('Error al decodificar municipios.json');

            return;
        }

        try {
            if (isset($data['Sheet1'])) {
                $municipios = collect($data['Sheet1'])->map(function ($item) {
                    return [
                        'estado_id' => $item['state_id'],
                        'nombre' => $item['name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
            } else {
                $municipios = collect($data)->map(function ($item) {
                    return [
                        'estado_id' => $item['state_id'] ?? $item['estado_id'] ?? 1,
                        'nombre' => $item['name'] ?? $item['nombre'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
            }

            // Insertar en lotes para mejorar rendimiento
            $chunks = array_chunk($municipios, 500);
            foreach ($chunks as $chunk) {
                DB::table('municipios')->insert($chunk);
            }

            $this->command->info('Municipios cargados exitosamente');
        } catch (\Exception $e) {
            $this->command->error('Error al cargar municipios: '.$e->getMessage());
        }
    }
}
