<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Proveedor, Tramite, User, DatoGeneral, Direccion, Contacto};
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProveedoresPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $rfc = 'EMP123456789';

        if (Proveedor::where('rfc', $rfc)->exists()) {
            $this->command->warn("⚠️  Ya existen proveedores con RFC {$rfc}");
            $this->command->info("💡 Ejecuta: php artisan db:seed --class=LimpiarProveedoresPruebaSeeder");
            return;
        }

        $usuario = User::create([
            'correo' => 'proveedor.prueba.' . time() . '@example.com',
            'nombre' => 'Proveedor de Prueba',
            'password' => bcrypt('password123'),
            'rfc' => $rfc,
            'verification' => 1,
        ]);

        $provHistorico = Proveedor::create([
            'usuario_id' => $usuario->id,
            'pv_numero' => 'PV001-2021',
            'token_publico' => Str::random(32),
            'rfc' => $rfc,
            'razon_social' => 'Empresa de Prueba S.A. de C.V. (Histórico)',
            'tipo_persona' => 'Moral',
            'estado_padron' => 'Vencido',
            'fecha_alta_padron' => Carbon::create(2021, 1, 15),
            'fecha_vencimiento_padron' => Carbon::create(2022, 12, 31),
            'created_at' => Carbon::create(2021, 1, 15),
            'updated_at' => Carbon::create(2022, 12, 31),
        ]);

        $tramiteInscripcion = Tramite::create([
            'proveedor_id' => $provHistorico->id,
            'tipo_tramite' => 'Inscripcion',
            'status' => 'Aprobado',
            'fecha_inicio' => Carbon::create(2021, 1, 10, 9, 0),
            'fecha_finalizacion' => Carbon::create(2021, 1, 20, 16, 30),
            'observaciones' => 'Trámite de inscripción inicial aprobado exitosamente.',
            'paso_actual' => 8,
            'created_at' => Carbon::create(2021, 1, 10),
            'updated_at' => Carbon::create(2021, 1, 20),
        ]);

        $provActivo = Proveedor::create([
            'usuario_id' => $usuario->id,
            'pv_numero' => 'PV001-2023',
            'token_publico' => Str::random(32),
            'rfc' => $rfc,
            'razon_social' => 'Empresa de Prueba S.A. de C.V.',
            'tipo_persona' => 'Moral',
            'estado_padron' => 'Activo',
            'fecha_alta_padron' => Carbon::create(2023, 1, 15),
            'fecha_vencimiento_padron' => Carbon::create(2026, 12, 31),
            'created_at' => Carbon::create(2023, 1, 15),
            'updated_at' => now(),
        ]);

        foreach ([
            ['fecha' => Carbon::create(2023, 2, 15), 'status' => 'Aprobado',        'obs' => 'Primera renovación aprobada - Año 2023'],
            ['fecha' => Carbon::create(2024, 2, 15), 'status' => 'Aprobado',        'obs' => 'Segunda renovación aprobada - Año 2024'],
            ['fecha' => Carbon::create(2025, 2, 15), 'status' => 'Revision_Digital','obs' => 'Tercera renovación en proceso - Año 2025'],
        ] as $d) {
            $fin = $d['status'] === 'Aprobado' ? $d['fecha']->copy()->addDays(10) : null;
            Tramite::create([
                'proveedor_id' => $provActivo->id,
                'revisor_digital_id' => $d['status'] === 'Revision_Digital' ? $usuario->id : null,
                'tipo_tramite' => 'Renovacion',
                'status' => $d['status'],
                'fecha_inicio' => $d['fecha']->copy()->subDays(5),
                'fecha_finalizacion' => $fin,
                'observaciones' => $d['obs'],
                'paso_actual' => $d['status'] === 'Aprobado' ? 8 : 3,
                'created_at' => $d['fecha']->copy()->subDays(5),
                'updated_at' => $fin ?? $d['fecha'],
            ]);
        }

        $this->crearDatosBasicos($provHistorico, $tramiteInscripcion, '2021');
        $this->crearDatosBasicos($provActivo, $provActivo->tramites()->latest('created_at')->first(), '2023');

        $this->command->info('✅ Proveedores de prueba creados');
        $this->command->info("RFC: {$rfc}");
        $this->command->info("Usuario: {$usuario->correo} | pass: password123");
    }

    private function crearDatosBasicos(Proveedor $proveedor, Tramite $tramite, string $anio): void
    {
        DatoGeneral::create([
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'curp' => $proveedor->tipo_persona === 'Moral' ? null : 'CURP123456HDFXXX09',
            'razon_social' => $proveedor->razon_social,
            'pagina_web' => 'https://empresaprueba.com',
            'telefono' => '555-0123-456',
            'status' => $tramite->status === 'Aprobado' ? 'vigente' : 'pendiente',
        ]);

        Direccion::create([
            'proveedor_id' => $proveedor->id,
            'tramite_id' => $tramite->id,
            'calle' => 'Av. Reforma',
            'entre_calle' => 'Calle Norte',
            'y_calle' => 'Calle Sur',
            'numero_exterior' => '123',
            'numero_interior' => 'A',
            'colonia' => 'Centro',
            'codigo_postal' => '06000',
            'municipio' => 'Cuauhtémoc',
            'asentamiento' => 'Centro Histórico',
            'estado_id' => 1,
            'status' => $tramite->status === 'Aprobado' ? 'vigente' : 'pendiente',
        ]);

        Contacto::create([
            'proveedor_id' => $proveedor->id,
            'tramite_id' => $tramite->id,
            'nombre_contacto' => 'Juan Carlos Pérez García',
            'cargo' => 'Director General',
            'telefono' => '555-0123-456',
            'correo_electronico' => "contacto{$anio}@empresaprueba.com",
            'status' => $tramite->status === 'Aprobado' ? 'vigente' : 'pendiente',
        ]);
    }
}
