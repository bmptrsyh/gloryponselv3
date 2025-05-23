<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected function configureRateLimiting()
{
    RateLimiter::for('login', function (Request $request) {
        $identifier = $request->login . '|' . $request->ip();
        return Limit::perMinute(5)->by($identifier);
    });
}
 

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
