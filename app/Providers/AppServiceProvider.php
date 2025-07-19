<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\ProveedorService;
use App\Services\EstadoService;
use App\Models\Estado;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register the EstadoService as a singleton
        $this->app->singleton(EstadoService::class, function ($app) {
            return new EstadoService();
        });
    }

    public function boot(): void
    {
        // Compartir estados con todas las vistas
        View::composer('*', function ($view) {
            try {
                $estadoService = app(EstadoService::class);
                $estados = $estadoService->getEstadosForSelect();
                
                $view->with('estados', $estados);
            } catch (\Exception $e) {
                // En caso de error, proporcionar array vacío
                $view->with('estados', []);
                logger('Error cargando estados: ' . $e->getMessage());
            }
        });

        // Compartir datos de usuario y proveedor con todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $proveedorService = app(ProveedorService::class);
                $proveedor = $proveedorService->getProveedorByUser();
                $tramitesDisponibles = $proveedorService->determinarTramitesDisponibles($proveedor);

                $view->with([
                    'proveedor' => $proveedor,
                    'availableProcedures' => $tramitesDisponibles
                ]);
            } else {
                $view->with([
                    'proveedor' => null,
                    'availableProcedures' => [
                        'inscripcion' => true, 
                        'renovacion' => false,
                        'actualizacion' => false,
                        'is_administrative' => false,
                        'message' => 'Debe iniciar sesión para acceder a los trámites.',
                        'estado_vigencia' => null
                    ]
                ]);
            }
        });
    }
}
