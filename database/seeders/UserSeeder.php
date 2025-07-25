<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(['correo' => '20161302@itoaxaca.edu.mx'], [
            'nombre' => 'Elias Abisai Ramos Jacinto',
            'rfc' => 'RAJE020226G97',
            'password' => Hash::make('gSSKAtlVP'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $superAdmin->assignRole('Super Administrador');

        $admin = User::firstOrCreate(['correo' => '20161273@itoaxaca.edu.mx'], [
            'nombre' => 'Jacqueline Patricia Miguel Pensamiento Dominguez',
            'rfc' => 'MIDJ020222G49',
            'password' => Hash::make('vHiTUCYQ'),
            'verification' => true,
            'verification_token' => null,
            'ultimo_acceso' => null,
        ]);
        $admin->assignRole('Administrador');

        $this->command->info('Usuarios creados correctamente');
    }
}
