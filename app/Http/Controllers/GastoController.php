<?php

namespace App\Http\Controllers;

use App\Liquidacion;
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
        $id = $request->get('id');
        if($id) return Gasto::agregarInformacion(Gasto::find($id));

        $user = User::find(Auth::user()->getAuthIdentifier());
        $size = $request->get('size') ? $request->get('size') : 5;

        if($user->isOperator()){
            $response = Gasto::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            $response = Gasto::list()->paginate($size);
        }

        foreach ($response->items() as $item) Gasto::agregarInformacion($item);

        return $response;
    }

    public function user(Request $request){
        $id = $request->get('id');
        if($id) return Gasto::agregarInformacion(Gasto::find($id));

        $user = User::find(Auth::user()->getAuthIdentifier());
        $size = $request->get('size') ? $request->get('size') : 5;

        $consorcioId = User::getConsorcioIdForUser($user->id);

        $response = Gasto::filterByConsorcio($consorcioId)->paginate($size);

        foreach ($response->items() as $item) Gasto::agregarInformacion($item);

        return $response;
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
        $mes = $fecha->month;
        $anio = $fecha->year;

        if(!$consorcioId) return response("El campo consorcio_id es obligatorio", 400);
        if(!$proveedorId) return response("El campo proveedor_id es obligatorio", 400);
        if(!$valor) return response("El campo gasto es obligatorio", 400);
        if(Liquidacion::existeParaMesAnioConsorcio($mes, $anio, $consorcioId)) return response("No se puede cargar un gasto a un periodo que ya fue liquidado", 400);

        $gasto = Gasto::create([
            'nombre' => Proveedor::find($proveedorId)->rubro,
            'valor' => $valor,
            'fecha' => $fecha->toDateString(),
            'proveedor_id' => $proveedorId,
            'consorcio_id' => $consorcioId,
            'es_gasto_fijo' => 0,
            'mes' => $mes,
            'anio' => $anio
        ]);

        return $gasto;
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        if(!$id) return response("Campo id es obligatorio", 400);

        $gasto = Gasto::find($id);
        if(!$gasto) return response("No se encontro un gasto con el id especificado", 404);

        $user = User::find(Auth::user()->getAuthIdentifier());
        if($user->isOperator() && ($gasto->consorcio_id != $user->administra_consorcio)) return response("No tenes permisos para ejecutar acciones sobre este gasto porque corresponde a un consorcio que no administras", 401);

        Gasto::destroy($id);

        return response("Se elimino correctamente el gasto");
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
        if(!$gasto) return response("No se encontro un gasto con el id especificado", 404);

        $user = User::find(Auth::user()->getAuthIdentifier());
        if($user->isOperator() && ($gasto->consorcio_id != $user->administra_consorcio)) return response("No tenes permisos para ejecutar acciones sobre este gasto porque corresponde a un consorcio que no administras", 401);

        $gasto->update($request->all());

        return $gasto;
    }
}
