<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Factura;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Factura as FacturaResource;
use App\Http\Resources\FacturaCollection;

class FacturaController extends Controller
{

    public function index(Request $request)
    {
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        $id = $request->get('id');
        if($id) return new FacturaResource(Factura::find($id));

        $mes = $request->get('mes');
        $anio = $request->get('anio');
        if($mes && $anio){
            $facturas = Factura::where('mes', $mes)->where('anio', $anio)->orderByDesc('vencimiento')->paginate($size);
            return new FacturaCollection($facturas);
        }

        if($user->isOperator()){
            $facturas = Factura::where('consorcio_id', $user->administra_consorcio)->orderByDesc('vencimiento')->paginate($size);
        } else {
            $facturas = Factura::orderByDesc('vencimiento')->paginate($size);
        }

        return new FacturaCollection($facturas);
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) return "PATOVA";

        $id = $request->get('id');
        if($id) return new FacturaResource(Factura::find($id));

        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        $facturas = Factura::where('usuario_id', $user->id)->paginate($size);

        return new FacturaCollection($facturas);
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
