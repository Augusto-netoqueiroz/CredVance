<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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
    public function boot()
{
    RateLimiter::for('gerar-boletos', function () {
        return Limit::perMinute(10);
    });
    RateLimiter::for('download-boletos-pdf', function () {
        return Limit::perMinute(10);
    });
}
}
