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
}
