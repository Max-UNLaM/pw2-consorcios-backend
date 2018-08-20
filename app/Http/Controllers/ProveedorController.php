<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('page')) {
            return $this->paginate($request);
        } else if ($request->get('id')) {
            return Proveedor::find($request->get('id'));
        } else {
            return Proveedor::all();
        }
    }

    public function paginate(Request $request)
    {
        return Proveedor::paginate($request->get('size'));
    }

    public function store(Request $request)
    {
        if (Proveedor::find($request->get('id')) != null) $this->delete($request);

        Proveedor::create($request->all());
        return response([
            'proveedor' => $request->all()
        ]);
    }

    public function delete(Request $request)
    {
        $resp = Proveedor::destroy($request->get('id'));

        if ($resp) {
            return 'ID ' . $request->get('id') . ' deleted OK';
        } else {
            return 'ID ' . $request->get('id') . ' not found';
        }
    }

    public function update(Request $request)
    {
        //Busco el proveedor correspondiente
        $proveedor = Proveedor::find($request->get('id'));

        //Pregunto si encontro un rproveedor con ese id
        if ($proveedor) {
            //Actualizo los atributos del proveedor encontrado 
            $proveedor->nombre = $request->get('nombre');
            $proveedor->tel = $request->get('tel');
            $proveedor->email = $request->get('email');
            $proveedor->rubro = $request->get('rubro');
            
            //Guardo los cambios
            $proveedor->save();

            return response([
                'proveedorGuardado' => $proveedor
            ]);
        } else {
            //Si no lo encuentra respondo un codigo 404 (not found)
            return response(['No se encontro el proveedor que se quiere actualizar'], 404);
        }
    }
}
