<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\Cita;
use App\Models\DatoGeneral;
use App\Models\Direccion;
use App\Models\Contacto;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CrearProveedoresPrueba extends Command
{
    protected $signature = 'proveedores:crear-prueba';

    public function handle()
    {
        // Obtener usuarios verificados
        $usuarios = User::where('verification', true)->get();
        
        if ($usuarios->isEmpty()) {
            $this->error('No hay usuarios verificados. Crea uno primero.');
            return 1;
        }

        $this->info('Creando 3 proveedores de prueba con trámites pendientes y citas vencidas...');

        for ($i = 1; $i <= 3; $i++) {
            $usuario = $usuarios->random(); // Asignar usuario aleatorio a cada proveedor
            $this->crearProveedorCompleto($usuario, $i);
        }

        $this->info('Proveedores de prueba creados exitosamente.');
        return 0;
    }

    private function crearProveedorCompleto($usuario, $numero)
    {
        // Crear proveedor sin PV ni fechas
        $proveedor = Proveedor::create([
            'usuario_id' => $usuario->id,
            'rfc' => 'TEST' . str_pad($numero, 3, '0', STR_PAD_LEFT) . '123ABC',
            'razon_social' => 'Empresa de Prueba ' . $numero,
            'tipo_persona' => 'Moral',
            'estado_padron' => 'Pendiente',
            'token_publico' => Str::random(64)
        ]);

        // Crear trámite pendiente
        $tramite = Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => 'Inscripcion',
            'status' => 'Revision_Digital',
            'fecha_inicio' => Carbon::now()->subDays(rand(5, 30)),
            'observaciones' => 'Trámite de prueba para testing'
        ]);

        // Crear datos generales
        DatoGeneral::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'razon_social' => $proveedor->razon_social,
            'telefono' => '555' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
            'status' => 'pendiente'
        ]);

        // Crear dirección
        Direccion::create([
            'proveedor_id' => $proveedor->id,
            'tramite_id' => $tramite->id,
            'calle' => 'Calle de Prueba ' . $numero,
            'numero_exterior' => (string)rand(1, 999),
            'numero_interior' => (string)rand(1, 50),
            'colonia' => 'Colonia de Prueba',
            'codigo_postal' => '12345',
            'municipio' => 'Municipio de Prueba',
            'asentamiento' => 'Asentamiento de Prueba',
            'estado_id' => 1, // Usar estado ID 1 como default
            'status' => 'pendiente'
        ]);

        // Crear contacto
        Contacto::create([
            'proveedor_id' => $proveedor->id,
            'tramite_id' => $tramite->id,
            'nombre_contacto' => 'Contacto ' . $numero,
            'cargo' => 'Gerente',
            'correo_electronico' => 'contacto' . $numero . '@prueba.com',
            'telefono' => '555' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
            'status' => 'pendiente'
        ]);

        // Crear cita vencida (hace 2-7 días)
        $fechaVencida = Carbon::now()->subDays(rand(2, 7));
        
        Cita::create([
            'tramite_id' => $tramite->id,
            'tipo_cita' => 'Presencial',
            'fecha_cita' => $fechaVencida,
            'estado' => 'Asignada',
            'asignado_a' => $usuario->id,
            'intento' => rand(1, 2) // Algunos con 1 intento, otros con 2
        ]);

        $this->line("Proveedor #{$numero} creado - Trámite #{$tramite->id} con cita vencida el {$fechaVencida->format('d/m/Y H:i')}");
    }
}
