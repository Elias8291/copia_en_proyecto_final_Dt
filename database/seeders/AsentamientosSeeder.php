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
                    'tipo_asentamiento_id' => $this->getTipoAsentamientoId($item['settlement_type_id']),
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
    
    private function getTipoAsentamientoId($settlementTypeId)
    {
        // Mapeo de tipos de asentamiento - devuelve el ID de la tabla tipos_asentamiento
        $tiposMap = [
            1 => 1,  // Colonia
            2 => 2,  // Fraccionamiento
            3 => 3,  // Condominio
            4 => 4,  // Unidad habitacional
            5 => 5,  // Barrio
            6 => 6,  // Equipamiento
            7 => 7,  // Zona comercial
            8 => 8,  // Rancho
            9 => 9,  // Ranchería
            10 => 10, // Zona industrial
            11 => 11, // Granja
            12 => 12, // Pueblo
            13 => 13, // Ejido
            14 => 14, // Aeropuerto
            15 => 15, // Paraje
            16 => 16, // Hacienda
            17 => 17, // Conjunto habitacional
            18 => 18, // Zona militar
            19 => 19, // Puerto
            20 => 20, // Zona federal
            21 => 21, // Exhacienda
            22 => 22, // Finca
            23 => 23, // Campamento
            24 => 24, // Zona naval
        ];
        
        return $tiposMap[$settlementTypeId] ?? 1; // Default to Colonia (ID 1)
    }
}