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
        // Get existing roles created by RoleSeeder
        $superAdminRole = Role::where('name', 'Super Administrador')->first();
        $adminRole = Role::where('name', 'Administrador')->first();
        $revisorDigitalRole = Role::where('name', 'Revisor Digital')->first();
        $revisorPresencialRole = Role::where('name', 'Revisor Presencial')->first();
        $revisorDomiciliarioRole = Role::where('name', 'Revisor Domiciliario')->first();
        $proveedorRole = Role::where('name', 'Proveedor')->first();
        $solicitanteRole = Role::where('name', 'Solicitante')->first();

        // Check if all roles exist
        if (!$superAdminRole || !$adminRole || !$revisorDigitalRole || !$revisorPresencialRole || !$revisorDomiciliarioRole || !$proveedorRole || !$solicitanteRole) {
            $this->command->error('❌ Algunos roles no existen. Asegúrese de que RoleSeeder se ejecute primero.');
            return;
        }

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
