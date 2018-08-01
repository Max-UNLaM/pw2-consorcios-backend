<?php

namespace App\Http\Controllers;

use App\Consorcio;
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
        } else if ($request->get('page') && !$request->get('consorcio_id')) {
            return Factura::obtenerFacturasDetalladasPorUsuario(Auth::user()->getAuthIdentifier(), $request->get('size'));
        } else if ($request->get('consorcio_id')) {
            return Factura::obtenerFacturasDetalladasPorUsuarioYConsorcio(Auth::user()->getAuthIdentifier(), $request->get('consorcio_id'), $request->get('size'));
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
            $consorcios = Consorcio::find($consorcioId);
        } else {
            $consorcios = Consorcio::all();
        }

        foreach ($consorcios as $consorcio) {
            if (Factura::cantidadDeFacturasEnElPeriodo($consorcio->id, $mes, $anio) == 0) {
                $facturaciones += Factura::facturarPeriodo($consorcio->id, $mes, $anio);
            }
        }
        return response('Creadas ' . $facturaciones . ' facturas', 201);
    }
}
