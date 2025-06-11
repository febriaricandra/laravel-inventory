<?php

namespace App\Providers;

use App\Http\View\Composers\AppComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
