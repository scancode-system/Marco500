<?php

namespace App\Package\Marco500\composers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\Produto;

class ProdutosComposer {

	private $filiais;
	private $filial;
	private $data_fechamento_antes;
	private $data_fechamento_depois;

	private $produtos;

	private $tot_qtd;
	private $tot_total;

	public function compose(View $view) {
		$this->filiais();
		$this->filial();
		$this->data_fechamento_antes();
		$this->data_fechamento_depois();
		$this->produtos();

		$view->with('filiais', $this->filiais);
		$view->with('filial', $this->filial);
		$view->with('produtos', $this->produtos);
		$view->with('data_fechamento_antes', $this->data_fechamento_antes);
		$view->with('data_fechamento_depois', $this->data_fechamento_depois);
		$view->with('tot_qtd', $this->tot_qtd);
		$view->with('tot_total', $this->tot_total);
	}

	private function filiais(){
		$this->filiais = ['Todas filiais', NULL] + Produto::groupBy('filial')->get()->pluck('filial_descricao', 'filial')->toArray();
	}

	private function filial(){
		$this->filial = request()->filial;
	}

	private function produtos(){
		$produtos = Produto::query();
		if($this->filial){
			$produtos->where('filial', $this->filial);
		}
		$produtos->with('pedido_itens', 'pedido_itens.pedido');
		$produtos->whereHas('pedido_itens', function ($query) {
			$query->whereHas('pedido', function ($query2) {
				$query2->where('id_status', 2);
				if($this->data_fechamento_antes){
					$query2->whereDate('data_fechamento', '<=', $this->data_fechamento_antes);
				}
				if($this->data_fechamento_depois){
					$query2->whereDate('data_fechamento', '>=', $this->data_fechamento_depois);
				}
			});	
		});

		$produtos_total = $produtos->get();
		$tot_qtd = 0;
		$tot_total = 0;
		foreach ($produtos_total as $produto) {
			foreach ($produto->pedido_itens as $pedido_item) {
				$tot_qtd += $pedido_item->quantidade;
				$tot_total += ($pedido_item->quantidade*$pedido_item->preco);
			}
		}

		$this->tot_qtd = $tot_qtd;
		$this->tot_total = $tot_total;

		$produtos_paginate = $produtos->paginate(10);
		$produtos_paginate->appends(request()->query());
		
		foreach ($produtos_paginate as $produto) {
			$produto->qtd = 0;
			$produto->total = 0;
			foreach ($produto->pedido_itens as $pedido_item) {
				$produto->qtd += $pedido_item->quantidade;
				$produto->total += ($pedido_item->quantidade*$pedido_item->preco);
			}
		}
		$this->produtos = $produtos_paginate;

//dd($produtos2);
		
	}    

	private function data_fechamento_antes(){
		$this->data_fechamento_antes = request()->data_fechamento_antes;
	}

	private function data_fechamento_depois(){
		$this->data_fechamento_depois = request()->data_fechamento_depois;
	}

}



