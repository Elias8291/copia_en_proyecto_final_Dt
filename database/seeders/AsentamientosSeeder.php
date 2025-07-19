<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AsentamientosSeeder extends Seeder
{
    public function run()
    {
        echo "Cargando asentamientos...\n";
        
        if (!$data = json_decode(File::get(database_path('json/asentamientos.json')), true)) {
            return $this->command->error('Error al cargar asentamientos.json');
        }

        // Verificar que existan localidades en la base de datos
        $localidadesCount = DB::table('localidades')->count();
        
        if ($localidadesCount === 0) {
            echo "Error: No hay localidades en la base de datos\n";
            return;
        }

        $insertedCount = 0;
        $totalAsentamientos = count($data['settlements'] ?? []);
        echo "Total de asentamientos a procesar: $totalAsentamientos\n";

        collect($data['settlements'] ?? [])->chunk(500)->each(function($chunk) use (&$insertedCount) {
            $asentamientos = $chunk->map(function($item) {
                // Verificar que el item tenga los campos necesarios
                if (!isset($item['name']) || !isset($item['zip_code']) || !isset($item['localidad_id']) || !isset($item['settlement_type_id'])) {
                    echo "Advertencia: Registro incompleto - " . json_encode($item) . "\n";
                    return null;
                }

                return [
                    'localidad_id' => $item['localidad_id'],
                    'nombre' => $item['name'],
                    'codigo_postal' => (string) $item['zip_code'],
                    'tipo_asentamiento' => $this->getTipoAsentamiento($item['settlement_type_id']),
                ];
            })->filter()->toArray();
            
            if (!empty($asentamientos)) {
                try {
                    DB::table('asentamientos')->insert($asentamientos);
                    $insertedCount += count($asentamientos);
                    echo "Insertados: $insertedCount asentamientos...\n";
                } catch (\Exception $e) {
                    echo "Error al insertar lote: " . $e->getMessage() . "\n";
                    // Intentar insertar uno por uno para identificar registros problemáticos
                    foreach ($asentamientos as $asentamiento) {
                        try {
                            DB::table('asentamientos')->insert([$asentamiento]);
                            $insertedCount++;
                        } catch (\Exception $e) {
                            echo "Error al insertar: " . json_encode($asentamiento) . " - " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
        });
        
        echo "Asentamientos cargados exitosamente: $insertedCount de $totalAsentamientos\n";
    }
    
    private function getTipoAsentamiento($settlementTypeId)
    {
        // Mapeo básico de tipos de asentamiento
        $tipos = [
            1 => 'Colonia',
            2 => 'Fraccionamiento', 
            3 => 'Barrio',
            4 => 'Pueblo',
            5 => 'Ejido',
            6 => 'Ranchería',
            7 => 'Zona Industrial',
            8 => 'Centro',
        ];
        
        return $tipos[$settlementTypeId] ?? 'Colonia';
    }
}