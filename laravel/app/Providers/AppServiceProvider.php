<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected $routeMiddleware = [
    'check.module' => CheckModuleActive::class,
];
}
