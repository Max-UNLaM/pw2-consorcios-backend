<?php

namespace App\Http\Controllers;

use App\Expensa;
use App\Factura;
use App\Pago;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        $id = $request->get('id');
        if($id) return Pago::find($id);

        $mes = $request->get('mes');
        $anio = $request->get('anio');
        if($mes && $anio) return Pago::filterByMesAnio($mes, $anio)->paginate($size);

        if($user->isOperator()){
            return Pago::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Pago::list()->paginate($size);
        }
    }

    public function user(Request $request)
    {
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        if ($request->get('puerta')) return "PATOVA";

        return Pago::filterByUsuario($user->id)->paginate($size);
    }

    public function paginate(Request $request){
        return Pago::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Pago::find($id);
    }
    
    public function delete(Request $request)
    {
        $resp = Pago::destroy($request->get('id'));

        if ($resp) {
            return 'ID ' . $request->get('id') . ' deleted OK';
        } else {
            return 'ID ' . $request->get('id') . ' not found';
        }
    }

    public function store(Request $request)
    {
        $facturaId = $request->get('factura_id');
        $monto = $request->get('monto');

        if(!$facturaId) return response("El campo factura_id es obligatorio", 400);
        if(!$monto) return response("El campo monto es obligatorio");

        $factura = Factura::find($facturaId);
        if(!$factura) return response("No se encontro una factura con el id indicado", 404);

        $adeuda = $factura->adeuda;

        if($monto > $adeuda) return response("No se realizo el pago porque el monto indicado supera el monto adeudado (".$adeuda.")", 400);

        $fecha = Carbon::now();
        $pago = Pago::realizarPago($facturaId, $monto, $fecha->toDateString());
        $factura = Factura::find($pago->factura_id);
        $expensa = Expensa::find($factura->expensa_id);

        return [
            'pago' => $pago,
            'factura' => $factura,
            'expensa' => $expensa
        ];
    }

    public function update(Request $request)
    {
        //Busco el gasto correspondiente
        $pago = Pago::find($request->get('id'));

        //Pregunto si encontro un gasto con ese id
        if ($pago) {
            //Actualizo los atributos del pago encontrado
            $pago->usuario_id = $request->get('usuario_id');
            $pago->factura_id = $request->get('factura_id');
            $pago->fecha = $request->get('fecha');
            $pago->monto = $request->get('monto');
           
            //Guardo los cambios
            $pago->save();

            return response([
                'pagoActualizado' => $pago
            ]);
        } else {
            //Si no lo encuentra respondo un codigo 404 (not found)
            return response(['No se encontro el pago que se quiere actualizar'], 404);
        }
    }
}
