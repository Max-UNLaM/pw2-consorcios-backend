<?php

namespace App\Http\Controllers;

use App\Liquidacion;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiquidacionController extends Controller
{
    public function index(Request $request){
        $id = $request->get('id');
        if($id) return Liquidacion::find($id);

        $size = $request->get('size') ? $request->get('size') : 5;
        $user = User::find(Auth::user()->getAuthIdentifier());

        if($user->isOperator()){
            return Liquidacion::filterByConsorcio($user->administra_consorcio)->paginate($size);
        } else {
            return Liquidacion::list()->paginate($size);
        }
    }
}
