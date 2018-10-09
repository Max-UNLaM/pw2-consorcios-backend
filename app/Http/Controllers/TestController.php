<?php

namespace App\Http\Controllers;

use App\Consorcio;
use App\Expensa;
use App\Factura;
use App\Gasto;
use App\Liquidacion;
use App\Pago;
use App\Unidad;
use App\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request){
        $users = User::all();
        $userIds = array();
        foreach ($users as $user){
            $userIds[] = $user->id;
        }
        shuffle($userIds);
        array_pop($userIds);
        return sizeof($userIds);
    }
}
