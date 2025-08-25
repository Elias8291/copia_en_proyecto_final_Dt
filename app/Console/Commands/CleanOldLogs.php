<?php

namespace App\Console\Commands;

use App\Models\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as LogFacade;

class CleanOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'logs:clean {--days=30 : Número de días para mantener los logs} {--level= : Solo limpiar logs de un nivel específico}';

    /**
     * The console command description.
     */
    protected $description = 'Limpiar logs antiguos del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $level = $this->option('level');
        $fechaLimite = now()->subDays($days);

        $query = Log::where('created_at', '<', $fechaLimite);

        // Filtrar por nivel si se especifica
        if ($level) {
            $query->where('level', $level);
            $this->info("Limpiando logs de nivel '{$level}' anteriores a {$days} días...");
        } else {
            $this->info("Limpiando todos los logs anteriores a {$days} días...");
        }

        $logsEliminados = $query->delete();

        $this->info("Se eliminaron {$logsEliminados} logs antiguos.");

        // Log la acción
        LogFacade::info("Comando logs:clean ejecutado", [
            'logs_eliminados' => $logsEliminados,
            'dias' => $days,
            'nivel' => $level,
            'fecha_limite' => $fechaLimite->toDateTimeString(),
        ]);

        return Command::SUCCESS;
    }
}

