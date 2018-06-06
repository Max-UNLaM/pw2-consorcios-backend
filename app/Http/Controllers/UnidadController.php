<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidad;

class UnidadController extends Controller
{
    public function index() {
    	return Unidad::all();
    }

	public function show($id)
	{
		return Unidad::find($id);
	}

	public function store(Request $request)
	{
		return Unidad::create($request->all());
	}

}
