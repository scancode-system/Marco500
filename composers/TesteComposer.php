<?php

namespace App\Packge\Marco500\composers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\Produto;

class TesteComposer {

    private $filiais;

    public function compose(View $view) {
        $view->with('filiais', $this->filiais());
    }

    private function filiais(){
        $produtos = Produto::groupBy('filial')->pluck('filial_descricao', 'filial')->toArray();
        dd($produtos);
    }

}
