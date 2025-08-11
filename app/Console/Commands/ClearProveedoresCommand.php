<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Proveedor;

class ClearProveedoresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:proveedores {--force : Forzar eliminación sin confirmación}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina todos los proveedores y sus datos relacionados de forma segura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $proveedoresCount = Proveedor::count();
        
        if ($proveedoresCount === 0) {
            $this->info('No hay proveedores para eliminar.');
            return 0;
        }

        $this->info("Se encontraron {$proveedoresCount} proveedores en la base de datos.");
        
        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres eliminar TODOS los proveedores y sus datos relacionados?')) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info('Iniciando eliminación de proveedores y datos relacionados...');
        
        try {
            DB::beginTransaction();
            
            // Obtener IDs de proveedores para eliminar datos relacionados
            $proveedorIds = Proveedor::pluck('id')->toArray();
            $tramiteIds = DB::table('tramites')->whereIn('proveedor_id', $proveedorIds)->pluck('id')->toArray();
            
            $this->info('Eliminando datos relacionados...');
            
            // Eliminar en orden correcto para evitar problemas de foreign keys
            $this->deleteRelatedData($proveedorIds, $tramiteIds);
            
            // Finalmente eliminar proveedores
            $deletedProveedores = Proveedor::whereIn('id', $proveedorIds)->delete();
            
            DB::commit();
            
            $this->info("\n✅ ¡Eliminación completada exitosamente!");
            $this->info("📊 Resumen:");
            $this->line("  • Proveedores eliminados: {$deletedProveedores}");
            $this->line("  • Trámites relacionados eliminados");
            $this->line("  • Todos los datos asociados eliminados");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error durante la eliminación: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }

    private function deleteRelatedData(array $proveedorIds, array $tramiteIds)
    {
        if (empty($proveedorIds)) {
            return;
        }

        // Eliminar archivos
        $archivosCount = DB::table('archivos')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($archivosCount > 0) {
            DB::table('archivos')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Archivos eliminados: {$archivosCount}");
        }

        // Eliminar actividades de proveedores
        $actividadesCount = DB::table('actividades')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($actividadesCount > 0) {
            DB::table('actividades')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Actividades eliminadas: {$actividadesCount}");
        }

        // Eliminar accionistas
        $accionistasCount = DB::table('accionistas')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($accionistasCount > 0) {
            DB::table('accionistas')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Accionistas eliminados: {$accionistasCount}");
        }

        // Eliminar apoderados legales
        $apoderadosCount = DB::table('apoderado_legal')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($apoderadosCount > 0) {
            DB::table('apoderado_legal')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Apoderados legales eliminados: {$apoderadosCount}");
        }

        // Eliminar datos constitutivos
        $constitutivosCount = DB::table('datos_constitutivos')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($constitutivosCount > 0) {
            DB::table('datos_constitutivos')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Datos constitutivos eliminados: {$constitutivosCount}");
        }

        // Eliminar instrumentos notariales
        $instrumentosCount = DB::table('instrumentos_notariales')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($instrumentosCount > 0) {
            DB::table('instrumentos_notariales')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Instrumentos notariales eliminados: {$instrumentosCount}");
        }

        // Eliminar contactos
        $contactosCount = DB::table('contactos')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($contactosCount > 0) {
            DB::table('contactos')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Contactos eliminados: {$contactosCount}");
        }

        // Eliminar direcciones
        $direccionesCount = DB::table('direcciones')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($direccionesCount > 0) {
            DB::table('direcciones')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Direcciones eliminadas: {$direccionesCount}");
        }

        // Eliminar datos generales
        $datosGeneralesCount = DB::table('datos_generales')->whereIn('proveedor_id', $proveedorIds)->count();
        if ($datosGeneralesCount > 0) {
            DB::table('datos_generales')->whereIn('proveedor_id', $proveedorIds)->delete();
            $this->line("  → Datos generales eliminados: {$datosGeneralesCount}");
        }

        // Eliminar trámites
        if (!empty($tramiteIds)) {
            $tramitesCount = DB::table('tramites')->whereIn('id', $tramiteIds)->count();
            if ($tramitesCount > 0) {
                DB::table('tramites')->whereIn('id', $tramiteIds)->delete();
                $this->line("  → Trámites eliminados: {$tramitesCount}");
            }
        }

        // Eliminar revisiones relacionadas con trámites
        if (!empty($tramiteIds)) {
            $revisionesCount = DB::table('revisiones')->whereIn('tramite_id', $tramiteIds)->count();
            if ($revisionesCount > 0) {
                DB::table('revisiones')->whereIn('tramite_id', $tramiteIds)->delete();
                $this->line("  → Revisiones eliminadas: {$revisionesCount}");
            }
        }

        // Eliminar notificaciones relacionadas (si las hay)
        $notificacionesCount = DB::table('notificaciones')
            ->where('datos_adicionales->proveedor_id', 'in', $proveedorIds)
            ->count();
        if ($notificacionesCount > 0) {
            DB::table('notificaciones')
                ->where('datos_adicionales->proveedor_id', 'in', $proveedorIds)
                ->delete();
            $this->line("  → Notificaciones relacionadas eliminadas: {$notificacionesCount}");
        }
    }
}
