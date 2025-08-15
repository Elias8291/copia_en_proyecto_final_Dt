<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Tramites\CorreccionService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar el CorreccionService
        $this->app->singleton(CorreccionService::class, function ($app) {
            return new CorreccionService(
                $app->make(\App\Services\Tramites\DatosGeneralesService::class),
                $app->make(\App\Services\Tramites\DomicilioService::class),
                $app->make(\App\Services\Tramites\ActividadesService::class),
                $app->make(\App\Services\Tramites\AccionistasService::class),
                $app->make(\App\Services\Tramites\ApoderadoService::class),
                $app->make(\App\Services\Tramites\ArchivosService::class),
                $app->make(\App\Services\Tramites\ConstitucionService::class),
                $app->make(\App\Services\Tramites\ContactoService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar Carbon para español
        \Carbon\Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_MX.UTF-8', 'es_MX', 'es_ES.UTF-8', 'es_ES', 'Spanish_Mexico', 'Spanish');
    }
}
