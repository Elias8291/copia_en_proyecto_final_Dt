<?php

namespace App\Providers;

use App\Services\Tramites\SesionSatService;
use App\Services\Tramites\ValidacionTramiteService;
use App\Services\Tramites\FormularioTramiteService;
use App\Services\Tramites\CitaTramiteService;
use App\Services\Tramites\RespuestaHttpService;
use App\Services\Revision\RevisionDocumentosService;
use App\Services\Revision\EstadoTramiteService;
use App\Services\Revision\RevisionVisualizacionService;
use App\Services\Revision\HistorialRevisionService;
use App\Services\Core\BaseDataService;
use App\Services\Core\BaseResponseService;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider para servicios de trámites y revisión
 * Registra automáticamente todos los servicios especializados
 */
class TramiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Servicios Base (Core)
        $this->app->singleton(BaseDataService::class);
        $this->app->singleton(BaseResponseService::class);

        // Servicios de Trámites
        $this->app->singleton(SesionSatService::class);
        $this->app->singleton(ValidacionTramiteService::class);
        $this->app->singleton(FormularioTramiteService::class);
        $this->app->singleton(CitaTramiteService::class);
        $this->app->singleton(RespuestaHttpService::class);

        // Servicios de Revisión
        $this->app->singleton(RevisionDocumentosService::class);
        $this->app->singleton(EstadoTramiteService::class);
        $this->app->singleton(RevisionVisualizacionService::class);
        $this->app->singleton(HistorialRevisionService::class);

                       // Servicios de Proveedores (Nuevos)
               $this->app->singleton(\App\Services\Proveedores\EstadosProveedorService::class);
               $this->app->singleton(\App\Services\Proveedores\NumerosPvService::class);
               $this->app->singleton(\App\Services\Proveedores\ProcesadorTramitesService::class);
               $this->app->singleton(\App\Services\Proveedores\TramitesDisponiblesService::class);
               $this->app->singleton(\App\Services\Proveedores\AprobacionTramiteService::class);
               $this->app->singleton(\App\Services\Proveedores\BusquedaProveedorService::class);
               $this->app->singleton(\App\Services\Proveedores\GestionProveedorService::class);
               $this->app->singleton(\App\Services\Proveedores\DatosTramiteAprobadoService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}