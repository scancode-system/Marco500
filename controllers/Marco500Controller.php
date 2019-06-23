<?php

namespace App\Package\Marco500\controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Excption;

class Marco500Controller extends BaseController {

	public function index() {
		return view('marco500::index');
	}

}
