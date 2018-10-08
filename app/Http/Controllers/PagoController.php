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
        $user = User::find(Auth::user()->getAuthIdentifier());
        $facturaId = $request->get('factura_id');
        $monto = $request->get('monto');
        $medioDePago = $request->get('medio_de_pago');
        $codigoComprobante = null;

        if(!$facturaId) return response("El campo factura_id es obligatorio", 400);
        if(!$monto) return response("El campo monto es obligatorio", 400);
        if(!$medioDePago) return response("El campo medio_de_pago es obligatorio", 400);
        if($user->isAdmin() == 0 && $user->isOperator() == 0){
            $codigo = $request->get('codigo_comprobante');
            if(!$codigo) return response("El codigo codigo_comprobante es obligatorio para usuarios que no son adminsitradores ni operadores",400);
            if(strlen($codigo) < 4) return response("El codigo_comprobante debe tener un minimo de 4 caracteres", 400);
            if(Pago::elCodigoYaFueRegistrado($codigo) > 0) return response("El codigo_comprobante entregado esta asociado a un pago existente", 400);

            $codigoComprobante = $codigo;
        }

        $factura = Factura::find($facturaId);
        if(!$factura) return response("No se encontro una factura con el id indicado", 404);

        $adeuda = $factura->adeuda;

        if($monto > $adeuda) return response("No se realizo el pago porque el monto indicado supera el monto adeudado (".$adeuda.")", 400);

        $fecha = Carbon::now();
        $pago = Pago::realizarPago($facturaId, $monto, $fecha->toDateString(), $user, $medioDePago, $codigoComprobante);
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

    public function filterByStatus(Request $request){
        $status = $request->get('status');
        if(!$status) return response("El parametro status es obligatorio", 400);
        if($status != 'APROBADO' && $status != 'APROBACION_PENDIENTE' && $status != 'RECHAZADO') return response("Los posibles valores del campo status son: APROBADO, APROBACION_PENDIENTE o RECHAZADO", 400);

        $size = $request->get('size') ? $request->get('size') : 5;
        return Pago::filterByStatus($status)->paginate($size);
    }
}
