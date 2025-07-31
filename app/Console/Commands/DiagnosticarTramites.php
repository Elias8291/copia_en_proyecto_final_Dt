<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use App\Models\Cita;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DiagnosticarTramites extends Command
{
    protected $signature = 'tramites:diagnostico';
    protected $description = 'Diagnostica trámites y citas';

    public function handle()
    {
        $this->info("=== DIAGNÓSTICO DE TRÁMITES ===");
        
        // Total trámites
        $totalTramites = Tramite::count();
        $this->info("Total trámites: {$totalTramites}");
        
        // Trámites por estado
        $this->info("\nTrámites por estado:");
        $estados = Tramite::selectRaw('estado, count(*) as total')->groupBy('estado')->get();
        foreach ($estados as $estado) {
            $this->line("  {$estado->estado}: {$estado->total}");
        }
        
        // Total citas
        $totalCitas = Cita::count();
        $this->info("\nTotal citas: {$totalCitas}");
        
        // Citas por estado
        $this->info("\nCitas por estado:");
        $citasEstados = Cita::selectRaw('estado, count(*) as total')->groupBy('estado')->get();
        foreach ($citasEstados as $cita) {
            $this->line("  {$cita->estado}: {$cita->total}");
        }
        
        // Citas vencidas
        $fechaLimite = Carbon::now()->subDays(30);
        $citasVencidas = Cita::where('fecha_cita', '<', $fechaLimite)->count();
        $this->info("\nCitas vencidas (más de 30 días): {$citasVencidas}");
        
        // Trámites con citas vencidas
        $tramitesVencidos = Tramite::whereNotIn('estado', ['Cancelado', 'Aprobado', 'Rechazado'])
            ->whereHas('cita', function ($query) use ($fechaLimite) {
                $query->where('fecha_cita', '<', $fechaLimite);
            })
            ->count();
        $this->info("Trámites con citas vencidas: {$tramitesVencidos}");
        
        $this->info("\n=== FIN DIAGNÓSTICO ===");
        return 0;
    }
} 