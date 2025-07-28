<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\User;

class CreateTestTramite extends Command
{
    protected $signature = 'create:test-tramite';
    protected $description = 'Crear un trámite de prueba para testing';

    public function handle()
    {
        // Crear usuario de prueba si no existe
        $user = User::firstOrCreate(
            ['correo' => 'test@example.com'],
            [
                'nombre' => 'Usuario Test',
                'correo' => 'test@example.com',
                'password' => bcrypt('password'),
                'rfc' => 'TEST123456789',
                'verification' => 1
            ]
        );

        // Crear proveedor de prueba si no existe
        $proveedor = Proveedor::firstOrCreate(
            ['rfc' => 'TEST123456789'],
            [
                'usuario_id' => $user->id,
                'rfc' => 'TEST123456789',
                'tipo_persona' => 'Física',
                'estado_padron' => 'Activo'
            ]
        );

        // Crear trámite de prueba
        $tramite = Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => 'Test',
            'estado' => 'En_Revision',
            'fecha_inicio' => now(),
            'paso_actual' => 1
        ]);

        $this->info("✅ Trámite de prueba creado exitosamente!");
        $this->info("ID del trámite: " . $tramite->id);
        $this->info("Proveedor ID: " . $proveedor->id);
        $this->info("Usuario ID: " . $user->id);

        return 0;
    }
} 