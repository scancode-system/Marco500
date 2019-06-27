<?php

Route::group(['middleware' => ['web']], function () {
	
	Route::get('marco500', '\App\Package\Marco500\controllers\Marco500Controller@index')->name('marco500');
	Route::get('marco500/produtos', '\App\Package\Marco500\controllers\Marco500Controller@produtos')->name('marco500.produtos');
	Route::get('marco500/produtos/xlsx', '\App\Package\Marco500\controllers\Marco500Controller@produtosXlsx')->name('marco500.produtos.xlsx');
	Route::get('marco500/pedidos', '\App\Package\Marco500\controllers\Marco500Controller@pedidos')->name('marco500.pedidos');
	Route::get('marco500/pedidos/xlsx', '\App\Package\Marco500\controllers\Marco500Controller@pedidosXlsx')->name('marco500.pedidos.xlsx');

	
});

