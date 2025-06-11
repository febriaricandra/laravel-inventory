<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

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
        // Log semua query ke storage/logs/laravel.log
        if (app()->environment('local')) {
            Model::preventLazyLoading(! app()->isProduction());
            // //handle lazy loading violation
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                $class = get_class($model);
                info("Lazy loading detected on {$class} when trying to load '{$relation}'");
            });
        }
    }
}
