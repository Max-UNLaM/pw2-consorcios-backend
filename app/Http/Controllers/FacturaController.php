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
        } else if($request->get('consorcio_id')){
            return Factura::obtenerFacturasDetalladasPorUsuarioYConsorcio(Auth::user()->getAuthIdentifier(), $request->get('consorcio_id'));
        } else {
            return Factura::obtenerFacturasDetalladasPorUsuario(Auth::user()->getAuthIdentifier());
        }
    }

    public function paginate(Request $request){
        return Factura::paginate($request->get('size'));
    }
}
