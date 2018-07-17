<?php

namespace App\Http\Controllers;

use App\Expensa;
use App\Gasto;
use App\Unidad;
use App\Consorcio;
use Illuminate\Http\Request;

class ExpensaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } else if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else if ($request->get('unidad_id')) {
            return $this->listByUnidad($request->get('unidad_id'));
        } else {
            return Expensa::all();
        }
    }

    public function user(Request $request){
        if ($request->get('puerta')) {
            return "PATOVA";
        } else if ($request->get('page')) {
            return $this->paginate($request);
        } else {
            return $this->show($request->get('id'));
        }
    }

    protected function listByUnidad(int $unidadId)
    {
        return Expensa::listByUnidad($unidadId);
    }

    public function paginate(Request $request)
    {
        return Expensa::paginate($request->get('size'));
    }

    public function store(Request $request)
    {
        $expensaNueva = Expensa::crearExpensaConImporte($request->all());
        return response([
            'expensa' => $expensaNueva
        ]);
    }

    public function update(Request $request){
        //Busco la expensa correspondiente
        $expensa = Expensa::find($request->get('id'));

        //Pregunto si encontro una expensa con ese id
        if($expensa){
            //Actualizo los atributos de la expensa encontrada
            $expensa->unidad_id = $request->get('unidad_id');
            $expensa->año = $request->get('año');
            $expensa->mes = $request->get('mes');
            $expensa->estado = $request->get('estado');
            $expensa->emision = $request->get('emision');
            $expensa->vencimiento = $request->get('vencimiento');
            $expensa->importe= $request->get('importe');

            //Guardo los cambios
            $expensa->save();

            return response([
                'expensaGuardada' => $expensa
            ]);
        } else {
            //Si no la encuentra respondo un codigo 404 (not found)
            return response(['No se encontro la expensa que se quiere actualizar'], 404);
        }


    }

    public function show($id)
    {
        return Expensa::find($id);
    }

    public function delete(Request $request)
    {
        $resp = Expensa::destroy($request->get('id'));

        if ($resp) {
            return 'ID ' . $request->get('id') . ' deleted OK';
        } else {
            return 'ID ' . $request->get('id') . ' not found';
        }
    }
}