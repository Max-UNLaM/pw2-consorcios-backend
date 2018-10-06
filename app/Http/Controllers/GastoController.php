<?php

namespace App\Http\Controllers;

use App\Proveedor;
use App\User;
use Illuminate\Http\Request;
use App\Gasto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::user()->getAuthIdentifier());
        $size = $request->get('size') ? $request->get('size') : 5;

        if($user->isOperator()){
            return Gasto::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Gasto::list()->paginate($size);
        }
    }


    public function paginate(Request $request)
    {
        return Gasto::paginate($request->get('size'));
    }

    public function show($id)
    {
        return Gasto::find($id);
    }

    public function store(Request $request)
    {
        $user = User::find(Auth::user()->getAuthIdentifier());

        $proveedorId = $request->get('proveedor_id');
        $valor = $request->get('valor');
        $consorcioId = ($user->isOperator()) ? $user->administra_consorcio : $request->get('consorcio_id');
        $fecha = Carbon::now();

        if(!$consorcioId) return response("El campo consorcio_id es obligatorio", 400);
        if(!$proveedorId) return response("El campo proveedor_id es obligatorio", 400);
        if(!$valor) return response("El campo gasto es obligatorio", 400);

        $gasto = Gasto::create([
            'nombre' => Proveedor::find($proveedorId)->rubro,
            'valor' => $valor,
            'fecha' => $fecha->toDateString(),
            'proveedor_id' => $proveedorId,
            'consorcio_id' => $consorcioId,
            'mes' => $fecha->month,
            'anio' => $fecha->year
        ]);

        return response([
            'gasto' => $gasto
        ]);
    }

    public function delete(Request $request)
    {
        $resp = Gasto::destroy($request->get('id'));

        if ($resp) {
            return 'ID ' . $request->get('id') . ' deleted OK';
        } else {
            return 'ID ' . $request->get('id') . ' not found';
        }
    }

    public function gastosMensual(Request $request)
    {
        $gasto = new Gasto();
        $datos = $request->all();

        return response([
            'total' => $gasto->importeGastosMensual($datos["year"], $datos["mes"])
        ]);
    }

    public function update(Request $request)
    {
        //Busco el gasto correspondiente
        $gasto = Gasto::find($request->get('id'));

        //Pregunto si encontro un gasto con ese id
        if ($gasto) {
            //Actualizo los atributos del gasto encontrado
            $gasto->nombre = $request->get('nombre');
            $gasto->valor = $request->get('valor');
            $gasto->mes = $request->get('mes');
            $gasto->anio = $request->get('anio');
            $gasto->fecha = $request->get('fecha');
            $gasto->proveedor_id = $request->get('proveedor_id');
            $gasto->consorcio_id = $request->get('consorcio_id');
           
            //Guardo los cambios
            $gasto->save();

            return response([
                'gastoActualizado' => $gasto
            ]);
        } else {
            //Si no lo encuentra respondo un codigo 404 (not found)
            return response(['No se encontro el pago que se quiere actualizar'], 404);
        }
    }
}
