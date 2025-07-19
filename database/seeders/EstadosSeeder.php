<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Cargando estados...');
        
        $estados = [
            'Aguascalientes',
            'Baja California',
            'Baja California Sur',
            'Campeche',
            'Chiapas',
            'Chihuahua',
            'Ciudad de México',
            'Coahuila',
            'Colima',
            'Durango',
            'Estado de México',
            'Guanajuato',
            'Guerrero',
            'Hidalgo',
            'Jalisco',
            'Michoacán',
            'Morelos',
            'Nayarit',
            'Nuevo León',
            'Oaxaca',
            'Puebla',
            'Querétaro',
            'Quintana Roo',
            'San Luis Potosí',
            'Sinaloa',
            'Sonora',
            'Tabasco',
            'Tamaulipas',
            'Tlaxcala',
            'Veracruz',
            'Yucatán',
            'Zacatecas',
        ];
        $mexicoId = DB::table('paises')->where('nombre', 'México')->first()->id ?? 1;

        $estadosData = array_map(function($nombre) use ($mexicoId) {
            return [
                'pais_id' => $mexicoId,
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $estados);
        
        DB::table('estados')->insert($estadosData);
        
        $this->command->info('Estados cargados exitosamente');
    }
}