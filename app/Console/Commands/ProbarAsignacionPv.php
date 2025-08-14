<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AsignacionPvService;
use App\Models\Tramite;
use App\Models\Proveedor;

class ProbarAsignacionPv extends Command
{
    protected $signature = 'proveedores:probar-asignacion-pv 
                            {numero_proveedor : Número identificador del proveedor}
                            {--ultimo-pv= : Último PV del sistema (formato PV901323)}
                            {--fecha-revision= : Fecha de revisión (YYYY-MM-DD, por defecto hoy)}
                            {--tramite-id= : ID del trámite para aplicar la asignación}
                            {--aplicar : Aplicar la asignación al proveedor del trámite}';

    protected $description = 'Prueba la asignación de PV y fechas de vigencia según las reglas de negocio';

    private AsignacionPvService $asignacionPvService;

    public function __construct(AsignacionPvService $asignacionPvService)
    {
        parent::__construct();
        $this->asignacionPvService = $asignacionPvService;
    }

    public function handle()
    {
        $numeroProveedor = (int) $this->argument('numero_proveedor');
        $ultimoPvSistema = $this->option('ultimo-pv');
        $fechaRevision = $this->option('fecha-revision') ?: now()->format('Y-m-d');
        $tramiteId = $this->option('tramite-id');
        $aplicar = $this->option('aplicar');

        $this->info("=== PRUEBA DE ASIGNACIÓN DE PV ===");
        $this->info("Número Proveedor: {$numeroProveedor}");
        $this->info("Último PV Sistema: " . ($ultimoPvSistema ?: 'null'));
        $this->info("Fecha Revisión: {$fechaRevision}");
        $this->info("Aplicar: " . ($aplicar ? 'Sí' : 'No'));
        $this->info("");

        // Probar asignación
        $resultado = $this->asignacionPvService->asignarPvYVigencia(
            $numeroProveedor,
            $ultimoPvSistema,
            $fechaRevision
        );

        // Mostrar resultados
        $this->info("=== RESULTADOS ===");
        $this->table(
            ['Campo', 'Valor'],
            [
                ['PV', $resultado['pv'] ?: 'ERROR'],
                ['Vigencia Inicio', $resultado['vigencia_inicio'] ?: 'ERROR'],
                ['Vigencia Fin', $resultado['vigencia_fin'] ?: 'ERROR'],
                ['Fecha Alta Padrón', $resultado['fecha_alta_padron'] ?: 'ERROR'],
                ['Notas Validación', $resultado['notas_validacion'] ?: 'Sin notas']
            ]
        );

        // Si hay error, mostrar y terminar
        if (!$resultado['pv']) {
            $this->error("Error en la asignación: " . $resultado['notas_validacion']);
            return 1;
        }

        // Si se solicita aplicar y hay trámite ID
        if ($aplicar && $tramiteId) {
            $this->info("");
            $this->info("=== APLICANDO ASIGNACIÓN ===");
            
            try {
                $tramite = Tramite::findOrFail($tramiteId);
                $proveedor = $tramite->proveedor;
                
                $this->info("Trámite ID: {$tramite->id}");
                $this->info("Tipo Trámite: {$tramite->tipo_tramite}");
                $this->info("Proveedor ID: {$proveedor->id}");
                $this->info("RFC: {$proveedor->rfc}");
                
                // Verificar que sea inscripción
                if ($tramite->tipo_tramite !== 'Inscripcion') {
                    $this->error("El trámite debe ser de tipo 'Inscripcion' para aplicar asignación de PV");
                    return 1;
                }
                
                $aplicacionExitosa = $this->asignacionPvService->aplicarAsignacion($proveedor, $resultado);
                
                if ($aplicacionExitosa) {
                    $this->info("✅ Asignación aplicada exitosamente");
                    
                    // Mostrar estado final del proveedor
                    $proveedor->refresh();
                    $this->info("");
                    $this->info("=== ESTADO FINAL DEL PROVEEDOR ===");
                    $this->table(
                        ['Campo', 'Valor'],
                        [
                            ['PV Número', $proveedor->pv_numero ?: 'No asignado'],
                            ['Estado Padrón', $proveedor->estado_padron],
                            ['Fecha Alta', $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('Y-m-d') : 'No asignada'],
                            ['Fecha Vencimiento', $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('Y-m-d') : 'No asignada']
                        ]
                    );
                } else {
                    $this->error("❌ Error al aplicar la asignación");
                    return 1;
                }
                
            } catch (\Exception $e) {
                $this->error("Error al aplicar asignación: " . $e->getMessage());
                return 1;
            }
        }

        $this->info("");
        $this->info("=== PRUEBA COMPLETADA ===");
        return 0;
    }
}
