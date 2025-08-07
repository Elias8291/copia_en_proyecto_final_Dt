<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Enums\UserRole;

class VerificarRevisoresPresenciales extends Command
{
    protected $signature = 'revisores:verificar-presenciales';
    protected $description = 'Verificar si hay revisores presenciales en la base de datos';

    public function handle()
    {
        $this->info('Verificando revisores presenciales...');
        
        $revisoresPresenciales = User::whereHas('roles', function($query) {
            $query->where('name', UserRole::REVISOR_PRESENCIAL->value);
        })->get();
        
        if ($revisoresPresenciales->isEmpty()) {
            $this->error('❌ No hay revisores presenciales en la base de datos');
            $this->info('Para que funcione el agendamiento automático de citas, necesitas:');
            $this->info('1. Crear usuarios con el rol "Revisor Presencial"');
            $this->info('2. O verificar que el rol existe en la tabla roles');
            
            // Mostrar todos los roles disponibles
            $roles = \Spatie\Permission\Models\Role::all();
            $this->info('Roles disponibles:');
            foreach ($roles as $rol) {
                $this->line("- {$rol->name}");
            }
            
            return 1;
        }
        
        $this->info("✅ Se encontraron {$revisoresPresenciales->count()} revisores presenciales:");
        
        foreach ($revisoresPresenciales as $revisor) {
            $this->line("- ID: {$revisor->id}, Nombre: {$revisor->name}, Email: {$revisor->email}");
        }
        
        $this->info('El agendamiento automático de citas debería funcionar correctamente.');
        
        return 0;
    }
} 