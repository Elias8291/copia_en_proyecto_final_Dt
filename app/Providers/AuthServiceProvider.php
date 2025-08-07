<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for different user roles
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('revisor', function ($user) {
            return in_array($user->role, ['revisor_digital', 'revisor_presencial', 'revisor_domiciliario']);
        });

        Gate::define('revisor-digital', function ($user) {
            return $user->role === 'revisor_digital';
        });

        Gate::define('revisor-presencial', function ($user) {
            return $user->role === 'revisor_presencial';
        });

        Gate::define('revisor-domiciliario', function ($user) {
            return $user->role === 'revisor_domiciliario';
        });
    }
} 