<?php

namespace App\Http\Controllers;

use App\Expensa;
use Illuminate\Http\Request;

class ExpensaController extends Controller
{
    public function index() {
		return Expensa::all();
	}

	public function create(){

		 $errors;

		 if(count($errors)){
		 	return response(['errors' => $errors], 401);
		}

		$expensa = $this->create($request->all());

		return response([
            'expensa' => $expensa
        ]);
	}
}
