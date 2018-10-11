<?php

namespace App\Http\Controllers;

use App\Liquidacion;
use App\Http\Resources\Liquidacion as LiquidacionResource;
use App\Http\Resources\LiquidacionCollection;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiquidacionController extends Controller
{
    public function index(Request $request){
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        $id = $request->get('id');
        if($id) return new LiquidacionResource(Liquidacion::find($id));


        $mes = $request->get('mes');
        $anio = $request->get('anio');
        if($mes && $anio){
            $liquidaciones = Liquidacion::where('mes', $mes)->where('anio', $anio)->paginate($size);
            return new LiquidacionCollection($liquidaciones);
        }

        if($user->isOperator()){
            $liquidaciones = Liquidacion::where('consorcio_id', $user->administra_consorcio)->paginate($size);
        } else {
            $liquidaciones = Liquidacion::paginate($size);
        }

        return new LiquidacionCollection($liquidaciones);
    }

    public function user(Request $request){
        $id = $request->get('id');
        if($id) return new LiquidacionResource(Liquidacion::find($id));

        $user = User::find(Auth::user()->getAuthIdentifier());
        $size = $request->get('size') ? $request->get('size') : 5;

        $consorcioId = User::getConsorcioIdForUser($user->id);

        $liquidaciones = Liquidacion::where('consorcio_id', $consorcioId)->paginate($size);

        return new LiquidacionCollection($liquidaciones);
    }

    public function store(Request $request){
        $user = User::find(Auth::user()->getAuthIdentifier());

        $consorcioId = $user->isOperator() ? $user->administra_consorcio : $request->get('consorcio_id');
        $mes = $request->get('mes');
        $anio = $request->get('anio');

        if(!$consorcioId) return response("El parametro consorcio_id es obligatorio", 400);
        if(!$mes) return response("El parametro mes es obligatorio", 400);
        if(!$anio) return response("El parametro anio es obligatorio",400);

        if(Liquidacion::existeParaMesAnioConsorcio($mes, $anio, $consorcioId)) return response("El periodo indicado fue liquidado previamente en el consorcio solicitado", 202);

        return Liquidacion::liquidarMesAnioConsorcio($mes, $anio, $consorcioId);
    }
}
