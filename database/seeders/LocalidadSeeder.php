<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, File};

class LocalidadSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cargando localidades...');

        $jsonPath = database_path('json/localidades.json');
        if (!File::exists($jsonPath)) {
            $this->command->error('El archivo localidades.json no existe');
            return;
        }

        $data = json_decode(File::get($jsonPath), true);
        if (!$data) {
            $this->command->error('Error al decodificar localidades.json');
            return;
        }

        try {
            if (isset($data['Sheet1'])) {
                $localidades = collect($data['Sheet1'])->map(function($item) {
                    return [
                        'municipio_id' => $item['municipality_id'],
                        'nombre' => $item['name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
            } else {
                $localidades = collect($data)->map(function($item) {
                    return [
                        'municipio_id' => $item['municipality_id'] ?? $item['municipio_id'] ?? 1,
                        'nombre' => $item['name'] ?? $item['nombre'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();
            }
            
            // Insertar en lotes para mejorar rendimiento
            $chunks = array_chunk($localidades, 500);
            foreach ($chunks as $chunk) {
                DB::table('localidades')->insert($chunk);
            }
            
            $this->command->info('Localidades cargadas exitosamente');
        } catch (\Exception $e) {
            $this->command->error('Error al cargar localidades: ' . $e->getMessage());
        }
    }
}
