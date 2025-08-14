<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Procesamiento diario a las 2:00 AM
        $schedule->command('proveedores:actualizar-vencidos')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/proveedores-vencidos.log'));
        
        $schedule->command('citas:procesar-vencidas')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/citas-procesamiento.log'));
        
        $schedule->command('tramites:notificar-correcciones-pendientes')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/correcciones-pendientes.log'));
        
        // Notificaciones diarias a las 8:00 AM
        $schedule->command('proveedores:notificar-vencimientos-proximos')
                 ->dailyAt('08:00')
                 ->appendOutputTo(storage_path('logs/vencimientos-proximos.log'));
        
        // Recordatorios de citas cada hora
        $schedule->command('citas:notificar-recordatorios')
                 ->hourly()
                 ->appendOutputTo(storage_path('logs/recordatorios-citas.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 