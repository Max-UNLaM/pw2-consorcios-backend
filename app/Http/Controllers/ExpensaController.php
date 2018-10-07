<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Liquidacion;
use App\Unidad;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;

class ExpensaController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->get('id');
        if($id) return Expensa::find($id);

        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        if($user->isOperator()){
            return Expensa::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Expensa::list()->paginate($size);
        }

    }


    public function user(Request $request)
    {
        $userId = Auth::user()->getAuthIdentifier();

        if ($request->get('puerta')) {
            return response(["entra" => "PATOVA"]);
        } else if ($request->get('id')) {
            return $this->show($request->get('id'));
        } else if ($request->get('unidad_id')) {
            return $this->listByUnidad($request);
        } else if ($request->get('page')) {
            $size = $request->get('size') ? $request->get('size') : 5;
            return Expensa::expensasPorUsuario($userId)->paginate($size);
        } else {
            return Expensa::expensasPorUsuario($userId)->get();
        }
    }

    /*protected function userGetAllExpensasPaginate(Request $request)
    {
        return Expensa::expensasPorUsuario(Auth::user()->getAuthIdentifier())->paginate($request->get('size'));
    }*/

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
            return Expensa::listByUnidad($request->get('unidad_id'))->paginate($request->get('size'));
        } else {
            return Expensa::listByUnidad($request->get('unidad_id'))->get();
        }
    }

    public function paginate(Request $request)
    {
        return Expensa::paginate($request->get('size'));
    }

    public function store(Request $request)
    {
        $user = User::find(Auth::user()->getAuthIdentifier());

        $consorcioId = $user->isOperator() ? $user->administra_consorcio : $request->get('consorcio_id');
        $mes = $request->get('mes');
        $anio = $request->get('anio');

        if(!$consorcioId) return response("El parametro consorcio_id es obligatorio", 400);
        if(!$mes) return response("El parametro mes es obligatorio", 400);
        if(!$anio) return response("El parametro anio es obligatorio",400);

        if(!Liquidacion::existeParaMesAnioConsorcio($mes, $anio, $consorcioId)) return response("No se encontro una liquidacion de gastos para el periodo solicitado. Generela e intentelo nuevamente.", 400);
        if(Expensa::cantiadadDeExpensasEnElPeriodo($consorcioId, $mes, $anio) > 0) return response("Las expensas del periodo indicado fueron generadas anteriormente", 202);

        return Expensa::generarExpensasDelMes($anio, $mes, $consorcioId);
    }

    public function update(Request $request)
    {
        //Busco la expensa correspondiente
        $expensa = Expensa::find($request->get('id'));

        //Pregunto si encontro una expensa con ese id
        if ($expensa) {
            //Actualizo los atributos de la expensa encontrada
            $expensa->unidad_id = $request->get('unidad_id');
            $expensa->anio = $request->get('anio');
            $expensa->mes = $request->get('mes');
            $expensa->estado = $request->get('estado');
            $expensa->emision = $request->get('emision');
            $expensa->vencimiento = $request->get('vencimiento');
            $expensa->importe = $request->get('importe');

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

    public function generarExpensas(Request $request)
    {
        $mes = $request->get('mes');
        $anio = $request->get('anio');
        $consorcio_id = $request->get('consorcio_id');
        $unidad_id = $request->get('unidad_id');
        $expensaSinImporte = new Expensa();

        if (!$mes || !$anio) return response(['Mes o anio invalidos'], 400);
        if ($consorcio_id && $unidad_id) return response(['No se aceptan numero de consorcio y unidad en un mismo pedido'], 400);

        if ($unidad_id) {
            $expensaSinImporte->unidad_id = (int)$unidad_id;
            $expensaSinImporte->anio = $anio;
            $expensaSinImporte->mes = $mes;
            $expensaSinImporte->estado = 'impago';
            $expensaSinImporte->emision = $anio . '-' . $mes . '-10';
            $expensaSinImporte->vencimiento = $anio . '-' . $mes . '-20';

            if (sizeof(Expensa::obtenerExpensaPorUnidadMesAnio($unidad_id, $mes, $anio))) {
                return response(['Las expensas de esa unidad en ese periodo ya fueron calculadas'], 400);
            } else {
                Expensa::crearExpensaConImporte($expensaSinImporte);
            }


        } else {
            $consorcios = $consorcio_id ? array(Consorcio::find($consorcio_id)) : Consorcio::all();
            foreach ($consorcios as $consorcio) {
                $unidadesDelConsorcio = Unidad::obtenerIdUnidadesPorIdConsorcio($consorcio->id);
                foreach ($unidadesDelConsorcio as $unidad) {
                    $expensaSinImporte->unidad_id = $unidad->id;
                    $expensaSinImporte->anio = $anio;
                    $expensaSinImporte->mes = $mes;
                    $expensaSinImporte->estado = 'impago';
                    $expensaSinImporte->emision = $anio . '-' . $mes . '-10';
                    $expensaSinImporte->vencimiento = $anio . '-' . $mes . '-20';

                    if (!sizeof(Expensa::obtenerExpensaPorUnidadMesAnio($unidad->id, $mes, $anio))) Expensa::crearExpensaConImporte($expensaSinImporte);
                }
            }
        }

        return "Las expensas se han creado correctamente";
    }

    protected function obtenerExpensasPagas(Request $request)
    {
        return Expensa::obtenerExpensasPagas($request->get('size'));
    }

    protected function obtenerExpensasImpagas(Request $request)
    {
        return Expensa::obtenerExpensasImpagas($request->get('size'));
    }
}	
