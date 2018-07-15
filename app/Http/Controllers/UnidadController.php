<?php

namespace App\Http\Controllers;

use App\Expensa;
use Illuminate\Http\Request;
use App\Unidad;

class UnidadController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else {
            return Unidad::all();
        }
    }


    public function paginate(Request $request)
    {
        return Unidad::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Unidad::find($id);
    }

    public function store(Request $request)
    {
        return Unidad::create($request->all());
    }

    public function delete($id){
        
        $expensas = Expensa::where('unidad_id', $id)->get();
        foreach ($expensas as $expensa) {
            Expensa::destroy($expensa->id);
        }

        Unidad::destroy($id);
    }

}