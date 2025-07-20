<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SectoresSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Cargando sectores...');
        
        $jsonPath = database_path('json/sectores.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('Error: Archivo sectores.json no encontrado en database/json/');
            return;
        }
        
        $data = json_decode(File::get($jsonPath), true);
        
        if (!$data || !isset($data['Hoja1'])) {
            $this->command->error('Error: Archivo de sectores inválido o estructura incorrecta');
            return;
        }

        $sectores = collect($data['Hoja1'])->map(function ($item, $index) {
            return [
                'nombre' => $item['sector'],
                'codigo' => str_pad($index + 1, 2, '0', STR_PAD_LEFT), // Genera códigos 01, 02, 03, etc.
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::table('sectores')->insert($sectores);
        
        $this->command->info('Sectores cargados exitosamente: ' . count($sectores) . ' registros');
    }
}