<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proveedor;
use App\Models\User;
use Faker\Factory as Faker;

class GenerarProveedoresMasivos extends Command
{
    protected $signature = 'generar:proveedores {cantidad=3000}';
    protected $description = 'Genera proveedores masivos para testing';

    public function handle()
    {
        $cantidad = (int) $this->argument('cantidad');
        $faker = Faker::create('es_MX');
        
        $this->info("Generando {$cantidad} proveedores...");
        
        // Obtener el primer usuario disponible
        $usuario = User::first();
        if (!$usuario) {
            $this->error('No hay usuarios en la base de datos. Por favor, cree al menos un usuario primero.');
            return;
        }
        
        $bar = $this->output->createProgressBar($cantidad);
        $bar->start();
        
        $batch = [];
        $batchSize = 100; // Procesar en lotes de 100
        
        for ($i = 0; $i < $cantidad; $i++) {
            $tipoPersona = $faker->randomElement(['Física', 'Moral']);
            
            // Generar RFC válido según tipo de persona
            if ($tipoPersona === 'Física') {
                // RFC Persona Física: 4 letras + 6 números + 3 caracteres
                $rfc = strtoupper($faker->lexify('????')) . $faker->date('ymd') . $faker->lexify('??') . $faker->randomDigit();
            } else {
                // RFC Persona Moral: 3 letras + 6 números + 3 caracteres
                $rfc = strtoupper($faker->lexify('???')) . $faker->date('ymd') . $faker->lexify('??') . $faker->randomDigit();
            }
            
            $razonSocial = null;
            if ($tipoPersona === 'Moral') {
                $razonSocial = $faker->company() . ' ' . $faker->randomElement(['S.A. de C.V.', 'S. de R.L.', 'S.C.']);
            } else {
                $razonSocial = $faker->firstName() . ' ' . $faker->lastName() . ' ' . $faker->lastName();
            }
            
            $proveedor = [
                'usuario_id' => $usuario->id,
                'pv_numero' => 'PV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'rfc' => $rfc,
                'razon_social' => $razonSocial,
                'tipo_persona' => $tipoPersona,
                'estado_padron' => $faker->randomElement(['Activo', 'Inactivo', 'Vencido', 'Pendiente']),
                'fecha_alta_padron' => $faker->dateTimeBetween('-5 years', 'now'),
                'fecha_vencimiento_padron' => $faker->dateTimeBetween('now', '+2 years'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            $batch[] = $proveedor;
            
            // Insertar en lotes
            if (count($batch) >= $batchSize) {
                Proveedor::insert($batch);
                $batch = [];
            }
            
            $bar->advance();
        }
        
        // Insertar el último lote si queda algo
        if (!empty($batch)) {
            Proveedor::insert($batch);
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("✅ Se generaron {$cantidad} proveedores exitosamente!");
        
        // Mostrar estadísticas
        $totalProveedores = Proveedor::count();
        $fisicas = Proveedor::where('tipo_persona', 'Física')->count();
        $morales = Proveedor::where('tipo_persona', 'Moral')->count();
        $activos = Proveedor::where('estado_padron', 'Activo')->count();
        
        $this->table(
            ['Tipo', 'Cantidad'],
            [
                ['Total Proveedores', $totalProveedores],
                ['Personas Físicas', $fisicas],
                ['Personas Morales', $morales],
                ['Activos', $activos]
            ]
        );
    }
}
