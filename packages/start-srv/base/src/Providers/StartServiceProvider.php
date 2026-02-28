<?php

namespace Universo\Start\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Universo\Start\Services\SupabaseAuthService;

/**
 * StartServiceProvider - Service provider for the start-srv package
 *
 * Registers:
 * - SupabaseAuthService in the service container
 * - API routes under /api/v1
 */
class StartServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->app->singleton(SupabaseAuthService::class);
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        $this->registerApiRoutes();
    }

    /**
     * Load package API routes, nested inside the root /api/v1 prefix.
     */
    protected function registerApiRoutes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(__DIR__ . '/../../routes/api.php');
    }
}
