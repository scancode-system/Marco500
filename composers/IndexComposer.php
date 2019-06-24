<?php

namespace App\Package\Marco500\composers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\Produto;

class IndexComposer {

	private $filiais;
	private $filial;
	private $data_fechamento;

	private $produtos;

	private $tot_qtd;
	private $tot_total;

	public function compose(View $view) {
		$this->filiais();
		$this->filial();
		$this->data_fechamento();
		$this->produtos();

		$view->with('filiais', $this->filiais);
		$view->with('filial', $this->filial);
		$view->with('produtos', $this->produtos);
		$view->with('data_fechamento', $this->data_fechamento);
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
				if($this->data_fechamento){
					$query2->where('id_status', 2);
					$query2->where('data_fechamento', $this->data_fechamento);
				}
			});	
		});
		$produtos = $produtos->paginate(10);
		$produtos->appends(request()->query());
		
		$tot_qtd = 0;
		$tot_total = 0;
		foreach ($produtos as $produto) {
			$produto->qtd = 0;
			$produto->total = 0;
			foreach ($produto->pedido_itens as $pedido_item) {
				$produto->qtd += $pedido_item->quantidade;
				$produto->total += ($pedido_item->quantidade*$pedido_item->preco);
			}

			$tot_qtd+= $produto->qtd;
			$tot_total+= $produto->total;
		}

		$this->produtos = $produtos;
		$this->tot_qtd = $tot_qtd;
		$this->tot_total = $tot_total;
	}    

	private function data_fechamento(){
		$this->data_fechamento = request()->data_fechamento;
	}

}



