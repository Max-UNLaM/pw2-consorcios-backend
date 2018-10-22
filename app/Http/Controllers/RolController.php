<?php

namespace App\Http\Controllers;

use App\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    public function index(Request $request){
        $id = $request->get('id');
        if($id) return Rol::find($id);

        $size = $request->get('size') ? $request->get('size') : 5;
        return Rol::list()->paginate($size);
    }

    public function store(Request $request){
        $nombre = $request->get('nombre');
        if(!$nombre) return response("El campo nombre es obligatorio", 400);

        $scope = $request->get('scope');
        if(!$scope) return response("El campo scope es obligatorio", 400);
        if($scope != 'admin' && $scope != 'operator' && $scope != 'user') return response("Los valores aceptados para el campo scope son admin, operator y user", 400);

        return Rol::create([
            'nombre' => $nombre,
            'scope' => $scope
        ]);
    }
}
