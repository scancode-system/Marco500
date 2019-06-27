<?php

namespace App\Package\Marco500\services;

use App\Models\Produto;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Maatwebsite\Excel\Facades\Excel;

class ExportService {

	public function produtosXlsx($filial, $data_fechamento_antes, $data_fechamento_depois){
		$produtos = Produto::query();
		if($filial){
			$produtos->where('filial', $filial);
		}
		$produtos->with('pedido_itens', 'pedido_itens.pedido');
		$produtos->whereHas('pedido_itens', function ($query) use( $data_fechamento_antes, $data_fechamento_depois) {
			$query->whereHas('pedido', function ($query2)  use( $data_fechamento_antes, $data_fechamento_depois){
				$query2->where('id_status', 2);
				if($data_fechamento_antes){
					$query2->whereDate('data_fechamento', '<=', $data_fechamento_antes);
				}
				if($data_fechamento_depois){
					$query2->whereDate('data_fechamento', '>=', $data_fechamento_depois);
				}
			});	
		});

		$produtos = $produtos->get();
		
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

		try {
			Excel::create('Produtos', function($excel) use($produtos, $tot_qtd, $tot_total) {
				$excel->sheet('Produtos', function($sheet) use($produtos, $tot_qtd, $tot_total) {
					$sheet->loadView('marco500::export.xlsx.produtos', 
						[
							'produtos' => $produtos,
							'tot_qtd' => $tot_qtd,
							'tot_total' => $tot_total
						]);
				});
			})->store('xlsx', public_path());
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}

		return public_path('Produtos.xlsx');
	}


	public function pedidosXlsx($filial){
		$datas = Pedido::groupBy('data_fechamento')->where('id_status', 2)->whereHas('pedido_itens', function ($query) use($filial){
			$query->whereHas('produto', function ($query2) use($filial) {
				if($filial){
					$query2->where('filial', $filial);
				}
			});	
		})->get()->pluck('data_fechamento')->toArray();
		

		$filiais = Produto::query();
		if($filial){
			$filiais->where('filial', $filial);
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

		try {
			Excel::create('Pedidos', function($excel) use($filiais, $datas) {
				$excel->sheet('Pedidos', function($sheet) use($filiais, $datas) {
					$sheet->loadView('marco500::export.xlsx.pedidos', 
						[
							'filiais' => $filiais,
							'datas' => $datas,
						]);
				});
			})->store('xlsx', public_path());
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}

		return public_path('Pedidos.xlsx');
	}

}
