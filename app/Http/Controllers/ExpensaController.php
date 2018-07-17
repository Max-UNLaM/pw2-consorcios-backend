<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

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

    public function user(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } else if ($request->get('unidad_id')) {
            return $this->listByUnidad($request);
        } else if ($request->get('page')) {
            return $this->userGetAllExpensasPaginate($request);
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else {
            return Expensa::all();
        }
    }

    protected function userGetAllExpensasPaginate(Request $request)
    {
        return Expensa::userGetAllUsersExpensas(Auth::user()->getAuthIdentifier())->paginate($request->get('size'));
    }
    
    protected function userGetAllExpensas()
    {
        return Expensa::userGetAllUsersExpensas(Auth::user()->getAuthIdentifier())->all();
    }

    protected function userGetByUnidadPaginate(Request $request)
    {
        $unidad = Unidad::find($request->get('unidad_id'));
        if (!$unidad) {
            return response('No se encuentra esta unidad', 404);
        }
        if ($unidad->usuario_id != Auth::user()->getAuthIdentifier()) {
            return response('No autorizado', 403);
        }
        return Expensa::userGetAllUsersExpensas(Auth::user()->getAuthIdentifier())->paginate($request);

    }

    protected function listByUnidad(Request $request)
    {
        if ($request->get('page')) {
            $size = $request->get('size') ? $request->get('size') : 10;
            return Expensa::listByUnidad($request->get('unidad_id'))->paginate($size);
        } else {
            return Expensa::listByUnidad($request->get('unidad'))->all();
        }
    }

    public function paginate(Request $request)
    {
        return Expensa::paginate($request->get('size'));
    }

    public function store(Request $request)
    {
        $expensaSinImporte = new Expensa();

        $expensaSinImporte->unidad_id = $request->get('unidad_id');
        $expensaSinImporte->año = $request->get('año');
        $expensaSinImporte->mes = $request->get('mes');
        $expensaSinImporte->estado = $request->get('estado');
        $expensaSinImporte->emision = $request->get('emision');
        $expensaSinImporte->vencimiento = $request->get('vencimiento');

        $expensaConImporte = Expensa::crearExpensaConImporte($expensaSinImporte);
        return response([
            'expensa' => $expensaConImporte
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
            return response(['ID ' . $request->get('id') . ' not found'], 404);
        }
    }

    public function generarExpensas(Request $request){
        $mes = $request->get('mes');
        $anio = $request->get('año');
        $consorcio_id = $request->get('consorcio_id');
        $unidad_id = $request->get('unidad_id');
        $expensaSinImporte = new Expensa();

        if(!$mes || !$anio) return response(['Mes o año invalidos'], 400);
        if($consorcio_id && $unidad_id) return response(['No se aceptan numero de consorcio y unidad en un mismo pedido'], 400);

        if ($unidad_id) {
                $expensaSinImporte->unidad_id = (int) $unidad_id;
                $expensaSinImporte->año = $anio;
                $expensaSinImporte->mes = $mes;
                $expensaSinImporte->estado = 'impago';
                $expensaSinImporte->emision = $anio.'-'.$mes.'-10';
                $expensaSinImporte->vencimiento = $anio.'-'.$mes.'-20';

                if(sizeof(Expensa::obtenerExpensaPorUnidadMesAnio($unidad_id, $mes, $anio))){
                    return response(['Las expensas de esa unidad en ese periodo ya fueron calculadas'], 400);
                } else {
                    Expensa::crearExpensaConImporte($expensaSinImporte);
                }


        } else {
            $consorcios = $consorcio_id ? array(Consorcio::find($consorcio_id)) : Consorcio::all();

            foreach ($consorcios as $consorcio){
                $unidadesDelConsorcio = Unidad::obtenerIdUnidadesPorIdConsorcio($consorcio->id);

                foreach ($unidadesDelConsorcio as $unidad){

                    $expensaSinImporte->unidad_id = $unidad->id;
                    $expensaSinImporte->año = $anio;
                    $expensaSinImporte->mes = $mes;
                    $expensaSinImporte->estado = 'impago';
                    $expensaSinImporte->emision = $anio.'-'.$mes.'-10';
                    $expensaSinImporte->vencimiento = $anio.'-'.$mes.'-20';

                    if(!sizeof(Expensa::obtenerExpensaPorUnidadMesAnio($unidad->id, $mes, $anio))) Expensa::crearExpensaConImporte($expensaSinImporte);
                }
            }
        }

        return "Las expensas se han creado correctamente";
    }
}