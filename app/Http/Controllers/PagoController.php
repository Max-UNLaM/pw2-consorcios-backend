<?php

namespace App\Http\Controllers;

use App\Factura;
use App\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return Pago::find($request->get('id'));
        } else {
            return Pago::all();
        }
    }

    public function user(Request $request)
    {
        if ($request->get('puerta')) {
            return "PATOVA";
        } else if($request->get('factura_id')){
            return Pago::obtenerPagosPorUsuarioYFactura(Auth::user()->getAuthIdentifier(),$request->get('factura_id'));
        } else {
            return Pago::obtenerPagosPorUsuario(Auth::user()->getAuthIdentifier());
        }
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
        if (Pago::find($request->get('id')) != null) $this->delete($request);

        Pago::create($request->all());
        return response([
            'pago' => $request->all()
        ]);
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
