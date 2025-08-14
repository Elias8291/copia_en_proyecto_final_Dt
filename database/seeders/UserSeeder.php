<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Administrador', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $revisorDigitalRole = Role::firstOrCreate(['name' => 'Revisor Digital', 'guard_name' => 'web']);
        $revisorPresencialRole = Role::firstOrCreate(['name' => 'Revisor Presencial', 'guard_name' => 'web']);
        $revisorDomiciliarioRole = Role::firstOrCreate(['name' => 'Revisor Domiciliario', 'guard_name' => 'web']);
        $proveedorRole = Role::firstOrCreate(['name' => 'Proveedor', 'guard_name' => 'web']);
        $solicitanteRole = Role::firstOrCreate(['name' => 'Solicitante', 'guard_name' => 'web']);

        $superAdmin = User::firstOrCreate(['correo' => 'elias.ramos@oaxaca.gob.mx'], [
            'nombre' => 'Elias Abisai Ramos Jacinto',
            'rfc' => 'RAJE020226H97',
            'password' => Hash::make('password'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $superAdmin->assignRole($superAdminRole);

        $admin = User::firstOrCreate(['correo' => 'jacqueline.miguel@oaxaca.gob.mx'], [
            'nombre' => 'Jacqueline Patricia Miguel Pensamiento Dominguez',
            'rfc' => 'MIDJ020222G49',
            'password' => Hash::make('password'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $admin->assignRole($adminRole);

        // Revisores Digitales
        $digitales = [
            ['Ricardo López Martínez', 'LOMR900101HDF', 'ricardo.lopez@oaxaca.gob.mx'],
            ['Dennis Pérez Gómez', 'PEGD850202M8A', 'dennis.perez@oaxaca.gob.mx'],
            ['María Fernanda Ruiz', 'RUFM920303K5B', 'maria.ruiz@oaxaca.gob.mx'],
        ];
        foreach ($digitales as [$nombre, $rfc, $correo]) {
            $user = User::firstOrCreate(['correo' => $correo], [
                'nombre' => $nombre,
                'rfc' => $rfc,
                'password' => Hash::make('password'),
                'verification' => true,
                'verification_token' => null,
                'ultimo_acceso' => null,
            ]);
            $user->assignRole($revisorDigitalRole);
        }

        // Revisores Presenciales
        $presenciales = [
            ['Jorge Ramírez Torres', 'RATJ880404N2C', 'jorge.ramirez@oaxaca.gob.mx'],
            ['Luis Hernández Chávez', 'HECL870505P7D', 'luis.hernandez@oaxaca.gob.mx'],
            ['Paola Sánchez Díaz', 'SADP890606Q9E', 'paola.sanchez@oaxaca.gob.mx'],
        ];
        foreach ($presenciales as [$nombre, $rfc, $correo]) {
            $user = User::firstOrCreate(['correo' => $correo], [
                'nombre' => $nombre,
                'rfc' => $rfc,
                'password' => Hash::make('password'),
                'verification' => true,
                'verification_token' => null,
                'ultimo_acceso' => null,
            ]);
            $user->assignRole($revisorPresencialRole);
        }

        // Revisores Domiciliarios
        $domiciliarios = [
            ['Claudia Romero Ortiz', 'ROOC910707R4F', 'claudia.romero@oaxaca.gob.mx'],
            ['Fernando Castillo Vega', 'CAVF860808S6G', 'fernando.castillo@oaxaca.gob.mx'],
            ['Andrea Torres Pineda', 'TOPA930909T8H', 'andrea.torres@oaxaca.gob.mx'],
        ];
        foreach ($domiciliarios as [$nombre, $rfc, $correo]) {
            $user = User::firstOrCreate(['correo' => $correo], [
                'nombre' => $nombre,
                'rfc' => $rfc,
                'password' => Hash::make('password'),
                'verification' => true,
                'verification_token' => null,
                'ultimo_acceso' => null,
            ]);
            $user->assignRole($revisorDomiciliarioRole);
        }

        $this->command->info('Usuarios creados correctamente');
    }
}
