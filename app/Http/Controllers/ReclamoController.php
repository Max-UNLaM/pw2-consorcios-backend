<?php

namespace App\Http\Controllers;

use App\Reclamo;
use Illuminate\Http\Request;

class ReclamoController extends Controller
{
    public function index() {
		return Reclamo::all();
	}

	public function create(){

		 /*

		 if (count($errors)) {
		 	return response(['errors' => $errors], 401);
		}
		*/

		$reclamo = $this->create($request->all());

		return response([
            'reclamo' => $reclamo
        ]);
	}
}
