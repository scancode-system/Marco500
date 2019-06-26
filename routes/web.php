<?php

Route::group(['middleware' => ['web']], function () {
	
	Route::get('marco500', '\App\Package\Marco500\controllers\Marco500Controller@index')->name('marco500');
	Route::get('marco500/xlsx', '\App\Package\Marco500\controllers\Marco500Controller@xlsx')->name('marco500.xlsx');
	
});

