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
        // Crear roles si no existen
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $revisorRole = Role::firstOrCreate(['name' => 'Revisor Presencial', 'guard_name' => 'web']);

        $superAdmin = User::firstOrCreate(['correo' => '20161302@itoaxaca.edu.mx'], [
            'nombre' => 'Elias Abisai Ramos Jacinto',
            'rfc' => 'RAJE020226G97',
            'password' => Hash::make('gSSKAtlVP'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $superAdmin->assignRole($superAdminRole);

        $admin = User::firstOrCreate(['correo' => '20161273@itoaxaca.edu.mx'], [
            'nombre' => 'Jacqueline Patricia Miguel Pensamiento Dominguez',
            'rfc' => 'MIDJ020222G49',
            'password' => Hash::make('vHiTUCYQ'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $admin->assignRole($adminRole);

        // Crear usuario revisor presencial
        $revisorPresencial = User::firstOrCreate(['correo' => 'revisor@test.com'], [
            'nombre' => 'Revisor Presencial Test',
            'rfc' => 'TEST123456789',
            'password' => Hash::make('password'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $revisorPresencial->assignRole($revisorRole);

        $this->command->info('Usuarios creados correctamente');
    }
}
