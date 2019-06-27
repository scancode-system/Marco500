<?php

namespace App\Package\Marco500\composers;

use Illuminate\View\View;
use Illuminate\Http\Request;

use App\Models\Produto;
use App\Models\Pedido;
use App\Models\PedidoItem;

class PedidosComposer {

	private $filiais;
	private $filial;

	private $totais;
	private $datas;


	public function compose(View $view) {
		$this->filiais();
		$this->filial();
		$this->totais();

		$view->with('filiais', $this->filiais);
		$view->with('filial', $this->filial);
		$view->with('totais', $this->totais);
		$view->with('datas', $this->datas);
		$view->with('data_fechamento_antes', null);
		$view->with('data_fechamento_depois', null);
	}

	private function filiais(){
		$this->filiais = ['Todas filiais', NULL] + Produto::groupBy('filial')->get()->pluck('filial_descricao', 'filial')->toArray();
	}

	private function filial(){
		$this->filial = request()->filial;
	}

	private function totais(){
		$datas = Pedido::groupBy('data_fechamento')->where('id_status', 2)->whereHas('pedido_itens', function ($query) {
			$query->whereHas('produto', function ($query2) {
				if($this->filial){
					$query2->where('filial', $this->filial);
				}
			});	
		})->get()->pluck('data_fechamento')->toArray();
		

		$filiais = Produto::query();
		if($this->filial){
			$filiais->where('filial', $this->filial);
		}
		$filiais = $filiais->groupBy('filial')->get()->pluck('filial_descricao', 'filial_descricao')->toArray();
		
		foreach ($filiais as $i => $filial) {
			$filial = (object)['total' => 0, 'datas' => []];
			foreach ($datas as $data) {
				$filial->datas[$data] = 0;
			}

			$filiais[$i] = $filial;
		}

		$pedido_items = PedidoItem::with(['pedido', 'produto', 'pedido.pedido_pagamento'])->whereHas('pedido', function ($query) {
			$query->where('id_status', 2);
		})->get();

		foreach ($pedido_items as $pedido_item) {
			$filial = $pedido_item->produto->filial_descricao;

			if(isset($filiais[$filial])){
				$filiais[$filial]->datas[$pedido_item->pedido->data_fechamento] += $pedido_item->total_liquido;
				$filiais[$filial]->total += $pedido_item->total_liquido;
			}
		}

		$this->totais = $filiais;
		$this->datas = $datas;
		
	}    

}



