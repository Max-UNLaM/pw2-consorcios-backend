<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Unidad;
use Illuminate\Http\Request;

class ConsorcioController extends Controller
{
	public function index(Request $request) {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if($request->get('id')) {
            return $this->show($request->get('id'));
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
        if(Consorcio::find($request->get('id'))) $this->delete($request);

        return Consorcio::create($request->all());
    }

    public function delete(Request $request){
        $unidadController = new UnidadController();

        $unidades = Unidad::where('consorcio_id', $request->get('id'))->get();

        foreach ($unidades as $unidad) {
            $unidadController->delete($unidad->id);
        }
	    $resp = Consorcio::destroy($request->get('id'));

        if($resp){
            return 'ID '.$request->get('id').' deleted OK';
        } else {
            return 'ID '.$request->get('id').' not found';
        }
    }


}
