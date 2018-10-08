<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Factura;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{

    public function index(Request $request)
    {
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        $id = $request->get('id');
        if($id) return Factura::find($id);

        $mes = $request->get('mes');
        $anio = $request->get('anio');
        if($mes && $anio) return Factura::filterByMesAnio($mes, $anio)->paginate($size);

        if($user->isOperator()){
            return Factura::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Factura::list()->paginate($size);
        }
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) return "PATOVA";

        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        return Factura::filterByUsuario($user->id)->paginate($size);
    }

    public function paginate(Request $request)
    {
        return Factura::paginate($request->get('size'));
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

        if(Expensa::cantiadadDeExpensasEnElPeriodo($consorcioId, $mes, $anio) == 0) return response("No se encontraron expensas en el periodo indicado. Generelas e intentelo nuevamente", 400);
        if(Factura::cantidadDeFacturasEnElPeriodo($consorcioId, $mes, $anio) > 0) return response("Ya se generaron las facturas para el periodo indicado", 400);

        return Factura::facturarPeriodo($consorcioId, $mes, $anio);
    }
}
