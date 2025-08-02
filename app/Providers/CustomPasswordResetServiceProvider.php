<?php

namespace App\Providers;

use App\Auth\CustomDatabaseTokenRepository;
use App\Auth\CustomPasswordBroker;
use Illuminate\Support\ServiceProvider;

class CustomPasswordResetServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        $this->app->singleton('auth.password.tokens', function ($app) {
            $config = $app['config']['auth.passwords.users'];
            $key = $app['config']['app.key'];

            if (empty($key)) {
                throw new \RuntimeException('Application key not set.');
            }

            $connection = $config['connection'] ?? null;
            $table = $config['table'];
            $hashKey = $config['key'] ?? $key;
            $expire = $config['expire'];

            return new CustomDatabaseTokenRepository(
                $app['db']->connection($connection),
                $app['hash'],
                $table,
                $hashKey,
                $expire
            );
        });
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Auth::provider('custom-eloquent', function ($app, $config) {
            return new \App\Auth\CustomEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
