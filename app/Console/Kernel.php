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
        // Ejecutar procesamiento de trámites vencidos automáticamente cada día a las 2:00 AM
        $schedule->command('tramites:cancelar')
                 ->dailyAt('02:00')
                 ->appendOutputTo(storage_path('logs/tramites-procesamiento.log'));
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