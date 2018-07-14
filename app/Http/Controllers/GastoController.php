<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gasto;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else {
            return Gasto::all();
        }
    }


    public function paginate(Request $request)
    {
        return Gasto::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Gasto::find($id);
    }

    public function store(Request $request)
    {
        return Gasto::create($request->all());
    }
}
