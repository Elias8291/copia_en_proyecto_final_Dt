<?php

namespace App\Providers;

use App\Services\ProveedorService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Configurar vista por defecto de paginación
        Paginator::defaultView('pagination::tailwind');
        
        // Compartir datos del proveedor y trámites en todas las vistas
        View::composer('*', function ($view) {
            // Verificar si la aplicación está bootstrapped y Auth está disponible
            if (app()->isBooted() && app()->bound('auth') && Auth::check()) {
                try {
                    $proveedorService = app(ProveedorService::class);
                    $proveedor = $proveedorService->getProveedorByUser();
                    $tramitesDisponibles = $proveedorService->determinarTramitesDisponibles($proveedor);
                    $hasActiveProveedor = $proveedorService->hasActiveProveedor();

                    $view->with([
                        'globalProveedor' => $proveedor,
                        'globalTramites' => $tramitesDisponibles,
                        'hasActiveProveedor' => $hasActiveProveedor,
                        'proveedorEstado' => $proveedor?->estado_padron ?? null,
                        'proveedorRazonSocial' => $proveedor?->razon_social ?? null,
                    ]);
                } catch (\Exception $e) {
                    // En caso de error, proporcionar valores por defecto
                    $view->with([
                        'globalProveedor' => null,
                        'globalTramites' => [],
                        'hasActiveProveedor' => false,
                        'proveedorEstado' => null,
                        'proveedorRazonSocial' => null,
                    ]);
                }
            } else {
                // Si no está autenticado o la aplicación no está lista, proporcionar valores por defecto
                $view->with([
                    'globalProveedor' => null,
                    'globalTramites' => [],
                    'hasActiveProveedor' => false,
                    'proveedorEstado' => null,
                    'proveedorRazonSocial' => null,
                ]);
            }
        });
    }
}
