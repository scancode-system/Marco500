<?php

namespace App\Package\Marco500\controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Excption;
use App\Package\Marco500\services\ExportService;

class Marco500Controller extends BaseController {

	public function index() {
		return view('marco500::index');
	}


	public function xlsx(Request $request, $filial = null, $data_fechamento = null){
        return response()->download((new ExportService())->xlsx($filial, $data_fechamento))->deleteFileAfterSend(true);
    }

}
