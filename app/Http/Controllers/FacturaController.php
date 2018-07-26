<?php

namespace App\Http\Controllers;

use App\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{

    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return Factura::obtenerDetalleDeFacturas(array(Factura::find($request->get('id'))));
        } else {
            return Factura::obtenerDetalleDeFacturas(Factura::all());
        }
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } else if($request->get('page') && !$request->get('consorcio_id')){
            return Factura::obtenerFacturasDetalladasPorUsuario(Auth::user()->getAuthIdentifier(), $request->get('size'));
        } else if($request->get('consorcio_id')){
            return Factura::obtenerFacturasDetalladasPorUsuarioYConsorcio(Auth::user()->getAuthIdentifier(), $request->get('consorcio_id'), $request->get('size'));
        }
    }

    public function paginate(Request $request){
        return Factura::paginate($request->get('size'));
    }

    public function facturarPeriodo(Request $request){
        $consorcioId = $request->get('consorcio_id');
        $mes = $request->get('mes');
        $anio = $request->get('anio');

        if(!$consorcioId) return response(['Parametro consorcio_id requerido'], 400);
        if(!$mes) return response(['Parametro mes requerido'], 400);
        if(!$anio) return response(['Parametro anio requerido'], 400);
        if(Factura::existenFacturasEnElPeriodo($consorcioId, $mes, $anio)) return response(['Este periodo ya fue facturado'], 400);

        $factura = Factura::facturarPeriodo($request->get('consorcio_id'), $request->get('mes'), $request->get('anio'));

        return $factura;
    }
}
