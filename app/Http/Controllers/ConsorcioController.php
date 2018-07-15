<?php

namespace App\Http\Controllers;

use App\Consorcio;
use Illuminate\Http\Request;

class ConsorcioController extends Controller
{
	public function index(Request $request) {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else {
            return Consorcio::all();
        }
	}

    public function paginate(Request $request)
    {
        return Consorcio::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Consorcio::find($id);
    }

    public function store(Request $request)
    {
        return Consorcio::create($request->all());
    }


}
