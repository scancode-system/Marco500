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

        View::composer('marco500::produtos', 'App\Package\Marco500\composers\ProdutosComposer');
        View::composer('marco500::pedidos', 'App\Package\Marco500\composers\PedidosComposer');
    }

    public function register()
    {

    }
}
