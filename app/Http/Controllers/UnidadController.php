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
        } else if($request->get('id')){
            return $this->show($request->get('id'));
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
        if(Unidad::find($request->get('id'))) $this->delete($request->get('id'));

        Unidad::create($request->all());

        return $request->all();
    }

    public function delete(Request $request){
        return $this->deleteById($request->get('id'));
    }

    public function deleteById($id){
        $expensas = Expensa::where('unidad_id', $id)->get();
        foreach ($expensas as $expensa) {
            Expensa::destroy($expensa->id);
        }

        $resp = Unidad::destroy($id);

        if($resp){
            return 'ID '.$id.' deleted OK';
        } else {
            return 'ID '.$id.' not found';
        }
    }

}