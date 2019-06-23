<?php

namespace App\Package\Marco500;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


class Marco500ServiceProvider extends ServiceProvider
{


    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'marco500');

        $this->publishes([
            __DIR__.'/views/widgets' => resource_path('views/widgets')
        ]);

        View::composer('marco500::index', 'App\Package\Marco500\composers\IndexComposer');
    }

    public function register()
    {

    }
}
