<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Factura;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{

    public function index(Request $request)
    {
        $id = $request->get('id');
        if($id) return Factura::find($id);

        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        if($user->isOperator()){
            return Factura::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Factura::list()->paginate($size);
        }
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } else if ($request->get('page') && !$request->get('consorcio_id')) {
            return Factura::obtenerFacturasDetalladasPorUsuario(Auth::user()->getAuthIdentifier(), $request->get('size'));
        } else if ($request->get('consorcio_id')) {
            return Factura::obtenerFacturasDetalladasPorUsuarioYConsorcio(Auth::user()->getAuthIdentifier(), $request->get('consorcio_id'), $request->get('size'));
        } else if ($request->get('id')) {
            return Factura::abrirFacturaDetallada((string)$request->get('id'))->get();
        } else {
            return Factura::obtenerFacturasDetalladasPorUsuario(Auth::user()->getAuthIdentifier(), $request->get('size'));
        }
    }

    public function paginate(Request $request)
    {
        return Factura::paginate($request->get('size'));
    }

    public function facturarPeriodo(Request $request)
    {
        $consorcioId = $request->get('consorcio_id');
        $mes = $request->get('mes');
        $anio = $request->get('anio');
        $facturaciones = 0;

        if (!$mes) return response(['Parametro mes requerido'], 400);
        if (!$anio) return response(['Parametro anio requerido'], 400);

        if ($consorcioId) {
            $consorcios = array(Consorcio::find($consorcioId));
        } else {
            $consorcios = Consorcio::all();
        }

        foreach ($consorcios as $consorcio) {
            # TODO mover a Expensas!
            #    if(Expensa::cantiadadDeExpensasEnElPeriodo($consorcio->id, $mes, $anio) == 0){
            #        Expensa::generarExpensasDelMes($anio, $mes, $consorcio->id);
            #    }

            if (Factura::cantidadDeFacturasEnElPeriodo($consorcio->id, $mes, $anio) == 0) {
                $facturaciones += Factura::facturarPeriodo($consorcio->id, $mes, $anio);
            }
        }
        return response('Creadas ' . $facturaciones . ' facturas', 201);
    }
}
