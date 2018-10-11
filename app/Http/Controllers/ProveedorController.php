<?php

namespace App\Http\Controllers;

use App\Proveedor;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->get('id');
        if($id) return Proveedor::find($id);

        $size = $request->get('size') ? $request->get('size') : 5;
        return Proveedor::paginate($size);
    }

    public function paginate(Request $request)
    {
        return Proveedor::paginate($request->get('size'));
    }

    public function user(Request $request){

        $id = $request->get('id');
        if($id) return Proveedor::find($id);

        $size = $request->get('size') ? $request->get('size') : 5;
        return Proveedor::paginate($size);
    }

    public function store(Request $request)
    {
        $nombre = $request->get('nombre');
        $tel = $request->get('tel');
        $email = $request->get('email');
        $rubro = $request->get('rubro');
        

        if(!$nombre) return response("El campo nombre es obligatorio", 400);
        if(!$tel) return response("El campo tel es obligatorio", 400);
        if(!$email) return response("El campo email es obligatorio", 400);
        if(!$rubro) return response("El rubro rubro es obligatorio", 400);
        

        $proveedor = Proveedor::create([
            'nombre' => $nombre,
            'tel' => $tel,
            'email' => $email,
            'rubro' => $rubro
        ]);

        return $proveedor;
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
