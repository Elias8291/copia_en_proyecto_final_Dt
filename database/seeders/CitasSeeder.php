<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;

class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos reales de las tablas relacionadas
        $tramites = Tramite::take(5)->get();
        $users = User::take(5)->get();

        if ($tramites->isEmpty() || $users->isEmpty()) {
            $this->command->info('No hay suficientes datos en las tablas relacionadas para crear citas.');
            return;
        }

        $tiposCita = ['Revision', 'Cotejo', 'Entrega', 'Consulta', 'Otro', 'Reunion', 'Administrativa'];
        $estados = ['Programada', 'Confirmada', 'Cancelada', 'Reagendada', 'Completada'];

        // Crear 10 citas de ejemplo
        for ($i = 0; $i < 10; $i++) {
            $fechaCita = Carbon::now()->addDays(rand(1, 30))->addHours(rand(9, 17));
            
            Cita::create([
                'tramite_id' => $tramites->random()->id,
                'user_id' => $users->random()->id,
                'fecha_cita' => $fechaCita,
                'tipo_cita' => $tiposCita[array_rand($tiposCita)],
                'estado' => $estados[array_rand($estados)],
                'atendido_por' => $users->random()->id,
                'observaciones' => 'Observación de ejemplo para la cita #' . ($i + 1),
                'motivo' => 'Motivo de ejemplo para la cita #' . ($i + 1),
            ]);
        }

        $this->command->info('Se han creado 10 citas de ejemplo exitosamente.');
    }
}
