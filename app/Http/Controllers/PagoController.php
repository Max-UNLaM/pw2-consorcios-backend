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
}
