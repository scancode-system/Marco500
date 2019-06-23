<?php

Route::group(['middleware' => ['web']], function () {
	
	Route::get('marco500', '\App\Package\Marco500\controllers\Marco500Controller@index')->name('marco500');
	
});

