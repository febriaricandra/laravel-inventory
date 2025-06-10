<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\AppComposer;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Mengikat composer ke view 'profile'
        View::composer('*', AppComposer::class);

        // Atau menggunakan closure
        // View::composer('dashboard', function ($view) {
        //     $view->with('key', 'value');
        // });
    }

    public function register()
    {
        //
    }
}