<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Services\ProveedorService;
use App\Models\Estado;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Compartir datos del proveedor y trámites en todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $proveedorService = app(ProveedorService::class);
                $proveedor = $proveedorService->getProveedorByUser();
                $tramitesDisponibles = $proveedorService->determinarTramitesDisponibles($proveedor);
                $hasActiveProveedor = $proveedorService->hasActiveProveedor();

                $view->with([
                    'globalProveedor' => $proveedor,
                    'globalTramites' => $tramitesDisponibles,
                    'hasActiveProveedor' => $hasActiveProveedor,
                    'proveedorEstado' => $proveedor ? $proveedor->estado_padron : null,
                    'proveedorRazonSocial' => $proveedor ? $proveedor->razon_social : null
                ]);
            }
        });
    }
}
