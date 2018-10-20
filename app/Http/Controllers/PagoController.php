<?php

namespace App\Http\Controllers;

use App\Expensa;
use App\Factura;
use App\Pago;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Pago as PagoResource;
use App\Http\Resources\PagoCollection;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());
        $id = $request->get('id');
        if ($id) return new PagoResource(Pago::find($id));
        $mes = $request->get('mes');
        $anio = $request->get('anio');
        if ($mes && $anio) {
            $pagos = Pago::where('mes', $mes)->where('anio', $anio)->paginate($size);
            return new PagoCollection($pagos);
        }
        if ($user->isOperator()) {
            $pagos = DB::table('pagos')
                ->join('facturas', 'facturas.id', '=', 'pagos.factura_id')
                ->where('facturas.consorcio_id', $user->administra_consorcio)
                ->orderByDesc('pagos.fecha')
                ->paginate($size);
        } else {
            $pagos = Pago::paginate($size);
        }

        return new PagoCollection($pagos);
    }

    public function user(Request $request)
    {
        $id = $request->get('id');
        if ($id) return new PagoResource(Pago::find($id));
        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());
        if ($request->get('puerta')) return "PATOVA";
        $pagos = DB::table('pagos')
            ->join('facturas', 'facturas.id', '=', 'pagos.factura_id')
            ->where('facturas.usuario_id', $user->id)
            ->orderByDesc('pagos.fecha')
            ->paginate($size);
        return new PagoCollection($pagos);
    }

    public function paginate(Request $request)
    {
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
        $banco = ($request->get('banco')) ? $request->get('banco') : null;
        if (!$facturaId) $errores['factura'] = 'El campo factura_id es obligatorio';
        if (!$monto) $errores['monto'] = "El campo monto es obligatorio";
        if (!$medioDePago) $errores['medioDePago'] = "El campo medio de pago es obligatorio";
        if (!empty($errores)) return response()->json($errores, 400);
        if ($user->isAdmin() == 0 && $user->isOperator() == 0) {
            $codigo = $request->get('codigo_comprobante');
            if(!$codigo) return response("El codigo codigo_comprobante es obligatorio para usuarios que no son adminsitradores ni operadores",400);
            if(strlen($codigo) < 4) return response("El codigo_comprobante debe tener un minimo de 4 caracteres", 400);

            $codigoComprobante = $codigo;
        }

        $factura = Factura::find($facturaId);
        if(!$factura) return response("No se encontro una factura con el id indicado", 404);

        $adeuda = $factura->adeuda;

        if($monto > $adeuda) return response("No se realizo el pago porque el monto indicado supera el monto adeudado (".$adeuda.")", 400);

        $fecha = Carbon::now();
        $pago = Pago::realizarPago($facturaId, $monto, $fecha->toDateString(), $user, $medioDePago, $codigoComprobante, $banco);
        $factura = Factura::find($pago->factura_id);
        $expensa = Expensa::find($factura->expensa_id);
        $mensaje = ($pago->estado == 'APROBADO') ? 'El pago ha sido ingresado y aprobado' : 'El pago ha sido ingresado y queda pendiente a la aprobación de un administrador';

        return [
            'pago' => $pago,
            'factura' => $factura,
            'expensa' => $expensa,
            'mensaje' => $mensaje
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

    public function filterByStatus(Request $request)
    {
        $status = $request->get('status');
        if (!$status) return response("El parametro status es obligatorio", 400);
        if ($status != 'APROBADO' && $status != 'APROBACION_PENDIENTE' && $status != 'RECHAZADO') return response("Los posibles valores del campo status son: APROBADO, APROBACION_PENDIENTE o RECHAZADO", 400);

        $size = $request->get('size') ? $request->get('size') : 5;
        $pagos = Pago::where('estado', $status)->orderByDesc('fecha')->paginate($size);

        return new PagoCollection($pagos);
    }

    public function approve(Request $request)
    {
        $id = $request->get('id');
        if (!$id) return response("El campo id es obligatorio", 400);

        $pago = Pago::find($id);
        if ($pago->estado != 'APROBACION_PENDIENTE') return response("La factura seleccionada no tiene el estado APROBACION_PENDIENTE", 400);
        $pago->aprobarPago();
        $pago->update();

        return $pago;
    }

    public function refuse(Request $request)
    {
        $id = $request->get('id');
        if (!$id) return response("El campo id es obligatorio", 400);

        $pago = Pago::find($id);
        if ($pago->estado != 'APROBACION_PENDIENTE') return response("La factura seleccionada no tiene el estado APROBACION_PENDIENTE", 400);
        $pago->estado = 'RECHAZADO';
        $pago->update();

        return $pago;
    }
}
