<?php

namespace App\Http\Controllers;

use App\Reclamo;
use Illuminate\Http\Request;

class ReclamoController extends Controller
{
	public function index(Request $request)
	{
		if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
			$this->show($request->get('id'));
		} else {
			return Reclamo::all();
		}
	}

	public function paginate(Request $request)
    {
        return Unidad::paginate($request->get('size'));
    }

	public function store()
	{

		$reclamo = $this->create($request->all());

		return response([
            'reclamo' => $reclamo
        ]);
	}

	public function show($id)
    {
        return Reclamo::find($id);
    }
}
