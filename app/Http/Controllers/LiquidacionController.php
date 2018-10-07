<?php

namespace App\Http\Controllers;

use App\Liquidacion;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiquidacionController extends Controller
{
    public function index(Request $request){
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        $id = $request->get('id');
        if($id) return Liquidacion::find($id);

        $mes = $request->get('mes');
        $anio = $request->get('anio');
        if($mes && $anio) return Liquidacion::filterByMesAnio($mes, $anio)->paginate($size);

        if($user->isOperator()){
            return Liquidacion::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Liquidacion::list()->paginate($size);
        }
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
