<?php

namespace App\Package\Marco500\services;

use App\Models\Produto;
use Maatwebsite\Excel\Facades\Excel;

class ExportService {

	public function xlsx($filial, $data_fechamento){
		$produtos = Produto::query();
		if($filial){
			$produtos->where('filial', $filial);
		}
		$produtos->with('pedido_itens', 'pedido_itens.pedido');
		$produtos->whereHas('pedido_itens', function ($query) use( $data_fechamento) {
			$query->whereHas('pedido', function ($query2)  use( $data_fechamento){
				$query2->where('id_status', 2);
				if($data_fechamento){
					$query2->where('id_status', 2);
					$query2->where('data_fechamento', $data_fechamento);
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

		try {
			Excel::create('Produtos', function($excel) use($produtos, $tot_qtd, $tot_total) {
				$excel->sheet('Produtos', function($sheet) use($produtos, $tot_qtd, $tot_total) {
					$sheet->loadView('marco500::export.xlsx', 
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

}
